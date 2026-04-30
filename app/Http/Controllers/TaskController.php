<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    // ── Créer une tâche ──
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

        // Position = fin de la colonne todo
        $position = $project->tasks()
                            ->where('status', 'todo')
                            ->count();

        Task::create([
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

        return redirect()->back()
                         ->with('success', 'Tâche créée avec succès !');
    }

    // ── Mettre à jour une tâche (statut, titre, priorité) ──
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

        $task->update([
            'title'       => $request->title,
            'description' => $request->description,
            'priority'    => $request->priority,
            'status'      => $request->status,
            'due_date'    => $request->due_date,
            'assigned_to' => $request->assigned_to ?: null,
        ]);

        return redirect()->back()
                         ->with('success', 'Tâche mise à jour !');
    }

    // ── Déplacer une tâche (drag & drop) ──
    // Appelé en AJAX depuis le Kanban
    public function move(Request $request, Project $project, Task $task)
    {
        $this->checkAccess($project);

        $request->validate([
            'status'   => 'required|in:todo,in_progress,done',
            'position' => 'required|integer|min:0',
        ]);

        // Mettre à jour statut + position
        $task->update([
            'status'   => $request->status,
            'position' => $request->position,
        ]);

        // IMPORTANT : retourner du JSON pour le fetch côté JS
        return response()->json([
            'success'  => true,
            'task_id'  => $task->id,
            'status'   => $task->status,
            'position' => $task->position,
        ]);
    }

    // ── Supprimer une tâche ──
    public function destroy(Project $project, Task $task)
    {
        $this->checkAccess($project);

        $task->delete();

        return redirect()->back()
                         ->with('success', 'Tâche supprimée.');
    }

    // ── Helper : vérifier accès au projet ──
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