@extends('layouts.app')
@section('title','Dashboard')
@section('content')
<div class="row g-3">
  <div class="col-md-4"><div class="card shadow-sm"><div class="card-body">
    <div class="text-muted">Tổng bài đã tạo</div><div class="h3">{{ $postsCount }}</div>
  </div></div></div>
  <div class="col-md-4"><div class="card shadow-sm"><div class="card-body">
    <div class="text-muted">Bài đang lên lịch</div><div class="h3">{{ $scheduledCount }}</div>
  </div></div></div>
  <div class="col-md-4"><div class="card shadow-sm"><div class="card-body">
    <div class="text-muted">Số Fanpage</div><div class="h3">{{ $pages->count() }}</div>
  </div></div></div>
</div>
<div class="mt-4">
  <a href="{{ route('fb.pages') }}" class="btn btn-primary">Kết nối/Quản lý Facebook</a>
  <a href="{{ route('posts.index') }}" class="btn btn-outline-primary">Quản lý bài viết</a>
</div>
@endsection
