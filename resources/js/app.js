import Alpine from 'alpinejs';
import './darkmode.js';
import './datatables.js';
import './sidebar.js';

window.Alpine = Alpine;
Alpine.start();

/**
 * Global form submit loading state.
 * Setiap form yang submit akan disable tombol submit-nya
 * dan tampilkan spinner untuk mencegah double-submit.
 */
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('form').forEach(function (form) {
        form.addEventListener('submit', function () {
            const btns = form.querySelectorAll('button[type="submit"]');
            btns.forEach(function (btn) {
                // Jangan proses jika form sudah pakai Alpine loading state sendiri
                if (btn.hasAttribute('x-bind:disabled') || btn.hasAttribute(':disabled')) return;

                btn.disabled = true;
                const originalText = btn.textContent.trim();
                btn.innerHTML =
                    '<svg class="inline-block w-4 h-4 mr-1.5 animate-spin" fill="none" viewBox="0 0 24 24">' +
                    '<circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>' +
                    '<path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>' +
                    '</svg>' + originalText;
            });
        });
    });
});
