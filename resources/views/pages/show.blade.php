<!doctype html>
<html>
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Quản lý Page — SocialSuite</title>
  <style>
    body { margin:0; font-family: system-ui, -apple-system, Segoe UI, Roboto, Arial; background:#0f172a; color:#e2e8f0; }
    .wrap { padding:24px; max-width:980px; margin:0 auto; }
    .h1 { font-size:26px; font-weight:800; margin:8px 0 16px }
    .grid { display:grid; grid-template-columns: 1fr 1fr; gap:16px }
    .card { background:#111827; border:1px solid #1f2937; border-radius:16px; padding:16px; }
    .small { font-size:12px; color:#94a3b8 }
    .row { display:flex; gap:10px; margin-bottom:10px; align-items:center }
    .btn { display:inline-block; padding:10px 14px; border-radius:10px; background:#e5e7eb; color:#111827; text-decoration:none; font-weight:700; border:0; cursor:pointer }
    input, textarea { width:100%; padding:10px; border-radius:10px; border:1px solid #1f2937; background:#0b1220; color:#e2e8f0; }
    .post { background:#0b1220; padding:12px; border-radius:10px; margin-bottom:10px }
  </style>
</head>
<body>
  <div class="wrap">
    <div class="h1">Quản lý Page — {{ $page->name }}
      <a class="small" style="float:right;color:#e5e7eb;text-decoration:none" href="{{ route('pages.index') }}">← Danh sách Pages</a>
    </div>

    @if (session('status'))
      <div class="card" style="border-color:#334155">{{ session('status') }}</div>
    @endif

    <div class="grid">
      <div class="card">
        <div class="row">
          <img src="{{ $page->picture_url }}" style="width:48px;height:48px;border-radius:10px;object-fit:cover" />
          <div>
            <div style="font-weight:700">{{ $page->name }}</div>
            <div class="small">{{ $page->page_id }} — {{ $page->category }}</div>
          </div>
        </div>
        <form action="{{ route('pages.publish', $page->page_id) }}" method="POST" enctype="multipart/form-data">
          @csrf
          <div class="small" style="margin:10px 0 6px">Nội dung</div>
          <textarea name="message" rows="4" placeholder="Viết nội dung..."></textarea>
          <div class="small" style="margin:12px 0 6px">Ảnh (tuỳ chọn)</div>
          <input type="file" name="photo" accept=".jpg,.jpeg,.png,.gif" />
          <div style="margin-top:12px">
            <button class="btn" type="submit">Đăng bài</button>
          </div>
        </form>
      </div>

      <div class="card">
        <div style="font-weight:700;margin-bottom:8px">Bài đăng gần đây</div>
        @forelse($recent as $post)
          <div class="post">
            <div class="small">{{ $post['id'] ?? '' }} — {{ \Carbon\Carbon::parse($post['created_time'] ?? now())->toDateTimeString() }}</div>
            <div>{{ $post['message'] ?? '(không có nội dung)' }}</div>
            @if(isset($post['permalink_url']))
              <div class="small"><a href="{{ $post['permalink_url'] }}" target="_blank" style="color:#93c5fd">Xem trên Facebook</a></div>
            @endif
          </div>
        @empty
          <div class="small">Chưa tải được danh sách bài đăng.</div>
        @endforelse
      </div>
    </div>
  </div>
</body>
</html>
