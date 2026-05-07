@extends('layouts.app')
@section('title', $project->name)
@section('content')

<div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:32px;">
    <div>
        <a href="{{ route('dashboard') }}"
           style="font-size:13px; color:var(--text2); text-decoration:none; margin-bottom:8px; display:inline-block;">
            Retour au dashboard
        </a>
        <h1 class="page-title">{{ $project->name }}</h1>
        <p class="page-subtitle">{{ $project->description ?? 'Aucune description.' }}</p>
    </div>
    <div style="display:flex; gap:10px; align-items:center; flex-wrap:wrap;">
        @php
            $statusColor = match($project->status) {
                'completed' => '#059669', 'archived' => '#9ca3af', default => '#f59e0b',
            };
            $statusLabel = match($project->status) {
                'completed' => 'Termine', 'archived' => 'Archive', default => 'Actif',
            };
            $isProjectAdmin = $project->isAdmin(Auth::id());
        @endphp
        <span style="font-size:12px; font-weight:600; color:{{ $statusColor }};
                     background:{{ $statusColor }}18; padding:4px 12px;
                     border-radius:999px; border:1px solid {{ $statusColor }}44;">
            {{ $statusLabel }}
        </span>

        @if($isProjectAdmin)
        <form method="POST" action="{{ route('projects.generate-tasks', $project) }}"
              onsubmit="this.querySelector('button').disabled=true; this.querySelector('button').textContent='Generation...';">
            @csrf
            <button type="submit"
                    style="background:linear-gradient(135deg,#6366f1,#8b5cf6); color:#fff;
                           border:none; padding:9px 16px; border-radius:8px; font-size:13px;
                           font-weight:500; cursor:pointer;">
                Generer avec IA
            </button>
        </form>

        <form method="POST" action="{{ route('projects.distribute-tasks', $project) }}"
              onsubmit="this.querySelector('button').disabled=true; this.querySelector('button').textContent='Distribution...';">
            @csrf
            <button type="submit"
                    style="background:linear-gradient(135deg,#0891b2,#0e7490); color:#fff;
                           border:none; padding:9px 16px; border-radius:8px; font-size:13px;
                           font-weight:500; cursor:pointer;">
                Distribuer avec IA
            </button>
        </form>

        <a href="{{ route('projects.edit', $project) }}" class="btn-primary">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
            </svg>
            Modifier
        </a>
        @else
        <span style="font-size:11px; padding:4px 12px; border-radius:999px;
                     background:#dbeafe; color:#1d4ed8; font-weight:500;">
            Membre
        </span>
        @endif
    </div>
</div>

@php
    $total    = $project->tasks->count();
    $todo     = $project->tasks->where('status', 'todo')->count();
    $inprog   = $project->tasks->where('status', 'in_progress')->count();
    $done     = $project->tasks->where('status', 'done')->count();
    $progress = $total > 0 ? round(($done / $total) * 100) : 0;
@endphp

<div style="display:grid; grid-template-columns:repeat(4,1fr); gap:16px; margin-bottom:24px;">
    @foreach([
        ['Total',    $total,  '#6b7280'],
        ['A faire',  $todo,   '#f59e0b'],
        ['En cours', $inprog, '#3b82f6'],
        ['Termine',  $done,   '#059669'],
    ] as [$label, $value, $color])
    <div class="card" style="padding:20px 24px;">
        <p style="font-size:28px; font-weight:700; color:{{ $color }}; margin-bottom:4px;">{{ $value }}</p>
        <p style="font-size:13px; color:var(--text2);">{{ $label }}</p>
    </div>
    @endforeach
</div>

<div class="card" style="padding:20px 24px; margin-bottom:32px;">
    <div style="display:flex; justify-content:space-between; margin-bottom:10px;">
        <span style="font-size:14px; font-weight:600; color:var(--text);">Progression globale</span>
        <span style="font-size:16px; font-weight:700; color:{{ $progress >= 100 ? '#059669' : '#2d6a4f' }};">
            {{ $progress }}%
        </span>
    </div>
    <div style="background:var(--border); border-radius:999px; height:8px; overflow:hidden;">
        <div style="background:{{ $progress >= 100 ? '#059669' : '#2d6a4f' }};
                    height:100%; border-radius:999px; width:{{ $progress }}%; transition:width 0.3s;"></div>
    </div>
