<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GitHubController extends Controller
{
    
    public function redirect()
    {
        return Socialite::driver('github')->redirect();
    }

    
    public function callback()
    {
        try {
            $githubUser = Socialite::driver('github')->user();
        } catch (\Exception $e) {
            return redirect()->route('login')
                             ->with('error', 'Connexion GitHub échouée. Réessaie.');
        }

        
        $user = User::where('github_id', $githubUser->getId())->first();

        if (!$user) {
            $user = User::where('email', $githubUser->getEmail())->first();
        }

        if (!$user) {
            
            $role = User::count() === 0 ? 'admin' : 'member';

            $user = User::create([
                'name'         => $githubUser->getName() ?? $githubUser->getNickname(),
                'email'        => $githubUser->getEmail(),
                'github_id'    => $githubUser->getId(),
                'github_token' => $githubUser->token,
                'avatar'       => $githubUser->getAvatar(),
                'role'         => $role,
                'theme'        => 'light',
                'language'     => 'fr',
                'password'     => \Illuminate\Support\Facades\Hash::make(
                          \Illuminate\Support\Str::random(32)
                      ),
            ]);
        } else {
            
            $user->update([
                'github_id'    => $githubUser->getId(),
                'github_token' => $githubUser->token,
                'avatar'       => $githubUser->getAvatar(),
            ]);
        }

        Auth::login($user, remember: true);

        return redirect()->route('dashboard')
                         ->with('success', 'Connecté avec GitHub !');
    }
}