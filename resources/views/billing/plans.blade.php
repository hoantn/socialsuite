@extends('layouts.app')
@section('title','Gói dịch vụ')
@section('content')
<h4 class="mb-3">Chọn gói phù hợp</h4>
<div class="row g-3">
  @foreach($plans as $p)
  <div class="col-md-4"><div class="card shadow-sm h-100">
    <div class="card-body d-flex flex-column">
      <h5 class="card-title">{{ $p->name }}</h5>
      <div class="display-6">{{ number_format($p->price) }}đ<span class="fs-6">/tháng</span></div>
      <ul class="mt-3">
        @php $f=$p->features??[]; @endphp
        <li>Tối đa fanpage: {{ ($f['max_pages']??1)===-1?'Không giới hạn':($f['max_pages']??1) }}</li>
        <li>Bài lên lịch/tháng: {{ ($f['max_scheduled_posts']??5)===-1?'Không giới hạn':($f['max_scheduled_posts']??5) }}</li>
        <li>Inbox: {{ ($f['inbox']??false)?'Có':'Không' }}</li>
      </ul>
      <form method="post" action="{{ route('plans.upgrade') }}" class="mt-auto">@csrf
        <input type="hidden" name="plan_id" value="{{ $p->id }}">
        <button class="btn btn-primary w-100" {{ (auth()->user()->plan_id==$p->id)?'disabled':'' }}>
          {{ (auth()->user()->plan_id==$p->id)?'Đang dùng':'Nâng cấp' }}
        </button>
      </form>
    </div></div></div>
  @endforeach
</div>
@endsection
