@extends('layouts.app')
@section('title', $project->name)
@section('content')


<div style="display:flex; justify-content:space-between;
            align-items:flex-start; margin-bottom:32px;">
    <div>
        <a href="{{ route('dashboard') }}"
           style="font-size:13px; color:#6b7280; text-decoration:none;
                  margin-bottom:8px; display:inline-block;">
            ← Retour au dashboard
        </a>
        <h1 class="page-title">{{ $project->name }}</h1>
        <p class="page-subtitle">{{ $project->description ?? 'Aucune description.' }}</p>
    </div>
    <div style="display:flex; gap:10px; align-items:center; flex-wrap:wrap;">
        @php
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
        <span style="font-size:12px; font-weight:600; color:{{ $statusColor }};
                     background:{{ $statusColor }}18; padding:4px 12px;
                     border-radius:999px; border:1px solid {{ $statusColor }}44;">
            {{ $statusLabel }}
        </span>

        <form method="POST" action="{{ route('projects.generate-tasks', $project) }}"
              onsubmit="
                this.querySelector('button').disabled = true;
                this.querySelector('button').innerHTML = '⏳ Génération...';
              ">
            @csrf
            <button type="submit"
                    style="background: linear-gradient(135deg, #6366f1, #8b5cf6);
                           color:#fff; border:none; padding:10px 18px;
                           border-radius:8px; font-size:13px; font-weight:500;
                           cursor:pointer; display:inline-flex;
                           align-items:center; gap:6px; transition:opacity 0.2s;"
                    onmouseover="this.style.opacity='0.85'"
                    onmouseout="this.style.opacity='1'">
                ✨ Générer avec l'IA
            </button>
        </form>

        <a href="{{ route('projects.edit', $project) }}" class="btn-primary">
            <svg width="14" height="14" fill="none" stroke="currentColor"
                 stroke-width="2" viewBox="0 0 24 24">
                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
            </svg>
            Modifier
        </a>
    </div>
</div>

@php
    $total    = $project->tasks->count();
    $todo     = $project->tasks->where('status', 'todo')->count();
    $inprog   = $project->tasks->where('status', 'in_progress')->count();
    $done     = $project->tasks->where('status', 'done')->count();
    $progress = $total > 0 ? round(($done / $total) * 100) : 0;
@endphp

<div style="display:grid; grid-template-columns:repeat(4,1fr);
            gap:16px; margin-bottom:24px;">
    @foreach([
        ['label' => 'Total',    'value' => $total,  'color' => '#6b7280'],
        ['label' => 'À faire',  'value' => $todo,   'color' => '#f59e0b'],
        ['label' => 'En cours', 'value' => $inprog, 'color' => '#3b82f6'],
        ['label' => 'Terminé',  'value' => $done,   'color' => '#059669'],
    ] as $stat)
    <div style="background:#fff; border:1px solid #e5e7eb;
                border-radius:12px; padding:20px 24px;">
        <p style="font-size:28px; font-weight:700;
                  color:{{ $stat['color'] }}; margin-bottom:4px;">
            {{ $stat['value'] }}
        </p>
        <p style="font-size:13px; color:#6b7280;">{{ $stat['label'] }}</p>
    </div>
    @endforeach
</div>

<div style="background:#fff; border:1px solid #e5e7eb;
            border-radius:12px; padding:20px 24px; margin-bottom:32px;">
    <div style="display:flex; justify-content:space-between; margin-bottom:10px;">
        <span style="font-size:14px; font-weight:600; color:#111827;">
            Progression globale
        </span>
        <span style="font-size:16px; font-weight:700;
                     color:{{ $progress >= 100 ? '#059669' : '#2d6a4f' }};">
            {{ $progress }}%
        </span>
    </div>
    <div style="background:#e5e7eb; border-radius:999px; height:8px; overflow:hidden;">
        <div style="background:{{ $progress >= 100 ? '#059669' : '#2d6a4f' }};
                    height:100%; border-radius:999px; width:{{ $progress }}%;
                    transition:width 0.3s ease;"></div>
    </div>
