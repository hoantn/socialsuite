@extends('layouts.app')
@section('title','Facebook Pages')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h4>Facebook Pages</h4>
  <a href="{{ route('fb.redirect') }}" class="btn btn-primary">Kết nối Facebook</a>
</div>
@if($pages->isEmpty())
  <div class="alert alert-info">Chưa có fanpage nào. Bấm "Kết nối Facebook" để lấy danh sách trang bạn quản lý.</div>
@else
  <div class="card shadow-sm"><div class="table-responsive">
    <table class="table table-hover mb-0"><thead><tr><th>Tên Page</th><th>Page ID</th><th>Chọn</th></tr></thead><tbody>
      @foreach($pages as $p)
      <tr><td>{{ $p->name }}</td><td>{{ $p->page_id }}</td>
        <td><form method="post" action="{{ route('fb.pages.select') }}">@csrf
          <input type="hidden" name="page_id" value="{{ $p->page_id }}">
          <button class="btn btn-sm btn-outline-primary">Chọn</button></form></td></tr>
      @endforeach
    </tbody></table>
  </div></div>
@endif
@endsection
