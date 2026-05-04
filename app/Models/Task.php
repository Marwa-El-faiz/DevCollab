<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'created_by',
        'assigned_to',
        'title',
        'description',
        'status',
        'priority',
        'position',
        'due_date',
        'ai_generated',
    ];

    protected $casts = [
        'due_date'     => 'date',
        'ai_generated' => 'boolean',
    ];

  

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }


    public function deadlineStatus(): string
    {
        if (!$this->due_date || $this->status === 'done') {
            return 'ok';
        }

        $due = Carbon::parse($this->due_date)->endOfDay();

        if ($due->isPast()) {
            return 'overdue';
        }

        if ($due->isBefore(now()->addDays(2))) {
            return 'soon';
        }

        return 'ok';
    }

   
    public function isOverdue(): bool
    {
        return $this->deadlineStatus() === 'overdue';
    }

   
    public function isDueSoon(): bool
    {
        return $this->deadlineStatus() === 'soon';
    }

  
    public function priorityColor(): string
    {
        return match($this->priority) {
            'high'   => '#ef4444',
            'medium' => '#f59e0b',
            'low'    => '#6b7280',
            default  => '#6b7280',
        };
    }
    public function attachments()
{
    return $this->hasMany(TaskAttachment::class);
}
}