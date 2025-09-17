<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Validation extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'value'];

    /**
     * Obtiene un ícono SVG basado en el nombre de la validación.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function icon(): Attribute
    {
        return Attribute::make(
            get: function () {
                $name = strtolower($this->name);
                if ($name === 'si' || $name === 'sí') {
                    return '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>'; // Palomita
                } elseif ($name === 'no') {
                    return '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>'; // Equis
                }
                return '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.228 9.049a4.02 4.02 0 015.544 0 4.02 4.02 0 010 5.684l-1.768 1.768a.5.5 0 00.707.707l1.768-1.768a5.02 5.02 0 000-7.098 5.02 5.02 0 00-7.098 0L6.5 10.5" /><path stroke-linecap="round" stroke-linejoin="round" d="M12 17v.01" /></svg>'; // Pregunta
            }
        );
    }

    public function tasks()
    {
        return $this->belongsToMany(Task::class, 'task_validation', 'validation_id', 'task_id');
    }
}
