@extends('layouts.app')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
  <div class="card p-5">
    <h2 class="text-xl font-semibold mb-4">Tạo lịch mới</h2>

    @if(session('ok'))
      <div class="mb-3 text-green-300">{{ session('ok') }}</div>
    @endif
    @if(session('error'))
      <div class="mb-3 text-red-300">{{ session('error') }}</div>
    @endif
    @if ($errors->any())
      <div class="mb-3 text-red-300">
        <ul class="list-disc ml-5">
          @foreach ($errors->all() as $e)
            <li>{{ $e }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form action="{{ route('schedule.store') }}" method="POST" enctype="multipart/form-data" id="scheduleForm">
      @csrf

      {{-- Bước 1: chọn Page --}}
      <div class="mb-5">
        <label class="block mb-2">Chọn Page (có thể chọn nhiều)</label>
        <input type="text" id="pageSearch" placeholder="Tìm theo tên..." class="mb-2">
        <div class="max-h-64 overflow-auto space-y-2" id="pageList">
          @foreach($pages as $p)
          <label class="flex items-center gap-3 p-2 rounded hover:bg-slate-800 cursor-pointer">
            <input type="checkbox" name="page_ids[]" value="{{ $p->page_id ?? $p->id }}">
            <img src="{{ $p->picture_url ?? 'https://i.pravatar.cc/48' }}" class="w-8 h-8 rounded" alt="">
            <div>
              <div class="font-medium">{{ $p->name }}</div>
              <div class="text-xs text-slate-400">{{ $p->page_id ?? $p->id }}</div>
            </div>
          </label>
          @endforeach
        </div>
      </div>

      {{-- Bước 2: nội dung --}}
      <div class="mb-5">
        <label class="block mb-2">Nội dung</label>
        <textarea name="message" rows="4" placeholder="Viết nội dung..." ></textarea>
      </div>

      {{-- Bước 3: Ảnh / Video --}}
      <div class="mb-5">
        <label class="block mb-2">Ảnh (1-5) hoặc 1 video</label>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <div class="mb-2 text-sm text-slate-400">Ảnh (tối đa 5, ≤5MB/ảnh)</div>
            <input type="file" name="photos[]" id="photos" accept="image/*" multiple>
            <div id="photoPreview" class="grid grid-cols-5 gap-2 mt-2"></div>
          </div>
          <div>
            <div class="mb-2 text-sm text-slate-400">Video (tối đa 1, ≤50MB)</div>
            <input type="file" name="videos[]" id="videos" accept="video/*">
            <div id="videoPreview" class="mt-2"></div>
          </div>
        </div>
        <div class="mt-2 text-xs text-amber-300">Facebook không cho phép đăng chung ảnh và video trong một bài.</div>
      </div>

      {{-- Bước 4: Múi giờ & thời điểm --}}
      <div class="mb-5">
        <label class="block mb-2">Múi giờ</label>
        <select name="timezone" id="timezone">
          @php
            $tzDefault = $tz ?? config('app.timezone', 'Asia/Ho_Chi_Minh');
          @endphp
          @foreach(['Asia/Ho_Chi_Minh','UTC','Asia/Bangkok','Asia/Tokyo','Asia/Singapore'] as $z)
            <option value="{{ $z }}" {{ $tzDefault===$z?'selected':'' }}>
              {{ $z }} {{ $z==='Asia/Ho_Chi_Minh'?'(UTC+7)':'' }}
            </option>
          @endforeach
        </select>
      </div>

      <div class="mb-5">
        <label class="block mb-2">Thời điểm đăng (theo múi giờ đã chọn)</label>
        <input type="datetime-local" name="publish_at" id="publishAt">
        <div class="mt-2 flex gap-2">
          <button type="button" class="chip" onclick="addMinutes(15)">+15m</button>
          <button type="button" class="chip" onclick="addMinutes(30)">+30m</button>
          <button type="button" class="chip" onclick="addMinutes(60)">+1h</button>
          <button type="button" class="chip" onclick="addMinutes(120)">+2h</button>
        </div>
      </div>

      <div class="flex justify-end">
        <button class="btn" type="submit">Lưu lịch</button>
      </div>
    </form>
  </div>

  {{-- Danh sách lịch --}}
  <div class="card p-5">
    <h2 class="text-xl font-semibold mb-4">Danh sách lịch</h2>
    <div class="overflow-x-auto">
      <table class="w-full text-sm">
        <thead>
          <tr class="text-left text-slate-300">
            <th class="py-2">#</th>
            <th class="py-2">Page</th>
            <th class="py-2">Nội dung</th>
            <th class="py-2">Thời gian</th>
            <th class="py-2">Trạng thái</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          @foreach($items as $it)
          @php
            $media = json_decode($it->media_paths, true) ?: [];
            $tzItem = $it->timezone ?: ($tz ?? 'Asia/Ho_Chi_Minh');
            $time = \Carbon\Carbon::parse($it->publish_at, 'UTC')->setTimezone($tzItem);
            $page = collect($pages)->first(fn($p)=>($p->page_id??$p->id)==$it->page_id);
          @endphp
          <tr class="border-t border-slate-800">
            <td class="py-2">#{{ $it->id }}</td>
            <td class="py-2">
              <div class="flex items-center gap-2">
                <img src="{{ $page->picture_url ?? 'https://i.pravatar.cc/32' }}" class="w-6 h-6 rounded">
                <div class="font-medium">{{ $page->name ?? $it->page_id }}</div>
              </div>
            </td>
            <td class="py-2">
              <div class="line-clamp-2 max-w-[280px]">{{ $it->message }}</div>
              <div class="text-xs text-slate-400">
                @if($it->media_type==='image')
                  {{ count($media) }} ảnh
                @else
                  1 video
                @endif
              </div>
            </td>
            <td class="py-2">
              <div>{{ $time->format('Y-m-d H:i:s') }}</div>
              <div class="text-xs text-slate-400">({{ $tzItem }})</div>
            </td>
            <td class="py-2">
              <span class="chip">{{ $it->status }}</span>
            </td>
            <td class="py-2">
              @if($it->status==='queued')
              <form method="POST" action="{{ route('schedule.cancel',$it->id) }}">
                @csrf
                <button class="btn btn-danger" onclick="return confirm('Hủy lịch #{{ $it->id }}?')">Hủy</button>
              </form>
              @endif
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
  // set default datetime-local = now + 15m
  function pad(n){return n<10?('0'+n):n}
  function toLocalInputValue(d){
    return d.getFullYear()+'-'+pad(d.getMonth()+1)+'-'+pad(d.getDate())+'T'+pad(d.getHours())+':'+pad(d.getMinutes())
  }
  const publishAt = document.getElementById('publishAt');
  if(publishAt && !publishAt.value){
    const d=new Date(); d.setMinutes(d.getMinutes()+15); publishAt.value=toLocalInputValue(d);
  }
  function addMinutes(m){
    const d = publishAt.value? new Date(publishAt.value) : new Date();
    d.setMinutes(d.getMinutes()+m);
    publishAt.value = toLocalInputValue(d);
  }

  // preview images
  const photos = document.getElementById('photos');
  const photoPreview = document.getElementById('photoPreview');
  if(photos){
    photos.addEventListener('change', e=>{
      photoPreview.innerHTML='';
      Array.from(e.target.files||[]).slice(0,5).forEach(f=>{
        const url = URL.createObjectURL(f);
        const img = document.createElement('img');
        img.src=url; img.className='w-16 h-16 object-cover rounded';
        photoPreview.appendChild(img);
      })
    })
  }
  // preview video
  const videos = document.getElementById('videos');
  const videoPreview = document.getElementById('videoPreview');
  if(videos){
    videos.addEventListener('change', e=>{
      videoPreview.innerHTML='';
      const f = (e.target.files||[])[0];
      if(f){
        const url = URL.createObjectURL(f);
        const v = document.createElement('video');
        v.src=url; v.controls=true; v.className='w-full max-w-xs rounded';
        videoPreview.appendChild(v);
      }
    })
  }

  // page filter
  const pageSearch = document.getElementById('pageSearch');
  if(pageSearch){
    pageSearch.addEventListener('input', ()=>{
      const q = pageSearch.value.toLowerCase();
      document.querySelectorAll('#pageList label').forEach(el=>{
        const txt = el.innerText.toLowerCase();
        el.style.display = txt.includes(q) ? '' : 'none';
      })
    })
  }
</script>
@endpush