</div>

<div style="display:grid; grid-template-columns:1fr 300px; gap:24px;">

    <div>

        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
            <h2 style="font-size:16px; font-weight:600; color:var(--text);">Kanban Board</h2>
            @if($isProjectAdmin)
            <button onclick="document.getElementById('modal-task').style.display='flex'"
                    class="btn-primary" style="font-size:13px; padding:8px 14px;">
                + Nouvelle tache
            </button>
            @else
            <span style="font-size:12px; color:var(--text2);">Seul l'admin peut creer des taches</span>
            @endif
        </div>

        <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:16px; margin-bottom:40px;">

            @foreach([
                'todo'        => ['label' => 'A faire',  'color' => '#f59e0b'],
                'in_progress' => ['label' => 'En cours', 'color' => '#3b82f6'],
                'done'        => ['label' => 'Termine',  'color' => '#059669'],
            ] as $status => $col)

            <div style="background:var(--bg); border-radius:12px; padding:16px;">
                <div style="display:flex; align-items:center; gap:8px; margin-bottom:16px;">
                    <span style="width:8px; height:8px; border-radius:50%;
                                 background:{{ $col['color'] }}; display:inline-block;"></span>
                    <span style="font-size:13px; font-weight:600; color:var(--text);">{{ $col['label'] }}</span>
                    <span style="margin-left:auto; font-size:12px; color:var(--text2);
                                 background:var(--border); padding:1px 8px; border-radius:999px;">
                        {{ $project->tasks->where('status', $status)->count() }}
                    </span>
                </div>

                @foreach($project->tasks->where('status', $status) as $task)
                @php
                    $priorityColor = match($task->priority) {
                        'high' => '#dc2626', 'medium' => '#f59e0b', default => '#6b7280',
                    };
                    $dlStatus = $task->deadlineStatus();
                    $cardBorder = match($dlStatus) {
                        'overdue' => 'border-left:3px solid #dc2626;',
                        'soon'    => 'border-left:3px solid #f59e0b;',
                        default   => ($task->ai_generated ? 'border-left:3px solid #8b5cf6;' : ''),
                    };
                @endphp

                <div class="card" style="padding:14px; margin-bottom:10px; {{ $cardBorder }}">

                    <div style="display:flex; gap:4px; margin-bottom:6px; flex-wrap:wrap;">
                        @if($task->ai_generated)
                        <span style="font-size:10px; color:#8b5cf6; background:#f3f0ff;
                                     padding:2px 6px; border-radius:4px; font-weight:600;">IA</span>
                        @endif
                        @if($dlStatus === 'overdue')
                        <span style="font-size:10px; color:#dc2626; background:#fee2e2;
                                     padding:2px 6px; border-radius:4px; font-weight:600;">En retard</span>
                        @elseif($dlStatus === 'soon')
                        <span style="font-size:10px; color:#d97706; background:#fef3c7;
                                     padding:2px 6px; border-radius:4px; font-weight:600;">Bientot</span>
                        @endif
                    </div>

                    <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:8px;">
                        <span style="font-size:10px; font-weight:600; color:{{ $priorityColor }};
                                     background:{{ $priorityColor }}18; padding:2px 8px;
                                     border-radius:999px; text-transform:uppercase;">
                            {{ $task->priority }}
                        </span>
                        @if($isProjectAdmin)
                        <div style="display:flex; gap:4px;">
                            <button onclick="openEditModal({{ $task->id }}, '{{ addslashes($task->title) }}', '{{ addslashes($task->description ?? '') }}', '{{ $task->priority }}', '{{ $task->status }}')"
                                    style="background:none; border:none; cursor:pointer; color:var(--text2); padding:2px;">
                                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                </svg>
                            </button>
                            <form method="POST" action="{{ route('tasks.destroy', [$project, $task]) }}"
                                  onsubmit="return confirm('Supprimer ?')" style="display:inline;">
                                @csrf @method('DELETE')
                                <button type="submit" style="background:none; border:none; cursor:pointer; color:#dc2626; padding:2px;">
                                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <polyline points="3 6 5 6 21 6"/>
                                        <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                        @endif
                    </div>

                    <p style="font-size:13px; font-weight:500; color:var(--text); margin-bottom:8px; line-height:1.4;
                              {{ $status === 'done' ? 'text-decoration:line-through; opacity:0.6;' : '' }}">
                        {{ $task->title }}
                    </p>

                    @if($task->description)
                    <p style="font-size:11px; color:var(--text2); margin-bottom:8px; line-height:1.4;">
                        {{ Str::limit($task->description, 60) }}
                    </p>
                    @endif

                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:8px;">
                        @if($task->due_date)
                        <span style="font-size:11px;
                                     color:{{ $dlStatus === 'overdue' ? '#dc2626' : ($dlStatus === 'soon' ? '#d97706' : 'var(--text2)') }};
                                     font-weight:{{ in_array($dlStatus, ['overdue','soon']) ? '600' : '400' }};">
                            {{ $task->due_date->format('d M') }}
                        </span>
                        @else<span></span>@endif
                        @if($task->assignee)
                        <div title="{{ $task->assignee->name }}"
                             style="width:24px; height:24px; border-radius:50%; background:#374151;
                                    display:flex; align-items:center; justify-content:center;
                                    font-size:9px; font-weight:600; color:#fff;">
                            {{ strtoupper(substr($task->assignee->name, 0, 2)) }}
                        </div>
                        @endif
                    </div>

                    @if($task->attachments->count() > 0)
                    <div style="border-top:1px solid var(--border); padding-top:8px; margin-bottom:8px;">
                        <p style="font-size:10px; font-weight:600; color:var(--text2);
                                  text-transform:uppercase; margin-bottom:6px;">
                            {{ $task->attachments->count() }} fichier(s)
                        </p>
                        <div style="display:flex; flex-direction:column; gap:4px;">
                            @foreach($task->attachments as $att)
                            <div style="display:flex; align-items:center; gap:6px;
                                        padding:4px 8px; background:var(--input-bg);
                                        border-radius:6px; border:1px solid var(--border);">
                                <a href="{{ route('attachments.download', [$task, $att]) }}"
                                   style="font-size:11px; color:#2d6a4f; text-decoration:none;
                                          flex:1; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;"
                                   title="{{ $att->filename }}">
                                    {{ Str::limit($att->filename, 20) }}
                                </a>
                                <span style="font-size:10px; color:var(--text2); flex-shrink:0;">
                                    {{ $att->readableSize() }}
                                </span>
                                @if($att->user_id === Auth::id())
                                <form method="POST" action="{{ route('attachments.destroy', [$task, $att]) }}"
                                      onsubmit="return confirm('Supprimer ce fichier ?')" style="display:inline;">
                                    @csrf @method('DELETE')
                                    <button type="submit" style="background:none; border:none; cursor:pointer; color:#dc2626; padding:0; line-height:1;">
                                        <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <line x1="18" y1="6" x2="6" y2="18"/>
                                            <line x1="6" y1="6" x2="18" y2="18"/>
                                        </svg>
                                    </button>
                                </form>
                                @endif
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <div style="border-top:1px solid var(--border); padding-top:8px; margin-bottom:8px;">
                        <form method="POST" action="{{ route('attachments.store', $task) }}"
                              enctype="multipart/form-data" id="upload-form-{{ $task->id }}">
                            @csrf
                            <label style="display:flex; align-items:center; gap:6px; cursor:pointer;
                                          font-size:11px; color:var(--text2); padding:4px 0;">
                                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M21.44 11.05l-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48"/>
                                </svg>
                                <span>Joindre un fichier</span>
                                <input type="file" name="file" style="display:none;"
                                       accept=".jpg,.jpeg,.png,.gif,.webp,.pdf,.doc,.docx,.xls,.xlsx,.zip,.txt"
                                       onchange="document.getElementById('upload-form-{{ $task->id }}').submit()">
                            </label>
                        </form>
                    </div>

                    <div style="border-top:1px solid var(--border); padding-top:8px;">
                        <form method="POST" action="{{ route('tasks.move', [$project, $task]) }}">
                            @csrf @method('PATCH')
                            <input type="hidden" name="position" value="0">
                            <select name="status" onchange="this.form.submit()"
                                    style="width:100%; font-size:11px; border:1px solid var(--border);
                                           border-radius:6px; padding:4px 6px; background:var(--input-bg);
                                           color:var(--text2); cursor:pointer; outline:none;">
                                <option value="todo"        {{ $task->status==='todo'        ?'selected':'' }}>A faire</option>
                                <option value="in_progress" {{ $task->status==='in_progress' ?'selected':'' }}>En cours</option>
                                <option value="done"        {{ $task->status==='done'        ?'selected':'' }}>Termine</option>
                            </select>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
            @endforeach
        </div>

        <div>
            <h2 style="font-size:16px; font-weight:600; color:var(--text); margin-bottom:16px;">Commentaires</h2>

            @php
                $comments = \App\Models\Comment::with(['user','task'])
                    ->whereHas('task', fn($q) => $q->where('project_id', $project->id))
                    ->latest()->take(10)->get();
            @endphp

            <div class="card" style="overflow:hidden; margin-bottom:16px;">
                @forelse($comments as $comment)
                <div style="display:flex; gap:12px; padding:16px 20px;
                            border-bottom:1px solid var(--border); align-items:flex-start;">
                    <div style="width:34px; height:34px; border-radius:50%; background:#2d6a4f;
                                display:flex; align-items:center; justify-content:center;
                                font-size:11px; font-weight:600; color:#fff; flex-shrink:0;">
                        {{ strtoupper(substr($comment->user->name, 0, 2)) }}
                    </div>
                    <div style="flex:1;">
                        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:4px;">
                            <span style="font-size:13px; font-weight:600; color:var(--text);">
                                {{ $comment->user->name }}
                            </span>
                            <div style="display:flex; align-items:center; gap:8px;">
                                <span style="font-size:11px; color:var(--text2);">
                                    {{ $comment->created_at->diffForHumans() }}
                                </span>
                                @if($comment->user_id === Auth::id())
                                <form method="POST"
                                      action="{{ route('comments.destroy', [$comment->task, $comment]) }}"
                                      onsubmit="return confirm('Supprimer ?')" style="display:inline;">
                                    @csrf @method('DELETE')
                                    <button type="submit" style="background:none; border:none; cursor:pointer;
                                                                  color:#dc2626; font-size:11px; padding:0;">
                                        Supprimer
                                    </button>
                                </form>
                                @endif
                            </div>
                        </div>
                        <p style="font-size:13px; color:var(--text); line-height:1.5;">{{ $comment->body }}</p>
                        <p style="font-size:11px; color:var(--text2); margin-top:4px;">
                            Sur : <em>{{ $comment->task->title }}</em>
                        </p>
                    </div>
                </div>
                @empty
                <div style="padding:32px; text-align:center; color:var(--text2); font-size:14px;">
                    Aucun commentaire pour l'instant.
                </div>
                @endforelse
            </div>

            @if($project->tasks->isNotEmpty())
            <div class="card" style="padding:20px;">
                <h3 style="font-size:14px; font-weight:600; color:var(--text); margin-bottom:12px;">
                    Ajouter un commentaire
                </h3>
                <form method="POST" id="comment-form"
                      action="{{ route('comments.store', $project->tasks->first()) }}">
                    @csrf
                    <select onchange="updateCommentAction(this)"
                            style="width:100%; padding:8px 12px; border:1px solid var(--input-border);
                                   border-radius:8px; font-size:13px; margin-bottom:10px;
                                   background:var(--input-bg); color:var(--text); outline:none;">
                        @foreach($project->tasks as $t)
                        <option value="{{ $t->id }}" data-url="{{ route('comments.store', $t) }}">
                            {{ $t->title }}
                        </option>
                        @endforeach
                    </select>
                    <textarea name="body" rows="3" placeholder="Ecris ton commentaire..."
                              style="width:100%; padding:10px 14px; border:1px solid var(--input-border);
                                     border-radius:8px; font-size:13px; outline:none;
                                     resize:vertical; background:var(--input-bg);
                                     color:var(--text); margin-bottom:10px;"></textarea>
                    @error('body')
                    <p style="color:#dc2626; font-size:12px; margin-bottom:8px;">{{ $message }}</p>
                    @enderror
                    <button type="submit" class="btn-primary" style="font-size:13px;">Envoyer</button>
                </form>
            </div>
            @endif
        </div>

        <div style="margin-top:32px;">
            <h2 style="font-size:16px; font-weight:600; color:var(--text); margin-bottom:16px;
                       display:flex; align-items:center; gap:8px;">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                </svg>
                Chat du projet
                <span id="chat-count" style="font-size:11px; background:var(--border);
                      color:var(--text2); padding:2px 8px; border-radius:999px; font-weight:400;">
                    0 messages
                </span>
            </h2>

            <div id="chat-box"
                 style="background:var(--card); border:1px solid var(--border);
                        border-radius:10px 10px 0 0; height:360px; overflow-y:auto;
                        padding:16px; display:flex; flex-direction:column; gap:12px;">
                <div id="chat-loading" style="text-align:center; color:var(--text2); font-size:13px; margin:auto;">
                    Chargement...
                </div>
            </div>

            <div style="background:var(--card); border:1px solid var(--border); border-top:none;
                        border-radius:0 0 10px 10px; padding:12px 16px;
                        display:flex; gap:10px; align-items:center;">
                <div style="width:32px; height:32px; border-radius:50%; background:#2d6a4f;
                            display:flex; align-items:center; justify-content:center;
                            font-size:11px; font-weight:700; color:#fff; flex-shrink:0;">
                    {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                </div>
                <input type="text" id="chat-input" placeholder="Ecrire un message..."
                       style="flex:1; padding:9px 14px; border:1px solid var(--border);
                              border-radius:8px; font-size:13px; background:var(--input-bg);
                              color:var(--text); outline:none; font-family:inherit;"
                       onkeydown="if(event.key==='Enter'&&!event.shiftKey){event.preventDefault();sendChat();}">
                <button onclick="sendChat()"
                        style="background:#2d6a4f; color:#fff; border:none; padding:9px 16px;
                               border-radius:8px; font-size:13px; font-weight:500;
                               cursor:pointer; white-space:nowrap;">
                    Envoyer
                </button>
            </div>
        </div>

    </div>

    <div>
        <h2 style="font-size:16px; font-weight:600; color:var(--text); margin-bottom:16px;">
            Membres ({{ $project->members->count() }})
        </h2>
        <div class="card" style="overflow:hidden; margin-bottom:16px;">
            @foreach($project->members as $member)
            <div style="display:flex; align-items:center; gap:12px;
                        padding:14px 16px; border-bottom:1px solid var(--border);">
                <div style="width:36px; height:36px; border-radius:50%; background:#2d6a4f;
                            display:flex; align-items:center; justify-content:center;
                            font-size:12px; font-weight:600; color:#fff; flex-shrink:0;">
                    {{ strtoupper(substr($member->name, 0, 2)) }}
                </div>
                <div style="flex:1; min-width:0;">
                    <p style="font-size:13px; font-weight:500; color:var(--text);
                              white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                        {{ $member->name }}
                        @if($member->id === $project->owner_id)
                        <span style="font-size:10px; color:#2d6a4f; font-weight:600;">(Owner)</span>
                        @endif
                    </p>
                    <p style="font-size:11px; color:var(--text2); text-transform:capitalize;">
                        {{ $member->pivot->role }}
                        @if($member->job_title)
                        — {{ $member->job_title }}
                        @endif
                    </p>
                    @if($member->skills)
                    <div style="display:flex; gap:3px; flex-wrap:wrap; margin-top:4px;">
                        @foreach(array_slice($member->skillsArray(), 0, 3) as $skill)
                        <span style="font-size:9px; padding:1px 6px; border-radius:999px;
                                     background:var(--input-bg); color:var(--text2);
                                     border:1px solid var(--border);">
                            {{ trim($skill) }}
                        </span>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        @if($isProjectAdmin)
        <div style="background:linear-gradient(135deg,#f3f0ff,#ede9fe);
                    border:1px solid #c4b5fd; border-radius:12px; padding:16px; margin-bottom:16px;">
            <p style="font-size:13px; font-weight:600; color:#6d28d9; margin-bottom:6px;">
                Generation IA
            </p>
            <p style="font-size:12px; color:#7c3aed; line-height:1.5;">
                Utilisez "Generer avec IA" pour creer 5 taches adaptees au projet.<br>
                Utilisez "Distribuer avec IA" pour assigner les taches non assignees selon les competences.
            </p>
        </div>

        <form method="POST" action="{{ route('projects.destroy', $project) }}"
              onsubmit="return confirm('Supprimer ce projet ?')">
            @csrf @method('DELETE')
            <button type="submit"
                    style="width:100%; padding:10px; border-radius:8px;
                           border:1px solid #fecaca; background:var(--card);
                           color:#dc2626; font-size:13px; font-weight:500; cursor:pointer;">
                Supprimer le projet
            </button>
        </form>
        @endif
    </div>

</div>

@if($isProjectAdmin)
<div id="modal-task"
     style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5);
            z-index:1000; align-items:center; justify-content:center;">
    <div style="background:var(--card); border-radius:16px; padding:32px;
                width:100%; max-width:500px; position:relative; border:1px solid var(--border);">
        <button onclick="document.getElementById('modal-task').style.display='none'"
                style="position:absolute; top:16px; right:16px; background:none;
                       border:none; cursor:pointer; color:var(--text2); font-size:20px;">x</button>
        <h2 style="font-size:18px; font-weight:600; color:var(--text); margin-bottom:24px;">
            Nouvelle tache
        </h2>
        <form method="POST" action="{{ route('tasks.store', $project) }}">
            @csrf
            <div class="form-group">
                <label class="form-label">Titre *</label>
                <input type="text" name="title" required class="form-input">
            </div>
            <div class="form-group">
                <label class="form-label">Description</label>
                <textarea name="description" rows="2" class="form-input" style="resize:vertical;"></textarea>
            </div>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px; margin-bottom:16px;">
                <div>
                    <label class="form-label">Priorite</label>
                    <select name="priority" class="form-input">
                        <option value="low">Basse</option>
                        <option value="medium" selected>Moyenne</option>
                        <option value="high">Haute</option>
                    </select>
                </div>
                <div>
                    <label class="form-label">Echeance</label>
                    <input type="date" name="due_date" class="form-input">
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Assigner a</label>
                <select name="assigned_to" class="form-input">
                    <option value="">Non assigne</option>
                    @foreach($project->members as $member)
                    <option value="{{ $member->id }}">
                        {{ $member->name }}
                        @if($member->skills) ({{ Str::limit($member->skills, 30) }}) @endif
                    </option>
                    @endforeach
                </select>
            </div>
            <div style="display:flex; gap:12px; margin-top:8px;">
                <button type="submit" class="btn-primary">Creer la tache</button>
                <button type="button" onclick="document.getElementById('modal-task').style.display='none'"
                        class="btn-secondary">Annuler</button>
            </div>
        </form>
    </div>
