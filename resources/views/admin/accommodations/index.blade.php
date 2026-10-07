@extends('layouts.admin')

@section('title', 'Hostel & PG Listings | KSO Admin')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold text-dark mb-0">Verified Hostels & PG Accommodations</h4>
    <a href="{{ route('admin.accommodations.create') }}" class="btn btn-primary shadow-sm">
        <i class="fa-solid fa-plus me-1"></i> Add Listing
    </a>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-muted extra-small text-uppercase">
                    <tr>
                        <th class="ps-4">Name</th>
                        <th>Type</th>
                        <th>Location</th>
                        <th>Rent / Month</th>
                        <th>Contact</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Actions</th>
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
                                <a href="{{ route('admin.accommodations.edit', $a->id) }}" class="btn btn-sm btn-light border me-1"><i class="fa-solid fa-pen"></i></a>
                                <form action="{{ route('admin.accommodations.destroy', $a->id) }}" method="POST" class="d-inline" id="delete-accommodation-{{ $a->id }}">
                                    @csrf @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-light border text-danger" onclick="confirmDelete('delete-accommodation-{{ $a->id }}')">
                                        <i class="fa-solid fa-trash"></i>
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

@endsection
