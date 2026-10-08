@extends('layouts.app')

@section('title', 'Digital Membership ID Card | KSO Chandigarh')

@section('content')

<div class="member-id-card-page">
<div class="container my-5 text-center">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <h1 class="h3 fw-bold text-primary mb-3"><i class="fa-solid fa-id-card me-2" aria-hidden="true"></i> KSO Official Student ID Card</h1>
            
            <div class="id-card-wrapper shadow-lg text-start my-4" id="idCardPrintArea">
                <div class="id-card-header">
                    <div class="d-flex align-items-center justify-content-center gap-2">
                        <img src="{{ asset('images/kso-logo.jpg') }}" alt="KSO Chandigarh emblem">
                        <div>
                            <h5 class="mb-0 text-white">KSO CHANDIGARH</h5>
                            <p class="text-warning fw-bold">Kuki Students' Organisation</p>
                        </div>
                    </div>
                </div>
                
                <div class="id-card-body">
                    <div class="id-card-photo-container">
                        <img src="{{ asset($member->photo) }}" alt="Member photo for {{ $member->full_name }}" onerror="this.src='/images/default-avatar-m.png'">
                    </div>

                    <div class="text-center">
                        <div class="id-card-name">{{ $member->full_name }}</div>
                        <div class="id-card-num">{{ $member->id }}</div>
                    </div>

                    <table class="id-card-details w-100">
                        <caption class="visually-hidden">Membership card details</caption>
                        <tbody>
                            <tr><th scope="row" class="label">College:</th><td class="fw-bold">{{ $member->institution }}</td></tr>
                            <tr><th scope="row" class="label">Course:</th><td>{{ $member->course }} ({{ $member->year_of_study }})</td></tr>
                            <tr><th scope="row" class="label">Blood Grp:</th><td class="fw-bold text-danger">{{ $member->blood_group }}</td></tr>
                            <tr><th scope="row" class="label">Emergency:</th><td>{{ $member->emergency_phone }}</td></tr>
                            <tr><th scope="row" class="label">Status:</th><td><span class="badge {{ $member->status === 'Approved' ? 'bg-success' : 'bg-warning text-dark' }} px-2 py-0 extra-small">{{ strtoupper($member->status) }}</span></td></tr>
                        </tbody>
                    </table>
                </div>

                <div class="id-card-footer">
                    <div>
                        <div class="fw-bold text-warning">VALID UNTIL: {{ $member->valid_until ? $member->valid_until->format('Y-m-d') : '2027-06-30' }}</div>
                        <div class="extra-small opacity-75">Recognized by KSO General HQ</div>
                    </div>
                    @php
                        $verifyUrl = route('membership.verifyDirect', $member->id);
                        $qrUrl = "https://chart.googleapis.com/chart?chs=100x100&cht=qr&chl=" . urlencode($verifyUrl) . "&choe=UTF-8";
                    @endphp
                    <img src="{{ $qrUrl }}" width="50" height="50" class="rounded bg-white p-1" alt="QR code for public member ID verification" loading="lazy">
                </div>
            </div>

            <div class="mt-4">
                <button type="button" id="printMemberIdCard" class="btn btn-accent btn-lg rounded-pill px-5 fw-bold shadow">
                    <i class="fa-solid fa-print me-2" aria-hidden="true"></i> Print / Download Digital ID Card
                </button>
            </div>
        </div>
    </div>
</div>

</div>
@endsection
