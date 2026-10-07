<?php $__env->startSection('title', 'Admin CMS Login | KSO Chandigarh'); ?>

<?php $__env->startSection('content'); ?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-5 col-lg-4">
            <div class="card shadow-lg border-0 rounded-4 p-4 text-center">
                <div class="icon-circle bg-teal text-white mx-auto mb-3 fs-3" style="background:#0d9488;">
                    <i class="fa-solid fa-user-shield"></i>
                </div>
                <h4 class="fw-bold text-dark mb-1">CMS Admin Login</h4>
                <p class="text-muted extra-small mb-4">KSO Chandigarh Management Portal</p>

                <form action="<?php echo e(route('admin.login.post')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="mb-3 text-start">
                        <label for="admin-email" class="form-label fw-bold">Admin Email</label>
                        <input id="admin-email" type="email" name="email" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('email')); ?>" autocomplete="username" required <?php if($errors->has('email')): ?> aria-invalid="true" aria-describedby="admin-email-error" <?php endif; ?>>
                        <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div id="admin-email-error" class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="mb-4 text-start">
                        <label for="admin-password" class="form-label fw-bold">Password</label>
                        <input id="admin-password" type="password" name="password" class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" autocomplete="current-password" required <?php if($errors->has('password')): ?> aria-invalid="true" aria-describedby="admin-password-error" <?php endif; ?>>
                        <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div id="admin-password-error" class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <button type="submit" class="btn btn-teal text-white btn-lg w-100 fw-bold shadow-sm" style="background:#0d9488;">
                        Sign In <i class="fa-solid fa-right-to-bracket ms-1"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/work/KUKI-STUDENTS-ORG-CHANDIGARH/KUKI-STUDENTS-ORG-CHANDIGARH/resources/views/admin/login.blade.php ENDPATH**/ ?>