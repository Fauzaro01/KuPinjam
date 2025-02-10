@extends('layouts/default-dashboard')
@section('title', 'Edit Pengguna')

@section('content')
<div class="col-md-6 col-12">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Edit Pengguna</h4>
        </div>
        <div class="card-content">
            <div class="card-body">
                <form class="form form-vertical" action="{{ route('usermanagement.update', $user) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group has-icon-left">
                                    <label for="username-input">Username</label>
                                    <div class="position-relative">
                                        <input type="text" class="form-control @error('username') is-invalid @enderror"
                                            name="username" placeholder="Masukan Username" id="username-input"
                                            value="{{ old('username', $user->username) }}">
                                        <div class="form-control-icon">
                                            <i class="bi bi-person-lines-fill"></i>
                                        </div>
                                    </div>
                                    @error('username')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group has-icon-left">
                                    <label for="email-id-icon">Email</label>
                                    <div class="position-relative">
                                        <input type="text" class="form-control @error('email') is-invalid @enderror"
                                            name="email" placeholder="Masukan Email Pengguna" id="email-id-icon"
                                            value="{{ old('email', $user->email) }}">
                                        <div class="form-control-icon">
                                            <i class="bi bi-envelope"></i>
                                        </div>
                                    </div>
                                    @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group has-icon-left">
                                    <label for="no_telp_input">No Telp</label>
                                    <div class="position-relative">
                                        <input type="text" class="form-control @error('no_telp') is-invalid @enderror"
                                            name="no_telp" placeholder="Masukan No Telepon (628123456)"
                                            id="no_telp_input" value="{{ old('no_telp', $user->no_telp) }}">
                                        <div class="form-control-icon">
                                            <i class="bi bi-telephone"></i>
                                        </div>
                                    </div>
                                    @error('no_telp')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group has-icon-left">
                                    <label for="password-id-icon">Password</label>
                                    <div class="position-relative">
                                        <input type="password" minlength="8"
                                            class="form-control @error('password') is-invalid @enderror" name="password"
                                            placeholder="Masukan Password Pengguna" id="password-id-icon"
                                            value="{{ old('password', $user->password) }}">
                                        <div class="form-control-icon">
                                            <i class="bi bi-key"></i>
                                        </div>
                                    </div>
                                    @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group has-icon-left">
                                    <label for="role_input">Role</label>
                                    <div class="position-relative">
                                        <select class="form-control @error('role') is-invalid @enderror" name="role"
                                            placeholder="Role Pengguna" id="role_input">
                                            <option value="karyawan" {{ $user->role == 'karyawan' ? 'selected' : ''
                                                }}>Karyawan</option>
                                            <option value="administrator" {{ $user->role == 'administrator' ? 'selected'
                                                : '' }}>Administrator</option>
                                        </select>
                                        <div class="form-control-icon">
                                            <i class="bi bi-diagram-2"></i>
                                        </div>
                                    </div>
                                    @error('role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
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