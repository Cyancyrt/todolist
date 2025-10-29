document.addEventListener("DOMContentLoaded", function () {
  const sidebar = document.querySelector("aside.group");
  if (sidebar) {
    // Pastikan hover trigger manual jika ada gangguan
    sidebar.addEventListener("mouseenter", function () {
      this.style.width = "12rem"; // Ekspansi manual
      const items = this.querySelectorAll(".sidebar-item");
      items.forEach((item) => (item.style.opacity = "1"));
    });
    sidebar.addEventListener("mouseleave", function () {
      this.style.width = "5rem"; // Kembali ke default
      const items = this.querySelectorAll(".sidebar-item");
      items.forEach((item) => (item.style.opacity = "0"));
    });
  }
});
