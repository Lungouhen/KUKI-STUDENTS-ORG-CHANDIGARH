# Front-end Libraries

All eight libraries are **self-hosted** under `public/vendor/` — no CDN calls,
consistent with the rest of the project. They are **opt-in per page**, so a view
loads only what it actually uses.

| Library | Version | Path | Used by |
|---|---|---|---|
| jQuery | 3.7.1 | `vendor/jquery/` | Admin CMS; dependency of Magnific Popup, Waypoints |
| Bootstrap | 5.3 (existing) | `vendor/bootstrap/` | Global layout, grid, components |
| CounterUp2 + Waypoints | 2.0.2 / 4.0.1 | `vendor/counterup/` | Homepage statistics |
| imagesLoaded | 5.0.0 | `vendor/imagesloaded/` | Gallery (Masonry re-layout) |
| lightGallery | 2.8.2 | `vendor/lightgallery/` | Gallery, homepage collage, admin gallery |
| Magnific Popup | 1.2.0 | `vendor/magnific-popup/` | Event poster lightbox |
| Masonry | 4.2.2 | `vendor/masonry/` | Gallery grid |
| Swiper | 11.2.10 | `vendor/swiper/` | Hero slider, testimonials, partners |

## How to use them in a view

Two partials do the loading. Request bundles by name:

```blade
@push('styles')
    @include('partials.vendor-styles', ['libs' => ['swiper', 'lightgallery']])
@endpush

@push('scripts')
    @include('partials.vendor-scripts', ['libs' => ['swiper', 'lightgallery']])
@endpush
```

`vendor-scripts` injects jQuery automatically (and first) whenever you request a
jQuery-dependent bundle — `magnific-popup`, `masonry` or `counterup` — and it
always appends `js/kso-ui.js`, which contains the initialisers.

## Markup contracts

Widgets are wired from data-attributes, so views contain no inline `<script>`.
`public/js/kso-ui.js` reads:

| Attribute | Behaviour |
|---|---|
| `data-swiper='{...}'` | Swiper carousel. JSON is merged over the defaults. Pagination/navigation are enabled only if `.swiper-pagination` / `.swiper-button-next` exist inside. |
| `data-counter` | Animates the element's number into view. |
| `data-masonry-grid` | Masonry layout; expects `[data-masonry-item]` children and one `[data-masonry-sizer]`. Re-lays out via imagesLoaded. |
| `data-lightgallery` | Lightbox over `[data-lg-item]` children (thumbnail + zoom plugins). |
| `data-mfp="image"` | Magnific Popup image. Wrap several in `[data-mfp-gallery]` for prev/next. |
| `data-mfp="iframe"` | Magnific Popup video (YouTube/Vimeo). |

`public/js/kso-admin.js` (jQuery, admin only) adds:

| Attribute | Behaviour |
|---|---|
| `data-image-preview="#img"` | Live thumbnail preview before upload. |
| `data-filter-input` + `data-filter-target=".sel"` | Client-side list filtering; updates `[data-filter-count]` and toggles `[data-filter-empty]`. |
| `data-confirm="msg"` | SweetAlert2 confirmation before a destructive submit (falls back to `window.confirm`). |
| `data-slug-source="#title"` | Auto-fills a slug, and stops as soon as the editor types their own. |
| `data-char-count="160"` | Live character counter, turns red past the limit. |

## Design notes

- **Graceful degradation.** Statistics, gallery images and event posters are all
  rendered server-side; the libraries only enhance them. With JS disabled the
  pages remain readable and the gallery still works as plain links.
- **Defensive initialisers.** Each initialiser returns early if its library is
  absent, and `boot()` wraps them individually so one failure cannot stop the
  rest. Malformed JSON in a data-attribute logs a warning instead of throwing.
- **Accessibility.** `prefers-reduced-motion` disables autoplay, counter
  animation and transitions. Carousel controls have `aria-label`s and Swiper's
  a11y/keyboard modules are enabled.
- **CounterUp2 gotcha.** Its UMD build exposes the callable as
  `window.counterUp.default`, not `window.counterUp`. `kso-ui.js` resolves both.

## Updating a library

```bash
npm i <package>@<version>          # in a scratch directory
cp node_modules/<pkg>/dist/<file> public/vendor/<name>/
```

Keep the same filenames so the Blade partials do not need editing, and re-check
that any CSS asset references (lightGallery ships icon fonts under
`vendor/lightgallery/fonts/`) still resolve.
