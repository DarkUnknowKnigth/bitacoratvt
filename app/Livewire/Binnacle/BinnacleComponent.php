<?php

namespace App\Livewire\Binnacle;

use App\Models\Binnacle;
use App\Models\Location;
use App\Models\Role;
use Livewire\Component;
use Livewire\WithPagination;

class BinnacleComponent extends Component
{
    use WithPagination;

    // Propiedades del formulario
    public $binnacle_id;
    public $name;
    public $type;
    public $location_id;
    public $role_id;

    // Propiedades de la vista
    public $search = '';
    public $locations = [];
    public $roles = [];

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:location,role',
            'location_id' => 'nullable|exists:locations,id',
            'role_id' => 'nullable|exists:roles,id',
        ];
    }

    public function render()
    {
        $binnacles = Binnacle::with(['location', 'role'])
            ->where('name', 'like', '%' . $this->search . '%')
            ->orderBy('name')
            ->paginate(10);

        return view('livewire.binnacle.binnacle-component', compact('binnacles'));
    }

    public function mount()
    {
        $this->locations = Location::orderBy('name')->get();
        $this->roles = Role::orderBy('name')->get();
    }

    public function save()
    {
        $this->validate();
        if($this->role_id && $this->location_id){
            session()->flash('status', 'Seleccione solo una opcion de bitácora, debe de decidir si quiere que la modalidad de rol o de ubicacion no ambass.');
            return redirect()->route('binnacles');
        }

        Binnacle::create($this->only(['name', 'type', 'location_id', 'role_id']));

        session()->flash('status', 'Bitácora creada exitosamente.');
        $this->resetFields();
        return redirect()->route('binnacles');
    }

    public function edit(Binnacle $binnacle)
    {
        $this->binnacle_id = $binnacle->id;
        $this->name = $binnacle->name;
        $this->type = $binnacle->type;
        $this->location_id = $binnacle->location_id;
        $this->role_id = $binnacle->role_id;
    }

    public function update(Binnacle $binnacle)
    {
        $this->validate();
        $binnacle->update($this->only(['name', 'type', 'location_id', 'role_id']));
        session()->flash('status', 'Bitácora actualizada exitosamente.');
        $this->resetFields();
        return redirect()->route('binnacles');
    }

    public function destroy(Binnacle $binnacle)
    {
        $binnacle->delete();
        session()->flash('status', 'Bitácora eliminada.');
        return redirect()->route('binnacles');
    }

    public function resetFields()
    {
        $this->reset(['binnacle_id', 'name', 'type', 'location_id', 'role_id']);
        $this->resetErrorBag();
    }
}
