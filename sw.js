/**
 * MotoCare Digital - Progressive Web App Service Worker
 * Provides offline caching, app shell pre-caching, and background resilience.
 */

const CACHE_NAME = 'motocare-cache-v1';
const PRECACHE_ASSETS = [
  './',
  './index.php',
  './manifest.json',
  './assets/css/custom.css',
  './assets/js/app.js',
  './assets/icons/icon.svg',
  './assets/icons/icon-192.svg'
];

// Install: Cache critical App Shell assets
self.addEventListener('install', (event) => {
  self.skipWaiting();
  event.waitUntil(
    caches.open(CACHE_NAME).then((cache) => {
      return cache.addAll(PRECACHE_ASSETS).catch((err) => {
        console.warn('PWA Precache warning:', err);
      });
    })
  );
});

// Activate: Cleanup legacy caches
self.addEventListener('activate', (event) => {
  event.waitUntil(
    caches.keys().then((cacheNames) => {
      return Promise.all(
        cacheNames.map((name) => {
          if (name !== CACHE_NAME) {
            return caches.delete(name);
          }
        })
      );
    }).then(() => self.clients.claim())
  );
});

// Fetch: Network-first for API, Stale-while-revalidate for static assets
self.addEventListener('fetch', (event) => {
  const { request } = event;
  const url = new URL(request.url);

  // Skip non-GET requests
  if (request.method !== 'GET') {
    return;
  }

  // 1. Dynamic API requests (Network-first with offline cache fallback)
  if (url.searchParams.has('action') || url.pathname.includes('/api/')) {
    event.respondWith(
      fetch(request)
        .then((networkResponse) => {
          if (networkResponse && networkResponse.status === 200) {
            const responseToCache = networkResponse.clone();
            caches.open(CACHE_NAME).then((cache) => cache.put(request, responseToCache));
          }
          return networkResponse;
        })
        .catch(() => {
          return caches.match(request).then((cachedResponse) => {
            if (cachedResponse) {
              return cachedResponse;
            }
            return new Response(JSON.stringify({
              success: false,
              offline: true,
              error: 'Anda sedang offline. Data terbaru akan dimuat saat terhubung kembali.'
            }), {
              headers: { 'Content-Type': 'application/json' }
            });
          });
        })
    );
    return;
  }

  // 2. App Shell & Static assets (Stale-While-Revalidate)
  event.respondWith(
    caches.match(request).then((cachedResponse) => {
      const fetchPromise = fetch(request).then((networkResponse) => {
        if (networkResponse && networkResponse.status === 200) {
          const responseToCache = networkResponse.clone();
          caches.open(CACHE_NAME).then((cache) => cache.put(request, responseToCache));
        }
        return networkResponse;
      }).catch(() => {
        // Fallback to cached version if offline
        return cachedResponse;
      });

      return cachedResponse || fetchPromise;
    })
  );
});
