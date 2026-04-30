@extends('layouts.app')
@section('title', 'Settings')
@section('content')

<h1 class="page-title">Settings</h1>
<p class="page-subtitle">Gérer ton compte et tes préférences</p>

<div style="display:grid; grid-template-columns:1fr 1fr; gap:20px; max-width:900px;">

    {{-- ── Profil ── --}}
    <div class="card" style="padding:24px;">
        <div style="display:flex; align-items:center; gap:10px; margin-bottom:20px; padding-bottom:16px; border-bottom:1px solid #f3f4f6;">
            <div style="width:36px; height:36px; border-radius:8px; background:#f3f4f6;
                        display:flex; align-items:center; justify-content:center;">
                <svg width="18" height="18" fill="none" stroke="#6b7280" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                    <circle cx="12" cy="7" r="4"/>
                </svg>
            </div>
            <div>
                <div style="font-size:14px; font-weight:600; color:#111827;">Profile</div>
                <div style="font-size:11px; color:#9ca3af;">Informations personnelles</div>
            </div>
        </div>

        <form method="POST" action="{{ route('settings.profile') }}">
            @csrf @method('PUT')

            <div class="form-group">
                <label class="form-label">Nom complet</label>
                <input type="text" name="name" class="form-input"
                       value="{{ old('name', Auth::user()->name) }}">
                @error('name')
                    <p style="color:#dc2626; font-size:11px; margin-top:3px;">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Adresse email</label>
                <input type="email" name="email" class="form-input"
                       value="{{ old('email', Auth::user()->email) }}">
                @error('email')
                    <p style="color:#dc2626; font-size:11px; margin-top:3px;">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Rôle</label>
                <input type="text" class="form-input"
                       value="{{ ucfirst(Auth::user()->role) }}" disabled
                       style="background:#f3f4f6; color:#9ca3af; cursor:not-allowed;">
            </div>

            <button type="submit" class="btn-primary" style="width:100%; justify-content:center;">
                Sauvegarder le profil
            </button>
        </form>
    </div>

    {{-- ── Sécurité ── --}}
    <div class="card" style="padding:24px;">
        <div style="display:flex; align-items:center; gap:10px; margin-bottom:20px; padding-bottom:16px; border-bottom:1px solid #f3f4f6;">
            <div style="width:36px; height:36px; border-radius:8px; background:#f3f4f6;
                        display:flex; align-items:center; justify-content:center;">
                <svg width="18" height="18" fill="none" stroke="#6b7280" stroke-width="2" viewBox="0 0 24 24">
                    <rect x="3" y="11" width="18" height="11" rx="2"/>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                </svg>
            </div>
            <div>
                <div style="font-size:14px; font-weight:600; color:#111827;">Security</div>
                <div style="font-size:11px; color:#9ca3af;">Changer ton mot de passe</div>
            </div>
        </div>

        <form method="POST" action="{{ route('settings.password') }}">
            @csrf @method('PUT')

            <div class="form-group">
                <label class="form-label">Mot de passe actuel</label>
                <input type="password" name="current_password" class="form-input" placeholder="••••••••">
            </div>

            <div class="form-group">
                <label class="form-label">Nouveau mot de passe</label>
                <input type="password" name="password" class="form-input" placeholder="Minimum 8 caractères">
            </div>

            <div class="form-group">
                <label class="form-label">Confirmer le mot de passe</label>
                <input type="password" name="password_confirmation" class="form-input" placeholder="Répéter le mot de passe">
                @error('password')
                    <p style="color:#dc2626; font-size:11px; margin-top:3px;">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="btn-primary" style="width:100%; justify-content:center;">
                Changer le mot de passe
            </button>
        </form>
    </div>

    {{-- ── Info compte ── --}}
    <div class="card" style="padding:24px; grid-column:1/-1;">
        <div style="display:flex; align-items:center; gap:10px; margin-bottom:20px; padding-bottom:16px; border-bottom:1px solid #f3f4f6;">
            <div style="width:36px; height:36px; border-radius:8px; background:#f3f4f6;
                        display:flex; align-items:center; justify-content:center;">
                <svg width="18" height="18" fill="none" stroke="#6b7280" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="12" y1="8" x2="12" y2="12"/>
                    <line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
            </div>
            <div>
                <div style="font-size:14px; font-weight:600; color:#111827;">Informations du compte</div>
                <div style="font-size:11px; color:#9ca3af;">Détails de ton compte DevCollab</div>
            </div>
        </div>

        <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:16px;">
            @foreach([
                ['Nom',         Auth::user()->name],
                ['Email',       Auth::user()->email],
                ['Rôle',        ucfirst(Auth::user()->role)],
                ['Membre depuis', Auth::user()->created_at->format('d/m/Y')],
                ['Projets',     \App\Models\Project::where('owner_id', Auth::id())->orWhereHas('members', fn($q) => $q->where('user_id', Auth::id()))->count() . ' projet(s)'],
                ['Tâches assignées', Auth::user()->tasks()->count() . ' tâche(s)'],
            ] as [$label, $value])
            <div style="padding:12px 16px; background:#f9fafb; border-radius:8px; border:1px solid #e5e7eb;">
                <div style="font-size:11px; color:#9ca3af; margin-bottom:4px;">{{ $label }}</div>
                <div style="font-size:13px; font-weight:500; color:#111827;">{{ $value }}</div>
            </div>
            @endforeach
        </div>
    </div>

</div>

@endsection