<?php

namespace App\Livewire\Tasks;

use App\Models\Task;
use App\Models\Validation;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithPagination;

class TaskComponent extends Component
{
    use WithPagination;
    public $tasks = [];
    public $validations = [];
    public $mainTasks = [];
    public $task_id;
    #[Validate('required')]
    public $name;
    public $validation;
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
        $this->validations = Validation::all();
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
        $task->validations()->detach($task->validations->pluck('id')->toArray());
        foreach ($task->subtasks as $subtask) {
            $subtask->validations()->detach($subtask->validations->pluck('id')->toArray());
            foreach($subtask->subReviews()->get() as $review){
                $review->delete();
            }
            $subtask->delete();
        }
        $task->subtasks()->detach($task->subtasks->pluck('id')->toArray());
        foreach ($task->reviews()->get() as $review) {
            $review->delete();
        }
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
    public function addValidation($task_id){
        $task = Task::find($task_id);
        if ($task && $this->validation) {
            $task->validations()->attach($this->validation);
            $this->tasks = Task::with(['subtasks','subtasks.validations'])->where('main', true)->get();
            session()->flash('status', 'Validación agregada a la tarea.');
            return redirect()->route('tasks');
        }
        session()->flash('error', 'No se pudo agregar la validación.');
        return redirect()->route('tasks');
    }
    public function unvalidate(Task $task, Validation $validation){
        $task->validations()->detach($validation->id);
    }
}
