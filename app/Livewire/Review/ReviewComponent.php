<?php

namespace App\Livewire\Review;

use App\Models\Location;
use App\Models\Review;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class ReviewComponent extends Component
{
    use WithPagination;
    public $reviews = [];
    public $locations = [];
    public $nowDate = '';
    public $location_id = '';

    public function render()
    {
        return view('livewire.review.review-component');
    }
    public function reloadReviews(){
        if(auth()->user()->role->slug=='admin'){
            $this->reviews = Review::with('location','validation')->whereDate('date',$this->nowDate)->where('location_id',$this->location_id)->get();
        } else {
            $this->reviews = Review::with('location','validation')->whereDate('date',$this->nowDate)->where('user_id',auth()->user()->id)->get();
        }
    }
    public function mount(){
        $this->nowDate = Carbon::now()->format('Y-m-d');
        $this->location_id = auth()->user()->location_id;
        $this->locations = Location::all();
        $this->reloadReviews();
    }
    public function destroy(Review $review){
        $review->delete();
        session()->flash('message','Bitácora eliminada correctamente');
        $this->reviews = Review::with('location','validation')->where('user_id',auth()->user()->id)->get();
    }
}
