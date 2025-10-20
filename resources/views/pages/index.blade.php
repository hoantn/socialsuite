@extends('layouts.app')
@section('content')
<div class="container">
  <h1>Pages</h1>
  @if(session('ok')) <div class="alert alert-success">{{ session('ok') }}</div> @endif
  <form action="{{ route('pages.sync') }}" method="post">@csrf
    <button class="btn btn-primary">Đồng bộ từ Facebook</button>
  </form>
  <hr>
  <div class="row">
    @foreach($pages as $p)
      <div class="col-md-4">
        <div class="card mb-3">
          <div class="card-body d-flex align-items-center">
            @if($p->avatar_url)
              <img src="{{ $p->avatar_url }}" style="height:40px;width:40px;border-radius:50%;object-fit:cover">
            @endif
            <div class="ms-3">
              <strong>{{ $p->name ?? $p->page_id }}</strong>
              <div class="mt-2">
                <a class="btn btn-outline-secondary btn-sm" href="{{ route('pages.settings',$p->page_id) }}">
                  Cấu hình
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    @endforeach
  </div>
</div>
@endsection
