{{-- resources/views/schedule/index.blade.php --}}
<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <title>SocialSuite — Lên lịch đăng</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        :root{
            --bg:#0f1622; --card:#151e2c; --muted:#aab6c6; --text:#e7eef7;
            --line:rgba(255,255,255,.08); --line2:rgba(255,255,255,.12);
            --primary:#3a7cff; --danger:#c63d3d; --accent:#27c498;
        }
        *{ box-sizing: border-box }
        body{ background:var(--bg); color:var(--text); font-family: ui-sans-serif,system-ui,-apple-system,Segoe UI,Roboto; margin:0; }
        a{ color:#7dc0ff; text-decoration:none }
        .wrap{ max-width:1180px; margin:28px auto; padding:0 16px;
               display:grid; grid-template-columns: 1.15fr .85fr; gap:20px }
        .card{ background:var(--card); border-radius:14px; padding:16px 18px;
               box-shadow: 0 0 0 1px var(--line) inset }
        .title{ font-weight:800; font-size:22px; letter-spacing:.2px; margin-bottom:12px }
        .small{ font-size:12px; color:var(--muted) }
        .mt6{ margin-top:6px } .mt10{ margin-top:10px } .mt12{ margin-top:12px } .mt16{ margin-top:16px }
        .mb6{ margin-bottom:6px } .mb10{ margin-bottom:10px }
        .row{ display:grid; grid-template-columns: 1fr 1fr; gap:12px }
        .btn{ display:inline-flex; align-items:center; justify-content:center; border-radius:12px;
              padding:10px 14px; border:1px solid var(--line2); background:#1f2a3b; color:var(--text); cursor:pointer }
        .btn:hover{ filter:brightness(1.06) }
        .btn-primary{ background:var(--primary); border-color:var(--primary) }
        .btn-danger{ background:var(--danger); border-color:var(--danger) }
        .btn-ghost{ background:transparent }
        .chip{ display:inline-flex; gap:6px; align-items:center; padding:7px 10px; border-radius:999px;
               border:1px dashed var(--line2); cursor:pointer; user-select:none }
        .chip:hover{ border-color:var(--primary); color:#cfe1ff }

        input[type="text"], input[type="datetime-local"], select, textarea{
            width:100%; background:#0f1622; color:var(--text);
            border:1px solid var(--line2); border-radius:10px; padding:9px 10px; outline:none
        }
        textarea{ min-height:110px }

        /* PAGE PICKER */
        .page-box{ border:1px solid var(--line2); border-radius:12px; overflow:hidden; background:#0f1622 }
        .page-head{ display:flex; align-items:center; gap:10px; padding:10px; border-bottom:1px solid var(--line) }
        .page-search{ flex:1 }
        .page-list{ max-height:220px; overflow:auto }
        .page-item{ display:flex; align-items:center; gap:10px; padding:10px; cursor:pointer; border-bottom:1px dashed var(--line) }
        .page-item:hover{ background:#101a29 }
        .page-item.selected{ background:rgba(58,124,255,.12); outline:1px solid var(--primary) }
        .page-avatar{ width:32px; height:32px; border-radius:8px; background:#223149; object-fit:cover }

        /* DROPZONE */
        .drop{ background:#0f1622; border:2px dashed var(--line2); border-radius:12px; padding:12px; text-align:center }
        .drop.drag{ border-color:var(--primary); background:rgba(58,124,255,.05) }
        .thumbs{ display:grid; grid-template-columns: repeat(5, 1fr); gap:8px; margin-top:10px }
        .thumb{ position:relative; width:100%; aspect-ratio:1/1; border-radius:10px; overflow:hidden; border:1px solid var(--line) }
        .thumb img{ width:100%; height:100%; object-fit:cover }
        .thumb .x{ position:absolute; right:6px; top:6px; background:rgba(0,0,0,.4); border:1px solid var(--line2);
                   width:22px; height:22px; border-radius:999px; display:grid; place-items:center; cursor:pointer }

        /* TABLE */
        table{ width:100%; border-collapse:collapse }
        th,td{ border-bottom:1px dashed var(--line); padding:10px 6px; vertical-align:top }
        .muted{ color:var(--muted) }
        .pill{ background:#20324a; padding:3px 8px; border-radius:999px; font-size:12px }
        @media (max-width: 980px){ .wrap{ grid-template-columns: 1fr } }
    </style>
</head>
<body>
<div class="wrap">

    {{-- LEFT: COMPOSE --}}
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

        <form id="scheduleForm" action="{{ route('schedule.store') }}" method="POST" enctype="multipart/form-data" class="mt10">
            @csrf

            {{-- STEP 1: CHỌN PAGE --}}
            <div class="mb6">Bước 1 — Chọn Page</div>
            <div class="page-box">
                <div class="page-head">
                    <input id="pageFilter" type="text" class="page-search" placeholder="Tìm theo tên hoặc ID...">
                    <span class="small muted" id="pageCount">{{ count($pages) }} pages</span>
                </div>
                <div class="page-list" id="pageList">
                    @foreach ($pages as $p)
                        <div class="page-item" data-id="{{ $p->page_id }}" data-name="{{ $p->name }}">
                            <img class="page-avatar" src="{{ $p->picture_url ?? 'https://placehold.co/64x64?text=P' }}" alt="">
                            <div style="flex:1">
                                <div style="font-weight:600">{{ $p->name }}</div>
                                <div class="small muted">{{ $p->page_id }}</div>
                            </div>
                            <div class="pill small">Chọn</div>
                        </div>
                    @endforeach
                </div>
            </div>
            <input type="hidden" name="page_id" id="page_id" required>
            <input type="hidden" name="page_name" id="page_name">

            {{-- STEP 2: NỘI DUNG --}}
            <div class="mt12 mb6">Bước 2 — Nội dung</div>
            <textarea name="message" placeholder="Viết nội dung…">{{ old('message') }}</textarea>

            {{-- STEP 3: ẢNH --}}
            <div class="mt12 mb6">Bước 3 — Ảnh (tối đa 5 ảnh, mỗi ảnh ≤ 5MB)</div>
            <div id="drop" class="drop">
                <div class="small muted">Kéo thả ảnh vào đây hoặc <span class="btn btn-ghost" id="btnPick">Chọn ảnh</span></div>
                <input id="photos" type="file" name="photos[]" multiple accept="image/*" hidden>
                <div class="small mt6" id="counter">0 / 5</div>
                <div id="thumbs" class="thumbs"></div>
            </div>

            {{-- STEP 4: THỜI GIAN --}}
            <div class="mt12 mb6">Bước 4 — Múi giờ & thời điểm đăng</div>
            <div class="row">
                <div>
                    <div class="small mb6">Múi giờ</div>
                    <input type="text" id="tzFilter" placeholder="Tìm múi giờ..." class="mb6">
                    <select name="timezone" id="timezone" required>
                        @php $tzLocal = $tz ?? 'UTC'; @endphp
                        @foreach ($timezones as $tzOpt)
                            <option value="{{ $tzOpt }}" {{ $tzLocal === $tzOpt ? 'selected' : '' }}>{{ $tzOpt }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <div class="small mb6">Thời điểm đăng (theo múi giờ đã chọn)</div>
                    <input type="datetime-local" name="publish_at" id="publish_at" required>
                    <div class="mt6">
                        <span class="chip" data-plus="15">+15m</span>
                        <span class="chip" data-plus="30">+30m</span>
                        <span class="chip" data-plus="60">+1h</span>
                        <span class="chip" data-plus="120">+2h</span>
                    </div>
                </div>
            </div>

            <div class="small muted mt10" id="previewTime">
                Chưa chọn thời điểm.
            </div>

            <div class="mt16">
                <button class="btn btn-primary" type="submit">Lưu lịch</button>
            </div>
        </form>
    </div>

    {{-- RIGHT: LIST --}}
    <div class="card">
        <div class="title">Danh sách lịch</div>
        <div class="small muted">Hiển thị 15 dòng gần nhất.</div>

        <table class="mt10">
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
                            <span class="pill">Album {{ is_array($item->media_paths) ? count($item->media_paths) : 0 }} ảnh</span>
                        @elseif($item->media_type === 'photo')
                            <span class="pill">Ảnh đơn</span>
                        @endif
                        <div>{{ $preview !== '' ? $preview : '—' }}</div>
                    </td>
                    <td>
                        @if($local)
                            {{ $local->format('Y-m-d H:i:s') }}
                            <div class="small muted">({{ $tzShow }})</div>
                        @else — @endif
                    </td>
                    <td><span class="small">{{ $item->status }}</span></td>
                    <td>
                        @if (in_array($item->status, ['queued','processing']))
                            <form action="{{ route('schedule.cancel', $item) }}" method="POST"
                                  onsubmit="return confirm('Hủy lịch #{{ $item->id }}?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger small" type="submit">Hủy</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="small muted">Không có lịch nào.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
(function(){
    // ====== PAGE PICKER ======
    const list  = document.getElementById('pageList');
    const filter= document.getElementById('pageFilter');
    const pid   = document.getElementById('page_id');
    const pname = document.getElementById('page_name');
    const pcount= document.getElementById('pageCount');

    function applyPageFilter(){
        const q = filter.value.toLowerCase();
        let visible = 0;
        [...list.children].forEach(el=>{
            const name = el.getAttribute('data-name')?.toLowerCase()||'';
            const id   = el.getAttribute('data-id')?.toLowerCase()||'';
            const ok = name.includes(q) || id.includes(q);
            el.style.display = ok ? '' : 'none';
            if(ok) visible++;
        });
        pcount.textContent = visible + ' pages';
    }
    filter.addEventListener('input', applyPageFilter);
    applyPageFilter();

    function selectItem(el){
        [...list.children].forEach(x=>x.classList.remove('selected'));
        el.classList.add('selected');
        pid.value = el.getAttribute('data-id') || '';
        pname.value = el.getAttribute('data-name') || '';
    }
    list.addEventListener('click', (e)=>{
        const item = e.target.closest('.page-item');
        if(item) selectItem(item);
    });

    // ====== DROPZONE + THUMBS ======
    const drop   = document.getElementById('drop');
    const input  = document.getElementById('photos');
    const thumbs = document.getElementById('thumbs');
    const counter= document.getElementById('counter');
    const btnPick= document.getElementById('btnPick');

    btnPick.addEventListener('click', ()=>input.click());
    drop.addEventListener('dragover', e=>{ e.preventDefault(); drop.classList.add('drag'); });
    drop.addEventListener('dragleave', ()=> drop.classList.remove('drag'));
    drop.addEventListener('drop', (e)=>{
        e.preventDefault(); drop.classList.remove('drag');
        const files = [...e.dataTransfer.files].filter(f=>f.type.startsWith('image/'));
        // merge vào FileList input (không thể gộp trực tiếp -> dùng DataTransfer)
        const dt = new DataTransfer();
        [...input.files, ...files].forEach(f=> dt.items.add(f));
        input.files = dt.files;
        renderThumbs();
    });

    input.addEventListener('change', renderThumbs);

    function renderThumbs(){
        const files = [...input.files];
        // Giới hạn 5 ảnh & 5MB
        let filtered = files.slice(0,5).filter(f=> f.size <= 5*1024*1024);
        if(filtered.length !== files.length){
            alert('Tối đa 5 ảnh và mỗi ảnh ≤ 5MB. Các ảnh vượt giới hạn đã bị bỏ.');
        }
        // set back
        const dt = new DataTransfer();
        filtered.forEach(f=> dt.items.add(f));
        input.files = dt.files;

        thumbs.innerHTML = '';
        filtered.forEach((f, idx)=>{
            const url = URL.createObjectURL(f);
            const div = document.createElement('div');
            div.className = 'thumb';
            div.innerHTML = `<img src="${url}"><div class="x">✕</div>`;
            div.querySelector('.x').onclick = ()=>{
                const arr = [...input.files]; arr.splice(idx,1);
                const dt2 = new DataTransfer(); arr.forEach(x=> dt2.items.add(x));
                input.files = dt2.files; renderThumbs();
            };
            thumbs.appendChild(div);
        });
        counter.textContent = `${filtered.length} / 5`;
    }

    // ====== TIMEZONE FILTER ======
    const tzFilter = document.getElementById('tzFilter');
    const tzSelect = document.getElementById('timezone');
    tzFilter.addEventListener('input', ()=>{
        const q = tzFilter.value.toLowerCase();
        [...tzSelect.options].forEach(o=>{
            const ok = o.value.toLowerCase().includes(q);
            o.hidden = !ok;
        });
    });

    // ====== QUICK ADD TIME ======
    const publishAt = document.getElementById('publish_at');
    document.querySelectorAll('.chip[data-plus]').forEach(chip=>{
        chip.addEventListener('click', ()=>{
            const m = parseInt(chip.getAttribute('data-plus'),10) || 0;
            const now = new Date();
            now.setMinutes(now.getMinutes()+m);
            // value for datetime-local = YYYY-MM-DDTHH:mm
            publishAt.value = toDatetimeLocal(now);
            updatePreview();
        });
    });

    function toDatetimeLocal(d){
        const pad=(n)=> String(n).padStart(2,'0');
        return `${d.getFullYear()}-${pad(d.getMonth()+1)}-${pad(d.getDate())}T${pad(d.getHours())}:${pad(d.getMinutes())}`;
    }

    // ====== PREVIEW TIME TEXT ======
    const preview = document.getElementById('previewTime');
    function updatePreview(){
        const tz = tzSelect.value || 'UTC';
        const val = publishAt.value;
        if(!val){ preview.textContent = 'Chưa chọn thời điểm.'; return; }
        // Hiển thị trực quan: người dùng nhập là "thời điểm theo múi giờ đã chọn".
        // Không convert ở client để tránh sai lệch; backend sẽ quy đổi UTC khi lưu.
        const dateText = val.replace('T',' ');
        preview.innerHTML = `Bạn sẽ đăng vào: <b>${dateText}</b> <span class="small">(${tz})</span><br>
            <span class="small">* Hệ thống sẽ tự quy đổi sang UTC khi lưu.</span>`;
    }
    publishAt.addEventListener('input', updatePreview);
    tzSelect.addEventListener('change', updatePreview);

    // mặc định: gợi ý giờ hiện tại + 15 phút
    (function initDefaultTime(){
        const d = new Date(); d.setMinutes(d.getMinutes()+15);
        publishAt.value = toDatetimeLocal(d);
        updatePreview();
    })();

    // ====== VALIDATE BEFORE SUBMIT ======
    document.getElementById('scheduleForm').addEventListener('submit', (e)=>{
        if(!pid.value){
            e.preventDefault(); alert('Hãy chọn Page trước khi lưu lịch.'); return false;
        }
        if(input.files.length>5){
            e.preventDefault(); alert('Tối đa 5 ảnh.'); return false;
        }
        // set page_name theo selection hiện tại (phòng khi user chọn bằng click)
        if(!pname.value){
            const sel = list.querySelector('.page-item.selected');
            if(sel){
                pname.value = sel.getAttribute('data-name') || '';
                pid.value   = sel.getAttribute('data-id') || '';
            }
        }
    });
})();
</script>
</body>
</html>
