@extends('layouts.admin')

@section('title', 'Admin Dashboard | KSO CMS')

@section('content')

<div class="admin-dashboard">
<!-- Metric Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-3 col-6">
        <div class="admin-metric-card p-3 bg-white shadow-sm rounded-3 border-start border-4 border-primary">
            <small class="text-muted text-uppercase fw-bold extra-small">Total Members</small>
            <h3 class="fw-black text-primary mb-0">{{ $stats['totalMembers'] }}</h3>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="admin-metric-card p-3 bg-white shadow-sm rounded-3 border-start border-4 border-warning">
            <small class="text-muted text-uppercase fw-bold extra-small">Pending Approvals</small>
            <h3 class="fw-black text-warning mb-0">{{ $stats['pendingMembers'] }}</h3>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="admin-metric-card p-3 bg-white shadow-sm rounded-3 border-start border-4 border-success">
            <small class="text-muted text-uppercase fw-bold extra-small">Active Events</small>
            <h3 class="fw-black text-success mb-0">{{ $stats['totalEvents'] }}</h3>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="admin-metric-card admin-donations-metric p-3 bg-white shadow-sm rounded-3 border-start border-4">
            <small class="text-muted text-uppercase fw-bold extra-small">Total Donations</small>
            <h3 class="admin-donations-metric-value fw-black mb-0">₹{{ number_format($stats['totalDonations']) }}</h3>
        </div>
    </div>
</div>

<!-- ApexCharts Analytics Graphs -->
<div class="row g-4 mb-4">
    <div class="col-lg-6">
        <div class="card admin-dashboard-chart border-0 shadow-sm rounded-4 p-3 bg-white">
            <h2 class="h6 fw-bold text-primary mb-3"><i class="fa-solid fa-chart-line me-2" aria-hidden="true"></i> Monthly Student Registrations Trend</h2>
            <div id="membersChart" role="img" aria-describedby="members-chart-description"></div>
            <p id="members-chart-description" class="visually-hidden">Monthly registrations from January through July: 12, 19, 25, 30, 42, 58, and 75.</p>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card admin-dashboard-chart border-0 shadow-sm rounded-4 p-3 bg-white">
            <h2 class="h6 fw-bold text-success mb-3"><i class="fa-solid fa-chart-column me-2" aria-hidden="true"></i> Monthly Donations & Welfare Funds Collection (₹)</h2>
            <div id="donationsChart" role="img" aria-describedby="donations-chart-description"></div>
            <p id="donations-chart-description" class="visually-hidden">Monthly collection from January through July in rupees: 15,000, 22,000, 18,000, 35,000, 48,000, 52,000, and 65,000.</p>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Recent Member Registrations -->
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white p-3 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold text-primary mb-0"><i class="fa-solid fa-user-plus me-2"></i> Recent Student Applications</h5>
                <a href="{{ route('admin.members.index') }}" class="btn btn-sm btn-outline-primary rounded-pill">View All</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 extra-small">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">Photo</th>
                                <th scope="col">Member ID</th>
                                <th scope="col">Full Name</th>
                                <th scope="col">College</th>
                                <th scope="col">Status</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentMembers as $m)
                                <tr>
                                    <td><img src="{{ asset($m->photo) }}" alt="Member photo for {{ $m->full_name }}" class="rounded-circle" width="32" height="36" loading="lazy" onerror="this.src='/images/default-avatar-m.png'"></td>
                                    <td class="fw-bold text-primary">{{ $m->id }}</td>
                                    <td class="fw-bold text-dark">{{ $m->full_name }}</td>
                                    <td>{{ $m->institution }}</td>
                                    <td><span class="badge {{ $m->status === 'Approved' ? 'bg-success' : 'bg-warning text-dark' }}">{{ $m->status }}</span></td>
                                    <td>
                                        <a href="{{ route('admin.members.show', $m->id) }}" class="btn btn-sm btn-outline-primary" aria-label="View profile for {{ $m->full_name }}" title="View Profile"><i class="fa-solid fa-eye" aria-hidden="true"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Inquiries -->
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white p-3 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold text-primary mb-0"><i class="fa-solid fa-envelope me-2"></i> Recent Inquiries</h5>
                <a href="{{ route('admin.messages.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill">View All</a>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush extra-small">
                    @foreach($recentMessages as $msg)
                        <div class="list-group-item p-3">
                            <div class="d-flex w-100 justify-content-between align-items-center mb-1">
                                <span class="fw-bold text-dark">{{ $msg->name }}</span>
                                <span class="badge {{ $msg->status === 'Unread' ? 'bg-danger' : 'bg-success' }}">{{ $msg->status }}</span>
                            </div>
                            <div class="fw-bold text-primary mb-1">{{ $msg->subject }}</div>
                            <p class="text-muted mb-0 text-truncate">{{ $msg->message }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

</div>

@endsection
