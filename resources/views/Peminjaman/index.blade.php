@extends('layouts/default-dashboard')

@section('title', 'Riwayat Peminjaman Kendaraan')

@section('head-css')
<link rel="stylesheet" crossorigin="" href="/assets/compiled/css/table-datatable.css">
@endsection

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>KuPinjam</h3>
                <p class="text-subtitle text-muted">List Riwayat Peminjaman yang ada pada layanan KuPinjam</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.html">Peminjaman</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Riwayat Peminjaman Kendaraan</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <section class="section">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Riwayat Peminjaman {{(Auth::user()->hasRole('administrator') ? "Kendaraan" : "Anda")}}</h4>
            </div>
            <div class="card-body">
                <table class="table table-striped dataTable-table" id="table1" width="100%">
                    <thead>
                        @if(Auth::user()->hasRole('administrator'))
                            <tr>
                                <th><a class="dataTable-sorter">ID</a>
                                </th>
                                <th><a class="dataTable-sorter">Username</a>
                                </th>
                                <th><a class="dataTable-sorter">Plat Kendaraan</a>
                                </th>
                                <th><a class="dataTable-sorter">Jenis</a>
                                </th>
                                <th><a class="dataTable-sorter">Tanggal Pinjam</a>
                                </th>
                                <th><a class="dataTable-sorter">Tanggal Kembali</a>
                                </th>
                                <th><a class="dataTable-sorter">Tujuan</a>
                                </th>
                                <th><a class="dataTable-sorter">Status</a>
                                </th>
                                <th><a class="dataTable-sorter">Aksi</a>
                                </th>
                            </tr>
                        @else
                            <tr>
                                <th><a class="dataTable-sorter">Merek</a>
                                </th>
                                <th><a class="dataTable-sorter">Plat Kendaraan</a>
                                </th>
                                <th><a class="dataTable-sorter">Jenis Kendaraan</a>
                                </th>
                                <th><a class="dataTable-sorter">Tanggal Pinjam</a>
                                </th>
                                <th><a class="dataTable-sorter">Tanggal Kembali</a>
                                </th>
                                <th><a class="dataTable-sorter">Tujuan</a>
                                </th>
                                <th><a class="dataTable-sorter">Status</a>
                                </th>
                            </tr>
                        @endif
                    </thead>
                    <tbody>
                        @if(Auth::user()->hasRole('administrator'))
                            @foreach($peminjamans as $peminjaman)
                            <tr>
                                <td>{{$peminjaman->id}}</td>
                                <td>{{$peminjaman->user->username}}</td>
                                <td>{{$peminjaman->kendaraan->plat_nomor}}</td>
                                <td>
                                    @if($peminjaman->kendaraan->jenis_kendaraan == 'motor')
                                    <span class="badge bg-light-primary">
                                        <i class="bi bi-bicycle me-2"></i>
                                        Motor
                                    </span>
                                    @elseif($peminjaman->kendaraan->jenis_kendaraan == 'mobil')
                                    <span class="badge bg-light-info">
                                        <i class="bi bi-car-front me-2"></i>
                                        Mobil
                                    </span>
                                    @endif
                                </td>
                                <td>{{$peminjaman->tanggal_pinjam}}</td>
                                <td>{{$peminjaman->tanggal_kembali}}</td>
                                <td>{{$peminjaman->tujuan}}</td>
                                <td>
                                        @if($peminjaman->status_peminjaman == 'selesai')
                                        <span class="badge bg-light-success">
                                            <i class="bi bi-check2 me-2"></i>
                                            Selesai
                                        </span>
                                        @elseif($peminjaman->status_peminjaman == 'dipinjam')
                                        <span class="badge bg-light-warning">
                                            <i class="bi bi-cone-striped me-2"></i>
                                            DiPinjam
                                        </span>
                                        @endif
                                </td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <a class="btn btn-sm btn-warning" href="{{ route('peminjaman.edit', $peminjaman) }}"><i
                                            class="bi bi-pencil-square"></i></a>
                                    <form action="{{ route('peminjaman.destroy', $peminjaman->id) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger" type="submit"
                                            onclick="return confirm('Yakin ingin menghapus Peminjaman ini?')"><i
                                                class="bi bi-trash3"></i></button>
                                    </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        @else
                            @foreach($peminjamans as $peminjaman)
                            <tr>
                                <td>{{$peminjaman->kendaraan->merk}}</td>
                                <td>{{$peminjaman->kendaraan->plat_nomor}}</td>
                                <td>
                                    @if($peminjaman->kendaraan->jenis_kendaraan == 'motor')
                                        <span class="badge bg-light-primary">
                                            <i class="bi bi-bicycle me-2"></i>
                                            Motor
                                        </span>
                                    @elseif($peminjaman->kendaraan->jenis_kendaraan == 'mobil')
                                        <span class="badge bg-light-info">
                                            <i class="bi bi-car-front me-2"></i>
                                            Mobil
                                        </span>
                                        @endif
                                    </td>
                                    <td>{{$peminjaman->tanggal_pinjam}}</td>
                                    <td>{{$peminjaman->tanggal_kembali}}</td>
                                    <td><span class="badge bg-light-primary">{{$peminjaman->tujuan}}</span></td>
                                    <td>
                                        @if($peminjaman->status_peminjaman == 'selesai')
                                        <span class="badge bg-light-success">
                                            <i class="bi bi-check2 me-2"></i>
                                            Selesai
                                        </span>
                                        @elseif($peminjaman->status_peminjaman == 'dipinjam')
                                        <span class="badge bg-light-warning">
                                            <i class="bi bi-cone-striped me-2"></i>
                                            DiPinjam
                                        </span>
                                        @endif
                                    </td>
                            </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>
<script src="/assets/extensions/datatables.net/js/jquery.js"></script>
<script src="/assets/extensions/datatables.net/js/jquery.dataTables.js"></script>
<script src="/assets/extensions/datatables.net-bs5/js/dataTables.bootstrap5.js"></script>
<script>
    let dataTable = new DataTable(document.getElementById("table1"), { scrollX: !0 }); function adaptPageDropdown() { let a = dataTable.wrapper.querySelector(".dataTable-selector"); a.parentNode.parentNode.insertBefore(a, a.parentNode), a.classList.add("form-select") } function adaptPagination() { let a = dataTable.wrapper.querySelectorAll("ul.dataTable-pagination-list"); a.forEach(a => a.classList.add("pagination", "pagination-primary")); let e = dataTable.wrapper.querySelectorAll("ul.dataTable-pagination-list li"); e.forEach(a => a.classList.add("page-item")); let t = dataTable.wrapper.querySelectorAll("ul.dataTable-pagination-list li a"); t.forEach(a => a.classList.add("page-link")) } const refreshPagination = () => adaptPagination(); dataTable.on("datatable.init", () => { adaptPageDropdown(), refreshPagination() }), dataTable.on("datatable.update", refreshPagination), dataTable.on("datatable.sort", refreshPagination), dataTable.on("datatable.page", adaptPagination);
</script>
@endsection