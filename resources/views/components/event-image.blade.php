@props(['event', 'variant' => 'card'])

<div {{ $attributes->class(['event-visual', 'event-visual--' . $variant]) }}>
    @if($event->image && !str_starts_with($event->image, '/images/event-'))
        <img src="{{ asset($event->image) }}" alt="{{ $event->title }}" loading="lazy" onerror="this.hidden = true; this.nextElementSibling.hidden = false;">
    @endif
    <div class="event-visual-fallback" @if($event->image && !str_starts_with($event->image, '/images/event-')) hidden @endif aria-hidden="true">
        <i class="fa-solid fa-calendar-days"></i>
        <span>{{ $event->category }}</span>
    </div>
</div>
