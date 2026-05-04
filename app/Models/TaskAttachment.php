<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskAttachment extends Model
{
    protected $fillable = [
        'task_id',
        'user_id',
        'filename',
        'original_name',
        'path',
        'mime_type',
        'size',
    ];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function readableSize(): string
    {
        $bytes = $this->size;
        if ($bytes < 1024)    return $bytes . ' B';
        if ($bytes < 1048576) return round($bytes / 1024, 1) . ' KB';
        return round($bytes / 1048576, 1) . ' MB';
    }

    public function isImage(): bool
    {
        return str_starts_with($this->mime_type, 'image/');
    }

    public function icon(): string
    {
        return match(true) {
            str_starts_with($this->mime_type, 'image/') => '🖼️',
            $this->mime_type === 'application/pdf'       => '📄',
            str_contains($this->mime_type, 'word')       => '📝',
            str_contains($this->mime_type, 'excel')      => '📊',
            str_contains($this->mime_type, 'zip')        => '🗜️',
            default                                       => '📎',
        };
    }
}