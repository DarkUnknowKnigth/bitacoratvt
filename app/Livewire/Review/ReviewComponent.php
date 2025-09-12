<?php

namespace App\Livewire\Review;

use App\Models\Review;
use Livewire\Component;

class ReviewComponent extends Component
{
    public $reviews = [];
    public function render()
    {
        return view('livewire.review.review-component');
    }
    public function mount(){
        if(auth()->user()->role->slug=='admin'){
            $this->reviews = Review::with('location','validation')->get();
        } else {
            $this->reviews = Review::with('location','validation')->where('user_id',auth()->user()->id)->get();
        }

    }
    public function destroy(Review $review){
        $review->delete();
        session()->flash('message','Bitácora eliminada correctamente');
        $this->reviews = Review::with('location','validation')->where('user_id',auth()->user()->id)->get();
    }
}
