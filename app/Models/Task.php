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
        return $this->hasMany(Review::class)->where('subtask_id',null);
    }
    public function subReviews($id){
        return $this->hasMany(Review::class, 'subtask_id')->where('task_id',$id);
    }
    public function completedReview($date, $location_id, $task_id = null){
        if ($this->main) {
            return $this->hasMany(Review::class)->where('subtask_id',null)->where([['date',$date],['location_id',$location_id]]);
        }else{
            return $this->hasMany(Review::class, 'subtask_id')->where('task_id',$task_id)->where([['date',$date],['location_id',$location_id]]);
        };
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
    public function completedSubtasks($date, $location_id, ?User $user){
        return $this->hasMany(Review::class, 'task_id')->where([['date',$date],['location_id',$location_id]])->when($user->id,function($quer) use($user){
            $quer->where('user_id',$user->id);
        });
    }

    /**
     * Obtiene las fallas asociadas directamente a esta tarea (como tarea principal).
     */
    public function failures()
    {
        return $this->hasMany(Failure::class, 'task_id');
    }

    /**
     * Obtiene las fallas donde esta tarea actúa como subtarea.
     */
    public function subtaskFailures(?Task $task = null)
    {
        return $this->hasMany(Failure::class, 'subtask_id')->when($task && $task->id, function ($query) use($task) {
            $query->where('task_id', $task->id);
        });
    }
    public function group(){
        return $this->belongsToMany(Group::class,'group_task','task_id','group_id');
    }
}
