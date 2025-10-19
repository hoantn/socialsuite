<template>
  <div>
    <nav>
      <div class="container navbar">
        <div class="brand">SocialSuite</div>
        <router-link to="/"        class="link" active-class="active" exact>Dashboard</router-link>
        <router-link to="/pages"   class="link" active-class="active">Pages</router-link>
        <router-link to="/inbox"   class="link" active-class="active">Inbox</router-link>
        <router-link to="/flows"   class="link" active-class="active">Flows</router-link>
        <router-link to="/broadcasts" class="link" active-class="active">Broadcasts</router-link>
        <router-link to="/settings" class="link" active-class="active">Settings</router-link>
        <div style="margin-left:auto;font-size:12px;color:#6b7280">SPA • Vue 3 + Vite</div>
      </div>
    </nav>
    <main class="container page">
      <router-view />
    </main>
  </div>
</template>

<script setup>
// Layout-only component
</script>
