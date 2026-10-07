<?php $__env->startSection('title', 'Support KSO Student Welfare Fund | KSO Chandigarh'); ?>

<?php $__env->startSection('content'); ?>

<div class="public-page-banner bg-primary text-white py-4 mb-4">
    <div class="container text-center">
        <h2 class="fw-black mb-1"><i class="fa-solid fa-hand-holding-heart me-2"></i> Support KSO Student Welfare Fund</h2>
        <p class="small text-light opacity-90 mb-0">Your generous contributions help fund student medical emergencies, books, and cultural events.</p>
    </div>
</div>

<div class="container my-5">
    <div class="row g-5">
        <div class="col-lg-7">
            <div class="bg-white p-4 p-md-5 rounded-4 shadow-sm border">
                <h4 class="fw-bold text-primary mb-3">Make a Contribution</h4>
                
                <form action="<?php echo e(route('donations.store')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="mb-4">
                        <label class="form-label fw-bold">Select Welfare Cause</label>
                        <select class="form-select" name="cause" required>
                            <option value="Student Emergency Welfare Fund">Student Emergency Welfare Fund (Medical / Legal)</option>
                            <option value="Annual Cultural Meet">Annual Freshers & Cultural Extravaganza</option>
                            <option value="Academic & Book Bank">Academic Book Bank & Scholarship Support</option>
                            <option value="General Support">General Community Support</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Donation Amount (₹)</label>
                        <input type="number" class="form-control form-control-lg fw-bold text-primary" name="amount" value="500" min="10" placeholder="Custom Amount (₹)" required>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Donor Name</label>
                            <input type="text" class="form-control" name="donor_name" placeholder="Full Name or Anonymous" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Phone Number</label>
                            <input type="tel" class="form-control" name="phone" placeholder="+91 9876543210">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Email Address</label>
                            <input type="email" class="form-control" name="email" placeholder="donor@gmail.com">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Payment / UPI Reference ID</label>
                            <input type="text" class="form-control" name="payment_ref" placeholder="UPI Ref / Transaction No." required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-accent btn-lg w-100 fw-bold shadow">
                        <i class="fa-solid fa-check-circle me-2"></i> Submit Donation Record
                    </button>
                </form>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="bg-white p-4 rounded-4 shadow-sm border text-center mb-4">
                <h5 class="fw-bold text-dark mb-2">Scan & Pay via UPI</h5>
                <p class="extra-small text-muted mb-3">Scan with Google Pay, PhonePe, Paytm, or BHIM</p>
                
                <div class="p-3 bg-light rounded-3 d-inline-block border mb-3">
                    <img src="<?php echo e(asset('images/upi-qr.png')); ?>" width="180" height="180" class="rounded border" alt="UPI QR">
                </div>

                <div class="alert alert-warning py-2 mb-0 extra-small fw-bold">
                    Official UPI ID: <span class="text-dark"><?php echo e(\App\Models\Setting::get('upiId', 'ksochandigarh@upi')); ?></span>
                </div>
            </div>

            <div class="bg-white p-4 rounded-4 shadow-sm border">
                <h5 class="fw-bold text-dark mb-3"><i class="fa-solid fa-heart text-danger me-2"></i> Recent Contributors</h5>
                <?php $__currentLoopData = $donations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="d-flex justify-content-between align-items-center p-2 border-bottom extra-small">
                        <div>
                            <div class="fw-bold text-dark"><?php echo e($d->donor_name); ?></div>
                            <div class="text-muted extra-small"><?php echo e($d->cause); ?></div>
                        </div>
                        <div class="fw-bold text-success fs-6">+ ₹<?php echo e(number_format($d->amount)); ?></div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/work/KUKI-STUDENTS-ORG-CHANDIGARH/KUKI-STUDENTS-ORG-CHANDIGARH/resources/views/donations/index.blade.php ENDPATH**/ ?>