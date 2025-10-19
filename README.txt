# Patch: fix `npm run build` "Could not resolve entry module 'index.html'."
- Place `vite.config.js` in the project root (same level as artisan).
- Ensure package.json scripts use `vite build` and not a custom builder.
- Then run: `npm ci && npm run build`.
