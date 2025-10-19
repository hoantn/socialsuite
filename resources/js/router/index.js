import { createRouter, createWebHistory } from 'vue-router'

const Dashboard  = () => import('../views/Dashboard.vue')
const Pages      = () => import('../views/Pages.vue')
const Inbox      = () => import('../views/Inbox.vue')
const Flows      = () => import('../views/Flows.vue')
const Broadcasts = () => import('../views/Broadcasts.vue')
const Settings   = () => import('../views/Settings.vue')
const NotFound   = () => import('../views/NotFound.vue')

export default createRouter({
  history: createWebHistory(),
  routes: [
    { path: '/', component: Dashboard },
    { path: '/pages', component: Pages },
    { path: '/inbox', component: Inbox },
    { path: '/flows', component: Flows },
    { path: '/broadcasts', component: Broadcasts },
    { path: '/settings', component: Settings },
    { path: '/:pathMatch(.*)*', component: NotFound },
  ]
})
