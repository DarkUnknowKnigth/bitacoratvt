<?php

namespace App\Livewire\Review;

use App\Exports\ReviewExport;
use App\Models\Binnacle;
use App\Models\Failure;
use App\Models\Review;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class ReviewComponent extends Component
{
    use WithPagination;
    public $users = [];
    public $reviews = [];
    public $failures = [];
    public $binnacles = [];

    #[Url(as: 'fecha', keep: false)]
    public $nowDate = '';
    #[Url(as: 'usuario', keep: false)]
    public $user_id;
    #[Url(as: 'bitacora', keep: false)]
    public $binnacle_id;

    public function render()
    {
        $this->reloadReviews();
        return view('livewire.review.review-component');
    }
    public function reloadReviews(){
        if(auth()->user()->roles->pluck('slug')->contains('admin')){
            $this->reviews = Review::with('location','validation')
            ->when($this->binnacle_id, function($query){
                $tasksIds = Binnacle::find($this->binnacle_id)->tasks()->pluck('id')->toArray();
                $query->whereIn('subtask_id',$tasksIds);
            })
            ->when($this->user_id, function($query){
                $query->where('user_id',$this->user_id);
            })
            ->whereDate('date',$this->nowDate)
            ->get();
        } else {
            $this->reviews = Review::with('location','validation')
            ->whereDate('date',$this->nowDate)
            ->where('user_id',auth()->user()->id)
            ->get();
        }

        // Cargar fallas correspondientes a los filtros
        $this->failures = Failure::with(['task', 'subtask', 'user', 'location'])
            ->whereDate('date', $this->nowDate)
            ->when($this->user_id, function($query) {
                $query->where('user_id', $this->user_id);
            })
            ->orderBy('created_at', 'desc')
            ->get();
        $this->dispatch('update-chart', data: $this->getHourlyPerformance());
        $this->dispatch('update-location-chart', data: $this->getPerformanceByLocation());
    }
    public function mount(){
        $this->nowDate = $this->nowDate ?: Carbon::now()->format('Y-m-d');
        // $this->user_id = $this->user_id ?: auth()->user()->id;
        if(auth()->user()->roles->pluck('slug')->contains('admin')){
            $this->users = User::all();
        }else{
            $this->users = User::where('id',auth()->user()->id)->get();
        }
        $this->binnacles = Binnacle::all();
        $this->reloadReviews();
    }
    public function destroy(Review $review){
        $review->delete();
        session()->flash('status','Bitácora eliminada correctamente');
        return redirect()->route('reviews');
    }
    public function getHourlyPerformance(){
        $query = Review::whereDate('date', $this->nowDate)
            ->when($this->user_id, function($query){
                $query->where('user_id',$this->user_id);
            })
            ->select(DB::raw('time'), DB::raw('COUNT(*) as total'))
            ->groupBy('time')
            ->orderBy('time');
        return $query->get()->toArray();
    }

    public function getPerformanceByLocation(){
        // Solo tiene sentido si el usuario es admin, que puede ver varias sucursales.
        if (!auth()->user()->roles->pluck('slug')->contains('admin')) {
            return [];
        }
        $query = Review::join('locations', 'reviews.location_id', '=', 'locations.id')
            ->when($this->user_id, function($query){
                $query->where('user_id',$this->user_id);
            })
            ->whereDate('date', $this->nowDate)
            ->select('locations.name as location_name', DB::raw('COUNT(reviews.id) as total'))
            ->groupBy('locations.name');
        return $query->get()->toArray();
    }
    public function export(){
        $day = Carbon::parse($this->nowDate);
        $user = User::find($this->user_id);
        $binnacle = Binnacle::find($this->binnacle_id);
        return Excel::download(new ReviewExport($day,$binnacle,$user ),'Exportable-'.$day->format('Y-m-d').'.xlsx');

    }
}
