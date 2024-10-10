// const CACHE_NAME = 'v1.0.0';

// const OFFLINE_VERSION = 1;

// const OFFLINE_URL = "/offline.html";

// const cacheAssets = [
//     '/favicon.ico',
//     '/offline.html',
//     '/build/',
//     '/css/',
//     '/images/',
//     '/js/',
//     '/default_profile.jpg',
// ];

// self.addEventListener('install', event => {
//     event.waitUntil(
//         caches.open(CACHE_NAME).then(cache => {
//             return Promise.all([
//                 cache.addAll(cacheAssets),
//                 cache.add(new Request(OFFLINE_URL, { cache: "reload" }))
//             ]);
//         })
//     );
//     self.skipWaiting();
// });

// self.addEventListener('activate', event => {
//     if ("navigationPreload" in self.registration) {
//         self.registration.navigationPreload.enable();
//     }

//     event.waitUntil(
//         caches.keys().then(keyList => {
//             return Promise.all(keyList.map(key => {
//                 if (key !== CACHE_NAME) {
//                     return caches.delete(key);
//                 }
//             }));
//         })
//     );
//     self.clients.claim();
// });

// self.addEventListener("fetch", (event) => {
//     if (event.request.mode === "navigate") {
//         event.respondWith(
//             (async () => {
//                 try {
//                     const preloadResponse = await event.preloadResponse;
//                     if (preloadResponse) {
//                         return preloadResponse;
//                     }

//                     const networkResponse = await fetch(event.request);

//                     const cache = await caches.open(CACHE_NAME);
//                     cache.put(event.request, networkResponse.clone());
//                     return networkResponse;
//                 } catch (error) {
//                     console.log("Fetch failed; returning offline page instead.", error);

//                     const cache = await caches.open(CACHE_NAME);
//                     const cachedResponse = await cache.match(OFFLINE_URL);
//                     return cachedResponse || Response.error();
//                 }
//             })()
//         );
//     } else {
//         event.respondWith(
//             caches.match(event.request).then(cachedResponse => {
//                 return cachedResponse || fetch(event.request);
//             })
//         );
//     }
// });


const CACHE = "pwabuilder-offline-page";

importScripts('https://storage.googleapis.com/workbox-cdn/releases/5.1.2/workbox-sw.js');

const offlineFallbackPage = "offline.html";

self.addEventListener("message", (event) => {
  if (event.data && event.data.type === "SKIP_WAITING") {
    self.skipWaiting();
  }
});

self.addEventListener('install', async (event) => {
  event.waitUntil(
    caches.open(CACHE)
      .then((cache) => cache.add(offlineFallbackPage))
  );
});

if (workbox.navigationPreload.isSupported()) {
  workbox.navigationPreload.enable();
}

workbox.routing.registerRoute(
  new RegExp('/*'),
  new workbox.strategies.StaleWhileRevalidate({
    cacheName: CACHE
  })
);

self.addEventListener('fetch', (event) => {
  if (event.request.mode === 'navigate') {
    event.respondWith((async () => {
      try {
        const preloadResp = await event.preloadResponse;

        if (preloadResp) {
          return preloadResp;
        }

        const networkResp = await fetch(event.request);
        return networkResp;
      } catch (error) {

        const cache = await caches.open(CACHE);
        const cachedResp = await cache.match(offlineFallbackPage);
        return cachedResp;
      }
    })());
  }
});
