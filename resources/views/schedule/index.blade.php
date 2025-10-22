{{-- resources/views/schedule/index.blade.php --}}
<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <title>SocialSuite — Lên lịch đăng</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body { background: #0f1622; color: #e7eef7; font-family: ui-sans-serif, system-ui, -apple-system; }
        a { color: #7dc0ff; text-decoration: none; }
        .wrap { max-width: 1120px; margin: 40px auto; display: grid; grid-template-columns: 1fr 1fr; gap: 24px; }
        .card { background: #151e2c; border-radius: 14px; padding: 16px 18px; box-shadow: 0 0 0 1px rgba(255,255,255,0.04) inset; }
        .title { font-weight: 800; font-size: 22px; letter-spacing: .2px; margin-bottom: 12px; }
        .small { font-size: 12px; opacity: .75;}
        select, input[type="text"], input[type="datetime-local"], textarea { width: 100%; background:#0f1622; color:#e7eef7; border:1px solid rgba(255,255,255,.08); border-radius:10px; padding:10px 12px; outline:none}
        textarea { min-height: 110px; }
        .row { display:grid; grid-template-columns: 1fr 1fr; gap:12px }
        .btn { display:inline-flex; align-items:center; justify-content:center; background:#1f2a3b; border:1px solid rgba(255,255,255,.12); border-radius:12px; padding:10px 14px; color:#e7eef7; cursor:pointer; }
        .btn-primary { background:#3a7cff; border-color:#3a7cff; }
        .btn-danger { background:#c63d3d; border-color:#c63d3d; }
        .muted { color: #aab6c6; }
        table { width: 100%; border-collapse: collapse; }
        td, th { border-bottom: 1px dashed rgba(255,255,255,.08); padding:10px 6px; vertical-align: top; }
        .mt8 { margin-top: 8px; }
        .mt12 { margin-top: 12px; }
        .mt16 { margin-top: 16px; }
        .mb8 { margin-bottom: 8px; }
    </style>
</head>
<body>
<div class="wrap">

    {{-- LEFT: Compose --}}
    <div class="card">
        <div class="title">Tạo lịch mới</div>

        @if (session('success'))
            <div class="small" style="padding:8px 12px;border:1px solid #285b2f;border-radius:10px;background:#123016">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="small" style="padding:8px 12px;border:1px solid #5b2d28;border-radius:10px;background:#301612">
                @foreach ($errors->all() as $e)
                    <div>• {{ $e }}</div>
                @endforeach
            </div>
        @endif

        <form id="scheduleForm" action="{{ route('schedule.store') }}" method="POST" enctype="multipart/form-data" class="mt12">
            @csrf

            <div class="mb8">Chọn Page</div>
            <select name="page_id" id="page_id" required>
                <option value="">— Chọn Page —</option>
                @foreach ($pages as $p)
                    <option value="{{ $p->page_id }}" data-name="{{ $p->name }}">{{ $p->name }} — {{ $p->page_id }}</option>
                @endforeach
            </select>
            <input type="hidden" name="page_name" id="page_name" value="">

            <div class="mt12 mb8">Nội dung</div>
            <textarea name="message" placeholder="Viết nội dung…">{{ old('message') }}</textarea>

            <div class="mt12 mb8">Ảnh (tối đa 5 ảnh, mỗi ảnh ≤ 5MB)</div>
            <input type="file" name="photos[]" multiple accept="image/*">

            <div class="row mt12">
                <div>
                    <div class="mb8">Múi giờ</div>
                    <select name="timezone" id="timezone" required>
                        @php $tzLocal = $tz ?? 'UTC'; @endphp
                        @foreach ($timezones as $tzOpt)
                            <option value="{{ $tzOpt }}" {{ $tzLocal === $tzOpt ? 'selected' : '' }}>{{ $tzOpt }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <div class="mb8">Thời điểm đăng (theo múi giờ đã chọn)</div>
                    {{-- ISO 8601 từ datetime-local: Laravel parse -> OK --}}
                    <input type="datetime-local" name="publish_at" required>
                </div>
            </div>

            <div class="mt16">
                <button class="btn btn-primary" type="submit">Lưu lịch</button>
            </div>
        </form>

        <script>
            // Gắn page_name theo option đã chọn
            const pageSelect = document.getElementById('page_id');
            const pageName   = document.getElementById('page_name');
            const form       = document.getElementById('scheduleForm');

            function updatePageName(){
                const opt = pageSelect.options[pageSelect.selectedIndex];
                pageName.value = opt ? opt.getAttribute('data-name') || '' : '';
            }
            pageSelect.addEventListener('change', updatePageName);
            form.addEventListener('submit', updatePageName);
        </script>
    </div>

    {{-- RIGHT: Danh sách lịch --}}
    <div class="card">
        <div class="title">Danh sách lịch</div>
        <div class="small muted">Hiển thị 15 dòng gần nhất.</div>

        <table class="mt12">
            <thead>
            <tr class="small muted">
                <th width="60">ID</th>
                <th>Page</th>
                <th>Nội dung</th>
                <th>Thời gian</th>
                <th>Trạng thái</th>
                <th width="80"></th>
            </tr>
            </thead>
            <tbody>
            @forelse ($scheduled as $item)
                @php
                    $tzShow = $tz ?? 'UTC';
                    $local = $item->publish_at ? $item->publish_at->copy()->setTimezone($tzShow) : null;
                    $preview = trim($item->message ?? '');
                    if (mb_strlen($preview) > 80) { $preview = mb_substr($preview, 0, 80) . '…'; }
                @endphp
                <tr>
                    <td>#{{ $item->id }}</td>
                    <td>
                        {{ $item->page_name }}
                        <div class="small muted">{{ $item->page_id }}</div>
                    </td>
                    <td>
                        @if($item->media_type === 'album')
                            <span class="small">[Album {{ is_array($item->media_paths) ? count($item->media_paths) : 0 }} ảnh]</span>
                        @elseif($item->media_type === 'photo')
                            <span class="small">[Ảnh đơn]</span>
                        @endif
                        <div>{{ $preview !== '' ? $preview : '—' }}</div>
                    </td>
                    <td>
                        @if($local)
                            {{ $local->format('Y-m-d H:i:s') }}
                            <div class="small muted">({{ $tzShow }})</div>
                        @else
                            —
                        @endif
                    </td>
                    <td>
                        <span class="small">{{ $item->status }}</span>
                    </td>
                    <td>
                        @if (in_array($item->status, ['queued','processing']))
                            <form action="{{ route('schedule.cancel', $item) }}" method="POST" onsubmit="return confirm('Hủy lịch #{{ $item->id }}?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger small" type="submit">Hủy</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="small muted">Không có lịch nào.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

</div>
</body>
</html>
