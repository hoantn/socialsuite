import { createApp } from 'vue';
import router from './router';
import AppLayout from './layout/AppLayout.vue';
import './app.css';

createApp(AppLayout).use(router).mount('#app');
