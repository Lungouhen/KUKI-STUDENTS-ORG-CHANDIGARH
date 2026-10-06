<header class="bg-primary text-white py-5">
    <div class="container py-lg-4">
        <div class="row justify-content-center text-center">
            <div class="col-lg-10">
                <span class="badge bg-warning text-dark text-uppercase mb-3">KSO Chandigarh</span>
                <h1 class="display-3 fw-black mb-3">{{ $page->title }}</h1>
                @if($page->excerpt)
                    <p class="lead opacity-90 mx-auto mb-0" style="max-width: 760px;">{{ $page->excerpt }}</p>
                @endif
            </div>
        </div>
    </div>
</header>

<main class="container py-5">
    <article class="row justify-content-center">
        <div class="col-lg-10 col-xl-9 fs-5 leading-relaxed">
            {!! $page->content !!}
        </div>
    </article>
</main>
