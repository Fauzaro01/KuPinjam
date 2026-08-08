<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreKendaraanRequest;
use App\Http\Requests\UpdateKendaraanRequest;
use App\Models\Kendaraan;
use App\Services\KendaraanService;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Support\Facades\Auth;

class KendaraanController extends Controller implements HasMiddleware
{
    public function __construct(
        protected KendaraanService $kendaraanService
    ) {}

    public static function middleware(): array
    {
        return ['auth'];
    }

    public function index()
    {
        $this->authorize('viewAny', Kendaraan::class);

        if (Auth::user()->hasRole('karyawan')) {
            return view('kendaraan.main', [
                'kendaraans' => Kendaraan::where('status', 'tersedia')->get(),
            ]);
        }

        return view('kendaraan.main', [
            'kendaraans' => Kendaraan::all(),
        ]);
    }

    public function create()
    {
        $this->authorize('create', Kendaraan::class);

        return view('kendaraan.createKendaraan');
    }

    public function store(StoreKendaraanRequest $request)
    {
        $this->authorize('create', Kendaraan::class);

        $this->kendaraanService->createKendaraan($request->validated());

        return redirect()->route('kendaraan.index')
            ->with('success', 'Kendaraan berhasil ditambahkan.');
    }

    public function edit(Kendaraan $kendaraan)
    {
        $this->authorize('update', $kendaraan);

        return view('kendaraan.editKendaraan', compact('kendaraan'));
    }

    public function update(UpdateKendaraanRequest $request, Kendaraan $kendaraan)
    {
        $this->authorize('update', $kendaraan);

        $this->kendaraanService->updateKendaraan($kendaraan, $request->validated());

        return redirect()->route('kendaraan.index')
            ->with('success', 'Kendaraan berhasil diperbarui.');
    }

    public function destroy(Kendaraan $kendaraan)
    {
        $this->authorize('delete', $kendaraan);

        $this->kendaraanService->deleteKendaraan($kendaraan);

        return redirect()->route('kendaraan.index')
            ->with('success', 'Kendaraan berhasil dihapus.');
    }
}
