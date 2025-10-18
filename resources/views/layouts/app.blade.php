<!doctype html>
<html lang="vi"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>@yield('title','Creator Suite')</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
</head><body class="bg-light">
<nav class="navbar navbar-expand-lg bg-white border-bottom sticky-top">
  <div class="container">
    <a class="navbar-brand fw-bold" href="{{ url('/') }}">CreatorSuite</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav"><span class="navbar-toggler-icon"></span></button>
    <div class="collapse navbar-collapse" id="nav">
      <ul class="navbar-nav me-auto">
        @auth
        <li class="nav-item"><a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('fb.pages') }}">Facebook</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('posts.index') }}">Bài viết</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('billing.topup') }}">Nạp tiền</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('plans') }}">Gói dịch vụ</a></li>
        @endauth
      </ul>
      <ul class="navbar-nav">
        @auth
          <li class="nav-item"><span class="nav-link">Hi, {{ auth()->user()->username }}</span></li>
          <li class="nav-item"><a class="nav-link" href="{{ route('logout') }}">Đăng xuất</a></li>
        @else
          <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Đăng nhập</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">Đăng ký</a></li>
        @endauth
      </ul>
    </div>
  </div>
</nav>
<main class="py-4"><div class="container">
  @if(session('ok'))<div class="alert alert-success">{{ session('ok') }}</div>@endif
  @if($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
  @yield('content')
</div></main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('assets/js/app.js') }}"></script>
</body></html>
