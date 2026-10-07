<?php $__env->startSection('title', 'Membership Forms | KSO CMS'); ?>

<?php $__env->startSection('content'); ?>
<section class="membership-forms-module">
    <header class="membership-forms-heading">
        <div>
            <span class="membership-forms-eyebrow">Member control · Tools</span>
            <h1>Membership forms</h1>
            <p>Share the online application or prepare a blank paper form for offline distribution.</p>
        </div>
        <span class="membership-forms-mark"><i class="fa-solid fa-file-invoice-dollar" aria-hidden="true"></i></span>
    </header>

    <div class="row g-4">
        <div class="col-xl-5">
            <article class="membership-form-action-card h-100">
                <span class="membership-form-step">01 · Online</span>
                <h2>Share the online application</h2>
                <p>Send this public link to students. They can submit their application from a phone or computer.</p>
                <label class="form-label fw-bold" for="membership-registration-link">Public registration link</label>
                <div class="input-group mb-3">
                    <input id="membership-registration-link" class="form-control" type="url" value="<?php echo e($onlineRegistrationUrl); ?>" readonly>
                    <button class="btn btn-outline-primary" type="button" id="copy-membership-link" data-copy-value="<?php echo e($onlineRegistrationUrl); ?>">Copy link</button>
                </div>
                <a class="btn btn-success" href="https://wa.me/?text=<?php echo e(urlencode('Apply for KSO Chandigarh membership: ' . $onlineRegistrationUrl)); ?>" target="_blank" rel="noopener noreferrer">
                    <i class="fa-brands fa-whatsapp me-1" aria-hidden="true"></i> Share on WhatsApp
                </a>
                <span class="membership-copy-status small text-muted ms-2" role="status" aria-live="polite"></span>
            </article>
        </div>

        <div class="col-xl-7">
            <article class="membership-form-action-card h-100">
                <span class="membership-form-step">02 · Offline</span>
                <h2>Build a printable blank form</h2>
                <p>Choose the sections to include. Print on paper, or download the standalone HTML file to open and print without internet.</p>

                <form action="<?php echo e(route('admin.membershipForms.print')); ?>" method="GET" id="membership-form-modules">
                    <fieldset>
                        <legend class="form-label fw-bold">Form sections</legend>
                        <div class="row g-2 mb-4">
                            <?php $__currentLoopData = $modules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="col-sm-6">
                                    <label class="membership-module-option">
                                        <input type="checkbox" name="modules[]" value="<?php echo e($key); ?>" <?php echo e(in_array($key, $selectedModules, true) ? 'checked' : ''); ?>>
                                        <span><?php echo e($label); ?></span>
                                    </label>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </fieldset>
                    <div class="d-flex flex-wrap gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa-solid fa-print me-1" aria-hidden="true"></i> Preview & print
                        </button>
                        <button type="submit" class="btn btn-outline-primary" formaction="<?php echo e(route('admin.membershipForms.download')); ?>">
                            <i class="fa-solid fa-file-invoice-dollar me-1" aria-hidden="true"></i> Download offline form
                        </button>
                        <button type="button" class="btn btn-outline-secondary" id="select-all-membership-modules">Select all sections</button>
                    </div>
                    <p class="small text-muted mt-3 mb-0">In the print dialog, choose <strong>Save as PDF</strong> to create a PDF copy.</p>
                </form>
            </article>
        </div>
    </div>

    <aside class="membership-form-workflow mt-4" aria-labelledby="offline-workflow-heading">
        <h2 id="offline-workflow-heading"><i class="fa-solid fa-list-check me-2" aria-hidden="true"></i>After collecting paper applications</h2>
        <ol class="mb-0">
            <li>Review the completed form and required supporting information.</li>
            <li>Open <a href="<?php echo e(route('admin.members.create')); ?>">Member Control → Add Member</a> and enter the details into the existing member record system.</li>
            <li>Use the existing review and approval process. Printing or downloading a blank form does not create or approve a member record.</li>
        </ol>
    </aside>
</section>

<?php $__env->startPush('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('membership-form-modules');
        const checkboxes = Array.from(form.querySelectorAll('input[name="modules[]"]'));
        const selectAllButton = document.getElementById('select-all-membership-modules');
        const copyButton = document.getElementById('copy-membership-link');
        const copyStatus = document.querySelector('.membership-copy-status');

        selectAllButton.addEventListener('click', function () {
            checkboxes.forEach((checkbox) => { checkbox.checked = true; });
        });

        form.addEventListener('submit', function (event) {
            if (!checkboxes.some((checkbox) => checkbox.checked)) {
                event.preventDefault();
                checkboxes[0].focus();
                window.alert('Choose at least one form section.');
            }
        });

        copyButton.addEventListener('click', async function () {
            try {
                await navigator.clipboard.writeText(copyButton.dataset.copyValue);
                copyStatus.textContent = 'Link copied.';
            } catch (error) {
                const input = document.getElementById('membership-registration-link');
                input.select();
                copyStatus.textContent = 'Select and copy the link above.';
            }
        });
    });
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/work/KUKI-STUDENTS-ORG-CHANDIGARH/KUKI-STUDENTS-ORG-CHANDIGARH/resources/views/admin/membership-forms/index.blade.php ENDPATH**/ ?>