<!-- ====================== Sidebar Styles ====================== -->
<style>
/* MOBILE & TABLET MODE: Sidebar start hidden (off-canvas) */
@media (max-width: 1023px) {
  #sidebar {
    position: fixed;
    width: 18rem;
    height: 100vh;
    background: white;
    top: 0px;
    left: 0;
    transform: translateX(-100%);
    transition: transform .4s ease;
    z-index: 50;
    overflow-y: auto;
  }

  #closeButton {
    display: block;
  }
}

/* WHEN OPEN (Mobile + Tablet) */
#sidebar.sidebar-open {
  transform: translateX(0);
}

/* DESKTOP MODE */
@media (min-width: 1024px) {
  #sidebar {
    position: relative;
    transform: translateX(0);
    background-color: white;
    top: 0;
    box-shadow: none;
  }

  #closeButton {
    display: none;
  }
}

/* Close button appearance */
#closeButton {
  position: absolute;
  right: 1rem;
  top: 1rem;
  font-size: 20px;
  cursor: pointer;
  color: #555;
}
</style>



<!-- ====================== SIDEBAR ====================== -->
<aside id="sidebar" class="p-4 shadow-md sidebar-closed">

    <div id="closeButton">✕</div>

    <!-- Dashboard -->
    <a href="<?= base_url('dashboard'); ?>" class="sidebar-link flex items-center space-x-4 w-full p-2 rounded-lg hover:bg-gray-50">
        <div class="w-10 h-10 bg-cyan-100 rounded-full flex items-center justify-center">
            <svg class="w-6 h-6 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2 2z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M8 5a2 2 0 012-2h4a2 2 0 012 2v2H8V5z"></path>
            </svg>
        </div>
        <span class="font-medium text-gray-700">Dashboard</span>
    </a>

    <!-- Activity -->
    <a href="<?= base_url('dashboard/activity'); ?>" class="sidebar-link flex items-center space-x-4 w-full p-2 rounded-lg hover:bg-gray-50">
        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
            </svg>
        </div>
        <span class="font-medium text-gray-700">Activity</span>
    </a>

    <!-- Notes -->
    <a href="<?= base_url('dashboard/notes'); ?>" class="sidebar-link flex items-center space-x-4 w-full p-2 rounded-lg hover:bg-gray-50">
        <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
            </svg>
        </div>
        <span class="font-medium text-gray-700">Notes</span>
    </a>

    <!-- Summary -->
    <a href="<?= base_url('dashboard/summary'); ?>" class="sidebar-link flex items-center space-x-4 w-full p-2 rounded-lg hover:bg-gray-50">
        <div class="w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center">
            <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
            </svg>
        </div>
        <span class="font-medium text-gray-700">Summary</span>
    </a>

    <!-- Calendar -->
    <a href="<?= base_url('dashboard/calendar'); ?>" class="sidebar-link flex items-center space-x-4 w-full p-2 rounded-lg hover:bg-gray-50">
        <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center">
            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
        </div>
        <span class="font-medium text-gray-700">Calendar</span>
    </a>
</aside>



<!-- ====================== SCRIPT ====================== -->
<script>
document.addEventListener("DOMContentLoaded", () => {

  const sidebar = document.getElementById("sidebar");
  const toggleBtn = document.getElementById("toggleSidebar");
  const closeBtn = document.getElementById("closeButton");
  const links = document.querySelectorAll(".sidebar-link");

  function updateSidebarMode() {
    if (window.innerWidth >= 1024) {
      sidebar.classList.remove("sidebar-open", "sidebar-closed");
    } else {
      sidebar.classList.add("sidebar-closed");
    }
  }

  toggleBtn?.addEventListener("click", () => {
    sidebar.classList.toggle("sidebar-open");
    sidebar.classList.toggle("sidebar-closed");
  });

  closeBtn?.addEventListener("click", () => {
    sidebar.classList.remove("sidebar-open");
    sidebar.classList.add("sidebar-closed");
  });

  links.forEach(link => {
    link.addEventListener("click", () => {
      if (window.innerWidth < 1024) {
        sidebar.classList.remove("sidebar-open");
        sidebar.classList.add("sidebar-closed");
      }
    });
  });

  window.addEventListener("resize", updateSidebarMode);

  updateSidebarMode();
});
</script>
