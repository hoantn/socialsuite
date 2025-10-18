@extends('layouts.app')
@section('title','Bài viết')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h4>Bài viết trên Page</h4>
  <div><a href="{{ route('posts.create') }}" class="btn btn-primary">Tạo bài viết</a>
    <a href="{{ route('posts.scheduled') }}" class="btn btn-outline-primary">Đã lên lịch</a></div>
</div>
@if(!$page)
  <div class="alert alert-warning">Bạn chưa chọn Page. Vào mục Facebook → chọn một Page.</div>
@else
  @if($error)<div class="alert alert-danger">{{ $error }}</div>@endif
  @if(empty($posts))
    <div class="alert alert-info">Chưa có bài viết.</div>
  @else
  <div class="row g-3">
    @foreach($posts as $post)
    <div class="col-md-6"><div class="card shadow-sm"><div class="card-body">
      <div class="text-muted small">{{ $post['created_time'] ?? '' }}</div>
      <div class="fw-bold mb-2">{{ $post['message'] ?? '(không có nội dung)' }}</div>
      <a class="btn btn-sm btn-outline-secondary" target="_blank" href="{{ $post['permalink_url'] ?? '#' }}">Mở Facebook</a>
      @if(isset($post['id']))
      <form class="d-inline" method="post" action="{{ route('posts.destroy',$post['id']) }}">@csrf @method('delete')
        <button class="btn btn-sm btn-outline-danger">Xoá</button>
      </form>
      @endif
    </div></div></div>
    @endforeach
  </div>
  @endif
@endif
@endsection
