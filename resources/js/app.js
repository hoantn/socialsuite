import { createApp, h } from 'vue'
import { createInertiaApp, Link, Head } from '@inertiajs/vue3'
createInertiaApp({
  resolve: name => import(/* @vite-ignore */ `./Pages/${name}.vue`),
  setup({ el, App, props, plugin }) {
    createApp({ render: () => h(App, props) })
      .use(plugin).component('Link', Link).component('Head', Head).mount(el)
  },
})