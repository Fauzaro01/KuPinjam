/**
 * DataTables initialization.
 * Menginisialisasi semua tabel dengan class `.datatable` secara otomatis.
 * DataTables dimuat via CDN di layout; file ini hanya berisi inisialisasi.
 */
document.addEventListener('DOMContentLoaded', function () {
    if (typeof $.fn !== 'undefined' && typeof $.fn.DataTable !== 'undefined') {
        const tables = document.querySelectorAll('.datatable');
        tables.forEach(function (table) {
            $(table).DataTable({
                responsive: true,
                language: {
                    search:         'Cari:',
                    lengthMenu:     'Tampilkan _MENU_ data',
                    info:           'Menampilkan _START_ sampai _END_ dari _TOTAL_ data',
                    infoEmpty:      'Tidak ada data tersedia',
                    infoFiltered:   '(difilter dari _MAX_ total data)',
                    paginate: {
                        first:    'Pertama',
                        last:     'Terakhir',
                        next:     'Berikutnya',
                        previous: 'Sebelumnya',
                    },
                    emptyTable: 'Tidak ada data tersedia',
                    zeroRecords: 'Tidak ditemukan data yang cocok',
                },
            });
        });
    }
});
