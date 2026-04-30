@extends('layouts.app')
@section('title', 'Projets')
@section('content')

<div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:28px;">
    <div>
        <h1 class="page-title">Projets</h1>
        <p class="page-subtitle">Tous tes projets et leurs tableaux Kanban</p>
    </div>
    <a href="{{ route('projects.create') }}" class="btn-primary">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
        </svg>
        Nouveau projet
    </a>
</div>

@if($projects->isEmpty())
<div class="card" style="padding:60px; text-align:center;">
    <p style="font-size:15px; color:#6b7280; margin-bottom:16px;">Aucun projet pour l'instant.</p>
    <a href="{{ route('projects.create') }}" class="btn-primary" style="display:inline-flex;">
        Créer mon premier projet
    </a>
</div>
@else

{{-- Liste des projets avec accès direct au Kanban --}}
<div style="display:flex; flex-direction:column; gap:12px;">
    @foreach($projects as $project)
    @php
        $total    = $project->tasks->count();
        $done     = $project->tasks->where('status','done')->count();
        $inprog   = $project->tasks->where('status','in_progress')->count();
        $todo     = $project->tasks->where('status','todo')->count();
        $progress = $total > 0 ? round(($done / $total) * 100) : 0;
        $statusColor = match($project->status) {
            'completed' => '#059669', 'archived' => '#9ca3af', default => '#f59e0b'
        };
    @endphp

    <div class="card" style="padding:20px 24px;">
        <div style="display:flex; align-items:center; gap:20px; flex-wrap:wrap;">

            {{-- Info projet --}}
            <div style="flex:1; min-width:200px;">
                <div style="display:flex; align-items:center; gap:8px; margin-bottom:4px;">
                    <a href="{{ route('projects.show', $project) }}"
                       style="font-size:15px; font-weight:600; color:#111827;">
                        {{ $project->name }}
                    </a>
                    <span style="font-size:10px; font-weight:600; color:{{ $statusColor }};
                                 background:{{ $statusColor }}18; padding:2px 8px; border-radius:999px;">
                        {{ ucfirst($project->status) }}
                    </span>
                </div>
                <p style="font-size:12px; color:#6b7280;">
                    {{ Str::limit($project->description ?? 'Aucune description', 60) }}
                </p>
            </div>

            {{-- Colonnes Kanban mini --}}
            <div style="display:flex; gap:8px;">
                @foreach([['todo','À faire','#f59e0b',$todo],['in_progress','En cours','#3b82f6',$inprog],['done','Terminé','#10b981',$done]] as [$s,$label,$color,$count])
                <div style="text-align:center; padding:8px 12px; background:#f9fafb;
                            border:1px solid #e5e7eb; border-radius:8px; min-width:70px;">
                    <div style="font-size:18px; font-weight:700; color:{{ $color }};">{{ $count }}</div>
                    <div style="font-size:10px; color:#9ca3af;">{{ $label }}</div>
                </div>
                @endforeach
            </div>

            {{-- Progress --}}
            <div style="min-width:120px;">
                <div style="display:flex; justify-content:space-between; margin-bottom:5px;">
                    <span style="font-size:11px; color:#6b7280;">Progression</span>
                    <span style="font-size:11px; font-weight:600; color:#111827;">{{ $progress }}%</span>
                </div>
                <div style="background:#e5e7eb; border-radius:999px; height:4px;">
                    <div style="background:#2d6a4f; height:100%; border-radius:999px; width:{{ $progress }}%;"></div>
                </div>
            </div>

            {{-- Membres --}}
            <div style="display:flex; align-items:center; gap:-4px;">
                @foreach($project->members->take(3) as $member)
                <div title="{{ $member->name }}"
                     style="width:28px; height:28px; border-radius:50%; background:#374151;
                            border:2px solid #fff; display:flex; align-items:center;
                            justify-content:center; font-size:9px; font-weight:700;
                            color:#fff; margin-right:-6px;">
                    {{ strtoupper(substr($member->name, 0, 2)) }}
                </div>
                @endforeach
            </div>

            {{-- Actions --}}
            <div style="display:flex; gap:8px; flex-shrink:0;">
                <a href="{{ route('projects.show', $project) }}" class="btn-primary" style="font-size:12px; padding:7px 14px;">
                    Ouvrir Kanban
                </a>
                <a href="{{ route('projects.edit', $project) }}" class="btn-secondary" style="font-size:12px; padding:7px 14px;">
                    Modifier
                </a>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endif

@endsection