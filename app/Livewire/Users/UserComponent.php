<?php

namespace App\Livewire\Users;

use App\Models\Location;
use App\Models\Role;
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
    #[Validate('required')]
    public $roles_selected;
    public $user_id;
    public $users = [];
    public $locations = [];
    public $roles = [];
    public function render()
    {
        return view('livewire.users.user-component');
    }
    public function mount(){
        $this->users = User::orderBy('name')->get();
        $this->locations = Location::orderBy('name')->get();
        $this->roles = Role::orderBy('name')->get();
    }
    public function save(){
        $this->validate();
        $user = User::create( $this->only(['name', 'email','password','location_id']));
        if ($this->roles_selected) {
            $user->roles()->attach($this->roles_selected);
        }
        session()->flash('status', 'Usuario creada.');
        $this->users = User::all();
        return redirect()->route('users');
    }
    public function destroy(User $user){
        $user->delete();
        $this->users = User::all();
        session()->flash('status', 'Usuario eliminada.');
        return redirect()->route('users');
    }
    public function edit(User $user){
        $this->name = $user->name;
        $this->email = $user->email;
        // $this->password = $user->password;
        $this->location_id = $user->location_id;
        $this->user_id = $user->id;
        $this->roles_selected = $user->roles->pluck('id')->toArray();
    }
    public function update(User $user){
        $this->validate([
            'name' => 'required',
            'email' => 'required',
            'location_id' => 'required',
        ]);
        if($this->password){
            $this->validate([
                'password' => 'required',
            ]);
            $user->update( $this->only(['password']));
        }
        $user->update( $this->only(['name','email','location_id']));
        if ($this->roles_selected) {
            $user->roles()->sync($this->roles_selected);
        }
        $this->users = User::all();
        session()->flash('status', 'Usuario actualizada.');
        return redirect()->route('users');
    }
}
