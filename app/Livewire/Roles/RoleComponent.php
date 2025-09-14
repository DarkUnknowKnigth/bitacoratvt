<?php

namespace App\Livewire\Roles;

use App\Models\Module;
use App\Models\Role;
use Livewire\Attributes\Validate;
use Livewire\Component;

class RoleComponent extends Component
{
    #[Validate('required')]
    public $name;
    #[Validate('required')]
    public $slug;
    public $role_id;
    public $roles = [];
    public $allModules = [];
    public $selectedModules = [];
    public $editingModulesForRoleId = null;

    public function render()
    {
        return view('livewire.roles.role-component');
    }

    public function mount()
    {
        $this->roles = Role::orderBy('name')->get();
        $this->allModules = Module::orderBy('name')->get();
    }

    public function save()
    {
        $this->validate();
        Role::create($this->only(['name', 'slug']));
        session()->flash('status', 'Rol creado.');
        $this->resetFields();
        return redirect()->route('roles');
    }

    public function destroy(Role $role)
    {
        $role->modules()->detach();
        $role->delete();
        session()->flash('status', 'Rol eliminado.');
        return redirect()->route('roles');
    }

    public function edit(Role $role)
    {
        $this->name = $role->name;
        $this->slug = $role->slug;
        $this->role_id = $role->id;
        $this->editingModulesForRoleId = null; // Ocultar el formulario de módulos al editar el rol
    }

    public function update(Role $role)
    {
        $this->validate();
        $role->update($this->only(['name', 'slug']));
        session()->flash('status', 'Rol actualizado.');
        $this->resetFields();
        return redirect()->route('roles');
    }

    public function resetFields()
    {
        $this->reset(['name', 'slug', 'role_id', 'editingModulesForRoleId', 'selectedModules']);
    }

    public function toggleModuleAssignment($roleId)
    {
        if ($this->editingModulesForRoleId === $roleId) {
            $this->editingModulesForRoleId = null;
            $this->selectedModules = [];
        } else {
            $this->editingModulesForRoleId = $roleId;
            $role = Role::find($roleId);
            $this->selectedModules = $role->modules->pluck('id')->toArray();
        }
    }

    public function assignModules(Role $role)
    {
        $role->modules()->sync($this->selectedModules);
        session()->flash('status', 'Módulos asignados correctamente.');
        $this->resetFields();
    }
}
