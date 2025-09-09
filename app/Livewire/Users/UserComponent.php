<?php

namespace App\Livewire\Users;

use App\Models\Location;
use App\Models\User;
use Livewire\Component;

class UserComponent extends Component
{
    public $name;
    public $email;
    public $password;
    public $location;
    public $locations = [];
    public $users = [];
    public function render()
    {
        return view('livewire.users.user-component');
    }
    public function mount(){
        $this->users = User::orderBy('name')->get();
        $this->locations = Location::orderBy('name')->get();
    }
}
