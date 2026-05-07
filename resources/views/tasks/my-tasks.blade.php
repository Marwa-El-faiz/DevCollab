@extends('layouts.app')

@section('title', 'Mes Tâches')

@section('content')

{{-- En-tête --}}
<div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:28px;">
    <div>
        <h1 class="page-title">Mes Tâches</h1>
        <p class="page-subtitle">Toutes les tâches qui vous sont assignées</p>
    </div>
</div>

{{-- Stats rapides --}}
<div style="display:grid; grid-template-columns:repeat(4,1fr); gap:16px; margin-bottom:28px;">
    @foreach([
        ['label' => 'À faire',   'value' => $stats['todo'] ?? 0,        'color' => '#f59e0b', 'bg' => '#fef3c7'],
        ['label' => 'En cours',  'value' => $stats['in_progress'] ?? 0, 'color' => '#3b82f6', 'bg' => '#dbeafe'],
        ['label' => 'Terminées', 'value' => $stats['done'] ?? 0,        'color' => '#059669', 'bg' => '#d1fae5'],
        ['label' => 'En retard', 'value' => $stats['overdue'] ?? 0,     'color' => '#dc2626', 'bg' => '#fee2e2'],
    ] as $stat)
        <div class="card" style="padding:20px 24px;">
            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:8px;">
                <span style="font-size:12px; color:var(--text2);">
                    {{ $stat['label'] }}
                </span>

                <span style="width:28px; height:28px; border-radius:7px;
                             background:{{ $stat['bg'] }};
                             display:flex; align-items:center; justify-content:center;">
                    <span style="width:8px; height:8px; border-radius:50%; background:{{ $stat['color'] }};"></span>
                </span>
            </div>

            <p style="font-size:28px; font-weight:700; color:{{ $stat['color'] }};">
                {{ $stat['value'] }}
            </p>
        </div>
    @endforeach
</div>

{{-- Alerte retard --}}
@if(($stats['overdue'] ?? 0) > 0)
    <div style="background:#fef2f2; border:1px solid #fecaca; border-radius:10px;
                padding:12px 20px; margin-bottom:24px;
                display:flex; align-items:center; gap:10px;">
        <span style="font-size:18px;">⚠️</span>

        <p style="font-size:13px; font-weight:600; color:#dc2626;">
            Vous avez {{ $stats['overdue'] }} tâche{{ $stats['overdue'] > 1 ? 's' : '' }} en retard.
            Traitez-les en priorité.
        </p>
    </div>
@endif

{{-- Pas de tâches --}}
@if($tasks->flatten()->isEmpty())
    <div class="card" style="padding:60px; text-align:center;">
        <svg width="40" height="40" fill="none" stroke="#d1d5db" stroke-width="1.5"
             viewBox="0 0 24 24" style="margin:0 auto 16px; display:block;">
            <path d="M9 11l3 3L22 4"/>
            <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/>
        </svg>

        <p style="font-size:15px; color:var(--text2);">
            Aucune tâche ne vous est assignée pour le moment.
        </p>
    </div>
