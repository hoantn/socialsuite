<!doctype html>
<html>
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Dashboard — SocialSuite</title>
  <style>
    body { margin:0; font-family: system-ui, -apple-system, Segoe UI, Roboto, Arial; background:#0f172a; color:#e2e8f0; }
    .wrap { padding:24px; max-width:980px; margin:0 auto; }
    .h1 { font-size:28px; font-weight:800; margin:8px 0 16px }
    .card { background:#111827; border:1px solid #1f2937; border-radius:16px; padding:20px; margin-bottom:16px }
    .muted { color:#94a3b8 }
    .row { display:flex; gap:12px; align-items:center; padding:10px; background:#0b1220; border-radius:10px; margin-bottom:8px }
    .name { font-weight:700 }
    .small { font-size:12px; color:#94a3b8 }
    .logout { float:right; color:#e5e7eb; text-decoration:none; }
  </style>
</head>
<body>
  <div class="wrap">
    <div class="h1">Dashboard
      <a class="logout" href="{{ route('logout') }}">Đăng xuất</a>
    </div>
    @if(!$user)
      <div class="card">Chưa đăng nhập. <a href="{{ route('facebook.redirect') }}">Đăng nhập Facebook</a></div>
    @else
      <div class="card">
        <div class="muted">Xin chào, {{ $user->name ?? 'User' }} (ID: {{ $user->fb_user_id }})</div>
        <div class="small">Token hết hạn: {{ $user->access_token_expires_at ? $user->access_token_expires_at->toDateTimeString() : '—' }}</div>
      </div>
      <div class="card">
        <div class="muted">Danh sách Pages ({{ $pages->count() }})</div>
        @forelse($pages as $p)
          <div class="row">
            <div><img src="{{ $p->picture_url }}" style="width:36px;height:36px;border-radius:6px;object-fit:cover" /></div>
            <div>
              <div class="name">{{ $p->name }}</div>
              <div class="small">{{ $p->page_id }} — {{ $p->category }}</div>
            </div>
          </div>
        @empty
          <div class="row">Không tìm thấy Page nào. Kiểm tra quyền ứng dụng hoặc token.</div>
        @endforelse
      </div>
    @endif
  </div>
</body>
</html>
