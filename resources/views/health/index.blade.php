@extends('layouts.app')
@section('content')
<div class="container">
  <h1>System Health</h1>
  <div class="mb-3">
    <strong>Jobs in queue:</strong> {{ $queueSize }} |
    <strong>Failed jobs:</strong> {{ $failed }}
  </div>
  <table class="table table-bordered">
    <thead><tr><th>Page</th><th>Page ID</th><th>Token Expires</th></tr></thead>
    <tbody>
      @foreach($pages as $p)
        <tr>
          <td>{{ $p->name }}</td>
          <td>{{ $p->page_id }}</td>
          <td>{{ $p->token_expires_at ?? 'N/A' }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>
</div>
@endsection
