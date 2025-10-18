@extends('layouts.app')
@section('title','Welcome')
@section('content')
<div class="p-5 mb-4 bg-white border rounded-3">
  <h1 class="display-6">Creator Suite</h1>
  <p class="lead">Nền tảng quản lý nội dung Facebook cho nhà sáng tạo. Đăng nhập để bắt đầu.</p>
  @guest
  <a class="btn btn-primary" href="{{ route('login') }}">Đăng nhập</a>
  <a class="btn btn-outline-primary" href="{{ route('register') }}">Đăng ký</a>
  @endguest
</div>
@endsection
