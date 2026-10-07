<?php $__env->startSection('title', 'Events & News | KSO Chandigarh'); ?>

<?php $__env->startSection('content'); ?>

<div class="public-page-banner bg-primary text-white py-4 mb-4">
    <div class="container text-center">
        <h2 class="fw-black mb-1">Events & Official Announcements</h2>
        <p class="small text-light opacity-90 mb-0">Stay connected with upcoming cultural meets, sports, and press releases</p>
    </div>
</div>

<div class="container my-4">

    <!-- FullCalendar JS Community Grid -->
    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-5">
        <h4 class="fw-bold text-primary mb-3"><i class="fa-solid fa-calendar-check text-warning me-2"></i> KSO Community Event Calendar Grid</h4>
        <div id="fullCalendarGrid" style="min-height: 450px;"></div>
    </div>

    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div class="btn-group" role="group">
            <a href="<?php echo e(route('events.index', ['category' => 'All'])); ?>" class="btn <?php echo e(!$category || $category == 'All' ? 'btn-primary active' : 'btn-outline-primary'); ?>">All Events</a>
            <a href="<?php echo e(route('events.index', ['category' => 'Cultural'])); ?>" class="btn <?php echo e($category == 'Cultural' ? 'btn-primary active' : 'btn-outline-primary'); ?>">Cultural</a>
            <a href="<?php echo e(route('events.index', ['category' => 'Sports'])); ?>" class="btn <?php echo e($category == 'Sports' ? 'btn-primary active' : 'btn-outline-primary'); ?>">Sports</a>
            <a href="<?php echo e(route('events.index', ['category' => 'Academic'])); ?>" class="btn <?php echo e($category == 'Academic' ? 'btn-primary active' : 'btn-outline-primary'); ?>">Academic</a>
            <a href="<?php echo e(route('events.index', ['category' => 'Social Service'])); ?>" class="btn <?php echo e($category == 'Social Service' ? 'btn-primary active' : 'btn-outline-primary'); ?>">Social Service</a>
        </div>
    </div>

    <div class="row g-4">
        <?php $__empty_1 = true; $__currentLoopData = $events; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100 bg-white hover-lift">
                    <?php if (isset($component)) { $__componentOriginalb4ae95e62e8615350ae7fdaa410354d0 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb4ae95e62e8615350ae7fdaa410354d0 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.event-image','data' => ['event' => $e]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('event-image'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['event' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($e)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalb4ae95e62e8615350ae7fdaa410354d0)): ?>
<?php $attributes = $__attributesOriginalb4ae95e62e8615350ae7fdaa410354d0; ?>
<?php unset($__attributesOriginalb4ae95e62e8615350ae7fdaa410354d0); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalb4ae95e62e8615350ae7fdaa410354d0)): ?>
<?php $component = $__componentOriginalb4ae95e62e8615350ae7fdaa410354d0; ?>
<?php unset($__componentOriginalb4ae95e62e8615350ae7fdaa410354d0); ?>
<?php endif; ?>
                    <div class="card-body p-3 d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge bg-primary-lt text-primary extra-small fw-bold"><?php echo e($e->category); ?></span>
                            <span class="badge <?php echo e($e->status === 'Upcoming' ? 'bg-success' : 'bg-secondary'); ?> extra-small"><?php echo e($e->status); ?></span>
                        </div>
                        <h5 class="card-title fw-bold text-dark mb-2"><?php echo e($e->title); ?></h5>
                        <p class="card-text text-muted extra-small mb-3"><?php echo e($e->description); ?></p>
                        <div class="mt-auto pt-2 border-top extra-small text-secondary">
                            <div class="mb-1"><i class="fa-solid fa-calendar-day text-primary me-1"></i> <strong>Date:</strong> <?php echo e($e->date ? $e->date->format('Y-m-d') : ''); ?> (<?php echo e($e->time ?? '10:00 AM'); ?>)</div>
                            <div class="mb-2"><i class="fa-solid fa-location-dot text-danger me-1"></i> <strong>Venue:</strong> <?php echo e($e->venue); ?></div>
                            <a href="<?php echo e(route('events.show', $e->id)); ?>" class="btn btn-primary btn-sm rounded-pill w-100 fw-bold">RSVP / Event Pass</a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="col-12 text-center text-muted py-4">No events found in this category.</div>
        <?php endif; ?>
    </div>

    <div class="mt-5">
        <h3 class="fw-bold text-dark mb-3 border-bottom pb-2"><i class="fa-solid fa-newspaper text-danger me-2"></i> All Press Notices & News</h3>
        <div class="row g-3">
            <?php $__currentLoopData = $news; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $n): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge bg-danger text-white extra-small"><?php echo e($n->category); ?></span>
                            <small class="text-muted extra-small"><i class="fa-solid fa-calendar me-1"></i> <?php echo e($n->date ? $n->date->format('Y-m-d') : ''); ?></small>
                        </div>
                        <h5 class="fw-bold text-dark mb-2"><?php echo e($n->title); ?></h5>
                        <p class="text-secondary extra-small mb-2"><?php echo e($n->content); ?></p>
                        <small class="text-primary fw-bold extra-small mt-auto"><i class="fa-solid fa-user-pen me-1"></i> <?php echo e($n->author); ?></small>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('fullCalendarGrid');
        if (calendarEl) {
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek'
                },
                events: [
                    <?php $__currentLoopData = $events; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ev): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    {
                        title: '<?php echo e($ev->title); ?>',
                        start: '<?php echo e($ev->date ? $ev->date->format("Y-m-d") : ""); ?>',
                        url: '<?php echo e(route("events.show", $ev->id)); ?>',
                        backgroundColor: '<?php echo e($ev->category === "Cultural" ? "#003566" : ($ev->category === "Sports" ? "#0d9488" : "#780000")); ?>'
                    },
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                ]
            });
            calendar.render();
        }
    });
</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/work/KUKI-STUDENTS-ORG-CHANDIGARH/KUKI-STUDENTS-ORG-CHANDIGARH/resources/views/events/index.blade.php ENDPATH**/ ?>