@else

    @php
        $renderTaskCard = function ($task) {
            $priorityLabel = match ($task->priority) {
                'high' => 'Haute',
                'medium' => 'Moyenne',
                'low' => 'Basse',
                default => ucfirst($task->priority ?? 'Normale'),
            };

            $priorityBg = match ($task->priority) {
                'high' => '#fee2e2',
                'medium' => '#fef3c7',
                'low' => '#e5e7eb',
                default => '#e5e7eb',
            };

            $priorityColor = match ($task->priority) {
                'high' => '#dc2626',
                'medium' => '#d97706',
                'low' => '#4b5563',
                default => '#4b5563',
            };

            $statusLabel = match ($task->status) {
                'todo' => 'À faire',
                'in_progress' => 'En cours',
                'done' => 'Terminée',
                default => ucfirst($task->status ?? ''),
            };

            return compact('priorityLabel', 'priorityBg', 'priorityColor', 'statusLabel');
        };

        $overdueList = $tasks->flatten()->filter(function ($task) {
            return $task->isOverdue();
        });
    @endphp

    {{-- Colonnes par statut --}}
    <div style="display:flex; flex-direction:column; gap:32px;">

        {{-- En retard --}}
        @if($overdueList->isNotEmpty())
            <div>
                <div style="display:flex; align-items:center; gap:8px; margin-bottom:14px;">
                    <span style="width:10px; height:10px; border-radius:50%;
                                 background:#dc2626; display:inline-block;"></span>

                    <h2 style="font-size:15px; font-weight:600; color:var(--text);">
                        En retard
                    </h2>

                    <span style="font-size:11px; background:#fee2e2; color:#dc2626;
                                 padding:2px 8px; border-radius:999px; font-weight:600;">
                        {{ $overdueList->count() }}
                    </span>
                </div>

                <div style="display:flex; flex-direction:column; gap:10px;">
                    @foreach($overdueList as $task)
                        @php($card = $renderTaskCard($task))

                        <div class="card" style="padding:16px 18px; display:flex; justify-content:space-between; gap:16px; align-items:flex-start;">
                            <div style="min-width:0; flex:1;">
                                <div style="display:flex; align-items:center; gap:8px; margin-bottom:6px; flex-wrap:wrap;">
                                    <h3 style="font-size:14px; font-weight:600; color:var(--text); margin:0;">
                                        {{ $task->title }}
                                    </h3>

                                    <span style="font-size:10px; padding:2px 7px; border-radius:999px;
                                                 background:{{ $card['priorityBg'] }};
                                                 color:{{ $card['priorityColor'] }};
                                                 font-weight:600;">
                                        {{ $card['priorityLabel'] }}
                                    </span>

                                    <span style="font-size:10px; padding:2px 7px; border-radius:999px;
                                                 background:#fee2e2; color:#dc2626; font-weight:600;">
                                        En retard
                                    </span>
                                </div>

                                @if($task->description)
                                    <p style="font-size:12px; color:var(--text2); margin-bottom:8px; line-height:1.5;">
                                        {{ $task->description }}
                                    </p>
                                @endif

                                <div style="display:flex; flex-wrap:wrap; gap:10px; font-size:12px; color:var(--text2);">
                                    @if($task->project)
                                        <span>Projet : {{ $task->project->name }}</span>
                                    @endif

                                    @if($task->due_date)
                                        <span style="color:#dc2626;">
                                            Échéance : {{ $task->due_date->format('d/m/Y') }}
                                        </span>
                                    @endif

                                    <span>Statut : {{ $card['statusLabel'] }}</span>
                                </div>
                            </div>

                            @if($task->project)
                                <a href="{{ route('projects.show', $task->project_id) }}"
                                   class="btn-secondary"
                                   style="padding:6px 10px; font-size:12px; flex-shrink:0;">
                                    Voir
                                </a>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        @foreach([
            'in_progress' => ['label' => 'En cours', 'color' => '#3b82f6', 'bg' => '#dbeafe', 'text' => '#1d4ed8'],
            'todo' => ['label' => 'À faire', 'color' => '#f59e0b', 'bg' => '#fef3c7', 'text' => '#d97706'],
            'done' => ['label' => 'Terminées', 'color' => '#059669', 'bg' => '#d1fae5', 'text' => '#065f46'],
        ] as $status => $section)
            @if(isset($tasks[$status]) && $tasks[$status]->isNotEmpty())
                <div>
                    <div style="display:flex; align-items:center; gap:8px; margin-bottom:14px;">
                        <span style="width:10px; height:10px; border-radius:50%;
                                     background:{{ $section['color'] }}; display:inline-block;"></span>

                        <h2 style="font-size:15px; font-weight:600; color:var(--text);">
                            {{ $section['label'] }}
                        </h2>

                        <span style="font-size:11px; background:{{ $section['bg'] }}; color:{{ $section['text'] }};
                                     padding:2px 8px; border-radius:999px; font-weight:600;">
                            {{ $tasks[$status]->count() }}
                        </span>
                    </div>

                    <div style="display:flex; flex-direction:column; gap:10px;">
                        @foreach($tasks[$status] as $task)
                            @php($card = $renderTaskCard($task))

                            <div class="card" style="padding:16px 18px; display:flex; justify-content:space-between; gap:16px; align-items:flex-start;">
                                <div style="min-width:0; flex:1;">
                                    <div style="display:flex; align-items:center; gap:8px; margin-bottom:6px; flex-wrap:wrap;">
                                        <h3 style="font-size:14px; font-weight:600; color:var(--text); margin:0;">
                                            {{ $task->title }}
                                        </h3>

                                        <span style="font-size:10px; padding:2px 7px; border-radius:999px;
                                                     background:{{ $card['priorityBg'] }};
                                                     color:{{ $card['priorityColor'] }};
                                                     font-weight:600;">
                                            {{ $card['priorityLabel'] }}
                                        </span>

                                        @if($task->isOverdue())
                                            <span style="font-size:10px; padding:2px 7px; border-radius:999px;
                                                         background:#fee2e2; color:#dc2626; font-weight:600;">
                                                En retard
                                            </span>
                                        @endif
                                    </div>

                                    @if($task->description)
                                        <p style="font-size:12px; color:var(--text2); margin-bottom:8px; line-height:1.5;">
                                            {{ $task->description }}
                                        </p>
                                    @endif

                                    <div style="display:flex; flex-wrap:wrap; gap:10px; font-size:12px; color:var(--text2);">
                                        @if($task->project)
                                            <span>Projet : {{ $task->project->name }}</span>
                                        @endif

                                        @if($task->due_date)
                                            <span style="color:{{ $task->isOverdue() ? '#dc2626' : 'var(--text2)' }};">
                                                Échéance : {{ $task->due_date->format('d/m/Y') }}
                                            </span>
                                        @endif

                                        <span>Statut : {{ $card['statusLabel'] }}</span>
                                    </div>
                                </div>

                                @if($task->project)
                                    <a href="{{ route('projects.show', $task->project_id) }}"
                                       class="btn-secondary"
                                       style="padding:6px 10px; font-size:12px; flex-shrink:0;">
                                        Voir
                                    </a>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @endforeach

    </div>
@endif

@endsection