</div>

<div style="display:grid; grid-template-columns:1fr 300px; gap:24px;">

    <div>

        <div style="display:flex; justify-content:space-between;
                    align-items:center; margin-bottom:16px;">
            <h2 style="font-size:16px; font-weight:600; color:#111827;">
                Kanban Board
            </h2>
            <button onclick="document.getElementById('modal-task').style.display='flex'"
                    class="btn-primary" style="font-size:13px; padding:8px 14px;">
                + Nouvelle tâche
            </button>
        </div>

        <div style="display:grid; grid-template-columns:repeat(3,1fr);
                    gap:16px; margin-bottom:40px;">

            @foreach(['todo' => ['label'=>'À faire','color'=>'#f59e0b'],
                      'in_progress' => ['label'=>'En cours','color'=>'#3b82f6'],
                      'done' => ['label'=>'Terminé','color'=>'#059669']] as $status => $col)

            <div style="background:#f9fafb; border-radius:12px; padding:16px;">

                <div style="display:flex; align-items:center; gap:8px; margin-bottom:16px;">
                    <span style="width:8px; height:8px; border-radius:50%;
                                 background:{{ $col['color'] }}; display:inline-block;"></span>
                    <span style="font-size:13px; font-weight:600; color:#374151;">
                        {{ $col['label'] }}
                    </span>
                    <span style="margin-left:auto; font-size:12px; color:#9ca3af;
                                 background:#e5e7eb; padding:1px 8px; border-radius:999px;">
                        {{ $project->tasks->where('status', $status)->count() }}
                    </span>
                </div>

                @foreach($project->tasks->where('status', $status) as $task)
                @php
                    $priorityColor = match($task->priority) {
                        'high'   => '#dc2626',
                        'medium' => '#f59e0b',
                        default  => '#6b7280',
                    };
                @endphp
                <div style="background:#fff; border:1px solid #e5e7eb;
                            border-radius:8px; padding:14px; margin-bottom:10px;
                            {{ $task->ai_generated ? 'border-left:3px solid #8b5cf6;' : '' }}">

                    @if($task->ai_generated)
                    <div style="margin-bottom:6px;">
                        <span style="font-size:10px; color:#8b5cf6;
                                     background:#f3f0ff; padding:2px 6px;
                                     border-radius:4px; font-weight:600;">
                            ✨ IA
                        </span>
                    </div>
                    @endif

                    <div style="display:flex; justify-content:space-between;
                                align-items:flex-start; margin-bottom:8px;">
                        <span style="font-size:10px; font-weight:600;
                                     color:{{ $priorityColor }};
                                     background:{{ $priorityColor }}18;
                                     padding:2px 8px; border-radius:999px;
                                     text-transform:uppercase;">
                            {{ $task->priority }}
                        </span>
                        <div style="display:flex; gap:4px;">
                            <button onclick="openEditModal({{ $task->id }}, '{{ addslashes($task->title) }}', '{{ addslashes($task->description ?? '') }}', '{{ $task->priority }}', '{{ $task->status }}')"
                                    style="background:none; border:none; cursor:pointer;
                                           color:#9ca3af; padding:2px;" title="Modifier">
                                <svg width="12" height="12" fill="none" stroke="currentColor"
                                     stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                </svg>
                            </button>
                            <form method="POST"
                                  action="{{ route('tasks.destroy', [$project, $task]) }}"
                                  onsubmit="return confirm('Supprimer ?')"
                                  style="display:inline;">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        style="background:none; border:none;
                                               cursor:pointer; color:#dc2626; padding:2px;">
                                    <svg width="12" height="12" fill="none" stroke="currentColor"
                                         stroke-width="2" viewBox="0 0 24 24">
                                        <polyline points="3 6 5 6 21 6"/>
                                        <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>

                    <p style="font-size:13px; font-weight:500; color:#111827;
                              margin-bottom:8px; line-height:1.4;
                              {{ $status === 'done' ? 'text-decoration:line-through;color:#9ca3af;' : '' }}">
                        {{ $task->title }}
                    </p>

                    @if($task->description)
                    <p style="font-size:11px; color:#9ca3af; margin-bottom:8px; line-height:1.4;">
                        {{ Str::limit($task->description, 60) }}
                    </p>
                    @endif

                    <div style="display:flex; justify-content:space-between;
                                align-items:center; margin-bottom:8px;">
                        @if($task->due_date)
                        <span style="font-size:11px; color:#9ca3af;">
                            {{ \Carbon\Carbon::parse($task->due_date)->format('d M') }}
                        </span>
                        @else<span></span>@endif
                        @if($task->assignee)
                        <div title="{{ $task->assignee->name }}"
                             style="width:24px; height:24px; border-radius:50%;
                                    background:#374151; display:flex; align-items:center;
                                    justify-content:center; font-size:9px;
                                    font-weight:600; color:#fff;">
                            {{ strtoupper(substr($task->assignee->name, 0, 2)) }}
                        </div>
                        @endif
                    </div>

                    <div style="border-top:1px solid #f3f4f6; padding-top:8px;">
                        <form method="POST" action="{{ route('tasks.update', [$project, $task]) }}">
                            @csrf @method('PATCH')
                            <input type="hidden" name="title" value="{{ $task->title }}">
                            <input type="hidden" name="priority" value="{{ $task->priority }}">
                            <select name="status" onchange="this.form.submit()"
                                    style="width:100%; font-size:11px; border:1px solid #e5e7eb;
                                           border-radius:6px; padding:4px 6px;
                                           background:#f9fafb; color:#6b7280;
                                           cursor:pointer; outline:none;">
                                <option value="todo"        {{ $task->status==='todo'        ?'selected':'' }}>→ À faire</option>
                                <option value="in_progress" {{ $task->status==='in_progress' ?'selected':'' }}>→ En cours</option>
                                <option value="done"        {{ $task->status==='done'        ?'selected':'' }}>→ Terminé</option>
                            </select>
                        </form>
                    </div>
                </div>
                @endforeach

            </div>
            @endforeach
        </div>

        <div>
            <h2 style="font-size:16px; font-weight:600; color:#111827; margin-bottom:16px;">
                Commentaires
            </h2>

            @php
                $comments = \App\Models\Comment::with(['user','task'])
                    ->whereHas('task', fn($q) => $q->where('project_id', $project->id))
                    ->latest()->take(10)->get();
            @endphp

            <div style="background:#fff; border:1px solid #e5e7eb;
                        border-radius:12px; overflow:hidden; margin-bottom:16px;">
                @forelse($comments as $comment)
                <div style="display:flex; gap:12px; padding:16px 20px;
                            border-bottom:1px solid #f3f4f6; align-items:flex-start;">
                    <div style="width:34px; height:34px; border-radius:50%;
                                background:#2d6a4f; display:flex; align-items:center;
                                justify-content:center; font-size:11px;
                                font-weight:600; color:#fff; flex-shrink:0;">
                        {{ strtoupper(substr($comment->user->name, 0, 2)) }}
                    </div>
                    <div style="flex:1;">
                        <div style="display:flex; justify-content:space-between;
                                    align-items:center; margin-bottom:4px;">
                            <span style="font-size:13px; font-weight:600; color:#111827;">
                                {{ $comment->user->name }}
                            </span>
                            <div style="display:flex; align-items:center; gap:8px;">
                                <span style="font-size:11px; color:#9ca3af;">
                                    {{ $comment->created_at->diffForHumans() }}
                                </span>
                                @if($comment->user_id === Auth::id())
                                <form method="POST"
                                      action="{{ route('comments.destroy', [$comment->task, $comment]) }}"
                                      onsubmit="return confirm('Supprimer ?')"
                                      style="display:inline;">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            style="background:none; border:none;
                                                   cursor:pointer; color:#dc2626;
                                                   font-size:11px; padding:0;">
                                        Supprimer
                                    </button>
                                </form>
                                @endif
                            </div>
                        </div>
                        <p style="font-size:13px; color:#374151; line-height:1.5;">
                            {{ $comment->body }}
                        </p>
                        <p style="font-size:11px; color:#9ca3af; margin-top:4px;">
                            Sur : <em>{{ $comment->task->title }}</em>
                        </p>
                    </div>
                </div>
                @empty
                <div style="padding:32px; text-align:center; color:#9ca3af; font-size:14px;">
                    Aucun commentaire pour l'instant.
                </div>
                @endforelse
            </div>

            @if($project->tasks->isNotEmpty())
            <div style="background:#fff; border:1px solid #e5e7eb;
                        border-radius:12px; padding:20px;">
                <h3 style="font-size:14px; font-weight:600; color:#111827; margin-bottom:12px;">
                    Ajouter un commentaire
                </h3>
                <form method="POST" id="comment-form"
                      action="{{ route('comments.store', $project->tasks->first()) }}">
                    @csrf
                    <select onchange="updateCommentAction(this)"
                            style="width:100%; padding:8px 12px; border:1px solid #d1d5db;
                                   border-radius:8px; font-size:13px; margin-bottom:10px;
                                   background:#f9fafb; outline:none;">
                        @foreach($project->tasks as $t)
                        <option value="{{ $t->id }}" data-url="{{ route('comments.store', $t) }}">
                            {{ $t->title }}
                        </option>
                        @endforeach
                    </select>
                    <textarea name="body" rows="3" placeholder="Écris ton commentaire..."
                              style="width:100%; padding:10px 14px; border:1px solid #d1d5db;
                                     border-radius:8px; font-size:13px; outline:none;
                                     resize:vertical; background:#f9fafb; margin-bottom:10px;">{{ old('body') }}</textarea>
                    @error('body')
                    <p style="color:#dc2626; font-size:12px; margin-bottom:8px;">{{ $message }}</p>
                    @enderror
                    <button type="submit" class="btn-primary" style="font-size:13px;">
                        Envoyer
                    </button>
                </form>
            </div>
            @endif
        </div>
    </div>

    <div>
        <h2 style="font-size:16px; font-weight:600; color:#111827; margin-bottom:16px;">
            Membres ({{ $project->members->count() }})
        </h2>
        <div style="background:#fff; border:1px solid #e5e7eb;
                    border-radius:12px; overflow:hidden; margin-bottom:16px;">
            @foreach($project->members as $member)
            <div style="display:flex; align-items:center; gap:12px;
                        padding:14px 16px; border-bottom:1px solid #f3f4f6;">
                <div style="width:36px; height:36px; border-radius:50%;
                            background:#2d6a4f; display:flex; align-items:center;
                            justify-content:center; font-size:12px;
                            font-weight:600; color:#fff; flex-shrink:0;">
                    {{ strtoupper(substr($member->name, 0, 2)) }}
                </div>
                <div style="flex:1; min-width:0;">
                    <p style="font-size:13px; font-weight:500; color:#111827;
                              white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                        {{ $member->name }}
                        @if($member->id === $project->owner_id)
                        <span style="font-size:10px; color:#2d6a4f; font-weight:600;">(Owner)</span>
                        @endif
                    </p>
                    <p style="font-size:11px; color:#9ca3af; text-transform:capitalize;">
                        {{ $member->pivot->role }}
                    </p>
                </div>
            </div>
            @endforeach
        </div>

        <div style="background:linear-gradient(135deg, #f3f0ff, #ede9fe);
                    border:1px solid #c4b5fd; border-radius:12px;
                    padding:16px; margin-bottom:16px;">
            <p style="font-size:13px; font-weight:600; color:#6d28d9; margin-bottom:6px;">
                ✨ Génération IA
            </p>
            <p style="font-size:12px; color:#7c3aed; line-height:1.5;">
                Clique sur "Générer avec l'IA" pour créer automatiquement 5 tâches
                adaptées à ton projet.
            </p>
        </div>

        <form method="POST" action="{{ route('projects.destroy', $project) }}"
              onsubmit="return confirm('Supprimer ce projet définitivement ?')">
            @csrf @method('DELETE')
            <button type="submit"
                    style="width:100%; padding:10px; border-radius:8px;
                           border:1px solid #fecaca; background:#fff;
                           color:#dc2626; font-size:13px; font-weight:500;
                           cursor:pointer; transition:background 0.15s;"
                    onmouseover="this.style.background='#fee2e2'"
                    onmouseout="this.style.background='#fff'">
                Supprimer le projet
            </button>
        </form>
    </div>
