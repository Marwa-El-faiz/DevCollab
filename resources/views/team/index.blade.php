@extends('layouts.app')
@section('title', 'Team')
@section('content')

<div style="display:flex; justify-content:space-between;
            align-items:flex-start; margin-bottom:32px;">
    <div>
        <h1 class="page-title">Team</h1>
        <p class="page-subtitle">Manage team members and their roles</p>
    </div>
</div>

<div style="background:#fff; border:1px solid #e5e7eb;
            border-radius:12px; overflow:hidden;">

    {{-- Header --}}
    <div style="padding:20px 24px; border-bottom:1px solid #e5e7eb;
                display:flex; align-items:center; justify-content:space-between;">
        <div style="display:flex; align-items:center; gap:8px;">
            <svg width="18" height="18" fill="none" stroke="#6b7280"
                 stroke-width="2" viewBox="0 0 24 24">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                <circle cx="9" cy="7" r="4"/>
                <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
            </svg>
            <span style="font-size:14px; font-weight:500; color:#374151;">
                {{ $members->count() }} Members
            </span>
        </div>
    </div>

    {{-- Liste membres --}}
    @foreach($members as $member)
    <div style="display:flex; align-items:center; gap:16px;
                padding:20px 24px;
                border-bottom:1px solid #f3f4f6;">

        {{-- Avatar --}}
        <div style="width:44px; height:44px; border-radius:50%;
                    background:{{ $member->isAdmin() ? '#2d6a4f' : '#374151' }};
                    display:flex; align-items:center; justify-content:center;
                    font-size:14px; font-weight:600; color:#fff; flex-shrink:0;">
            {{ strtoupper(substr($member->name, 0, 2)) }}
        </div>

        {{-- Infos --}}
        <div style="flex:1;">
            <div style="display:flex; align-items:center; gap:8px;
                        margin-bottom:2px;">
                <p style="font-size:15px; font-weight:600; color:#111827;">
                    {{ $member->name }}
                </p>
                {{-- Badge rôle --}}
                <span style="font-size:11px; padding:2px 8px; border-radius:20px;
                             font-weight:500;
                             background:{{ $member->isAdmin() ? '#d1fae5' : '#f3f4f6' }};
                             color:{{ $member->isAdmin() ? '#065f46' : '#374151' }};">
                    {{ $member->isAdmin() ? 'Admin' : 'Member' }}
                </span>
                {{-- Toi --}}
                @if($member->id === Auth::id())
                <span style="font-size:11px; padding:2px 8px; border-radius:20px;
                             background:#eff6ff; color:#1d4ed8; font-weight:500;">
                    Vous
                </span>
                @endif
            </div>
            <p style="font-size:13px; color:#6b7280;">
                {{ $member->email }}
            </p>
        </div>

        {{-- Compteur tâches --}}
        <div style="text-align:right;">
            <p style="font-size:14px; font-weight:600; color:#111827;">
                {{ $member->tasks_count }} tasks
            </p>
            <p style="font-size:12px; color:#10b981; margin-top:2px;">
                Active
            </p>
        </div>

    </div>
    @endforeach

</div>

@endsection