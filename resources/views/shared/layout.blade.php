<!doctype html>
<html lang=\"vi\"><head><meta charset=\"utf-8\"><meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">
<title>SocialSuite</title>
<style>
 body{font-family:Inter,system-ui,-apple-system,Segoe UI,Roboto,sans-serif;margin:0;background:#f8fafc;color:#0f172a}
 header{position:sticky;top:0;background:white;border-bottom:1px solid #e2e8f0}
 .nav{max-width:1100px;margin:0 auto;padding:12px 16px;display:flex;gap:16px;align-items:center;justify-content:space-between}
 .brand{font-weight:700}.btn{background:#0a66ff;color:#fff;border:0;padding:8px 14px;border-radius:10px;cursor:pointer}
 .wrap{max-width:1000px;margin:24px auto;padding:0 16px}
 .grid{display:grid;gap:16px}.alert{padding:10px 12px;border-radius:10px;margin:10px 0}
 .alert-ok{background:#ecfdf5;color:#065f46}.alert-err{background:#fef2f2;color:#991b1b}
 .inp, select, textarea{width:100%;padding:10px 12px;border:1px solid #cbd5e1;border-radius:10px}
 .card{border:1px solid #e2e8f0;border-radius:16px;padding:16px;background:#fff}
 .row{display:flex;gap:10px;flex-wrap:wrap}
</style></head>
<body>
<header><div class=\"nav\">
 <div class=\"brand\">SocialSuite</div>
 <div class=\"row\">
  <a href=\"{{ route('pages') }}\">Trang</a>
  <a href=\"{{ route('me') }}\">Tài khoản</a>
  <a href=\"{{ route('logout') }}\">Đăng xuất</a>
 </div>
</div></header>
@yield('content')
</body></html>
