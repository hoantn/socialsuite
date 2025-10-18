@extends('layouts.app')
@section('title','Đăng ký')
@section('content')
<div class="row justify-content-center"><div class="col-md-6">
  <div class="card shadow-sm"><div class="card-body">
    <h5 class="mb-3">Tạo tài khoản</h5>
    <form method="post" action="{{ url('/register') }}">@csrf
      <div class="row g-3">
        <div class="col-md-6"><label class="form-label">Tên đăng nhập</label>
          <input name="username" class="form-control" value="{{ old('username') }}"></div>
        <div class="col-md-6"><label class="form-label">Mật khẩu</label>
          <input type="password" name="password" class="form-control"></div>
        <div class="col-md-6"><label class="form-label">Email (không bắt buộc)</label>
          <input name="email" class="form-control" value="{{ old('email') }}"></div>
        <div class="col-md-6"><label class="form-label">SĐT (không bắt buộc)</label>
          <input name="phone" class="form-control" value="{{ old('phone') }}"></div>
      </div>
      <div class="mt-3"><button class="btn btn-primary">Đăng ký</button></div>
    </form>
  </div></div>
</div></div>
@endsection
