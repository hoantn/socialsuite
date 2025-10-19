import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'
import vue from '@vitejs/plugin-vue'

export default defineConfig({
  plugins: [
    laravel({
      input: ['resources/js/app.js'],
      refresh: true,
    }),
    vue(),
  ],
  server: {
    host: 'mmo.homes',
    port: 5173,
    hmr: {
      host: 'mmo.homes',
      protocol: 'http',   // <-- http
      port: 5173,
    },
  },
})
