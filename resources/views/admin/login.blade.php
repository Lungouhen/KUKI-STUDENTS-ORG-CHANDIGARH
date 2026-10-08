@extends('layouts.app')

@section('title', 'Admin CMS Login | KSO Chandigarh')

@section('content')

<div class="admin-login-page">
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-5 col-lg-4">
            <div class="card shadow-lg border-0 rounded-4 p-4 text-center">
                <div class="icon-circle bg-teal text-white mx-auto mb-3 fs-3" style="background:#0d9488;">
                    <i class="fa-solid fa-user-shield" aria-hidden="true"></i>
                </div>
                <h1 class="h3 fw-bold text-dark mb-1">CMS Admin Login</h1>
                <p class="text-muted extra-small mb-4">KSO Chandigarh Management Portal</p>

                <form action="{{ route('admin.login.post') }}" method="POST" id="adminLoginForm">
                    @csrf
                    <div class="mb-3 text-start">
                        <label for="admin-email" class="form-label fw-bold">Admin Email</label>
                        <input id="admin-email" type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" autocomplete="username" required @if($errors->has('email')) aria-invalid="true" aria-describedby="admin-email-error" @endif>
                        @error('email')<div id="admin-email-error" class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-4 text-start">
                        <label for="admin-password" class="form-label fw-bold">Password</label>
                        <input id="admin-password" type="password" name="password" class="form-control @error('password') is-invalid @enderror" autocomplete="current-password" required @if($errors->has('password')) aria-invalid="true" aria-describedby="admin-password-error" @endif>
                        @error('password')<div id="admin-password-error" class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <button type="submit" class="btn btn-teal text-white btn-lg w-100 fw-bold shadow-sm" style="background:#0d9488;">
                        <span id="admin-login-label">Sign In</span> <i class="fa-solid fa-right-to-bracket ms-1" aria-hidden="true"></i>
                    </button>
                    <span class="visually-hidden" id="admin-login-status" role="status" aria-live="polite"></span>
                </form>
            </div>
        </div>
    </div>
</div>

</div>
@endsection
