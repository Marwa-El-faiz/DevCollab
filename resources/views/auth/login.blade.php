<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DevCollab — Connexion</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', -apple-system, sans-serif;
            min-height: 100vh;
            display: flex;
        }

        .auth-container {
            display: flex;
            width: 100%;
            min-height: 100vh;
        }

        /* Panneau gauche */
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

        /* Stats décoratifs */
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

        .deco-stat .number {
            font-size: 22px;
            font-weight: 700;
            color: #ffffff;
        }

        .deco-stat .label {
            font-size: 11px;
            color: #6b7280;
            margin-top: 2px;
        }

        /* Panneau droit */
        .auth-right {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 60px 40px;
            background: #ffffff;
        }

        .auth-form-box {
            width: 100%;
            max-width: 400px;
        }

        .auth-form-box h1 {
            font-size: 24px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 6px;
        }

        .auth-form-box .subtitle {
            color: #6b7280;
            font-size: 14px;
            margin-bottom: 32px;
        }

        .form-group {
            margin-bottom: 20px;
        }

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
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 14px;
            color: #111827;
            outline: none;
            transition: border-color 0.15s;
            background: #f9fafb;
        }

        .form-group input:focus {
            border-color: #2d6a4f;
            background: #ffffff;
            box-shadow: 0 0 0 3px rgba(45, 106, 79, 0.1);
        }

        .form-error {
            color: #dc2626;
            font-size: 12px;
            margin-top: 4px;
        }

        /* Ligne remember + forgot */
        .form-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
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
            width: 16px;
            height: 16px;
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

        /* Alerte erreur session */
        .alert-error {
            background: #fee2e2;
            border: 1px solid #fca5a5;
            color: #991b1b;
            padding: 10px 14px;
            border-radius: 8px;
            font-size: 13px;
            margin-bottom: 20px;
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

        {{-- Stats décoratifs --}}
        <div class="deco-stats">
            <div class="deco-stat">
                <div class="number">3</div>
                <div class="label">Projets actifs</div>
            </div>
            <div class="deco-stat">
                <div class="number">54</div>
                <div class="label">Tâches totales</div>
            </div>
            <div class="deco-stat">
                <div class="number">5</div>
                <div class="label">Membres</div>
            </div>
            <div class="deco-stat">
                <div class="number">66%</div>
                <div class="label">Progression</div>
            </div>
        </div>
    </div>

    {{-- ── Panneau droit — Formulaire ── --}}
    <div class="auth-right">
        <div class="auth-form-box">

            <h1>Se connecter</h1>
            <p class="subtitle">Entrez vos identifiants pour continuer</p>

            {{-- Erreur de session --}}
            @if ($errors->any())
                <div class="alert-error">
                    Email ou mot de passe incorrect.
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                {{-- Email --}}
                <div class="form-group">
                    <label for="email">Adresse email</label>
                    <input type="email"
                           id="email"
                           name="email"
                           value="{{ old('email') }}"
                           placeholder="john@devcollab.app"
                           required
                           autofocus>
                    @error('email')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Mot de passe --}}
                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <input type="password"
                           id="password"
                           name="password"
                           placeholder="Votre mot de passe"
                           required>
                    @error('password')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Remember + Forgot --}}
                <div class="form-row">
                    <label class="remember-label">
                        <input type="checkbox" name="remember">
                        Se souvenir de moi
                    </label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="forgot-link">
                            Mot de passe oublié ?
                        </a>
                    @endif
                </div>

                <button type="submit" class="btn-submit">
                    Se connecter →
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