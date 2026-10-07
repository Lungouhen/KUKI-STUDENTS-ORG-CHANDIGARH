<header class="bg-primary text-white py-5 mb-5">
    <div class="container text-center">
        <h1 class="fw-black display-5 mb-2"><?php echo e($page->title); ?></h1>
        <?php if($page->excerpt): ?>
            <p class="lead opacity-90 mx-auto mb-0" style="max-width: 700px;"><?php echo e($page->excerpt); ?></p>
        <?php endif; ?>
    </div>
</header>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <article class="bg-white p-4 p-md-5 rounded-4 shadow-sm border leading-relaxed">
                <?php echo $page->content; ?>

            </article>
        </div>
    </div>
</div>
<?php /**PATH /home/runner/work/KUKI-STUDENTS-ORG-CHANDIGARH/KUKI-STUDENTS-ORG-CHANDIGARH/resources/views/pages/templates/standard.blade.php ENDPATH**/ ?>