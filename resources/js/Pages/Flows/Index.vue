<script setup>
import Layout from '../_Layout.vue'
import { Head, useForm } from '@inertiajs/vue3'
const props = defineProps({ flows: Array })
const form = useForm({ page_id:1, name:'Welcome Flow' })
function submit(){ form.post('/flows') }
</script>
<template>
  <Layout>
    <Head title="Flows · SocialSuite" />
    <div class="grid md:grid-cols-2 gap-6">
      <div class="card">
        <h2 class="font-semibold mb-3">Tạo flow (demo)</h2>
        <form @submit.prevent="submit" class="space-y-3">
          <input v-model="form.page_id" class="w-full border rounded-xl px-3 py-2" placeholder="Page ID (demo=1)"/>
          <input v-model="form.name" class="w-full border rounded-xl px-3 py-2" placeholder="Tên flow"/>
          <button class="btn">Tạo</button>
        </form>
      </div>
      <div class="card">
        <h2 class="font-semibold mb-3">Danh sách flow</h2>
        <ul class="space-y-2"><li v-for="f in props.flows" :key="f.id"><div class="font-medium">{{ f.name }}</div></li></ul>
      </div>
    </div>
  </Layout>
</template>