@extends('layouts.app')
@section('title', 'Calendrier')
@section('content')

<div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:24px;">
    <div>
        <h1 class="page-title">Calendrier</h1>
        <p class="page-subtitle">Visualise toutes tes tâches par date limite</p>
    </div>
</div>

{{-- ══ FILTRES ══ --}}
<div class="card" style="padding:16px 20px; margin-bottom:20px;
     display:flex; align-items:center; gap:16px; flex-wrap:wrap;">

    {{-- Filtre projet --}}
    <div style="display:flex; align-items:center; gap:8px;">
        <label style="font-size:12px; font-weight:500; color:var(--text2);">Projet</label>
        <select id="filter-project" class="form-input" style="width:180px; padding:6px 10px;">
            <option value="">Tous les projets</option>
            @foreach($projects as $project)
                <option value="{{ $project->id }}">{{ $project->name }}</option>
            @endforeach
        </select>
    </div>

    {{-- Filtre statut --}}
    <div style="display:flex; align-items:center; gap:8px;">
        <label style="font-size:12px; font-weight:500; color:var(--text2);">Statut</label>
        <select id="filter-status" class="form-input" style="width:140px; padding:6px 10px;">
            <option value="">Tous</option>
            <option value="todo">À faire</option>
            <option value="in_progress">En cours</option>
            <option value="done">Terminé</option>
        </select>
    </div>

    {{-- Légende --}}
    <div style="margin-left:auto; display:flex; align-items:center; gap:12px; flex-wrap:wrap;">
        @foreach([
            ['À faire',  '#f59e0b'],
            ['En cours', '#3b82f6'],
            ['Terminé',  '#10b981'],
            ['En retard','#ef4444'],
        ] as [$label, $color])
        <div style="display:flex; align-items:center; gap:5px;">
            <div style="width:10px; height:10px; border-radius:50%; background:{{ $color }};"></div>
            <span style="font-size:11px; color:var(--text2);">{{ $label }}</span>
        </div>
        @endforeach
    </div>
</div>

{{-- ══ CALENDRIER ══ --}}
<div class="card" style="padding:24px;">
    <div id="calendar"></div>
</div>

{{-- ══ MODAL DÉTAIL TÂCHE ══ --}}
<div id="modal-task-detail"
     style="display:none; position:fixed; inset:0;
            background:rgba(0,0,0,0.5); z-index:9999;
            align-items:center; justify-content:center; padding:20px;">
    <div style="background:var(--card); border-radius:12px;
                padding:28px; max-width:400px; width:90%;
                border:1px solid var(--border);">

        <div style="display:flex; align-items:flex-start;
                    justify-content:space-between; margin-bottom:20px;">
            <h3 id="modal-title"
                style="font-size:16px; font-weight:600;
                       color:var(--text); flex:1; margin-right:12px;">
            </h3>
            <button onclick="closeModal()"
                    style="background:none; border:none; cursor:pointer;
                           color:var(--text2); padding:2px;">
                <svg width="18" height="18" fill="none" stroke="currentColor"
                     stroke-width="2" viewBox="0 0 24 24">
                    <line x1="18" y1="6" x2="6" y2="18"/>
                    <line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>

        <div style="display:flex; flex-direction:column; gap:12px;">

            <div style="display:flex; align-items:center; gap:10px;">
                <svg width="14" height="14" fill="none" stroke="#6b7280"
                     stroke-width="2" viewBox="0 0 24 24">
                    <rect x="3" y="3" width="18" height="18" rx="2"/>
                    <line x1="3" y1="9" x2="21" y2="9"/>
                </svg>
                <span style="font-size:13px; color:var(--text2);">Projet :</span>
                <span id="modal-project"
                      style="font-size:13px; font-weight:500; color:var(--text);">
                </span>
            </div>

            <div style="display:flex; align-items:center; gap:10px;">
                <svg width="14" height="14" fill="none" stroke="#6b7280"
                     stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10"/>
                    <polyline points="12 6 12 12 16 14"/>
                </svg>
                <span style="font-size:13px; color:var(--text2);">Statut :</span>
                <span id="modal-status"
                      style="font-size:12px; font-weight:500;
                             padding:2px 10px; border-radius:999px;">
                </span>
            </div>

            <div style="display:flex; align-items:center; gap:10px;">
                <svg width="14" height="14" fill="none" stroke="#6b7280"
                     stroke-width="2" viewBox="0 0 24 24">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                    <circle cx="12" cy="7" r="4"/>
                </svg>
                <span style="font-size:13px; color:var(--text2);">Assigné à :</span>
                <span id="modal-assignee"
                      style="font-size:13px; font-weight:500; color:var(--text);">
                </span>
            </div>

            <div style="display:flex; align-items:center; gap:10px;">
                <svg width="14" height="14" fill="none" stroke="#6b7280"
                     stroke-width="2" viewBox="0 0 24 24">
                    <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                </svg>
                <span style="font-size:13px; color:var(--text2);">Priorité :</span>
                <span id="modal-priority"
                      style="font-size:13px; font-weight:500; color:var(--text);">
                </span>
            </div>

        </div>

        <div style="display:flex; gap:10px; margin-top:24px;">
            <a id="modal-link" href="#" class="btn-primary"
               style="flex:1; justify-content:center;">
                Voir le projet
            </a>
            <button onclick="closeModal()" class="btn-secondary"
                    style="flex:1; justify-content:center;">
                Fermer
            </button>
        </div>
    </div>
