@extends('layouts.app')

@section('content')
<div class="container" style="max-width:1100px">
    <h2 style="font-weight:700;margin-bottom:16px">Tạo lịch mới</h2>

    @if(session('ok'))
        <div class="alert alert-success">{{ session('ok') }}</div>
    @endif

    <form action="{{ route('schedule.store') }}" method="POST" enctype="multipart/form-data" class="card card-body" style="margin-bottom:24px">
        @csrf

        {{-- B1: Chọn Page (1 hoặc nhiều) --}}
        <div class="mb-3">
            <label class="form-label fw-bold">Chọn Page</label>
            <select name="page_ids[]" class="form-select" multiple size="6" required>
                @foreach($pages as $p)
                    <option value="{{ $p->page_id }}">
                        {{ $p->name }} — {{ $p->page_id }}
                    </option>
                @endforeach
            </select>
            <div class="form-text">Giữ Ctrl/Command để chọn nhiều Page.</div>
        </div>

        {{-- B2: Nội dung --}}
        <div class="mb-3">
            <label class="form-label fw-bold">Nội dung</label>
            <textarea name="message" class="form-control" rows="3" placeholder="Viết nội dung..."></textarea>
        </div>

        {{-- B3: Ảnh (tối đa 5) --}}
        <div class="mb-3">
            <label class="form-label fw-bold">Ảnh (tối đa 5, ≤ 5MB/ảnh)</label>
            <input type="file" name="photos[]" class="form-control" accept="image/*" multiple>
            <div class="form-text">Chọn nhiều ảnh nếu muốn đăng album.</div>
        </div>

        {{-- B4: Múi giờ & thời điểm đăng (theo múi giờ đã chọn) --}}
        @php
            $defaultTz = 'Asia/Ho_Chi_Minh';
            $nowLocal  = \Carbon\Carbon::now($defaultTz)->format('Y-m-d H:i');
        @endphp
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label fw-bold">Múi giờ</label>
                <select name="tz" class="form-select">
                    <option value="Asia/Ho_Chi_Minh" selected>Asia/Ho_Chi_Minh (UTC+7)</option>
                    <option value="UTC">UTC</option>
                    <option value="Asia/Bangkok">Asia/Bangkok (UTC+7)</option>
                </select>
                <div class="form-text">Mặc định Việt Nam.</div>
            </div>
            <div class="col-md-8">
                <label class="form-label fw-bold">Thời điểm đăng (theo múi giờ đã chọn)</label>
                <input type="text" name="publish_at_local" class="form-control" value="{{ $nowLocal }}" placeholder="YYYY-mm-dd HH:ii">
                <div class="d-flex gap-2 mt-2">
                    <button type="button" class="btn btn-outline-secondary btn-sm quick-add" data-min="15">+15m</button>
                    <button type="button" class="btn btn-outline-secondary btn-sm quick-add" data-min="30">+30m</button>
                    <button type="button" class="btn btn-outline-secondary btn-sm quick-add" data-min="60">+1h</button>
                    <button type="button" class="btn btn-outline-secondary btn-sm quick-add" data-min="120">+2h</button>
                </div>
            </div>
        </div>

        <div class="mt-3">
            <button class="btn btn-primary">Lưu lịch</button>
        </div>
    </form>

    {{-- Danh sách 15 lịch mới nhất --}}
    <div class="card">
        <div class="card-header fw-bold">Danh sách lịch</div>
        <div class="card-body p-0">
            <table class="table mb-0">
                <thead>
                <tr>
                    <th width="60">ID</th>
                    <th>Page</th>
                    <th>Nội dung</th>
                    <th>Loại</th>
                    <th>Thời gian</th>
                    <th>Trạng thái</th>
                    <th width="80"></th>
                </tr>
                </thead>
                <tbody>
                @forelse($items as $it)
                    <tr>
                        <td class="align-middle">{{ $it->id }}</td>
                        <td class="align-middle">
                            <div style="font-weight:600">{{ $it->page_id }}</div>
                        </td>
                        <td class="align-middle">
                            {{ Str::limit($it->message, 80) }}
                        </td>
                        <td class="align-middle">
                            @php $cnt = is_array($it->media_paths) ? count($it->media_paths) : 0; @endphp
                            @if($cnt > 1)
                                <span class="badge bg-primary">Album {{ $cnt }}</span>
                            @elseif($cnt === 1)
                                <span class="badge bg-secondary">1 ảnh</span>
                            @else
                                <span class="badge bg-light text-dark">Không ảnh</span>
                            @endif
                        </td>
                        <td class="align-middle">
                            <div>
                                {{ optional($it->publish_local)->format('Y-m-d H:i') }}
                            </div>
                            <div class="small text-muted">({{ $it->tz }})</div>
                            <div class="small text-muted">UTC: {{ optional($it->publish_at)->copy()->setTimezone('UTC')->format('Y-m-d H:i') }}</div>
                        </td>
                        <td class="align-middle">
                            <span class="badge bg-dark">{{ $it->status }}</span>
                        </td>
                        <td class="align-middle">
                            @if($it->status === 'queued')
                                <form method="POST" action="{{ route('schedule.cancel', $it) }}">
                                    @csrf
                                    <button class="btn btn-outline-danger btn-sm">Hủy</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center py-4">Chưa có lịch</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Quick add buttons --}}
<script>
document.querySelectorAll('.quick-add').forEach(btn => {
    btn.addEventListener('click', function () {
        const min = parseInt(this.dataset.min, 10);
        const input = document.querySelector('input[name="publish_at_local"]');
        if (!input.value) return;

        // Format: Y-m-d H:i
        const parts = input.value.trim().replace('  ', ' ').split(' ');
        if (parts.length !== 2) return;

        const date = parts[0].split('-');
        const time = parts[1].split(':');
        if (date.length !== 3 || time.length < 2) return;

        const y = +date[0], m = (+date[1])-1, d = +date[2];
        const hh = +time[0], mm = +time[1];

        const dt = new Date(y, m, d, hh, mm, 0);
        dt.setMinutes(dt.getMinutes() + min);

        const pad = n => (n<10?'0'+n:n);
        const newVal = dt.getFullYear() + '-' + pad(dt.getMonth()+1) + '-' + pad(dt.getDate())
            + ' ' + pad(dt.getHours()) + ':' + pad(dt.getMinutes());
        input.value = newVal;
    });
});
</script>
@endsection
