const appLayout = document.querySelector(".app-layout");

const sidebarToggle = document.getElementById("sidebarToggle");
const mobileSidebarToggle = document.getElementById("mobileSidebarToggle");

const mobileBreakpoint = 768;

/* =========================
           HANDLE RESPONSIVE STATE
        ========================= */

function handleSidebarResize() {
    const isMobile = window.innerWidth <= mobileBreakpoint;

    if (isMobile) {
        /*
         * Desktop collapsed state should NOT
         * exist while on mobile.
         */
        appLayout.classList.remove("sidebar-collapsed");

        /*
         * Mobile starts closed.
         * We don't automatically open it.
         */
    } else {
        /*
         * Remove mobile state when returning
         * to desktop.
         */
        appLayout.classList.remove("mobile-sidebar-open");

        /*
         * Restore desktop collapsed state
         * from localStorage.
         */
        const isCollapsed = localStorage.getItem("sidebarCollapsed") === "true";

        appLayout.classList.toggle("sidebar-collapsed", isCollapsed);
    }
}

/* =========================
           DESKTOP SIDEBAR
        ========================= */

if (sidebarToggle) {
    sidebarToggle.addEventListener("click", function () {
        /*
         * Don't allow desktop toggle behavior
         * while on mobile.
         */
        if (window.innerWidth <= mobileBreakpoint) {
            return;
        }

        appLayout.classList.toggle("sidebar-collapsed");

        const isCollapsed = appLayout.classList.contains("sidebar-collapsed");

        localStorage.setItem("sidebarCollapsed", isCollapsed);
    });
}

/* =========================
           MOBILE SIDEBAR
        ========================= */

if (mobileSidebarToggle) {
    mobileSidebarToggle.addEventListener("click", function () {
        /*
         * Don't allow mobile behavior
         * while on desktop.
         */
        if (window.innerWidth > mobileBreakpoint) {
            return;
        }

        appLayout.classList.toggle("mobile-sidebar-open");

        const isOpen = appLayout.classList.contains("mobile-sidebar-open");

        mobileSidebarToggle.setAttribute(
            "aria-label",
            isOpen ? "Close sidebar" : "Open sidebar",
        );
    });
}

/* =========================
           INITIAL STATE
        ========================= */

handleSidebarResize();

/* =========================
           WINDOW RESIZE
        ========================= */

window.addEventListener("resize", handleSidebarResize);
