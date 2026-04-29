<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class TeamController extends Controller
{
    public function index()
    {
        // Tous les utilisateurs avec leur nombre de tâches
        $members = User::withCount([
            'tasks as tasks_count'
        ])->get();

        return view('team.index', compact('members'));
    }
}