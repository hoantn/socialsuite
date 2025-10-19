// vite.config.js
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
    host: '127.0.0.1',     // hoặc '0.0.0.0' nếu cần truy cập từ LAN
    port: 5173,
    strictPort: true,
    hmr: {
      host: 'mmo.homes',   // TÊN MIỀN bạn đang mở trên trình duyệt
      protocol: 'https',   // vì site của bạn đang chạy HTTPS
      port: 5173,
    },
  },
})
