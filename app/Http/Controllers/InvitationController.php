<?php

namespace App\Http\Controllers;

use App\Mail\InvitationMail;
use App\Models\Invitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class InvitationController extends Controller
{
    // Envoyer une invitation
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email',
            'role'  => 'required|in:admin,member',
        ]);

        // Vérifier si invitation déjà envoyée
        $existing = Invitation::where('email', $request->email)
                               ->where('used', false)
                               ->where('expires_at', '>', now())
                               ->first();

        if ($existing) {
            return back()->with('error', 'Une invitation a déjà été envoyée à cet email.');
        }

        $invitation = Invitation::create([
            'invited_by' => Auth::id(),
            'email'      => $request->email,
            'token'      => Str::random(64),
            'role'       => $request->role,
            'expires_at' => now()->addDays(7),
        ]);

        Mail::to($request->email)->send(new InvitationMail($invitation));

        return back()->with('success', "Invitation envoyée à {$request->email} !");
    }

    // Vérifier le token à l'inscription
    public function verify(string $token)
    {
        $invitation = Invitation::where('token', $token)
                                 ->where('used', false)
                                 ->where('expires_at', '>', now())
                                 ->first();

        if (!$invitation) {
            return redirect()->route('register')
                             ->with('error', 'Invitation invalide ou expirée.');
        }

        return redirect()->route('register', [
            'token' => $token,
            'email' => $invitation->email,
        ]);
    }
}