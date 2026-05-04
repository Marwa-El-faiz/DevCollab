<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
public function index(Request $request, Project $project)
{
    $this->checkAccess($project);

    $query = ChatMessage::where('project_id', $project->id)->with('user');

    // Si on poll depuis un ID
    if ($request->has('after')) {
        $query->where('id', '>', (int) $request->after);
    } else {
        $query->latest()->take(50);
    }

    $messages = $query->oldest()->get();

    return response()->json($messages->map(fn($m) => [
        'id'            => $m->id,
        'body'          => $m->body,
        'user_name'     => $m->user->name,
        'user_initials' => strtoupper(substr($m->user->name, 0, 2)),
        'is_me'         => $m->user_id === Auth::id(),
        'time'          => $m->created_at->format('H:i'),
        'date'          => $m->created_at->diffForHumans(),
    ]));
}

    // Envoyer un message
    public function store(Request $request, Project $project)
    {
        $this->checkAccess($project);

        $request->validate([
            'body' => 'required|string|max:1000',
        ]);

        $message = ChatMessage::create([
            'project_id' => $project->id,
            'user_id'    => Auth::id(),
            'body'       => $request->body,
        ]);

        $message->load('user');

        return response()->json([
            'id'            => $message->id,
            'body'          => $message->body,
            'user_name'     => $message->user->name,
            'user_initials' => strtoupper(substr($message->user->name, 0, 2)),
            'is_me'         => true,
            'time'          => $message->created_at->format('H:i'),
            'date'          => 'à l\'instant',
        ]);
    }

    // Supprimer son propre message
    public function destroy(Project $project, ChatMessage $message)
    {
        if ($message->user_id !== Auth::id()) {
            abort(403);
        }

        $message->delete();
        return response()->json(['success' => true]);
    }

    private function checkAccess(Project $project): void
    {
        $userId = Auth::id();
        $ok = $project->owner_id === $userId
            || $project->members()->where('user_id', $userId)->exists();
        if (!$ok) abort(403);
    }
}