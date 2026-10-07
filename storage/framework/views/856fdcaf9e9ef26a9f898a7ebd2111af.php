<?php $__env->startSection('title', 'Verify Student ID Card | KSO Chandigarh'); ?>

<?php $__env->startSection('content'); ?>

<div class="public-page-banner bg-primary text-white py-4 mb-4">
    <div class="container text-center">
        <h2 class="fw-black mb-1"><i class="fa-solid fa-qrcode me-2"></i> Student ID Card Verification Portal</h2>
        <p class="small text-light opacity-90 mb-0">Verify official KSO Chandigarh membership credentials instantly</p>
    </div>
</div>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-sm border-0 rounded-4 p-4 text-center">
                <div class="icon-circle bg-primary-lt mx-auto mb-3 text-primary fs-2">
                    <i class="fa-solid fa-shield-halved"></i>
                </div>
                <h4 class="fw-bold text-dark mb-3">Verify KSO Member ID</h4>
                <p class="text-muted extra-small mb-4">Enter the 16-character Membership ID printed on the ID Card (e.g. <code>KSO-CHD-2026-0001</code>)</p>
                
                <form action="<?php echo e(route('membership.verify')); ?>" method="POST" class="mb-3">
                    <?php echo csrf_field(); ?>
                    <div class="input-group input-group-lg mb-3">
                        <input type="text" name="member_id" class="form-control text-uppercase fw-bold" placeholder="KSO-CHD-2026-0001" value="<?php echo e($id ?? ''); ?>" required>
                        <button class="btn btn-accent px-4 fw-bold" type="submit">Verify</button>
                    </div>
                </form>

                <?php if(isset($member)): ?>
                    <div class="mt-4">
                        <div class="alert <?php echo e($member->status === 'Approved' ? 'alert-success border-success' : 'alert-warning border-warning'); ?> text-start rounded-4 shadow-sm p-3">
                            <div class="d-flex align-items-center mb-3">
                                <img src="<?php echo e(asset($member->photo)); ?>" class="rounded-circle me-3 border border-2" width="60" height="60" style="object-fit:cover;" onerror="this.src='/images/default-avatar-m.png'">
                                <div>
                                    <span class="badge <?php echo e($member->status === 'Approved' ? 'bg-success' : 'bg-warning text-dark'); ?> fw-bold mb-1">
                                        <i class="fa-solid <?php echo e($member->status === 'Approved' ? 'fa-circle-check' : 'fa-clock'); ?> me-1"></i> STATUS: <?php echo e(strtoupper($member->status)); ?>

                                    </span>
                                    <h5 class="fw-bold mb-0 text-dark"><?php echo e($member->full_name); ?></h5>
                                    <small class="text-muted"><?php echo e($member->id); ?></small>
                                </div>
                            </div>
                            <table class="table table-sm table-borderless mb-0 extra-small">
                                <tr><td class="fw-bold text-muted">College:</td><td><?php echo e($member->institution); ?></td></tr>
                                <tr><td class="fw-bold text-muted">Course:</td><td><?php echo e($member->course); ?> (<?php echo e($member->year_of_study); ?>)</td></tr>
                                <tr><td class="fw-bold text-muted">Membership:</td><td><?php echo e($member->membership_type); ?></td></tr>
                                <tr><td class="fw-bold text-muted">Valid Until:</td><td class="fw-bold text-primary"><?php echo e($member->valid_until ? $member->valid_until->format('Y-m-d') : '2027-06-30'); ?></td></tr>
                            </table>
                        </div>
                    </div>
                <?php elseif(isset($id)): ?>
                    <div class="alert alert-danger text-center rounded-4 shadow-sm p-3 mt-4">
                        <i class="fa-solid fa-circle-xmark fs-2 text-danger mb-2"></i>
                        <h6 class="fw-bold mb-1">Verification Failed</h6>
                        <p class="extra-small mb-0">No active student record found matching ID: <code><?php echo e($id); ?></code></p>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/work/KUKI-STUDENTS-ORG-CHANDIGARH/KUKI-STUDENTS-ORG-CHANDIGARH/resources/views/membership/verify.blade.php ENDPATH**/ ?>