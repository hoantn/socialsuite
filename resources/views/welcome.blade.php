<!doctype html>
<html>
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>SocialSuite — Phase 2 (No Socialite)</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
  <style>
    :root { font-family: 'Inter', system-ui, -apple-system, Segoe UI, Roboto, Arial; }
    body { margin:0; background:#0f172a; color:#e2e8f0; }
    .wrap { min-height:100dvh; display:grid; place-items:center; padding:40px; }
    .card { background:#111827; border:1px solid #1f2937; border-radius:16px; padding:32px; max-width:640px; width:100%; box-shadow:0 10px 30px rgba(0,0,0,.3); }
    .title { font-size:28px; font-weight:700; margin:0 0 8px }
    .muted { color:#94a3b8; margin:0 0 20px }
    .btn { display:inline-block; padding:14px 18px; border-radius:12px; background:#e5e7eb; color:#111827; text-decoration:none; font-weight:700; }
    .btn:hover { filter:brightness(.95) }
    .tag { display:inline-block; background:#0b4; color:#031; padding:4px 8px; border-radius:999px; font-size:12px; font-weight:700 }
    .status { margin-top:16px; color:#d1fae5; }
    .footer { margin-top:24px; font-size:12px; color:#94a3b8; }
    code { background:#0b1220; padding:2px 6px; border-radius:6px; }
  </style>
</head>
<body>
  <div class="wrap">
    <div class="card">
      <div class="tag">PHASE 2</div>
      <h1 class="title">Đăng nhập Facebook (không dùng Socialite)</h1>
      <p class="muted">Nhấn để đăng nhập, hệ thống sẽ đồng bộ Pages & token.</p>
      <a class="btn" href="{{ route('facebook.redirect') }}">Đăng nhập Facebook</a>
      @if (session('status'))
        <div class="status">⚠️ {{ session('status') }}</div>
      @endif
      <div class="footer">APP_URL: <code>{{ config('app.url') }}</code></div>
    </div>
  </div>
</body>
</html>
