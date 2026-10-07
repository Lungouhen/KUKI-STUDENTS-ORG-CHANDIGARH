<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title', 'Kuki Students\' Organisation Chandigarh'); ?></title>
    
    <!-- Local Bootstrap 5 CSS -->
    <link href="<?php echo e(asset('vendor/bootstrap/bootstrap.min.css')); ?>" rel="stylesheet">
    <!-- Local FontAwesome 6 CSS -->
    <link href="<?php echo e(asset('vendor/fontawesome/all.min.css')); ?>" rel="stylesheet">
    <!-- Local SweetAlert2 CSS -->
    <link href="<?php echo e(asset('vendor/sweetalert2/sweetalert2.min.css')); ?>" rel="stylesheet">
    <?php if(request()->routeIs('gallery.index')): ?>
        <link href="<?php echo e(asset('vendor/magnific-popup/magnific-popup.css')); ?>" rel="stylesheet">
    <?php endif; ?>
    <!-- Custom Styles -->
    <link href="<?php echo e(asset('css/custom.css')); ?>" rel="stylesheet">
    <?php if(file_exists(public_path('build/manifest.json'))): ?>
        <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <?php endif; ?>

    <!-- Local Alpine.js -->
    <script defer src="<?php echo e(asset('vendor/alpine/alpine.min.js')); ?>"></script>
    <!-- Local SweetAlert2 JS -->
    <script src="<?php echo e(asset('vendor/sweetalert2/sweetalert2.min.js')); ?>"></script>
    
    <?php echo $__env->yieldPushContent('styles'); ?>
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
    <?php if (isset($component)) { $__componentOriginal57b7ac81b71e7fe2d81fa75baf439455 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal57b7ac81b71e7fe2d81fa75baf439455 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.topbar','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('topbar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal57b7ac81b71e7fe2d81fa75baf439455)): ?>
<?php $attributes = $__attributesOriginal57b7ac81b71e7fe2d81fa75baf439455; ?>
<?php unset($__attributesOriginal57b7ac81b71e7fe2d81fa75baf439455); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal57b7ac81b71e7fe2d81fa75baf439455)): ?>
<?php $component = $__componentOriginal57b7ac81b71e7fe2d81fa75baf439455; ?>
<?php unset($__componentOriginal57b7ac81b71e7fe2d81fa75baf439455); ?>
<?php endif; ?>

    <!-- ─── ANNOUNCEMENT TICKER ─── -->
    <div class="announcement-ticker">
        <div class="container-fluid px-lg-5 d-flex align-items-center">
            <span class="badge bg-dark me-3 px-2 py-1"><i class="fa-solid fa-bullhorn me-1"></i> NOTICE</span>
            <span role="status">
                <?php echo e(\App\Models\Setting::get('announcement', '📢 Welcome to KSO Chandigarh! Annual Membership Registration 2025-2026 is now OPEN. Get your official digital student ID card online!')); ?>

            </span>
        </div>
    </div>

    <!-- ─── MODERN GLASS NAVBAR COMPONENT ─── -->
    <?php if (isset($component)) { $__componentOriginala591787d01fe92c5706972626cdf7231 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala591787d01fe92c5706972626cdf7231 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.navbar','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('navbar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala591787d01fe92c5706972626cdf7231)): ?>
<?php $attributes = $__attributesOriginala591787d01fe92c5706972626cdf7231; ?>
<?php unset($__attributesOriginala591787d01fe92c5706972626cdf7231); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala591787d01fe92c5706972626cdf7231)): ?>
<?php $component = $__componentOriginala591787d01fe92c5706972626cdf7231; ?>
<?php unset($__componentOriginala591787d01fe92c5706972626cdf7231); ?>
<?php endif; ?>

    <!-- ─── MAIN CONTENT ─── -->
    <main id="main-content" tabindex="-1">
        <?php if(session('success')): ?>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: "<?php echo e(session('success')); ?>",
                        confirmButtonColor: '#003566'
                    });
                });
            </script>
        <?php endif; ?>

        <?php if(session('error')): ?>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Notice',
                        text: "<?php echo e(session('error')); ?>",
                        confirmButtonColor: '#003566'
                    });
                });
            </script>
        <?php endif; ?>

        <?php echo $__env->yieldContent('content'); ?>
    </main>

    <!-- ─── FLOATING WHATSAPP BUTTON ─── -->
    <a href="https://wa.me/<?php echo e(preg_replace('/[^0-9]/', '', \App\Models\Setting::get('whatsapp', '919876543210'))); ?>" target="_blank" class="whatsapp-float" title="Chat with KSO Support">
        <i class="fa-brands fa-whatsapp"></i>
    </a>

    <!-- ─── FOOTER COMPONENT ─── -->
    <?php if (isset($component)) { $__componentOriginal8a8716efb3c62a45938aca52e78e0322 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8a8716efb3c62a45938aca52e78e0322 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.footer','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('footer'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8a8716efb3c62a45938aca52e78e0322)): ?>
<?php $attributes = $__attributesOriginal8a8716efb3c62a45938aca52e78e0322; ?>
<?php unset($__attributesOriginal8a8716efb3c62a45938aca52e78e0322); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8a8716efb3c62a45938aca52e78e0322)): ?>
<?php $component = $__componentOriginal8a8716efb3c62a45938aca52e78e0322; ?>
<?php unset($__componentOriginal8a8716efb3c62a45938aca52e78e0322); ?>
<?php endif; ?>

    <!-- Local Bootstrap 5 JS -->
    <script src="<?php echo e(asset('vendor/bootstrap/bootstrap.bundle.min.js')); ?>"></script>
    <?php if(request()->routeIs('home')): ?>
        <script src="<?php echo e(asset('vendor/jquery/jquery.min.js')); ?>"></script>
        <script src="<?php echo e(asset('vendor/waypoints/jquery.waypoints.min.js')); ?>"></script>
        <script src="<?php echo e(asset('vendor/counterup/jquery.counterup.min.js')); ?>"></script>
        <script>
            const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
            if (!reduceMotion) {
                jQuery('.counter').counterUp({ delay: 10, time: 1500, offset: 70, beginAt: 0 });
            }
        </script>
    <?php elseif(request()->routeIs('gallery.index')): ?>
        <script src="<?php echo e(asset('vendor/jquery/jquery.min.js')); ?>"></script>
        <script src="<?php echo e(asset('vendor/imagesloaded/imagesloaded.pkgd.min.js')); ?>"></script>
        <script src="<?php echo e(asset('vendor/magnific-popup/jquery.magnific-popup.min.js')); ?>"></script>
        <script src="<?php echo e(asset('vendor/masonry/masonry.pkgd.min.js')); ?>"></script>
        <script>
            jQuery('.masonry-grid').imagesLoaded(function () {
                jQuery('.masonry-grid').masonry({
                    itemSelector: '.masonry-item',
                    columnWidth: '.masonry-item',
                    percentPosition: true
                });
            });
            jQuery('.popup-gallery').magnificPopup({ type: 'image', gallery: { enabled: true } });
        </script>
    <?php elseif(request()->routeIs('events.index')): ?>
        <script src="<?php echo e(asset('vendor/fullcalendar/fullcalendar.min.js')); ?>"></script>
    <?php endif; ?>

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
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH /home/runner/work/KUKI-STUDENTS-ORG-CHANDIGARH/KUKI-STUDENTS-ORG-CHANDIGARH/resources/views/layouts/app.blade.php ENDPATH**/ ?>