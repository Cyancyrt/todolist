import { getNotificationToken } from "./firebase/index.js"; // contoh

async function registerDeviceToken() {
  const token = await getNotificationToken();
  if (!token) return;

  await fetch("/dashboard/saveToken", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ token }),
  });
}

// panggil setelah halaman dashboard siap
document.addEventListener("DOMContentLoaded", registerDeviceToken);
