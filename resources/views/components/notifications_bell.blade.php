@php
    $notifications = auth()->user()->unreadNotifications()->latest()->take(5)->get();
    $notifCount    = auth()->user()->unreadNotifications()->count();
@endphp

<div style="position:relative; display:inline-block;">

    {{-- Cloche --}}
    <button onclick="document.getElementById('notif-dropdown').classList.toggle('hidden')"
            style="background:none; border:none; cursor:pointer; color:#9ca3af;
                   padding:6px; border-radius:6px; display:flex; align-items:center;
                   position:relative; transition:color 0.15s;"
            onmouseover="this.style.color='#e5e7eb'"
            onmouseout="this.style.color='#9ca3af'">
        <svg width="18" height="18" fill="none" stroke="currentColor"
             stroke-width="2" viewBox="0 0 24 24">
            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
            <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
        </svg>
        @if($notifCount > 0)
        <span style="position:absolute; top:2px; right:2px; width:8px; height:8px;
                     background:#ef4444; border-radius:50%; border:2px solid var(--sidebar-bg);">
        </span>
        @endif
    </button>

    {{-- Dropdown --}}
    <div id="notif-dropdown"
         class="hidden"
         style="position:absolute; bottom:40px; left:0; width:300px;
                background:#fff; border:1px solid #e5e7eb; border-radius:10px;
                box-shadow:0 8px 24px rgba(0,0,0,0.12); z-index:999; overflow:hidden;">

        <div style="padding:12px 16px; border-bottom:1px solid #f3f4f6;
                    display:flex; justify-content:space-between; align-items:center;">
            <span style="font-size:13px; font-weight:600; color:#111827;">
                Notifications
                @if($notifCount > 0)
                <span style="background:#ef4444; color:#fff; font-size:10px;
                             padding:1px 6px; border-radius:999px; margin-left:4px;">
                    {{ $notifCount }}
                </span>
                @endif
            </span>
            @if($notifCount > 0)
            <form method="POST" action="{{ route('notifications.markAllRead') }}">
                @csrf
                <button type="submit"
                        style="background:none; border:none; cursor:pointer;
                               font-size:11px; color:#6b7280; padding:0;">
                    Tout lire
                </button>
            </form>
            @endif
        </div>

        @forelse($notifications as $notif)
        <div style="padding:12px 16px; border-bottom:1px solid #f9fafb;
                    background:{{ $notif->read_at ? '#fff' : '#f0fdf4' }};">
            <div style="display:flex; align-items:flex-start; gap:10px;">
                <div style="width:8px; height:8px; border-radius:50%;
                            background:{{ $notif->read_at ? '#d1d5db' : '#2d6a4f' }};
                            margin-top:5px; flex-shrink:0;">
                </div>
                <div style="flex:1;">
                    <p style="font-size:12px; color:#111827; margin-bottom:2px; line-height:1.4;">
                        {{ $notif->data['message'] ?? 'Nouvelle notification' }}
                    </p>
                    <p style="font-size:11px; color:#9ca3af;">
                        {{ $notif->created_at->diffForHumans() }}
                    </p>
                </div>
                <form method="POST"
                      action="{{ route('notifications.markRead', $notif->id) }}">
                    @csrf
                    <button type="submit"
                            style="background:none; border:none; cursor:pointer;
                                   color:#9ca3af; font-size:16px; padding:0; line-height:1;">
                        ✓
                    </button>
                </form>
            </div>
        </div>
        @empty
        <div style="padding:24px; text-align:center; color:#9ca3af; font-size:13px;">
            Aucune notification
        </div>
        @endforelse

    </div>
</div>

<script>
// Fermer le dropdown si on clique ailleurs
document.addEventListener('click', function(e) {
    const dropdown = document.getElementById('notif-dropdown');
    if (dropdown && !e.target.closest('[onclick*="notif-dropdown"]')
        && !dropdown.contains(e.target)) {
        dropdown.classList.add('hidden');
    }
});
</script>