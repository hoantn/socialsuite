@extends('shared/layout')
@section('content')
<div class=\"wrap\">
  <div class=\"card\">
    <div class=\"row\" style=\"align-items:center\">
      <img src=\"{{ $account->avatar_url }}\" style=\"width:56px;height:56px;border-radius:50%\">
      <div>
        <div style=\"font-weight:700\">{{ $account->name }}</div>
        <div style=\"color:#64748b\">FB ID: {{ $account->fb_user_id }}</div>
      </div>
    </div>
    <div class=\"row\" style=\"margin-top:10px\">
      <form method=\"POST\" action=\"{{ route('me.sync_pages') }}\">@csrf
        <button class=\"btn\">Đồng bộ Page</button>
      </form>
      <a class=\"btn\" href=\"{{ route('pages') }}\">Quản lý Page ({{ $pagesCount }})</a>
    </div>
  </div>
</div>
@endsection
