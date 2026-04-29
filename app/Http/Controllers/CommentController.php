<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request, Task $task)
    {
        $request->validate([
            'body' => 'required|string|max:1000',
        ]);

        $task->comments()->create([
            'user_id' => Auth::id(),
            'body'    => $request->body,
        ]);

        return redirect()->route('projects.show', $task->project_id)
                         ->with('success', 'Commentaire ajouté !');
    }

    public function destroy(Task $task, Comment $comment)
    {
        // Seul l'auteur peut supprimer son commentaire
        if ($comment->user_id !== Auth::id()) {
            abort(403);
        }

        $comment->delete();

        return redirect()->route('projects.show', $task->project_id)
                         ->with('success', 'Commentaire supprimé.');
    }
}