<?php

namespace App\Livewire\Review;

use App\Models\Location;
use App\Models\Review;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class ReviewComponent extends Component
{
    use WithPagination;
    public $users = [];
    public $reviews = [];
    public $locations = [];
    public $nowDate = '';
    public $location_id = '';
    public $user_id;

    public function render()
    {
        $this->reloadReviews();
        return view('livewire.review.review-component');
    }
    public function reloadReviews(){
        if(auth()->user()->role->slug=='admin'){
            $this->reviews = Review::with('location','validation')
            ->whereDate('date',$this->nowDate)
            ->when($this->location_id, function($query){
                $query->where('location_id',$this->location_id);

            })
            ->when($this->user_id, function($query){
                $query->where('user_id',$this->user_id);
            })
            ->get();
        } else {
            $this->reviews = Review::with('location','validation')
            ->whereDate('date',$this->nowDate)
            ->where('user_id',auth()->user()->id)
            ->get();
        }
    }
    public function mount(){
        $this->nowDate = Carbon::now()->format('Y-m-d');
        $this->location_id = auth()->user()->location_id;
        $this->locations = Location::all();
        $this->user_id = auth()->user()->id;
        if(auth()->user()->role->slug=='admin'){
            $this->users = User::all();
        }else{
            $this->users = User::where('id',auth()->user()->id)->get();
        }

        $this->reloadReviews();
    }
    public function destroy(Review $review){
        $review->delete();
        session()->flash('message','Bitácora eliminada correctamente');
        $this->reloadReviews();
    }
    public function getHourlyPerformance(){
        return Review::where('date',$this->nowDate)->select('time', DB::raw('COUNT(*) as total'))->groupBy('time')->get()->toArray();
    }
}
