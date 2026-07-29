@extends('layouts.admin')

@section('title', 'Admin Dashboard | KSO CMS')

@section('content')

<!-- Metric Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-3 col-6">
        <div class="p-3 bg-white shadow-sm rounded-3 border-start border-4 border-primary">
            <small class="text-muted text-uppercase fw-bold extra-small">Total Members</small>
            <h3 class="fw-black text-primary mb-0">{{ $stats['totalMembers'] }}</h3>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="p-3 bg-white shadow-sm rounded-3 border-start border-4 border-warning">
            <small class="text-muted text-uppercase fw-bold extra-small">Pending Approvals</small>
            <h3 class="fw-black text-warning mb-0">{{ $stats['pendingMembers'] }}</h3>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="p-3 bg-white shadow-sm rounded-3 border-start border-4 border-success">
            <small class="text-muted text-uppercase fw-bold extra-small">Active Events</small>
            <h3 class="fw-black text-success mb-0">{{ $stats['totalEvents'] }}</h3>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="p-3 bg-white shadow-sm rounded-3 border-start border-4 border-teal" style="border-color:#0d9488 !important;">
            <small class="text-muted text-uppercase fw-bold extra-small">Total Donations</small>
            <h3 class="fw-black text-teal mb-0" style="color:#0d9488;">₹{{ number_format($stats['totalDonations']) }}</h3>
        </div>
    </div>
</div>

<!-- ApexCharts Analytics Graphs -->
<div class="row g-4 mb-4">
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
            <h6 class="fw-bold text-primary mb-3"><i class="fa-solid fa-chart-line me-2"></i> Monthly Student Registrations Trend</h6>
            <div id="membersChart" style="min-height: 250px;"></div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
            <h6 class="fw-bold text-success mb-3"><i class="fa-solid fa-chart-column me-2"></i> Monthly Donations & Welfare Funds Collection (₹)</h6>
            <div id="donationsChart" style="min-height: 250px;"></div>
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
                                <th>Photo</th>
                                <th>Member ID</th>
                                <th>Full Name</th>
                                <th>College</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentMembers as $m)
                                <tr>
                                    <td><img src="{{ asset($m->photo) }}" class="rounded-circle" width="32" height="36" style="object-fit:cover;" onerror="this.src='/images/default-avatar-m.png'"></td>
                                    <td class="fw-bold text-primary">{{ $m->id }}</td>
                                    <td class="fw-bold text-dark">{{ $m->full_name }}</td>
                                    <td>{{ $m->institution }}</td>
                                    <td><span class="badge {{ $m->status === 'Approved' ? 'bg-success' : 'bg-warning text-dark' }}">{{ $m->status }}</span></td>
                                    <td>
                                        <a href="{{ route('admin.members.show', $m->id) }}" class="btn btn-sm btn-outline-primary" title="View Profile"><i class="fa-solid fa-eye"></i></a>
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

@push('scripts')
{{-- ApexCharts is ~796 KB and the dashboard is the only page that charts
     anything, so it is loaded here rather than in the global admin layout. --}}
<script src="{{ asset('vendor/apexcharts/apexcharts.min.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Real trailing-12-month data supplied by DashboardController.
        var categories = @json($chart['labels']);

        var membersEl = document.querySelector('#membersChart');
        if (membersEl) {
            new ApexCharts(membersEl, {
                series: [{ name: 'New Registrations', data: @json($chart['members']) }],
                chart: { type: 'area', height: 230, toolbar: { show: false }, fontFamily: 'inherit' },
                colors: ['#003566'],
                stroke: { curve: 'smooth', width: 3 },
                dataLabels: { enabled: false },
                fill: { type: 'gradient', gradient: { opacityFrom: 0.4, opacityTo: 0.05 } },
                xaxis: { categories: categories },
                yaxis: { labels: { formatter: function (v) { return Math.round(v); } } },
                noData: { text: 'No registrations in this period' }
            }).render();
        }

        var donationsEl = document.querySelector('#donationsChart');
        if (donationsEl) {
            new ApexCharts(donationsEl, {
                series: [{ name: 'Welfare Funds', data: @json($chart['donations']) }],
                chart: { type: 'bar', height: 230, toolbar: { show: false }, fontFamily: 'inherit' },
                colors: ['#0d9488'],
                plotOptions: { bar: { borderRadius: 6, columnWidth: '45%' } },
                dataLabels: { enabled: false },
                xaxis: { categories: categories },
                yaxis: {
                    labels: {
                        formatter: function (v) { return '₹' + Number(v).toLocaleString('en-IN'); }
                    }
                },
                noData: { text: 'No donations in this period' }
            }).render();
        }
    });
</script>
@endpush

@endsection
