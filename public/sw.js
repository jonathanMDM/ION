const CACHE_NAME = "paladin-v2";
const STATIC_ASSETS = [
    "/",
    "/dashboard",
    "/icons/icon-192x192.png",
    "/icons/icon-512x512.png",
];

// Install Event: Cache static assets
self.addEventListener("install", (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => {
            return cache.addAll(STATIC_ASSETS).catch((error) => {
                console.error("Failed to cache static assets:", error);
            });
        })
    );
    self.skipWaiting();
});

// Activate Event: Clean up old caches
self.addEventListener("activate", (event) => {
    event.waitUntil(
        caches.keys().then((cacheNames) => {
            return Promise.all(
                cacheNames.map((cache) => {
                    if (cache !== CACHE_NAME) {
                        return caches.delete(cache);
                    }
                })
            );
        })
    );
    self.clients.claim();
});

// Fetch Event: Network First strategy for pages, Cache First for assets
self.addEventListener("fetch", (event) => {
    const url = new URL(event.request.url);

    // Skip non-GET requests
    if (event.request.method !== "GET") {
        return;
    }

    // Skip non-http/https schemes (like chrome-extension://)
    if (!url.protocol.startsWith("http")) {
        return;
    }

    // Handle static assets (CSS, JS, Images, Fonts) with Cache First
    if (
        url.pathname.startsWith("/build/") ||
        url.pathname.startsWith("/icons/") ||
        url.pathname.match(/\.(css|js|png|jpg|jpeg|svg|woff|woff2)$/)
    ) {
        event.respondWith(
            caches.match(event.request).then((response) => {
                return (
                    response ||
                    fetch(event.request)
                        .then((fetchResponse) => {
                            return caches.open(CACHE_NAME).then((cache) => {
                                cache.put(event.request, fetchResponse.clone());
                                return fetchResponse;
                            });
                        })
                        .catch(() => {
                            // Return a fallback for images if offline
                            if (url.pathname.match(/\.(png|jpg|jpeg|svg)$/)) {
                                return caches.match("/icons/icon-192x192.png");
                            }
                        })
                );
            })
        );
        return;
    }

    // Handle navigation/pages with Network First
    if (event.request.mode === "navigate") {
        event.respondWith(
            fetch(event.request)
                .then((response) => {
                    return caches.open(CACHE_NAME).then((cache) => {
                        cache.put(event.request, response.clone());
                        return response;
                    });
                })
                .catch(() => {
                    return caches.match(event.request).then((response) => {
                        return response || caches.match("/");
                    });
                })
        );
        return;
    }

    // Default: Network First for everything else
    event.respondWith(
        fetch(event.request).catch(() => {
            return caches.match(event.request);
        })
    );
});
