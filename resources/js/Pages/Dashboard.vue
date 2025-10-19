<template>
  <Layout>
    <Head title="Dashboard · SocialSuite" />
    <div class="grid md:grid-cols-3 gap-4">
      <div class="card"><h2 class="font-semibold">Hội thoại hôm nay</h2><div class="text-3xl mt-2">12</div></div>
      <div class="card"><h2 class="font-semibold">Tin nhắn gửi</h2><div class="text-3xl mt-2">48</div></div>
      <div class="card"><h2 class="font-semibold">Subscribers</h2><div class="text-3xl mt-2">1,204</div></div>
    </div>
  </Layout>
</template>
<script setup>
import Layout from './_Layout.vue'
import { Head } from '@inertiajs/vue3'
</script>