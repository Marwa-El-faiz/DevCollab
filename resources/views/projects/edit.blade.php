@extends('layouts.app')
@section('title', 'Modifier — ' . $project->name)
@section('content')

<div style="max-width:800px;">

    <div style="margin-bottom:32px;">
        <a href="{{ route('projects.show', $project) }}"
           style="font-size:13px; color:#6b7280; text-decoration:none;">
            ← Retour au projet
        </a>
        <h1 class="page-title" style="margin-top:8px;">
            Modifier le projet
        </h1>
        <p class="page-subtitle">{{ $project->name }}</p>
    </div>

    <div style="background:#fff; border:1px solid #e5e7eb;
                border-radius:12px; padding:32px;">

        <form method="POST" action="{{ route('projects.update', $project) }}">
            @csrf
            @method('PUT')

            {{-- Nom --}}
            <div style="margin-bottom:20px;">
                <label style="display:block; font-size:13px;
                              font-weight:500; color:#374151;
                              margin-bottom:6px;">
                    Nom du projet *
                </label>
                <input type="text" name="name"
                       value="{{ old('name', $project->name) }}"
                       style="width:100%; padding:10px 14px;
                              border:1px solid #d1d5db; border-radius:8px;
                              font-size:14px; outline:none; background:#f9fafb;">
                @error('name')
                    <p style="color:#dc2626; font-size:12px;
                              margin-top:4px;">{{ $message }}</p>
                @enderror
            </div>

            {{-- Description --}}
            <div style="margin-bottom:20px;">
                <label style="display:block; font-size:13px;
                              font-weight:500; color:#374151;
                              margin-bottom:6px;">
                    Description
                </label>
                <textarea name="description" rows="3"
                          style="width:100%; padding:10px 14px;
                                 border:1px solid #d1d5db; border-radius:8px;
                                 font-size:14px; outline:none;
                                 background:#f9fafb; resize:vertical;">{{ old('description', $project->description) }}</textarea>
            </div>

            {{-- Statut --}}
            <div style="margin-bottom:20px;">
                <label style="display:block; font-size:13px;
                              font-weight:500; color:#374151;
                              margin-bottom:6px;">
                    Statut
                </label>
                <select name="status"
                        style="width:100%; padding:10px 14px;
                               border:1px solid #d1d5db; border-radius:8px;
                               font-size:14px; outline:none;
                               background:#f9fafb; cursor:pointer;">
                    <option value="active"
                        {{ old('status', $project->status) === 'active' ? 'selected' : '' }}>
                        Actif
                    </option>
                    <option value="completed"
                        {{ old('status', $project->status) === 'completed' ? 'selected' : '' }}>
                        Terminé
                    </option>
                    <option value="archived"
                        {{ old('status', $project->status) === 'archived' ? 'selected' : '' }}>
                        Archivé
                    </option>
                </select>
            </div>

            {{-- Membres --}}
            <div style="margin-bottom:28px;">
                <label style="display:block; font-size:13px;
                              font-weight:500; color:#374151;
                              margin-bottom:6px;">
                    Membres du projet
                </label>
                <p style="font-size:12px; color:#9ca3af; margin-bottom:12px;">
                    Coche les membres à inclure dans ce projet
                </p>

                @if($users->isEmpty())
                    <p style="font-size:13px; color:#9ca3af; font-style:italic;">
                        Aucun autre utilisateur inscrit pour l'instant.
                    </p>
                @else
                    <div style="display:flex; flex-direction:column; gap:10px;">
                        @foreach($users as $user)
                        <label style="display:flex; align-items:center;
                                      gap:12px; padding:12px 16px;
                                      border:1px solid {{ in_array($user->id, $currentMembers) ? '#2d6a4f' : '#e5e7eb' }};
                                      border-radius:8px; cursor:pointer;
                                      background:{{ in_array($user->id, $currentMembers) ? '#f0fdf4' : '#fff' }};
                                      transition:all 0.15s;"
                               onmouseover="this.style.borderColor='#2d6a4f'"
                               onmouseout="this.style.borderColor='{{ in_array($user->id, $currentMembers) ? '#2d6a4f' : '#e5e7eb' }}'">
                            <input type="checkbox"
                                   name="members[]"
                                   value="{{ $user->id }}"
                                   style="width:16px; height:16px; accent-color:#2d6a4f;"
                                   {{ in_array($user->id, old('members', $currentMembers)) ? 'checked' : '' }}>
                            <div style="width:32px; height:32px; border-radius:50%;
                                        background:#374151; display:flex;
                                        align-items:center; justify-content:center;
                                        font-size:11px; font-weight:600;
                                        color:#fff; flex-shrink:0;">
                                {{ strtoupper(substr($user->name, 0, 2)) }}
                            </div>
                            <div>
                                <p style="font-size:14px; font-weight:500;
                                          color:#111827; margin-bottom:1px;">
                                    {{ $user->name }}
                                </p>
                                <p style="font-size:12px; color:#9ca3af;">
                                    {{ $user->email }} —
                                    <span style="text-transform:capitalize;">
                                        {{ $user->role }}
                                    </span>
                                </p>
                            </div>
                        </label>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Boutons --}}
            <div style="display:flex; gap:12px;">
                <button type="submit" class="btn-primary">
                    Sauvegarder les modifications
                </button>
                <a href="{{ route('projects.show', $project) }}"
                   style="padding:10px 20px; border:1px solid #d1d5db;
                          border-radius:8px; font-size:14px; color:#374151;
                          text-decoration:none; display:inline-flex;
                          align-items:center;">
                    Annuler
                </a>
            </div>

        </form>
    </div>
</div>

@endsection