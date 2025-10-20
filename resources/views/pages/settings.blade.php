@extends('layouts.app')
@section('content')
<div class="container">
  <h1>Cấu hình Page: {{ $page->name ?? $page->page_id }}</h1>
  @if(session('ok')) <div class="alert alert-success">{{ session('ok') }}</div> @endif
  <form action="{{ route('pages.settings.update',$page->page_id) }}" method="post">@csrf
    <div class="mb-3">
      <label class="form-label">Posting defaults (JSON)</label>
      <textarea class="form-control" name="posting_defaults" rows="3">{{ json_encode($cfg->settings['posting_defaults'] ?? [], JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE) }}</textarea>
    </div>
    <div class="mb-3">
      <label class="form-label">Schedule (JSON)</label>
      <textarea class="form-control" name="schedule" rows="3">{{ json_encode($cfg->settings['schedule'] ?? [], JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE) }}</textarea>
    </div>
    <div class="mb-3">
      <label class="form-label">Auto reply (JSON)</label>
      <textarea class="form-control" name="auto_reply" rows="3">{{ json_encode($cfg->settings['auto_reply'] ?? [], JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE) }}</textarea>
    </div>
    <button class="btn btn-primary">Lưu</button>
  </form>
</div>
@endsection