</div>

<div id="modal-task"
     style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.4);
            z-index:1000; align-items:center; justify-content:center;">
    <div style="background:#fff; border-radius:16px; padding:32px;
                width:100%; max-width:500px; position:relative;">
        <button onclick="document.getElementById('modal-task').style.display='none'"
                style="position:absolute; top:16px; right:16px; background:none;
                       border:none; cursor:pointer; color:#6b7280; font-size:20px;">✕</button>
        <h2 style="font-size:18px; font-weight:600; color:#111827; margin-bottom:24px;">
            Nouvelle tâche
        </h2>
        <form method="POST" action="{{ route('tasks.store', $project) }}">
            @csrf
            <div style="margin-bottom:16px;">
                <label style="display:block; font-size:13px; font-weight:500;
                              color:#374151; margin-bottom:6px;">Titre *</label>
                <input type="text" name="title" required
                       style="width:100%; padding:10px 14px; border:1px solid #d1d5db;
                              border-radius:8px; font-size:14px; outline:none; background:#f9fafb;">
            </div>
            <div style="margin-bottom:16px;">
                <label style="display:block; font-size:13px; font-weight:500;
                              color:#374151; margin-bottom:6px;">Description</label>
                <textarea name="description" rows="2"
                          style="width:100%; padding:10px 14px; border:1px solid #d1d5db;
                                 border-radius:8px; font-size:14px; outline:none;
                                 background:#f9fafb; resize:vertical;"></textarea>
            </div>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px; margin-bottom:16px;">
                <div>
                    <label style="display:block; font-size:13px; font-weight:500;
                                  color:#374151; margin-bottom:6px;">Priorité</label>
                    <select name="priority"
                            style="width:100%; padding:10px 14px; border:1px solid #d1d5db;
                                   border-radius:8px; font-size:14px; background:#f9fafb; outline:none;">
                        <option value="low">Basse</option>
                        <option value="medium" selected>Moyenne</option>
                        <option value="high">Haute</option>
                    </select>
                </div>
                <div>
                    <label style="display:block; font-size:13px; font-weight:500;
                                  color:#374151; margin-bottom:6px;">Échéance</label>
                    <input type="date" name="due_date"
                           style="width:100%; padding:10px 14px; border:1px solid #d1d5db;
                                  border-radius:8px; font-size:14px; background:#f9fafb; outline:none;">
                </div>
            </div>
            <div style="margin-bottom:24px;">
                <label style="display:block; font-size:13px; font-weight:500;
                              color:#374151; margin-bottom:6px;">Assigner à</label>
                <select name="assigned_to"
                        style="width:100%; padding:10px 14px; border:1px solid #d1d5db;
                               border-radius:8px; font-size:14px; background:#f9fafb; outline:none;">
                    <option value="">— Non assigné —</option>
                    @foreach($project->members as $member)
                    <option value="{{ $member->id }}">{{ $member->name }}</option>
                    @endforeach
                </select>
            </div>
            <div style="display:flex; gap:12px;">
                <button type="submit" class="btn-primary">Créer la tâche</button>
                <button type="button"
                        onclick="document.getElementById('modal-task').style.display='none'"
                        style="padding:10px 20px; border:1px solid #d1d5db; border-radius:8px;
                               font-size:14px; color:#374151; background:#fff; cursor:pointer;">
                    Annuler
                </button>
            </div>
        </form>
    </div>
