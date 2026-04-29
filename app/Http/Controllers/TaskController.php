<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function store(Request $request, Project $project)
    {
        $this->authorizeAccess($project);

        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority'    => 'nullable|in:low,medium,high',
            'assigned_to' => 'nullable|exists:users,id',
            'due_date'    => 'nullable|date',
        ]);

        $project->tasks()->create([
            'created_by'  => Auth::id(),
            'assigned_to' => $request->assigned_to,
            'title'       => $request->title,
            'description' => $request->description,
            'priority'    => $request->priority ?? 'medium',
            'status'      => 'todo',
            'due_date'    => $request->due_date,
        ]);

        return redirect()->route('projects.show', $project)
                         ->with('success', 'Tâche créée !');
    }

    public function update(Request $request, Project $project, Task $task)
    {
        $this->authorizeAccess($project);

        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority'    => 'nullable|in:low,medium,high',
            'status'      => 'nullable|in:todo,in_progress,done',
            'assigned_to' => 'nullable|exists:users,id',
            'due_date'    => 'nullable|date',
        ]);

        $task->update($request->only([
            'title', 'description', 'priority',
            'status', 'assigned_to', 'due_date',
        ]));

        return redirect()->route('projects.show', $project)
                         ->with('success', 'Tâche mise à jour !');
    }

    public function destroy(Project $project, Task $task)
    {
        $this->authorizeAccess($project);
        $task->delete();

        return redirect()->route('projects.show', $project)
                         ->with('success', 'Tâche supprimée.');
    }

    // Changer le statut via drag & drop (AJAX)
    public function move(Request $request, Project $project, Task $task)
    {
        $this->authorizeAccess($project);

        $request->validate([
            'status' => 'required|in:todo,in_progress,done',
        ]);

        $task->update(['status' => $request->status]);

        return response()->json(['success' => true]);
    }

    private function authorizeAccess(Project $project)
    {
        $userId = Auth::id();
        $hasAccess = $project->owner_id === $userId
            || $project->members()->where('user_id', $userId)->exists();

        if (!$hasAccess) abort(403);
    }
}