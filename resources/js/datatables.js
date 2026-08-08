/**
 * DataTables initialization — Tailwind-compatible.
 * Menggunakan kombinasi CSS override + createdRow + headerCallback
 * untuk memastikan styling konsisten dengan Tailwind.
 */
document.addEventListener('DOMContentLoaded', function () {
    if (typeof $ === 'undefined' || typeof $.fn.DataTable === 'undefined') return;

    // Inject override styles SETELAH DataTables JS (lebih spesifik)
    const style = document.createElement('style');
    style.id = 'dt-tailwind-override';
    style.textContent = `
        /* ── Wrapper ── */
        .dataTables_wrapper { font-family: inherit; font-size: 0.875rem; }

        /* ── Controls area ── */
        .dataTables_wrapper .dataTables_length label,
        .dataTables_wrapper .dataTables_filter label {
            display: inline-flex; align-items: center; gap: 0.5rem;
            font-size: 0.875rem; color: #6B7280; font-weight: 400;
        }
        .dataTables_wrapper .dataTables_length select {
            border: 1px solid #D1D5DB; border-radius: 0.5rem;
            padding: 0.375rem 0.625rem; font-size: 0.875rem;
            background-color: #fff; color: #374151;
            outline: none; cursor: pointer;
        }
        .dataTables_wrapper .dataTables_length select:focus {
            border-color: #3B82F6; box-shadow: 0 0 0 3px rgba(59,130,246,0.15);
        }
        .dataTables_wrapper .dataTables_filter input[type="search"] {
            border: 1px solid #D1D5DB; border-radius: 0.5rem;
            padding: 0.375rem 0.75rem; font-size: 0.875rem;
            background-color: #fff; color: #374151;
            outline: none; min-width: 180px;
        }
        .dataTables_wrapper .dataTables_filter input[type="search"]:focus {
            border-color: #3B82F6; box-shadow: 0 0 0 3px rgba(59,130,246,0.15);
        }

        /* ── Table reset ── */
        table.dataTable { border-collapse: collapse !important; border: none !important; width: 100% !important; }
        table.dataTable, table.dataTable th, table.dataTable td { box-sizing: border-box; }

        /* ── Header ── */
        table.dataTable thead th,
        table.dataTable thead td {
            background-color: #F9FAFB !important;
            border: none !important;
            padding: 0.75rem 1rem !important;
            font-size: 0.7rem !important;
            font-weight: 600 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.06em !important;
            color: #9CA3AF !important;
            white-space: nowrap;
        }

        /* ── Sorting icons ── */
        table.dataTable thead .sorting,
        table.dataTable thead .sorting_asc,
        table.dataTable thead .sorting_desc,
        table.dataTable thead .sorting_asc_disabled,
        table.dataTable thead .sorting_desc_disabled {
            cursor: pointer;
            background-image: none !important;
            padding-right: 1.25rem !important;
            position: relative;
        }
        table.dataTable thead .sorting:after        { content: "↕"; position: absolute; right: 0.35rem; top: 50%; transform: translateY(-50%); font-size: 0.65rem; color: #D1D5DB; }
        table.dataTable thead .sorting_asc:after    { content: "↑"; position: absolute; right: 0.35rem; top: 50%; transform: translateY(-50%); font-size: 0.65rem; color: #6B7280; }
        table.dataTable thead .sorting_desc:after   { content: "↓"; position: absolute; right: 0.35rem; top: 50%; transform: translateY(-50%); font-size: 0.65rem; color: #6B7280; }

        /* ── Body rows ── */
        table.dataTable tbody tr { background-color: #fff !important; }
        table.dataTable tbody tr:hover { background-color: #F8FAFC !important; }
        table.dataTable tbody tr td {
            padding: 0.65rem 1rem !important;
            color: #374151 !important;
            font-size: 0.875rem !important;
            border: none !important;
            vertical-align: middle !important;
            line-height: 1.4 !important;
        }
        table.dataTable tbody tr:first-child td { border: none !important; }

        /* ── Info text ── */
        .dataTables_wrapper .dataTables_info {
            font-size: 0.8125rem; color: #9CA3AF; padding-top: 0.5rem;
        }

        /* ── Pagination ── */
        .dataTables_wrapper .dataTables_paginate {
            display: flex; align-items: center; gap: 0.25rem;
            padding-top: 0.5rem; flex-wrap: wrap; justify-content: flex-end;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            display: inline-flex !important; align-items: center; justify-content: center;
            min-width: 2rem; height: 2rem; padding: 0 0.5rem;
            border-radius: 0.5rem; font-size: 0.8125rem; font-weight: 500;
            color: #4B5563 !important; border: 1px solid #E5E7EB !important;
            background: #fff !important; cursor: pointer; user-select: none;
            transition: all 0.15s;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: #F3F4F6 !important; border-color: #D1D5DB !important;
            color: #111827 !important;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button.current,
        .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
            background: #2563EB !important; border-color: #2563EB !important;
            color: #fff !important; cursor: default;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button.disabled,
        .dataTables_wrapper .dataTables_paginate .paginate_button.disabled:hover {
            opacity: 0.3 !important; cursor: not-allowed !important;
            background: #fff !important; color: #9CA3AF !important;
        }

        /* ── Dark mode ── */
        .dark .dataTables_wrapper .dataTables_length label,
        .dark .dataTables_wrapper .dataTables_filter label { color: #94A3B8; }
        .dark .dataTables_wrapper .dataTables_length select,
        .dark .dataTables_wrapper .dataTables_filter input[type="search"] {
            background-color: #334155 !important; border-color: #475569 !important; color: #E2E8F0 !important;
        }
        .dark table.dataTable thead th,
        .dark table.dataTable thead td {
            background-color: #1E293B !important; color: #64748B !important;
        }
        .dark table.dataTable tbody tr { background-color: #1E293B !important; }
        .dark table.dataTable tbody tr:hover { background-color: #263548 !important; }
        .dark table.dataTable tbody tr td {
            color: #CBD5E1 !important;
        }
        .dark .dataTables_wrapper .dataTables_info { color: #64748B; }
        .dark .dataTables_wrapper .dataTables_paginate .paginate_button {
            color: #94A3B8 !important; border-color: #334155 !important; background: #1E293B !important;
        }
        .dark .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: #263548 !important; border-color: #475569 !important; color: #E2E8F0 !important;
        }
        .dark .dataTables_wrapper .dataTables_paginate .paginate_button.current,
        .dark .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
            background: #2563EB !important; border-color: #2563EB !important; color: #fff !important;
        }
        .dark .dataTables_wrapper .dataTables_paginate .paginate_button.disabled,
        .dark .dataTables_wrapper .dataTables_paginate .paginate_button.disabled:hover {
            background: #1E293B !important;
        }
    `;
    document.head.appendChild(style);

    // ── Inisialisasi semua tabel ──────────────────────────────────────────
    const tables = document.querySelectorAll('.datatable');

    tables.forEach(function (table) {
        $(table).DataTable({
            responsive: false,

            dom:
                '<"flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-4"lf>' +
                't' +
                '<"flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 mt-3"ip>',

            language: {
                search:            '',
                searchPlaceholder: 'Cari...',
                lengthMenu:        'Tampilkan _MENU_ data',
                info:              'Menampilkan _START_–_END_ dari _TOTAL_ data',
                infoEmpty:         'Tidak ada data tersedia',
                infoFiltered:      '(difilter dari _MAX_ total)',
                paginate: {
                    first:    '«',
                    last:     '»',
                    next:     '›',
                    previous: '‹',
                },
                emptyTable:  'Tidak ada data tersedia',
                zeroRecords: 'Tidak ada data yang cocok',
                processing:  'Memuat...',
            },
        });
    });
});
