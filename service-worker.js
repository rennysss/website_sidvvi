/* SIDVVI · Service Worker (PWA)
 * - Assets estáticos: cache-first
 * - Páginas HTML: network-first con página offline de respaldo
 * - El panel /admin-panel nunca se cachea
 * - Preparado para notificaciones PUSH (fase 9)
 */
const VERSION = 'sidvvi-v2';
// Carpeta del proyecto: / en sidvvi.com y /sidvvi/ en MAMP. No depende del scope del panel.
const BASE = new URL('./', self.location).pathname;
function abs(path) { return BASE + String(path).replace(/^\//, ''); }
const ADMIN = BASE.replace(/\/$/, '') + '/admin-panel';
const STATIC = [
  'offline.html',
  'assets/css/sidvvi.css',
  'assets/js/app.js',
  'assets/vendor/bootstrap/bootstrap.min.css',
  'assets/vendor/bootstrap/bootstrap.bundle.min.js',
  'assets/vendor/jquery/jquery.min.js',
  'assets/vendor/fontawesome/css/all.min.css',
  'assets/img/logo.png',
  'assets/img/icon-192.png'
].map(abs);

self.addEventListener('install', e => {
  e.waitUntil(caches.open(VERSION).then(c => c.addAll(STATIC)).then(() => self.skipWaiting()));
});

self.addEventListener('activate', e => {
  e.waitUntil(caches.keys().then(keys => Promise.all(keys.filter(k => k !== VERSION).map(k => caches.delete(k)))).then(() => self.clients.claim()));
});

self.addEventListener('fetch', e => {
  const req = e.request;
  const url = new URL(req.url);
  if (req.method !== 'GET' || url.origin !== location.origin) return;
  if (url.pathname === ADMIN || url.pathname.startsWith(ADMIN + '/')) return;

  if (req.mode === 'navigate') {
    e.respondWith(fetch(req).catch(() => caches.match(abs('offline.html'))));
    return;
  }
  if (url.pathname.startsWith(abs('assets/')) || url.pathname.startsWith(abs('uploads/'))) {
    e.respondWith(caches.match(req).then(hit => hit || fetch(req).then(res => {
      const copy = res.clone();
      caches.open(VERSION).then(c => c.put(req, copy));
      return res;
    })));
  }
});

self.addEventListener('push', e => {
  const data = e.data ? e.data.json() : { title: 'SIDVVI', body: '' };
  e.waitUntil(self.registration.showNotification(data.title, {
    body: data.body, icon: abs('assets/img/icon-192.png'), badge: abs('assets/img/icon-192.png'), data: { url: data.url || abs('') }
  }));
});

self.addEventListener('notificationclick', e => {
  e.notification.close();
  e.waitUntil(clients.openWindow(e.notification.data.url));
});
