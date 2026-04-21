<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DevCollab — Inscription</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', -apple-system, sans-serif;
            background: #f3f4f6;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .auth-container {
            display: flex;
            width: 100%;
            min-height: 100vh;
        }

        /* Panneau gauche — décoratif */
        .auth-left {
            width: 45%;
            background: #1a1d23;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 60px;
        }

        .auth-left h2 {
            color: #ffffff;
            font-size: 28px;
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

        .auth-logo {
            font-size: 32px;
            font-weight: 800;
            color: #ffffff;
            margin-bottom: 40px;
            letter-spacing: -0.5px;
        }

        .auth-logo span {
            color: #2d6a4f;
        }

        /* Petites cards décoratifs */
        .deco-cards {
            margin-top: 48px;
            display: flex;
            flex-direction: column;
            gap: 12px;
            width: 100%;
            max-width: 280px;
        }

        .deco-card {
            background: #2d3139;
            border-radius: 10px;
            padding: 14px 18px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .deco-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        .deco-card span {
            color: #9ca3af;
            font-size: 13px;
        }

        /* Panneau droit — formulaire */
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
            margin-top: 8px;
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
    </style>
</head>
<body>

<div class="auth-container">

    {{-- ── Panneau gauche décoratif ── --}}
    <div class="auth-left">
        <div class="auth-logo">Dev<span>Collab</span></div>

        <h2>Gérez vos projets<br>en équipe</h2>
        <p>Organisez vos tâches, collaborez<br>avec votre équipe et livrez plus vite.</p>

        {{-- Mini Kanban décoratif --}}
        <div class="deco-cards">
            <div class="deco-card">
                <div class="deco-dot" style="background: #6b7280;"></div>
                <span>Design settings page mockup</span>
            </div>
            <div class="deco-card">
                <div class="deco-dot" style="background: #f59e0b;"></div>
                <span>Update authentication flow</span>
            </div>
            <div class="deco-card">
                <div class="deco-dot" style="background: #10b981;"></div>
                <span>Setup CI/CD pipeline ✓</span>
            </div>
        </div>
    </div>

    {{-- ── Panneau droit — Formulaire ── --}}
    <div class="auth-right">
        <div class="auth-form-box">

            <h1>Créer un compte</h1>
            <p class="subtitle">Rejoignez DevCollab et commencez à collaborer</p>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                {{-- Nom --}}
                <div class="form-group">
                    <label for="name">Nom complet</label>
                    <input type="text"
                           id="name"
                           name="name"
                           value="{{ old('name') }}"
                           placeholder="John Doe"
                           required
                           autofocus>
                    @error('name')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Email --}}
                <div class="form-group">
                    <label for="email">Adresse email</label>
                    <input type="email"
                           id="email"
                           name="email"
                           value="{{ old('email') }}"
                           placeholder="john@devcollab.app"
                           required>
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
                           placeholder="Minimum 8 caractères"
                           required>
                    @error('password')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Confirmation --}}
                <div class="form-group">
                    <label for="password_confirmation">Confirmer le mot de passe</label>
                    <input type="password"
                           id="password_confirmation"
                           name="password_confirmation"
                           placeholder="Répéter le mot de passe"
                           required>
                </div>

                <button type="submit" class="btn-submit">
                    Créer mon compte →
                </button>

            </form>

            <div class="auth-link">
                Déjà un compte ?
                <a href="{{ route('login') }}">Se connecter</a>
            </div>

        </div>
    </div>

</div>

</body>
</html>