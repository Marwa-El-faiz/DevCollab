@extends('layouts.app')
@section('title', 'Analytics')
@section('content')

<div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:28px;">
    <div>
        <h1 class="page-title">Analytics</h1>
        <p class="page-subtitle">Vue d'ensemble de tes projets et de ton équipe</p>
    </div>
</div>

{{-- ══ STATS GLOBALES ══ --}}
<div style="display:grid; grid-template-columns:repeat(4,1fr); gap:16px; margin-bottom:28px;">

    @foreach([
        ['Projets actifs',   $stats['active_projects'],  '#2d6a4f', '#d1fae5', '<path d="M3 3h18v18H3z" stroke-width="0"/><rect x="3" y="3" width="18" height="18" rx="2"/><line x1="3" y1="9" x2="21" y2="9"/><line x1="9" y1="21" x2="9" y2="9"/>'],
        ['Tâches totales',   $stats['total_tasks'],      '#1d4ed8', '#dbeafe', '<line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/>'],
        ['Tâches terminées', $stats['done_tasks'],       '#059669', '#d1fae5', '<polyline points="20 6 9 17 4 12"/>'],
        ['En retard',        $stats['overdue_tasks'],    '#dc2626', '#fee2e2', '<circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>'],
    ] as [$label, $value, $color, $bg, $icon])
    <div class="card" style="padding:20px;">
        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:12px;">
            <div style="width:36px; height:36px; border-radius:8px; background:{{ $bg }};
                        display:flex; align-items:center; justify-content:center;">
                <svg width="16" height="16" fill="none" stroke="{{ $color }}" stroke-width="2" viewBox="0 0 24 24">
                    {!! $icon !!}
                </svg>
            </div>
        </div>
        <div style="font-size:28px; font-weight:700; color:{{ $color }}; margin-bottom:4px;">
            {{ $value }}
        </div>
        <div style="font-size:12px; color:var(--text2);">{{ $label }}</div>
    </div>
    @endforeach

</div>

{{-- ══ STATS SECONDAIRES ══ --}}
<div style="display:grid; grid-template-columns:repeat(4,1fr); gap:16px; margin-bottom:28px;">
    @foreach([
        ['À faire',     $stats['todo_tasks'],    '#f59e0b'],
        ['En cours',    $stats['inprog_tasks'],  '#3b82f6'],
        ['Membres',     $stats['total_members'], '#8b5cf6'],
        ['Commentaires',$stats['total_comments'],'#6b7280'],
    ] as [$label, $value, $color])
    <div class="card" style="padding:16px; display:flex; align-items:center; gap:14px;">
        <div style="width:8px; height:40px; background:{{ $color }}; border-radius:4px; flex-shrink:0;"></div>
        <div>
            <div style="font-size:22px; font-weight:700; color:var(--text);">{{ $value }}</div>
            <div style="font-size:12px; color:var(--text2);">{{ $label }}</div>
        </div>
    </div>
    @endforeach
</div>

