<script setup>
import Layout from '../_Layout.vue'
import { Head, useForm } from '@inertiajs/vue3'
const props = defineProps({ items: Array })
const form = useForm({ page_id:1, name:'Chiến dịch mới', content:'Xin chào!' })
function submit(){ form.post('/broadcasts') }
</script>
<template>
  <Layout>
    <Head title="Broadcasts · SocialSuite" />
    <div class="grid md:grid-cols-2 gap-6">
      <div class="card">
        <h2 class="font-semibold mb-3">Tạo broadcast (demo)</h2>
        <form @submit.prevent="submit" class="space-y-3">
          <input v-model="form.page_id" class="w-full border rounded-xl px-3 py-2" placeholder="Page ID (demo=1)"/>
          <input v-model="form.name" class="w-full border rounded-xl px-3 py-2" placeholder="Tên chiến dịch"/>
          <textarea v-model="form.content" class="w-full border rounded-xl px-3 py-2" placeholder="Nội dung"></textarea>
          <button class="btn">Lưu</button>
        </form>
      </div>
      <div class="card">
        <h2 class="font-semibold mb-3">Lịch sử</h2>
        <ul class="space-y-2">
          <li v-for="i in props.items" :key="i.id" class="flex items-center justify-between">
            <div><span class="font-medium">{{ i.name }}</span> — {{ i.status }}</div>
            <span class="badge">{{ i.page_id }}</span>
          </li>
        </ul>
      </div>
    </div>
  </Layout>
</template>