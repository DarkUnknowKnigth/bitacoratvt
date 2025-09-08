<?php

namespace App\Livewire\Dashboard;

use App\Models\Task;
use Livewire\Attributes\Rule;
use Livewire\Component;

class DashboardComponent extends Component
{
    // Propiedades del componente
    public $tasks = [];
    public bool $showForm = false;
    public string $newTaskName = '';
    public $completedTasksCount = 0;

    // Método que se ejecuta al cargar el componente
    public function mount()
    {
        // Carga de tareas iniciales (datos estáticos para el ejemplo)
        $this->tasks = Task::with(['subtasks'])->where('main', true)->get();
    }

    // Propiedades computadas para obtener el conteo de tareas
    public function getCompletedTasksCountProperty()
    {
        return collect($this->tasks)->filter(fn ($task) => $task['completed'])->count();
    }

    public function getPendingTasksCountProperty()
    {
        return collect($this->tasks)->filter(fn ($task) => !$task['completed'])->count();
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
