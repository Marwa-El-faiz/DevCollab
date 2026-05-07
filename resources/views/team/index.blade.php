@extends('layouts.app')
@section('title', 'Équipe')
@section('content')

{{-- ══ En-tête ══ --}}
<div style="display:flex; justify-content:space-between;
            align-items:flex-start; margin-bottom:28px;">
    <div>
        <h1 class="page-title">Équipe</h1>
        <p class="page-subtitle">Gérer les membres, leurs rôles et compétences</p>
    </div>

    @if(Auth::user()->isAdmin())
    <button onclick="document.getElementById('modal-invite').style.display='flex'"
            class="btn-primary">
        <svg width="14" height="14" fill="none" stroke="currentColor"
             stroke-width="2.5" viewBox="0 0 24 24">
            <line x1="12" y1="5" x2="12" y2="19"/>
            <line x1="5" y1="12" x2="19" y2="12"/>
        </svg>
        Inviter un membre
    </button>
    @endif
</div>

<div class="card" style="overflow:hidden;">

    <div style="padding:18px 24px; border-bottom:1px solid var(--border);
                display:flex; align-items:center; gap:8px;">
        <svg width="16" height="16" fill="none" stroke="var(--text2)"
             stroke-width="2" viewBox="0 0 24 24">
            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
            <circle cx="9" cy="7" r="4"/>
            <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
            <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
        </svg>
        <span style="font-size:14px; font-weight:600; color:var(--text);">
            {{ $members->count() }} membre{{ $members->count() > 1 ? 's' : '' }}
        </span>
    </div>

    @foreach($members as $member)
    @php
        $donePct = $member->tasks_count > 0
            ? round(($member->done_count / $member->tasks_count) * 100)
            : 0;
    @endphp
    <div style="padding:20px 24px; border-bottom:1px solid var(--border);
                transition:background 0.15s;"
         onmouseover="this.style.background='var(--input-bg)'"
         onmouseout="this.style.background='transparent'">

        <div style="display:flex; align-items:center; gap:16px; flex-wrap:wrap;">

            <div style="width:46px; height:46px; border-radius:50%; flex-shrink:0;
                        background:{{ $member->isAdmin() ? '#2d6a4f' : '#374151' }};
                        display:flex; align-items:center; justify-content:center;
                        font-size:15px; font-weight:700; color:#fff;">
                {{ strtoupper(substr($member->name, 0, 2)) }}
            </div>

            <div style="flex:1; min-width:180px;">
                <div style="display:flex; align-items:center; gap:8px; flex-wrap:wrap; margin-bottom:3px;">
                    <span style="font-size:15px; font-weight:600; color:var(--text);">
                        {{ $member->name }}
                    </span>
                    <span style="font-size:11px; font-weight:500; padding:2px 8px; border-radius:20px;
                                 background:{{ $member->isAdmin() ? '#d1fae5' : 'var(--input-bg)' }};
                                 color:{{ $member->isAdmin() ? '#065f46' : 'var(--text2)' }};
                                 border:1px solid {{ $member->isAdmin() ? '#a7f3d0' : 'var(--border)' }};">
                        {{ $member->isAdmin() ? 'Admin' : 'Member' }}
                    </span>
                    @if($member->id === Auth::id())
                    <span style="font-size:11px; font-weight:500; padding:2px 8px;
                                 border-radius:20px; background:#eff6ff;
                                 color:#1d4ed8; border:1px solid #bfdbfe;">
                        Vous
                    </span>
                    @endif
                </div>

                <div style="display:flex; align-items:center; gap:5px; margin-bottom:4px;">
                    <svg width="11" height="11" fill="none" stroke="var(--text3)"
                         stroke-width="2" viewBox="0 0 24 24">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                        <polyline points="22,6 12,13 2,6"/>
                    </svg>
                    <span style="font-size:12px; color:var(--text2);">{{ $member->email }}</span>
                </div>

                @if($member->job_title || $member->skills)
                <div style="display:flex; gap:6px; flex-wrap:wrap; margin-top:4px;">
                    @if($member->job_title)
                    <span style="font-size:11px; color:var(--text2); background:var(--input-bg);
                                 border:1px solid var(--border); padding:2px 8px; border-radius:20px;">
                         {{ $member->job_title }}
                    </span>
                    @endif
                    @foreach($member->skillsArray() as $skill)
                    <span style="font-size:11px; color:#2d6a4f; background:#d1fae520;
                                 border:1px solid #a7f3d0; padding:2px 8px; border-radius:20px;">
                        {{ $skill }}
                    </span>
                    @endforeach
                </div>
                @endif
            </div>

            {{-- Stats tâches --}}
            <div style="text-align:center; min-width:80px;">
                <p style="font-size:18px; font-weight:700; color:var(--text);">
                    {{ $member->tasks_count }}
                </p>
                <p style="font-size:11px; color:var(--text2);">tâches</p>
                @if($member->tasks_count > 0)
                <div style="background:var(--border); border-radius:999px; height:3px;
                            margin-top:4px; overflow:hidden;">
                    <div style="background:#2d6a4f; height:100%;
                                width:{{ $donePct }}%; border-radius:999px;"></div>
                </div>
                <p style="font-size:10px; color:var(--text3); margin-top:2px;">
                    {{ $donePct }}% fait
                </p>
                @endif
            </div>

            {{-- Actions admin --}}
            @if(Auth::user()->isAdmin() && $member->id !== Auth::id())
            <div style="display:flex; gap:8px; flex-shrink:0;">

                {{-- Modifier rôle --}}
                <button onclick="openRoleModal({{ $member->id }}, '{{ addslashes($member->name) }}', '{{ $member->role }}')"
                        style="padding:6px 12px; border:1px solid var(--border);
                               border-radius:7px; background:none; color:var(--text2);
                               font-size:12px; cursor:pointer; transition:all 0.15s;"
                        onmouseover="this.style.borderColor='#2d6a4f'; this.style.color='#2d6a4f'"
                        onmouseout="this.style.borderColor='var(--border)'; this.style.color='var(--text2)'"
                        title="Modifier le rôle">
                     Rôle
                </button>

                {{-- Modifier compétences --}}
                <button onclick="openSkillsModal({{ $member->id }}, '{{ addslashes($member->name) }}', '{{ addslashes($member->skills ?? '') }}', '{{ addslashes($member->job_title ?? '') }}')"
                        style="padding:6px 12px; border:1px solid var(--border);
                               border-radius:7px; background:none; color:var(--text2);
                               font-size:12px; cursor:pointer; transition:all 0.15s;"
                        onmouseover="this.style.borderColor='#2d6a4f'; this.style.color='#2d6a4f'"
                        onmouseout="this.style.borderColor='var(--border)'; this.style.color='var(--text2)'"
                        title="Modifier les compétences">
                     Compétences
                </button>

                {{-- Supprimer --}}
                @if(!$member->isAdmin())
                <form method="POST" action="{{ route('team.destroy', $member) }}"
                      onsubmit="return confirm('Supprimer {{ $member->name }} de l\'équipe ?')"
                      style="display:inline;">
                    @csrf @method('DELETE')
                    <button type="submit"
                            style="padding:6px 10px; border:1px solid #fecaca;
                                   border-radius:7px; background:none; color:#dc2626;
                                   font-size:12px; cursor:pointer; transition:all 0.15s;"
                            onmouseover="this.style.background='#fee2e2'"
                            onmouseout="this.style.background='none'"
                            title="Supprimer ce membre">
                        🗑
                    </button>
                </form>
                @endif

            </div>
            @endif

        </div>
    </div>
    @endforeach