</div>

<div id="modal-edit-task"
     style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5);
            z-index:1000; align-items:center; justify-content:center;">
    <div style="background:var(--card); border-radius:16px; padding:32px;
                width:100%; max-width:500px; position:relative; border:1px solid var(--border);">
        <button onclick="document.getElementById('modal-edit-task').style.display='none'"
                style="position:absolute; top:16px; right:16px; background:none;
                       border:none; cursor:pointer; color:var(--text2); font-size:20px;">x</button>
        <h2 style="font-size:18px; font-weight:600; color:var(--text); margin-bottom:24px;">
            Modifier la tache
        </h2>
        <form method="POST" id="edit-task-form">
            @csrf @method('PATCH')
            <div class="form-group">
                <label class="form-label">Titre *</label>
                <input type="text" name="title" id="edit-title" required class="form-input">
            </div>
            <div class="form-group">
                <label class="form-label">Description</label>
                <textarea name="description" id="edit-description" rows="2"
                          class="form-input" style="resize:vertical;"></textarea>
            </div>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px; margin-bottom:16px;">
                <div>
                    <label class="form-label">Priorite</label>
                    <select name="priority" id="edit-priority" class="form-input">
                        <option value="low">Basse</option>
                        <option value="medium">Moyenne</option>
                        <option value="high">Haute</option>
                    </select>
                </div>
                <div>
                    <label class="form-label">Statut</label>
                    <select name="status" id="edit-status" class="form-input">
                        <option value="todo">A faire</option>
                        <option value="in_progress">En cours</option>
                        <option value="done">Termine</option>
                    </select>
                </div>
            </div>
            <div style="display:flex; gap:12px;">
                <button type="submit" class="btn-primary">Sauvegarder</button>
                <button type="button" onclick="document.getElementById('modal-edit-task').style.display='none'"
                        class="btn-secondary">Annuler</button>
            </div>
        </form>
    </div>
