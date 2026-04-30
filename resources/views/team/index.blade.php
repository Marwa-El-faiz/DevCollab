@extends('layouts.app')
@section('title', 'Team')
@section('content')

{{-- En-tête --}}
<div style="display:flex; justify-content:space-between;
            align-items:flex-start; margin-bottom:32px;">
    <div>
        <h1 class="page-title">{{ $nav['team'] ?? 'Équipe' }}</h1>
        <p class="page-subtitle">Gérer les membres de l'équipe et leurs rôles</p>
    </div>
</div>

<div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; overflow:hidden;">

    <div style="padding:18px 24px; border-bottom:1px solid #e5e7eb;
                display:flex; align-items:center; justify-content:space-between;">
        <div style="display:flex; align-items:center; gap:8px;">
            <svg width="17" height="17" fill="none" stroke="#6b7280"
                 stroke-width="2" viewBox="0 0 24 24">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                <circle cx="9" cy="7" r="4"/>
                <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
            </svg>
            <span style="font-size:14px; font-weight:600; color:#374151;">
{{ $members->count() }} Membres            </span>
        </div>

        @if(Auth::user()->isAdmin())
        <a href="{{ route('register') }}" class="btn-primary" style="font-size:13px;">
            Invite Membre
        </a>
        @endif
    </div>

    @foreach($members as $member)
    <div style="display:flex; align-items:center; gap:16px;
                padding:18px 24px; border-bottom:1px solid #f3f4f6;
                transition:background 0.15s;"
         onmouseover="this.style.background='#fafafa'"
         onmouseout="this.style.background='transparent'">

        <div style="width:46px; height:46px; border-radius:50%; flex-shrink:0;
                    background:{{ $member->isAdmin() ? '#2d6a4f' : '#374151' }};
                    display:flex; align-items:center; justify-content:center;
                    font-size:15px; font-weight:700; color:#fff;">
            {{ strtoupper(substr($member->name, 0, 2)) }}
        </div>

        <div style="flex:1; min-width:0;">
            <div style="display:flex; align-items:center; gap:8px; margin-bottom:3px; flex-wrap:wrap;">
                <span style="font-size:15px; font-weight:600; color:#111827;">
                    {{ $member->name }}
                </span>
                <span style="font-size:11px; font-weight:500; padding:2px 8px;
                             border-radius:20px;
                             background:{{ $member->isAdmin() ? '#d1fae5' : '#f3f4f6' }};
                             color:{{ $member->isAdmin() ? '#065f46' : '#374151' }};
                             border:1px solid {{ $member->isAdmin() ? '#a7f3d0' : '#e5e7eb' }};">
                    {{ $member->isAdmin() ? 'Admin' : 'Member' }}
                </span>
                @if($member->id === Auth::id())
                <span style="font-size:11px; font-weight:500; padding:2px 8px;
                             border-radius:20px; background:#eff6ff;
                             color:#1d4ed8; border:1px solid #bfdbfe;">
                    Vous
                </span>
                @endif
            </div>
            <div style="display:flex; align-items:center; gap:5px;">
                <svg width="12" height="12" fill="none" stroke="#9ca3af"
                     stroke-width="2" viewBox="0 0 24 24">
                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                    <polyline points="22,6 12,13 2,6"/>
                </svg>
                <span style="font-size:13px; color:#6b7280;">{{ $member->email }}</span>
            </div>
        </div>

        <div style="text-align:right; flex-shrink:0;">
            <p style="font-size:14px; font-weight:600; color:#111827; margin-bottom:2px;">
                {{ $member->tasks_count }} taches
            </p>
            <p style="font-size:12px; color:#10b981; font-weight:500;">Active</p>
        </div>

        @if(Auth::user()->isAdmin() && $member->id !== Auth::id())
        <button style="background:none; border:none; color:#9ca3af;
                       cursor:pointer; padding:4px 6px; border-radius:6px;
                       font-size:16px; transition:color 0.15s;"
                onmouseover="this.style.color='#374151'"
                onmouseout="this.style.color='#9ca3af'"
                title="Options">
            ···
        </button>
        @endif

    </div>
    @endforeach

</div>

@endsection