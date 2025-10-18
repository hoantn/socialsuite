@extends('layouts.app')
@section('content')
<div class="mx-auto max-w-md px-4 pt-16 pb-24">
  <h1 class="text-2xl font-bold">Đăng nhập</h1>
  <form method="POST" action="{{ route('login') }}" class="mt-6 space-y-4">
    @csrf
    <div>
      <label class="text-sm">Username / Email / Số điện thoại</label>
      <input name="login" type="text" required class="mt-1 w-full border rounded-xl p-3 focus:outline-none focus:ring"/>
      @error('login')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
    </div>
    <div>
      <label class="text-sm">Mật khẩu</label>
      <input name="password" type="password" required class="mt-1 w-full border rounded-xl p-3 focus:outline-none focus:ring"/>
      @error('password')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
    </div>
    <button class="w-full px-4 py-3 rounded-xl bg-brand text-white">Đăng nhập</button>
  </form>
  <div class="mt-4 text-sm">Chưa có tài khoản? <a href="{{ route('register.form') }}" class="text-brand">Đăng ký</a></div>
</div>
@endsection
