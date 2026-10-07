<nav class="navbar navbar-expand-xl navbar-modern sticky-top">
    <div class="container-fluid px-lg-5">
        <a class="navbar-brand d-flex align-items-center" href="<?php echo e(route('home')); ?>">
            <img src="<?php echo e(asset('images/kso-logo.jpg')); ?>" alt="KSO Logo" class="me-2 rounded-circle border border-warning" onerror="this.src='/images/default-avatar-m.png'">
            <div>
                <span class="fw-extrabold fs-5 d-block text-dark lh-1">KSO CHANDIGARH</span>
                <small class="text-muted extra-small fw-semibold">Kuki Students' Organisation</small>
            </div>
        </a>
        
        <button class="navbar-toggler collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon" aria-hidden="true"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item">
                    <a class="nav-link <?php echo e(request()->routeIs('home') ? 'active' : ''); ?>" href="<?php echo e(route('home')); ?>"><i class="fa-solid fa-house me-1"></i> Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo e(request()->routeIs('about') ? 'active' : ''); ?>" href="<?php echo e(route('about')); ?>"><i class="fa-solid fa-circle-info me-1"></i> About Us</a>
                </li>
                <li class="nav-item dropdown">
                    <button class="nav-link dropdown-toggle <?php echo e(request()->is('members*') ? 'active' : ''); ?>" type="button" data-bs-toggle="dropdown" aria-expanded="false" aria-controls="membershipDropdownMenu">
                        <i class="fa-solid fa-id-card me-1"></i> Membership <i class="fa-solid fa-chevron-down"></i>
                    </button>
                    <ul class="dropdown-menu" id="membershipDropdownMenu">
                        <li><a class="dropdown-item" href="<?php echo e(route('membership.register')); ?>"><i class="fa-solid fa-user-plus text-teal me-2"></i> Register Member</a></li>
                        <li><a class="dropdown-item" href="<?php echo e(route('membership.verifyForm')); ?>"><i class="fa-solid fa-circle-check text-success me-2"></i> Verify Student ID</a></li>
                        <li><a class="dropdown-item" href="<?php echo e(route('membership.portal')); ?>"><i class="fa-solid fa-right-to-bracket text-primary me-2"></i> Member Portal Login</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo e(request()->routeIs('events.index') ? 'active' : ''); ?>" href="<?php echo e(route('events.index')); ?>"><i class="fa-solid fa-calendar-days me-1"></i> Events & News</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo e(request()->routeIs('gallery.index') ? 'active' : ''); ?>" href="<?php echo e(route('gallery.index')); ?>"><i class="fa-solid fa-images me-1"></i> Gallery</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo e(request()->routeIs('donations.index') ? 'active' : ''); ?>" href="<?php echo e(route('donations.index')); ?>"><i class="fa-solid fa-hand-holding-heart me-1"></i> Support Us</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo e(request()->routeIs('page.faqs') ? 'active' : ''); ?>" href="<?php echo e(route('page.faqs')); ?>"><i class="fa-solid fa-circle-question me-1"></i> FAQs</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo e(request()->routeIs('contact.index') ? 'active' : ''); ?>" href="<?php echo e(route('contact.index')); ?>"><i class="fa-solid fa-envelope me-1"></i> Contact</a>
                </li>
            </ul>

            <div class="navbar-actions d-flex align-items-center gap-2 mt-3 mt-xl-0">
                <a href="<?php echo e(route('donations.index')); ?>" class="btn btn-accent btn-sm rounded-pill px-3 shadow-sm"><i class="fa-solid fa-heart me-1"></i> Donate</a>
                <a href="<?php echo e(route('admin.login')); ?>" class="nav-link-admin"><i class="fa-solid fa-user-shield me-1"></i> CMS Admin</a>
            </div>
        </div>
    </div>
</nav>
<?php /**PATH /home/runner/work/KUKI-STUDENTS-ORG-CHANDIGARH/KUKI-STUDENTS-ORG-CHANDIGARH/resources/views/components/navbar.blade.php ENDPATH**/ ?>