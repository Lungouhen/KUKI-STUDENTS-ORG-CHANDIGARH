@props(['member'])

<div class="id-card-wrapper shadow-lg text-start my-3" id="idCardPrintArea">
    <div class="id-card-header">
        <div class="d-flex align-items-center justify-content-center gap-2">
            <img src="{{ asset('images/kso-logo.jpg') }}" onerror="this.src='/images/default-avatar-m.png'">
            <div>
                <h5 class="mb-0 text-white">KSO CHANDIGARH</h5>
                <p class="text-warning fw-bold">Kuki Students' Organisation</p>
            </div>
        </div>
    </div>
    
    <div class="id-card-body">
        <div class="id-card-photo-container">
            <img src="{{ asset($member->photo) }}" onerror="this.src='/images/default-avatar-m.png'">
        </div>

        <div class="text-center">
            <div class="id-card-name">{{ $member->full_name }}</div>
            <div class="id-card-num">{{ $member->id }}</div>
        </div>

        <table class="id-card-details w-100">
            <tr><td class="label">College:</td><td class="fw-bold">{{ $member->institution }}</td></tr>
            <tr><td class="label">Course:</td><td>{{ $member->course }} ({{ $member->year_of_study }})</td></tr>
            <tr><td class="label">Blood Grp:</td><td class="fw-bold text-danger">{{ $member->blood_group }}</td></tr>
            <tr><td class="label">Emergency:</td><td>{{ $member->emergency_phone }}</td></tr>
            <tr><td class="label">Status:</td><td><span class="badge {{ $member->status === 'Approved' ? 'bg-success' : 'bg-warning text-dark' }} px-2 py-0 extra-small">{{ strtoupper($member->status) }}</span></td></tr>
        </table>
    </div>

    <div class="id-card-footer">
        <div>
            <div class="fw-bold text-warning">VALID UNTIL: {{ $member->valid_until ? $member->valid_until->format('Y-m-d') : '2027-06-30' }}</div>
            <div class="extra-small opacity-75">Recognized by KSO General HQ</div>
        </div>
        <span class="rounded bg-white p-1 d-inline-flex" title="Scan to verify this membership">
            {!! \App\Support\QrCode::svg(route('membership.verifyDirect', $member->id), 44) !!}
        </span>
    </div>
</div>
