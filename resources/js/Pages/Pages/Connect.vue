<script setup>
import Layout from '../_Layout.vue'
import { Head } from '@inertiajs/vue3'
const props = defineProps({ accounts: Array, debug: Object })
function toggle(p) { p._selected = !p._selected }
async function importPages() {
  const selected = props.accounts?.filter(a => a._selected) || []
  if (!selected.length) { alert('Hãy chọn ít nhất một Page.'); return }
  await fetch('/pages/import', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
    body: JSON.stringify({ pages: selected })
  }).then(() => location.href='/pages')
}
</script>

<template>
  <Layout>
    <Head title="Chọn Page để kết nối" />

    <div class="card">
      <h2 class="font-semibold mb-4">Bạn quản lý các Page sau — chọn Page để kết nối</h2>

      <template v-if="accounts && accounts.length">
        <ul class="space-y-2">
          <li v-for="p in accounts" :key="p.id" class="flex items-center justify-between p-3 border rounded-xl">
            <div>
              <div class="font-medium">{{ p.name }}</div>
              <div class="text-xs text-slate-500">id: {{ p.id }}</div>
            </div>
            <button class="btn" :class="p._selected ? 'bg-green-600 hover:bg-green-700' : ''" @click="toggle(p)">
              {{ p._selected ? 'Đã chọn' : 'Chọn' }}
            </button>
          </li>
        </ul>
        <div class="mt-4 flex items-center gap-3">
          <button class="btn" @click="importPages">Nhập & Subscribe webhook</button>
          <span class="text-sm text-slate-600">Đã chọn: {{ accounts.filter(a=>a._selected).length }}</span>
        </div>
      </template>

      <template v-else>
        <div class="text-sm text-slate-600">
          Không nhận được Page nào từ Facebook.
          <div v-if="debug">
            <div class="mt-2">Scopes hiện có: <code>{{ debug.scopes }}</code></div>
            <div class="mt-2" v-if="debug.note">{{ debug.note }}</div>
          </div>
        </div>
      </template>
    </div>
  </Layout>
</template>
