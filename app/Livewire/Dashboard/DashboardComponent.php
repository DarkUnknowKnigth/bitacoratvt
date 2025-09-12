<?php

namespace App\Livewire\Dashboard;

use App\Models\Review;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Rule;
use Livewire\Component;

class DashboardComponent extends Component
{
    // Propiedades del componente
    public $tasks = [];
    public $allTasks = [];
    public $validation_id = null;
    public $validationValue = 0;
    public string $comments = '';
    public $completedTasksCount = 0;
    private string $nowFormated = '';
    private string $nowTimeFormated = '';
    public $title = "Actividades diarias";

    // Método que se ejecuta al cargar el componente
    public function mount()
    {
        // Carga de tareas iniciales (datos estáticos para el ejemplo)
        $this->tasks = Task::with(['subtasks','subtasks.validations'])->where('main', true)->get();
        $this->allTasks = DB::table('subtasks')->count()+DB::table('tasks')->where('main',true)->count();
        $this->nowFormated = Carbon::now()->format('Y-m-d');
        $this->nowTimeFormated = Carbon::now()->format('H:i');
    }
    // Método para alternar el estado de una tarea
    public function toggleTaskStatus(int $taskId)
    {
        $this->tasks = collect($this->tasks)->map(function ($task) use ($taskId) {
            if ($task['id'] === $taskId) {
                $task['completed'] = !$task['completed'];
            }
            return $task;
        })->toArray();
    }

    // Método para crear una nueva tarea
    public function createTask()
    {
        $this->validate([
            'newTaskName' => 'required|min:3',
        ]);

        $newId = count($this->tasks) > 0 ? max(array_column($this->tasks, 'id')) + 1 : 1;

        $this->tasks[] = [
            'id' => $newId,
            'name' => $this->newTaskName,
            'completed' => false,
        ];

        $this->reset('newTaskName', 'showForm');
    }
    public function reviewTask(Task $task, ?Task $subtask){
        $this->nowFormated = Carbon::now()->format('Y-m-d');
        $this->nowTimeFormated = Carbon::now()->format('H:i');
        $this->validate([
            'comments' => 'nullable|string|max:255',
        ]);
        Review::create([
            'task_id' => $task->id,
            'subtask_id' => $subtask? $subtask->id : null,
            'user_id' => auth()->user()->id,
            'validation_id' => $this->validation_id ?? null,
            'value'=> $this->validationValue ?? null,
            'comments' => $this->comments ?? null,
            'date' => $this->nowFormated,
            'time' => $this->nowTimeFormated,
            'location_id' => auth()->user()->location_id ?? 1,
        ]);
        $this->comments= '';
        $this->validation_id = null;
        $this->validationValue = 0;
        session()->flash('status', 'Tarea completada.');
    }
    public function render()
    {

        $this->completedTasksCount = Review::where('date', $this->nowFormated)->where('location_id',Auth::user()->location_id)->count();
        return view('livewire.dashboard.dashboard-component');
    }
}
