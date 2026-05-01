@extends('layouts.app')
@section('title', 'Settings')
@section('content')

<h1 class="page-title">Settings</h1>
<p class="page-subtitle">Gérer ton compte et tes préférences</p>

<div style="display:grid; grid-template-columns:1fr 1fr; gap:20px; max-width:900px;">

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
                <div style="font-size:14px; font-weight:600; color:var(--text-primary);">
                    Apparence
                </div>
                <div style="font-size:11px; color:var(--text-muted);">
                    Thème clair ou sombre
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('settings.theme') }}">
            @csrf
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">

                <label style="cursor:pointer;">
                    <input type="radio" name="theme" value="light"
                           {{ Auth::user()->theme === 'light' ? 'checked' : '' }}
                           style="display:none;" onchange="this.form.submit()">
                    <div style="border:2px solid {{ Auth::user()->theme === 'light' ? '#2d6a4f' : 'var(--border)' }};
                                border-radius:10px; padding:16px; text-align:center;
                                background:{{ Auth::user()->theme === 'light' ? '#f0fdf4' : 'var(--input-bg)' }};
                                transition:all 0.2s;">
                        <div style="font-size:22px; margin-bottom:6px;">☀️</div>
                        <p style="font-size:12px; font-weight:500; color:var(--text-primary);">
                            Clair
                        </p>
                    </div>
                </label>

                <label style="cursor:pointer;">
                    <input type="radio" name="theme" value="dark"
                           {{ Auth::user()->theme === 'dark' ? 'checked' : '' }}
                           style="display:none;" onchange="this.form.submit()">
                    <div style="border:2px solid {{ Auth::user()->theme === 'dark' ? '#2d6a4f' : 'var(--border)' }};
                                border-radius:10px; padding:16px; text-align:center;
                                background:{{ Auth::user()->theme === 'dark' ? '#f0fdf4' : 'var(--input-bg)' }};
                                transition:all 0.2s;">
                        <div style="font-size:22px; margin-bottom:6px;">🌙</div>
                        <p style="font-size:12px; font-weight:500; color:var(--text-primary);">
                            Sombre
                        </p>
                    </div>
                </label>

            </div>
        </form>
    </div>

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
                <div style="font-size:14px; font-weight:600; color:var(--text-primary);">
                    Langue
                </div>
                <div style="font-size:11px; color:var(--text-muted);">
                    Français ou English
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('settings.language') }}">
            @csrf
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">

                <label style="cursor:pointer;">
                    <input type="radio" name="language" value="fr"
                           {{ Auth::user()->language === 'fr' ? 'checked' : '' }}
                           style="display:none;" onchange="this.form.submit()">
                    <div style="border:2px solid {{ Auth::user()->language === 'fr' ? '#2d6a4f' : 'var(--border)' }};
                                border-radius:10px; padding:16px; text-align:center;
                                background:{{ Auth::user()->language === 'fr' ? '#f0fdf4' : 'var(--input-bg)' }};
                                transition:all 0.2s;">
                        <div style="font-size:22px; margin-bottom:6px;">🇫🇷</div>
                        <p style="font-size:12px; font-weight:500; color:var(--text-primary);">
                            Français
                        </p>
                    </div>
                </label>

                <label style="cursor:pointer;">
                    <input type="radio" name="language" value="en"
                           {{ Auth::user()->language === 'en' ? 'checked' : '' }}
                           style="display:none;" onchange="this.form.submit()">
                    <div style="border:2px solid {{ Auth::user()->language === 'en' ? '#2d6a4f' : 'var(--border)' }};
                                border-radius:10px; padding:16px; text-align:center;
                                background:{{ Auth::user()->language === 'en' ? '#f0fdf4' : 'var(--input-bg)' }};
                                transition:all 0.2s;">
                        <div style="font-size:22px; margin-bottom:6px;">🇬🇧</div>
                        <p style="font-size:12px; font-weight:500; color:var(--text-primary);">
                            English
                        </p>
                    </div>
                </label>

            </div>
        </form>
    </div>

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
                       style="color:var(--text-muted); cursor:not-allowed;">
            </div>

            <button type="submit" class="btn-primary" style="width:100%; justify-content:center;">
                Sauvegarder le profil
            </button>
        </form>
    </div>

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
                <div style="font-size:11px; color:var(--text-muted);">Changer ton mot de passe</div>
            </div>
        </div>

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
    </div>

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
            @foreach([
                ['Nom',              Auth::user()->name],
                ['Email',            Auth::user()->email],
                ['Rôle',             ucfirst(Auth::user()->role)],
                ['Membre depuis',    Auth::user()->created_at->format('d/m/Y')],
                ['Projets',          \App\Models\Project::where('owner_id', Auth::id())
                                        ->orWhereHas('members', fn($q) => $q->where('user_id', Auth::id()))
                                        ->count() . ' projet(s)'],
                ['Tâches assignées', Auth::user()->tasks()->count() . ' tâche(s)'],
            ] as [$label, $value])
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
    <div class="card" style="padding:24px; grid-column:1/-1;
         border:1px solid #fecaca;">
        <div style="display:flex; align-items:center; gap:10px;
                    margin-bottom:20px; padding-bottom:16px;
                    border-bottom:1px solid #fecaca;">
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
                <div style="font-size:14px; font-weight:600; color:#dc2626;">
                    Zone de danger
                </div>
                <div style="font-size:11px; color:#9ca3af;">
                    Actions irréversibles
                </div>
            </div>
        </div>

        <div style="display:flex; align-items:center;
                    justify-content:space-between; flex-wrap:wrap; gap:16px;">
            <div>
                <p style="font-size:13px; font-weight:500;
                          color:var(--text); margin-bottom:4px;">
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

    {{-- Modal confirmation suppression --}}
    <div id="modal-delete"
         style="display:none; position:fixed; inset:0;
                background:rgba(0,0,0,0.5); z-index:1000;
                align-items:center; justify-content:center;">
        <div style="background:var(--card); border-radius:12px;
                    padding:32px; max-width:420px; width:90%;
                    border:1px solid var(--border);">
            <h3 style="font-size:16px; font-weight:600;
                       color:#dc2626; margin-bottom:8px;">
                Confirmer la suppression
            </h3>
            <p style="font-size:13px; color:var(--text2); margin-bottom:20px;">
                Cette action est irréversible. Entre ton mot de passe pour confirmer.
            </p>
            <form method="POST" action="{{ route('settings.account.delete') }}">
                @csrf
                @method('DELETE')
                <div class="form-group">
                    <label class="form-label">Mot de passe</label>
                    <input type="password" name="password"
                           class="form-input" placeholder="••••••••" required>
                </div>
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
