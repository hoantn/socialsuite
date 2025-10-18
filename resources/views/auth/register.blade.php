@extends('layouts.app')
@section('content')
<div class="mx-auto max-w-md px-4 pt-16 pb-24">
  <h1 class="text-2xl font-bold">Đăng ký</h1>
  <form method="POST" action="{{ route('register') }}" class="mt-6 space-y-4">
    @csrf

    <div>
      <label class="text-sm">Tên hiển thị (tuỳ chọn)</label>
      <input name="name" type="text" class="mt-1 w-full border rounded-xl p-3 focus:outline-none focus:ring"/>
    </div>

    <div>
      <label class="text-sm">Username <span class="text-red-600">*</span></label>
      <input name="username" type="text" required class="mt-1 w-full border rounded-xl p-3 focus:outline-none focus:ring"/>
      @error('username')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
    </div>

    <div>
      <label class="text-sm">Email (tuỳ chọn)</label>
      <input name="email" type="email" class="mt-1 w-full border rounded-xl p-3 focus:outline-none focus:ring"/>
      @error('email')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
    </div>

    <div>
      <label class="text-sm">Số điện thoại (tuỳ chọn)</label>
      <input name="phone" type="text" class="mt-1 w-full border rounded-xl p-3 focus:outline-none focus:ring" placeholder="+84901234567 hoặc 0901234567"/>
      @error('phone')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
    </div>

    <div>
      <label class="text-sm">Mật khẩu <span class="text-red-600">*</span></label>
      <input name="password" type="password" required class="mt-1 w-full border rounded-xl p-3 focus:outline-none focus:ring"/>
      @error('password')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
    </div>
    <div>
      <label class="text-sm">Xác nhận mật khẩu <span class="text-red-600">*</span></label>
      <input name="password_confirmation" type="password" required class="mt-1 w-full border rounded-xl p-3 focus:outline-none focus:ring"/>
    </div>

    <button class="w-full px-4 py-3 rounded-xl bg-brand text-white">Tạo tài khoản</button>
  </form>
  <div class="mt-4 text-sm">Đã có tài khoản? <a href="{{ route('login.form') }}" class="text-brand">Đăng nhập</a></div>
</div>
@endsection
