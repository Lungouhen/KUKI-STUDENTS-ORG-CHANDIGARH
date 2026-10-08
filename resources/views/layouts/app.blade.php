<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @php
        $seoSiteName = \App\Models\Setting::get('siteName') ?: 'Kuki Students\' Organisation Chandigarh';
        $seoDefaultTitle = \App\Models\Setting::get('seoTitle') ?: $seoSiteName;
        $seoDefaultDescription = \App\Models\Setting::get('seoDescription') ?: \App\Models\Setting::get('tagline', 'Empowering Students • Preserving Culture • Serving Community');
        $seoDefaultImagePath = \App\Models\Setting::get('seoSocialImage');
        $seoDefaultImage = $seoDefaultImagePath ? asset($seoDefaultImagePath) : '';
        $seoIndexingEnabled = filter_var(\App\Models\Setting::get('seoIndexingEnabled', true), FILTER_VALIDATE_BOOLEAN);
        $seoPublicRoute = request()->routeIs('home', 'about', 'page.show', 'page.faqs', 'events.index', 'events.show', 'gallery.index', 'donations.index', 'contact.index');
        $seoCanonicalPath = trim(request()->path(), '/');
        $seoCanonicalUrl = rtrim(config('app.url'), '/').($seoCanonicalPath === '' ? '' : '/'.$seoCanonicalPath);
    @endphp
    <title>@yield('title', $seoDefaultTitle)</title>
    <meta name="description" content="@yield('meta_description', $seoDefaultDescription)">
    <meta name="robots" content="@yield('robots', $seoIndexingEnabled && $seoPublicRoute ? 'index,follow' : 'noindex,nofollow')">
    @if($seoPublicRoute)
        <link rel="canonical" href="{{ $seoCanonicalUrl }}">
    @endif
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:site_name" content="{{ $seoSiteName }}">
    <meta property="og:title" content="@yield('title', $seoDefaultTitle)">
    <meta property="og:description" content="@yield('meta_description', $seoDefaultDescription)">
    @if($seoPublicRoute)
        <meta property="og:url" content="{{ $seoCanonicalUrl }}">
    @endif
    @php($seoImage = $__env->yieldContent('og_image', $seoDefaultImage))
    @if(filled($seoImage))
        <meta property="og:image" content="{{ $seoImage }}">
        <meta name="twitter:card" content="summary_large_image">
    @else
        <meta name="twitter:card" content="summary">
    @endif
    <meta name="twitter:title" content="@yield('title', $seoDefaultTitle)">
    <meta name="twitter:description" content="@yield('meta_description', $seoDefaultDescription)">
    
    <!-- Local Bootstrap 5 CSS -->
    <link href="{{ asset('vendor/bootstrap/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Local FontAwesome 6 CSS -->
    <link href="{{ asset('vendor/fontawesome/all.min.css') }}" rel="stylesheet">
    <!-- Local SweetAlert2 CSS -->
    <link href="{{ asset('vendor/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet">
    @if(request()->routeIs('gallery.index'))
        <link href="{{ asset('vendor/magnific-popup/magnific-popup.css') }}" rel="stylesheet">
    @endif
    @if(request()->routeIs('home'))
        <link href="{{ asset('css/home.css') }}" rel="stylesheet">
    @endif
    <!-- Custom Styles -->
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    @if(request()->routeIs('about'))
        <link href="{{ asset('css/pages/about.css') }}" rel="stylesheet">
    @endif
    @if(request()->routeIs('contact.index'))
        <link href="{{ asset('css/pages/contact.css') }}" rel="stylesheet">
    @endif
    @if(request()->routeIs('donations.index'))
        <link href="{{ asset('css/pages/donations.css') }}" rel="stylesheet">
    @endif
    @if(request()->routeIs('admin.donations.receipt'))
        <link href="{{ asset('css/pages/admin-donation-receipt.css') }}" rel="stylesheet">
    @endif
    @if(request()->routeIs('gallery.index'))
        <link href="{{ asset('css/pages/gallery.css') }}" rel="stylesheet">
    @endif
    @if(request()->routeIs('events.index'))
        <link href="{{ asset('css/pages/events.css') }}" rel="stylesheet">
    @endif
    @if(request()->routeIs('events.show'))
        <link href="{{ asset('css/pages/event-detail.css') }}" rel="stylesheet">
    @endif
    @if(request()->routeIs('events.ticketPass'))
        <link href="{{ asset('css/pages/event-pass.css') }}" rel="stylesheet">
    @endif
    @if(request()->routeIs('page.faqs'))
        <link href="{{ asset('css/pages/faqs.css') }}" rel="stylesheet">
    @endif
    @if(request()->routeIs('page.show'))
        <link href="{{ asset('css/pages/cms-page.css') }}" rel="stylesheet">
    @endif
    @if(request()->routeIs('membership.register'))
        <link href="{{ asset('css/pages/membership-register.css') }}" rel="stylesheet">
    @endif
    @if(request()->routeIs('membership.portal'))
        <link href="{{ asset('css/pages/member-login.css') }}" rel="stylesheet">
    @endif
    @if(request()->routeIs('membership.verifyForm', 'membership.verify', 'membership.verifyDirect'))
        <link href="{{ asset('css/pages/member-verification.css') }}" rel="stylesheet">
    @endif
    @if(request()->routeIs('membership.portalDashboard'))
        <link href="{{ asset('css/pages/member-dashboard.css') }}" rel="stylesheet">
    @endif
    @if(request()->routeIs('membership.idCard'))
        <link href="{{ asset('css/pages/member-id-card.css') }}" rel="stylesheet">
    @endif
    @if(request()->routeIs('admin.login'))
        <link href="{{ asset('css/pages/admin-login.css') }}" rel="stylesheet">
    @endif
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

    <a class="skip-link" href="#main-content">Skip to main content</a>

    <!-- ─── TOPBAR COMPONENT ─── -->
    <x-topbar />

    <!-- ─── ANNOUNCEMENT TICKER ─── -->
    <div class="announcement-ticker">
        <div class="container-fluid px-lg-5 d-flex align-items-center">
            <span class="badge bg-dark me-3 px-2 py-1"><i class="fa-solid fa-bullhorn me-1"></i> NOTICE</span>
            <span role="status">
                {{ \App\Models\Setting::get('announcement', '📢 Welcome to KSO Chandigarh! Annual Membership Registration 2025-2026 is now OPEN. Get your official digital student ID card online!') }}
            </span>
        </div>
    </div>

    <!-- ─── MODERN GLASS NAVBAR COMPONENT ─── -->
    <x-navbar />

    <!-- ─── MAIN CONTENT ─── -->
    <main id="main-content" tabindex="-1">
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
    @if(request()->routeIs('home'))
        <script src="{{ asset('js/home.js') }}" defer></script>
    @elseif(request()->routeIs('contact.index'))
        <script src="{{ asset('js/pages/contact.js') }}" defer></script>
    @elseif(request()->routeIs('donations.index'))
        <script src="{{ asset('js/pages/donations.js') }}" defer></script>
    @elseif(request()->routeIs('gallery.index'))
        <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
        <script src="{{ asset('vendor/imagesloaded/imagesloaded.pkgd.min.js') }}"></script>
        <script src="{{ asset('vendor/magnific-popup/jquery.magnific-popup.min.js') }}"></script>
        <script src="{{ asset('vendor/masonry/masonry.pkgd.min.js') }}"></script>
        <script src="{{ asset('js/pages/gallery.js') }}" defer></script>
    @elseif(request()->routeIs('events.index'))
        <script src="{{ asset('vendor/fullcalendar/fullcalendar.min.js') }}"></script>
        <script src="{{ asset('js/pages/events.js') }}" defer></script>
    @elseif(request()->routeIs('events.show'))
        <script src="{{ asset('js/pages/event-detail.js') }}" defer></script>
    @elseif(request()->routeIs('events.ticketPass'))
        <script src="{{ asset('js/pages/event-pass.js') }}" defer></script>
    @elseif(request()->routeIs('membership.register'))
        <script src="{{ asset('js/pages/membership-register.js') }}" defer></script>
    @elseif(request()->routeIs('membership.portal'))
        <script src="{{ asset('js/pages/member-login.js') }}" defer></script>
    @elseif(request()->routeIs('membership.verifyForm', 'membership.verify', 'membership.verifyDirect'))
        <script src="{{ asset('js/pages/member-verification.js') }}" defer></script>
    @elseif(request()->routeIs('membership.portalDashboard'))
        <script src="{{ asset('js/pages/member-dashboard.js') }}" defer></script>
    @elseif(request()->routeIs('membership.idCard'))
        <script src="{{ asset('js/pages/member-id-card.js') }}" defer></script>
    @elseif(request()->routeIs('admin.login'))
        <script src="{{ asset('js/pages/admin-login.js') }}" defer></script>
    @elseif(request()->routeIs('admin.donations.receipt'))
        <script src="{{ asset('js/pages/admin-donation-receipt.js') }}" defer></script>
    @endif

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
