@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')

{{-- En-tête --}}
<div style="display:flex; justify-content:space-between;
            align-items:flex-start; margin-bottom:32px;">
    <div>
        <h1 class="page-title">Projects</h1>
        <p class="page-subtitle">Overview of active projects and tasks</p>
    </div>
    <a href="{{ route('projects.create') }}" class="btn-primary">
        <svg width="16" height="16" fill="none" stroke="currentColor"
             stroke-width="2.5" viewBox="0 0 24 24">
            <line x1="12" y1="5" x2="12" y2="19"/>
            <line x1="5" y1="12" x2="19" y2="12"/>
        </svg>
        New Project
    </a>
</div>

{{-- Grille projets --}}
@if($projects->isEmpty())
    <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px;
                padding:60px; text-align:center;">
        <p style="font-size:15px; color:#6b7280; margin-bottom:12px;">
            Aucun projet pour l'instant.
        </p>
        <a href="{{ route('projects.create') }}" class="btn-primary"
           style="display:inline-flex;">
            Créer mon premier projet
        </a>
    </div>
@else
    <div style="display:grid;
                grid-template-columns:repeat(auto-fill, minmax(320px,1fr));
                gap:20px; margin-bottom:48px;">

        @foreach($projects as $project)

        @php
            $total    = $project->tasks->count();
            $done     = $project->tasks->where('status','done')->count();
            $progress = $total > 0 ? round(($done / $total) * 100) : 0;

            $statusColor = match($project->status) {
                'completed' => '#059669',
                'archived'  => '#9ca3af',
                default     => '#f59e0b',
            };
            $statusLabel = match($project->status) {
                'completed' => 'Terminé',
                'archived'  => 'Archivé',
                default     => 'Actif',
            };
        @endphp

        <div style="background:#fff; border:1px solid #e5e7eb;
                    border-radius:12px; padding:24px;
                    display:flex; flex-direction:column; gap:16px;
                    transition:box-shadow 0.2s;"
             onmouseover="this.style.boxShadow='0 4px 16px rgba(0,0,0,0.08)'"
             onmouseout="this.style.boxShadow='none'">

            {{-- Header --}}
            <div style="display:flex; justify-content:space-between;
                        align-items:flex-start;">
                <div style="flex:1; min-width:0;">
                    <a href="{{ route('projects.show', $project) }}"
                       style="font-size:16px; font-weight:600;
                              color:#111827; text-decoration:none;
                              display:block; margin-bottom:4px;">
                        {{ $project->name }}
                    </a>
                    <span style="font-size:11px; font-weight:500;
                                 color:{{ $statusColor }};
                                 background:{{ $statusColor }}18;
                                 padding:2px 8px; border-radius:999px;">
                        {{ $statusLabel }}
                    </span>
                </div>

                {{-- Actions --}}
                <div style="display:flex; gap:6px; flex-shrink:0; margin-left:12px;">
                    <a href="{{ route('projects.edit', $project) }}"
                       title="Modifier"
                       style="width:28px; height:28px; display:flex;
                              align-items:center; justify-content:center;
                              border:1px solid #e5e7eb; border-radius:6px;
                              color:#6b7280; text-decoration:none;
                              transition:all 0.15s;"
                       onmouseover="this.style.borderColor='#2d6a4f';this.style.color='#2d6a4f'"
                       onmouseout="this.style.borderColor='#e5e7eb';this.style.color='#6b7280'">
                        <svg width="13" height="13" fill="none" stroke="currentColor"
                             stroke-width="2" viewBox="0 0 24 24">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                        </svg>
                    </a>
                    <form method="POST"
                          action="{{ route('projects.destroy', $project) }}"
                          onsubmit="return confirm('Supprimer ce projet ?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" title="Supprimer"
                                style="width:28px; height:28px; display:flex;
                                       align-items:center; justify-content:center;
                                       border:1px solid #fecaca; border-radius:6px;
                                       background:none; color:#dc2626;
                                       cursor:pointer; transition:all 0.15s;"
                                onmouseover="this.style.background='#fee2e2'"
                                onmouseout="this.style.background='none'">
                            <svg width="13" height="13" fill="none" stroke="currentColor"
                                 stroke-width="2" viewBox="0 0 24 24">
                                <polyline points="3 6 5 6 21 6"/>
                                <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                                <path d="M10 11v6M14 11v6"/>
                                <path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>

            {{-- Description --}}
            <p style="font-size:13px; color:#6b7280; line-height:1.5;
                      display:-webkit-box; -webkit-line-clamp:2;
                      -webkit-box-orient:vertical; overflow:hidden;">
                {{ $project->description ?? 'Aucune description.' }}
            </p>

            {{-- Barre progression --}}
            <div>
                <div style="display:flex; justify-content:space-between;
                            margin-bottom:8px;">
                    <span style="font-size:13px; color:#374151; font-weight:500;">
                        Progress
                    </span>
                    <span style="font-size:13px; font-weight:600; color:#111827;">
                        {{ $progress }}%
                    </span>
                </div>
                <div style="background:#e5e7eb; border-radius:999px;
                            height:6px; overflow:hidden;">
                    <div style="background: {{ $progress >= 100 ? '#059669' : '#2d6a4f' }};
                                height:100%; border-radius:999px;
                                width:{{ $progress }}%;
                                transition:width 0.3s ease;">
                    </div>
                </div>
            </div>

            {{-- Footer : avatars + tâches + date --}}
            <div style="display:flex; align-items:center;
                        justify-content:space-between; padding-top:4px;
                        border-top:1px solid #f3f4f6;">

                {{-- Avatars membres --}}
                <div style="display:flex; align-items:center;">
                    @foreach($project->members->take(4) as $member)
                    <div title="{{ $member->name }}"
                         style="width:28px; height:28px; border-radius:50%;
                                background:#374151; border:2px solid #fff;
                                display:flex; align-items:center;
                                justify-content:center; font-size:10px;
                                font-weight:600; color:#fff; margin-right:-6px;">
                        {{ strtoupper(substr($member->name, 0, 2)) }}
                    </div>
                    @endforeach
                    @if($project->members->count() > 4)
                    <div style="width:28px; height:28px; border-radius:50%;
                                background:#e5e7eb; border:2px solid #fff;
                                display:flex; align-items:center;
                                justify-content:center; font-size:10px;
                                color:#6b7280; margin-right:-6px;">
                        +{{ $project->members->count() - 4 }}
                    </div>
                    @endif
                    @if($project->members->isEmpty())
                    <span style="font-size:12px; color:#9ca3af;">Aucun membre</span>
                    @endif
                </div>

                {{-- Compteur tâches --}}
                <span style="font-size:12px; color:#6b7280; font-weight:500;">
                    {{ $done }}/{{ $total }} tasks
                </span>

            </div>
        </div>
        @endforeach
    </div>
