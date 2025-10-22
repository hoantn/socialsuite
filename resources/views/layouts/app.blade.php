<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ $title ?? 'SocialSuite' }}</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    body { background:#0b1220; color:#dbeafe; }
    .card { background:#0f172a; border:1px solid #1f2a44; border-radius:14px; }
    .btn { background:#0ea5e9; color:white; padding:8px 14px; border-radius:10px; }
    .btn:hover { background:#0284c7; }
    .btn-danger { background:#ef4444; }
    .btn-danger:hover { background:#dc2626; }
    .chip { font-size:12px; padding:2px 8px; background:#172554; border-radius:999px; }
    input, select, textarea { background:#0b1430; border:1px solid #1f2a44; border-radius:10px; padding:10px; color:#e5e7eb; width:100%; }
    label { color:#93c5fd; font-weight:600; }
  </style>
</head>
<body class="antialiased">
  <div class="max-w-[1280px] mx-auto px-4 py-6">
    @yield('content')
  </div>
  @stack('scripts')
</body>
</html>
