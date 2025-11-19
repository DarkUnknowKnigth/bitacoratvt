<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'location_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
    public function location()
    {
        return $this->belongsTo(Location::class);
    }
    public function roles(){
        return $this->belongsToMany(Role::class, 'role_user', 'user_id', 'role_id');
    }
    public function tasks()
    {
        return $this->belongsToMany(Task::class);
    }

    /**
     * Obtiene las fallas reportadas por este usuario.
     */
    public function failures()
    {
        return $this->hasMany(Failure::class);
    }
    //agrega la funcionalidad para saber si es administrador    public function isAdmin()
    public function isAdmin()
    {
        return $this->role && $this->role->slug === 'admin';
    }

}
