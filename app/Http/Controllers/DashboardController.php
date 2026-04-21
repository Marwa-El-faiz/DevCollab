<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Plus tard on passera les vrais projets ici
        // Pour l'instant on retourne juste la vue
        return view('dashboard');
    }
}