@endif

{{-- Recent Activity --}}
<div>
    <h2 style="font-size:18px; font-weight:600; color:#111827;
               margin-bottom:20px; display:flex; align-items:center; gap:8px;">
        <svg width="18" height="18" fill="none" stroke="currentColor"
             stroke-width="2" viewBox="0 0 24 24">
            <circle cx="12" cy="12" r="10"/>
            <polyline points="12 6 12 12 16 14"/>
        </svg>
        Recent Activity
    </h2>

    @php
        $recentComments = \App\Models\Comment::with(['user','task'])
            ->whereHas('task.project', function($q) {
                $q->where('owner_id', Auth::id())
                  ->orWhereHas('members', function($q2) {
                      $q2->where('user_id', Auth::id());
                  });
            })
            ->latest()->take(5)->get();
    @endphp

    <div style="background:#fff; border:1px solid #e5e7eb;
                border-radius:12px; overflow:hidden;">

        @forelse($recentComments as $comment)
        <div style="display:flex; align-items:center; gap:14px;
                    padding:16px 20px; border-bottom:1px solid #f3f4f6;">
            <div style="width:36px; height:36px; border-radius:50%;
                        background:#2d6a4f; display:flex; align-items:center;
                        justify-content:center; font-size:12px;
                        font-weight:600; color:#fff; flex-shrink:0;">
                {{ strtoupper(substr($comment->user->name, 0, 2)) }}
            </div>
            <div style="flex:1; min-width:0;">
                <p style="font-size:14px; color:#111827; margin-bottom:2px;">
                    <strong>{{ $comment->user->name }}</strong>
                    <span style="color:#6b7280;"> a commenté sur </span>
                    <strong>{{ $comment->task->title }}</strong>
                </p>
                <p style="font-size:12px; color:#9ca3af;">
                    {{ $comment->created_at->diffForHumans() }}
                </p>
            </div>
        </div>
        @empty
        <div style="padding:40px; text-align:center;
                    color:#9ca3af; font-size:14px;">
            <svg width="32" height="32" fill="none" stroke="currentColor"
                 stroke-width="1.5" viewBox="0 0 24 24"
                 style="margin:0 auto 12px; display:block; opacity:0.4;">
                <circle cx="12" cy="12" r="10"/>
                <polyline points="12 6 12 12 16 14"/>
            </svg>
            Aucune activité récente.
        </div>
        @endforelse

    </div>
</div>

@endsection