<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DevCollab — Connexion</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('favicon.jpeg') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', -apple-system, sans-serif;
            min-height: 100vh;
            display: flex;
        }

        .auth-container { display: flex; width: 100%; min-height: 100vh; }

        /* ── Panneau gauche ── */
        .auth-left {
            width: 45%;
            background: #1a1d23;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 60px;
        }

        .auth-logo {
            font-size: 32px;
            font-weight: 800;
            color: #ffffff;
            margin-bottom: 40px;
            letter-spacing: -0.5px;
        }
        .auth-logo span { color: #2d6a4f; }

        .auth-left h2 {
            color: #ffffff;
            font-size: 26px;
            font-weight: 700;
            margin-bottom: 12px;
            text-align: center;
        }

        .auth-left p {
            color: #6b7280;
            font-size: 15px;
            text-align: center;
            line-height: 1.6;
        }

        .deco-stats {
            margin-top: 48px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            width: 100%;
            max-width: 280px;
        }

        .deco-stat {
            background: #2d3139;
            border-radius: 10px;
            padding: 16px;
            text-align: center;
        }
        .deco-stat .number { font-size: 22px; font-weight: 700; color: #ffffff; }
        .deco-stat .label  { font-size: 11px; color: #6b7280; margin-top: 2px; }

        /* ── Panneau droit ── */
        .auth-right {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 60px 40px;
            background: #ffffff;
        }

        .auth-form-box { width: 100%; max-width: 400px; }

        .auth-form-box h1 {
            font-size: 24px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 6px;
        }

        .auth-form-box .subtitle {
            color: #6b7280;
            font-size: 14px;
            margin-bottom: 28px;
        }

        /* ── Boutons OAuth ── */
        .oauth-buttons {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-bottom: 24px;
        }

        .btn-oauth {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            width: 100%;
            padding: 11px 16px;
            border: 1.5px solid #e5e7eb;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            color: #374151;
            background: #ffffff;
            text-decoration: none;
            transition: all 0.15s;
            cursor: pointer;
        }
        .btn-oauth:hover {
            background: #f9fafb;
            border-color: #9ca3af;
            color: #111827;
        }

        /* ── Divider ── */
        .divider {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 24px;
        }
        .divider-line { flex: 1; height: 1px; background: #e5e7eb; }
        .divider-text { font-size: 12px; color: #9ca3af; white-space: nowrap; }

        /* ── Formulaire ── */
        .form-group { margin-bottom: 18px; }

        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: 500;
            color: #374151;
            margin-bottom: 6px;
        }

        .form-group input {
            width: 100%;
            padding: 10px 14px;
            border: 1.5px solid #d1d5db;
            border-radius: 8px;
            font-size: 14px;
            color: #111827;
            outline: none;
            transition: border-color 0.15s, box-shadow 0.15s;
            background: #f9fafb;
        }
        .form-group input:focus {
            border-color: #2d6a4f;
            background: #ffffff;
            box-shadow: 0 0 0 3px rgba(45,106,79,0.1);
        }

        .form-error {
            color: #dc2626;
            font-size: 12px;
            margin-top: 4px;
        }

        .form-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .remember-label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: #6b7280;
            cursor: pointer;
        }
        .remember-label input[type="checkbox"] {
            width: 16px; height: 16px;
            accent-color: #2d6a4f;
            cursor: pointer;
        }

        .forgot-link {
            font-size: 13px;
            color: #2d6a4f;
            text-decoration: none;
            font-weight: 500;
        }
        .forgot-link:hover { text-decoration: underline; }

        .btn-submit {
            width: 100%;
            background: #2d6a4f;
            color: #ffffff;
            border: none;
            padding: 12px;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.15s;
        }
        .btn-submit:hover { background: #1b4332; }

        .auth-link {
            text-align: center;
            margin-top: 20px;
            font-size: 13px;
            color: #6b7280;
        }
        .auth-link a {
            color: #2d6a4f;
            font-weight: 500;
            text-decoration: none;
        }
        .auth-link a:hover { text-decoration: underline; }

        .alert-error {
            background: #fee2e2;
            border: 1px solid #fca5a5;
            color: #991b1b;
            padding: 10px 14px;
            border-radius: 8px;
            font-size: 13px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .alert-success {
            background: #d1fae5;
            border: 1px solid #6ee7b7;
            color: #065f46;
            padding: 10px 14px;
            border-radius: 8px;
            font-size: 13px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
    </style>
</head>
<body>

<div class="auth-container">

    {{-- ── Panneau gauche décoratif ── --}}
    <div class="auth-left">
        <div class="auth-logo">Dev<span>Collab</span></div>

        <h2>Bon retour<br>parmi nous 👋</h2>
        <p>Connectez-vous pour accéder<br>à vos projets et vos tâches.</p>

        <div class="deco-stats">
            <div class="deco-stat">
                <div class="number">+3</div>
                <div class="label">Projets actifs</div>
            </div>
            <div class="deco-stat">
                <div class="number">+54</div>
                <div class="label">Tâches totales</div>
            </div>
            <div class="deco-stat">
                <div class="number">+5</div>
                <div class="label">Membres</div>
            </div>
            <div class="deco-stat">
                <div class="number">+66%</div>
                <div class="label">Progression</div>
            </div>
        </div>
    </div>

    {{-- ── Panneau droit — Formulaire ── --}}
    <div class="auth-right">
        <div class="auth-form-box">

            <h1>Se connecter</h1>
            <p class="subtitle">Entrez vos identifiants pour continuer</p>

            {{-- Messages flash --}}
            @if(session('error'))
            <div class="alert-error">
                <svg width="15" height="15" fill="none" stroke="currentColor"
                     stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="12" y1="8" x2="12" y2="12"/>
                    <line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
                {{ session('error') }}
            </div>
            @endif

            @if(session('success'))
            <div class="alert-success">
                <svg width="15" height="15" fill="none" stroke="currentColor"
                     stroke-width="2.5" viewBox="0 0 24 24">
                    <polyline points="20 6 9 17 4 12"/>
                </svg>
                {{ session('success') }}
            </div>
            @endif

            @if ($errors->any())
            <div class="alert-error">
                <svg width="15" height="15" fill="none" stroke="currentColor"
                     stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="12" y1="8" x2="12" y2="12"/>
                    <line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
                Email ou mot de passe incorrect.
            </div>
            @endif

            {{-- ════ BOUTONS OAUTH (Google + GitHub) ════ --}}
            <div class="oauth-buttons">

                {{-- Google --}}
                <a href="{{ route('google.redirect') }}" class="btn-oauth">
                    {{-- Logo Google officiel SVG --}}
                    <svg width="18" height="18" viewBox="0 0 48 48">
                        <path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/>
                        <path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"/>
                        <path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"/>
                        <path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/>
                        <path fill="none" d="M0 0h48v48H0z"/>
                    </svg>
                    Continuer avec Google
                </a>

                {{-- GitHub --}}
                <a href="{{ route('github.redirect') }}" class="btn-oauth">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="#111827">
                        <path d="M12 0C5.37 0 0 5.37 0 12c0 5.31 3.435 9.795 8.205 11.385.6.105.825-.255.825-.57 0-.285-.015-1.23-.015-2.235-3.015.555-3.795-.735-4.035-1.41-.135-.345-.72-1.41-1.23-1.695-.42-.225-1.02-.78-.015-.795.945-.015 1.62.87 1.845 1.23 1.08 1.815 2.805 1.305 3.495.99.105-.78.42-1.305.765-1.605-2.67-.3-5.46-1.335-5.46-5.925 0-1.305.465-2.385 1.23-3.225-.12-.3-.54-1.53.12-3.18 0 0 1.005-.315 3.3 1.23.96-.27 1.98-.405 3-.405s2.04.135 3 .405c2.295-1.56 3.3-1.23 3.3-1.23.66 1.65.24 2.88.12 3.18.765.84 1.23 1.905 1.23 3.225 0 4.605-2.805 5.625-5.475 5.925.435.375.81 1.095.81 2.22 0 1.605-.015 2.895-.015 3.3 0 .315.225.69.825.57A12.02 12.02 0 0 0 24 12c0-6.63-5.37-12-12-12z"/>
                    </svg>
                    Continuer avec GitHub
                </a>

            </div>

            {{-- Séparateur --}}
            <div class="divider">
                <div class="divider-line"></div>
                <span class="divider-text">ou continuer avec email</span>
                <div class="divider-line"></div>
            </div>

            {{-- ════ FORMULAIRE EMAIL ════ --}}
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="form-group">
                    <label for="email">Adresse email</label>
                    <input type="email" id="email" name="email"
                           value="{{ old('email') }}"
                           placeholder="john@devcollab.app"
                           required autofocus>
                    @error('email')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <input type="password" id="password" name="password"
                           placeholder="Votre mot de passe"
                           required>
                    @error('password')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-row">
                    <label class="remember-label">
                        <input type="checkbox" name="remember">
                        Se souvenir de moi
                    </label>
                    @if(Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="forgot-link">
                            Mot de passe oublié ?
                        </a>
                    @endif
                </div>

                <button type="submit" class="btn-submit">
                    Se connecter
                </button>

            </form>

            <div class="auth-link">
                Pas encore de compte ?
                <a href="{{ route('register') }}">S'inscrire gratuitement</a>
            </div>

        </div>
    </div>

</div>

</body>
</html>