@extends('layouts.admin')

@section('title', 'Payment Gateway Config | KSO Admin')

@section('content')

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-header bg-white p-3 border-0">
        <h5 class="fw-bold mb-0 text-success"><i class="fa-solid fa-credit-card me-2"></i> Payment Gateway & UPI Settings</h5>
    </div>
    <div class="card-body p-4">
        <form action="{{ route('admin.settings.update') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-12 border-bottom pb-2 mb-2">
                    <h6 class="fw-bold small mb-0 text-dark">Razorpay Integration</h6>
                </div>
                <div class="col-md-6">
                    <label class="form-label extra-small fw-bold">Razorpay Key ID</label>
                    <input type="text" name="razorpayKey" class="form-control" value="{{ $settings['razorpayKey'] }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label extra-small fw-bold">Razorpay Secret Key</label>
                    <input type="password" name="razorpaySecret" class="form-control" value="{{ $settings['razorpaySecret'] }}">
                </div>

                <div class="col-12 border-bottom pb-2 mt-4 mb-2">
                    <h6 class="fw-bold small mb-0 text-dark">UPI & QR Collection</h6>
                </div>
                <div class="col-md-12">
                    <label class="form-label extra-small fw-bold">Primary UPI ID (for QR Generation)</label>
                    <input type="text" name="upiId" class="form-control" value="{{ $settings['upiId'] }}" placeholder="kso@upi">
                </div>
                
                <div class="col-12 mt-4">
                    <button type="submit" class="btn btn-success px-5 fw-bold shadow text-white">Save Gateway Settings</button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection
