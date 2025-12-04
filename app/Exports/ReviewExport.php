<?php

namespace App\Exports;

use App\Models\Binnacle;
use App\Models\Location;
use App\Models\Review;
use App\Models\User;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;

class ReviewExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $day;
    protected $binnacle;
    protected $location;
    protected $user;

    public function __construct(Carbon $day, Binnacle $binnacle, ?Location $location, ?User $user)
    {
        $this->day = $day;
        $this->binnacle = $binnacle;
        $this->location = $location;
        $this->user = $user;
    }
    public function collection()
    {
        return Review::with('location','validation')
            ->whereDate('date',$this->day->format('Y-m-d'))
            ->when($this->location, function($query){
                $query->where('location_id',$this->location->id);

            })
            ->when($this->user, function($query){
                $query->where('user_id',$this->user->id);
            })
            ->when($this->binnacle, function($query){
                $tasksIds = Binnacle::find($this->binnacle->id)->tasks()->pluck('id')->toArray();
                $query->whereIn('task_id',$tasksIds)
                ->orWhereIn('subtask_id',$tasksIds);
            })
            ->get()
            ->map(function($review){
                return [
                    'ID' => $review->id,
                    'Bitácora' => $review->binnacle->name,
                    'Fecha' => $review->date,
                    'Hora' => $review->time,
                    'Usuario' => $review->user->name,
                    'Sucursal' => $review->location->name,
                    'Tarea' => $review->task->name ?? 'N/A',
                    'Subtarea' => $review->subtask->name ?? 'N/A',
                    'Validación' => $review->validation->name ?? 'N/A',
                    'Observación' => $review->observation,
                    'Captura' => $review->value,
                    'Latitud' => $review->latitude,
                    'Longitud' => $review->longitude,
                    'Comentario' => $review->comment,
                    'Creado' => $review->created_at,
                    'Actualizado' => $review->updated_at,
                    'Mapa'=> route('map',['user'=> $review->user->id, 'date'=> $review->date])
                ];
            });
    }
}
