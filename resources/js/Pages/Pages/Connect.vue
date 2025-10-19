<script setup>
import Layout from '../_Layout.vue'
import { Head } from '@inertiajs/vue3'
const props = defineProps({ accounts: Array })
function toggle(p) { p._selected = !p._selected }
async function importPages() {
  const selected = props.accounts.filter(a => a._selected)
  const res = await fetch('/pages/import', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
    body: JSON.stringify({ pages: selected })
  })
  location.href = '/pages'
}
</script>
<template>
  <Layout>
    <Head title="Chọn Page để kết nối" />
    <div class="card">
      <h2 class="font-semibold mb-4">Bạn quản lý các Page sau — chọn Page để kết nối</h2>
      <ul class="space-y-2">
        <li v-for="p in props.accounts" :key="p.id" class="flex items-center justify-between p-3 border rounded-xl">
          <div>
            <div class="font-medium">{{ p.name }}</div>
            <div class="text-xs text-slate-500">id: {{ p.id }}</div>
          </div>
          <button class="btn" :class="p._selected ? 'bg-green-600 hover:bg-green-700' : ''" @click="toggle(p)">
            {{ p._selected ? 'Đã chọn' : 'Chọn' }}
          </button>
        </li>
      </ul>
      <div class="mt-4">
        <button class="btn" @click="importPages">Nhập & Subscribe webhook</button>
      </div>
    </div>
  </Layout>
</template>