document.addEventListener("DOMContentLoaded", function () {
    const sidebar = document.getElementById("sidebarMenu");
    const toggleBtn = document.getElementById("sidebarToggleBtn");
    if (toggleBtn && sidebar) {
        toggleBtn.addEventListener("click", function (e) {
            e.stopPropagation();
            sidebar.classList.toggle("sidebar-collapsed");
        });
        // Hide sidebar when clicking outside (for all screen sizes)
        document.addEventListener("click", function (e) {
            if (!sidebar.contains(e.target) && !toggleBtn.contains(e.target)) {
                sidebar.classList.add("sidebar-collapsed");
            }
        });
        // Prevent sidebar click from closing itself
        sidebar.addEventListener("click", function (e) {
            e.stopPropagation();
        });
    }
});
