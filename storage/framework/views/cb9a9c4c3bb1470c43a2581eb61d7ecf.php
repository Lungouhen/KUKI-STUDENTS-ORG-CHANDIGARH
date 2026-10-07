<?php $__env->startSection('title', 'Payment Gateway Config | KSO Admin'); ?>

<?php $__env->startSection('content'); ?>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-header bg-white p-3 border-0">
        <h5 class="fw-bold mb-0 text-success"><i class="fa-solid fa-credit-card me-2"></i> Payment Gateway & UPI Settings</h5>
    </div>
    <div class="card-body p-4">
        <form action="<?php echo e(route('admin.settings.update')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <div class="row g-3">
                <div class="col-12 border-bottom pb-2 mb-2">
                    <h6 class="fw-bold small mb-0 text-dark">Razorpay Integration</h6>
                </div>
                <div class="col-md-6">
                    <label class="form-label extra-small fw-bold">Razorpay Key ID</label>
                    <input type="text" name="razorpayKey" class="form-control" value="<?php echo e($settings['razorpayKey']); ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label extra-small fw-bold">Razorpay Secret Key</label>
                    <input type="password" name="razorpaySecret" class="form-control" autocomplete="new-password" placeholder="<?php echo e($settings['hasRazorpaySecret'] ? 'Configured; enter a new secret to replace' : 'Enter Razorpay secret'); ?>">
                </div>

                <div class="col-12 border-bottom pb-2 mt-4 mb-2">
                    <h6 class="fw-bold small mb-0 text-dark">UPI & QR Collection</h6>
                </div>
                <div class="col-md-12">
                    <label class="form-label extra-small fw-bold">Primary UPI ID (for QR Generation)</label>
                    <input type="text" name="upiId" class="form-control" value="<?php echo e($settings['upiId']); ?>" placeholder="kso@upi">
                </div>
                
                <div class="col-12 mt-4">
                    <button type="submit" class="btn btn-success px-5 fw-bold shadow text-white">Save Gateway Settings</button>
                </div>
            </div>
        </form>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/work/KUKI-STUDENTS-ORG-CHANDIGARH/KUKI-STUDENTS-ORG-CHANDIGARH/resources/views/admin/settings/gateways.blade.php ENDPATH**/ ?>