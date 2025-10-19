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
    <div class="grid md:grid-cols-2 gap-6">
      <div class="card">
        <h2 class="font-semibold mb-3">Kết nối Fanpage (demo)</h2>
        <form @submit.prevent="submit" class="space-y-3">
          <input v-model="form.name" placeholder="Tên page" class="w-full border rounded-xl px-3 py-2" />
          <input v-model="form.page_id" placeholder="Page ID" class="w-full border rounded-xl px-3 py-2" />
          <input v-model="form.access_token" placeholder="Page Access Token (tuỳ chọn)" class="w-full border rounded-xl px-3 py-2" />
          <button class="btn">Thêm Page</button>
        </form>
      </div>
      <div class="card">
        <h2 class="font-semibold mb-3">Danh sách Pages</h2>
        <ul class="space-y-2">
          <li v-for="p in props.pages" :key="p.id" class="flex items-center justify-between">
            <div class="flex items-center gap-3"><span class="badge">{{ p.channel }}</span><span class="font-medium">{{ p.name }}</span></div>
            <form :action="`/pages/${p.id}`" method="post">
              <input type="hidden" name="_method" value="DELETE"><button class="text-red-600 hover:underline">Remove</button>
            </form>
          </li>
        </ul>
      </div>
    </div>
  </Layout>
</template>