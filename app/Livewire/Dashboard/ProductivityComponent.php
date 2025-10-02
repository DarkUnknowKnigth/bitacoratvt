<?php

namespace App\Livewire\Dashboard;

use App\Models\Review;
use App\Models\User;
use Livewire\Component;

class ProductivityComponent extends Component
{
    public User $user;
    public string $date;
    public $reviews;

    public function mount(User $user, string $date)
    {
        $this->user = $user;
        $this->date = $date;

        // Cargamos las revisiones con sus relaciones para usarlas en el popup del mapa
        $this->reviews = Review::where('user_id', $this->user->id)
            ->where('date', $this->date)
            ->whereNotNull(['latitude', 'longitude']) // Solo las que tienen coordenadas
            ->with(['task', 'subtask']) // Carga ansiosa para optimizar
            ->orderBy('time', 'asc')
            ->get();
    }

    public function render()
    {
        return view('livewire.dashboard.productivity-component');
    }
}
