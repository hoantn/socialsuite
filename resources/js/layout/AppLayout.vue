<template>
  <div>
    <nav class="nav">
      <strong>SocialSuite</strong>
      <RouterLink to="/">Dashboard</RouterLink>
      <RouterLink to="/pages">Pages</RouterLink>
      <RouterLink to="/inbox">Inbox</RouterLink>
      <RouterLink to="/flows">Flows</RouterLink>
      <RouterLink to="/broadcasts">Broadcasts</RouterLink>
      <RouterLink to="/settings">Settings</RouterLink>
      <span style="margin-left:auto" class="badge">SPA • Vue 3 + Vite</span>
    </nav>
    <main class="container">
      <RouterView />
    </main>
  </div>
</template>

<script setup>
// SPA layout shell
</script>
