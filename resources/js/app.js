import { createApp } from 'vue'
import { createRouter, createWebHistory } from 'vue-router'
import AppLayout from './layout/AppLayout.vue'
import Dashboard from './pages/Dashboard.vue'
import PagesIndex from './pages/PagesIndex.vue'
import Inbox from './pages/Inbox.vue'
import Flows from './pages/Flows.vue'
import Broadcasts from './pages/Broadcasts.vue'
import Settings from './pages/Settings.vue'
import './app.css'

const routes = [
  { path: '/', name: 'dashboard', component: Dashboard },
  { path: '/pages', name: 'pages', component: PagesIndex },
  { path: '/inbox', name: 'inbox', component: Inbox },
  { path: '/flows', name: 'flows', component: Flows },
  { path: '/broadcasts', name: 'broadcasts', component: Broadcasts },
  { path: '/settings', name: 'settings', component: Settings },
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

createApp(AppLayout).use(router).mount('#app')
