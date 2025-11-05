<?php
header("Content-Type: application/javascript");
?>
window.FIREBASE_CONFIG = {
  apiKey: "<?= getenv('FIREBASE_API_KEY') ?>",
  authDomain: "<?= getenv('FIREBASE_AUTH_DOMAIN') ?>",
  projectId: "<?= getenv('FIREBASE_PROJECT_ID') ?>",
  storageBucket: "<?= getenv('FIREBASE_STORAGE_BUCKET') ?>",
  messagingSenderId: "<?= getenv('FIREBASE_MESSAGING_SENDER_ID') ?>",
  appId: "<?= getenv('FIREBASE_APP_ID') ?>",
};

window.FIREBASE_PAIRING_CODE = "<?= getenv('FIREBASE_PAIRING_CODE') ?>";
