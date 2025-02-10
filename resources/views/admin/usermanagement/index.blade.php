@extends('layouts/default-dashboard')

@section('title', 'Daftar Pengguna')

@section('head-css')
<link rel="stylesheet" crossorigin="" href="/assets/compiled/css/table-datatable.css">
@endsection

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>KuPinjam</h3>
                <p class="text-subtitle text-muted">List Pengguna yang terdaftar pada layanan KuPinjam</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.html">Pengelola Pengguna</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Daftar Pengguna</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <section class="section">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Daftar Pengguna KuPinjam</h4>
            </div>
            <div class="card-body">
                <table class="table table-striped dataTable-table" id="table1" width="100%">
                    <thead>
                        <tr>
                            <th><a class="dataTable-sorter">ID</a>
                            </th>
                            <th><a class="dataTable-sorter">Username</a>
                            </th>
                            <th><a class="dataTable-sorter">Email</a>
                            </th>
                            <th><a class="dataTable-sorter">No Telp</a>
                            </th>
                            <th><a class="dataTable-sorter">Role</a>
                            </th>
                            <th><a class="dataTable-sorter">Aksi</a>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td>{{$user['id']}}</td>
                            <td>{{$user['username']}}</td>
                            <td>{{$user['email']}}</td>
                            <td>{{$user['no_telp']}}</td>
                            <td>
                                @if($user['role'] == 'karyawan')
                                <span class="badge bg-success">Karyawan</span>
                                @else
                                <span class="badge bg-warning">Administrator</span>
                                @endif
                            </td>
                            <td>
                                <a class="btn btn-sm btn-warning" href="{{ route('usermanagement.edit', $user) }}"><i
                                        class="bi bi-pencil-square"></i></a>
                                <form action="{{ route('usermanagement.destroy', $user->id) }}" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger" type="submit"
                                        onclick="return confirm('Yakin ingin menghapus pengguna ini?')"><i
                                            class="bi bi-trash3"></i></button>
                                </form>
                            </td>
                            @endforeach
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