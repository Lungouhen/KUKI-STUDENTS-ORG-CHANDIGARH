<?php $__env->startSection('title', 'SMTP Email Settings | KSO Admin'); ?>

<?php $__env->startSection('content'); ?>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-header bg-white p-3 border-0">
        <h5 class="fw-bold mb-0 text-primary"><i class="fa-solid fa-envelope-circle-check me-2"></i> SMTP Configuration</h5>
    </div>
    <div class="card-body p-4">
        <form action="<?php echo e(route('admin.settings.update')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <div class="row g-3">
                <div class="col-md-8">
                    <label class="form-label extra-small fw-bold">SMTP Host</label>
                    <input type="text" name="mail_host" class="form-control" value="<?php echo e($settings['mail_host']); ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label extra-small fw-bold">SMTP Port</label>
                    <input type="text" name="mail_port" class="form-control" value="<?php echo e($settings['mail_port']); ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label extra-small fw-bold">Username</label>
                    <input type="text" name="mail_username" class="form-control" value="<?php echo e($settings['mail_username']); ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label extra-small fw-bold">Password</label>
                    <input type="password" name="mail_password" class="form-control" autocomplete="new-password" placeholder="<?php echo e($settings['hasMailPassword'] ? 'Configured; enter a new password to replace' : 'Enter SMTP password'); ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label extra-small fw-bold">Encryption</label>
                    <select name="mail_encryption" class="form-select">
                        <option value="tls" <?php echo e($settings['mail_encryption'] == 'tls' ? 'selected' : ''); ?>>TLS</option>
                        <option value="ssl" <?php echo e($settings['mail_encryption'] == 'ssl' ? 'selected' : ''); ?>>SSL</option>
                        <option value="none" <?php echo e($settings['mail_encryption'] == 'none' ? 'selected' : ''); ?>>None</option>
                    </select>
                </div>
                <div class="col-12 mt-4">
                    <button type="submit" class="btn btn-primary px-5 fw-bold shadow">Save SMTP Settings</button>
                </div>
            </div>
        </form>
        <form action="<?php echo e(route('admin.settings.smtp.test')); ?>" method="POST" class="mt-3">
            <?php echo csrf_field(); ?>
            <button type="submit" class="btn btn-outline-secondary px-4">Send Test Mail to My Admin Email</button>
            <p class="form-text mb-0">Save settings first. The test message goes only to your signed-in admin email.</p>
        </form>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/work/KUKI-STUDENTS-ORG-CHANDIGARH/KUKI-STUDENTS-ORG-CHANDIGARH/resources/views/admin/settings/smtp.blade.php ENDPATH**/ ?>