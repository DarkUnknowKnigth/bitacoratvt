<?php

namespace App\Livewire\Dashboard;

use App\Models\Group;
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
    public $groups = [];
    public $tasksWithoutGroup = [];
    public $allTasks = [];
    public array $validation_ids = [];
    public array $validationValues = [];
    public array $comments = [];
    public $completedTasksCount = 0;
    public string $nowFormated = '';
    private string $nowTimeFormated = '';
    public $title = "Actividades diarias";
    public $latitude;
    public $longitude;

    // Método que se ejecuta al cargar el componente
    public function mount()
    {
        // Carga de tareas iniciales (datos estáticos para el ejemplo)
        $this->groups = Group::with([
            'tasks' => function ($query) {
                $query->where('main', true)->with(['subtasks', 'subtasks.validations']);
            }
        ])->orderBy('name')->get();

        $this->tasksWithoutGroup = Task::where('main', true)
            ->whereDoesntHave('group')
            ->with(['subtasks', 'subtasks.validations'])
            ->get();
        $this->allTasks = DB::table('subtasks')->count();
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
    public function reviewTask(Task $task, ?Task $subtask = null){
        $this->nowFormated = Carbon::now()->format('Y-m-d');
        $this->nowTimeFormated = Carbon::now()->format('H:i');

        $key = $subtask->id ? 'st-'.$subtask->id : 't-'.$task->id;

        $this->validate([
            "comments.{$key}" => 'nullable|string|max:255',
        ]);
        Review::create([
            'task_id' => $task->id,
            'subtask_id' => $subtask? $subtask->id : null,
            'user_id' => auth()->user()->id,
            'validation_id' => $this->validation_ids[$key] ?? null,
            'value'=> $this->validationValues[$key] ?? null,
            'comments' => $this->comments[$key] ?? null,
            'date' => $this->nowFormated,
            'time' => $this->nowTimeFormated,
            'location_id' => auth()->user()->location_id ?? 1,
        ]);
        $this->comments[$key] = '';
        $this->validation_ids[$key] = null;
        $this->validationValues[$key] = null;
        session()->flash('status', 'Tarea completada.');
    }
    public function render()
    {

        $this->completedTasksCount = Review::where('date', $this->nowFormated)->where('location_id',Auth::user()->location_id)->count();
        return view('livewire.dashboard.dashboard-component');
    }
    public function setLocation(float $latitude, float $longitude){
        $this->latitude = $latitude;
        $this->longitude = $longitude;
    }
}
