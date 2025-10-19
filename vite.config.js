import { defineConfig } from 'vite';
import vue from '@vitejs/plugin-vue';
import path from 'path';

export default defineConfig({
  plugins: [vue()],
  resolve: {
    alias: {
      '@': path.resolve(__dirname, 'resources/js'),
    },
  },
  server: {
    host: true,
    https: false,
    cors: true,
    hmr: {
      protocol: 'ws',
      host: process.env.VITE_HMR_HOST || 'localhost',
      clientPort: Number(process.env.VITE_HMR_CLIENT_PORT || 5173),
      port: Number(process.env.VITE_HMR_PORT || 5173),
    },
  },
});