{{-- ══ GRAPHIQUES ══ --}}
<div style="display:grid; grid-template-columns:1fr 1fr; gap:20px; margin-bottom:28px;">

    {{-- Graphique barres — progression par projet --}}
    <div class="card" style="padding:24px;">
        <h3 style="font-size:14px; font-weight:600; color:var(--text); margin-bottom:20px;">
            Progression par projet
        </h3>
        <canvas id="projectChart" height="220"></canvas>
    </div>

    {{-- Graphique donut — tâches par statut --}}
    <div class="card" style="padding:24px;">
        <h3 style="font-size:14px; font-weight:600; color:var(--text); margin-bottom:20px;">
            Répartition des tâches
        </h3>
        <div style="display:flex; align-items:center; gap:24px;">
            <canvas id="statusChart" width="180" height="180" style="flex-shrink:0;"></canvas>
            <div style="flex:1;">
                @foreach([
                    ['À faire',  $stats['todo_tasks'],   '#f59e0b'],
                    ['En cours', $stats['inprog_tasks'], '#3b82f6'],
                    ['Terminé',  $stats['done_tasks'],   '#10b981'],
                    ['En retard',$stats['overdue_tasks'],'#ef4444'],
                ] as [$label, $value, $color])
                <div style="display:flex; align-items:center; justify-content:space-between;
                            margin-bottom:10px;">
                    <div style="display:flex; align-items:center; gap:8px;">
                        <div style="width:10px; height:10px; border-radius:50%; background:{{ $color }};"></div>
                        <span style="font-size:12px; color:var(--text2);">{{ $label }}</span>
                    </div>
                    <span style="font-size:13px; font-weight:600; color:var(--text);">{{ $value }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

</div>

<div style="display:grid; grid-template-columns:1fr 1fr; gap:20px; margin-bottom:28px;">

    {{-- Graphique priorités --}}
    <div class="card" style="padding:24px;">
        <h3 style="font-size:14px; font-weight:600; color:var(--text); margin-bottom:20px;">
            Tâches par priorité
        </h3>
        <canvas id="priorityChart" height="200"></canvas>
    </div>

    {{-- Performance équipe --}}
    <div class="card" style="padding:24px;">
        <h3 style="font-size:14px; font-weight:600; color:var(--text); margin-bottom:16px;">
            Performance par membre
        </h3>
        @forelse($memberStats as $member)
        <div style="margin-bottom:14px;">
            <div style="display:flex; align-items:center; justify-content:space-between;
                        margin-bottom:5px;">
                <div style="display:flex; align-items:center; gap:8px;">
                    <div style="width:26px; height:26px; border-radius:50%;
                                background:#374151; display:flex; align-items:center;
                                justify-content:center; font-size:9px;
                                font-weight:700; color:#fff; flex-shrink:0;">
                        {{ $member['initials'] }}
                    </div>
                    <span style="font-size:13px; color:var(--text); font-weight:500;">
                        {{ $member['name'] }}
                    </span>
                </div>
                <span style="font-size:12px; color:var(--text2);">
                    {{ $member['done'] }}/{{ $member['assigned'] }} · {{ $member['percent'] }}%
                </span>
            </div>
            <div style="background:var(--border); border-radius:999px; height:5px; overflow:hidden;">
                <div style="background:{{ $member['percent'] >= 75 ? '#10b981' : ($member['percent'] >= 40 ? '#f59e0b' : '#ef4444') }};
                            height:100%; border-radius:999px;
                            width:{{ $member['percent'] }}%;
                            transition:width 0.3s;">
                </div>
            </div>
        </div>
        @empty
        <p style="font-size:13px; color:var(--text2); text-align:center; padding:20px 0;">
            Aucune tâche assignée pour l'instant.
        </p>
        @endforelse
    </div>

</div>

{{-- Tableau détaillé projets --}}
<div class="card" style="padding:24px;">
    <h3 style="font-size:14px; font-weight:600; color:var(--text); margin-bottom:20px;">
        Détail par projet
    </h3>
    <table style="width:100%; border-collapse:collapse;">
        <thead>
            <tr style="border-bottom:1px solid var(--border);">
                @foreach(['Projet','À faire','En cours','Terminé','Total','Progression'] as $col)
                <th style="text-align:left; padding:8px 12px; font-size:11px;
                           font-weight:600; color:var(--text2); text-transform:uppercase;">
                    {{ $col }}
                </th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($projectData as $p)
            <tr style="border-bottom:1px solid var(--border);"
                onmouseover="this.style.background='var(--input-bg)'"
                onmouseout="this.style.background='transparent'">
                <td style="padding:12px; font-size:13px; font-weight:500; color:var(--text);">
                    {{ $p['name'] }}
                </td>
                <td style="padding:12px;">
                    <span style="font-size:12px; color:#f59e0b; font-weight:600;">{{ $p['todo'] }}</span>
                </td>
                <td style="padding:12px;">
                    <span style="font-size:12px; color:#3b82f6; font-weight:600;">{{ $p['inprog'] }}</span>
                </td>
                <td style="padding:12px;">
                    <span style="font-size:12px; color:#10b981; font-weight:600;">{{ $p['done'] }}</span>
                </td>
                <td style="padding:12px;">
                    <span style="font-size:12px; color:var(--text2);">{{ $p['total'] }}</span>
                </td>
                <td style="padding:12px; min-width:120px;">
                    <div style="display:flex; align-items:center; gap:8px;">
                        <div style="flex:1; background:var(--border); border-radius:999px; height:5px;">
                            <div style="background:#2d6a4f; height:100%; border-radius:999px;
                                        width:{{ $p['percent'] }}%;"></div>
                        </div>
                        <span style="font-size:11px; font-weight:600; color:var(--text); min-width:28px;">
                            {{ $p['percent'] }}%
                        </span>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

{{-- Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
const isDark    = document.body.classList.contains('dark');
const textColor = isDark ? '#9ca3af' : '#6b7280';
const gridColor = isDark ? '#2d3139' : '#e5e7eb';

Chart.defaults.color = textColor;
Chart.defaults.font.family = '-apple-system, BlinkMacSystemFont, Segoe UI, sans-serif';
Chart.defaults.font.size = 11;

// ── Graphique barres — Projets ──
const projectData = @json($projectData);

new Chart(document.getElementById('projectChart'), {
    type: 'bar',
    data: {
        labels: projectData.map(p => p.name),
        datasets: [
            {
                label: 'À faire',
                data: projectData.map(p => p.todo),
                backgroundColor: '#fbbf24',
                borderRadius: 4,
            },
            {
                label: 'En cours',
                data: projectData.map(p => p.inprog),
                backgroundColor: '#60a5fa',
                borderRadius: 4,
            },
            {
                label: 'Terminé',
                data: projectData.map(p => p.done),
                backgroundColor: '#34d399',
                borderRadius: 4,
            },
        ],
    },
    options: {
        responsive: true,
        plugins: {
            legend: { position: 'bottom', labels: { boxWidth: 10, padding: 12 } },
        },
        scales: {
            x: { stacked: true, grid: { color: gridColor } },
            y: { stacked: true, grid: { color: gridColor }, ticks: { stepSize: 1 } },
        },
    },
});

// ── Graphique donut — Statuts ──
new Chart(document.getElementById('statusChart'), {
    type: 'doughnut',
    data: {
        labels: ['À faire', 'En cours', 'Terminé', 'En retard'],
        datasets: [{
            data: [
                {{ $stats['todo_tasks'] }},
                {{ $stats['inprog_tasks'] }},
                {{ $stats['done_tasks'] }},
                {{ $stats['overdue_tasks'] }},
            ],
            backgroundColor: ['#fbbf24', '#60a5fa', '#34d399', '#f87171'],
            borderWidth: 0,
            hoverOffset: 4,
        }],
    },
    options: {
        responsive: false,
        plugins: { legend: { display: false } },
        cutout: '65%',
    },
});

// ── Graphique priorités ──
new Chart(document.getElementById('priorityChart'), {
    type: 'bar',
    data: {
        labels: ['Haute', 'Moyenne', 'Basse'],
        datasets: [{
            label: 'Tâches',
            data: [
                {{ $priorityData['high'] }},
                {{ $priorityData['medium'] }},
                {{ $priorityData['low'] }},
            ],
            backgroundColor: ['#f87171', '#fbbf24', '#9ca3af'],
            borderRadius: 6,
        }],
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            x: { grid: { display: false } },
            y: { grid: { color: gridColor }, ticks: { stepSize: 1 } },
        },
    },
});
</script>

@endsection