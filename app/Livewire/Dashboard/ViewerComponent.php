<?php

namespace App\Livewire\Dashboard;

use App\Models\Group;
use App\Models\Location;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ViewerComponent extends Component
{
    public $selectedLocation;
    public $selectedUser;
    public $selectedDate;
    public $prevDate;

    public function mount()
    {
        $this->selectedDate = Carbon::now()->format('Y-m-d');
        $this->prevDate = Carbon::now()->subDay()->format('Y-m-d');
    }

    public function render()
    {
        $locations = Location::when(Auth::user()->role->slug != 'admin', function($query){
                $query->where('id', Auth::user()->location_id);
            })
            ->get();
        $users = User::query()
            ->when($this->selectedLocation, function ($query) {
                $query->where('location_id', $this->selectedLocation);
            })
            ->when(Auth::user()->role->slug != 'admin', function($query){
                $query->where('location_id', Auth::user()->location_id);
            })
            ->get();

        // Lógica para obtener los grupos con sus tareas y las tareas sin grupo
        $commonTaskQuery = function ($query) {
            $query->where('main', true)
                ->with([
                    'subtasks.validations',
                    'subtasks.subtaskFailures',
                    'reviews' => function ($reviewQuery) {
                        $reviewQuery->where('date', $this->selectedDate)
                            ->when($this->selectedLocation, fn($q) => $q->where('location_id', $this->selectedLocation))
                            ->when($this->selectedUser, fn($q) => $q->where('user_id', $this->selectedUser));
                    },
                    'subtasks.reviews' => function ($reviewQuery) {
                        $reviewQuery->where('date', $this->selectedDate)
                            ->when($this->selectedLocation, fn($q) => $q->where('location_id', $this->selectedLocation))
                            ->when($this->selectedUser, fn($q) => $q->where('user_id', $this->selectedUser));
                    },
                    'subtasks.reviews.validation'
                ]);
        };

        $groups = Group::with(['tasks' => $commonTaskQuery])->get();

        $tasksWithoutGroup = Task::whereDoesntHave('group')
            ->where($commonTaskQuery)
            ->get();

        return view('livewire.dashboard.viewer-component', [
            'locations' => $locations,
            'users' => $users,
            'groups' => $groups,
            'tasksWithoutGroup' => $tasksWithoutGroup,
            'nowFormated' => $this->selectedDate
        ]);
    }
}
