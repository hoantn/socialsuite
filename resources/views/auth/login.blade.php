@extends('layouts.app')
@section('title','Đăng nhập')
@section('content')
<div class="row justify-content-center"><div class="col-md-5">
  <div class="card shadow-sm"><div class="card-body">
    <h5 class="mb-3">Đăng nhập</h5>
    <form method="post" action="{{ url('/login') }}">@csrf
      <div class="mb-3"><label class="form-label">Tên đăng nhập</label>
        <input name="username" class="form-control" value="{{ old('username') }}"></div>
      <div class="mb-3"><label class="form-label">Mật khẩu</label>
        <input type="password" name="password" class="form-control"></div>
      <div class="mb-3 form-check"><input type="checkbox" name="remember" class="form-check-input" id="remember">
        <label for="remember" class="form-check-label">Ghi nhớ</label></div>
      <button class="btn btn-primary">Đăng nhập</button>
    </form>
  </div></div>
</div></div>
@endsection
