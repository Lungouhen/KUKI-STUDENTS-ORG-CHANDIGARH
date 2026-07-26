@extends('layouts.app')

@section('title', 'Admin CMS Login | KSO Chandigarh')

@section('content')

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-5 col-lg-4">
            <div class="card shadow-lg border-0 rounded-4 p-4 text-center">
                <div class="icon-circle bg-teal text-white mx-auto mb-3 fs-3" style="background:#0d9488;">
                    <i class="fa-solid fa-user-shield"></i>
                </div>
                <h4 class="fw-bold text-dark mb-1">CMS Admin Login</h4>
                <p class="text-muted extra-small mb-4">KSO Chandigarh Management Portal</p>

                <form action="{{ route('admin.login.post') }}" method="POST">
                    @csrf
                    <div class="mb-3 text-start">
                        <label class="form-label fw-bold">Admin Email</label>
                        <input type="email" name="email" class="form-control" value="admin@ksochandigarh.org" required>
                    </div>
                    <div class="mb-4 text-start">
                        <label class="form-label fw-bold">Password</label>
                        <input type="password" name="password" class="form-control" value="admin123" required>
                    </div>
                    <button type="submit" class="btn btn-teal text-white btn-lg w-100 fw-bold shadow-sm" style="background:#0d9488;">
                        Sign In <i class="fa-solid fa-right-to-bracket ms-1"></i>
                    </button>
                </form>
                <div class="mt-3 text-muted extra-small">Default Credentials: <code>admin@ksochandigarh.org</code> / <code>admin123</code></div>
            </div>
        </div>
    </div>
</div>

@endsection
