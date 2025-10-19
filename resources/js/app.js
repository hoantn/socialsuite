import { createApp, h } from 'vue'
import { createInertiaApp, Link, Head } from '@inertiajs/vue3'

createInertiaApp({
  resolve: name => import(/* @vite-ignore */ `./Pages/${name}.vue`),
  setup({ el, App, props, plugin }) {
    const app = createApp({ render: () => h(App, props) })
    app.use(plugin).component('Link', Link).component('Head', Head)
    app.mount(el)
  },
})