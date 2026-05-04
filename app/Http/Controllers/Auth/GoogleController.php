<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
{
    try {
        $googleUser = Socialite::driver('google')->user();
    } catch (\Exception $e) {
        return redirect()->route('login')
                         ->with('error', 'Connexion Google échouée.');
    }

    $user = User::where('google_id', $googleUser->getId())->first()
         ?? User::where('email', $googleUser->getEmail())->first();

    if (!$user) {
        $user = User::create([
            'name'      => $googleUser->getName(),
            'email'     => $googleUser->getEmail(),
            'google_id' => $googleUser->getId(),  // ← crucial
            'avatar'    => $googleUser->getAvatar(),
            'role'      => 'member',
            'theme'     => 'light',
            'language'  => 'fr',
            'password'  => null,
        ]);
    } else {
        $user->update([
            'google_id' => $googleUser->getId(),  // ← met à jour si manquant
            'avatar'    => $googleUser->getAvatar(),
        ]);
    }

    Auth::login($user, remember: true);
    return redirect()->route('dashboard');
}}