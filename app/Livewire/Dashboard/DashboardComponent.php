<?php

namespace App\Livewire\Dashboard;

use App\Models\Review;
use App\Models\Task;
use Carbon\Carbon;
use Livewire\Attributes\Rule;
use Livewire\Component;

class DashboardComponent extends Component
{
    // Propiedades del componente
    public $tasks = [];
    public $allTasks = [];
    public $validation_id = 0;
    public string $comments;
    public $completedTasksCount = 0;
    public $pendingTasksCount = 0;
    public string $nowFormated = '';
    public string $nowTimeFormated = '';
    public $title = "Actividades diarias";

    // Método que se ejecuta al cargar el componente
    public function mount()
    {
        // Carga de tareas iniciales (datos estáticos para el ejemplo)
        $this->tasks = Task::with(['subtasks','subtasks.validations'])->where('main', true)->get();
        $this->allTasks = Task::with(['subtasks','subtasks.validations'])->count();
        $this->nowFormated = Carbon::now()->format('Y-m-d');
        $this->nowTimeFormated = Carbon::now()->format('H:i');
    }

    // Propiedades computadas para obtener el conteo de tareas
    public function getCompletedTasksCountProperty()
    {
        return Review::where('date', $this->nowFormated)->count();
    }

    public function getPendingTasksCountProperty()
    {
        return Task::whereNotIn('task_id', Review::where('date', $this->nowFormated)->select('task_id')->get()->pluck('task_id')->toArray())->count();
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
        $this->validate([
            'comments' => 'nullable|string|max:255',
        ]);
        Review::create([
            'task_id' => $task->id,
            'subtask_id' => $subtask? $subtask->id : null,
            'user_id' => auth()->user()->id,
            'validation_id' => $this->validation_id ?? null,
            'comments' => $this->comments,
            'date' => $this->nowFormated,
            'time' => $this->nowTimeFormated,
            'location_id' => auth()->user()->location_id ?? 1,
        ]);
    }
    public function render()
    {
        return view('livewire.dashboard.dashboard-component');
    }
}
