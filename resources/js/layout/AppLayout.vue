<template>
  <div>
    <nav class="nav">
      <RouterLink to="/">Dashboard</RouterLink>
      <RouterLink to="/pages">Pages</RouterLink>
      <RouterLink to="/inbox">Inbox</RouterLink>
      <RouterLink to="/flows">Flows</RouterLink>
      <RouterLink to="/broadcasts">Broadcasts</RouterLink>
      <RouterLink to="/settings">Settings</RouterLink>
    </nav>
    <main class="main"><RouterView/></main>
  </div>
</template>
<style scoped>
.nav{display:flex;gap:14px;padding:10px;border-bottom:1px solid #eee}
.main{padding:18px;font:14px/1.5 system-ui, -apple-system, Segoe UI, Roboto}
a{color:#2563eb;text-decoration:none}
a.router-link-active{font-weight:700}
</style>
