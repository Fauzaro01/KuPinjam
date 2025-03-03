<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Kendaraan;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PeminjamanController extends Controller
{
    public function index()
    {
        if (Auth::user()->hasRole('administrator')) {
            $peminjamans = Peminjaman::with(['user', 'kendaraan'])->get();
        } else {
            $peminjamans = Peminjaman::with(['user', 'kendaraan'])->where('user_id', Auth::user()->id)->get();
        }
        return view('peminjaman.index', compact('peminjamans'));
    }

    public function create()
    {
        $kendaraans = Kendaraan::where('status', 'tersedia')->get();
        $users = User::where('role', 'karyawan')->get();
        return view('peminjaman.create', compact('kendaraans', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'kendaraan_id' => 'required',
            'tanggal_pinjam' => 'required|date',
            'tanggal_kembali' => 'required|date|after:tanggal_pinjam',
            'tujuan' => 'required|string|max:255',
        ]);

        Peminjaman::create([
            'user_id' => $request->user_id,
            'kendaraan_id' => $request->kendaraan_id,
            'tanggal_pinjam' => Carbon::parse($request->tanggal_pinjam),
            'tanggal_kembali' => Carbon::parse($request->tanggal_kembali),
            'tujuan' => $request->tujuan,
            'keterangan' => $request->keterangan,
        ]);

        $kendaraan = Kendaraan::find($request->kendaraan_id);
        $kendaraan->status = 'dipinjam';
        $kendaraan->save();

        return redirect()->route('peminjaman.index')->with('success', 'Peminjaman berhasil!');
    }

    public function edit(Peminjaman $peminjaman)
    {
        $kendaraans = Kendaraan::where('status', 'tersedia')->get();
        $users = User::where('role', 'karyawan')->get();
        return view('peminjaman.edit', compact('peminjaman', 'kendaraans', 'users'));
    }

    public function update(Request $request, Peminjaman $peminjaman)
    {
        $request->validate([
            'user_id' => 'required',
            'kendaraan_id' => 'required',
            'tanggal_pinjam' => 'required|date',
            'tanggal_kembali' => 'required|date|after:tanggal_pinjam',
            'tujuan' => 'required|string|max:255',
        ]);

        $peminjaman->update([
            'user_id' => $request->user_id,
            'kendaraan_id' => $request->kendaraan_id,
            'tanggal_pinjam' => Carbon::parse($request->tanggal_pinjam),
            'tanggal_kembali' => Carbon::parse($request->tanggal_kembali),
            'tujuan' => $request->tujuan,
            'keterangan' => $request->keterangan,
        ]);

        $kendaraan = Kendaraan::find($request->kendaraan_id);
        $kendaraan->status = 'dipinjam';
        $kendaraan->save();

        return redirect()->route('peminjaman.index')->with('success', 'Peminjaman berhasil diperbarui!');
    }

    public function destroy(Peminjaman $peminjaman)
    {
        $kendaraan = Kendaraan::find($peminjaman->kendaraan_id);
        $kendaraan->status = 'tersedia';
        $kendaraan->save();

        $peminjaman->delete();
        return redirect()->route('peminjaman.index')->with('success', 'Peminjaman berhasil dihapus!');
    }
    
    public function pinjam(Request $request) {
        $validated = $request->validate([
            'kendaraan_id' => 'required|exists:kendaraans,id',
            'tanggal_waktu' => 'required|string',
            'tujuan' => 'required|string|max:255',
        ]);
        
        $kendaraan = Kendaraan::find($validated['kendaraan_id']);
        if ($kendaraan->status !== 'Tersedia') {
            return redirect()->route('peminjaman.index')->with('error', 'Kendaraan tidak tersedia');
        }
        
        [$tanggalPinjam, $tanggalKembali] = explode(" to ", $validated['tanggal_waktu']);
        
        if (Carbon::parse($tanggalPinjam)->gte(Carbon::parse($tanggalKembali))) {
            return redirect()->route('peminjaman.index')->with('error', 'Tanggal kembali harus setelah tanggal pinjam');
        }
        
        $peminjaman = Peminjaman::create([
            'user_id' => auth()->user()->id,
            'kendaraan_id' => $kendaraan->id,
            'tanggal_pinjam' => Carbon::parse($tanggalPinjam),
            'tanggal_kembali' => Carbon::parse($tanggalKembali),
            'status_peminjaman' => 'dipinjam',
            'tujuan' => $validated['tujuan'],
            'keterangan' => $request->keterangan,
        ]);
        
        $kendaraan->update(['status' => 'dipinjam']);
        
        return redirect()->route('peminjaman.index')->with('success', 'Kendaraan telah kamu pinjam!');
    }
    
    
}
