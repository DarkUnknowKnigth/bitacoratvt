<?php

namespace App\Livewire\Users;

use App\Models\Location;
use App\Models\User;
use Livewire\Attributes\Validate;
use Livewire\Component;

class UserComponent extends Component
{
    #[Validate('required')]
    public $name;
    #[Validate('required')]
    public $email;
    #[Validate('required')]
    public $password;
    #[Validate('required')]
    public $location_id;
    public $user_id;
    public $users = [];
    public $locations = [];
    public function render()
    {
        return view('livewire.users.user-component');
    }
    public function mount(){
        $this->users = User::orderBy('name')->get();
        $this->locations = Location::orderBy('name')->get();
    }
    public function save(){
        $this->validate();
        User::create( $this->only(['name', 'email','password','location_id']));
        session()->flash('status', 'Sucursal creada.');
        $this->users = User::all();
        return redirect()->route('users');
    }
    public function destroy(User $user){
        $user->delete();
        $this->users = User::all();
        session()->flash('status', 'Sucursal eliminada.');
        return redirect()->route('users');
    }
    public function edit(User $user){
        $this->name = $user->name;
        $this->email = $user->email;
        // $this->password = $user->password;
        $this->location_id = $user->location_id;
        $this->user_id = $user->id;
    }
    public function update(User $user){
        $this->validate();
        $user->update( $this->only(['name', 'email','password','location_id']));
        $this->users = User::all();
        session()->flash('status', 'Sucursal actualizada.');
        return redirect()->route('users');
    }
}
