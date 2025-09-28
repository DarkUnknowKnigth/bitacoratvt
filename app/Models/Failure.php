<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Failure extends Model
{
    use HasFactory;
    protected $fillable = [
        'subtask_id',
        'task_id',
        'user_id',
        'location_id',
        'description',
        'date',
        'solved',
    ];

    /**
     * Obtiene la tarea principal asociada a la falla.
     */
    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    /**
     * Obtiene la subtarea asociada a la falla.
     */
    public function subtask()
    {
        return $this->belongsTo(Task::class, 'subtask_id');
    }

    /**
     * Obtiene el usuario que reportó la falla.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Obtiene la sucursal donde ocurrió la falla.
     */
    public function location()
    {
        return $this->belongsTo(Location::class);
    }
}
