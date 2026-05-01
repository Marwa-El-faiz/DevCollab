<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
    'name',
    'email',
    'password',
    'role',
    'theme',
    'language',
    'github_id',
    'github_token',
     'google_id',
    'avatar',       
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }


    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isMember(): bool
    {
        return $this->role === 'member';
    }
    public function ownedProjects()
{
    return $this->hasMany(Project::class, 'owner_id');
}

public function projects()
{
    return $this->belongsToMany(Project::class)
                ->withPivot('role')
                ->withTimestamps();
}

public function tasks()
{
    return $this->hasMany(Task::class, 'assigned_to');
}

public function comments()
{
    return $this->hasMany(Comment::class);
}
}