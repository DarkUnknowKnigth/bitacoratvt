<?php

namespace App\Livewire\Modules;

use App\Models\Module;
use App\Models\Role;
use Livewire\Attributes\Validate;
use Livewire\Component;

class ModuleComponent extends Component
{
    #[Validate('required')]
    public $name;
    #[Validate('required')]
    public $url;
    public $description;
    public $module_id;
    public $modules = [];
    public $roles = [];

    public function render()
    {
        return view('livewire.modules.module-component');
    }

    public function mount()
    {
        $this->modules = Module::orderBy('name')->get();
        $this->roles = Role::orderBy('name')->get();
    }

    public function save()
    {
        $this->validate();
        Module::create($this->only(['name', 'url', 'description']));
        session()->flash('status', 'Módulo creado.');
        $this->resetFields();
        return redirect()->route('modules');
    }

    public function destroy(Module $module)
    {
        $module->delete();
        session()->flash('status', 'Módulo eliminado.');
        return redirect()->route('modules');
    }

    public function edit(Module $module)
    {
        $this->name = $module->name;
        $this->url = $module->url;
        $this->description = $module->description;
        $this->module_id = $module->id;
    }

    public function update(Module $module)
    {
        $this->validate();
        $module->update($this->only(['name', 'url', 'description']));
        session()->flash('status', 'Módulo actualizado.');
        $this->resetFields();
        return redirect()->route('modules');
    }

    public function resetFields()
    {
        $this->reset(['name', 'url', 'description', 'module_id']);
    }
}