</div>



<div id="modal-invite"
     style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5);
            z-index:1000; align-items:center; justify-content:center; padding:20px;">
    <div style="background:var(--card); border-radius:14px; padding:32px;
                width:100%; max-width:440px; position:relative;
                border:1px solid var(--border);">
        <button onclick="document.getElementById('modal-invite').style.display='none'"
                style="position:absolute; top:16px; right:16px; background:none;
                       border:none; cursor:pointer; color:var(--text2); font-size:20px;">
            ✕
        </button>

        <h2 style="font-size:17px; font-weight:600; color:var(--text); margin-bottom:6px;">
            Inviter un membre
        </h2>
        <p style="font-size:13px; color:var(--text2); margin-bottom:24px;">
            Un email d'invitation sera envoyé avec un lien d'accès.
        </p>

        <form method="POST" action="{{ route('invitations.store') }}">
            @csrf
            <div class="form-group">
                <label class="form-label">Adresse email *</label>
                <input type="email" name="email" class="form-input"
                       placeholder="membre@exemple.com" required>
            </div>
            <div class="form-group">
                <label class="form-label">Rôle *</label>
                <select name="role" class="form-input">
                    <option value="member">Member — accès standard</option>
                    <option value="admin">Admin — accès complet</option>
                </select>
            </div>
            <div style="display:flex; gap:12px; margin-top:20px;">
                <button type="submit" class="btn-primary" style="flex:1; justify-content:center;">
                    Envoyer l'invitation
                </button>
                <button type="button"
                        onclick="document.getElementById('modal-invite').style.display='none'"
                        class="btn-secondary" style="flex:1; justify-content:center;">
                    Annuler
                </button>
            </div>
        </form>
    </div>
</div>



