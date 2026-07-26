@extends('layouts.app')

@section('title', 'Official Donation Receipt #' . $donation->id)

@section('content')

<div class="container my-5 text-center">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-7">
            <div class="card border-0 shadow-lg rounded-4 p-5 text-start bg-white" id="receiptPrintArea">
                <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
                    <div class="d-flex align-items-center gap-2">
                        <img src="{{ asset('images/kso-logo.jpg') }}" class="rounded-circle border border-warning" width="50" height="50" onerror="this.src='/images/default-avatar-m.png'">
                        <div>
                            <h4 class="fw-bold text-primary mb-0">KSO CHANDIGARH</h4>
                            <p class="text-muted extra-small mb-0">Kuki Students' Organisation NGO</p>
                        </div>
                    </div>
                    <div class="text-end">
                        <span class="badge bg-success fs-6">OFFICIAL RECEIPT</span>
                        <div class="extra-small text-muted mt-1">Receipt No: <strong>RCPT-{{ str_pad($donation->id, 5, '0', STR_PAD_LEFT) }}</strong></div>
                        <div class="extra-small text-muted">Date: {{ $donation->date ? $donation->date->format('Y-m-d') : date('Y-m-d') }}</div>
                    </div>
                </div>

                <div class="p-3 bg-light rounded-3 border mb-4">
                    <div class="row g-2 extra-small">
                        <div class="col-md-6"><strong>Donor Name:</strong> {{ $donation->donor_name }}</div>
                        <div class="col-md-6"><strong>Contact Phone:</strong> {{ $donation->phone ?? 'N/A' }}</div>
                        <div class="col-md-6"><strong>Email:</strong> {{ $donation->email ?? 'N/A' }}</div>
                        <div class="col-md-6"><strong>Payment Method/Ref:</strong> <code>{{ $donation->payment_ref }}</code></div>
                    </div>
                </div>

                <table class="table table-bordered extra-small mb-4">
                    <thead class="table-primary">
                        <tr>
                            <th>Welfare Cause / Purpose</th>
                            <th class="text-end">Amount (₹)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>{{ $donation->cause }}</strong></td>
                            <td class="text-end fw-bold text-success fs-6">₹{{ number_format($donation->amount, 2) }}</td>
                        </tr>
                    </tbody>
                </table>

                <div class="alert alert-warning extra-small mb-4">
                    <strong>NGO Registration Notice:</strong> KSO Chandigarh is a non-governmental student welfare organization. All donations directly fund student medical relief, books, and accommodation support.
                </div>

                <div class="d-flex justify-content-between align-items-end pt-4 border-top">
                    <div>
                        <small class="text-muted extra-small">Computer Generated Official Receipt</small>
                    </div>
                    <div class="text-center">
                        <div class="fw-bold text-primary border-bottom pb-1" style="width:150px;">President / Treasurer</div>
                        <small class="text-muted extra-small">Authorized Signature</small>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <button onclick="window.print()" class="btn btn-accent btn-lg rounded-pill px-5 fw-bold shadow">
                    <i class="fa-solid fa-print me-2"></i> Print Official Receipt
                </button>
            </div>
        </div>
    </div>
</div>

@endsection
