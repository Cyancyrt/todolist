importScripts(
  "https://www.gstatic.com/firebasejs/10.13.2/firebase-app-compat.js"
);
importScripts(
  "https://www.gstatic.com/firebasejs/10.13.2/firebase-messaging-compat.js"
);

const firebaseConfig = {
  apiKey: "AIzaSyCXbk0bs2MBIfVcT2TvPzwm8ts95h8AN9Y",
  authDomain: "todo-d9c7d.firebaseapp.com",
  projectId: "todo-d9c7d",
  storageBucket: "todo-d9c7d.firebasestorage.app",
  messagingSenderId: "922385975817",
  appId: "1:922385975817:web:9b027aa99c67aeab9b46d8",
  measurementId: "G-MFVDFZMYTQ",
};
firebase.initializeApp(firebaseConfig);

// Handle background message
const messaging = firebase.messaging();
messaging.onBackgroundMessage((payload) => {
  console.log("Received background message ", payload);
  self.registration.showNotification(payload.notification.title, {
    body: payload.notification.body,
    icon: payload.notification.icon,
  });
});
