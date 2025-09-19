<?php

namespace App\Livewire\Tasks;

use App\Models\Failure;
use App\Models\Location;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;
use Livewire\Component;

class FailureComponent extends Component
{
    public $failures = [];
    public $tasks = [];
    public $subtasks = [];
    public $locations = [];
    public $failure_id = null;

    // Propiedades del formulario
    #[Validate('required|exists:tasks,id')]
    public $task_id = '';

    #[Validate('nullable|exists:tasks,id')]
    public $subtask_id = '';

    #[Validate('required|exists:locations,id')]
    public $location_id = '';

    #[Validate('required|string|min:5')]
    public $description = '';

    #[Validate('required|date')]
    public $date = '';

    #[Validate('boolean')]
    public $solved = false;

    public function mount()
    {
        $this->tasks = Task::where('main', true)->orderBy('name')->get();
        $this->locations = Location::orderBy('name')->get();
        $this->date = Carbon::now()->format('Y-m-d');
        $this->location_id = Auth::user()->location_id;
        $this->loadFailures();
    }

    public function updatedTaskId($value)
    {
        if ($value) {
            $this->subtasks = Task::find($value)->subtasks()->orderBy('name')->get();
        } else {
            $this->subtasks = [];
        }
        $this->subtask_id = '';
    }

    public function loadFailures()
    {
        $this->failures = Failure::with(['task', 'subtask', 'user', 'location'])
            ->orderBy('date', 'desc')
            ->get();
    }

    public function save()
    {
        $this->validate();

        Failure::create([
            'task_id' => $this->task_id,
            'subtask_id' => $this->subtask_id ?: null,
            'location_id' => $this->location_id,
            'user_id' => Auth::id(),
            'description' => $this->description,
            'date' => $this->date,
            'solved' => $this->solved,
        ]);

        session()->flash('status', 'Falla registrada correctamente.');
        return redirect()->route('failures');
    }

    public function edit(Failure $failure)
    {
        $this->failure_id = $failure->id;
        $this->task_id = $failure->task_id;
        $this->updatedTaskId($failure->task_id); // Cargar subtareas
        $this->subtask_id = $failure->subtask_id;
        $this->location_id = $failure->location_id;
        $this->description = $failure->description;
        $this->date = $failure->date;
        $this->solved = $failure->solved;
    }

    public function update()
    {
        $this->validate();
        $failure = Failure::find($this->failure_id);
        $failure->update($this->only(['task_id', 'subtask_id', 'location_id', 'description', 'date', 'solved']));

        session()->flash('status', 'Falla actualizada correctamente.');
        return redirect()->route('failures');
    }

    public function destroy(Failure $failure)
    {
        $failure->delete();
        session()->flash('status', 'Falla eliminada correctamente.');
        $this->loadFailures();
    }

    public function toggleSolved(Failure $failure)
    {
        $failure->update(['solved' => !$failure->solved]);
        $this->loadFailures();
    }

    public function render()
    {
        return view('livewire.tasks.failure-component');
    }
}
