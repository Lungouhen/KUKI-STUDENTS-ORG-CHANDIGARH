<?php $__env->startSection('title', 'Photo Gallery | KSO Chandigarh'); ?>

<?php $__env->startSection('content'); ?>

<div class="public-page-banner bg-primary text-white py-4 mb-4">
    <div class="container text-center">
        <h2 class="fw-black mb-1">Photo Gallery</h2>
        <p class="small text-light opacity-90 mb-0">Capturing moments of student life, cultural festivals, and community service</p>
    </div>
</div>

<div class="container my-4">
    <div class="masonry-grid" id="galleryGrid">
        <?php $__empty_1 = true; $__currentLoopData = $gallery; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $g): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="masonry-item">
                <figure class="community-photo-card gallery-photo-card mb-0">
                    <a href="<?php echo e(asset($g->image_url)); ?>" class="popup-gallery d-block" title="<?php echo e($g->title); ?>">
                        <img src="<?php echo e(asset($g->image_url)); ?>" class="gal-img w-100" alt="<?php echo e($g->caption ?: $g->title); ?>" loading="lazy" onerror="this.hidden = true; this.nextElementSibling.hidden = false;">
                        <div class="community-photo-fallback" hidden aria-hidden="true"><i class="fa-solid fa-users"></i><span>Community moments</span></div>
                    </a>
                    <figcaption><span><?php echo e($g->category); ?></span><strong><?php echo e($g->title); ?></strong></figcaption>
                </figure>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="col-12">
                <div class="gallery-empty-state">
                    <i class="fa-regular fa-images" aria-hidden="true"></i>
                    <h3>Community photos are on the way</h3>
                    <p class="mb-0">This gallery will feature moments shared by KSO Chandigarh students and organizers.</p>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/work/KUKI-STUDENTS-ORG-CHANDIGARH/KUKI-STUDENTS-ORG-CHANDIGARH/resources/views/gallery/index.blade.php ENDPATH**/ ?>