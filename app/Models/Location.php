<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'address'];
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Obtiene las fallas reportadas en esta sucursal.
     */
    public function failures()
    {
        return $this->hasMany(Failure::class);
    }
}
