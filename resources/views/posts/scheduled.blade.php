@extends('layouts.app')
@section('title','Bài đã lên lịch')
@section('content')
<h4 class="mb-3">Bài đã lên lịch</h4>
@if($items->isEmpty())
  <div class="alert alert-info">Chưa có bài hẹn giờ.</div>
@else
  <div class="list-group">
    @foreach($items as $it)
    <div class="list-group-item">
      <div class="d-flex justify-content-between">
        <div><div class="fw-bold">{{ $it->message }}</div>
          <div class="text-muted small">Đăng lúc: {{ $it->scheduled_at }}</div></div>
        @if($it->fb_post_id)
        <form method="post" action="{{ route('posts.destroy',$it->fb_post_id) }}">@csrf @method('delete')
            <button class="btn btn-sm btn-outline-danger">Hủy</button>
        </form>
        @endif
      </div>
    </div>
    @endforeach
  </div>
@endif
@endsection
