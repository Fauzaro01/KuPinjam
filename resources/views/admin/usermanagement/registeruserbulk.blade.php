@extends('layouts/default-dashboard')
@section('title', 'Registrasi Pengguna Masal')

@section('head-css')
<link rel="stylesheet" href="/assets/extensions/filepond/filepond.css">
<link rel="stylesheet" href="/assets/extensions/toastify-js/src/toastify.css">
@endsection

@section('content')
<div class="col-md-10 col-12">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Tambah Banyak Pengguna</h4>
        </div>
        <div class="card-content">
            <div class="card-body">
                <div class="card-text">
                    Tambahkan pengguna dalam jumlah banyak dengan mengunggah file CSV. Unduh template CSV melalui tombol
                    di bawah ini, isi data sesuai format, lalu unggah untuk menambah pengguna.
                    <a href="{{route('usermanagement.downloadcsvuser')}}"
                        class="btn btn-sm btn-outline-primary icon icon-left"><i class="bi bi-file-earmark-person"></i>
                        Download File CSV</a>
                </div>
                <form class="form form-vertical mt-4" action="{{ route('usermanagement.bulkstoreuser') }}"
                    enctype="multipart/form-data" method="POST">
                    @csrf
                    @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                    @endif
                    <div class="form-body">
                        <div class="row">
                            <div class="col-10 m-auto">
                                <div class="validate-file"></div>
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
<script src="/assets/extensions/filepond/filepond.js"></script>
<script src="/assets/extensions/filepond-plugin-file-validate-size/filepond-plugin-file-validate-size.min.js"></script>
<script src="/assets/extensions/filepond-plugin-file-validate-type/filepond-plugin-file-validate-type.min.js"></script>
<script src="/assets/extensions/toastify-js/src/toastify.js"></script>
<script>

    FilePond.registerPlugin(
        FilePondPluginFileValidateSize,
        FilePondPluginFileValidateType,
    )

    FilePond.create(document.querySelector(".validate-file"), {
        credits: false,
        allowFileEncode: false,
        name: 'file',

        required: true,
        acceptedFileTypes: ["text/csv"],
        fileValidateTypeDetectType: (source, type) =>
            new Promise((resolve, reject) => {
                resolve(type)
            }),
        storeAsFile: true,
    })
</script>

@endsection