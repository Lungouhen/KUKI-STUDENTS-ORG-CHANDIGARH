@extends('layouts.admin')

@section('title', 'SMTP Email Settings | KSO Admin')

@section('content')

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-header bg-white p-3 border-0">
        <h5 class="fw-bold mb-0 text-primary"><i class="fa-solid fa-envelope-circle-check me-2"></i> SMTP Configuration</h5>
    </div>
    <div class="card-body p-4">
        <form action="{{ route('admin.settings.update') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-8">
                    <label class="form-label extra-small fw-bold">SMTP Host</label>
                    <input type="text" name="mail_host" class="form-control" value="{{ $settings['mail_host'] }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label extra-small fw-bold">SMTP Port</label>
                    <input type="text" name="mail_port" class="form-control" value="{{ $settings['mail_port'] }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label extra-small fw-bold">Username</label>
                    <input type="text" name="mail_username" class="form-control" value="{{ $settings['mail_username'] }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label extra-small fw-bold">Password</label>
                    <input type="password" name="mail_password" class="form-control" value="{{ $settings['mail_password'] }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label extra-small fw-bold">Encryption</label>
                    <select name="mail_encryption" class="form-select">
                        <option value="tls" {{ $settings['mail_encryption'] == 'tls' ? 'selected' : '' }}>TLS</option>
                        <option value="ssl" {{ $settings['mail_encryption'] == 'ssl' ? 'selected' : '' }}>SSL</option>
                        <option value="none" {{ $settings['mail_encryption'] == 'none' ? 'selected' : '' }}>None</option>
                    </select>
                </div>
                <div class="col-12 mt-4">
                    <button type="submit" class="btn btn-primary px-5 fw-bold shadow">Save SMTP Settings</button>
                    <button type="button" class="btn btn-outline-secondary px-4 ms-2">Send Test Mail</button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection
