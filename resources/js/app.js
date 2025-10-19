import { createApp } from 'vue'
import router from './router'
import Layout from './layout/AppLayout.vue'
import './app.css'

createApp(Layout).use(router).mount('#app')
