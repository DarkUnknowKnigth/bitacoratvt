<?php

namespace App\Livewire\Validation;

use App\Models\Task;
use App\Models\Validation;
use Livewire\Component;

class ValidationComponent extends Component
{
    public $validations = [];
    public $tasks = [];
    public function render()
    {
        return view('livewire.validation.validation-component');
    }
    public function mount(){
        $this->validations = Validation::with(['tasks'])->orderBy('name')->get();
        $this->tasks = Task::orderBy('name')->get();
    }
}
