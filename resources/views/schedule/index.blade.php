@php
    use Carbon\CarbonImmutable;
    $tz = $tz ?? 'Asia/Ho_Chi_Minh';
@endphp
@extends('layouts.app')
@section('content')
<div class="container mx-auto max-w-6xl text-gray-200">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-slate-800 rounded-xl p-4">
            <h2 class="text-lg font-semibold mb-3">Tạo lịch mới</h2>
            @if(session('ok'))
                <div class="bg-emerald-600/20 border border-emerald-600 text-emerald-200 px-3 py-2 rounded mb-3">
                    {{ session('ok') }}
                </div>
            @endif
            <form method="POST" action="{{ route('schedule.store') }}" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm mb-1">Chọn Page</label>
                    <div class="border border-slate-600 rounded">
                        <div class="max-h-56 overflow-y-auto divide-y divide-slate-700">
                            @foreach($pages as $p)
                                <label class="flex items-center gap-3 p-2 hover:bg-slate-700">
                                    <input type="checkbox" name="page_ids[]" value="{{ $p->page_id }}" class="accent-indigo-500">
                                    <div class="text-sm">
                                        <div class="font-medium">{{ $p->name }}</div>
                                        <div class="text-slate-400">{{ $p->page_id }}</div>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>
                    @error('page_ids') <div class="text-red-400 text-sm mt-1">{{ $message }}</div> @enderror
                </div>

                <div>
                    <label class="block text-sm mb-1">Nội dung</label>
                    <textarea name="message" rows="3" class="w-full bg-slate-900 border border-slate-600 rounded p-2" placeholder="Viết nội dung...">{{ old('message') }}</textarea>
                </div>

                <div>
                    <label class="block text-sm mb-1">Ảnh (tối đa 5)</label>
                    <input type="file" name="photos[]" multiple accept="image/*" class="w-full bg-slate-900 border border-slate-600 rounded p-2">
                    @error('photos') <div class="text-red-400 text-sm mt-1">{{ $message }}</div> @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm mb-1">Múi giờ</label>
                        <select name="timezone" class="w-full bg-slate-900 border border-slate-600 rounded p-2">
                            <option value="Asia/Ho_Chi_Minh" {{ ($tz==='Asia/Ho_Chi_Minh')?'selected':'' }}>UTC+7 — Asia/Ho_Chi_Minh</option>
                            <option value="UTC" {{ ($tz==='UTC')?'selected':'' }}>UTC</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm mb-1">Thời điểm đăng</label>
                        <input type="text" name="publish_at" value="{{ now($tz)->addMinutes(15)->format('Y-m-d H:i') }}" class="w-full bg-slate-900 border border-slate-600 rounded p-2">
                        <div class="text-xs text-slate-400 mt-1">Nhập dạng <code>YYYY-mm-dd HH:ii</code>. Lưu ý: lưu dưới UTC.</div>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button class="px-4 py-2 rounded bg-indigo-600 hover:bg-indigo-500">Lưu lịch</button>
                </div>
            </form>
        </div>

        <div class="bg-slate-800 rounded-xl p-4">
            <h2 class="text-lg font-semibold mb-3">Danh sách lịch</h2>
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-slate-400">
                        <th class="text-left py-2">#</th>
                        <th class="text-left">Page</th>
                        <th class="text-left">Nội dung</th>
                        <th class="text-left">Thời gian<br><span class="text-xs">({{ $tz }})</span></th>
                        <th class="text-left">Trạng thái</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700">
                    @foreach($items as $it)
                        @php
                            $local = CarbonImmutable::parse($it->publish_at, 'UTC')->setTimezone($tz);
                        @endphp
                        <tr>
                            <td class="py-2">#{{ $it->id }}</td>
                            <td class="py-2">{{ $it->page_id }}</td>
                            <td class="py-2">{{ Str::limit($it->message, 40) }}</td>
                            <td class="py-2">{{ $local->format('Y-m-d H:i') }}</td>
                            <td class="py-2">
                                <span class="px-2 py-0.5 rounded bg-slate-700">{{ $it->status }}</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
