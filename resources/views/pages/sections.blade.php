@foreach($sections as $section)
    <section class="container my-4">
        @switch($section['type'] ?? '')
            @case('heading')
                <h2>{{ $section['heading'] }}</h2>
                @break
            @case('text')
                <div class="text-break" style="white-space: pre-line">{{ $section['text'] }}</div>
                @break
            @case('image')
                <img class="img-fluid rounded-4" src="{{ asset($section['image']) }}" alt="{{ $mediaAltText[$section['image']] ?? '' }}" loading="lazy">
                @break
            @case('callout')
                <aside class="alert alert-info rounded-4">{{ $section['text'] }}</aside>
                @break
            @case('button')
                <a class="btn btn-primary" href="{{ $section['link_url'] }}">{{ $section['link_label'] }}</a>
                @break
        @endswitch
    </section>
@endforeach
