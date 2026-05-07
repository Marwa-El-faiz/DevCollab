<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeamController extends Controller
{
    // ── Liste équipe — TOUS ──
    public function index()
    {
        $members = User::withCount([
            'tasks as tasks_count',
            'tasks as done_count' => fn($q) => $q->where('status', 'done'),
        ])->get();

        return view('team.index', compact('members'));
    }

    // ── Voir profil d'un membre — TOUS ──
    public function show(User $user)
    {
        $user->loadCount(['tasks', 'tasks as done_count' => fn($q) => $q->where('status', 'done')]);

        $projects = $user->projects()->with('tasks')->get();

        return view('team.show', compact('user', 'projects'));
    }

    // ── Modifier rôle d'un membre — ADMIN ONLY ──
    public function updateRole(Request $request, User $user)
    {
        if (!Auth::user()->isAdmin()) {
            return redirect()->back()->with('error', 'Accès réservé aux administrateurs.');
        }

        // Empêcher de changer son propre rôle
        if ($user->id === Auth::id()) {
            return redirect()->back()->with('error', 'Vous ne pouvez pas modifier votre propre rôle.');
        }

        $request->validate([
            'role' => 'required|in:admin,member',
        ]);

        $user->update(['role' => $request->role]);

        return redirect()->back()
            ->with('success', "Rôle de {$user->name} mis à jour en " . ucfirst($request->role) . " !");
    }

    // ── Modifier compétences d'un membre — ADMIN ONLY ──
    public function updateSkills(Request $request, User $user)
    {
        if (!Auth::user()->isAdmin()) {
            return redirect()->back()->with('error', 'Accès réservé aux administrateurs.');
        }

        $request->validate([
            'skills'    => 'nullable|string|max:500',
            'job_title' => 'nullable|string|max:100',
            'bio'       => 'nullable|string|max:500',
        ]);

        $user->update([
            'skills'    => $request->skills,
            'job_title' => $request->job_title,
            'bio'       => $request->bio,
        ]);

        return redirect()->back()
            ->with('success', "Profil de {$user->name} mis à jour !");
    }

    // ── Supprimer un membre — ADMIN ONLY ──
    public function destroy(User $user)
    {
        if (!Auth::user()->isAdmin()) {
            return redirect()->back()->with('error', 'Accès réservé aux administrateurs.');
        }

        // Empêcher de se supprimer soi-même
        if ($user->id === Auth::id()) {
            return redirect()->back()->with('error', 'Vous ne pouvez pas supprimer votre propre compte depuis ici.');
        }

        // Empêcher de supprimer un autre admin
        if ($user->isAdmin()) {
            return redirect()->back()->with('error', 'Vous ne pouvez pas supprimer un autre administrateur.');
        }

        $userName = $user->name;
        $user->delete();

        return redirect()->route('team.index')
            ->with('success', "{$userName} a été supprimé de l'équipe.");
    }
}