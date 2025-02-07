@extends('layouts/default-dashboard')

@section('title', 'Daftar Kendaraan')

@section('head-css')
<link rel="stylesheet" crossorigin="" href="assets/compiled/css/table-datatable.css">
<link rel="stylesheet" crossorigin="" href="assets/compiled/css/table-datatable.css">
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
                                <td>{{$kendaraan['jenis_kendaraan']}}</td>
                                <td>
                                    @if($kendaraan['status'] == 'Tersedia')
                                        <span class="badge bg-success">Tersedia</span>
                                    @elseif($kendaraan['status'] == 'Dipinjam')
                                        <span class="badge bg-warning">DiPinjam</span>
                                    @else
                                        <span class="badge bg-danger">Perbaikan</span>
                                    @endif
                                </td>
                                @if(Auth::user()->role == "administrator")
                                    <td>
                                        <a class="btn btn-sm btn-warning" href="{{ route('kendaraan.edit', $kendaraan) }}"><i class="bi bi-pencil-square"></i></a>
                                        <form action="{{ route('kendaraan.destroy', $kendaraan->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger" type="submit" onclick="return confirm('Yakin ingin menghapus kendaraan ini?')"><i class="bi bi-trash3"></i></button>
                                        </form>
                                    </td>
                                @else
                                    <td>
                                        <button class="btn btn-sm btn-success">
                                            <i class="bi bi-bookmark-plus"> Pinjam Kendaraan</i>
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
<script src="assets/extensions/datatables.net/js/jquery.js"></script>
<script src="assets/extensions/datatables.net/js/jquery.dataTables.js"></script>
<script src="assets/extensions/datatables.net-bs5/js/dataTables.bootstrap5.js"></script>
<script>
    let dataTable = new DataTable(
        document.getElementById("table1")
    , {
        scrollX: true
    })
    function adaptPageDropdown() {
        const selector = dataTable.wrapper.querySelector(".dataTable-selector")
        selector.parentNode.parentNode.insertBefore(selector, selector.parentNode)
        selector.classList.add("form-select")
    }

    function adaptPagination() {
        const paginations = dataTable.wrapper.querySelectorAll(
            "ul.dataTable-pagination-list"
        )

        for (const pagination of paginations) {
            pagination.classList.add(...["pagination", "pagination-primary"])
        }

        const paginationLis = dataTable.wrapper.querySelectorAll(
            "ul.dataTable-pagination-list li"
        )

        for (const paginationLi of paginationLis) {
            paginationLi.classList.add("page-item")
        }

        const paginationLinks = dataTable.wrapper.querySelectorAll(
            "ul.dataTable-pagination-list li a"
        )

        for (const paginationLink of paginationLinks) {
            paginationLink.classList.add("page-link")
        }
    }

    const refreshPagination = () => {
        adaptPagination()
    }

    dataTable.on("datatable.init", () => {
        adaptPageDropdown()
        refreshPagination()
    })
    dataTable.on("datatable.update", refreshPagination)
    dataTable.on("datatable.sort", refreshPagination)

    dataTable.on("datatable.page", adaptPagination)
</script>
@endsection