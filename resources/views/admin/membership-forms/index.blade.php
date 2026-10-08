@extends('layouts.admin')

@section('title', 'Membership Forms | KSO CMS')

@section('content')
<section class="membership-forms-module">
    <header class="membership-forms-heading">
        <div>
            <span class="membership-forms-eyebrow">Member control · Tools</span>
            <h1>Membership forms</h1>
            <p>Share the online application or prepare a blank paper form for offline distribution.</p>
        </div>
        <span class="membership-forms-mark"><i class="fa-solid fa-file-invoice-dollar" aria-hidden="true"></i></span>
    </header>

    <div class="row g-4">
        <div class="col-xl-5">
            <article class="membership-form-action-card h-100">
                <span class="membership-form-step">01 · Online</span>
                <h2>Share the online application</h2>
                <p>Send this public link to students. They can submit their application from a phone or computer.</p>
                <label class="form-label fw-bold" for="membership-registration-link">Public registration link</label>
                <div class="input-group mb-3">
                    <input id="membership-registration-link" class="form-control" type="url" value="{{ $onlineRegistrationUrl }}" readonly>
                    <button class="btn btn-outline-primary" type="button" id="copy-membership-link" data-copy-value="{{ $onlineRegistrationUrl }}">Copy link</button>
                </div>
                <a class="btn btn-success" href="https://wa.me/?text={{ urlencode('Apply for KSO Chandigarh membership: ' . $onlineRegistrationUrl) }}" target="_blank" rel="noopener noreferrer">
                    <i class="fa-brands fa-whatsapp me-1" aria-hidden="true"></i> Share on WhatsApp
                </a>
                <span class="membership-copy-status small text-muted ms-2" role="status" aria-live="polite"></span>
            </article>
        </div>

        <div class="col-xl-7">
            <article class="membership-form-action-card h-100">
                <span class="membership-form-step">02 · Offline</span>
                <h2>Build a printable blank form</h2>
                <p>Choose the sections to include. Print on paper, or download the standalone HTML file to open and print without internet.</p>

                <form action="{{ route('admin.membershipForms.print') }}" method="GET" id="membership-form-modules">
                    <fieldset>
                        <legend class="form-label fw-bold">Form sections</legend>
                        <p id="membership-section-error" class="alert alert-danger small py-2" role="alert" aria-live="assertive" hidden>Choose at least one form section.</p>
                        <div class="row g-2 mb-4">
                            @foreach($modules as $key => $label)
                                <div class="col-sm-6">
                                    <label class="membership-module-option">
                                        <input type="checkbox" name="modules[]" value="{{ $key }}" {{ in_array($key, $selectedModules, true) ? 'checked' : '' }}>
                                        <span>{{ $label }}</span>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </fieldset>
                    <div class="d-flex flex-wrap gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa-solid fa-print me-1" aria-hidden="true"></i> Preview & print
                        </button>
                        <button type="submit" class="btn btn-outline-primary" formaction="{{ route('admin.membershipForms.download') }}">
                            <i class="fa-solid fa-file-invoice-dollar me-1" aria-hidden="true"></i> Download offline form
                        </button>
                        <button type="button" class="btn btn-outline-secondary" id="select-all-membership-modules">Select all sections</button>
                    </div>
                    <p class="small text-muted mt-3 mb-0">In the print dialog, choose <strong>Save as PDF</strong> to create a PDF copy.</p>
                </form>
            </article>
        </div>
    </div>

    <aside class="membership-form-workflow mt-4" aria-labelledby="offline-workflow-heading">
        <h2 id="offline-workflow-heading"><i class="fa-solid fa-list-check me-2" aria-hidden="true"></i>After collecting paper applications</h2>
        <ol class="mb-0">
            <li>Review the completed form and required supporting information.</li>
            <li>Open <a href="{{ route('admin.members.create') }}">Member Control → Add Member</a> and enter the details into the existing member record system.</li>
            <li>Use the existing review and approval process. Printing or downloading a blank form does not create or approve a member record.</li>
        </ol>
    </aside>
</section>

@endsection
