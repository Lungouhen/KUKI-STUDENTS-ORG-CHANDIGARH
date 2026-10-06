<main class="container my-5">
    <article class="mx-auto border border-warning border-2 rounded-4 overflow-hidden shadow-sm" style="max-width: 900px;">
        <header class="bg-warning-subtle p-4 p-md-5 border-bottom border-warning">
            <span class="badge text-bg-warning mb-3">NOTICE</span>
            <h1 class="h2 fw-bold text-primary mb-2">{{ $page->title }}</h1>
            @if($page->excerpt)
                <p class="lead text-dark mb-0">{{ $page->excerpt }}</p>
            @endif
        </header>
        <div class="bg-white p-4 p-md-5 leading-relaxed">
            {!! $page->content !!}
        </div>
    </article>
</main>
