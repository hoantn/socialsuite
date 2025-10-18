@extends('shared/layout')
@section('content')
<div class=\"wrap\">
  @if(session('ok'))<div class=\"alert alert-ok\">{{ session('ok') }}</div>@endif
  @if(session('error'))<div class=\"alert alert-err\">{{ session('error') }}</div>@endif
  <div class=\"grid\" style=\"grid-template-columns:repeat(auto-fill,minmax(240px,1fr))\">
    @forelse($pages as $p)
      <a href=\"{{ route('pages.posts',$p) }}\" class=\"card\">
        <div style=\"font-weight:700\">{{ $p->name }}</div>
        <div style=\"color:#64748b\">ID: {{ $p->page_id }}</div>
      </a>
    @empty
      <div class=\"card\">Chưa có Page nào. Nhấn “Đồng bộ Page” ở trang Tài khoản.</div>
    @endforelse
  </div>
  <div style=\"margin-top:10px\">{{ $pages->links() }}</div>
</div>
@endsection
