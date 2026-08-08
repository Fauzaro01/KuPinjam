<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Jalan Peminjaman - {{ $peminjaman->id }}</title>
    {{-- Google Fonts: Inter --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6;
            margin: 0;
            padding: 20px;
            color: #1f2937;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -1px rgba(0,0,0,0.06);
            padding: 40px;
            position: relative;
        }

        /* Kop Surat */
        .header-letter {
            border-bottom: 3px double #d1d5db;
            padding-bottom: 20px;
            margin-bottom: 30px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .company-logo {
            width: 50px;
            height: 50px;
            background-color: #3b82f6;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 24px;
        }

        .company-info h1 {
            margin: 0;
            font-size: 20px;
            font-weight: 700;
            letter-spacing: -0.025em;
            color: #111827;
        }

        .company-info p {
            margin: 2px 0 0 0;
            font-size: 11px;
            color: #6b7280;
        }

        .header-right {
            text-align: right;
        }

        .document-title {
            font-size: 16px;
            font-weight: 700;
            color: #3b82f6;
            margin: 0;
            text-transform: uppercase;
        }

        .document-id {
            font-size: 11px;
            color: #9ca3af;
            margin: 4px 0 0 0;
            font-family: monospace;
        }

        /* Barcode Simulasi */
        .barcode-sim {
            margin-top: 10px;
            background-color: #111;
            height: 35px;
            width: 140px;
            display: inline-block;
            border-radius: 2px;
            position: relative;
            background: repeating-linear-gradient(
                90deg,
                #111,
                #111 2px,
                #fff 2px,
                #fff 4px,
                #111 4px,
                #111 8px,
                #fff 8px,
                #fff 10px
            );
        }

        /* Detail Grid */
        .section-title {
            font-size: 13px;
            font-weight: 600;
            color: #4b5563;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border-bottom: 1px solid #f3f4f6;
            padding-bottom: 8px;
            margin-bottom: 15px;
        }

        .grid-detail {
            display: grid;
            grid-cols: 1;
            gap: 20px;
            margin-bottom: 30px;
        }

        @media (min-width: 600px) {
            .grid-detail {
                grid-template-columns: 1fr 1fr;
            }
        }

        .info-group {
            background-color: #f9fafb;
            border: 1px solid #f3f4f6;
            border-radius: 12px;
            padding: 15px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            font-size: 13px;
            border-bottom: 1px dashed #e5e7eb;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            color: #6b7280;
        }

        .info-value {
            font-weight: 600;
            color: #1f2937;
        }

        /* Catatan */
        .notes-box {
            background-color: #eff6ff;
            border: 1px solid #bfdbfe;
            border-radius: 12px;
            padding: 15px;
            margin-bottom: 40px;
            font-size: 13px;
        }

        .notes-title {
            font-weight: 600;
            color: #1e3a8a;
            margin-bottom: 6px;
        }

        .notes-content {
            color: #1e40af;
            line-height: 1.5;
        }

        /* Signature block */
        .signature-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            text-align: center;
            margin-top: 50px;
            font-size: 13px;
        }

        .signature-space {
            height: 70px;
        }

        .signature-name {
            font-weight: 600;
            color: #1f2937;
            text-decoration: underline;
        }

        .signature-title {
            color: #6b7280;
            font-size: 11px;
            margin-top: 4px;
        }

        /* Action Buttons */
        .actions {
            margin-top: 30px;
            display: flex;
            justify-content: center;
            gap: 15px;
        }

        .btn {
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.15s ease-in-out;
        }

        .btn-print {
            background-color: #3b82f6;
            color: white;
            border: none;
        }

        .btn-print:hover {
            background-color: #2563eb;
        }

        .btn-back {
            background-color: #f3f4f6;
            color: #4b5563;
            border: 1px solid #d1d5db;
        }

        .btn-back:hover {
            background-color: #e5e7eb;
        }

        /* Print Override */
        @media print {
            body {
                background-color: white;
                padding: 0;
            }

            .container {
                box-shadow: none;
                padding: 0;
                max-width: 100%;
            }

            .actions {
                display: none;
            }
        }
    </style>
</head>
<body>

    <div class="container">
        {{-- Kop Surat --}}
        <div class="header-letter">
            <div class="header-left">
                <div class="company-logo">K</div>
                <div class="company-info">
                    <h1>PT. KUPINJAM INDONESIA</h1>
                    <p>Jl. Jenderal Sudirman No. 45, Jakarta Pusat, DKI Jakarta</p>
                    <p>Telp: (021) 1234-5678 | Email: support@kupinjam.co.id</p>
                </div>
            </div>
            <div class="header-right">
                <p class="document-title">Surat Jalan Peminjaman</p>
                <p class="document-id">REF: KUP-{{ str_pad($peminjaman->id, 6, '0', STR_PAD_LEFT) }}</p>
                <div class="barcode-sim"></div>
            </div>
        </div>

        {{-- Detail Grid --}}
        <div class="grid-detail">
            {{-- Peminjam --}}
            <div>
                <h2 class="section-title">Detail Pengemudi (Peminjam)</h2>
                <div class="info-group">
                    <div class="info-row">
                        <span class="info-label">Nama Lengkap</span>
                        <span class="info-value">{{ $peminjaman->user?->username ?? '-' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Email Kantor</span>
                        <span class="info-value">{{ $peminjaman->user?->email ?? '-' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Nomor Telepon</span>
                        <span class="info-value">{{ $peminjaman->user?->no_telp ?? '-' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Role Jabatan</span>
                        <span class="info-value" style="text-transform: capitalize;">{{ $peminjaman->user?->role ?? '-' }}</span>
                    </div>
                </div>
            </div>

            {{-- Kendaraan --}}
            <div>
                <h2 class="section-title">Detail Unit Kendaraan</h2>
                <div class="info-group">
                    <div class="info-row">
                        <span class="info-label">Plat Nomor</span>
                        <span class="info-value">{{ $peminjaman->kendaraan?->plat_nomor ?? '-' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Merk / Brand</span>
                        <span class="info-value">{{ $peminjaman->kendaraan?->merk ?? '-' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Model / Tipe</span>
                        <span class="info-value">{{ $peminjaman->kendaraan?->model ?? '-' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Jenis Kendaraan</span>
                        <span class="info-value" style="text-transform: capitalize;">{{ $peminjaman->kendaraan?->jenis_kendaraan ?? '-' }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Detail Waktu & Perjalanan --}}
        <h2 class="section-title">Detail Waktu & Perjalanan</h2>
        <div class="info-group" style="margin-bottom: 30px;">
            <div class="info-row">
                <span class="info-label">Tanggal Mulai Pinjam</span>
                <span class="info-value">{{ \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->translatedFormat('d F Y H:i') }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Rencana Pengembalian</span>
                <span class="info-value">{{ \Carbon\Carbon::parse($peminjaman->tanggal_kembali)->translatedFormat('d F Y H:i') }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Tujuan Perjalanan</span>
                <span class="info-value">{{ $peminjaman->tujuan }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Keterangan Tambahan</span>
                <span class="info-value">{{ $peminjaman->keterangan ?? 'Tidak ada' }}</span>
            </div>
        </div>

        {{-- Notes --}}
        <div class="notes-box">
            <div class="notes-title">⚠️ Syarat & Ketentuan Penggunaan Armada</div>
            <div class="notes-content">
                1. Kendaraan hanya boleh digunakan untuk kepentingan operasional kantor sesuai tujuan di atas.<br>
                2. Pengemudi wajib mematuhi seluruh peraturan lalu lintas yang berlaku di Indonesia.<br>
                3. Segala bentuk kerusakan kecil/besar atau kehilangan komponen kendaraan saat peminjaman harus segera dilaporkan ke admin.
            </div>
        </div>

        {{-- Signature Section --}}
        <div class="signature-section">
            <div>
                <p>Menyetujui,</p>
                <div class="signature-space"></div>
                <p class="signature-name">ADMIN OPERASIONAL</p>
                <p class="signature-title">PT. KuPinjam Indonesia</p>
            </div>
            <div>
                <p>Pengemudi / Peminjam,</p>
                <div class="signature-space"></div>
                <p class="signature-name">{{ strtoupper($peminjaman->user?->username ?? 'KARYAWAN') }}</p>
                <p class="signature-title">Staff Pengemudi</p>
            </div>
        </div>

        {{-- Actions --}}
        <div class="actions">
            <a href="{{ route('peminjaman.index') }}" class="btn btn-back">
                &larr; Kembali ke Daftar
            </a>
            <button onclick="window.print()" class="btn btn-print">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                </svg>
                Cetak Surat Jalan
            </button>
        </div>
    </div>

</body>
</html>
