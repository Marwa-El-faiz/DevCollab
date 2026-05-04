<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;

class AnalyticsController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $projects = Project::where('owner_id', $user->id)
            ->orWhereHas('members', fn($q) => $q->where('user_id', $user->id))
            ->with(['tasks', 'members'])
            ->get();

        $projectIds = $projects->pluck('id');

        $stats = [
            'total_projects'  => $projects->count(),
            'active_projects' => $projects->where('status', 'active')->count(),
            'total_tasks'     => Task::whereIn('project_id', $projectIds)->count(),
            'done_tasks'      => Task::whereIn('project_id', $projectIds)->where('status','done')->count(),
            'inprog_tasks'    => Task::whereIn('project_id', $projectIds)->where('status','in_progress')->count(),
            'todo_tasks'      => Task::whereIn('project_id', $projectIds)->where('status','todo')->count(),
            'overdue_tasks'   => Task::whereIn('project_id', $projectIds)
                                     ->where('status','!=','done')
                                     ->whereNotNull('due_date')
                                     ->where('due_date','<', now()->toDateString())
                                     ->count(),
            'total_members'   => User::count(),
            'total_comments'  => Comment::whereHas('task', fn($q) =>
                                     $q->whereIn('project_id', $projectIds)
                                 )->count(),
        ];

        // Données projets pour graphique barres
        $projectData = $projects->map(fn($p) => [
            'name'    => \Str::limit($p->name, 20),
            'todo'    => $p->tasks->where('status','todo')->count(),
            'inprog'  => $p->tasks->where('status','in_progress')->count(),
            'done'    => $p->tasks->where('status','done')->count(),
            'total'   => $p->tasks->count(),
            'percent' => $p->tasks->count() > 0
                ? round($p->tasks->where('status','done')->count() / $p->tasks->count() * 100)
                : 0,
        ])->values();

        // Stats par membre
        $memberStats = User::withCount([
            'tasks as assigned' => fn($q) => $q->whereIn('project_id', $projectIds),
            'tasks as done'     => fn($q) => $q->whereIn('project_id', $projectIds)->where('status','done'),
        ])->having('assigned', '>', 0)->get()->map(fn($u) => [
            'name'     => $u->name,
            'initials' => strtoupper(substr($u->name, 0, 2)),
            'assigned' => $u->assigned,
            'done'     => $u->done,
            'percent'  => $u->assigned > 0 ? round($u->done / $u->assigned * 100) : 0,
        ]);

        // Tâches par priorité
        $priorityData = [
            'high'   => Task::whereIn('project_id', $projectIds)->where('priority','high')->count(),
            'medium' => Task::whereIn('project_id', $projectIds)->where('priority','medium')->count(),
            'low'    => Task::whereIn('project_id', $projectIds)->where('priority','low')->count(),
        ];

        return view('analytics.index', compact(
            'stats', 'projectData', 'memberStats', 'priorityData'
        ));
    }
}