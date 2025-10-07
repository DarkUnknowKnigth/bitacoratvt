<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Binnacle extends Model
{
    use HasFactory;
    protected $fillable = ['name','type','location_id','role_id'];
    public function location(){
        return $this->belongsTo(Location::class);
    }
    public function role(){
        return $this->belongsTo(Role::class);
    }
    public function tasks(){
        return $this->hasMany(Task::class);
    }
}
