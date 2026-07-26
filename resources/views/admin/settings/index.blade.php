@extends('layouts.admin')

@section('title', 'Website Settings | KSO CMS')

@section('content')

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-header bg-white p-3">
        <h5 class="fw-bold text-primary mb-0"><i class="fa-solid fa-sliders me-2"></i> Website Global Settings</h5>
    </div>
    <div class="card-body p-4">
        <form action="{{ route('admin.settings.update') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-bold">Site Title</label>
                    <input type="text" class="form-control" name="siteName" value="{{ $settings['siteName'] }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">Tagline</label>
                    <input type="text" class="form-control" name="tagline" value="{{ $settings['tagline'] }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">Official Email</label>
                    <input type="email" class="form-control" name="email" value="{{ $settings['email'] }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">Phone Number</label>
                    <input type="text" class="form-control" name="phone" value="{{ $settings['phone'] }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">Emergency Helpline</label>
                    <input type="text" class="form-control" name="helpline" value="{{ $settings['helpline'] }}">
                </div>
                <div class="col-12">
                    <label class="form-label fw-bold">Announcement Banner Text</label>
                    <input type="text" class="form-control" name="announcement" value="{{ $settings['announcement'] }}">
                </div>
                <div class="col-12">
                    <label class="form-label fw-bold">Official Address</label>
                    <input type="text" class="form-control" name="address" value="{{ $settings['address'] }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">UPI ID for Donations</label>
                    <input type="text" class="form-control" name="upiId" value="{{ $settings['upiId'] }}">
                </div>
                <div class="col-12 text-end mt-4">
                    <button type="submit" class="btn btn-accent px-5 fw-bold shadow"><i class="fa-solid fa-floppy-disk me-1"></i> Save Settings</button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection
