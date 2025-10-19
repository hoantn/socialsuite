<script setup>
import Layout from '../_Layout.vue'
import { Head, useForm } from '@inertiajs/vue3'
const props = defineProps({ pages: Array })
const form = useForm({ name:'', page_id:'', access_token:'' })
function submit(){ form.post('/pages') }
</script>
<template>
  <Layout>
    <Head title="Pages · SocialSuite" />
    <div class="flex justify-between items-center mb-4">
      <h2 class="text-lg font-semibold">Pages</h2>
      <a class="btn" href="/auth/facebook/redirect">Kết nối Facebook</a>
    </div>
    <div class="grid md:grid-cols-2 gap-6">
      <div class="card">
        <h3 class="font-semibold mb-3">Kết nối thủ công (demo)</h3>
        <form @submit.prevent="submit" class="space-y-3">
          <input v-model="form.name" placeholder="Tên page" class="w-full border rounded-xl px-3 py-2" />
          <input v-model="form.page_id" placeholder="Page ID" class="w-full border rounded-xl px-3 py-2" />
          <input v-model="form.access_token" placeholder="Page Access Token (tuỳ chọn)" class="w-full border rounded-xl px-3 py-2" />
          <button class="btn">Thêm Page</button>
        </form>
      </div>
      <div class="card">
        <h3 class="font-semibold mb-3">Danh sách Pages</h3>
        <ul class="space-y-2">
          <li v-for="p in props.pages" :key="p.id" class="flex items-center justify-between">
            <div class="flex items-center gap-3">
              <span class="badge">{{ p.channel }}</span>
              <span class="font-medium">{{ p.name }}</span>
              <span class="text-xs" :class="p.subscribed ? 'text-green-600' : 'text-slate-400'">
                {{ p.subscribed ? 'Subscribed' : 'Not subscribed' }}
              </span>
              <span class="text-xs text-slate-500" v-if="p.token_expires_at"> · expires: {{ new Date(p.token_expires_at).toLocaleString() }}</span>
            </div>
            <div class="flex items-center gap-3">
              <form :action="`/pages/${p.id}/refresh-token`" method="post"><button class="text-blue-600 hover:underline">Refresh token</button></form>
              <form v-if="!p.subscribed" :action="`/pages/${p.id}/subscribe`" method="post"><button class="text-green-600 hover:underline">Subscribe</button></form>
              <form v-else :action="`/pages/${p.id}/unsubscribe`" method="post"><button class="text-orange-600 hover:underline">Unsubscribe</button></form>
              <form :action="`/pages/${p.id}`" method="post">
                <input type="hidden" name="_method" value="DELETE"><button class="text-red-600 hover:underline">Remove</button>
              </form>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </Layout>
</template>