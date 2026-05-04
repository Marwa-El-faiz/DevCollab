<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: -apple-system, sans-serif; background:#f3f4f6; margin:0; padding:40px 20px; }
        .card { background:#fff; border-radius:12px; padding:40px; max-width:480px; margin:0 auto; }
        .logo { font-size:20px; font-weight:700; color:#111827; margin-bottom:8px; }
        .logo span { color:#2d6a4f; }
        h1 { font-size:22px; color:#111827; margin:24px 0 8px; }
        p  { font-size:14px; color:#6b7280; line-height:1.6; }
        .btn {
            display:inline-block; background:#2d6a4f; color:#fff;
            padding:12px 28px; border-radius:8px; font-size:15px;
            font-weight:600; text-decoration:none; margin:24px 0;
        }
        .note { font-size:12px; color:#9ca3af; margin-top:20px; }
    </style>
</head>
<body>
<div class="card">
    <div class="logo">Dev<span>Collab</span></div>
    <h1>Tu es invité !</h1>
    <p>
        <strong>{{ $invitation->inviter->name }}</strong> t'invite à rejoindre
        DevCollab en tant que <strong>{{ $invitation->role }}</strong>.
    </p>
    <a href="{{ url('/register?token=' . $invitation->token) }}" class="btn">
        Accepter l'invitation →
    </a>
    <p class="note">
        Ce lien expire le {{ $invitation->expires_at->format('d/m/Y à H:i') }}.<br>
        Si tu n'attendais pas cette invitation, ignore cet email.
    </p>
</div>
</body>
</html>