<template>
  <div>
    <div style="display:flex;align-items:center;gap:12px;margin-bottom:16px">
      <button class="btn" :disabled="busy" @click="importAndSubscribe">
        Nhập & Subscribe webhook
      </button>
      <span v-if="selectedIds.size" class="mono">Đã chọn: {{ selectedIds.size }}</span>
    </div>

    <div v-if="loading" class="mono">Đang tải…</div>
    <div v-else>
      <div v-if="pages.length === 0" class="mono">Không có Page nào</div>
      <div class="list" v-else>
        <div class="item" v-for="p in pages" :key="p.id">
          <div>
            <div><strong>{{ p.name }}</strong></div>
            <div class="mono">id: {{ p.id }}</div>
          </div>
          <div>
            <button class="btn" @click="toggle(p.id)">
              {{ selectedIds.has(p.id) ? 'Đã chọn' : 'Chọn' }}
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';

const loading = ref(true);
const pages = ref([]);
const selectedIds = ref(new Set());
const busy = ref(false);

onMounted(async () => {
  try {
    const res = await fetch('/api/facebook/pages', { credentials: 'same-origin' });
    const data = await res.json();
    pages.value = data?.data ?? [];
  } catch (e) {
    console.error(e);
  } finally {
    loading.value = false;
  }
});

function toggle(id) {
  if (selectedIds.value.has(id)) selectedIds.value.delete(id);
  else selectedIds.value.add(id);
  selectedIds.value = new Set(selectedIds.value); // force update
}

async function importAndSubscribe() {
  busy.value = true;
  try {
    for (const id of selectedIds.value) {
      await fetch('/api/facebook/subscribe', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ page_id: id })
      });
    }
    alert('Đã xử lý xong (DEV giả lập).');
  } catch (e) {
    alert('Lỗi: ' + (e?.message || e));
  } finally {
    busy.value = false;
  }
}
</script>
