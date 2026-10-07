<?php $__env->startSection('title', 'Pages CMS | KSO CMS'); ?>

<?php $__env->startSection('content'); ?>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-header bg-white p-3 d-flex justify-content-between align-items-center">
        <h5 class="fw-bold text-primary mb-0"><i class="fa-solid fa-file-lines me-2"></i> Custom Dynamic Web Pages</h5>
        <div class="d-flex gap-2 align-items-center">
            <form action="<?php echo e(route('admin.pages.index')); ?>" method="GET" class="d-flex align-items-center gap-2">
                <label class="visually-hidden" for="page-status">Filter pages by status</label>
                <select id="page-status" name="status" class="form-select form-select-sm">
                    <option value="all" <?php if($status === 'all'): echo 'selected'; endif; ?>>All statuses</option>
                    <option value="published" <?php if($status === 'published'): echo 'selected'; endif; ?>>Published</option>
                    <option value="draft" <?php if($status === 'draft'): echo 'selected'; endif; ?>>Drafts</option>
                </select>
                <button type="submit" class="btn btn-sm btn-outline-primary">Filter</button>
            </form>
            <a href="<?php echo e(route('admin.pages.create')); ?>" class="btn btn-primary btn-sm fw-bold"><i class="fa-solid fa-plus me-1"></i> Build New Page</a>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 extra-small">
                <thead class="table-light">
                    <tr>
                        <th>Page Title</th>
                        <th>Slug / Route</th>
                        <th>Views</th>
                        <th>Published</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $pages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td class="fw-bold text-dark"><?php echo e($p->title); ?></td>
                            <td><code>/page/<?php echo e($p->slug); ?></code></td>
                            <td><?php echo e($p->view_count); ?></td>
                            <td><span class="badge <?php echo e($p->is_published ? 'bg-success' : 'bg-secondary'); ?>"><?php echo e($p->is_published ? 'Published' : 'Draft'); ?></span></td>
                            <td>
                                <a href="<?php echo e(route('admin.pages.preview', $p->id)); ?>" target="_blank" rel="noopener" class="btn btn-sm btn-outline-primary" aria-label="Preview <?php echo e($p->title); ?>"><i class="fa-solid fa-eye"></i></a>
                                <a href="<?php echo e(route('admin.pages.edit', $p->id)); ?>" class="btn btn-sm btn-outline-secondary" aria-label="Edit <?php echo e($p->title); ?>"><i class="fa-solid fa-pen"></i></a>
                                <form action="<?php echo e(route('admin.pages.destroy', $p->id)); ?>" method="POST" class="d-inline" onsubmit="return confirm('Delete page?')">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
        <div class="p-3">
            <?php echo e($pages->links()); ?>

        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/work/KUKI-STUDENTS-ORG-CHANDIGARH/KUKI-STUDENTS-ORG-CHANDIGARH/resources/views/admin/pages/index.blade.php ENDPATH**/ ?>