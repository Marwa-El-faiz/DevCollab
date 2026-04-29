@extends('layouts.app')
@section('title', 'Nouveau Projet')
@section('content')

<div style="max-width:800px;">

    <div style="margin-bottom:32px;">
        <a href="{{ route('dashboard') }}"
           style="font-size:13px; color:#6b7280; text-decoration:none;">
            ← Retour au dashboard
        </a>
        <h1 class="page-title" style="margin-top:8px;">
            Nouveau Projet
        </h1>
    </div>

    <div style="background:#fff; border:1px solid #e5e7eb;
                border-radius:12px; padding:32px;">

        <form method="POST" action="{{ route('projects.store') }}">
            @csrf

            {{-- Nom --}}
            <div style="margin-bottom:20px;">
                <label style="display:block; font-size:13px;
                              font-weight:500; color:#374151;
                              margin-bottom:6px;">
                    Nom du projet *
                </label>
                <input type="text" name="name"
                       value="{{ old('name') }}"
                       placeholder="Ex: Mobile App Redesign"
                       style="width:100%; padding:10px 14px;
                              border:1px solid #d1d5db; border-radius:8px;
                              font-size:14px; outline:none;
                              background:#f9fafb;">
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
                          placeholder="Décris l'objectif de ce projet..."
                          style="width:100%; padding:10px 14px;
                                 border:1px solid #d1d5db; border-radius:8px;
                                 font-size:14px; outline:none;
                                 background:#f9fafb; resize:vertical;">{{ old('description') }}</textarea>
            </div>

            {{-- Membres (utilisateurs réels) --}}
            <div style="margin-bottom:28px;">
                <label style="display:block; font-size:13px;
                              font-weight:500; color:#374151;
                              margin-bottom:6px;">
                    Inviter des membres
                </label>
                <p style="font-size:12px; color:#9ca3af; margin-bottom:12px;">
                    Sélectionne les membres de ton équipe
                </p>

                @if($users->isEmpty())
                    <p style="font-size:13px; color:#9ca3af;
                              font-style:italic;">
                        Aucun autre utilisateur inscrit pour l'instant.
                    </p>
                @else
                    <div style="display:flex; flex-direction:column; gap:10px;">
                        @foreach($users as $user)
                        <label style="display:flex; align-items:center;
                                      gap:12px; padding:12px 16px;
                                      border:1px solid #e5e7eb;
                                      border-radius:8px; cursor:pointer;
                                      transition:border-color 0.15s;"
                               onmouseover="this.style.borderColor='#2d6a4f'"
                               onmouseout="this.style.borderColor='#e5e7eb'">
                            <input type="checkbox"
                                   name="members[]"
                                   value="{{ $user->id }}"
                                   style="width:16px; height:16px;
                                          accent-color:#2d6a4f;"
                                   {{ in_array($user->id, old('members', [])) ? 'checked' : '' }}>

                            {{-- Avatar --}}
                            <div style="width:32px; height:32px;
                                        border-radius:50%; background:#374151;
                                        display:flex; align-items:center;
                                        justify-content:center; font-size:11px;
                                        font-weight:600; color:#fff;
                                        flex-shrink:0;">
                                {{ strtoupper(substr($user->name, 0, 2)) }}
                            </div>

                            {{-- Infos --}}
                            <div>
                                <p style="font-size:14px; font-weight:500;
                                          color:#111827; margin-bottom:1px;">
                                    {{ $user->name }}
                                </p>
                                <p style="font-size:12px; color:#9ca3af;">
                                    {{ $user->email }}
                                    — <span style="text-transform:capitalize;">
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
                    Créer le projet
                </button>
                <a href="{{ route('dashboard') }}"
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