<script setup>
defineProps({ pages: { type: Array, default: () => ([]) } })
</script>

<template>
  <div class="p-6">
    <h1 class="text-xl font-bold mb-4">Connected Pages</h1>
    <ul class="list-disc pl-4">
      <li v-for="p in pages" :key="p.id">{{ p.name }}</li>
    </ul>
  </div>
</template>