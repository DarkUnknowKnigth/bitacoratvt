<?php

namespace App\Livewire\Tasks;

use App\Models\Group;
use App\Models\Task;
use Livewire\Attributes\Validate;
use Livewire\Component;

class GroupComponent extends Component
{
    #[Validate('required')]
    public $name;
    public $group_id;
    public $groups = [];
    public $tasks = [];
    public $selectedTasks = [];

    public function render()
    {
        return view('livewire.tasks.group-component');
    }

    public function mount()
    {
        $this->groups = Group::with('tasks')->orderBy('name')->get();
        $this->tasks = Task::where('main',true)->orderBy('name')->get();
    }

    public function save()
    {
        $this->validate();
        $group = Group::create($this->only(['name']));
        $group->tasks()->sync($this->selectedTasks);
        session()->flash('status', 'Grupo creado.');
        $this->resetFields();
        return redirect()->route('groups');
    }

    public function destroy(Group $group)
    {
        $group->delete();
        session()->flash('status', 'Grupo eliminado.');
        return redirect()->route('groups');
    }

    public function edit(Group $group)
    {
        $this->name = $group->name;
        $this->group_id = $group->id;
        $this->selectedTasks = $group->tasks->pluck('id')->toArray();
    }

    public function update(Group $group)
    {
        $this->validate();
        $group->update($this->only(['name']));
        $group->tasks()->sync($this->selectedTasks);
        session()->flash('status', 'Grupo actualizado.');
        $this->resetFields();
        return redirect()->route('groups');
    }

    public function resetFields()
    {
        $this->reset(['name', 'group_id', 'selectedTasks']);
    }
}