</div>
@endif

<script>
function openEditModal(id, title, description, priority, status) {
    document.getElementById('edit-title').value       = title;
    document.getElementById('edit-description').value = description;
    document.getElementById('edit-priority').value    = priority;
    document.getElementById('edit-status').value      = status;
    document.getElementById('edit-task-form').action  = '/projects/{{ $project->id }}/tasks/' + id;
    document.getElementById('modal-edit-task').style.display = 'flex';
}

function updateCommentAction(select) {
    document.getElementById('comment-form').action = select.options[select.selectedIndex].dataset.url;
}

const CHAT_URL   = '{{ route("chat.index", $project) }}';
const CHAT_STORE = '{{ route("chat.store", $project) }}';
const CSRF       = document.querySelector('meta[name="csrf-token"]').content;
let lastMessageId = 0;

async function loadChat() {
    try {
        const res  = await fetch(CHAT_URL);
        const msgs = await res.json();
        renderMessages(msgs, true);
        if (msgs.length) lastMessageId = msgs[msgs.length - 1].id;
    } catch(e) {}
}

function renderMessages(msgs, replace = false) {
    const box     = document.getElementById('chat-box');
    const loading = document.getElementById('chat-loading');
    if (loading) loading.remove();
    if (replace) box.innerHTML = '';
    if (msgs.length === 0 && replace) {
        box.innerHTML = '<div style="text-align:center;color:var(--text2);font-size:13px;margin:auto;">Aucun message.</div>';
        document.getElementById('chat-count').textContent = '0 messages';
        return;
    }
    msgs.forEach(msg => {
        if (document.getElementById('msg-' + msg.id)) return;
        const el = document.createElement('div');
        el.id = 'msg-' + msg.id;
        el.style.cssText = 'display:flex;gap:10px;align-items:flex-start;' + (msg.is_me ? 'flex-direction:row-reverse;' : '');
        el.innerHTML = `
            <div style="width:30px;height:30px;border-radius:50%;background:${msg.is_me?'#2d6a4f':'#374151'};
                        display:flex;align-items:center;justify-content:center;font-size:10px;font-weight:700;color:#fff;flex-shrink:0;">
                ${msg.user_initials}</div>
            <div style="max-width:70%;">
                <div style="display:flex;align-items:center;gap:6px;margin-bottom:3px;${msg.is_me?'justify-content:flex-end;':''}">
                    <span style="font-size:11px;font-weight:600;color:var(--text);">${msg.is_me?'Vous':msg.user_name}</span>
                    <span style="font-size:10px;color:var(--text2);">${msg.time}</span>
                </div>
                <div style="background:${msg.is_me?'#2d6a4f':'var(--input-bg)'};color:${msg.is_me?'#fff':'var(--text)'};
                            padding:8px 12px;border-radius:${msg.is_me?'12px 12px 4px 12px':'12px 12px 12px 4px'};
                            font-size:13px;line-height:1.5;word-break:break-word;">
                    ${msg.body.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;')}
                </div>
            </div>`;
        box.appendChild(el);
    });
    box.scrollTop = box.scrollHeight;
    const total = box.querySelectorAll('[id^="msg-"]').length;
    document.getElementById('chat-count').textContent = total + ' message' + (total > 1 ? 's' : '');
}

async function sendChat() {
    const input = document.getElementById('chat-input');
    const body  = input.value.trim();
    if (!body) return;
    input.value = ''; input.disabled = true;
    try {
        const res = await fetch(CHAT_STORE, {
            method: 'POST',
            headers: {'Content-Type':'application/json','X-CSRF-TOKEN':CSRF},
            body: JSON.stringify({body}),
        });
        const msg = await res.json();
        renderMessages([msg]);
        lastMessageId = msg.id;
    } catch(e) { input.value = body; }
    finally { input.disabled = false; input.focus(); }
}

async function pollChat() {
    try {
        const res  = await fetch(CHAT_URL + '?after=' + lastMessageId);
        const msgs = await res.json();
        const newMsgs = msgs.filter(m => m.id > lastMessageId);
        if (newMsgs.length) { renderMessages(newMsgs); lastMessageId = newMsgs[newMsgs.length-1].id; }
    } catch(e) {}
}

document.addEventListener('DOMContentLoaded', function() {
    loadChat();
    setInterval(pollChat, 3000);
});
</script>

@endsection