</div>

{{-- FullCalendar --}}
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>

<style>
    /* Adapter FullCalendar au dark mode */
    .fc { color: var(--text) !important; }
    .fc-theme-standard td, .fc-theme-standard th { border-color: var(--border) !important; }
    .fc-theme-standard .fc-scrollgrid { border-color: var(--border) !important; }
    .fc-col-header-cell { background: var(--input-bg) !important; }
    .fc-daygrid-day:hover { background: var(--input-bg) !important; }
    .fc-day-today { background: {{ Auth::user()->theme === 'dark' ? '#1e3a2f' : '#f0fdf4' }} !important; }
    .fc-toolbar-title { font-size: 16px !important; font-weight: 600 !important; color: var(--text) !important; }
    .fc-button { background: #2d6a4f !important; border-color: #2d6a4f !important; font-size: 12px !important; }
    .fc-button:hover { background: #1b4332 !important; border-color: #1b4332 !important; }
    .fc-button-active { background: #1b4332 !important; }
    .fc-event { cursor: pointer; font-size: 11px !important; padding: 2px 4px !important; }
    .fc-event-title { font-weight: 500 !important; }
    .fc-daygrid-day-number { color: var(--text2) !important; font-size: 12px !important; }
    .fc-col-header-cell-cushion { color: var(--text2) !important; font-size: 12px !important; font-weight: 600 !important; }
    .fc-scrollgrid-sync-inner { background: var(--card) !important; }
</style>

<script>
const CSRF      = document.querySelector('meta[name="csrf-token"]').content;
const statusLabels = {
    todo:        'À faire',
    in_progress: 'En cours',
    done:        'Terminé',
};
const statusColors = {
    todo:        { bg: '#fef3c7', color: '#92400e' },
    in_progress: { bg: '#dbeafe', color: '#1e40af' },
    done:        { bg: '#d1fae5', color: '#065f46' },
};
const priorityLabels = {
    high:   '🔴 Haute',
    medium: '🟡 Moyenne',
    low:    '⚪ Basse',
};

let calendar;

document.addEventListener('DOMContentLoaded', function() {

    const calendarEl = document.getElementById('calendar');

    calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'fr',
        headerToolbar: {
            left:   'prev,next today',
            center: 'title',
            right:  'dayGridMonth,dayGridWeek,listMonth',
        },
        buttonText: {
            today:      'Aujourd\'hui',
            month:      'Mois',
            week:       'Semaine',
            list:       'Liste',
        },
        height:    'auto',
        editable:  true,   // Drag & drop activé
        eventSources: [{
            url:    '{{ route("calendar.events") }}',
            method: 'GET',
            extraParams: function() {
                return {
                    project_id: document.getElementById('filter-project').value,
                    status:     document.getElementById('filter-status').value,
                };
            },
        }],

        // Clic sur un événement → ouvrir le modal
        eventClick: function(info) {
            const props = info.event.extendedProps;
            const title = info.event.title;

            document.getElementById('modal-title').textContent = title;
            document.getElementById('modal-project').textContent = props.project;
            document.getElementById('modal-assignee').textContent = props.assignee;
            document.getElementById('modal-priority').textContent = priorityLabels[props.priority] || props.priority;

            const statusEl = document.getElementById('modal-status');
            const sc = props.is_overdue
                ? { bg: '#fee2e2', color: '#991b1b' }
                : (statusColors[props.status] || { bg: '#f3f4f6', color: '#374151' });
            statusEl.textContent = props.is_overdue ? '⚠️ En retard' : (statusLabels[props.status] || props.status);
            statusEl.style.background = sc.bg;
            statusEl.style.color      = sc.color;

            document.getElementById('modal-link').href =
                '/projects/' + props.project_id;

            document.getElementById('modal-task-detail').style.display = 'flex';
        },

        // Drag & drop pour changer la date
        eventDrop: function(info) {
            const taskId  = info.event.id;
            const newDate = info.event.startStr;

            fetch(`/calendar/tasks/${taskId}/date`, {
                method:  'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF,
                },
                body: JSON.stringify({ due_date: newDate }),
            })
            .then(r => r.json())
            .then(data => {
                if (!data.success) info.revert();
            })
            .catch(() => info.revert());
        },

        // Afficher le nombre de tâches si plusieurs le même jour
        dayMaxEvents: 3,

        // Message "voir plus"
        moreLinkText: function(num) {
            return '+' + num + ' tâches';
        },
    });

    calendar.render();

    // Filtres — rechargement des événements
    document.getElementById('filter-project').addEventListener('change', function() {
        calendar.refetchEvents();
    });

    document.getElementById('filter-status').addEventListener('change', function() {
        calendar.refetchEvents();
    });
});

function closeModal() {
    document.getElementById('modal-task-detail').style.display = 'none';
}

// Fermer en cliquant en dehors
document.getElementById('modal-task-detail').addEventListener('click', function(e) {
    if (e.target === this) closeModal();
});
</script>

@endsection