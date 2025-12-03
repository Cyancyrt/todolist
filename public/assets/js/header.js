document.addEventListener("DOMContentLoaded", () => {
  const logoutModal = document.getElementById("logoutModal");
  const openLogoutModal = document.getElementById("openLogoutModal");
  const cancelLogout = document.getElementById("cancelLogout");

  if (!logoutModal || !openLogoutModal || !cancelLogout) return;

  // Open modal
  openLogoutModal.addEventListener("click", () => {
    logoutModal.classList.remove("opacity-0", "pointer-events-none");
    logoutModal.firstElementChild.classList.remove("scale-90");
    logoutModal.firstElementChild.classList.add("scale-100");
  });

  // Close modal via Cancel button
  cancelLogout.addEventListener("click", () => {
    logoutModal.classList.add("opacity-0", "pointer-events-none");
    logoutModal.firstElementChild.classList.add("scale-90");
    logoutModal.firstElementChild.classList.remove("scale-100");
  });

  // Close modal when clicking outside
  logoutModal.addEventListener("click", (e) => {
    if (e.target === logoutModal) {
      logoutModal.classList.add("opacity-0", "pointer-events-none");
      logoutModal.firstElementChild.classList.add("scale-90");
      logoutModal.firstElementChild.classList.remove("scale-100");
    }
  });
});
