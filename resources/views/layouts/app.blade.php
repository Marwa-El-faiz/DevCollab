<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>DevCollab — @yield('title', 'Dashboard')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Inter', -apple-system, sans-serif; background: #f3f4f6; }
        a { text-decoration: none; }

        .sidebar {
            width: 260px;
            background: #1a1d23;
            color: #fff;
            display: flex;
            flex-direction: column;
            position: fixed;
            height: 100vh;
            top: 0;
            left: 0;
            z-index: 100;
        }
        .sidebar-logo {
            padding: 24px 20px 16px;
            border-bottom: 1px solid #2d3139;
        }
        .sidebar-logo h1 {
            font-size: 18px;
            font-weight: 700;
            color: #ffffff;
        }
        .sidebar-logo p {
            font-size: 12px;
            color: #6b7280;
            margin-top: 2px;
        }
        .sidebar-nav {
            padding: 16px 12px;
            flex: 1;
        }
        .nav-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            border-radius: 8px;
            color: #9ca3af;
            margin-bottom: 4px;
            font-size: 14px;
            transition: all 0.15s;
        }
        .nav-link:hover {
            background: #2d3139;
            color: #ffffff;
        }
        .nav-link.active {
            background: #2d3139;
            color: #ffffff;
        }
        .sidebar-user {
            padding: 16px 20px;
            border-top: 1px solid #2d3139;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: #2d6a4f;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            font-weight: 600;
            color: #fff;
            flex-shrink: 0;
        }

        .main-content {
            margin-left: 260px;
            padding: 40px 48px;
            min-height: 100vh;
        }
        .page-title {
            font-size: 26px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 4px;
        }
        .page-subtitle {
            font-size: 14px;
            color: #6b7280;
            margin-bottom: 32px;
        }

        .alert-success {
            background: #d1fae5;
            border: 1px solid #6ee7b7;
            color: #065f46;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 24px;
            font-size: 14px;
        }
        .alert-error {
            background: #fee2e2;
            border: 1px solid #fca5a5;
            color: #991b1b;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 24px;
            font-size: 14px;
        }

        .btn-primary {
            background: #2d6a4f;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: background 0.15s;
        }
        .btn-primary:hover { background: #1b4332; color: #fff; }
    </style>
</head>
<body>

<div style="display: flex;">

    <aside class="sidebar">

        <div class="sidebar-logo">
            <h1>DevCollab</h1>
            <p>Project Management</p>
        </div>

        <nav class="sidebar-nav">

            <a href="{{ route('dashboard') }}"
               class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <svg width="18" height="18" fill="none" stroke="currentColor"
                     stroke-width="2" viewBox="0 0 24 24">
                    <rect x="3" y="3" width="7" height="7" rx="1"/>
                    <rect x="14" y="3" width="7" height="7" rx="1"/>
                    <rect x="3" y="14" width="7" height="7" rx="1"/>
                    <rect x="14" y="14" width="7" height="7" rx="1"/>
                </svg>
                Dashboard
            </a>

            <a href="{{ route('projects.index') }}"
               class="nav-link {{ request()->routeIs('projects.*') ? 'active' : '' }}">
                <svg width="18" height="18" fill="none" stroke="currentColor"
                     stroke-width="2" viewBox="0 0 24 24">
                    <line x1="3" y1="6" x2="3" y2="18"/>
                    <line x1="9" y1="4" x2="9" y2="20"/>
                    <line x1="15" y1="8" x2="15" y2="16"/>
                    <line x1="21" y1="6" x2="21" y2="18"/>
                </svg>
                Board
            </a>

            <a href="{{ route('team.index') }}"
               class="nav-link {{ request()->routeIs('team.*') ? 'active' : '' }}">
                <svg width="18" height="18" fill="none" stroke="currentColor"
                     stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="9" cy="7" r="4"/>
                    <path d="M3 21v-2a4 4 0 0 1 4-4h4a4 4 0 0 1 4 4v2"/>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                    <path d="M21 21v-2a4 4 0 0 0-3-3.85"/>
                </svg>
                Team
            </a>

            <a href="{{ route('settings.index') }}"
               class="nav-link {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                <svg width="18" height="18" fill="none" stroke="currentColor"
                     stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="3"/>
                    <path d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"/>
                </svg>
                Settings
            </a>

        </nav>

        <div class="sidebar-user">
            <div class="avatar">
                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
            </div>
            <div style="flex: 1; min-width: 0;">
                <div style="font-size: 13px; font-weight: 500; color: #fff;
                            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                    {{ auth()->user()->name }}
                </div>
                <div style="font-size: 11px; color: #6b7280; text-transform: capitalize;">
                    {{ auth()->user()->role }}
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" title="Se déconnecter"
                    style="background: none; border: none; color: #6b7280;
                           cursor: pointer; padding: 4px;">
                    <svg width="16" height="16" fill="none" stroke="currentColor"
                         stroke-width="2" viewBox="0 0 24 24">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                        <polyline points="16 17 21 12 16 7"/>
                        <line x1="21" y1="12" x2="9" y2="12"/>
                    </svg>
                </button>
            </form>
        </div>

    </aside>

    <main class="main-content">

        @if(session('success'))
            <div class="alert-success">✓ {{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert-error">✕ {{ session('error') }}</div>
        @endif

        @yield('content')

    </main>

</div>

</body>
</html>