<div id="modal-role"
     style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5);
            z-index:1000; align-items:center; justify-content:center; padding:20px;">
    <div style="background:var(--card); border-radius:14px; padding:32px;
                width:100%; max-width:400px; position:relative;
                border:1px solid var(--border);">
        <button onclick="document.getElementById('modal-role').style.display='none'"
                style="position:absolute; top:16px; right:16px; background:none;
                       border:none; cursor:pointer; color:var(--text2); font-size:20px;">
            ✕
        </button>

        <h2 style="font-size:17px; font-weight:600; color:var(--text); margin-bottom:6px;">
            Modifier le rôle
        </h2>
        <p id="role-member-name" style="font-size:13px; color:var(--text2); margin-bottom:24px;"></p>

        <form id="role-form" method="POST">
            @csrf @method('PUT')
            <div class="form-group">
                <label class="form-label">Nouveau rôle</label>
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">

                    <label style="cursor:pointer;">
                        <input type="radio" id="role-member" name="role" value="member"
                               style="display:none;" onchange="updateRoleUI(this)">
                        <div id="role-card-member"
                             style="border:2px solid var(--border); border-radius:10px;
                                    padding:16px; text-align:center; transition:all 0.2s;">
                            <div style="font-size:22px; margin-bottom:6px;"></div>
                            <p style="font-size:12px; font-weight:600; color:var(--text);">Member</p>
                            <p style="font-size:11px; color:var(--text2); margin-top:3px;">
                                Peut voir et commenter
                            </p>
                        </div>
                    </label>

                    <label style="cursor:pointer;">
                        <input type="radio" id="role-admin" name="role" value="admin"
                               style="display:none;" onchange="updateRoleUI(this)">
                        <div id="role-card-admin"
                             style="border:2px solid var(--border); border-radius:10px;
                                    padding:16px; text-align:center; transition:all 0.2s;">
                            <div style="font-size:22px; margin-bottom:6px;"></div>
                            <p style="font-size:12px; font-weight:600; color:var(--text);">Admin</p>
                            <p style="font-size:11px; color:var(--text2); margin-top:3px;">
                                Accès complet
                            </p>
                        </div>
                    </label>

                </div>
            </div>
            <div style="display:flex; gap:12px; margin-top:20px;">
                <button type="submit" class="btn-primary" style="flex:1; justify-content:center;">
                    Sauvegarder
                </button>
                <button type="button"
                        onclick="document.getElementById('modal-role').style.display='none'"
                        class="btn-secondary" style="flex:1; justify-content:center;">
                    Annuler
                </button>
            </div>
        </form>
    </div>
</div>



<div id="modal-skills"
     style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5);
            z-index:1000; align-items:center; justify-content:center; padding:20px;">
    <div style="background:var(--card); border-radius:14px; padding:32px;
                width:100%; max-width:440px; position:relative;
                border:1px solid var(--border);">
        <button onclick="document.getElementById('modal-skills').style.display='none'"
                style="position:absolute; top:16px; right:16px; background:none;
                       border:none; cursor:pointer; color:var(--text2); font-size:20px;">
            ✕
        </button>

        <h2 style="font-size:17px; font-weight:600; color:var(--text); margin-bottom:6px;">
            Modifier les compétences
        </h2>
        <p id="skills-member-name" style="font-size:13px; color:var(--text2); margin-bottom:24px;"></p>

        <form id="skills-form" method="POST">
            @csrf @method('PUT')

            <div class="form-group">
                <label class="form-label">Titre du poste</label>
                <input type="text" id="skills-job-title" name="job_title"
                       class="form-input" placeholder="Ex: Développeur Backend, Designer UI...">
            </div>

            <div class="form-group">
                <label class="form-label">Compétences</label>
                <input type="text" id="skills-input" name="skills"
                       class="form-input"
                       placeholder="Ex: PHP, Laravel, Vue.js, MySQL">
                <p style="font-size:11px; color:var(--text3); margin-top:4px;">
                    Sépare les compétences par des virgules
                </p>
            </div>

            <div style="display:flex; gap:12px; margin-top:20px;">
                <button type="submit" class="btn-primary" style="flex:1; justify-content:center;">
                    Sauvegarder
                </button>
                <button type="button"
                        onclick="document.getElementById('modal-skills').style.display='none'"
                        class="btn-secondary" style="flex:1; justify-content:center;">
                    Annuler
                </button>
            </div>
        </form>
    </div>
</div>


<script>
function openRoleModal(userId, userName, currentRole) {
    document.getElementById('role-member-name').textContent = 'Membre : ' + userName;
    document.getElementById('role-form').action = '/team/' + userId + '/role';

    document.getElementById('role-' + currentRole).checked = true;
    updateRoleCardStyles(currentRole);

    document.getElementById('modal-role').style.display = 'flex';
}

function updateRoleUI(radio) {
    updateRoleCardStyles(radio.value);
}

function updateRoleCardStyles(activeRole) {
    ['member', 'admin'].forEach(function(role) {
        const card = document.getElementById('role-card-' + role);
        if (card) {
            card.style.borderColor = role === activeRole ? '#2d6a4f' : 'var(--border)';
            card.style.boxShadow   = role === activeRole ? '0 0 0 3px #2d6a4f20' : 'none';
        }
    });
}

function openSkillsModal(userId, userName, currentSkills, currentJobTitle) {
    document.getElementById('skills-member-name').textContent = 'Membre : ' + userName;
    document.getElementById('skills-form').action = '/team/' + userId + '/skills';
    document.getElementById('skills-input').value = currentSkills;
    document.getElementById('skills-job-title').value = currentJobTitle;
    document.getElementById('modal-skills').style.display = 'flex';
}

['modal-invite', 'modal-role', 'modal-skills'].forEach(function(id) {
    const el = document.getElementById(id);
    if (el) {
        el.addEventListener('click', function(e) {
            if (e.target === el) el.style.display = 'none';
        });
    }
});
</script>

@endsection