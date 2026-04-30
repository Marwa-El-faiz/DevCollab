<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>DevCollab — @yield('title')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* LIGHT (défaut) */
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            --bg:             #f3f4f6;
            --card:           #ffffff;
            --border:         #e5e7eb;
            --text:           #111827;
            --text2:          #6b7280;
            --text3:          #9ca3af;
            --input-bg:       #f9fafb;
            --input-border:   #d1d5db;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            width: 100%;
            overflow-x: hidden;
        }

        /* DARK — appliqué par PHP sur <body class="dark"> */
        body.dark {
            --bg:           #0f1117;
            --card:         #1a1d23;
            --border:       #2d3139;
            --text:         #f9fafb;
            --text2:        #9ca3af;
            --text3:        #6b7280;
            --input-bg:     #22262f;
            --input-border: #374151;
        }

        a { text-decoration: none; color: inherit; }

        /* ══ LAYOUT ══ */
        .layout {
            display: flex;
            width: 100%;
            min-height: 100vh;
        }

        /* ══ SIDEBAR ══ */
        .sidebar {
            width: 220px;
            min-width: 220px;
            flex-shrink: 0;
            background: #1a1d23;
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; left: 0;
            height: 100vh;
            z-index: 100;
        }
        body.dark .sidebar { background: #111318; }

        .sidebar-logo {
            padding: 20px 18px 16px;
            border-bottom: 1px solid #2d3139;
        }
        .sidebar-logo .brand { font-size: 16px; font-weight: 700; color: #fff; }
        .sidebar-logo .sub   { font-size: 11px; color: #6b7280; margin-top: 2px; }

        .sidebar-nav { padding: 12px 8px; flex: 1; }

        .nav-link {
            display: flex; align-items: center; gap: 10px;
            padding: 8px 12px; border-radius: 7px;
            color: #9ca3af; margin-bottom: 2px; font-size: 13.5px;
            transition: all 0.15s;
        }
        .nav-link:hover, .nav-link.active {
            background: #2d3139; color: #fff;
        }
        .nav-link.active { font-weight: 500; }

        .sidebar-user {
            padding: 12px 16px;
            border-top: 1px solid #2d3139;
            display: flex; align-items: center; gap: 10px;
        }
        .avatar-sm {
            width: 32px; height: 32px; border-radius: 50%;
            background: #2d6a4f; display: flex; align-items: center;
            justify-content: center; font-size: 11px; font-weight: 700;
            color: #fff; flex-shrink: 0;
        }
        .user-info { flex: 1; min-width: 0; }
        .user-name { font-size: 12px; font-weight: 500; color: #fff; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .user-role { font-size: 10px; color: #6b7280; text-transform: capitalize; }
        .logout-btn {
            background: none; border: none; color: #6b7280;
            cursor: pointer; padding: 4px; display: flex; align-items: center;
        }
        .logout-btn:hover { color: #e5e7eb; }

        /* ══ MAIN — PLEINE LARGEUR ══ */
        .main-content {
            margin-left: 220px;
            width: calc(100% - 220px);
            min-height: 100vh;
            padding: 32px 40px;
            background: var(--bg);
        }

        /* ══ COMPOSANTS ══ */
        .page-title    { font-size: 24px; font-weight: 700; color: var(--text); margin-bottom: 4px; }
        .page-subtitle { font-size: 13px; color: var(--text2); margin-bottom: 28px; }

        .card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 10px;
        }

        .btn-primary {
            background: #2d6a4f; color: #fff; border: none;
            padding: 8px 16px; border-radius: 7px; font-size: 13px;
            font-weight: 500; cursor: pointer; display: inline-flex;
            align-items: center; gap: 6px; transition: background 0.15s;
        }
        .btn-primary:hover { background: #1b4332; color: #fff; }

        .btn-secondary {
            background: var(--card); color: var(--text2);
            border: 1px solid var(--border);
            padding: 8px 16px; border-radius: 7px; font-size: 13px;
            font-weight: 500; cursor: pointer; display: inline-flex;
            align-items: center; gap: 6px; transition: all 0.15s;
        }
        .btn-secondary:hover { color: var(--text); }

        .form-input {
            width: 100%; padding: 9px 12px;
            border: 1px solid var(--input-border); border-radius: 7px;
            font-size: 13px; background: var(--input-bg);
            color: var(--text); outline: none; font-family: inherit;
            transition: border-color 0.15s;
        }
        .form-input:focus { border-color: #2d6a4f; }

        .form-label { display: block; font-size: 12px; font-weight: 500; color: var(--text2); margin-bottom: 5px; }
        .form-group { margin-bottom: 16px; }

        .alert { padding: 11px 16px; border-radius: 8px; margin-bottom: 20px; font-size: 13px; display: flex; align-items: center; gap: 8px; }
        .alert-success { background: #d1fae5; border: 1px solid #a7f3d0; color: #065f46; }
        .alert-error   { background: #fee2e2; border: 1px solid #fecaca; color: #991b1b; }
    </style>
</head>

@php
    $theme = auth()->check() ? auth()->user()->theme    : 'light';
    $lang  = auth()->check() ? auth()->user()->language : 'fr';

    // Traductions navigation
    $t = [
        'fr' => ['dashboard'=>'Tableau de bord','board'=>'Board','team'=>'Équipe','settings'=>'Paramètres','logout'=>'Déconnexion'],
        'en' => ['dashboard'=>'Dashboard',      'board'=>'Projects','team'=>'Team','settings'=>'Settings','logout'=>'Logout'],
    ];
    $nav = $t[$lang] ?? $t['fr'];

    // Traductions messages flash
    $msgs = [
        'fr' => [
            'messages.theme_updated'    => 'Thème mis à jour !',
            'messages.language_updated' => 'Langue mise à jour !',
            'messages.profile_updated'  => 'Profil mis à jour !',
            'messages.password_updated' => 'Mot de passe mis à jour !',
            'messages.wrong_password'   => 'Mot de passe actuel incorrect.',
            'messages.project_created'  => 'Projet créé avec succès !',
            'messages.project_updated'  => 'Projet mis à jour !',
            'messages.project_deleted'  => 'Projet supprimé.',
            'messages.task_created'     => 'Tâche créée !',
            'messages.task_updated'     => 'Tâche mise à jour !',
            'messages.task_deleted'     => 'Tâche supprimée.',
            'messages.comment_added'    => 'Commentaire ajouté !',
            'messages.comment_deleted'  => 'Commentaire supprimé.',
        ],
        'en' => [
            'messages.theme_updated'    => 'Theme updated!',
            'messages.language_updated' => 'Language updated!',
            'messages.profile_updated'  => 'Profile updated!',
            'messages.password_updated' => 'Password updated!',
            'messages.wrong_password'   => 'Current password is incorrect.',
            'messages.project_created'  => 'Project created successfully!',
            'messages.project_updated'  => 'Project updated!',
            'messages.project_deleted'  => 'Project deleted.',
            'messages.task_created'     => 'Task created!',
            'messages.task_updated'     => 'Task updated!',
            'messages.task_deleted'     => 'Task deleted.',
            'messages.comment_added'    => 'Comment added!',
            'messages.comment_deleted'  => 'Comment deleted.',
        ],
    ];
    $dict = $msgs[$lang] ?? $msgs['fr'];

    // Résoudre le message flash
    $successMsg = session('success');
    $successMsg = $dict[$successMsg] ?? $successMsg;
    $errorMsg   = session('error');
    $errorMsg   = $dict[$errorMsg]   ?? $errorMsg;
@endphp

<body class="{{ $theme === 'dark' ? 'dark' : '' }}">
<div class="layout">

    {{-- SIDEBAR --}}
    <aside class="sidebar">
        <div class="sidebar-logo">
            <div class="brand">DevCollab</div>
            <div class="sub">Project Management</div>
        </div>

        <nav class="sidebar-nav">
            <a href="{{ route('dashboard') }}"
               class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <rect x="3" y="3" width="7" height="7" rx="1"/>
                    <rect x="14" y="3" width="7" height="7" rx="1"/>
                    <rect x="3" y="14" width="7" height="7" rx="1"/>
                    <rect x="14" y="14" width="7" height="7" rx="1"/>
                </svg>
                {{ $nav['dashboard'] }}
            </a>

            <a href="{{ route('projects.index') }}"
               class="nav-link {{ request()->routeIs('projects.*') ? 'active' : '' }}">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <rect x="3" y="3" width="18" height="18" rx="2"/>
                    <line x1="3" y1="9" x2="21" y2="9"/>
                    <line x1="9" y1="21" x2="9" y2="9"/>
                </svg>
                {{ $nav['board'] }}
            </a>

            <a href="{{ route('team.index') }}"
               class="nav-link {{ request()->routeIs('team.*') ? 'active' : '' }}">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                    <circle cx="9" cy="7" r="4"/>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                </svg>
                {{ $nav['team'] }}
            </a>

            <a href="{{ route('settings.index') }}"
               class="nav-link {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="3"/>
                    <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/>
                </svg>
                {{ $nav['settings'] }}
            </a>
        </nav>

        <div class="sidebar-user">
            <div class="avatar-sm">
                {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
            </div>
            <div class="user-info">
                <div class="user-name">{{ Auth::user()->name }}</div>
                <div class="user-role">{{ Auth::user()->role }}</div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-btn" title="{{ $nav['logout'] }}">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                        <polyline points="16 17 21 12 16 7"/>
                        <line x1="21" y1="12" x2="9" y2="12"/>
                    </svg>
                </button>
            </form>
        </div>
    </aside>

    {{-- MAIN --}}
    <main class="main-content">

        @if($successMsg)
        <div class="alert alert-success">
            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <polyline points="20 6 9 17 4 12"/>
            </svg>
            {{ $successMsg }}
        </div>
        @endif

        @if($errorMsg)
        <div class="alert alert-error">
            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="10"/>
                <line x1="12" y1="8" x2="12" y2="12"/>
                <line x1="12" y1="16" x2="12.01" y2="16"/>
            </svg>
            {{ $errorMsg }}
        </div>
        @endif

        @yield('content')
    </main>

</div>
</body>
</html>