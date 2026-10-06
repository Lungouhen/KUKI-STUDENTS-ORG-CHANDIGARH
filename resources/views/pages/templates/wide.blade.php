<main class="container-fluid px-3 px-lg-5 my-4 my-lg-5">
    <article class="bg-white p-4 p-lg-5 rounded-4 shadow-sm border">
        <header class="border-bottom pb-3 mb-4">
            <h1 class="display-6 fw-bold text-primary mb-2">{{ $page->title }}</h1>
            @if($page->excerpt)
                <p class="lead text-secondary mb-0">{{ $page->excerpt }}</p>
            @endif
        </header>
        <div class="leading-relaxed">
            {!! $page->content !!}
        </div>
    </article>
</main>
