import { createRouter, createWebHistory } from 'vue-router';

const Dashboard = () => import('../pages/Dashboard.vue');
const PagesIndex = () => import('../pages/PagesIndex.vue');
const Inbox = () => import('../pages/Inbox.vue');
const Flows = () => import('../pages/Flows.vue');
const Broadcasts = () => import('../pages/Broadcasts.vue');
const Settings = () => import('../pages/Settings.vue');

export default createRouter({
  history: createWebHistory(),
  routes: [
    { path: '/', name:'dashboard', component: Dashboard },
    { path: '/pages', name:'pages', component: PagesIndex },
    { path: '/inbox', name:'inbox', component: Inbox },
    { path: '/flows', name:'flows', component: Flows },
    { path: '/broadcasts', name:'broadcasts', component: Broadcasts },
    { path: '/settings', name:'settings', component: Settings },
  ],
  scrollBehavior() { return { top: 0 } }
});
