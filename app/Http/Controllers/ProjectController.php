<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    // ── Dashboard — tous les projets de l'utilisateur ──
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

    // ── Formulaire création — ADMIN ONLY ──
    public function create()
    {
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('dashboard')
                ->with('error', 'Seul un administrateur peut créer un projet.');
        }

        $users = User::where('id', '!=', Auth::id())->get();
        return view('projects.create', compact('users'));
    }

    // ── Sauvegarder — ADMIN ONLY ──
    public function store(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('dashboard')
                ->with('error', 'Seul un administrateur peut créer un projet.');
        }

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

    // ── Voir un projet — membres ET admin ──
    public function show(Project $project)
    {
        $this->authorizeAccess($project);
        $project->load(['members', 'tasks.assignee', 'tasks.attachments.user', 'tasks.comments.user']);
        return view('projects.show', compact('project'));
    }

    // ── Formulaire édition — ADMIN PROJET ONLY ──
    public function edit(Project $project)
    {
        $this->authorizeAdmin($project);
        $users          = User::where('id', '!=', Auth::id())->get();
        $currentMembers = $project->members->pluck('id')->toArray();
        return view('projects.edit', compact('project', 'users', 'currentMembers'));
    }

    // ── Mettre à jour — ADMIN PROJET ONLY ──
    public function update(Request $request, Project $project)
    {
        $this->authorizeAdmin($project);

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

    // ── Supprimer — ADMIN GLOBAL ONLY ──
    public function destroy(Project $project)
    {
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('dashboard')
                ->with('error', 'Seul un administrateur peut supprimer un projet.');
        }
        $project->delete();
        return redirect()->route('dashboard')->with('success', 'Projet supprimé.');
    }

    // ── Helpers ──
    private function authorizeAccess(Project $project): void
    {
        $userId = Auth::id();
        $hasAccess = $project->owner_id === $userId
            || $project->members()->where('user_id', $userId)->exists();
        if (!$hasAccess) abort(403, 'Accès refusé.');
    }

    private function authorizeAdmin(Project $project): void
    {
        if (!$project->isAdmin(Auth::id())) {
            abort(403, 'Seul un administrateur du projet peut effectuer cette action.');
        }
    }
}