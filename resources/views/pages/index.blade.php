@extends('layouts.app')
@section('content')
<div class="mx-auto max-w-7xl px-4 pt-10 pb-10">
  <div class="flex items-center justify-between">
    <h1 class="text-2xl font-bold">Trang của bạn</h1>
    <form method="POST" action="{{ route('pages.sync') }}">@csrf
      <button class="px-4 py-2 rounded-xl bg-brand text-white">Đồng bộ từ Facebook</button>
    </form>
  </div>
  @if(session('ok'))<div class="mt-4 p-3 rounded bg-green-50 text-green-700">{{ session('ok') }}</div>@endif
  @if(session('error'))<div class="mt-4 p-3 rounded bg-red-50 text-red-700">{{ session('error') }}</div>@endif
  <div class="mt-6 grid md:grid-cols-3 gap-4">
    @forelse($pages as $p)
      <a href="{{ route('pages.posts',$p) }}" class="p-4 border rounded-2xl hover:shadow-sm block">
        <div class="font-semibold">{{ $p->name }}</div>
        <div class="text-sm text-slate-500">ID: {{ $p->page_id }}</div>
      </a>
    @empty
      <div class="text-slate-500">Chưa có Page nào. Nhấn “Đồng bộ từ Facebook”.</div>
    @endforelse
  </div>
  <div class="mt-6">{{ $pages->links() }}</div>
</div>
@endsection