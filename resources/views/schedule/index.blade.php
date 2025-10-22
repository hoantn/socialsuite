@php use Illuminate\Support\Str; @endphp
<!doctype html>
<html>
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Lên lịch — SocialSuite</title>
  <style>
    body { margin:0; font-family: system-ui, -apple-system, Segoe UI, Roboto, Arial; background:#0f172a; color:#e2e8f0; }
    .wrap { padding:24px; max-width:1100px; margin:0 auto; }
    .h1 { font-size:26px; font-weight:800; margin:8px 0 16px }
    .grid { display:grid; grid-template-columns: 1fr 1fr; gap:16px }
    .card { background:#111827; border:1px solid #1f2937; border-radius:16px; padding:16px; }
    .small { font-size:12px; color:#94a3b8 }
    input, select, textarea { width:100%; padding:10px; border-radius:10px; border:1px solid #1f2937; background:#0b1220; color:#e2e8f0; }
    .btn { padding:10px 14px; border-radius:10px; background:#e5e7eb; color:#111827; border:0; font-weight:700; cursor:pointer }
    table { width:100%; border-collapse: collapse }
    th, td { padding:8px; border-bottom:1px solid #1f2937; text-align:left; vertical-align:top }
    .pill { display:inline-block; padding:2px 8px; border-radius:999px; background:#0b1220; border:1px solid #1f2937; font-size:12px }
    a { color:#93c5fd; text-decoration:none }
  </style>
</head>
<body>
  <div class="wrap">
    <div class="h1">Lên lịch đăng
      <span style="float:right">
        <a href="{{ route('pages.index') }}">← Danh sách Pages</a>
      </span>
    </div>

    @if (session('status'))
      <div class="card" style="border-color:#334155">{{ session('status') }}</div>
    @endif

    <div class="grid">
      <div class="card">
        <div style="font-weight:700;margin-bottom:8px">Tạo lịch mới</div>
        <form action="{{ route('schedule.store') }}" method="POST" enctype="multipart/form-data">
          @csrf
          <div class="small" style="margin:6px 0">Chọn Page</div>
          <select name="page_id" required>
            @foreach($pages as $p)
              <option value="{{ $p->page_id }}">{{ $p->name }} — {{ $p->page_id }}</option>
            @endforeach
          </select>

          <div class="small" style="margin:10px 0 6px">Nội dung</div>
          <textarea name="message" rows="4" placeholder="Viết nội dung..."></textarea>

          <div class="small" style="margin:10px 0 6px">Ảnh (tối đa 5, mỗi ảnh ≤ 5MB)</div>
          <input type="file" name="photos[]" multiple accept="image/*">

          <div class="small" style="margin:10px 0 6px">Múi giờ</div>
          <select name="timezone">
            @php $tzlist = ['Asia/Ho_Chi_Minh','Asia/Bangkok','Asia/Tokyo','Asia/Seoul','UTC','Europe/Berlin','America/Los_Angeles','America/New_York']; @endphp
            @foreach($tzlist as $tz)
              <option value="{{ $tz }}">{{ $tz }}</option>
            @endforeach
          </select>

          <div class="small" style="margin:10px 0 6px">Thời điểm đăng (theo múi giờ đã chọn)</div>
          <input type="datetime-local" name="publish_at" required />

          <div style="margin-top:12px">
            <button class="btn" type="submit">Lưu lịch</button>
          </div>
        </form>
      </div>

      <div class="card">
        <div style="font-weight:700;margin-bottom:8px">Danh sách lịch</div>
        <div class="small" style="margin-bottom:8px">Hiển thị 15 dòng gần nhất.</div>
        <table>
          <thead>
            <tr>
              <th>ID</th>
              <th>Page</th>
              <th>Nội dung</th>
              <th>Thời gian</th>
              <th>Trạng thái</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            @forelse($list as $item)
              @php
                $tz = $item->timezone ?: 'Asia/Ho_Chi_Minh';
                $local = $item->publish_at ? $item->publish_at->copy()->setTimezone($tz) : null;
              @endphp
              <tr>
                <td>#{{ $item->id }}</td>
                <td>{{ $item->page_name }}<br><span class="small">{{ $item->page_id }}</span></td>
                <td>{{ Str::limit($item->message, 80) }}</td>
                <td>
                  @if($local)
                    {{ $local->format('Y-m-d H:i:s') }} <span class="small">({{ $tz }})</span><br>
                    <span class="small">UTC: {{ $item->publish_at->format('Y-m-d H:i:s') }}</span>
                  @else
                    —
                  @endif
                </td>
                <td><span class="pill">{{ $item->status }}</span><br>
                  @if($item->error_message)
                    <span class="small" style="color:#fca5a5">{{ $item->error_message }}</span>
                  @endif
                </td>
                <td>
                  @if(in_array($item->status, ['queued','processing']))
                    <form action="{{ route('schedule.cancel',$item->id) }}" method="POST">
                      @csrf
                      <button class="btn" type="submit">Hủy</button>
                    </form>
                  @endif
                </td>
              </tr>
            @empty
              <tr><td colspan="6" class="small">Chưa có lịch</td></tr>
            @endforelse
          </tbody>
        </table>

        <div style="margin-top:8px">
          {{ $list->links() }}
        </div>
      </div>
    </div>
  </div>
</body>
</html>
