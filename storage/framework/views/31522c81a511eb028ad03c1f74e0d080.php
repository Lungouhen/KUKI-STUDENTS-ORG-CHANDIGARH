<?php $__env->startSection('title', 'Website Settings | KSO CMS'); ?>

<?php $__env->startSection('content'); ?>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-header bg-white p-3">
        <h5 class="fw-bold text-primary mb-0"><i class="fa-solid fa-sliders me-2"></i> Website Global Settings</h5>
    </div>
    <div class="card-body p-4">
        <form action="<?php echo e(route('admin.settings.update')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-bold">Site Title</label>
                    <input type="text" class="form-control" name="siteName" value="<?php echo e($settings['siteName']); ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">Tagline</label>
                    <input type="text" class="form-control" name="tagline" value="<?php echo e($settings['tagline']); ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">Official Email</label>
                    <input type="email" class="form-control" name="email" value="<?php echo e($settings['email']); ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">Phone Number</label>
                    <input type="text" class="form-control" name="phone" value="<?php echo e($settings['phone']); ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">Emergency Helpline</label>
                    <input type="text" class="form-control" name="helpline" value="<?php echo e($settings['helpline']); ?>">
                </div>
                <div class="col-12">
                    <label class="form-label fw-bold">Announcement Banner Text</label>
                    <input type="text" class="form-control" name="announcement" value="<?php echo e($settings['announcement']); ?>">
                </div>
                <div class="col-12">
                    <label class="form-label fw-bold">Official Address</label>
                    <input type="text" class="form-control" name="address" value="<?php echo e($settings['address']); ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">UPI ID for Donations</label>
                    <input type="text" class="form-control" name="upiId" value="<?php echo e($settings['upiId']); ?>">
                </div>

                <div class="col-12 border-top pt-3 mt-4">
                    <h6 class="fw-bold text-dark"><i class="fa-solid fa-palette me-1"></i> Branding & Custom Identity</h6>
                </div>
                <div class="col-md-4">
                    <label class="form-label extra-small fw-bold">Primary Color (Hex)</label>
                    <input type="color" class="form-control form-control-color w-100" name="primaryColor" value="<?php echo e($settings['primaryColor']); ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label extra-small fw-bold">Accent Color (Hex)</label>
                    <input type="color" class="form-control form-control-color w-100" name="accentColor" value="<?php echo e($settings['accentColor']); ?>">
                </div>

                <div class="col-12 border-top pt-3 mt-4">
                    <h6 class="fw-bold text-dark"><i class="fa-solid fa-barcode me-1"></i> ID Prefix Configurations</h6>
                </div>
                <div class="col-md-3">
                    <label class="form-label extra-small fw-bold">Member ID Prefix</label>
                    <input type="text" class="form-control" name="memberPrefix" value="<?php echo e($settings['memberPrefix']); ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label extra-small fw-bold">Donor ID Prefix</label>
                    <input type="text" class="form-control" name="donorPrefix" value="<?php echo e($settings['donorPrefix']); ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label extra-small fw-bold">Beneficiary ID Prefix</label>
                    <input type="text" class="form-control" name="beneficiaryPrefix" value="<?php echo e($settings['beneficiaryPrefix']); ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label extra-small fw-bold">Project ID Prefix</label>
                    <input type="text" class="form-control" name="projectPrefix" value="<?php echo e($settings['projectPrefix']); ?>">
                </div>

                <div class="col-12 border-top pt-3 mt-4">
                    <h6 class="fw-bold text-dark"><i class="fa-solid fa-map-location-dot me-1"></i> Location & Map Settings</h6>
                </div>
                <div class="col-12">
                    <label class="form-label extra-small fw-bold">Google Maps Embed URL</label>
                    <input type="text" class="form-control" name="mapEmbedUrl" value="<?php echo e($settings['mapEmbedUrl'] ?? ''); ?>">
                </div>

                <div class="col-12 border-top pt-3 mt-4">
                    <h6 class="fw-bold text-dark"><i class="fa-solid fa-chart-line me-1"></i> Public Statistics (Home Page)</h6>
                </div>
                <div class="col-md-4">
                    <label class="form-label extra-small fw-bold">Base Member Count (Fallback)</label>
                    <input type="number" class="form-control" name="baseMemberCount" value="<?php echo e($settings['baseMemberCount']); ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label extra-small fw-bold">Colleges/Institutions Count</label>
                    <input type="number" class="form-control" name="collegesCount" value="<?php echo e($settings['collegesCount']); ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label extra-small fw-bold">Base Event Count (Fallback)</label>
                    <input type="number" class="form-control" name="baseEventCount" value="<?php echo e($settings['baseEventCount']); ?>">
                </div>

                <div class="col-12 border-top pt-3 mt-4">
                    <h6 class="fw-bold text-dark"><i class="fa-solid fa-key me-1"></i> Payment Gateway API Keys (Razorpay)</h6>
                </div>
                <div class="col-md-6">
                    <label class="form-label extra-small fw-bold">Razorpay Key ID</label>
                    <input type="password" class="form-control" name="razorpayKey" value="<?php echo e($settings['razorpayKey']); ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label extra-small fw-bold">Razorpay Key Secret</label>
                    <input type="password" class="form-control" name="razorpaySecret" autocomplete="new-password" placeholder="<?php echo e($settings['hasRazorpaySecret'] ? 'Configured; enter a new secret to replace' : 'Enter Razorpay secret'); ?>">
                </div>

                <div class="col-12 border-top pt-3 mt-4">
                    <h6 class="fw-bold text-dark"><i class="fa-solid fa-share-nodes me-1"></i> Social Media & Helpline Links</h6>
                </div>
                <div class="col-md-4">
                    <label class="form-label extra-small fw-bold">Facebook URL</label>
                    <input type="text" class="form-control" name="facebook" value="<?php echo e($settings['facebook'] ?? ''); ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label extra-small fw-bold">Instagram URL</label>
                    <input type="text" class="form-control" name="instagram" value="<?php echo e($settings['instagram'] ?? ''); ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label extra-small fw-bold">WhatsApp Number</label>
                    <input type="text" class="form-control" name="whatsapp" value="<?php echo e($settings['whatsapp'] ?? ''); ?>">
                </div>

                <div class="col-12 text-end mt-4">
                    <button type="submit" class="btn btn-accent px-5 fw-bold shadow"><i class="fa-solid fa-floppy-disk me-1"></i> Save Settings</button>
                </div>
            </div>
        </form>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/work/KUKI-STUDENTS-ORG-CHANDIGARH/KUKI-STUDENTS-ORG-CHANDIGARH/resources/views/admin/settings/index.blade.php ENDPATH**/ ?>