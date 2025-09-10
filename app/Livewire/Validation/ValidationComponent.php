<?php

namespace App\Livewire\Validation;

use App\Models\Task;
use App\Models\Validation;
use Livewire\Attributes\Validate;
use Livewire\Component;

class ValidationComponent extends Component
{
    #[Validate('required')]
    public $name = '';
    #[Validate('required')]
    public $value ='';
    public $task = null;
    public $validations = [];
    public $validation_id = null;
    public $tasks = [];
    public function render()
    {
        return view('livewire.validation.validation-component');
    }
    public function mount(){
        $this->validations = Validation::with(['tasks'])->orderBy('name')->get();
        $this->tasks = Task::orderBy('name')->get();
    }
    public function save(){
        $this->validate();
        $validation = Validation::create( $this->only(['name', 'value']));
        session()->flash('status', 'Validacion creada.');
        $validation->tasks()->attach($this->task);
        $this->validations =Validation::with(['tasks'])->orderBy('name')->get();
        return redirect()->route('validations');
    }
    public function destroy(Validation $validation){
        $validation->tasks()->sync([]);
        $validation->delete();
        $this->validations =Validation::with(['tasks'])->orderBy('name')->get();
        session()->flash('status', 'Validacion eliminada.');
        return redirect()->route('validations');
    }
    public function unsync(Validation $validation, Task $task){
        $validation->tasks()->detach($task->id);
        $this->validations =Validation::with(['tasks'])->orderBy('name')->get();
        session()->flash('status', 'Tarea desvinculada eliminada.');
        return redirect()->route('validations');
    }
    public function edit(Validation $validation){
        $this->name = $validation->name;
        $this->value = $validation->value;
        $this->validation_id = $validation->id;
        // $this->password = $validation->password;
        $this->task = $validation->task;
    }
    public function update(Validation $validation){
        $this->validate();
        $validation->update( $this->only(['name', 'value']));
        if ($this->task) {
            $task_ids = $validation->tasks->pluck('id')->toArray();
            $validation->tasks()->sync(array_merge($task_ids, [$this->task]));
        }
        $this->validations =Validation::with(['tasks'])->orderBy('name')->get();
        session()->flash('status', 'Validacion actualizada.');
        return redirect()->route('validations');
    }
}
