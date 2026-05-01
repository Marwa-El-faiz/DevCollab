<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Comment;
use App\Notifications\CommentAdded;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request, Task $task)
    {
        $request->validate([
            'body' => 'required|string|max:1000',
        ]);

        $comment = $task->comments()->create([
            'user_id' => Auth::id(),
            'body'    => $request->body,
        ]);

        if ($task->created_by && $task->created_by != Auth::id()) {
            $creator = \App\Models\User::find($task->created_by);
            if ($creator) {
                $creator->notify(new CommentAdded($comment));
            }
        }

        if ($task->assigned_to
            && $task->assigned_to != Auth::id()
            && $task->assigned_to != $task->created_by) {
            $assignee = \App\Models\User::find($task->assigned_to);
            if ($assignee) {
                $assignee->notify(new CommentAdded($comment));
            }
        }

        return redirect()->route('projects.show', $task->project_id)
                         ->with('success', 'Commentaire ajouté !');
    }

    public function destroy(Task $task, Comment $comment)
    {
        if ($comment->user_id !== Auth::id()) {
            abort(403);
        }

        $comment->delete();

        return redirect()->route('projects.show', $task->project_id)
                         ->with('success', 'Commentaire supprimé.');
    }
}