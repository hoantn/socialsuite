import { createApp, h } from 'vue'
import { createInertiaApp, Link, Head } from '@inertiajs/vue3'

window.csrf = () => document.querySelector('meta[name="csrf-token"]').getAttribute('content')
window.postJSON = async (url, data) => {
  const res = await fetch(url, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-Requested-With': 'XMLHttpRequest',
      'X-CSRF-TOKEN': window.csrf(),
    },
    body: JSON.stringify(data ?? {}),
    credentials: 'same-origin',
  })
  if (res.redirected) { window.location.href = res.url; return }
  return res
}

createInertiaApp({
  resolve: name => import(/* @vite-ignore */ `./Pages/${name}.vue`),
  setup({ el, App, props, plugin }) {
    const app = createApp({ render: () => h(App, props) })
    app.use(plugin).component('Link', Link).component('Head', Head)
    app.mount(el)
  },
})