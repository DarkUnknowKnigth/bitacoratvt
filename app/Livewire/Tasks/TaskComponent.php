<?php

namespace App\Livewire\Tasks;

use App\Models\Task;
use Livewire\Component;

class TaskComponent extends Component
{
    public $tasks = [];
    public $mainTasks = [];
    public function render()
    {
        return view('livewire.tasks.task-component');
    }
    public function mount(){
        $this->tasks = Task::with(['subtasks','subtasks.validations'])->where('main', true)->get();
        $this->mainTasks = Task::with(['subtasks','subtasks.validations'])->where('main', true)->get();
    }
}
