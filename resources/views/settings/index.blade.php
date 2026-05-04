@extends('layouts.app')
@section('title', 'Settings')
@section('content')

@php
    $user     = Auth::user();
    $isOAuth  = is_null($user->password);
    $provider = $user->github_id ? 'GitHub' : ($user->google_id ? 'Google' : null);
@endphp

<h1 class="page-title">Paramètres</h1>
<p class="page-subtitle">Gérer ton compte et tes préférences</p>

<div style="display:grid; grid-template-columns:1fr 1fr; gap:20px;">

    {{-- ── APPARENCE ── --}}
    <div class="card" style="padding:24px;">
        <div style="display:flex; align-items:center; gap:10px; margin-bottom:20px;
                    padding-bottom:16px; border-bottom:1px solid var(--border);">
            <div style="width:36px; height:36px; border-radius:8px;
                        background:var(--input-bg); display:flex;
                        align-items:center; justify-content:center;">
                <svg width="18" height="18" fill="none" stroke="#6b7280"
                     stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="5"/>
                    <line x1="12" y1="1" x2="12" y2="3"/>
                    <line x1="12" y1="21" x2="12" y2="23"/>
                    <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/>
                    <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/>
                    <line x1="1" y1="12" x2="3" y2="12"/>
                    <line x1="21" y1="12" x2="23" y2="12"/>
                    <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/>
                    <line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/>
                </svg>
            </div>
            <div>
                <div style="font-size:14px; font-weight:600; color:var(--text-primary);">Apparence</div>
                <div style="font-size:11px; color:var(--text-muted);">Thème clair ou sombre</div>
            </div>
        </div>

        <form method="POST" action="{{ route('settings.theme') }}">
            @csrf
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">

                {{-- Clair --}}
                <label style="cursor:pointer;">
                    <input type="radio" name="theme" value="light"
                           {{ $user->theme === 'light' ? 'checked' : '' }}
                           style="display:none;" onchange="this.form.submit()">
                    <div style="border:2px solid {{ $user->theme === 'light' ? '#2d6a4f' : 'var(--border)' }};
                                border-radius:10px; padding:16px; text-align:center;
                                background:#ffffff;
                                box-shadow:{{ $user->theme === 'light' ? '0 0 0 3px #2d6a4f20' : 'none' }};
                                transition:all 0.2s;">
                        <div style="font-size:22px; margin-bottom:6px;">☀️</div>
                        <p style="font-size:12px; font-weight:500; color:#111827;">Clair</p>
                        @if($user->theme === 'light')
                        <div style="width:16px; height:16px; border-radius:50%;
                                    background:#2d6a4f; margin:6px auto 0;
                                    display:flex; align-items:center; justify-content:center;">
                            <svg width="9" height="9" fill="none" stroke="#fff" stroke-width="2.5" viewBox="0 0 24 24">
                                <polyline points="20 6 9 17 4 12"/>
                            </svg>
                        </div>
                        @endif
                    </div>
                </label>

                {{-- Sombre --}}
                <label style="cursor:pointer;">
                    <input type="radio" name="theme" value="dark"
                           {{ $user->theme === 'dark' ? 'checked' : '' }}
                           style="display:none;" onchange="this.form.submit()">
                    <div style="border:2px solid {{ $user->theme === 'dark' ? '#2d6a4f' : 'var(--border)' }};
                                border-radius:10px; padding:16px; text-align:center;
                                background:#ffffff;
                                box-shadow:{{ $user->theme === 'dark' ? '0 0 0 3px #2d6a4f20' : 'none' }};
                                transition:all 0.2s;">
                        <div style="font-size:22px; margin-bottom:6px;">🌙</div>
                        <p style="font-size:12px; font-weight:500; color:#111827;">Sombre</p>
                        @if($user->theme === 'dark')
                        <div style="width:16px; height:16px; border-radius:50%;
                                    background:#2d6a4f; margin:6px auto 0;
                                    display:flex; align-items:center; justify-content:center;">
                            <svg width="9" height="9" fill="none" stroke="#fff" stroke-width="2.5" viewBox="0 0 24 24">
                                <polyline points="20 6 9 17 4 12"/>
                            </svg>
                        </div>
                        @endif
                    </div>
                </label>

            </div>
        </form>
    </div>

    {{-- ── LANGUE ── --}}
    <div class="card" style="padding:24px;">
        <div style="display:flex; align-items:center; gap:10px; margin-bottom:20px;
                    padding-bottom:16px; border-bottom:1px solid var(--border);">
            <div style="width:36px; height:36px; border-radius:8px;
                        background:var(--input-bg); display:flex;
                        align-items:center; justify-content:center;">
                <svg width="18" height="18" fill="none" stroke="#6b7280"
                     stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="2" y1="12" x2="22" y2="12"/>
                    <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>
                </svg>
            </div>
            <div>
                <div style="font-size:14px; font-weight:600; color:var(--text-primary);">Langue</div>
                <div style="font-size:11px; color:var(--text-muted);">Français ou English</div>
            </div>
        </div>

        <form method="POST" action="{{ route('settings.language') }}">
            @csrf
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">

                {{-- Français --}}
                <label style="cursor:pointer;">
                    <input type="radio" name="language" value="fr"
                           {{ $user->language === 'fr' ? 'checked' : '' }}
                           style="display:none;" onchange="this.form.submit()">
                    <div style="border:2px solid {{ $user->language === 'fr' ? '#2d6a4f' : 'var(--border)' }};
                                border-radius:10px; padding:16px; text-align:center;
                                background:#ffffff;
                                box-shadow:{{ $user->language === 'fr' ? '0 0 0 3px #2d6a4f20' : 'none' }};
                                transition:all 0.2s;">
                        <div style="font-size:22px; margin-bottom:6px;">🇫🇷</div>
                        <p style="font-size:12px; font-weight:500; color:#111827;">Français</p>
                        @if($user->language === 'fr')
                        <div style="width:16px; height:16px; border-radius:50%;
                                    background:#2d6a4f; margin:6px auto 0;
                                    display:flex; align-items:center; justify-content:center;">
                            <svg width="9" height="9" fill="none" stroke="#fff" stroke-width="2.5" viewBox="0 0 24 24">
                                <polyline points="20 6 9 17 4 12"/>
                            </svg>
                        </div>
                        @endif
                    </div>
                </label>

                {{-- English --}}
                <label style="cursor:pointer;">
                    <input type="radio" name="language" value="en"
                           {{ $user->language === 'en' ? 'checked' : '' }}
                           style="display:none;" onchange="this.form.submit()">
                    <div style="border:2px solid {{ $user->language === 'en' ? '#2d6a4f' : 'var(--border)' }};
                                border-radius:10px; padding:16px; text-align:center;
                                background:#ffffff;
                                box-shadow:{{ $user->language === 'en' ? '0 0 0 3px #2d6a4f20' : 'none' }};
                                transition:all 0.2s;">
                        <div style="font-size:22px; margin-bottom:6px;">🇬🇧</div>
                        <p style="font-size:12px; font-weight:500; color:#111827;">English</p>
                        @if($user->language === 'en')
                        <div style="width:16px; height:16px; border-radius:50%;
                                    background:#2d6a4f; margin:6px auto 0;
                                    display:flex; align-items:center; justify-content:center;">
                            <svg width="9" height="9" fill="none" stroke="#fff" stroke-width="2.5" viewBox="0 0 24 24">
                                <polyline points="20 6 9 17 4 12"/>
                            </svg>
                        </div>
                        @endif
                    </div>
                </label>

            </div>
        </form>
    </div>

    {{-- ── PROFIL ── --}}
    <div class="card" style="padding:24px;">
        <div style="display:flex; align-items:center; gap:10px; margin-bottom:20px;
                    padding-bottom:16px; border-bottom:1px solid var(--border);">
            <div style="width:36px; height:36px; border-radius:8px;
                        background:var(--input-bg); display:flex;
                        align-items:center; justify-content:center;">
                <svg width="18" height="18" fill="none" stroke="#6b7280"
                     stroke-width="2" viewBox="0 0 24 24">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                    <circle cx="12" cy="7" r="4"/>
                </svg>
            </div>
            <div>
                <div style="font-size:14px; font-weight:600; color:var(--text-primary);">Profile</div>
                <div style="font-size:11px; color:var(--text-muted);">Informations personnelles</div>
            </div>
        </div>

        @if($isOAuth)
        <div style="display:flex; align-items:center; gap:8px; padding:10px 14px;
                    background:var(--input-bg); border:1px solid var(--border);
                    border-radius:8px; margin-bottom:16px;">
            @if($provider === 'GitHub')
            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"
                 style="color:var(--text-primary);">
                <path d="M12 0C5.37 0 0 5.37 0 12c0 5.31 3.435 9.795 8.205 11.385.6.105.825-.255.825-.57 0-.285-.015-1.23-.015-2.235-3.015.555-3.795-.735-4.035-1.41-.135-.345-.72-1.41-1.23-1.695-.42-.225-1.02-.78-.015-.795.945-.015 1.62.87 1.845 1.23 1.08 1.815 2.805 1.305 3.495.99.105-.78.42-1.305.765-1.605-2.67-.3-5.46-1.335-5.46-5.925 0-1.305.465-2.385 1.23-3.225-.12-.3-.54-1.53.12-3.18 0 0 1.005-.315 3.3 1.23.96-.27 1.98-.405 3-.405s2.04.135 3 .405c2.295-1.56 3.3-1.23 3.3-1.23.66 1.65.24 2.88.12 3.18.765.84 1.23 1.905 1.23 3.225 0 4.605-2.805 5.625-5.475 5.925.435.375.81 1.095.81 2.22 0 1.605-.015 2.895-.015 3.3 0 .315.225.69.825.57A12.02 12.02 0 0 0 24 12c0-6.63-5.37-12-12-12z"/>
            </svg>
            @else
            <svg width="16" height="16" viewBox="0 0 24 24">
                <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
            </svg>
            @endif
            <span style="font-size:12px; color:var(--text2);">
                Connecté via <strong style="color:var(--text-primary);">{{ $provider }}</strong>
            </span>
        </div>
        @endif

        <form method="POST" action="{{ route('settings.profile') }}">
            @csrf @method('PUT')

            <div class="form-group">
                <label class="form-label">Nom complet</label>
                <input type="text" name="name" class="form-input"
                       value="{{ old('name', $user->name) }}">
                @error('name')
                    <p style="color:#dc2626; font-size:11px; margin-top:3px;">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Adresse email</label>
                <input type="email" name="email" class="form-input"
                       value="{{ old('email', $user->email) }}">
                @error('email')
                    <p style="color:#dc2626; font-size:11px; margin-top:3px;">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Rôle</label>
                <input type="text" class="form-input"
                       value="{{ ucfirst($user->role) }}" disabled
                       style="color:var(--text-muted); cursor:not-allowed;">
            </div>

            <button type="submit" class="btn-primary" style="width:100%; justify-content:center;">
                Sauvegarder le profil
            </button>
        </form>
    </div>

    {{-- ── SÉCURITÉ ── --}}
    <div class="card" style="padding:24px;">
        <div style="display:flex; align-items:center; gap:10px; margin-bottom:20px;
                    padding-bottom:16px; border-bottom:1px solid var(--border);">
            <div style="width:36px; height:36px; border-radius:8px;
                        background:var(--input-bg); display:flex;
                        align-items:center; justify-content:center;">
                <svg width="18" height="18" fill="none" stroke="#6b7280"
                     stroke-width="2" viewBox="0 0 24 24">
                    <rect x="3" y="11" width="18" height="11" rx="2"/>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                </svg>
            </div>
            <div>
                <div style="font-size:14px; font-weight:600; color:var(--text-primary);">Security</div>
                <div style="font-size:11px; color:var(--text-muted);">
                    {{ $isOAuth ? 'Sécurité du compte' : 'Changer ton mot de passe' }}
                </div>
            </div>
        </div>

        @if($isOAuth)
            <div style="text-align:center; padding:28px 16px;">
                <div style="width:48px; height:48px; border-radius:50%;
                            background:var(--input-bg); display:flex;
                            align-items:center; justify-content:center;
                            margin:0 auto 14px;">
                    <svg width="22" height="22" fill="none" stroke="#6b7280"
                         stroke-width="1.5" viewBox="0 0 24 24">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                    </svg>
                </div>
                <p style="font-size:13px; font-weight:500; color:var(--text-primary); margin-bottom:6px;">
                    Compte sécurisé via {{ $provider }}
                </p>
                <p style="font-size:12px; color:var(--text2); line-height:1.5;">
                    Ton mot de passe est géré par {{ $provider }}.<br>
                    Tu peux le modifier depuis ton compte {{ $provider }}.
                </p>
                <a href="{{ $provider === 'GitHub' ? 'https://github.com/settings/security' : 'https://myaccount.google.com/security' }}"
                   target="_blank"
                   style="display:inline-flex; align-items:center; gap:6px;
                          margin-top:16px; font-size:12px; color:#2d6a4f;
                          text-decoration:none; font-weight:500;">
                    Gérer la sécurité {{ $provider }}
                    <svg width="12" height="12" fill="none" stroke="currentColor"
                         stroke-width="2" viewBox="0 0 24 24">
                        <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/>
                        <polyline points="15 3 21 3 21 9"/>
                        <line x1="10" y1="14" x2="21" y2="3"/>
                    </svg>
                </a>
            </div>
        @else
            <form method="POST" action="{{ route('settings.password') }}">
                @csrf @method('PUT')

                <div class="form-group">
                    <label class="form-label">Mot de passe actuel</label>
                    <input type="password" name="current_password"
                           class="form-input" placeholder="••••••••">
                    @error('current_password')
                        <p style="color:#dc2626; font-size:11px; margin-top:3px;">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Nouveau mot de passe</label>
                    <input type="password" name="password"
                           class="form-input" placeholder="Minimum 8 caractères">
                </div>

                <div class="form-group">
                    <label class="form-label">Confirmer le mot de passe</label>
                    <input type="password" name="password_confirmation"
                           class="form-input" placeholder="Répéter le mot de passe">
                    @error('password')
                        <p style="color:#dc2626; font-size:11px; margin-top:3px;">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="btn-primary" style="width:100%; justify-content:center;">
                    Changer le mot de passe
                </button>
            </form>
        @endif
    </div>

    {{-- ── INFO COMPTE ── --}}
    <div class="card" style="padding:24px; grid-column:1/-1;">
        <div style="display:flex; align-items:center; gap:10px; margin-bottom:20px;
                    padding-bottom:16px; border-bottom:1px solid var(--border);">
            <div style="width:36px; height:36px; border-radius:8px;
                        background:var(--input-bg); display:flex;
                        align-items:center; justify-content:center;">
                <svg width="18" height="18" fill="none" stroke="#6b7280"
                     stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="12" y1="8" x2="12" y2="12"/>
                    <line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
            </div>
            <div>
                <div style="font-size:14px; font-weight:600; color:var(--text-primary);">
                    Informations du compte
                </div>
                <div style="font-size:11px; color:var(--text-muted);">
                    Détails de ton compte DevCollab
                </div>
            </div>
        </div>

        <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:16px;">
            @php
                $infos = [
                    ['Nom',              $user->name],
                    ['Email',            $user->email],
                    ['Rôle',             ucfirst($user->role)],
                    ['Connexion',        $isOAuth ? $provider : 'Email'],
                    ['Membre depuis',    $user->created_at->format('d/m/Y')],
                    ['Tâches assignées', $user->tasks()->count() . ' tâche(s)'],
                ];
            @endphp
            @foreach($infos as [$label, $value])
            <div style="padding:12px 16px; background:var(--input-bg);
                        border-radius:8px; border:1px solid var(--border);">
                <div style="font-size:11px; color:var(--text-muted); margin-bottom:4px;">
                    {{ $label }}
                </div>
                <div style="font-size:13px; font-weight:500; color:var(--text-primary);">
                    {{ $value }}
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- ── DANGER ZONE ── --}}
    <div class="card" style="padding:24px; grid-column:1/-1; border-color:#fecaca;">
        <div style="display:flex; align-items:center; gap:10px; margin-bottom:20px;
                    padding-bottom:16px; border-bottom:1px solid #fecaca;">
            <div style="width:36px; height:36px; border-radius:8px;
                        background:#fee2e2; display:flex; align-items:center;
                        justify-content:center;">
                <svg width="18" height="18" fill="none" stroke="#dc2626"
                     stroke-width="2" viewBox="0 0 24 24">
                    <polyline points="3 6 5 6 21 6"/>
                    <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                    <path d="M10 11v6M14 11v6"/>
                </svg>
            </div>
            <div>
                <div style="font-size:14px; font-weight:600; color:#dc2626;">Zone de danger</div>
                <div style="font-size:11px; color:#9ca3af;">Actions irréversibles</div>
            </div>
        </div>

        <div style="display:flex; align-items:center;
                    justify-content:space-between; flex-wrap:wrap; gap:16px;">
            <div>
                <p style="font-size:13px; font-weight:500; color:var(--text-primary); margin-bottom:4px;">
                    Supprimer mon compte
                </p>
                <p style="font-size:12px; color:var(--text2);">
                    Toutes tes données seront supprimées définitivement.
                </p>
            </div>
            <button onclick="document.getElementById('modal-delete').style.display='flex'"
                    style="background:#dc2626; color:#fff; border:none;
                           padding:8px 16px; border-radius:7px; font-size:13px;
                           font-weight:500; cursor:pointer;">
                Supprimer mon compte
            </button>
        </div>
    </div>

    {{-- ── MODAL SUPPRESSION ── --}}
    <div id="modal-delete"
         style="display:none; position:fixed; inset:0;
                background:rgba(0,0,0,0.6); z-index:9999;
                align-items:center; justify-content:center; padding:20px;">
        <div style="background:var(--card); border-radius:12px;
                    padding:32px; max-width:420px; width:90%;
                    border:1px solid var(--border);">
            <h3 style="font-size:16px; font-weight:600; color:#dc2626; margin-bottom:8px;">
                Confirmer la suppression
            </h3>
            <p style="font-size:13px; color:var(--text2); margin-bottom:20px;">
                Cette action est irréversible et supprimera toutes tes données.
            </p>

            <form method="POST" action="{{ route('settings.account.delete') }}">
                @csrf
                @method('DELETE')

                @if($isOAuth)
                <div style="background:#fef3c7; border:1px solid #fcd34d;
                            border-radius:8px; padding:12px 16px;
                            margin-bottom:16px; font-size:13px; color:#92400e;
                            display:flex; align-items:center; gap:8px;">
                    <svg width="16" height="16" fill="none" stroke="#92400e"
                         stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10"/>
                        <line x1="12" y1="8" x2="12" y2="12"/>
                        <line x1="12" y1="16" x2="12.01" y2="16"/>
                    </svg>
                    Connecté via <strong>{{ $provider }}</strong> — aucun mot de passe requis.
                </div>
                @else
                <div class="form-group">
                    <label class="form-label">Entre ton mot de passe pour confirmer</label>
                    <input type="password" name="password"
                           class="form-input" placeholder="••••••••" required>
                </div>
                @endif

                <div style="display:flex; gap:12px; margin-top:20px;">
                    <button type="submit"
                            style="background:#dc2626; color:#fff; border:none;
                                   padding:8px 16px; border-radius:7px;
                                   font-size:13px; cursor:pointer; flex:1;">
                        Supprimer définitivement
                    </button>
                    <button type="button"
                            onclick="document.getElementById('modal-delete').style.display='none'"
                            class="btn-secondary" style="flex:1; justify-content:center;">
                        Annuler
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

@endsection