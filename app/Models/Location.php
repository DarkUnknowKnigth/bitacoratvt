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
    public function tasks()
    {
        return $this->belongsToMany(Task::class, 'location_task', 'location_id', 'task_id');
    }
}
