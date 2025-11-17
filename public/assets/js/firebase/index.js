import { initializeApp } from "https://www.gstatic.com/firebasejs/10.13.2/firebase-app.js";
import {
  getMessaging,
  getToken,
  onMessage,
} from "https://www.gstatic.com/firebasejs/10.13.2/firebase-messaging.js";

// Pastikan FIREBASE_CONFIG sudah didefinisikan di window dari server
const app = initializeApp(window.FIREBASE_CONFIG);
const messaging = getMessaging(app);

// ✅ Register Service Worker (wajib sebelum getToken)
async function registerSW() {
  if (!("serviceWorker" in navigator)) {
    throw new Error("Service Worker not supported in this browser.");
  }

  // Daftarkan SW
  await navigator.serviceWorker.register("/firebase-messaging-sw.js");

  // Tunggu SW siap
  return await navigator.serviceWorker.ready;
}
// ✅ Ambil Token Notifikasi
export async function getNotificationToken() {
  try {
    const permission = await Notification.requestPermission();
    if (permission !== "granted") {
      return null;
    }

    const registration = await registerSW();
    if (!registration) return null;

    const token = await getToken(messaging, {
      vapidKey: window.FIREBASE_VAPID_KEY, // buat variabel global untuk VAPID KEY
      serviceWorkerRegistration: registration,
    });

    if (!token) {
      return null;
    }

    return token;
  } catch (err) {
    console.error("❌ Failed to get notification token:", err);
    return null;
  }
}

// ✅ Terima pesan ketika aplikasi sedang aktif (Foreground)
onMessage(messaging, (payload) => {
  console.log("📩 Foreground message:", payload);

  if (Notification.permission === "granted") {
    new Notification(payload.notification.title, {
      body: payload.notification.body,
      icon: payload.notification.icon || "/favicon.ico",
    });
  }
});
