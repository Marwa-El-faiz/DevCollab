<?php

namespace App\Http\Controllers;
use App\Models\Comment;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    // ── Ajouter un commentaire sur une tâche ──
    public function store(Request $request, Task $task)
    {
        // Valider le contenu
        $request->validate([
            'body' => 'required|string|min:1|max:1000',
        ]);

        // Vérifier que l'utilisateur a accès au projet de cette tâche
        $project = $task->project;
        $userId  = Auth::id();

        $hasAccess = $project->owner_id === $userId
            || $project->members()->where('user_id', $userId)->exists();

        if (!$hasAccess) {
            abort(403, 'Accès refusé.');
        }

        // Créer le commentaire
        Comment::create([
            'task_id' => $task->id,
            'user_id' => $userId,
            'body'    => $request->body,
        ]);

        return redirect()->back()
                         ->with('success', 'Commentaire ajouté !');
    }

    // ── Supprimer un commentaire ──
    public function destroy(Task $task, Comment $comment)
    {
        // Seul l'auteur peut supprimer son commentaire
        if ($comment->user_id !== Auth::id()) {
            abort(403, 'Vous ne pouvez pas supprimer ce commentaire.');
        }

        $comment->delete();

        return redirect()->back()
                         ->with('success', 'Commentaire supprimé.');
    }
}