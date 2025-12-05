const CACHE_NAME = "moviedrift-v2";
const ASSETS = [
  "/",
  "/index.html",
  "/img/logo.png",
  "/img/logo-192.png",
  "/img/logo-512.png",
  "/manifest.json"
];

// Install Service Worker and Cache Static Assets
self.addEventListener("install", (e) => {
  e.waitUntil(
    caches.open(CACHE_NAME).then((cache) => cache.addAll(ASSETS))
  );
  self.skipWaiting(); // Activate worker immediately
});

// Activate Service Worker and Clean Old Caches
self.addEventListener("activate", (e) => {
  e.waitUntil(
    caches.keys().then((keys) => 
      Promise.all(
        keys
          .filter((key) => key !== CACHE_NAME)
          .map((key) => caches.delete(key))
      )
    )
  );
  self.clients.claim(); // Take control of pages immediately
});

// Fetch Cached Assets, Fallback to Network
self.addEventListener("fetch", (e) => {
  e.respondWith(
    caches.match(e.request).then((cachedResponse) => {
      if (cachedResponse) return cachedResponse;

      // Fetch from network and cache it dynamically
      return fetch(e.request)
        .then((networkResponse) => {
          return caches.open(CACHE_NAME).then((cache) => {
            // Only cache GET requests and avoid opaque responses
            if (e.request.method === "GET" && networkResponse.type !== "opaque") {
              cache.put(e.request, networkResponse.clone());
            }
            return networkResponse;
          });
        })
        .catch(() => {
          // Optional: fallback page or image if offline
          if (e.request.destination === "document") return caches.match("/index.html");
        });
    })
  );
});
