<?php

namespace App\Livewire\Tasks;

use App\Models\Task;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithPagination;

class TaskComponent extends Component
{
    use WithPagination;
    public $tasks = [];
    public $mainTasks = [];
    public $task_id;
    #[Validate('required')]
    public $name;
    #[Validate('numeric')]
    public $main = 1;
    #[Validate('nullable')]
    public $parent;
    public function render()
    {
        return view('livewire.tasks.task-component');
    }
    public function mount(){
        $this->tasks = Task::with(['subtasks','subtasks.validations'])->where('main', true)->get();
        $this->mainTasks = Task::with(['subtasks','subtasks.validations'])->where('main', true)->get();
    }
    public function save(){
        $this->validate();
        $task = Task::create( $this->only(['name', 'main']));
        if ($this->parent && $this->main==0) {
            $parentTask = Task::find($this->parent);
            $parentTask->subtasks()->attach($task->id);
        }
        session()->flash('status', 'Tarea creada.');
        $this->tasks = Task::with(['subtasks','subtasks.validations'])->where('main', true)->get();
        return redirect()->route('tasks');
    }
    public function destroy(Task $task){
        $task->delete();
        $this->tasks = Task::with(['subtasks','subtasks.validations'])->where('main', true)->get();
        session()->flash('status', 'Tarea eliminada.');
        return redirect()->route('tasks');
    }
    public function edit(Task $task){
        $this->name = $task->name;
        $this->main = $task->main;
        // $this->password = $task->password;
        $this->task_id = $task->id;
    }
    public function update(Task $task){
        $this->validate();
        $task->update( $this->only(['name', 'main']));
        $this->tasks = Task::with(['subtasks','subtasks.validations'])->where('main', true)->get();
        session()->flash('status', 'Tarea actualizada.');
        return redirect()->route('tasks');
    }
}
