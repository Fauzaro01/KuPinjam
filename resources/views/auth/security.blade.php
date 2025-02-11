@extends('layouts/default-dashboard')

@section('title', 'Account security');

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Account Security</h3>
                <p class="text-subtitle text-muted">A page where this page can change account security settings</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Security</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <section class="section">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Change Password</h5>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                        <div class="card-text">
                            <div class="alert alert-light-success color-success"><i class="bi bi-check-circle"></i>
                            {{session('success')}}
                            </div>
                        </div>
                        @endif
                        @if(session('eror'))
                        <div class="card-text">
                            <div class="alert alert-light-warning color-warning"><i class="bi bi-exclamation-triangle"></i>
                            {{session('eror')}}
                            </div>
                        </div>
                        @endif
                        <form action="{{route('changePassword')}}" method="post">
                            @csrf
                            <div class="form-group my-2">
                                <label for="current_password" class="form-label">Current Password</label>
                                <input type="password" name="current_password" id="current_password" class="form-control" placeholder="Enter your current password" required>
                                @if ($errors->has('password'))
                                    <span class="text-danger">{{ $errors->first('password') }}</span>
                                @endif
                            </div>
                            <div class="form-group my-2">
                                <label for="password" class="form-label">New Password</label>
                                <input type="password" name="new_password" id="password" class="form-control" placeholder="Enter new password" value="" required>
                                @if ($errors->has('new_password'))
                                    <span class="text-danger">{{ $errors->first('new_password') }}</span>
                                @endif
                            </div>
                            <div class="form-group my-2">
                                <label for="new_password_confirmation" class="form-label">Confirm Password</label>
                                <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="form-control" placeholder="Enter confirm password" value="" required>
                                @if ($errors->has('new_password_confirmation'))
                                    <span class="text-danger">{{ $errors->first('new_password_confirmation') }}</span>
                                @endif
                            </div>

                            <div class="form-group my-2 d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection