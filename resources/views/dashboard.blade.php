@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')

<div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:28px;">
    <div>
        <h1 class="page-title">Dashboard</h1>
        <p class="page-subtitle">Bonjour {{ Auth::user()->name }}, voici l'état de tes projets</p>
    </div>
    <a href="{{ route('projects.create') }}" class="btn-primary">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
        </svg>
        Nouveau projet
    </a>
</div>

@php
    $totalProjects = $projects->count();
    $totalTasks    = $projects->sum(fn($p) => $p->tasks->count());
    $doneTasks     = $projects->sum(fn($p) => $p->tasks->where('status','done')->count());
    $activeProjects = $projects->where('status','active')->count();
@endphp

<div style="display:grid; grid-template-columns:repeat(4,1fr); gap:16px; margin-bottom:28px;">
    @foreach([
        ['label'=>'Projets actifs',  'value'=>$activeProjects, 'color'=>'#2d6a4f', 'bg'=>'#d1fae5'],
        ['label'=>'Tâches totales',  'value'=>$totalTasks,     'color'=>'#1d4ed8', 'bg'=>'#dbeafe'],
        ['label'=>'Tâches terminées','value'=>$doneTasks,      'color'=>'#059669', 'bg'=>'#d1fae5'],
        ['label'=>'Membres',         'value'=>\App\Models\User::count(), 'color'=>'#7c3aed', 'bg'=>'#ede9fe'],
    ] as $stat)
    <div class="card" style="padding:20px 24px;">
        <div style="font-size:28px; font-weight:700; color:{{ $stat['color'] }}; margin-bottom:4px;">
            {{ $stat['value'] }}
        </div>
        <div style="font-size:12px; color:#6b7280;">{{ $stat['label'] }}</div>
    </div>
    @endforeach
</div>

@if($projects->isEmpty())
<div class="card" style="padding:60px; text-align:center;">
    <svg width="40" height="40" fill="none" stroke="#d1d5db" stroke-width="1.5" viewBox="0 0 24 24" style="margin:0 auto 16px;">
        <rect x="3" y="3" width="18" height="18" rx="2"/>
        <line x1="3" y1="9" x2="21" y2="9"/>
        <line x1="9" y1="21" x2="9" y2="9"/>
    </svg>
    <p style="font-size:15px; color:#6b7280; margin-bottom:16px;">Aucun projet pour l'instant.</p>
    <a href="{{ route('projects.create') }}" class="btn-primary" style="display:inline-flex;">
        Créer mon premier projet
    </a>
</div>
@else

<div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(340px,1fr)); gap:16px; margin-bottom:32px;">
    @foreach($projects as $project)
    @php
        $total    = $project->tasks->count();
        $done     = $project->tasks->where('status','done')->count();
        $inprog   = $project->tasks->where('status','in_progress')->count();
        $progress = $total > 0 ? round(($done / $total) * 100) : 0;
        $statusColor = match($project->status) {
            'completed' => '#059669', 'archived' => '#9ca3af', default => '#f59e0b'
        };
        $statusLabel = match($project->status) {
            'completed' => 'Terminé', 'archived' => 'Archivé', default => 'Actif'
        };
    @endphp

    <div class="card" style="padding:20px; display:flex; flex-direction:column; gap:14px;
         transition:box-shadow 0.2s;"
         onmouseover="this.style.boxShadow='0 4px 16px rgba(0,0,0,0.08)'"
         onmouseout="this.style.boxShadow='none'">

        <div style="display:flex; justify-content:space-between; align-items:flex-start;">
            <div style="flex:1; min-width:0;">
                <a href="{{ route('projects.show', $project) }}"
                   style="font-size:15px; font-weight:600; color:#111827; display:block; margin-bottom:4px;">
                    {{ $project->name }}
                </a>
                <span style="font-size:11px; font-weight:500; color:{{ $statusColor }};
                             background:{{ $statusColor }}18; padding:2px 8px; border-radius:999px;">
                    {{ $statusLabel }}
                </span>
            </div>
            <div style="display:flex; gap:4px; margin-left:8px;">
                <a href="{{ route('projects.edit', $project) }}"
                   style="width:26px; height:26px; display:flex; align-items:center; justify-content:center;
                          border:1px solid #e5e7eb; border-radius:6px; color:#9ca3af; transition:all 0.15s;"
                   onmouseover="this.style.borderColor='#2d6a4f';this.style.color='#2d6a4f'"
                   onmouseout="this.style.borderColor='#e5e7eb';this.style.color='#9ca3af'">
                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                    </svg>
                </a>
                <form method="POST" action="{{ route('projects.destroy', $project) }}"
                      onsubmit="return confirm('Supprimer ce projet ?')">
                    @csrf @method('DELETE')
                    <button type="submit"
                            style="width:26px; height:26px; display:flex; align-items:center; justify-content:center;
                                   border:1px solid #fecaca; border-radius:6px; background:none; color:#dc2626; cursor:pointer;">
                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <polyline points="3 6 5 6 21 6"/>
                            <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                        </svg>
                    </button>
                </form>
            </div>
        </div>

        <p style="font-size:12px; color:#6b7280; line-height:1.5;
                  overflow:hidden; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical;">
            {{ $project->description ?? 'Aucune description.' }}
        </p>

        <div>
            <div style="display:flex; justify-content:space-between; margin-bottom:6px;">
                <span style="font-size:12px; color:#374151; font-weight:500;">Progression</span>
                <span style="font-size:12px; font-weight:600; color:#111827;">{{ $progress }}%</span>
            </div>
            <div style="background:#e5e7eb; border-radius:999px; height:5px; overflow:hidden;">
                <div style="background:#2d6a4f; height:100%; border-radius:999px; width:{{ $progress }}%; transition:width 0.3s;"></div>
            </div>
        </div>

        <div style="display:flex; align-items:center; justify-content:space-between; padding-top:8px; border-top:1px solid #f3f4f6;">
            {{-- Avatars --}}
            <div style="display:flex;">
                @foreach($project->members->take(4) as $member)
                <div title="{{ $member->name }}"
                     style="width:26px; height:26px; border-radius:50%; background:#374151;
                            border:2px solid #fff; display:flex; align-items:center;
                            justify-content:center; font-size:9px; font-weight:700;
                            color:#fff; margin-right:-6px;">
                    {{ strtoupper(substr($member->name, 0, 2)) }}
                </div>
                @endforeach
                @if($project->members->count() > 4)
                <div style="width:26px; height:26px; border-radius:50%; background:#e5e7eb;
                            border:2px solid #fff; display:flex; align-items:center;
                            justify-content:center; font-size:9px; color:#6b7280; margin-right:-6px;">
                    +{{ $project->members->count() - 4 }}
                </div>
                @endif
            </div>
            <div style="display:flex; gap:12px; font-size:11px; color:#9ca3af;">
                <span>{{ $inprog }} en cours</span>
                <span>{{ $done }}/{{ $total }} faites</span>
            </div>
        </div>
    </div>
    @endforeach
</div>

@endif

<div>
    <h2 style="font-size:16px; font-weight:600; color:#111827; margin-bottom:16px; display:flex; align-items:center; gap:8px;">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
        </svg>
        Activité récente
    </h2>

    @php
        $recentComments = \App\Models\Comment::with(['user','task.project'])
            ->whereHas('task.project', function($q) {
                $q->where('owner_id', Auth::id())
                  ->orWhereHas('members', fn($q2) => $q2->where('user_id', Auth::id()));
            })
            ->latest()->take(6)->get();
    @endphp

    <div class="card" style="overflow:hidden;">
        @forelse($recentComments as $i => $comment)
        <div style="display:flex; align-items:center; gap:12px; padding:14px 20px;
                    {{ $i < $recentComments->count()-1 ? 'border-bottom:1px solid #f3f4f6;' : '' }}">
            <div style="width:32px; height:32px; border-radius:50%; background:#2d6a4f;
                        display:flex; align-items:center; justify-content:center;
                        font-size:11px; font-weight:700; color:#fff; flex-shrink:0;">
                {{ strtoupper(substr($comment->user->name, 0, 2)) }}
            </div>
            <div style="flex:1; min-width:0;">
                <p style="font-size:13px; color:#111827; margin-bottom:2px;">
                    <strong>{{ $comment->user->name }}</strong>
                    <span style="color:#6b7280; font-weight:400;"> a commenté sur </span>
                    <a href="{{ route('projects.show', $comment->task->project_id) }}"
                       style="color:#2d6a4f; font-weight:500;">{{ $comment->task->title }}</a>
                </p>
                <p style="font-size:11px; color:#9ca3af;">{{ $comment->created_at->diffForHumans() }}</p>
            </div>
        </div>
        @empty
        <div style="padding:40px; text-align:center; color:#9ca3af; font-size:13px;">
            Aucune activité récente. Commence par commenter une tâche !
        </div>
        @endforelse
    </div>
</div>

@endsection