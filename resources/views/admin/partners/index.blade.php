@extends('layouts.admin')

@section('title', 'Partners Management | KSO Admin')

@section('content')

<div class="admin-partners-page">
@if($errors->any())
    <div class="alert alert-danger rounded-4 small" role="alert" aria-labelledby="partnerErrorsHeading">
        <h2 id="partnerErrorsHeading" class="h6 fw-bold">Review the partner details</h2>
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h4 fw-bold text-dark mb-0">Strategic Partners & Collaborators</h1>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.partners.exportCsv') }}" class="btn btn-outline-success shadow-sm">
            <i class="fa-solid fa-file-csv me-1" aria-hidden="true"></i> Export CSV
        </a>
        <button type="button" class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#addPartnerModal">
            <i class="fa-solid fa-plus me-1" aria-hidden="true"></i> Add New Partner
        </button>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" aria-describedby="partnersTableCaption">
                <caption id="partnersTableCaption" class="visually-hidden">Registered strategic partners and their status</caption>
                <thead class="bg-light text-muted extra-small text-uppercase">
                    <tr>
                        <th scope="col" class="ps-4">Organization Name</th>
                        <th scope="col">Type</th>
                        <th scope="col">Category</th>
                        <th scope="col">Contact Person</th>
                        <th scope="col">Status</th>
                        <th scope="col" class="text-end pe-4">Actions</th>
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
                                <a href="{{ route('admin.partners.edit', $p->id) }}" class="btn btn-sm btn-light border me-1" aria-label="Edit {{ $p->name }}"><i class="fa-solid fa-pen" aria-hidden="true"></i></a>
                                <form action="{{ route('admin.partners.destroy', $p->id) }}" method="POST" class="d-inline" id="delete-partner-{{ $p->id }}">
                                    @csrf @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-light border text-danger" data-delete-form="delete-partner-{{ $p->id }}" aria-label="Delete {{ $p->name }}">
                                        <i class="fa-solid fa-trash" aria-hidden="true"></i>
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
<div class="mt-3">{{ $partners->links() }}</div>

<!-- Add Partner Modal -->
<div class="modal fade" id="addPartnerModal" tabindex="-1" aria-labelledby="addPartnerModalTitle" data-reopen-on-error="{{ $errors->any() ? 'true' : 'false' }}">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h2 class="h5 fw-bold" id="addPartnerModalTitle">Register New Partner</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close partner form"></button>
            </div>
            <form action="{{ route('admin.partners.store') }}" method="POST" id="createPartnerForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label extra-small fw-bold" for="partnerName">Organization/Partner Name</label>
                        <input type="text" id="partnerName" name="name" class="form-control" value="{{ old('name') }}" maxlength="255" required>
                    </div>
                    <div class="row g-3">
                        <div class="col-6">
                            <label class="form-label extra-small fw-bold" for="partnerType">Partnership Type</label>
                            <select id="partnerType" name="type" class="form-select" required>
                                <option value="Donor" @selected(old('type', 'Donor') === 'Donor')>Donor</option>
                                <option value="Collaborator" @selected(old('type') === 'Collaborator')>Collaborator</option>
                                <option value="Sponsor" @selected(old('type') === 'Sponsor')>Sponsor</option>
                                <option value="Government" @selected(old('type') === 'Government')>Government</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label extra-small fw-bold" for="partnerCategory">Category</label>
                            <select id="partnerCategory" name="category" class="form-select" required>
                                <option value="NGO" @selected(old('category', 'NGO') === 'NGO')>NGO</option>
                                <option value="Corporate" @selected(old('category') === 'Corporate')>Corporate</option>
                                <option value="Local Group" @selected(old('category') === 'Local Group')>Local Group</option>
                                <option value="Institution" @selected(old('category') === 'Institution')>Institution</option>
                            </select>
                        </div>
                    </div>
                    <div class="mt-3">
                        <label class="form-label extra-small fw-bold" for="partnerContactPerson">Contact Person</label>
                        <input type="text" id="partnerContactPerson" name="contact_person" class="form-control" value="{{ old('contact_person') }}">
                    </div>
                    <div class="row g-3 mt-1">
                        <div class="col-6">
                            <label class="form-label extra-small fw-bold" for="partnerEmail">Email</label>
                            <input type="email" id="partnerEmail" name="email" class="form-control" value="{{ old('email') }}">
                        </div>
                        <div class="col-6">
                            <label class="form-label extra-small fw-bold" for="partnerPhone">Phone</label>
                            <input type="tel" id="partnerPhone" name="phone" class="form-control" value="{{ old('phone') }}">
                        </div>
                    </div>
                    <div class="mt-3">
                        <label class="form-label extra-small fw-bold" for="partnerStatus">Status</label>
                        <select id="partnerStatus" name="status" class="form-select" required>
                            <option value="Active" @selected(old('status', 'Active') === 'Active')>Active</option>
                            <option value="Inactive" @selected(old('status') === 'Inactive')>Inactive</option>
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
