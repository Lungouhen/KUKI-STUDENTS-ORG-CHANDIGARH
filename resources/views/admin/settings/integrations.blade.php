@extends('layouts.admin')

@section('title', 'API Integrations & Third-Party Tools | KSO Admin')

@section('content')

<div class="mb-4">
    <h4 class="fw-bold text-dark">Full Package Integrations</h4>
    <p class="text-muted extra-small">Connect and configure your favorite tools and services to enhance NGO operations.</p>
</div>

<div class="row g-4">
    <!-- AI Integrations -->
    <div class="col-md-6">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white p-3 border-0">
                <h6 class="fw-bold mb-0 text-primary"><i class="fa-solid fa-robot me-2"></i> Artificial Intelligence (LLMs)</h6>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                        <div class="d-flex align-items-center">
                            <i class="fa-solid fa-brain me-3 text-secondary"></i>
                            <div>
                                <div class="fw-bold extra-small">DeepSeek AI</div>
                                @if(\App\Models\Setting::get('deepseekKey'))
                                    <div class="badge bg-success-lt text-success extra-small">Connected (Default)</div>
                                @else
                                    <div class="badge bg-light text-dark extra-small border">Not Connected</div>
                                @endif
                            </div>
                        </div>
                        <a href="{{ route('admin.settings.index') }}" class="btn btn-sm btn-light border">Configure</a>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                        <div class="d-flex align-items-center">
                            <i class="fa-solid fa-bolt me-3 text-secondary"></i>
                            <div>
                                <div class="fw-bold extra-small">Gemini AI</div>
                                @if(\App\Models\Setting::get('geminiKey'))
                                    <div class="badge bg-success-lt text-success extra-small">Connected</div>
                                @else
                                    <div class="badge bg-light text-dark extra-small border">Not Connected</div>
                                @endif
                            </div>
                        </div>
                        <a href="{{ route('admin.settings.index') }}" class="btn btn-sm btn-primary">Connect</a>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                        <div class="d-flex align-items-center">
                            <i class="fa-solid fa-microchip me-3 text-secondary"></i>
                            <div>
                                <div class="fw-bold extra-small">OpenAI (GPT-4)</div>
                                @if(\App\Models\Setting::get('openaiKey'))
                                    <div class="badge bg-success-lt text-success extra-small">Connected</div>
                                @else
                                    <div class="badge bg-light text-dark extra-small border">Not Connected</div>
                                @endif
                            </div>
                        </div>
                        <a href="{{ route('admin.settings.index') }}" class="btn btn-sm btn-primary">Connect</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Email Marketing -->
    <div class="col-md-6">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white p-3 border-0">
                <h6 class="fw-bold mb-0 text-success"><i class="fa-solid fa-paper-plane me-2"></i> Email Marketing & CRM</h6>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                        <div class="d-flex align-items-center">
                            <i class="fa-solid fa-envelope me-3 text-secondary"></i>
                            <div>
                                <div class="fw-bold extra-small">Mailchimp</div>
                                <div class="badge bg-light text-dark extra-small border">Not Connected</div>
                            </div>
                        </div>
                        <button class="btn btn-sm btn-primary">Connect</button>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                        <div class="d-flex align-items-center">
                            <i class="fa-solid fa-envelope me-3 text-secondary"></i>
                            <div>
                                <div class="fw-bold extra-small">Brevo (Sendinblue)</div>
                                <div class="badge bg-light text-dark extra-small border">Not Connected</div>
                            </div>
                        </div>
                        <button class="btn btn-sm btn-primary">Connect</button>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Webinar -->
    <div class="col-md-6">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white p-3 border-0">
                <h6 class="fw-bold mb-0 text-danger"><i class="fa-solid fa-video me-2"></i> Virtual Meetings & Webinars</h6>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                        <div class="d-flex align-items-center">
                            <i class="fa-solid fa-camera me-3 text-secondary"></i>
                            <div>
                                <div class="fw-bold extra-small">Zoom NGO</div>
                                <div class="badge bg-light text-dark extra-small border">Not Connected</div>
                            </div>
                        </div>
                        <button class="btn btn-sm btn-primary">Connect</button>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

@endsection
