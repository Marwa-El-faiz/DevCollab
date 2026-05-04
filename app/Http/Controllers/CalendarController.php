<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CalendarController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $projects = Project::where('owner_id', $user->id)
            ->orWhereHas('members', fn($q) => $q->where('user_id', $user->id))
            ->get(['id', 'name']);

        return view('calendar.index', compact('projects'));
    }

    // API — retourne les tâches en JSON pour FullCalendar
    public function events(Request $request)
    {
        $user = Auth::user();

        $query = Task::whereHas('project', function($q) use ($user) {
            $q->where('owner_id', $user->id)
              ->orWhereHas('members', fn($q2) => $q2->where('user_id', $user->id));
        })
        ->whereNotNull('due_date')
        ->with(['project', 'assignee']);

        // Filtre par projet
        if ($request->project_id) {
            $query->where('project_id', $request->project_id);
        }

        // Filtre par statut
        if ($request->status) {
            $query->where('status', $request->status);
        }

        $tasks = $query->get();

        // Formater pour FullCalendar
        $events = $tasks->map(function($task) {
            $colors = [
                'todo'        => ['bg' => '#f59e0b', 'border' => '#d97706'],
                'in_progress' => ['bg' => '#3b82f6', 'border' => '#2563eb'],
                'done'        => ['bg' => '#10b981', 'border' => '#059669'],
            ];

            // Rouge si en retard
            $isOverdue = $task->status !== 'done'
                && $task->due_date
                && $task->due_date->isPast();

            $color = $isOverdue
                ? ['bg' => '#ef4444', 'border' => '#dc2626']
                : ($colors[$task->status] ?? $colors['todo']);

            return [
                'id'              => $task->id,
                'title'           => $task->title,
                'start'           => $task->due_date->format('Y-m-d'),
                'backgroundColor' => $color['bg'],
                'borderColor'     => $color['border'],
                'textColor'       => '#ffffff',
                'extendedProps'   => [
                    'project'     => $task->project->name,
                    'status'      => $task->status,
                    'priority'    => $task->priority,
                    'assignee'    => $task->assignee?->name ?? 'Non assigné',
                    'project_id'  => $task->project_id,
                    'is_overdue'  => $isOverdue,
                ],
            ];
        });

        return response()->json($events);
    }

    // Mettre à jour la date via drag & drop
    public function updateDate(Request $request, Task $task)
    {
        $request->validate([
            'due_date' => 'required|date',
        ]);

        $task->update(['due_date' => $request->due_date]);

        return response()->json(['success' => true]);
    }
}