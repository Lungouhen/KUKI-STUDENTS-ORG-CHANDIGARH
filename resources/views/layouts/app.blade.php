<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Kuki Students\' Organisation Chandigarh')</title>
    
    <!-- Local Bootstrap 5 CSS -->
    <link href="{{ asset('vendor/bootstrap/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Local FontAwesome 6 CSS -->
    <link href="{{ asset('vendor/fontawesome/all.min.css') }}" rel="stylesheet">
    <!-- Local Choices.js CSS -->
    <link href="{{ asset('vendor/choices/choices.min.css') }}" rel="stylesheet">
    <!-- Local FilePond CSS -->
    <link href="{{ asset('vendor/filepond/filepond.min.css') }}" rel="stylesheet">
    <!-- Local SweetAlert2 CSS -->
    <link href="{{ asset('vendor/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet">
    <!-- lightGallery CSS -->
    <link href="{{ asset('vendor/lightgallery/lightgallery-bundle.min.css') }}" rel="stylesheet">
    <!-- Magnific Popup CSS -->
    <link href="{{ asset('vendor/magnific-popup/magnific-popup.css') }}" rel="stylesheet">
    <!-- Swiper CSS -->
    <link href="{{ asset('vendor/swiper/swiper-bundle.min.css') }}" rel="stylesheet">
    <!-- Custom Styles & Vite Assets -->
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    @if(file_exists(public_path('build/manifest.json')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    <!-- Local Alpine.js -->
    <script defer src="{{ asset('vendor/alpine/alpine.min.js') }}"></script>
    <!-- Local SweetAlert2 JS -->
    <script src="{{ asset('vendor/sweetalert2/sweetalert2.min.js') }}"></script>
    
    @stack('styles')
</head>
<body x-data="{ 
    mobileMenuOpen: false, 
    darkMode: localStorage.getItem('theme') === 'dark',
    toggleTheme() {
        this.darkMode = !this.darkMode;
        localStorage.setItem('theme', this.darkMode ? 'dark' : 'light');
    }
}" :data-bs-theme="darkMode ? 'dark' : 'light'">

    <!-- ─── TOPBAR COMPONENT ─── -->
    <x-topbar />

    <!-- ─── ANNOUNCEMENT TICKER ─── -->
    <div class="announcement-ticker">
        <div class="container-fluid px-lg-5 d-flex align-items-center">
            <span class="badge bg-dark me-3 px-2 py-1"><i class="fa-solid fa-bullhorn me-1"></i> NOTICE</span>
            <marquee behavior="scroll" direction="left" onmouseover="this.stop();" onmouseout="this.start();">
                {{ \App\Models\Setting::get('announcement', '📢 Welcome to KSO Chandigarh! Annual Membership Registration 2025-2026 is now OPEN. Get your official digital student ID card online!') }}
            </marquee>
        </div>
    </div>

    <!-- ─── MODERN GLASS NAVBAR COMPONENT ─── -->
    <x-navbar />

    <!-- ─── MAIN CONTENT ─── -->
    <main>
        @if(session('success'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: "{{ session('success') }}",
                        confirmButtonColor: '#003566'
                    });
                });
            </script>
        @endif

        @if(session('error'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Notice',
                        text: "{{ session('error') }}",
                        confirmButtonColor: '#003566'
                    });
                });
            </script>
        @endif

        @yield('content')
    </main>

    <!-- ─── FLOATING WHATSAPP BUTTON ─── -->
    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', \App\Models\Setting::get('whatsapp', '919876543210')) }}" target="_blank" class="whatsapp-float" title="Chat with KSO Support">
        <i class="fa-brands fa-whatsapp"></i>
    </a>

    <!-- ─── FOOTER COMPONENT ─── -->
    <x-footer />

    <!-- Local Bootstrap 5 JS -->
    <script src="{{ asset('vendor/bootstrap/bootstrap.bundle.min.js') }}"></script>
    <!-- Local Choices.js JS -->
    <script src="{{ asset('vendor/choices/choices.min.js') }}"></script>
    <!-- Local FilePond JS -->
    <script src="{{ asset('vendor/filepond/filepond.min.js') }}"></script>
    <!-- Local FullCalendar JS -->
    <script src="{{ asset('vendor/fullcalendar/fullcalendar.min.js') }}"></script>

    <!-- jQuery -->
    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <!-- Waypoints (for Counter-Up) -->
    <script src="{{ asset('vendor/waypoints/jquery.waypoints.min.js') }}"></script>
    <!-- Counter-Up -->
    <script src="{{ asset('vendor/counterup/jquery.counterup.min.js') }}"></script>
    <!-- imagesLoaded -->
    <script src="{{ asset('vendor/imagesloaded/imagesloaded.pkgd.min.js') }}"></script>
    <!-- lightGallery -->
    <script src="{{ asset('vendor/lightgallery/lightgallery.min.js') }}"></script>
    <!-- Magnific Popup -->
    <script src="{{ asset('vendor/magnific-popup/jquery.magnific-popup.min.js') }}"></script>
    <!-- Masonry -->
    <script src="{{ asset('vendor/masonry/masonry.pkgd.min.js') }}"></script>
    <!-- Swiper -->
    <script src="{{ asset('vendor/swiper/swiper-bundle.min.js') }}"></script>

    <!-- Plugin Initializations -->
    <script>
        (function () {
            function initPlugins() {
                // Counter-Up (needs jQuery & Waypoints)
                if (window.jQuery && jQuery.fn.counterUp) {
                    jQuery('.counter').counterUp({ delay: 10, time: 1500, offset: 70, beginAt: 0 });
                }

                // Masonry
                if (window.jQuery && jQuery.fn.masonry) {
                    var $masonry = jQuery('.masonry-grid');
                    if ($masonry.length) {
                        $masonry.imagesLoaded(function () {
                            $masonry.masonry({ itemSelector: '.masonry-item', columnWidth: '.masonry-item', percentPosition: true });
                        });
                    }
                } else if (window.Masonry) {
                    var msContainers = document.querySelectorAll('.masonry-grid');
                    msContainers.forEach(function (el) {
                        new Masonry(el, { itemSelector: '.masonry-item', columnWidth: '.masonry-item', percentPosition: true });
                    });
                }

                // lightGallery
                if (window.lightGallery) {
                    var lgContainers = document.querySelectorAll('.lightgallery');
                    lgContainers.forEach(function (el) {
                        lightGallery(el, { selector: 'a', download: false, counter: true });
                    });
                }

                // Magnific Popup
                if (window.jQuery && jQuery.fn.magnificPopup) {
                    jQuery('.popup-gallery').magnificPopup({ type: 'image', gallery: { enabled: true } });
                    jQuery('.popup-single').magnificPopup({ type: 'image' });
                }

                // Swiper
                if (window.Swiper) {
                    document.querySelectorAll('.swiper').forEach(function (el) {
                        new Swiper(el, { loop: true, pagination: { el: '.swiper-pagination', clickable: true }, navigation: { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' }, autoplay: { delay: 2500, disableOnInteraction: false } });
                    });
                }
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initPlugins);
            } else {
                initPlugins();
            }
        })();
    </script>

    <script>
        function confirmDelete(formId, message = 'Are you sure you want to delete this record?') {
            Swal.fire({
                title: 'Confirm Action',
                text: message,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, Delete!'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(formId).submit();
                }
            });
        }
    </script>
    @stack('scripts')
</body>
</html>
