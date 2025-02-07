@extends('layouts/default-dashboard')
@section('title', 'Tambah Kendaraan')

@section('content')
<div class="col-md-6 col-12">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Tambah Kendaraan</h4>
        </div>
        <div class="card-content">
            <div class="card-body">
                <form class="form form-vertical" action="{{route('kendaraan.store')}}" method="POST">
                    @csrf
                    <div class="form-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group has-icon-left">
                                    <label for="first-name-icon">Plat Nomor</label>
                                    <div class="position-relative">
                                        <input type="text" class="form-control" name="plat_nomor"
                                            placeholder="Masukan Plat Nomor" id="first-name-icon">
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
                                            placeholder="Masukan Merek Kendaraan" id="email-id-icon">
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
                                            placeholder="Masukan Model kendaraan" id="mobile-id-icon">
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
                                            placeholder="Masukan Tahun Keluaran Kendaraan" id="password-id-icon">
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
                                            placeholder="Masukan Tahun Keluaran Kendaraan" id="jenis-kendaraan-icon">
                                            <option value="mobil">Mobil</option>
                                            <option value="motor">Motor</option>
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