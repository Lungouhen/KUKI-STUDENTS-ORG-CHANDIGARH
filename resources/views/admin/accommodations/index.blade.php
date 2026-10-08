@extends('layouts.admin')

@section('title', 'Hostel & PG Listings | KSO Admin')

@section('content')

<div class="admin-accommodations-page">
@if(session('success'))
    <div class="alert alert-success rounded-4 small" role="status" aria-live="polite">{{ session('success') }}</div>
@endif
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h4 fw-bold text-dark mb-0">Verified Hostels & PG Accommodations</h1>
    <a href="{{ route('admin.accommodations.create') }}" class="btn btn-primary shadow-sm">
        <i class="fa-solid fa-plus me-1" aria-hidden="true"></i> Add Listing
    </a>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <caption class="visually-hidden">Hostel and PG accommodation listings</caption>
                <thead class="bg-light text-muted extra-small text-uppercase">
                    <tr>
                        <th scope="col" class="ps-4">Name</th>
                        <th scope="col">Type</th>
                        <th scope="col">Location</th>
                        <th scope="col">Rent / Month</th>
                        <th scope="col">Contact</th>
                        <th scope="col">Status</th>
                        <th scope="col" class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($accommodations as $a)
                        <tr class="extra-small">
                            <td class="ps-4 fw-bold text-dark">{{ $a->name }}</td>
                            <td><span class="badge bg-info-lt text-info">{{ $a->type }}</span></td>
                            <td>{{ $a->location }}</td>
                            <td class="fw-bold text-success">₹{{ number_format($a->rent_monthly) }}</td>
                            <td>{{ $a->contact_phone }}</td>
                            <td>
                                <span class="badge {{ $a->is_active ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $a->is_active ? 'Visible' : 'Hidden' }}
                                </span>
                            </td>
                            <td class="text-end pe-4">
                                <a href="{{ route('admin.accommodations.edit', $a->id) }}" class="btn btn-sm btn-light border me-1" aria-label="Edit {{ $a->name }}"><i class="fa-solid fa-pen" aria-hidden="true"></i></a>
                                <form action="{{ route('admin.accommodations.destroy', $a->id) }}" method="POST" class="d-inline" id="delete-accommodation-{{ $a->id }}">
                                    @csrf @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-light border text-danger accommodation-delete-button" data-form-id="delete-accommodation-{{ $a->id }}" data-confirm-message="Remove {{ $a->name }} from the accommodation directory?" aria-label="Delete {{ $a->name }}">
                                        <i class="fa-solid fa-trash" aria-hidden="true"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center py-4 text-muted">No accommodation listings yet. Add the first verified hostel or PG.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-3">
            {{ $accommodations->links() }}
        </div>
    </div>
</div>

</div>
@endsection
