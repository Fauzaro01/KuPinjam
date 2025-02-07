<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kendaraan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controllers\HasMiddleware;
class KendaraanController extends Controller implements hasMiddleware
{
    public static function middleware() {
        return [
            'auth'
        ];
    }

    public function index()
    {  
        if(Auth::user()->hasRole('karyawan')) {
            return view('kendaraan.main', [
                'kendaraans' => Kendaraan::where('status', 'tersedia')->get()
            ]);
            
        }
        return view('kendaraan.main', [
            'kendaraans' => Kendaraan::all()
        ]);
    }

    public function statistic() {
        return view('kendaraan.statistic');
    }

    public function create()
    {
        return view("kendaraan.createKendaraan");
    }

    public function store(Request $request) {
    $request->validate([
        'plat_nomor' => 'required|string|max:20',
        'merk' => 'required|string|max:50',
        'model' => 'required|string|max:50',
        'tahun' => 'required|integer',
        'jenis_kendaraan' => 'required|in:mobil,motor',
    ]);

    Kendaraan::create([
        'plat_nomor' => $request->plat_nomor,
        'merk' => $request->merk,
        'model' => $request->model,
        'tahun' => $request->tahun,
        'jenis_kendaraan' => $request->jenis_kendaraan,
        'status' => "tersedia"
    ]);

    return redirect()->route('kendaraan.index')->withSuccess('Kendaraan berhasil ditambahkan');
    }


    public function show(string $id)
    {
        //
    }

    public function edit(Kendaraan $kendaraan)
    {
        return view('kendaraan.editKendaraan', compact('kendaraan'));
    }

    public function update(Request $request, Kendaraan $kendaraan)
    {
        $request->validate([
            'plat_nomor' => 'required|max:20',
            'merk' => 'required|max:50',
            'model' => 'required|max:50',
            'tahun' => 'required|integer|between:1900,' . date('Y'),
            'jenis_kendaraan' => 'required|in:motor,mobil',
            'status' => 'required|in:tersedia,dipinjam,perawatan',
        ]);

        $kendaraan->update($request->all());

        return redirect()->route('kendaraan.index')
            ->with('success', 'Kendaraan berhasil diperbarui.');
    }

    public function destroy(Kendaraan $kendaraan)
    {
        $kendaraan->delete();

        return redirect()->route('kendaraan.index')->with('success', 'Kendaraan berhasil dihapus.');
    }

}
