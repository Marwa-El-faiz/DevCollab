<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Notifications\TaskAssigned;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TaskController extends Controller
{
    public function store(Request $request, Project $project)
    {
        $this->checkAccess($project);

        if (!$project->isAdmin(Auth::id())) {
            return redirect()
                ->back()
                ->with('error', 'Seul un administrateur peut créer des tâches.');
        }

        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority'    => 'required|in:low,medium,high',
            'status'      => 'nullable|in:todo,in_progress,done',
            'due_date'    => 'nullable|date',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        $position = $project->tasks()
            ->where('status', 'todo')
            ->count();

        $task = Task::create([
            'project_id'  => $project->id,
            'created_by'  => Auth::id(),
            'assigned_to' => $request->assigned_to ?: null,
            'title'       => $request->title,
            'description' => $request->description,
            'priority'    => $request->priority,
            'status'      => $request->status ?? 'todo',
            'due_date'    => $request->due_date,
            'position'    => $position,
        ]);

        if ($request->assigned_to && $request->assigned_to != Auth::id()) {
            $assignee = User::find($request->assigned_to);

            if ($assignee) {
                try {
                    $assignee->notify(new TaskAssigned($task));
                } catch (\Exception $e) {
                    Log::warning('Notification email échouée : ' . $e->getMessage());
                }
            }
        }

        return redirect()
            ->back()
            ->with('success', 'Tâche créée avec succès !');
    }

    public function update(Request $request, Project $project, Task $task)
    {
        $this->checkAccess($project);

        if (!$project->isAdmin(Auth::id())) {
            return redirect()
                ->back()
                ->with('error', 'Seul un administrateur peut modifier les tâches.');
        }

        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority'    => 'required|in:low,medium,high',
            'status'      => 'required|in:todo,in_progress,done',
            'due_date'    => 'nullable|date',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        $oldAssignee = $task->assigned_to;

        $task->update([
            'title'       => $request->title,
            'description' => $request->description,
            'priority'    => $request->priority,
            'status'      => $request->status,
            'due_date'    => $request->due_date,
            'assigned_to' => $request->assigned_to ?: null,
        ]);

        if (
            $request->assigned_to &&
            $request->assigned_to != $oldAssignee &&
            $request->assigned_to != Auth::id()
        ) {
            $assignee = User::find($request->assigned_to);

            if ($assignee) {
                try {
                    $assignee->notify(new TaskAssigned($task));
                } catch (\Exception $e) {
                    Log::warning('Notification email échouée : ' . $e->getMessage());
                }
            }
        }

        return redirect()
            ->back()
            ->with('success', 'Tâche mise à jour !');
    }

    public function move(Request $request, Project $project, Task $task)
    {
        $this->checkAccess($project);

        if (!$project->isAdmin(Auth::id()) && $task->assigned_to !== Auth::id()) {
            return response()->json(['error' => 'Non autorisé.'], 403);
        }

        $request->validate([
            'status'   => 'required|in:todo,in_progress,done',
            'position' => 'required|integer|min:0',
        ]);

        $task->update([
            'status'   => $request->status,
            'position' => $request->position,
        ]);

        return response()->json([
            'success'  => true,
            'task_id'  => $task->id,
            'status'   => $task->status,
            'position' => $task->position,
        ]);
    }

    public function destroy(Project $project, Task $task)
    {
        $this->checkAccess($project);

        if (!$project->isAdmin(Auth::id())) {
            return redirect()
                ->back()
                ->with('error', 'Seul un administrateur peut supprimer des tâches.');
        }

        $task->delete();

        return redirect()
            ->back()
            ->with('success', 'Tâche supprimée.');
    }

    public function myTasks()
    {
        $user = Auth::user();

        $tasks = Task::where('assigned_to', $user->id)
            ->with(['project', 'assignee'])
            ->orderByRaw("FIELD(status, 'in_progress', 'todo', 'done')")
            ->orderBy('due_date')
            ->get()
            ->groupBy('status');

        $stats = [
            'todo' => Task::where('assigned_to', $user->id)
                ->where('status', 'todo')
                ->count(),

            'in_progress' => Task::where('assigned_to', $user->id)
                ->where('status', 'in_progress')
                ->count(),

            'done' => Task::where('assigned_to', $user->id)
                ->where('status', 'done')
                ->count(),

            'overdue' => Task::where('assigned_to', $user->id)
                ->where('status', '!=', 'done')
                ->whereNotNull('due_date')
                ->where('due_date', '<', now()->toDateString())
                ->count(),
        ];

        return view('tasks.my-tasks', compact('tasks', 'stats'));
    }

    private function checkAccess(Project $project): void
    {
        $userId = Auth::id();

        $hasAccess = $project->owner_id === $userId
            || $project->members()
                ->where('user_id', $userId)
                ->exists();

        if (!$hasAccess) {
            abort(403, 'Accès refusé à ce projet.');
        }
    }
}
