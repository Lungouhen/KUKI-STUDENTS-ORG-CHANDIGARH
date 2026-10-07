@extends('layouts.admin')

@section('title', 'Integrations | KSO Admin')

@section('content')

<div class="mb-4">
    <h4 class="fw-bold text-dark">Integrations</h4>
    <p class="text-muted">Manage integrations available in this application. Services that are not implemented are clearly marked as unavailable.</p>
</div>

<div class="row g-4">
    <div class="col-md-6">
        <section class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body p-4">
                <div class="d-flex align-items-start justify-content-between gap-3">
                    <div>
                        <h5 class="fw-bold text-primary"><i class="fa-solid fa-envelope-circle-check me-2"></i>Email delivery (SMTP)</h5>
                        <p class="text-muted mb-3">Send transactional member and donation email through the configured SMTP server.</p>
                    </div>
                    <span class="badge {{ $hasSmtpSettings ? 'bg-success' : 'bg-secondary' }}">
                        {{ $hasSmtpSettings ? 'Configured' : 'Not configured' }}
                    </span>
                </div>
                <a href="{{ route('admin.settings.smtp') }}" class="btn btn-outline-primary">Configure SMTP</a>
            </div>
        </section>
    </div>

    <div class="col-md-6">
        <section class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body p-4">
                <div class="d-flex align-items-start justify-content-between gap-3">
                    <div>
                        <h5 class="fw-bold text-success"><i class="fa-solid fa-credit-card me-2"></i>Razorpay credentials</h5>
                        <p class="text-muted mb-3">Manage gateway credentials. Saving credentials alone does not activate a verified online checkout.</p>
                    </div>
                    <span class="badge {{ $hasRazorpayCredentials ? 'bg-success' : 'bg-secondary' }}">
                        {{ $hasRazorpayCredentials ? 'Credentials saved' : 'Not configured' }}
                    </span>
                </div>
                <a href="{{ route('admin.settings.gateways') }}" class="btn btn-outline-success">Manage gateway settings</a>
            </div>
        </section>
    </div>

    <div class="col-md-6">
        <section class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body p-4">
                <h5 class="fw-bold text-success"><i class="fa-brands fa-whatsapp me-2"></i>Contact and social links</h5>
                <p class="text-muted mb-3">Configure WhatsApp contact and social profile links used on the public site.</p>
                <a href="{{ route('admin.settings.index') }}" class="btn btn-outline-success">Manage public links</a>
            </div>
        </section>
    </div>

    <div class="col-md-6">
        <section class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body p-4">
                <h5 class="fw-bold text-secondary"><i class="fa-solid fa-plug me-2"></i>Other providers</h5>
                <p class="text-muted mb-0">AI providers, mailing-list services, and webinar providers do not currently have working integrations in this application.</p>
            </div>
        </section>
    </div>
</div>

@endsection
