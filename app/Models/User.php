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
        'skills',
        'bio',
        'job_title',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    // ── Rôle global ──
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isMember(): bool
    {
        return $this->role === 'member';
    }

public function isProjectAdmin(Project $project): bool
{
    return $project->owner_id === $this->id
        || $project->members()
            ->where('project_user.user_id', $this->id)
            ->where('project_user.role', 'admin')
            ->exists();
}
    // ── Skills sous forme de tableau ──
    public function skillsArray(): array
    {
        if (!$this->skills) return [];
        return array_map('trim', explode(',', $this->skills));
    }

    // ── Relations ──
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