<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AttachmentController extends Controller
{
    // Upload fichier
    public function store(Request $request, Task $task)
    {
        $request->validate([
            'file' => 'required|file|max:10240|mimes:jpg,jpeg,png,gif,webp,pdf,doc,docx,xls,xlsx,zip,txt',
        ], [
            'file.max'   => 'Le fichier ne doit pas dépasser 10 MB.',
            'file.mimes' => 'Format non supporté. Formats acceptés : images, PDF, Word, Excel, ZIP.',
        ]);

        // Vérifier accès au projet
        $this->checkAccess($task);

        $file = $request->file('file');

        // Stocker dans storage/app/attachments/task_{id}/
        $path = $file->store("attachments/task_{$task->id}", 'local');

        TaskAttachment::create([
    'task_id'       => $task->id,
    'user_id'       => Auth::id(),
    'filename'      => $file->getClientOriginalName(),
    'original_name' => $file->getClientOriginalName(),
    'path'          => $path,
    'mime_type'     => $file->getMimeType(),
    'size'          => $file->getSize(),
]);

        return back()->with('success', 'Fichier uploadé avec succès !');
    }

    // Télécharger fichier
    public function download(Task $task, TaskAttachment $attachment)
    {
        $this->checkAccess($task);

        if (!Storage::disk('local')->exists($attachment->path)) {
            return back()->with('error', 'Fichier introuvable.');
        }

        return Storage::disk('local')->download(
            $attachment->path,
            $attachment->filename
        );
    }

    // Supprimer fichier
    public function destroy(Task $task, TaskAttachment $attachment)
    {
        $this->checkAccess($task);

        // Seul l'auteur ou admin peut supprimer
        if ($attachment->user_id !== Auth::id()) {
            abort(403);
        }

        Storage::disk('local')->delete($attachment->path);
        $attachment->delete();

        return back()->with('success', 'Fichier supprimé.');
    }

    private function checkAccess(Task $task): void
    {
        $userId = Auth::id();
        $ok = $task->project->owner_id === $userId
            || $task->project->members()->where('user_id', $userId)->exists();
        if (!$ok) abort(403);
    }
}