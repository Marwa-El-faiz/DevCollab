@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

    <h1 class="page-title">Projects</h1>
    <p class="page-subtitle">Overview of active projects and tasks</p>

    {{-- Message temporaire --}}
    <div style="background: #fff; border: 1px solid #e5e7eb; border-radius: 12px;
                padding: 48px; text-align: center; color: #6b7280;">
        <p style="font-size: 15px;">Bienvenue sur DevCollab, {{ auth()->user()->name }} ! 👋</p>
        <p style="font-size: 13px; margin-top: 8px;">Les projets arriveront en Phase 3.</p>
    </div>

@endsection