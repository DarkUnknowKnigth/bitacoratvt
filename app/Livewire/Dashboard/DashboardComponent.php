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
    public bool $showForm = false;
    public string $newTaskName = '';
    public $completedTasksCount = 0;
    public $pendingTasksCount = 0;
    public string $nowFormated = '';

    // Método que se ejecuta al cargar el componente
    public function mount()
    {
        // Carga de tareas iniciales (datos estáticos para el ejemplo)
        $this->tasks = Task::with(['subtasks','subtasks.validations'])->where('main', true)->get();
        $this->allTasks = Task::with(['subtasks','subtasks.validations'])->count();
        $this->nowFormated = Carbon::now()->format('Y-m-d');
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

    public function render()
    {
        return view('livewire.dashboard.dashboard-component');
    }
}
