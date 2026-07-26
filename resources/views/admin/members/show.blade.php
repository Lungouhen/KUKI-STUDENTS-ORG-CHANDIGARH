@extends('layouts.admin')

@section('title', 'Member Profile - ' . $member->full_name)

@section('content')

<div class="row g-4">
    <div class="col-lg-5 text-center">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
            <x-id_card_preview :member="$member" />

            <div class="mt-3 d-flex justify-content-center gap-2">
                <button onclick="window.print()" class="btn btn-accent rounded-pill px-4 fw-bold">
                    <i class="fa-solid fa-print me-1"></i> Print ID Card
                </button>
                <a href="{{ route('admin.members.index') }}" class="btn btn-outline-secondary rounded-pill">
                    <i class="fa-solid fa-arrow-left me-1"></i> Back to List
                </a>
            </div>
        </div>
    </div>

    <div class="col-lg-7">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
            <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-3">
                <h4 class="fw-bold text-primary mb-0"><i class="fa-solid fa-user-check me-2"></i> Application Profile Details</h4>
                <span class="badge {{ $member->status === 'Approved' ? 'bg-success' : 'bg-warning text-dark' }} fs-6">{{ $member->status }}</span>
            </div>

            <table class="table table-borderless table-sm extra-small">
                <tr><th class="text-primary" style="width:35%;">Membership ID:</th><td class="fw-bold text-dark">{{ $member->id }}</td></tr>
                <tr><th class="text-primary">Full Name:</th><td class="fw-bold fs-6 text-dark">{{ $member->full_name }}</td></tr>
                <tr><th class="text-primary">Gender & DOB:</th><td>{{ $member->gender }} • {{ $member->dob ? $member->dob->format('Y-m-d') : '' }}</td></tr>
                <tr><th class="text-primary">Contact Phone:</th><td><a href="tel:{{ $member->phone }}" class="fw-bold text-dark">{{ $member->phone }}</a></td></tr>
                <tr><th class="text-primary">Email Address:</th><td><a href="mailto:{{ $member->email }}">{{ $member->email }}</a></td></tr>
                <tr><th class="text-primary">Blood Group:</th><td><span class="badge bg-danger fs-6">{{ $member->blood_group }}</span></td></tr>
                <tr><th class="text-primary">College / Institute:</th><td class="fw-bold">{{ $member->institution }}</td></tr>
                <tr><th class="text-primary">Course & Dept:</th><td>{{ $member->course }} ({{ $member->department ?? 'N/A' }}) - {{ $member->year_of_study }}</td></tr>
                <tr><th class="text-primary">Student Roll No:</th><td><code>{{ $member->roll_no ?? 'N/A' }}</code></td></tr>
                <tr><th class="text-primary">Permanent Address (Manipur):</th><td>{{ $member->permanent_address }}</td></tr>
                <tr><th class="text-primary">Current Address (Chandigarh):</th><td>{{ $member->current_address }}</td></tr>
                <tr><th class="text-primary">Emergency Contact:</th><td>{{ $member->emergency_contact }} (<strong>{{ $member->emergency_phone }}</strong>)</td></tr>
                <tr><th class="text-primary">Applied Date:</th><td>{{ $member->applied_date ? $member->applied_date->format('Y-m-d') : '' }}</td></tr>
                <tr><th class="text-primary">Approval Date:</th><td>{{ $member->approval_date ? $member->approval_date->format('Y-m-d') : 'Pending' }}</td></tr>
            </table>

            @if($member->status === 'Pending')
                <div class="mt-4 pt-3 border-top d-flex gap-2">
                    <form action="{{ route('admin.members.updateStatus', $member->id) }}" method="POST" class="flex-grow-1">
                        @csrf
                        <input type="hidden" name="status" value="Approved">
                        <button type="submit" class="btn btn-success w-100 fw-bold py-2"><i class="fa-solid fa-check me-2"></i> Approve Membership Application</button>
                    </form>
                    <form action="{{ route('admin.members.updateStatus', $member->id) }}" method="POST" class="flex-grow-1">
                        @csrf
                        <input type="hidden" name="status" value="Rejected">
                        <button type="submit" class="btn btn-warning text-dark w-100 fw-bold py-2"><i class="fa-solid fa-xmark me-2"></i> Reject Application</button>
                    </form>
                </div>
            @endif
        </div>
    </div>
</div>

@endsection
