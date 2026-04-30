@extends('layouts.app')
@section('title', 'Modifier — ' . $project->name)
@section('content')

<div style="margin-bottom:32px;">
    <a href="{{ route('projects.show', $project) }}"
       style="font-size:13px; color:var(--text-secondary); text-decoration:none;">
        ← Retour au projet
    </a>
    <h1 class="page-title" style="margin-top:8px;">Modifier le projet</h1>
    <p class="page-subtitle">{{ $project->name }}</p>
</div>

<div class="card" style="padding:32px;">
    <form method="POST" action="{{ route('projects.update', $project) }}">
        @csrf
        @method('PUT')

        <div style="display:grid; grid-template-columns:1fr 1fr; gap:24px; margin-bottom:24px;">

            {{-- Nom --}}
            <div class="form-group" style="margin-bottom:0;">
                <label class="form-label">Nom du projet *</label>
                <input type="text" name="name" class="form-input"
                       value="{{ old('name', $project->name) }}">
                @error('name')
                    <p style="color:#dc2626; font-size:11px; margin-top:3px;">{{ $message }}</p>
                @enderror
            </div>

            {{-- Statut --}}
            <div class="form-group" style="margin-bottom:0;">
                <label class="form-label">Statut</label>
                <select name="status" class="form-input">
                    <option value="active"     {{ old('status', $project->status) === 'active'    ? 'selected' : '' }}>Actif</option>
                    <option value="completed"  {{ old('status', $project->status) === 'completed' ? 'selected' : '' }}>Terminé</option>
                    <option value="archived"   {{ old('status', $project->status) === 'archived'  ? 'selected' : '' }}>Archivé</option>
                </select>
            </div>
        </div>

        {{-- Description --}}
        <div class="form-group">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-input" rows="3"
                      style="resize:vertical;">{{ old('description', $project->description) }}</textarea>
        </div>

        {{-- Membres --}}
        <div style="margin-bottom:28px;">
            <label class="form-label" style="margin-bottom:10px;">Membres du projet</label>

            @if($users->isEmpty())
                <p style="font-size:13px; color:var(--text-muted); font-style:italic;">
                    Aucun autre utilisateur inscrit.
                </p>
            @else
                <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(260px,1fr)); gap:10px;">
                    @foreach($users as $user)
                    @php $isMember = in_array($user->id, old('members', $currentMembers)); @endphp
                    <label style="display:flex; align-items:center; gap:12px;
                                  padding:12px 16px;
                                  border:2px solid {{ $isMember ? '#2d6a4f' : 'var(--border)' }};
                                  border-radius:8px; cursor:pointer;
                                  background:{{ $isMember ? '#f0fdf420' : 'var(--card-bg)' }};
                                  transition:all 0.15s;">
                        <input type="checkbox" name="members[]" value="{{ $user->id }}"
                               style="width:16px; height:16px; accent-color:#2d6a4f;"
                               {{ $isMember ? 'checked' : '' }}>
                        <div style="width:32px; height:32px; border-radius:50%;
                                    background:#374151; display:flex; align-items:center;
                                    justify-content:center; font-size:11px;
                                    font-weight:600; color:#fff; flex-shrink:0;">
                            {{ strtoupper(substr($user->name, 0, 2)) }}
                        </div>
                        <div>
                            <p style="font-size:13px; font-weight:500; color:var(--text-primary); margin-bottom:1px;">
                                {{ $user->name }}
                            </p>
                            <p style="font-size:11px; color:var(--text-muted);">{{ $user->email }}</p>
                        </div>
                    </label>
                    @endforeach
                </div>
            @endif
        </div>

        <div style="display:flex; gap:12px;">
            <button type="submit" class="btn-primary">Sauvegarder</button>
            <a href="{{ route('projects.show', $project) }}" class="btn-secondary">Annuler</a>
        </div>
    </form>
</div>

@endsection