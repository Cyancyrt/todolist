<!DOCTYPE html>
<html>
<head>
    <title>Web Push Test</title>
</head>
<body>

    <!-- Firebase SDK -->
    <script src="https://www.gstatic.com/firebasejs/10.13.2/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/10.13.2/firebase-messaging-compat.js"></script>

    <script>
    // Firebase config
    const firebaseConfig = {
        apiKey: "<?= esc($fcmConfig['apiKey']) ?>",
        authDomain: "<?= esc($fcmConfig['authDomain']) ?>",
        projectId:"<?= esc($fcmConfig['projectId']) ?>",
        storageBucket: "<?= esc($fcmConfig['storageBucket']) ?>",
        messagingSenderId: "<?= esc($fcmConfig['messagingSenderId']) ?>",
        appId: "<?= esc($fcmConfig['appId']) ?>"
    };

    firebase.initializeApp(firebaseConfig);
    const messaging = firebase.messaging();

    // Foreground message handler
    messaging.onMessage(payload => {
        console.log("Foreground message:", payload);
        if (Notification.permission === "granted") {
            new Notification(payload.notification.title, {
                body: payload.notification.body,
                icon: payload.notification.icon || '/favicon.ico'
            });
        }
    });

    // Enable notification & send token to server
    document.getElementById('enable-notification').addEventListener('click', async () => {
        try {
            const swReg = await navigator.serviceWorker.register('/firebase-messaging-sw.js');
            const permission = await Notification.requestPermission();
            if (permission !== 'granted') return alert('Permission denied');

            const token = await messaging.getToken({
                vapidKey: '<?= getenv('FIREBASE_PAIRING_CODE') ?>',
                serviceWorkerRegistration: swReg
            });


            const userId = document.getElementById('user-id').value;
            await fetch('/save-token', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ token, user_id: userId })
            });

        } catch (err) {
            console.error('Error getting token: ', err);
        }
    });
    </script>
</body>
</html>
