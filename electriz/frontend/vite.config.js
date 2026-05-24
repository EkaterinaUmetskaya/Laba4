import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import { VitePWA } from 'vite-plugin-pwa'

export default defineConfig({
  plugins: [
    vue(),
    VitePWA({
      registerType: 'autoUpdate',
      includeAssets: ['favicon.ico', 'icons/*.png'],
      manifest: {
        name: 'ElectrizShop — электроника',
        short_name: 'ElectrizShop',
        description: 'Интернет-магазин электроники с быстрой доставкой',
        theme_color: '#2563EB',
        background_color: '#0a0a0f',
        display: 'standalone',
        orientation: 'portrait',
        start_url: '/',
        scope: '/',
        icons: [
          { src: '/icons/icon-72x72.png',   sizes: '72x72',   type: 'image/png' },
          { src: '/icons/icon-96x96.png',   sizes: '96x96',   type: 'image/png' },
          { src: '/icons/icon-128x128.png', sizes: '128x128', type: 'image/png' },
          { src: '/icons/icon-144x144.png', sizes: '144x144', type: 'image/png' },
          { src: '/icons/icon-152x152.png', sizes: '152x152', type: 'image/png' },
          { src: '/icons/icon-192x192.png', sizes: '192x192', type: 'image/png', purpose: 'any maskable' },
          { src: '/icons/icon-384x384.png', sizes: '384x384', type: 'image/png' },
          { src: '/icons/icon-512x512.png', sizes: '512x512', type: 'image/png' },
        ],
        categories: ['shopping', 'electronics']
      },
      workbox: {
        globPatterns: ['**/*.{js,css,html,ico,png,svg,woff2}'],
        runtimeCaching: [
          { urlPattern: /^http:\/\/localhost:8000\/api\/products/, handler: 'NetworkFirst', options: { cacheName: 'api-products', expiration: { maxEntries: 50, maxAgeSeconds: 300 }, networkTimeoutSeconds: 10 } },
          { urlPattern: /^http:\/\/localhost:8000\/api\/categories/, handler: 'CacheFirst', options: { cacheName: 'api-categories', expiration: { maxEntries: 20, maxAgeSeconds: 3600 } } },
          { urlPattern: /^http:\/\/localhost:8000\/api\/news/, handler: 'NetworkFirst', options: { cacheName: 'api-news', expiration: { maxEntries: 20, maxAgeSeconds: 600 } } },
        ],
      },
    }),
  ],
  server: { port: 3000 },
})
