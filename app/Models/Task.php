<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'main', 'task_id'];
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
    public function validations(){
        return $this->hasMany(Validation::class);
    }
    public function subtasks(){
        return $this->hasMany(Task::class, 'task_id');
    }
    public function parentTask(){
        return $this->belongsTo(Task::class, 'task_id');
    }
}
