<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Notifications\TaskAssigned;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function store(Request $request, Project $project)
    {
        $this->checkAccess($project);

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

        // ── Notification : tâche assignée ──
        if ($request->assigned_to && $request->assigned_to != Auth::id()) {
            $assignee = User::find($request->assigned_to);
            if ($assignee) {
                $assignee->notify(new TaskAssigned($task));
            }
        }

        return redirect()->back()
                         ->with('success', 'Tâche créée avec succès !');
    }

    public function update(Request $request, Project $project, Task $task)
    {
        $this->checkAccess($project);

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

        // ── Notification : nouvel assigné ──
        if ($request->assigned_to
            && $request->assigned_to != $oldAssignee
            && $request->assigned_to != Auth::id()) {
            $assignee = User::find($request->assigned_to);
            if ($assignee) {
                $assignee->notify(new TaskAssigned($task));
            }
        }

        return redirect()->back()
                         ->with('success', 'Tâche mise à jour !');
    }

    public function move(Request $request, Project $project, Task $task)
    {
        $this->checkAccess($project);

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

        $task->delete();

        return redirect()->back()
                         ->with('success', 'Tâche supprimée.');
    }

    private function checkAccess(Project $project): void
    {
        $userId = Auth::id();

        $hasAccess = $project->owner_id === $userId
            || $project->members()->where('user_id', $userId)->exists();

        if (!$hasAccess) {
            abort(403, 'Accès refusé à ce projet.');
        }
    }
}