<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title>{{ $meta['title'] ?? 'SocialSuite' }}</title>
  <meta name="description" content="{{ $meta['description'] ?? '' }}">
  <meta name="keywords" content="{{ $meta['keywords'] ?? '' }}">
  <link rel="canonical" href="{{ $meta['url'] ?? url()->current() }}"/>

  <meta property="og:type" content="website">
  <meta property="og:title" content="{{ $meta['title'] ?? '' }}">
  <meta property="og:description" content="{{ $meta['description'] ?? '' }}">
  <meta property="og:url" content="{{ $meta['url'] ?? '' }}">
  <meta property="og:image" content="{{ $meta['image'] ?? '' }}">
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="{{ $meta['title'] ?? '' }}">
  <meta name="twitter:description" content="{{ $meta['description'] ?? '' }}">
  <meta name="twitter:image" content="{{ $meta['image'] ?? '' }}">

  @verbatim
  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "Organization",
    "name": "{{ meta.brand ?? 'SocialSuite' }}",
    "url": "{{ meta.url ?? '' }}",
    "logo": "{{ meta.image ?? '' }}"
  }
  </script>
  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "WebSite",
    "url": "{{ meta.url ?? '' }}",
    "name": "{{ meta.title ?? 'SocialSuite' }}",
    "potentialAction": { "@type": "SearchAction", "target": "{{ url('/') }}?q={search_term_string}", "query-input": "required name=search_term_string" }
  }
  </script>
  @endverbatim

  <script src="https://cdn.tailwindcss.com"></script>
  <script>tailwind.config = { theme:{ extend:{ colors:{ brand:'#2563eb'} } } };</script>
</head>
<body class="text-slate-800 antialiased">
  <header class="sticky top-0 z-50 bg-white/80 backdrop-blur border-b">
    <div class="mx-auto max-w-7xl px-4 py-3 flex items-center justify-between">
      <a href="{{ url('/') }}" class="font-bold text-xl">SocialSuite</a>
      <nav class="hidden md:flex items-center gap-6">
        <a href="{{ url('/#features') }}" class="hover:text-brand">Tính năng</a>
        <a href="{{ url('/#pricing') }}" class="hover:text-brand">Bảng giá</a>
        <a href="{{ url('/#faqs') }}" class="hover:text-brand">Hỏi đáp</a>
        <a href="{{ route('fb.login') }}" class="px-4 py-2 rounded-xl bg-brand text-white hover:opacity-90">Kết nối Facebook</a>
        @auth
          <form method="POST" action="{{ route('logout') }}" class="ml-2">
            @csrf
            <button class="px-3 py-2 rounded-xl border hover:border-brand hover:text-brand">Đăng xuất</button>
          </form>
        @endauth
        @guest
          <a href="{{ route('login.form') }}" class="px-3 py-2 rounded-xl border hover:border-brand hover:text-brand">Đăng nhập</a>
        @endguest
      </nav>
    </div>
  </header>

  <main>@yield('content')</main>

  <footer class="mt-24 border-t">
    <div class="mx-auto max-w-7xl px-4 py-10 grid md:grid-cols-3 gap-6">
      <div>
        <div class="font-bold text-lg">SocialSuite</div>
        <p class="text-sm mt-2 text-slate-600">Nền tảng quản lý & tự động hoá Facebook cho doanh nghiệp.</p>
      </div>
      <div>
        <div class="font-semibold mb-2">Điều hướng</div>
        <ul class="space-y-1 text-sm">
          <li><a href="{{ url('/#features') }}" class="hover:text-brand">Tính năng</a></li>
          <li><a href="{{ url('/#pricing') }}" class="hover:text-brand">Bảng giá</a></li>
          <li><a href="{{ route('sitemap') }}" class="hover:text-brand">Sitemap</a></li>
        </ul>
      </div>
      <div id="contact">
        <div class="font-semibold mb-2">Liên hệ</div>
        <p class="text-sm text-slate-600">Email: hello@socialsuite.local</p>
      </div>
    </div>
    <div class="text-center text-xs text-slate-500 pb-8">© {{ date('Y') }} SocialSuite. All rights reserved.</div>
  </footer>
</body>
</html>
