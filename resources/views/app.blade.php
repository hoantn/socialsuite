<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title inertia>SocialSuite · Facebook Messaging SaaS</title>
  <meta name="description" content="SocialSuite - chatbot Facebook, inbox hợp nhất, broadcast và automation.">
  <meta property="og:title" content="SocialSuite · Facebook Messaging SaaS" />
  <meta property="og:description" content="Xây bot, quản lý hội thoại và gửi chiến dịch tin nhắn dễ dàng." />
  <meta property="og:type" content="website" />
  <meta property="og:url" content="{{ config('app.url') }}" />
  <link rel="icon" href="/favicon.ico">
  @vite(['resources/css/app.css','resources/js/app.js'])
  @inertiaHead
</head>
<body class="bg-gray-50 text-slate-800 antialiased">
  @inertia
</body>
</html>