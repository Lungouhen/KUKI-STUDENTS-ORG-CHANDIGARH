<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Kuki Students\' Organisation Chandigarh')</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome 6 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <!-- Choices.js CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />
    <!-- FilePond CSS -->
    <link href="https://unpkg.com/filepond@^4/dist/filepond.css" rel="stylesheet" />
    <!-- FullCalendar CSS -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/main.min.css" rel="stylesheet" />
    <!-- Custom Laravel Asset Styles & Vite Bundle -->
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    @if(file_exists(public_path('build/manifest.json')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    <!-- Alpine.js CDN -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    @stack('styles')
</head>
<body x-data="{ mobileMenuOpen: false }">

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

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Choices.js JS -->
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
    <!-- FilePond JS -->
    <script src="https://unpkg.com/filepond@^4/dist/filepond.js"></script>
    <!-- FullCalendar JS -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
    <!-- Stripe.js SDK -->
    <script src="https://js.stripe.com/v3/"></script>
    <!-- Razorpay Checkout SDK -->
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>

    <script>
        // Global SweetAlert2 delete prompt helper
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
