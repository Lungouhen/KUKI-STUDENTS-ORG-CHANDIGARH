@extends('layouts.admin')

@section('title', 'Project Beneficiaries | KSO Admin')

@section('content')

<div class="admin-beneficiaries-page">
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h4 fw-bold text-dark mb-1">Beneficiary Tracking</h1>
        <p class="small text-muted mb-0">Search the beneficiary records on this page.</p>
    </div>
    <div class="admin-beneficiary-search">
        <label class="visually-hidden" for="beneficiarySearch">Filter beneficiaries by name, project, type, or support details</label>
        <input id="beneficiarySearch" type="search" class="form-control" placeholder="Filter current page">
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <caption class="visually-hidden">Beneficiary support records</caption>
                <thead class="bg-light text-muted extra-small text-uppercase">
                    <tr>
                        <th scope="col" class="ps-4">Beneficiary Name</th>
                        <th scope="col">Project</th>
                        <th scope="col">Type</th>
                        <th scope="col">Support Details</th>
                        <th scope="col">Date Registered</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($beneficiaries as $b)
                        <tr class="extra-small" data-beneficiary-row>
                            <td class="ps-4 fw-bold text-dark">{{ $b->name }}</td>
                            <td>{{ $b->project->title ?? 'General' }}</td>
                            <td><span class="badge bg-light text-dark border">{{ $b->type }}</span></td>
                            <td>{{ $b->support_needed }}</td>
                            <td>{{ $b->created_at->format('Y-m-d') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center py-4 text-muted">No beneficiaries recorded yet.</td></tr>
                    @endforelse
                    @if($beneficiaries->isNotEmpty())
                        <tr id="beneficiaryNoResults" hidden><td colspan="5" class="text-center py-4 text-muted">No beneficiaries match that filter.</td></tr>
                    @endif
                </tbody>
            </table>
        </div>
        <p id="beneficiarySearchStatus" class="visually-hidden" role="status" aria-live="polite"></p>
    </div>
</div>

</div>
@endsection
