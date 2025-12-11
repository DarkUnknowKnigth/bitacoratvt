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

        // Validar que solo uno de los dos (rol o ubicación) esté seleccionado
        if ($this->type === 'location' && !$this->location_id) {
            $this->addError('location_id', 'Debe seleccionar una ubicación para este tipo de bitácora.');
            return;
        }
        if ($this->type === 'role' && !$this->role_id) {
            $this->addError('role_id', 'Debe seleccionar un rol para este tipo de bitácora.');
            return;
        }

        // Doble verificación para evitar que se guarden ambos por error
        if ($this->role_id && $this->location_id) {
            session()->flash('status', 'Error: No se pueden asignar una ubicación y un rol a la misma bitácora. Por favor, elija solo uno.');
            return redirect()->route('binnacles');
        }

        Binnacle::create($this->only(['name', 'type']) + [
            'location_id' => $this->type === 'location' ? $this->location_id : null,
            'role_id' => $this->type === 'role' ? $this->role_id : null,
        ]);

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

        // Asegurar que solo se guarde el ID correspondiente al tipo
        $data = $this->only(['name', 'type']);
        $data['location_id'] = $this->type === 'location' ? $this->location_id : null;
        $data['role_id'] = $this->type === 'role' ? $this->role_id : null;

        $binnacle->update($data);

        session()->flash('status', 'Bitácora actualizada exitosamente.');
        $this->resetFields();
        return redirect()->route('binnacles');
    }

    public function destroy(Binnacle $binnacle)
    {
        foreach ($binnacle->tasks as $task) {
            foreach ($task->subtasks as $subtask) {
                $subtask->reviews()->delete();
                $subtask->delete();
            }
            $task->reviews()->delete();
            $task->delete();
        }
        $binnacle->delete();
        session()->flash('status', 'Bitácora eliminada.');
        return redirect()->route('binnacles');
    }

    public function resetFields()
    {
        $this->reset(['binnacle_id', 'name', 'type', 'location_id', 'role_id']);
        $this->resetErrorBag();
    }

    public function updatedType($value)
    {
        // Limpiar los campos de ID cuando cambia el tipo para evitar confusiones
        $this->location_id = null;
        $this->role_id = null;
    }

    public function updatedLocationId($value)
    {
        if ($value) $this->role_id = null;
    }

    public function updatedRoleId($value)
    {
        if ($value) $this->location_id = null;
    }
}
