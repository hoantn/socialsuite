@extends('shared/layout')
@section('content')
<div class=\"wrap\">
  <h1>Kết nối Facebook</h1>
  @if(session('error'))<div class=\"alert alert-err\">{{ session('error') }}</div>@endif
  <p>Dán <code>user access token</code> vào ô dưới (hoặc gắn vào OAuth callback để POST tới <code>/auth/facebook/bind</code>).</p>
  <form method=\"POST\" action=\"{{ route('fb.bind') }}\">@csrf
    <input name=\"access_token\" placeholder=\"EAAG...\" class=\"inp\" />
    <button class=\"btn\">Kết nối</button>
  </form>
</div>
@endsection
