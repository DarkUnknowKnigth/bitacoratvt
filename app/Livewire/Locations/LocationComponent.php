<?php

namespace App\Livewire\Locations;

use App\Models\Location;
use Livewire\Attributes\Validate;
use Livewire\Component;

class LocationComponent extends Component
{
    public $locations = [];
    #[Validate('required')]
    public $name;
    #[Validate('required')]
    public $address;
    public $location_id;
    public function render()
    {
        return view('livewire.locations.location-component');
    }
    public function mount(){
        $this->locations = Location::all();
    }
    public function save(){
        $this->validate();
        Location::create( $this->only(['name', 'address']));
        session()->flash('status', 'Sucursal creada.');
        $this->locations = Location::all();
        return redirect()->route('locations');
    }
    public function destroy(Location $location){
        $location->delete();
        $this->locations = Location::all();
        session()->flash('status', 'Sucursal eliminada.');
        return redirect()->route('locations');
    }
    public function edit(Location $location){
        $this->name = $location->name;
        $this->address = $location->address;
        $this->location_id = $location->id;
    }
    public function update(Location $location){
        $this->validate();
        $location->update($this->only(['name', 'address']));
        $this->locations = Location::all();
        session()->flash('status', 'Sucursal actualizada.');
        return redirect()->route('locations');
    }
}
