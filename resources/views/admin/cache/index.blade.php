@extends('layouts.admin')

@section('title', 'Cache Manager | KSO Admin')

@section('content')
<div class="mb-4">
    <h4 class="fw-bold text-dark">Cache Manager</h4>
    <p class="text-muted mb-0">Inspect Laravel cache status and clear framework caches after configuration or content changes.</p>
</div>

<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body">
                <span class="small text-muted">Cache driver</span>
                <div class="fs-5 fw-bold text-primary">{{ $cacheDriver }}</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body">
                <span class="small text-muted">Configuration cache</span>
                <div class="fs-5 fw-bold">{{ $configurationCached ? 'Cached' : 'Not cached' }}</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body">
                <span class="small text-muted">Route cache</span>
                <div class="fs-5 fw-bold">{{ $routesCached ? 'Cached' : 'Not cached' }}</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body">
                <span class="small text-muted">Compiled views</span>
                <div class="fs-5 fw-bold">{{ number_format($compiledViewCount) }}</div>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-header bg-white p-3">
        <h5 class="fw-bold text-primary mb-0">Clear caches</h5>
    </div>
    <div class="card-body p-4">
        <div class="row g-3">
            @foreach([
                'application' => ['Application cache', 'Remove cached application data from the configured cache store.', 'fa-database'],
                'config' => ['Configuration cache', 'Reload configuration from the application config files.', 'fa-sliders'],
                'routes' => ['Route cache', 'Rebuild route definitions from the current application routes.', 'fa-route'],
                'views' => ['Compiled views', 'Remove compiled Blade templates; they are recreated as needed.', 'fa-file-code'],
                'all' => ['All optimized caches', 'Run Laravel’s optimize:clear command for framework caches.', 'fa-broom'],
            ] as $type => [$label, $description, $icon])
                <div class="col-md-6">
                    <section class="border rounded-3 p-3 h-100 d-flex flex-column">
                        <h6 class="fw-bold"><i class="fa-solid {{ $icon }} me-2 text-primary"></i>{{ $label }}</h6>
                        <p class="small text-muted flex-grow-1">{{ $description }}</p>
                        <form method="POST" action="{{ route('admin.cache.clear', $type) }}" onsubmit="return confirm('Clear {{ strtolower($label) }}?')">
                            @csrf
                            <button type="submit" class="btn btn-outline-primary">Clear cache</button>
                        </form>
                    </section>
                </div>
            @endforeach
        </div>
        <p class="small text-muted mt-4 mb-0">Cache operations are restricted to the listed Laravel commands and are recorded in the audit trail.</p>
    </div>
</div>
@endsection
