<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>SocialSuite</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light mb-3">
  <div class="container">
    <a class="navbar-brand" href="/">SocialSuite</a>
    <div class="d-flex gap-2">
      <a class="btn btn-outline-secondary btn-sm" href="/pages">Pages</a>
      <form action="/logout" method="post">@csrf<button class="btn btn-outline-danger btn-sm">Logout</button></form>
    </div>
  </div>
</nav>
<main>@yield('content')</main>
</body>
</html>
