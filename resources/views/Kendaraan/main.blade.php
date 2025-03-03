@extends('layouts/default-dashboard')

@section('title', 'Daftar Kendaraan')

@section('head-css')
<link rel="stylesheet" href="/assets/compiled/css/table-datatable.css">
<link rel="stylesheet" href="/assets/extensions/flatpickr/flatpickr.min.css">
@endsection

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>KuPinjam</h3>
                <p class="text-subtitle text-muted">List Kendaran yang terdaftar pada layanan KuPinjam</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.html">Kendaraan</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Daftar Kendaran</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <section class="section">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Daftar Keseluruhan Kendaraan</h4>
            </div>
            <div class="card-body">
                @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
                @endif

                @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
                @endif
                <table class="table table-striped dataTable-table" id="table1" width="100%">
                    <thead>
                        <tr>
                            <th><a class="dataTable-sorter">Plat Nomor</a>
                            </th>
                            <th><a class="dataTable-sorter">Merek</a>
                            </th>
                            <th><a class="dataTable-sorter">Tahun</a>
                            </th>
                            <th><a class="dataTable-sorter">Jenis Kendaraan</a>
                            </th>
                            <th><a class="dataTable-sorter">Status</a>
                            </th>
                            <th><a class="dataTable-sorter">Aksi</a>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($kendaraans as $kendaraan)
                        <tr>
                            <td>{{$kendaraan['plat_nomor']}}</td>
                            <td>{{$kendaraan['merk']}}</td>
                            <td>{{$kendaraan['tahun']}}</td>
                            <td>
                                @if($kendaraan['jenis_kendaraan'] == 'motor')
                                <span class="badge bg-light-primary">
                                    <i class="bi bi-bicycle me-2"></i>
                                    Motor
                                </span>
                                @elseif($kendaraan['jenis_kendaraan'] == 'mobil')
                                <span class="badge bg-light-info">
                                    <i class="bi bi-car-front me-2"></i>
                                    Mobil
                                </span>
                                @endif
                            </td>
                            <td>
                                @if($kendaraan['status'] == 'Tersedia')
                                <span class="badge bg-success">Tersedia</span>
                                @elseif($kendaraan['status'] == 'Dipinjam')
                                <span class="badge bg-warning">DiPinjam</span>
                                @else
                                <span class="badge bg-danger">Perbaikan</span>
                                @endif
                            </td>
                            @if(Auth::user()->hasRole("administrator"))
                            <td>
                                <a class="btn btn-sm btn-warning" href="{{ route('kendaraan.edit', $kendaraan) }}"><i class="bi bi-pencil-square"></i></a>
                                <form action="{{ route('kendaraan.destroy', $kendaraan->id) }}" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger" type="submit"
                                        onclick="return confirm('Yakin ingin menghapus kendaraan ini?')"><i class="bi bi-trash3"></i></button>
                                </form>
                            </td>
                            @else
                            <td>
                                <button type="button" class="btn btn-sm btn-success"
                                    onclick="openDatePicker({{ $kendaraan->id }})">
                                    <i class="bi bi-bookmark-plus-fill"> Pinjam Kendaraan</i>
                                </button>
                            </td>
                            @endif
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>

@if(Auth::user()->hasRole('karyawan'))
<div class="modal fade text-left" id="inlineForm" tabindex="-1" aria-labelledby="myModalLabel33" aria-hidden="true"
    role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel33">Formulir Pinjam Kendaraan</h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Menutup">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="bi bi-x">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>
            <form action="{{ route('peminjaman.pinjam')}}" method="POST">
                @csrf
                <div class="d-none"><input type="hidden" name="kendaraan_id" id="kendaraan_id_input"></div>
                <div class="modal-body">
                    <div class="form-group has-icon-left">
                        <label for="tanggal_waktu">Tanggal & Waktu Peminjaman</label>
                        <div class="position-relative">
                            <input type="text" class="form-control" name="tanggal_waktu" placeholder="Pilih rentang waktu" id="tanggal_waktu">
                            <div class="form-control-icon">
                                <i class="bi bi-calendar-range"></i>
                            </div>
                        </div>
                    </div>
                    <div class="form-group has-icon-left">
                        <label for="tujuan_pinjam">Tujuan Meminjam</label>
                        <div class="position-relative">
                            <input type="text" class="form-control" name="tujuan" placeholder="Masukan Tujuan Meminjam kendaraan"
                                id="tujuan_pinjam">
                            <div class="form-control-icon">
                                <i class="bi bi-card-checklist"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                        <span class="d-none d-sm-block">Menutup</span>
                    </button>
                    <button type="submit" class="btn btn-primary ms-1">
                        <span class="d-none d-sm-block">Kirim</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="/assets/extensions/flatpickr/flatpickr.min.js"></script>
<script src="/assets/extensions/flatpickr/plugins/confirmDate/confirmDate.js"></script>
<script>
    flatpickr("#tanggal_waktu", {
        enableTime: true,
        dateFormat: "Y-m-d H:i",
        minDate: "today",
        mode: "range",
        time_24hr: true,
        plugins: [new confirmDatePlugin({})]
    });
    function openDatePicker(id) {
        var modalElement = document.getElementById('inlineForm');
        var kendaraanIdInput = document.getElementById('kendaraan_id_input')
        var modal = new bootstrap.Modal(modalElement);

        modalElement.removeAttribute('inert');
        modal.show();
        kendaraanIdInput.value = id;
        console.log(id);
    }
    document.querySelector('#inlineForm').addEventListener('hidden.bs.modal', function () {
        this.setAttribute('inert', 'true');
    });
</script>
@endif

<script src="/assets/extensions/datatables.net/js/jquery.js"></script>
<script src="/assets/extensions/datatables.net/js/jquery.dataTables.js"></script>
<script src="/assets/extensions/datatables.net-bs5/js/dataTables.bootstrap5.js"></script>
<script>
    let dataTable = new DataTable(document.getElementById("table1"), { scrollX: !0 }); function adaptPageDropdown() { let a = dataTable.wrapper.querySelector(".dataTable-selector"); a.parentNode.parentNode.insertBefore(a, a.parentNode), a.classList.add("form-select") } function adaptPagination() { let a = dataTable.wrapper.querySelectorAll("ul.dataTable-pagination-list"); a.forEach(a => a.classList.add("pagination", "pagination-primary")); let e = dataTable.wrapper.querySelectorAll("ul.dataTable-pagination-list li"); e.forEach(a => a.classList.add("page-item")); let t = dataTable.wrapper.querySelectorAll("ul.dataTable-pagination-list li a"); t.forEach(a => a.classList.add("page-link")) } const refreshPagination = () => adaptPagination(); dataTable.on("datatable.init", () => { adaptPageDropdown(), refreshPagination() }), dataTable.on("datatable.update", refreshPagination), dataTable.on("datatable.sort", refreshPagination), dataTable.on("datatable.page", adaptPagination);
</script>
@endsection