<template>
  <div>
    <Head><meta name="theme-color" content="#2563eb" /></Head>
    <header class="bg-white border-b">
      <div class="max-w-6xl mx-auto px-4 py-4 flex items-center justify-between">
        <h1 class="font-bold text-lg">SocialSuite</h1>
        <nav class="flex gap-4 text-sm">
          <Link class="link" href="/">Dashboard</Link>
          <Link class="link" href="/pages">Pages</Link>
          <Link class="link" href="/inbox">Inbox</Link>
          <Link class="link" href="/flows">Flows</Link>
          <Link class="link" href="/broadcasts">Broadcasts</Link>
          <Link class="link" href="/settings">Settings</Link>
        </nav>
      </div>
    </header>
    <main class="max-w-6xl mx-auto p-4"><slot /></main>
    <footer class="max-w-6xl mx-auto px-4 py-8 text-sm text-slate-500">© {{ new Date().getFullYear() }} SocialSuite</footer>
  </div>
</template>
<script setup>
import { Head, Link } from '@inertiajs/vue3'
</script>