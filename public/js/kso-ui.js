/*!
 * KSO Chandigarh — shared front-end behaviour
 * ---------------------------------------------------------------------------
 * Every widget is wired declaratively from data-attributes, so Blade templates
 * stay free of inline <script> blocks and each library is initialised in
 * exactly one place.
 *
 * Each initialiser is defensive: if its library was not loaded on the current
 * page (they are opt-in per view), it simply returns instead of throwing and
 * killing the rest of this file.
 *
 *   [data-swiper]        Swiper carousel; options read from the attribute JSON
 *   [data-counter]       CounterUp2 + Waypoints animated statistic
 *   [data-masonry-grid]  Masonry, laid out after imagesLoaded resolves
 *   [data-lightgallery]  lightGallery with thumbnail + zoom plugins
 *   [data-mfp="image"]   Magnific Popup single image / gallery
 *   [data-mfp="iframe"]  Magnific Popup video (YouTube / Vimeo)
 */
(function () {
    'use strict';

    /** Safely parse a JSON data-attribute, returning {} on malformed input. */
    function readOptions(el, attr) {
        var raw = el.getAttribute(attr);
        if (!raw) return {};
        try {
            return JSON.parse(raw);
        } catch (e) {
            console.warn('[kso-ui] Invalid JSON in ' + attr + ' on', el, e);
            return {};
        }
    }

    function prefersReducedMotion() {
        return window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    }

    /* ─── Swiper carousels ────────────────────────────────────────────── */
    function initSwipers() {
        var nodes = document.querySelectorAll('[data-swiper]');
        if (!nodes.length) return;
        if (typeof window.Swiper === 'undefined') {
            console.warn('[kso-ui] Swiper markup present but the library was not loaded.');
            return;
        }

        nodes.forEach(function (el) {
            var opts = readOptions(el, 'data-swiper');

            var config = Object.assign({
                slidesPerView: 1,
                spaceBetween: 24,
                loop: el.querySelectorAll('.swiper-slide').length > 1,
                grabCursor: true,
                a11y: { enabled: true },
                keyboard: { enabled: true }
            }, opts);

            // Respect the OS "reduce motion" setting for auto-advancing slides.
            if (prefersReducedMotion()) {
                config.autoplay = false;
                config.speed = 0;
            }

            // Wire controls only when the markup actually contains them.
            if (el.querySelector('.swiper-pagination') && config.pagination !== false) {
                config.pagination = Object.assign(
                    { el: el.querySelector('.swiper-pagination'), clickable: true },
                    config.pagination || {}
                );
            }
            if (el.querySelector('.swiper-button-next')) {
                config.navigation = Object.assign({
                    nextEl: el.querySelector('.swiper-button-next'),
                    prevEl: el.querySelector('.swiper-button-prev')
                }, config.navigation || {});
            }

            new window.Swiper(el, config);
        });
    }

    /* ─── Animated counters ───────────────────────────────────────────── */
    function initCounters() {
        var nodes = document.querySelectorAll('[data-counter]');
        if (!nodes.length) return;

        // The CounterUp2 UMD build exposes the callable as `counterUp.default`
        // (an ES-module interop wrapper), not as `counterUp` itself.
        var counterUp = window.counterUp;
        if (counterUp && typeof counterUp !== 'function' && typeof counterUp.default === 'function') {
            counterUp = counterUp.default;
        }

        if (typeof counterUp !== 'function') {
            console.warn('[kso-ui] Counter markup present but CounterUp2 was not loaded.');
            return;
        }

        // Counting up is decorative; show the final value immediately instead.
        if (prefersReducedMotion()) return;

        var run = function (el) {
            counterUp(el, {
                duration: parseInt(el.getAttribute('data-counter-duration'), 10) || 1200,
                delay: parseInt(el.getAttribute('data-counter-delay'), 10) || 16
            });
        };

        // Waypoints ships as a jQuery plugin here, so prefer it when available
        // and fall back to IntersectionObserver otherwise.
        var $ = window.jQuery;
        if ($ && typeof window.Waypoint !== 'undefined') {
            nodes.forEach(function (el) {
                new window.Waypoint({
                    element: el,
                    offset: 'bottom-in-view',
                    handler: function () {
                        run(el);
                        this.destroy();
                    }
                });
            });
            return;
        }

        if ('IntersectionObserver' in window) {
            var io = new IntersectionObserver(function (entries, obs) {
                entries.forEach(function (entry) {
                    if (!entry.isIntersecting) return;
                    run(entry.target);
                    obs.unobserve(entry.target);
                });
            }, { threshold: 0.4 });
            nodes.forEach(function (el) { io.observe(el); });
        } else {
            nodes.forEach(run);
        }
    }

    /* ─── Masonry grids ───────────────────────────────────────────────── */
    function initMasonry() {
        var grids = document.querySelectorAll('[data-masonry-grid]');
        if (!grids.length) return;
        if (typeof window.Masonry === 'undefined') {
            console.warn('[kso-ui] Masonry markup present but the library was not loaded.');
            return;
        }

        grids.forEach(function (grid) {
            var opts = readOptions(grid, 'data-masonry-grid');

            var msnry = new window.Masonry(grid, Object.assign({
                itemSelector: '[data-masonry-item]',
                columnWidth: '[data-masonry-sizer]',
                percentPosition: true,
                transitionDuration: prefersReducedMotion() ? 0 : '0.3s'
            }, opts));

            // Images have no intrinsic height until decoded, so re-layout once
            // they finish — otherwise tiles overlap on first paint.
            if (typeof window.imagesLoaded !== 'undefined') {
                window.imagesLoaded(grid, function () {
                    msnry.layout();
                    grid.classList.add('is-laid-out');
                });
                window.imagesLoaded(grid).on('progress', function () {
                    msnry.layout();
                });
            } else {
                grid.classList.add('is-laid-out');
            }
        });
    }

    /* ─── lightGallery ────────────────────────────────────────────────── */
    function initLightGallery() {
        var nodes = document.querySelectorAll('[data-lightgallery]');
        if (!nodes.length) return;
        if (typeof window.lightGallery === 'undefined') {
            console.warn('[kso-ui] lightGallery markup present but the library was not loaded.');
            return;
        }

        var plugins = [];
        if (typeof window.lgThumbnail !== 'undefined') plugins.push(window.lgThumbnail);
        if (typeof window.lgZoom !== 'undefined') plugins.push(window.lgZoom);

        nodes.forEach(function (el) {
            var opts = readOptions(el, 'data-lightgallery');

            window.lightGallery(el, Object.assign({
                plugins: plugins,
                selector: '[data-lg-item]',
                speed: prefersReducedMotion() ? 0 : 400,
                download: false,
                thumbnail: true,
                zoom: true
            }, opts));
        });
    }

    /* ─── Magnific Popup ──────────────────────────────────────────────── */
    function initMagnificPopup() {
        var $ = window.jQuery;
        if (!$ || !$.fn || !$.fn.magnificPopup) {
            if (document.querySelector('[data-mfp]')) {
                console.warn('[kso-ui] Magnific Popup markup present but the plugin was not loaded.');
            }
            return;
        }

        // Grouped image galleries: every [data-mfp="image"] inside a shared parent.
        $('[data-mfp-gallery]').each(function () {
            $(this).magnificPopup({
                delegate: '[data-mfp="image"]',
                type: 'image',
                gallery: { enabled: true, navigateByImgClick: true },
                image: { titleSrc: 'data-mfp-title' },
                mainClass: 'mfp-fade'
            });
        });

        // Standalone images not inside a gallery wrapper.
        $('[data-mfp="image"]').not('[data-mfp-gallery] [data-mfp="image"]').magnificPopup({
            type: 'image',
            image: { titleSrc: 'data-mfp-title' },
            mainClass: 'mfp-fade'
        });

        // Embedded video (YouTube / Vimeo).
        $('[data-mfp="iframe"]').magnificPopup({
            type: 'iframe',
            mainClass: 'mfp-fade',
            removalDelay: 200
        });
    }

    /* ─── Boot ────────────────────────────────────────────────────────── */
    function boot() {
        // Isolate failures so one broken widget cannot stop the others.
        [initSwipers, initCounters, initMasonry, initLightGallery, initMagnificPopup]
            .forEach(function (fn) {
                try { fn(); } catch (e) { console.error('[kso-ui] ' + fn.name + ' failed:', e); }
            });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', boot);
    } else {
        boot();
    }
})();
