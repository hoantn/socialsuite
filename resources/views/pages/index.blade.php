<!doctype html>
<html>
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Pages — SocialSuite</title>
  <style>
    body { margin:0; font-family: system-ui, -apple-system, Segoe UI, Roboto, Arial; background:#0f172a; color:#e2e8f0; }
    .wrap { padding:24px; max-width:980px; margin:0 auto; }
    .h1 { font-size:28px; font-weight:800; margin:8px 0 16px }
    .card { background:#111827; border:1px solid #1f2937; border-radius:16px; padding:16px; margin-bottom:16px; }
    .row { display:flex; gap:12px; align-items:center; padding:12px; background:#0b1220; border-radius:10px; margin-bottom:10px; text-decoration:none; color:#e2e8f0; }
    .row:hover { background:#142036; }
    .small { font-size:12px; color:#94a3b8 }
    .logout, .login { float:right; color:#e5e7eb; text-decoration:none; }
    .btn { display:inline-block; padding:12px 16px; border-radius:12px; background:#e5e7eb; color:#111827; text-decoration:none; font-weight:700; }
    .center { text-align:center; padding:24px 0; }
  </style>
</head>
<body>
  <div class="wrap">
    <div class="h1">Danh sách Pages
      @if($user)
        <a class="logout" href="{{ route('logout') }}">Đăng xuất</a>
      @else
        <a class="login" href="{{ route('facebook.redirect') }}">Đăng nhập</a>
      @endif
    </div>

    <div class="card">
      @if(!$user)
        <div class="center">
          <div style="margin-bottom:12px">Bạn đã đăng xuất.</div>
          <a class="btn" href="{{ route('facebook.redirect') }}">Đăng nhập Facebook</a>
        </div>
      @else
        @forelse($pages as $p)
          <a class="row" href="{{ route('pages.show', $p->page_id) }}">
            <img src="{{ $p->picture_url }}" style="width:40px;height:40px;border-radius:8px;object-fit:cover" />
            <div>
              <div style="font-weight:700">{{ $p->name }}</div>
              <div class="small">{{ $p->page_id }} — {{ $p->category }}</div>
            </div>
          </a>
        @empty
          <div>Không có Page nào. Hãy kiểm tra quyền ứng dụng hoặc token.</div>
        @endforelse
      @endif
    </div>
  </div>
</body>
</html>