</div>

<div id="modal-edit-task"
     style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.4);
            z-index:1000; align-items:center; justify-content:center;">
    <div style="background:#fff; border-radius:16px; padding:32px;
                width:100%; max-width:500px; position:relative;">
        <button onclick="document.getElementById('modal-edit-task').style.display='none'"
                style="position:absolute; top:16px; right:16px; background:none;
                       border:none; cursor:pointer; color:#6b7280; font-size:20px;">✕</button>
        <h2 style="font-size:18px; font-weight:600; color:#111827; margin-bottom:24px;">
            Modifier la tâche
        </h2>
        <form method="POST" id="edit-task-form">
            @csrf @method('PATCH')
            <div style="margin-bottom:16px;">
                <label style="display:block; font-size:13px; font-weight:500;
                              color:#374151; margin-bottom:6px;">Titre *</label>
                <input type="text" name="title" id="edit-title" required
                       style="width:100%; padding:10px 14px; border:1px solid #d1d5db;
                              border-radius:8px; font-size:14px; outline:none; background:#f9fafb;">
            </div>
            <div style="margin-bottom:16px;">
                <label style="display:block; font-size:13px; font-weight:500;
                              color:#374151; margin-bottom:6px;">Description</label>
                <textarea name="description" id="edit-description" rows="2"
                          style="width:100%; padding:10px 14px; border:1px solid #d1d5db;
                                 border-radius:8px; font-size:14px; outline:none;
                                 background:#f9fafb; resize:vertical;"></textarea>
            </div>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px; margin-bottom:24px;">
                <div>
                    <label style="display:block; font-size:13px; font-weight:500;
                                  color:#374151; margin-bottom:6px;">Priorité</label>
                    <select name="priority" id="edit-priority"
                            style="width:100%; padding:10px 14px; border:1px solid #d1d5db;
                                   border-radius:8px; font-size:14px; background:#f9fafb; outline:none;">
                        <option value="low">Basse</option>
                        <option value="medium">Moyenne</option>
                        <option value="high">Haute</option>
                    </select>
                </div>
                <div>
                    <label style="display:block; font-size:13px; font-weight:500;
                                  color:#374151; margin-bottom:6px;">Statut</label>
                    <select name="status" id="edit-status"
                            style="width:100%; padding:10px 14px; border:1px solid #d1d5db;
                                   border-radius:8px; font-size:14px; background:#f9fafb; outline:none;">
                        <option value="todo">À faire</option>
                        <option value="in_progress">En cours</option>
                        <option value="done">Terminé</option>
                    </select>
                </div>
            </div>
            <div style="display:flex; gap:12px;">
                <button type="submit" class="btn-primary">Sauvegarder</button>
                <button type="button"
                        onclick="document.getElementById('modal-edit-task').style.display='none'"
                        style="padding:10px 20px; border:1px solid #d1d5db; border-radius:8px;
                               font-size:14px; color:#374151; background:#fff; cursor:pointer;">
                    Annuler
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openEditModal(id, title, description, priority, status) {
    document.getElementById('edit-title').value       = title;
    document.getElementById('edit-description').value = description;
    document.getElementById('edit-priority').value    = priority;
    document.getElementById('edit-status').value      = status;
    document.getElementById('edit-task-form').action  =
        '/projects/{{ $project->id }}/tasks/' + id;
    document.getElementById('modal-edit-task').style.display = 'flex';
}

function updateCommentAction(select) {
    const url = select.options[select.selectedIndex].dataset.url;
    document.getElementById('comment-form').action = url;
}
</script>

@endsection