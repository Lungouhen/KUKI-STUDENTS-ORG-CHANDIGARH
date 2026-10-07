<?php $__env->startSection('title', $page->meta_title ?? $page->title); ?>

<?php $__env->startSection('content'); ?>

<?php if($isPreview ?? false): ?>
    <div class="alert alert-warning rounded-0 mb-0 text-center" role="status">
        Preview only — this page is not publicly available until it is published.
    </div>
<?php endif; ?>

<?php echo $__env->make($page->templateView(), array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/work/KUKI-STUDENTS-ORG-CHANDIGARH/KUKI-STUDENTS-ORG-CHANDIGARH/resources/views/pages/show.blade.php ENDPATH**/ ?>