<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SettingsController extends Controller
{
    public function index()
    {
        return view('settings.index');
    }

    public function updateTheme(Request $request)
    {
        $request->validate([
            'theme' => 'required|in:light,dark',
        ]);

        Auth::user()->update(['theme' => $request->theme]);

        return back()->with('success', __('messages.theme_updated'));
    }

    public function updateLanguage(Request $request)
    {
        $request->validate([
            'language' => 'required|in:fr,en',
        ]);

        Auth::user()->update(['language' => $request->language]);

        return back()->with('success', __('messages.language_updated'));
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . Auth::id(),
        ]);

        Auth::user()->update([
            'name'  => $request->name,
            'email' => $request->email,
        ]);

        return back()->with('success', __('messages.profile_updated'));
    }

   public function updatePassword(Request $request)
{
    // Compte OAuth sans mot de passe → ignorer
    if (is_null(Auth::user()->password)) {
        return back()->with('error', 'Impossible de changer le mot de passe d\'un compte OAuth.');
    }

    $request->validate([
        'current_password' => 'required',
        'password'         => 'required|min:8|confirmed',
    ], [
        'current_password.required' => 'Le mot de passe actuel est requis.',
        'password.required'         => 'Le nouveau mot de passe est requis.',
        'password.min'              => 'Minimum 8 caractères.',
        'password.confirmed'        => 'Les mots de passe ne correspondent pas.',
    ]);

    if (!Hash::check($request->current_password, Auth::user()->password)) {
        return back()->with('error', 'Mot de passe actuel incorrect.');
    }

    Auth::user()->update([
        'password' => Hash::make($request->password),
    ]);

    return back()->with('success', 'Mot de passe mis à jour !');
}
    public function deleteAccount(Request $request)
{
    $request->validate([
        'password' => 'required',
    ]);

    $user = Auth::user();

    if (!Hash::check($request->password, $user->password)) {
        return back()->with('error', 'Mot de passe incorrect.');
    }

    Auth::logout();
    $user->delete();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('login')
                     ->with('success', 'Compte supprimé avec succès.');
}

}