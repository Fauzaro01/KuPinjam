/**
 * Sidebar toggle untuk mobile.
 * Tombol toggle (#sidebar-toggle) memperlihatkan/menyembunyikan sidebar
 * di layar mobile dengan Alpine.js atau fallback vanilla JS.
 */
document.addEventListener('DOMContentLoaded', function () {
    const toggleBtn = document.getElementById('sidebar-toggle');
    const sidebar   = document.getElementById('sidebar');
    const overlay   = document.getElementById('sidebar-overlay');

    if (!toggleBtn || !sidebar) return;

    function openSidebar() {
        sidebar.classList.remove('-translate-x-full');
        sidebar.classList.add('translate-x-0');
        if (overlay) overlay.classList.remove('hidden');
    }

    function closeSidebar() {
        sidebar.classList.remove('translate-x-0');
        sidebar.classList.add('-translate-x-full');
        if (overlay) overlay.classList.add('hidden');
    }

    toggleBtn.addEventListener('click', function () {
        const isOpen = !sidebar.classList.contains('-translate-x-full');
        isOpen ? closeSidebar() : openSidebar();
    });

    if (overlay) {
        overlay.addEventListener('click', closeSidebar);
    }
});
