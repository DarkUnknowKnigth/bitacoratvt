<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Validation extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'value'];
    public function tasks()
    {
        return $this->belongsToMany(Task::class, 'task_validation', 'validation_id', 'task_id');
    }
}
