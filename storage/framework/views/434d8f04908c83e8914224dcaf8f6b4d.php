<?php $__env->startSection('title', 'Medical Emergency Relief Desk | KSO CMS'); ?>

<?php $__env->startSection('content'); ?>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-header bg-white p-3">
        <h5 class="fw-bold text-danger mb-0"><i class="fa-solid fa-notes-medical me-2"></i> Medical Emergency Relief Applications</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 extra-small">
                <thead class="table-light">
                    <tr>
                        <th>Member ID</th>
                        <th>Patient Name</th>
                        <th>Hospital</th>
                        <th>Nature of Illness</th>
                        <th>Requested Amount</th>
                        <th>Approved Amount</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $claims; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="fw-bold text-primary"><?php echo e($c->member_id); ?></td>
                            <td class="fw-bold text-dark"><?php echo e($c->patient_name); ?></td>
                            <td><?php echo e($c->hospital_name); ?></td>
                            <td><?php echo e($c->nature_of_illness); ?></td>
                            <td class="fw-bold text-primary">₹<?php echo e(number_format($c->amount_requested)); ?></td>
                            <td class="fw-bold text-success">₹<?php echo e(number_format($c->amount_approved)); ?></td>
                            <td><span class="badge <?php echo e($c->status === 'Disbursed' ? 'bg-success' : ($c->status === 'Pending' ? 'bg-warning text-dark' : 'bg-secondary')); ?>"><?php echo e($c->status); ?></span></td>
                            <td>
                                <form action="<?php echo e(route('admin.medical.updateStatus', $c->id)); ?>" method="POST" class="d-inline-flex gap-1">
                                    <?php echo csrf_field(); ?>
                                    <input type="number" name="amount_approved" value="<?php echo e(in_array($c->status, ['Approved', 'Disbursed'], true) ? $c->amount_approved : $c->amount_requested); ?>" min="0.01" max="<?php echo e($c->amount_requested); ?>" step="0.01" <?php echo e(in_array($c->status, ['Approved', 'Disbursed'], true) ? 'readonly' : ''); ?> class="form-control form-control-sm" style="width:90px;">
                                    <select name="status" class="form-select form-select-sm" style="width:110px;">
                                        <option value="Pending" <?php echo e($c->status == 'Pending' ? 'selected' : ''); ?>>Pending</option>
                                        <option value="Approved" <?php echo e($c->status == 'Approved' ? 'selected' : ''); ?>>Approved</option>
                                        <option value="Disbursed" <?php echo e($c->status == 'Disbursed' ? 'selected' : ''); ?>>Disbursed</option>
                                        <option value="Rejected" <?php echo e($c->status == 'Rejected' ? 'selected' : ''); ?>>Rejected</option>
                                    </select>
                                    <button class="btn btn-sm btn-primary py-0">Save</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="8" class="text-center py-4 text-muted">No active medical relief claims.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="p-3">
            <?php echo e($claims->links()); ?>

        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/work/KUKI-STUDENTS-ORG-CHANDIGARH/KUKI-STUDENTS-ORG-CHANDIGARH/resources/views/admin/medical/index.blade.php ENDPATH**/ ?>