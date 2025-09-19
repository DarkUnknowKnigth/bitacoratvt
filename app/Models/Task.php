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
}
