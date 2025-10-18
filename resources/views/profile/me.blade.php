@extends('layouts.app')
@section('content')
<div class="mx-auto max-w-2xl px-4 pt-10 pb-20">
  <h1 class="text-2xl font-bold">Tài khoản của bạn</h1>
  @if(session('ok'))<div class="mt-4 p-3 rounded bg-green-50 text-green-700">{{ session('ok') }}</div>@endif
  <form method="POST" action="{{ route('me.update') }}" class="mt-6 space-y-4">
    @csrf
    <div>
      <label class="text-sm">Tên hiển thị</label>
      <input name="name" type="text" value="{{ old('name',$u->name) }}" class="mt-1 w-full border rounded-xl p-3">
    </div>
    <div>
      <label class="text-sm">Email</label>
      <input name="email" type="email" value="{{ old('email',$u->email) }}" class="mt-1 w-full border rounded-xl p-3">
    </div>
    <div>
      <label class="text-sm">Số điện thoại</label>
      <input name="phone" type="text" value="{{ old('phone',$u->phone) }}" class="mt-1 w-full border rounded-xl p-3">
    </div>
    <div class="grid md:grid-cols-2 gap-4">
      <div>
        <label class="text-sm">Mật khẩu mới (tuỳ chọn)</label>
        <input name="password" type="password" class="mt-1 w-full border rounded-xl p-3">
      </div>
      <div>
        <label class="text-sm">Xác nhận mật khẩu mới</label>
        <input name="password_confirmation" type="password" class="mt-1 w-full border rounded-xl p-3">
      </div>
    </div>
    <div class="flex gap-3">
      <button class="px-4 py-2 rounded-xl bg-brand text-white">Lưu thay đổi</button>
      <a href="{{ route('pages') }}" class="px-4 py-2 rounded-xl border">Quản lý Page</a>
    </div>
  </form>
</div>
@endsection
