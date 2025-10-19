import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'
import vue from '@vitejs/plugin-vue'
import fs from 'fs'

// >>> Update these paths to your Apache SSL cert & key if different
const SSL_KEY  = 'D:/xampp/apache/conf/ssl.key/server.key'
const SSL_CERT = 'D:/xampp/apache/conf/ssl.crt/server.crt'

export default defineConfig({
  plugins: [
    laravel({
      input: ['resources/css/app.css','resources/js/app.js'],
      refresh: true,
    }),
    vue(),
  ],
  server: {
    https: {
      key: fs.readFileSync(SSL_KEY),
      cert: fs.readFileSync(SSL_CERT),
    },
    host: 'mmo.homes',
    port: 5173,
    hmr: {
      host: 'mmo.homes',
      protocol: 'wss',
      port: 5173,
    },
  },
})