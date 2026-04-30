@extends('layouts.app')
@section('title', 'Nouveau Projet')
@section('content')

{{-- Pas de max-width ici — le layout prend toute la largeur --}}
<div style="margin-bottom:32px;">
    <a href="{{ route('dashboard') }}"
       style="font-size:13px; color:var(--text-secondary); text-decoration:none;">
        ← Retour au dashboard
    </a>
    <h1 class="page-title" style="margin-top:8px;">Nouveau Projet</h1>
</div>

<div class="card" style="padding:32px;">
    <form method="POST" action="{{ route('projects.store') }}">
        @csrf

        <div style="display:grid; grid-template-columns:1fr 1fr; gap:24px; margin-bottom:24px;">

            {{-- Nom --}}
            <div class="form-group" style="margin-bottom:0;">
                <label class="form-label">Nom du projet *</label>
                <input type="text" name="name" class="form-input"
                       value="{{ old('name') }}"
                       placeholder="Ex: Mobile App Redesign">
                @error('name')
                    <p style="color:#dc2626; font-size:11px; margin-top:3px;">{{ $message }}</p>
                @enderror
            </div>

            {{-- Description --}}
            <div class="form-group" style="margin-bottom:0;">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-input" rows="1"
                          placeholder="Décris l'objectif de ce projet..."
                          style="resize:none;">{{ old('description') }}</textarea>
            </div>
        </div>

        {{-- Membres --}}
        <div style="margin-bottom:28px;">
            <label class="form-label" style="margin-bottom:10px;">
                Inviter des membres
            </label>

            @if($users->isEmpty())
                <p style="font-size:13px; color:var(--text-muted); font-style:italic;">
                    Aucun autre utilisateur inscrit pour l'instant.
                </p>
            @else
                <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(260px,1fr)); gap:10px;">
                    @foreach($users as $user)
                    <label style="display:flex; align-items:center; gap:12px;
                                  padding:12px 16px;
                                  border:1px solid var(--border);
                                  border-radius:8px; cursor:pointer;
                                  background:var(--card-bg);
                                  transition:border-color 0.15s;"
                           onmouseover="this.style.borderColor='#2d6a4f'"
                           onmouseout="this.style.borderColor='var(--border)'">
                        <input type="checkbox" name="members[]" value="{{ $user->id }}"
                               style="width:16px; height:16px; accent-color:#2d6a4f;"
                               {{ in_array($user->id, old('members', [])) ? 'checked' : '' }}>
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
                            <p style="font-size:11px; color:var(--text-muted);">
                                {{ $user->email }}
                            </p>
                        </div>
                    </label>
                    @endforeach
                </div>
            @endif
        </div>

        <div style="display:flex; gap:12px;">
            <button type="submit" class="btn-primary">Créer le projet</button>
            <a href="{{ route('dashboard') }}" class="btn-secondary">Annuler</a>
        </div>
    </form>
</div>

@endsection