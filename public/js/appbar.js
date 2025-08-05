document.addEventListener("DOMContentLoaded", function () {
    const sidebarToggle = document.getElementById("sidebarToggle");
    const layout = document.getElementById("mainLayout");
    const sidebar = document.querySelector(".sidebar");

    if (sidebarToggle && sidebar && layout) {
        sidebarToggle.addEventListener("click", function (e) {
            e.stopPropagation();
            if (sidebar.classList.contains("visible")) {
                layout.classList.remove("shifted");
                sidebar.classList.remove("visible");
            } else {
                layout.classList.add("shifted");
                sidebar.classList.add("visible");
            }
        });

        // Hide sidebar when clicking outside
        document.addEventListener("click", function (e) {
            if (
                !sidebar.contains(e.target) &&
                !sidebarToggle.contains(e.target)
            ) {
                layout.classList.remove("shifted");
                sidebar.classList.remove("visible");
            }
        });

        // Prevent sidebar click from closing itself
        sidebar.addEventListener("click", function (e) {
            e.stopPropagation();
        });
    }
});
