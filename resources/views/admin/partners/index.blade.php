@extends('layouts.admin')

@section('title', 'Partners Management | KSO Admin')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold text-dark mb-0">Strategic Partners & Collaborators</h4>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.partners.exportCsv') }}" class="btn btn-outline-success shadow-sm">
            <i class="fa-solid fa-file-csv me-1"></i> Export CSV
        </a>
        <button type="button" class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#addPartnerModal">
            <i class="fa-solid fa-plus me-1"></i> Add New Partner
        </button>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-muted extra-small text-uppercase">
                    <tr>
                        <th class="ps-4">Organization Name</th>
                        <th>Type</th>
                        <th>Category</th>
                        <th>Contact Person</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($partners as $p)
                        <tr class="extra-small">
                            <td class="ps-4 fw-bold text-dark">{{ $p->name }}</td>
                            <td><span class="badge bg-info-lt text-info">{{ $p->type }}</span></td>
                            <td>{{ $p->category }}</td>
                            <td>{{ $p->contact_person ?? 'N/A' }}</td>
                            <td>
                                <span class="badge {{ $p->status === 'Active' ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $p->status }}
                                </span>
                            </td>
                            <td class="text-end pe-4">
                                <a href="{{ route('admin.partners.edit', $p->id) }}" class="btn btn-sm btn-light border me-1"><i class="fa-solid fa-pen"></i></a>
                                <form action="{{ route('admin.partners.destroy', $p->id) }}" method="POST" class="d-inline" id="delete-partner-{{ $p->id }}">
                                    @csrf @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-light border text-danger" onclick="confirmDelete('delete-partner-{{ $p->id }}')">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center py-4 text-muted">No partners registered yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Partner Modal -->
<div class="modal fade" id="addPartnerModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="fw-bold">Register New Partner</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.partners.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label extra-small fw-bold">Organization/Partner Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="row g-3">
                        <div class="col-6">
                            <label class="form-label extra-small fw-bold">Partnership Type</label>
                            <select name="type" class="form-select">
                                <option>Donor</option>
                                <option>Collaborator</option>
                                <option>Sponsor</option>
                                <option>Government</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label extra-small fw-bold">Category</label>
                            <select name="category" class="form-select">
                                <option>NGO</option>
                                <option>Corporate</option>
                                <option>Local Group</option>
                                <option>Institution</option>
                            </select>
                        </div>
                    </div>
                    <div class="mt-3">
                        <label class="form-label extra-small fw-bold">Contact Person</label>
                        <input type="text" name="contact_person" class="form-control">
                    </div>
                    <div class="row g-3 mt-1">
                        <div class="col-6">
                            <label class="form-label extra-small fw-bold">Email</label>
                            <input type="email" name="email" class="form-control">
                        </div>
                        <div class="col-6">
                            <label class="form-label extra-small fw-bold">Phone</label>
                            <input type="text" name="phone" class="form-control">
                        </div>
                    </div>
                    <div class="mt-3">
                        <label class="form-label extra-small fw-bold">Status</label>
                        <select name="status" class="form-select">
                            <option>Active</option>
                            <option>Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="submit" class="btn btn-primary w-100 fw-bold py-2 shadow">Register Partner</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
