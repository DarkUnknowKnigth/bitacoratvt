<?php

namespace App\Livewire\Tasks;

use App\Models\Location;
use App\Models\Task;
use App\Models\Validation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithPagination;

class TaskComponent extends Component
{
    use WithPagination;
    public $tasks = [];
    public $validations = [];
    public $mainTasks = [];
    public $locations = [];
    public $task_id;
    #[Validate('required')]
    public $name;
    public $validation;
    #[Validate('numeric')]
    public $main = 1;
    #[Validate('nullable')]
    public $parent;
    public $search = '';
    public $location_ids = [];
    private function loadTasks()
    {
        $this->tasks = Task::with(['subtasks', 'subtasks.validations','locations'])
            ->where('main', true)
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->orderBy('name')
            ->get();
    }

    public function render()
    {
        return view('livewire.tasks.task-component');
    }
    public function mount()
    {
        $this->loadTasks();
        $this->validations = Validation::all();
        $this->locations = Location::all();
    }

    public function updatedSearch()
    {
        $this->loadTasks();
    }

    public function save()
    {
        $this->validate();
        $task = Task::create($this->only(['name', 'main']));
        if ($this->parent && $this->main == 0) {
            $parentTask = Task::find($this->parent);
            $parentTask->subtasks()->attach($task->id);
        }
        if ($this->location_ids && count($this->location_ids) > 0) {
            $task->locations()->sync($this->location_ids);
        }
        $this->reset('name', 'main', 'parent', 'location_ids', 'task_id');
        session()->flash('status', 'Tarea creada.');
        $this->loadTasks();
        return redirect()->route('tasks');
    }
    public function destroy(Task $task)
    {
        $task->validations()->detach();
        foreach ($task->subtasks as $subtask) {
            $subtask->validations()->detach();
            foreach ($subtask->subReviews($task->id)->get() as $review) {
                $review->delete();
            }
            $subtask->delete();
        }
        $task->subtasks()->detach();
        foreach ($task->reviews()->get() as $review) {
            $review->delete();
        }
        $task->locations()->detach();
        $task->delete();
        $this->loadTasks();
        session()->flash('status', 'Tarea eliminada.');
        return redirect()->route('tasks');
    }
    public function edit(Task $task)
    {
        $this->name = $task->name;
        $this->main = $task->main;
        $this->location_ids = $task->locations->pluck('id')->toArray();
        $this->task_id = $task->id;
    }
    public function update(Task $task)
    {
        $this->validate();
        $task->update($this->only(['name', 'main']));
        $this->loadTasks();
        if ($this->location_ids && count($this->location_ids) > 0) {
            $task->locations()->sync($this->location_ids);
        }
        session()->flash('status', 'Tarea actualizada.');
        $this->reset('name', 'main', 'parent', 'location_ids', 'task_id');
        return redirect()->route('tasks');
    }
    public function addValidation($task_id)
    {
        $task = Task::find($task_id);
        if ($task && $this->validation) {
            $task->validations()->attach($this->validation);
            $this->loadTasks();
            session()->flash('status', 'Validación agregada a la tarea.');
            return redirect()->route('tasks');
        }
        session()->flash('error', 'No se pudo agregar la validación.');
        return redirect()->route('tasks');
    }
    public function unvalidate(Task $task, Validation $validation)
    {
        $task->validations()->detach($validation->id);
    }
    public function deleteErrors()
    {
        // 1. Obtener los IDs de todas las subtareas que SÍ están correctamente asociadas a un padre.
        // Usamos pluck() para obtener solo la columna 'subtask_id'.
        $valid_subtask_ids = DB::table('subtasks')->pluck('subtask_id');

        // 2. Buscar tareas que son subtareas (main=false) pero su ID NO está en la lista de IDs válidos.
        // Estas son las subtareas huérfanas.
        $orphan_subtasks = Task::where('main', false)
            ->whereNotIn('id', $valid_subtask_ids)
            ->get();
        $deleted_count = $orphan_subtasks->count();

        foreach ($orphan_subtasks as $task) {
            // Eliminar relaciones y la tarea misma
            $task->validations()->detach();
            $task->reviews()->delete();
            $task->delete();
        }
        //parent ghost buster
        $ghost_tasks = DB::table('subtasks')->whereNotIn('tasK_id', Task::where('main',true)->pluck('id'));
        $ghost_counter = $ghost_tasks->count();
        $ghost_tasks->delete();
        //child ghost buster
        $ghost_parent_tasks = DB::table('subtasks')->whereNotIn('subtasK_id', Task::where('main',false)->pluck('id'));
        $ghost_counter += $ghost_parent_tasks->count();
        $ghost_parent_tasks->delete();



        session()->flash('status',"$deleted_count subtarea(s) huérfana(s) han sido eliminada(s) y $ghost_counter fanstasmas(s).");
        // Recargar las tareas para reflejar los cambios en la vista
        $this->loadTasks();
        return redirect()->route('tasks');
    }
}
