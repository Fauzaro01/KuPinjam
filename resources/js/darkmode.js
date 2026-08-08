/**
 * Dark mode handler.
 * IIFE dijalankan sebelum render untuk mencegah FOUC (flash of unstyled content).
 * Fungsi toggleDarkMode() dipanggil dari Alpine.js di sidebar.
 */
(function () {
    if (localStorage.getItem('darkmode') === 'true') {
        document.documentElement.classList.add('dark');
    }
})();

window.toggleDarkMode = function () {
    const html = document.documentElement;
    const isDark = html.classList.toggle('dark');
    localStorage.setItem('darkmode', isDark ? 'true' : 'false');
};
