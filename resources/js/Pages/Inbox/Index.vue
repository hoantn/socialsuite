<script setup>
import Layout from '../_Layout.vue'
import { Head } from '@inertiajs/vue3'
const props = defineProps({ conversations: Array })
</script>
<template>
  <Layout>
    <Head title="Inbox · SocialSuite" />
    <div class="grid md:grid-cols-3 gap-4">
      <div class="card md:col-span-1">
        <h2 class="font-semibold mb-3">Conversations</h2>
        <ul class="space-y-2"><li v-for="c in props.conversations" :key="c.id" class="flex items-center justify-between">
          <div class="font-medium">Subscriber A</div><span class="badge">{{ c.status }}</span></li></ul>
      </div>
      <div class="card md:col-span-2">
        <h2 class="font-semibold mb-3">Messages</h2>
        <div>Chọn hội thoại để xem chi tiết (demo hiển thị tĩnh).</div>
      </div>
    </div>
  </Layout>
</template>