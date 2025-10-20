@extends('layouts.app')
@section('content')
<div class="container py-5">
  <div class="text-center">
    <h1>Welcome to SocialSuite</h1>
    <p class="text-muted">Kết nối Facebook và quản lý theo Page.</p>
    <a class="btn btn-primary" href="{{ route('fb.redirect') }}">Kết nối Facebook</a>
  </div>
</div>
@endsection
