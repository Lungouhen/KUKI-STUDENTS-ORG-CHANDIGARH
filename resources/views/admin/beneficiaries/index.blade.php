@extends('layouts.admin')

@section('title', 'Project Beneficiaries | KSO Admin')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold text-dark mb-0">Beneficiary Tracking</h4>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-muted extra-small text-uppercase">
                    <tr>
                        <th class="ps-4">Beneficiary Name</th>
                        <th>Project</th>
                        <th>Type</th>
                        <th>Support Details</th>
                        <th>Date Registered</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($beneficiaries as $b)
                        <tr class="extra-small">
                            <td class="ps-4 fw-bold text-dark">{{ $b->name }}</td>
                            <td>{{ $b->project->title ?? 'General' }}</td>
                            <td><span class="badge bg-light text-dark border">{{ $b->type }}</span></td>
                            <td>{{ $b->support_needed }}</td>
                            <td>{{ $b->created_at->format('Y-m-d') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center py-4 text-muted">No beneficiaries recorded yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
