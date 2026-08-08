<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\PerawatanService;
use App\Models\PerawatanKendaraan;
use Illuminate\Http\Request;

class PerawatanController extends Controller
{
    public function __construct(private PerawatanService $service) {}

    public function index()
    {
        $perawatans = $this->service->getAll();
        return view('admin.perawatan.index', compact('perawatans'));
    }

    public function create()
    {
        $kendaraans = $this->service->getKendaraanList();
        return view('admin.perawatan.create', compact('kendaraans'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'kendaraan_id'    => 'required|exists:kendaraans,id',
            'jenis_perawatan' => 'required|string|max:100',
            'tanggal_mulai'   => 'required|date',
            'estimasi_selesai'=> 'nullable|date|after_or_equal:tanggal_mulai',
            'catatan'         => 'nullable|string|max:1000',
        ]);

        $this->service->jadwalkan($data);

        return redirect()->route('perawatan.index')
                         ->with('success', 'Jadwal perawatan berhasil ditambahkan.');
    }

    public function edit(PerawatanKendaraan $perawatan)
    {
        $kendaraans = $this->service->getKendaraanList();
        return view('admin.perawatan.edit', compact('perawatan', 'kendaraans'));
    }

    public function update(Request $request, PerawatanKendaraan $perawatan)
    {
        $data = $request->validate([
            'jenis_perawatan' => 'required|string|max:100',
            'tanggal_mulai'   => 'required|date',
            'estimasi_selesai'=> 'nullable|date|after_or_equal:tanggal_mulai',
            'catatan'         => 'nullable|string|max:1000',
        ]);

        $this->service->update($perawatan, $data);

        return redirect()->route('perawatan.index')
                         ->with('success', 'Perawatan berhasil diperbarui.');
    }

    public function selesai(PerawatanKendaraan $perawatan)
    {
        $this->service->selesaikan($perawatan);

        return redirect()->route('perawatan.index')
                         ->with('success', 'Perawatan ditandai selesai. Kendaraan kini tersedia.');
    }

    public function destroy(PerawatanKendaraan $perawatan)
    {
        $this->service->hapus($perawatan);

        return redirect()->route('perawatan.index')
                         ->with('success', 'Jadwal perawatan dihapus.');
    }
}
