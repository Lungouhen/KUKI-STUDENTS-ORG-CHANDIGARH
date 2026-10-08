@extends('layouts.app')

@section('title', 'Verify Student ID Card | KSO Chandigarh')

@section('content')

<div class="member-verification-page">
<div class="public-page-banner bg-primary text-white py-4 mb-4">
    <div class="container text-center">
        <h1 class="fw-black mb-1"><i class="fa-solid fa-shield-halved me-2" aria-hidden="true"></i> Student ID Card Verification Portal</h1>
        <p class="small text-light opacity-90 mb-0">Verify official KSO Chandigarh membership credentials instantly</p>
    </div>
</div>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-sm border-0 rounded-4 p-4 text-center">
                <div class="icon-circle bg-primary-lt mx-auto mb-3 text-primary fs-2">
                    <i class="fa-solid fa-shield-halved" aria-hidden="true"></i>
                </div>
                <h2 class="h4 fw-bold text-dark mb-3">Verify KSO Member ID</h2>
                <p class="text-muted extra-small mb-4" id="verification-id-help">Enter the Membership ID printed on the ID Card (e.g. <code>KSO-CHD-2026-0001</code>).</p>
                
                <form action="{{ route('membership.verify') }}" method="POST" class="mb-3" id="memberVerificationForm">
                    @csrf
                    <div class="input-group input-group-lg mb-3">
                        <label class="visually-hidden" for="verification-member-id">Membership ID</label>
                        <input type="text" id="verification-member-id" name="member_id" class="form-control text-uppercase fw-bold" placeholder="KSO-CHD-2026-0001" value="{{ $id ?? old('member_id') }}" autocomplete="off" maxlength="32" aria-describedby="verification-id-help" required>
                        <button class="btn btn-accent px-4 fw-bold" id="verification-submit" type="submit"><span id="verification-submit-label">Verify</span></button>
                    </div>
                    <span class="visually-hidden" id="verification-status" role="status" aria-live="polite"></span>
                </form>

                @if(isset($member))
                    <div class="mt-4">
                        <div class="alert {{ $member->status === 'Approved' ? 'alert-success border-success' : 'alert-warning border-warning' }} text-start rounded-4 shadow-sm p-3" role="status" aria-live="polite">
                            <div class="d-flex align-items-center mb-3">
                                <img src="{{ asset($member->photo) }}" alt="Member photo for {{ $member->full_name }}" class="rounded-circle me-3 border border-2" width="60" height="60" loading="lazy" onerror="this.src='/images/default-avatar-m.png'">
                                <div>
                                    <span class="badge {{ $member->status === 'Approved' ? 'bg-success' : 'bg-warning text-dark' }} fw-bold mb-1">
                                        <i class="fa-solid {{ $member->status === 'Approved' ? 'fa-circle-check' : 'fa-clock' }} me-1" aria-hidden="true"></i> STATUS: {{ strtoupper($member->status) }}
                                    </span>
                                    <h5 class="fw-bold mb-0 text-dark">{{ $member->full_name }}</h5>
                                    <small class="text-muted">{{ $member->id }}</small>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-sm table-borderless mb-0 extra-small">
                                    <caption class="visually-hidden">Verified membership details</caption>
                                    <tbody>
                                        <tr><th scope="row" class="fw-bold text-muted">College:</th><td>{{ $member->institution }}</td></tr>
                                        <tr><th scope="row" class="fw-bold text-muted">Course:</th><td>{{ $member->course }} ({{ $member->year_of_study }})</td></tr>
                                        <tr><th scope="row" class="fw-bold text-muted">Membership:</th><td>{{ $member->membership_type }}</td></tr>
                                        <tr><th scope="row" class="fw-bold text-muted">Valid Until:</th><td class="fw-bold text-primary">{{ $member->valid_until ? $member->valid_until->format('Y-m-d') : '2027-06-30' }}</td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @elseif(isset($id))
                    <div class="alert alert-danger text-center rounded-4 shadow-sm p-3 mt-4" role="alert">
                        <i class="fa-solid fa-circle-xmark fs-2 text-danger mb-2" aria-hidden="true"></i>
                        <h6 class="fw-bold mb-1">Verification Failed</h6>
                        <p class="extra-small mb-0">No member record was found for ID: <code>{{ $id }}</code></p>
                    </div>
                @endif

            </div>
        </div>
    </div>
</div>

</div>
@endsection
