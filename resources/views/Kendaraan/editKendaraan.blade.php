@extends('layouts/default-dashboard')
@section('title', 'Edit Kendaraan')

@section('content')
<div class="col-md-6 col-12">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Tambah Kendaraan</h4>
        </div>
        <div class="card-content">
            <div class="card-body">
                <form class="form form-vertical" action="{{ route('kendaraan.update', $kendaraan) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group has-icon-left">
                                    <label for="first-name-icon">Plat Nomor</label>
                                    <div class="position-relative">
                                        <input type="text" class="form-control" name="plat_nomor"
                                            placeholder="Masukan Plat Nomor" id="first-name-icon" value="{{ old('plat_nomor', $kendaraan->plat_nomor) }}" required>
                                        <div class="form-control-icon">
                                            <i class="bi bi-card-list"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">

                                <div class="form-group has-icon-left">
                                    <label for="email-id-icon">Merk</label>
                                    <div class="position-relative">
                                        <input type="text" class="form-control" name="merk"
                                            placeholder="Masukan Merek Kendaraan" id="email-id-icon" value="{{ old('merk', $kendaraan->merk) }}" required>
                                        <div class="form-control-icon">
                                            <i class="bi bi-car-front"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group has-icon-left">
                                    <label for="mobile-id-icon">Model</label>
                                    <div class="position-relative">
                                        <input type="text" class="form-control" name="model"
                                            placeholder="Masukan Model kendaraan" id="mobile-id-icon" value="{{ old('model', $kendaraan->model) }}" required>
                                        <div class="form-control-icon">
                                            <i class="bi bi-grid-3x2-gap-fill"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group has-icon-left">
                                    <label for="password-id-icon">Tahun Keluaran</label>
                                    <div class="position-relative">
                                        <input type="number" min="1900" class="form-control" name="tahun"
                                            placeholder="Masukan Tahun Keluaran Kendaraan" id="password-id-icon" value="{{ old('tahun', $kendaraan->tahun) }}" required>
                                        <div class="form-control-icon">
                                            <i class="bi bi-calendar-event"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group has-icon-left">
                                    <label for="jenis-kendaraan-icon">Jenis Kendaraan</label>
                                    <div class="position-relative">
                                        <select class="form-control" name="jenis_kendaraan"
                                            placeholder="Masukan Tahun Keluaran Kendaraan" id="jenis-kendaraan-icon" required>
                                                <option value="motor" {{ $kendaraan->jenis_kendaraan == 'motor' ? 'selected' : '' }}>Motor</option>
                                                <option value="mobil" {{ $kendaraan->jenis_kendaraan == 'mobil' ? 'selected' : '' }}>Mobil</option>
                                        </select>
                                        <div class="form-control-icon">
                                            <i class="bi bi-option"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group has-icon-left">
                                    <label for="status-kendaraan-icon">Status Kendaraan</label>
                                    <div class="position-relative">
                                        <select class="form-control" name="status"
                                            placeholder="Masukan Status kendaraan" id="status-kendaraan-icon" required>
                                                    <option value="tersedia" {{ $kendaraan->status == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                                                    <option value="dipinjam" {{ $kendaraan->status == 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                                                    <option value="perawatan" {{ $kendaraan->status == 'perawatan' ? 'selected' : '' }}>Perawatan</option>
                                        </select>
                                        <div class="form-control-icon">
                                            <i class="bi bi-option"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 mt-3 d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary me-1 mb-1">Kirim</button>
                                <button type="reset" class="btn btn-light-secondary me-1 mb-1">Reset</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection