<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    // Dashboard — vue résumé
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $projects = Project::where('owner_id', $user->id)
            ->orWhereHas('members', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->withCount('tasks')
            ->with(['members', 'tasks'])
            ->latest()
            ->get();

        return view('projects.index', compact('projects'));
    }

    public function create()
    {
        $users = User::where('id', '!=', Auth::id())->get();
        return view('projects.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'members'     => 'nullable|array',
            'members.*'   => 'exists:users,id',
        ]);

        $project = Project::create([
            'owner_id'    => Auth::id(),
            'name'        => $request->name,
            'description' => $request->description,
            'status'      => 'active',
        ]);

        $project->members()->attach(Auth::id(), ['role' => 'admin']);

        if ($request->members) {
            foreach ($request->members as $memberId) {
                $project->members()->attach($memberId, ['role' => 'member']);
            }
        }

        return redirect()->route('dashboard')
                         ->with('success', 'Projet créé avec succès !');
    }

    public function show(Project $project)
    {
        $this->authorizeAccess($project);
        $project->load(['members', 'tasks.assignee', 'tasks.comments.user']);
        return view('projects.show', compact('project'));
    }

    public function edit(Project $project)
    {
        $this->authorizeAccess($project);
        $users          = User::where('id', '!=', Auth::id())->get();
        $currentMembers = $project->members->pluck('id')->toArray();
        return view('projects.edit', compact('project', 'users', 'currentMembers'));
    }

    public function update(Request $request, Project $project)
    {
        $this->authorizeAccess($project);

        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'status'      => 'required|in:active,completed,archived',
            'members'     => 'nullable|array',
            'members.*'   => 'exists:users,id',
        ]);

        $project->update([
            'name'        => $request->name,
            'description' => $request->description,
            'status'      => $request->status,
        ]);

        $syncData = [Auth::id() => ['role' => 'admin']];
        if ($request->members) {
            foreach ($request->members as $memberId) {
                $syncData[$memberId] = ['role' => 'member'];
            }
        }
        $project->members()->sync($syncData);

        return redirect()->route('dashboard')->with('success', 'Projet mis à jour !');
    }

    public function destroy(Project $project)
    {
        $this->authorizeAccess($project);
        $project->delete();
        return redirect()->route('dashboard')->with('success', 'Projet supprimé.');
    }

    private function authorizeAccess(Project $project)
    {
        $userId = Auth::id();
        $hasAccess = $project->owner_id === $userId
            || $project->members()->where('user_id', $userId)->exists();
        if (!$hasAccess) abort(403, 'Accès refusé.');
    }
}