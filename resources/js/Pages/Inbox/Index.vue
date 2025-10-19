<script setup>
defineProps({ messages: { type: Array, default: () => ([]) } })
</script>

<template>
  <div class="p-6">
    <h1 class="text-xl font-bold mb-4">Inbox</h1>
    <div v-for="m in messages" :key="m.time" class="mb-2">
      <strong>{{ m.from }}:</strong> {{ m.text }}
      <span class="text-xs text-gray-500"> ({{ m.time }})</span>
    </div>
  </div>
</template>