/* DALI Quản Lý — Service Worker (PWA admin) */
const CACHE = 'dali-admin-v1';

self.addEventListener('install', (e) => self.skipWaiting());

self.addEventListener('activate', (e) => {
  e.waitUntil(
    caches.keys().then((keys) =>
      Promise.all(keys.filter((k) => k !== CACHE).map((k) => caches.delete(k)))
    ).then(() => self.clients.claim())
  );
});

// Mạng trước (admin cần dữ liệu mới); chỉ để qua, không cache động.
self.addEventListener('fetch', (e) => {
  // để trình duyệt xử lý mặc định (network)
});

// Bấm vào thông báo -> mở/đưa app về trang đơn hàng
self.addEventListener('notificationclick', (e) => {
  e.notification.close();
  const target = (e.notification.data && e.notification.data.url) || '/admin/orders';
  e.waitUntil(
    self.clients.matchAll({ type: 'window', includeUncontrolled: true }).then((list) => {
      for (const c of list) {
        if (c.url.includes('/admin') && 'focus' in c) { c.navigate(target); return c.focus(); }
      }
      if (self.clients.openWindow) return self.clients.openWindow(target);
    })
  );
});
