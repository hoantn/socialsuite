<!doctype html>
<html>
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Compose đa Page — SocialSuite</title>
  <style>
    body { margin:0; font-family: system-ui, -apple-system, Segoe UI, Roboto, Arial; background:#0f172a; color:#e2e8f0; }
    .wrap { padding:24px; max-width:980px; margin:0 auto; }
    .h1 { font-size:26px; font-weight:800; margin:8px 0 16px }
    .grid { display:grid; grid-template-columns: 1fr 1fr; gap:16px }
    .card { background:#111827; border:1px solid #1f2937; border-radius:16px; padding:16px; }
    .small { font-size:12px; color:#94a3b8 }
    .row { display:flex; gap:10px; margin-bottom:8px; align-items:center }
    .btn { display:inline-block; padding:10px 14px; border-radius:10px; background:#e5e7eb; color:#111827; text-decoration:none; font-weight:700; border:0; cursor:pointer }
    input, textarea { width:100%; padding:10px; border-radius:10px; border:1px solid #1f2937; background:#0b1220; color:#e2e8f0; }
    .list { max-height:420px; overflow:auto; padding-right:8px }
    .topright { float:right }
    a { color:#93c5fd; text-decoration:none }
  </style>
</head>
<body>
  <div class="wrap">
    <div class="h1">Đăng bài cho nhiều Page
      <span class="topright">
        <a href="{{ route('pages.index') }}">← Danh sách Pages</a>
      </span>
    </div>

    @if (session('status'))
      <div class="card" style="border-color:#334155">{{ session('status') }}</div>
    @endif

    <form class="grid" action="{{ route('compose.publish') }}" method="POST" enctype="multipart/form-data">
      @csrf
      <div class="card">
        <div style="font-weight:700;margin-bottom:6px">Chọn Page</div>
        <div class="list">
          @forelse($pages as $p)
            <label class="row">
              <input type="checkbox" name="pages[]" value="{{ $p->page_id }}" />
              <img src="{{ $p->picture_url }}" style="width:32px;height:32px;border-radius:8px;object-fit:cover" />
              <div>
                <div style="font-weight:700">{{ $p->name }}</div>
                <div class="small">{{ $p->page_id }} — {{ $p->category }}</div>
              </div>
            </label>
          @empty
            <div class="small">Không có Page.</div>
          @endforelse
        </div>
      </div>

      <div class="card">
        <div class="small" style="margin:10px 0 6px">Nội dung</div>
        <textarea name="message" rows="6" placeholder="Viết nội dung..."></textarea>
        <div class="small" style="margin:12px 0 6px">Ảnh (tuỳ chọn)</div>
        <input type="file" name="photo" accept=".jpg,.jpeg,.png,.gif" />
        <div style="margin-top:12px">
          <button class="btn" type="submit">Đăng bài cho các Page đã chọn</button>
        </div>
      </div>
    </form>
  </div>
</body>
</html>
