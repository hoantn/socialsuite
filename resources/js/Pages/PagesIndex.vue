
<script setup>
import { ref, onMounted } from 'vue'

const loading = ref(false)
const pages = ref([])
const selected = ref(new Set())

const loadPages = async () => {
  loading.value = true
  const res = await fetch('/api/pages')
  pages.value = await res.json()
  loading.value = false
}

const toggle = (pid) => {
  if (selected.value.has(pid)) selected.value.delete(pid)
  else selected.value.add(pid)
}

const importAndSubscribe = async () => {
  loading.value = true
  const res = await fetch('/api/facebook/import-pages', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ select: Array.from(selected.value) })
  })
  const data = await res.json()
  await loadPages()
  alert(`Đã import ${data.count} page`)
  loading.value = false
}

onMounted(loadPages)
</script>

<template>
  <div class="space-y-4">
    <div class="flex items-center justify-between">
      <h1 class="text-2xl font-semibold">Pages</h1>
      <button class="px-3 py-1 rounded bg-blue-600 text-white"
              @click="importAndSubscribe" :disabled="loading">
        Nhập & Subscribe webhook
      </button>
    </div>

    <p v-if="loading">Đang tải…</p>

    <div v-else class="space-y-2">
      <div v-for="p in pages" :key="p.id"
           class="border rounded p-3 flex items-center justify-between">
        <label class="flex items-center gap-3">
          <input type="checkbox"
                 :checked="selected.has(p.provider_page_id)"
                 @change="toggle(p.provider_page_id)">
          <span class="font-medium">{{ p.name || 'Untitled' }}</span>
          <span class="text-gray-500">id: {{ p.provider_page_id }}</span>
        </label>
        <span v-if="p.subscribed" class="text-green-600">Đã subscribe ✓</span>
        <span v-else class="text-gray-400">Chưa subscribe</span>
      </div>
    </div>
  </div>
</template>
