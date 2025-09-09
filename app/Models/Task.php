<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'main'];
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
    public function validations(){
        return $this->belongsToMany(Validation::class, 'task_validation', 'task_id', 'validation_id');
    }
    public function subtasks(){
        return $this->belongsToMany(Task::class, 'subtasks', 'task_id', 'subtask_id');
    }
    public function mainTasks(){
        return $this->belongsToMany(Task::class, 'subtasks', 'subtask_id', 'task_id');
    }
}
