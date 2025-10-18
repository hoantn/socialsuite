@extends('layouts.app')
@section('title','Tạo bài viết')
@section('content')
<h4 class="mb-3">Tạo bài viết</h4>
@if(!$page)
  <div class="alert alert-warning">Bạn chưa chọn Page. Vào mục Facebook → chọn một Page trước.</div>
@else
<form method="post" action="{{ route('posts.store') }}">@csrf
  <div class="mb-3"><label class="form-label">Nội dung</label>
    <textarea name="message" rows="5" class="form-control" placeholder="Bạn đang nghĩ gì?">{{ old('message') }}</textarea></div>
  <div class="row g-3"><div class="col-md-4">
    <label class="form-label">Lên lịch</label>
    <input type="datetime-local" name="schedule_at" class="form-control" value="{{ old('schedule_at') }}">
    <div class="form-text">Nếu để trống hoặc chọn thời gian đã qua → đăng ngay.</div>
  </div></div>
  <div class="mt-3"><button class="btn btn-primary">Đăng</button></div>
</form>
@endif
@endsection
