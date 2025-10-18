@extends('layouts.app')
@section('content')
<div class="mx-auto max-w-7xl px-4 pt-10 pb-10 grid md:grid-cols-3 gap-6">
  <div class="md:col-span-2">
    <h1 class="text-2xl font-bold">{{ $page->name }}</h1>
    @if(session('ok'))<div class="mt-4 p-3 rounded bg-green-50 text-green-700">{{ session('ok') }}</div>@endif
    @if(session('error'))<div class="mt-4 p-3 rounded bg-red-50 text-red-700">{{ session('error') }}</div>@endif
    <div class="mt-6 space-y-4">
      @foreach($posts as $post)
        <div class="p-4 border rounded-2xl">
          <div class="text-sm text-slate-500 flex items-center justify-between">
            <div>#{{ $post->id }} • {{ $post->type }} • {{ $post->status }}</div>
            <div>{{ optional($post->scheduled_at)->format('d/m H:i') }}</div>
          </div>
          <div class="mt-2 whitespace-pre-wrap">{{ $post->message }}</div>
          @if($post->link)<div class="mt-1 text-blue-600 text-sm">{{ $post->link }}</div>@endif
          @if($post->image_url)<img src="{{ $post->image_url }}" class="mt-2 rounded-xl max-h-60 object-cover">@endif
          <div class="mt-3 flex gap-2">
            @if($post->status !== 'published')
            <form method="POST" action="{{ route('pages.posts.publish', [$page,$post]) }}">
              @csrf
              <button class="px-3 py-1 rounded-xl border hover:border-brand hover:text-brand">Đăng ngay</button>
            </form>
            @endif
            @if($post->fb_post_id)
              <a target="_blank" class="px-3 py-1 rounded-xl border" href="https://facebook.com/{{ $post->fb_post_id }}">Xem trên Facebook</a>
            @endif
          </div>
        </div>
      @endforeach
    </div>
    <div class="mt-6">{{ $posts->links() }}</div>
  </div>
  <div class="md:col-span-1">
    <div class="p-4 border rounded-2xl">
      <div class="font-semibold">Tạo bài viết</div>
      <form class="mt-3 space-y-3" method="POST" action="{{ route('pages.posts.store', $page) }}">
        @csrf
        <div>
          <label class="text-sm">Loại</label>
          <select name="type" class="mt-1 w-full border rounded-xl p-2">
            <option value="text">Văn bản</option>
            <option value="photo">Ảnh (URL)</option>
            <option value="link">Link</option>
          </select>
        </div>
        <div>
          <label class="text-sm">Nội dung</label>
          <textarea name="message" rows="4" class="mt-1 w-full border rounded-xl p-3"></textarea>
        </div>
        <div>
          <label class="text-sm">Link (tuỳ chọn)</label>
          <input name="link" type="url" class="mt-1 w-full border rounded-xl p-2"/>
        </div>
        <div>
          <label class="text-sm">Ảnh - URL (tuỳ chọn)</label>
          <input name="image_url" type="url" class="mt-1 w-full border rounded-xl p-2"/>
        </div>
        <div>
          <label class="text-sm">Lịch đăng (tuỳ chọn)</label>
          <input name="scheduled_at" type="datetime-local" class="mt-1 w-full border rounded-xl p-2"/>
        </div>
        <div class="flex gap-2">
          <button name="action" value="save" class="px-4 py-2 rounded-xl border">Lưu nháp</button>
          <button name="action" value="publish_now" class="px-4 py-2 rounded-xl bg-brand text-white">Đăng ngay</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
