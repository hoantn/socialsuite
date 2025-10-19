<script setup>
import Layout from '../_Layout.vue'
import { Head } from '@inertiajs/vue3'
</script>
<template>
  <Layout>
    <Head title="Settings · SocialSuite" />
    <div class="card space-y-2">
      <h2 class="font-semibold">Cấu hình</h2>
      <p>Điền <code>META_PAGE_ACCESS_TOKEN</code> vào .env để bật gửi tin nhắn qua <code>/api/facebook/send</code>.</p>
      <p>Webhook: <code>https://mmo.homes/webhook/facebook</code> — verify token: <code>META_VERIFY_TOKEN</code>.</p>
    </div>
  </Layout>
</template>