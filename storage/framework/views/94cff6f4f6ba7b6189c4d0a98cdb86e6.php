<?php $__env->startSection('title', 'Page Not Found | KSO Chandigarh'); ?>

<?php $__env->startSection('content'); ?>
<div class="container d-flex flex-column align-items-center justify-content-center" style="min-height: 70vh; text-align: center;">
    <div class="display-1 fw-black text-primary mb-3" style="font-size: 8rem; letter-spacing: -0.05em;">404</div>
    <h2 class="fw-black mb-2">Page Not Found</h2>
    <p class="text-muted mb-4">The page you are looking for does not exist or has been moved.</p>
    <a href="<?php echo e(url('/')); ?>" class="btn btn-primary rounded-pill px-4">Go Home</a>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/work/KUKI-STUDENTS-ORG-CHANDIGARH/KUKI-STUDENTS-ORG-CHANDIGARH/resources/views/errors/404.blade.php ENDPATH**/ ?>