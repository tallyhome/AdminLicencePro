<?php $__env->startSection('title', 'Questions Fréquentes - AdminLicence'); ?>

<?php $__env->startSection('content'); ?>
<div class="container py-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="text-center mb-5">
                <h1 class="display-4 fw-bold mb-4">Questions Fréquentes</h1>
                <p class="lead text-muted">Trouvez rapidement les réponses à vos questions sur AdminLicence</p>
            </div>

            <?php if(isset($faqs) && $faqs->count() > 0): ?>
                <div class="accordion" id="faqAccordion">
                    <?php $__currentLoopData = $faqs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $faq): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="accordion-item mb-3">
                        <h2 class="accordion-header" id="faq<?php echo e($index); ?>">
                            <button class="accordion-button <?php echo e($index === 0 ? '' : 'collapsed'); ?>" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?php echo e($index); ?>">
                                <?php echo e($faq->question); ?>

                            </button>
                        </h2>
                        <div id="collapse<?php echo e($index); ?>" class="accordion-collapse collapse <?php echo e($index === 0 ? 'show' : ''); ?>" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                <?php echo nl2br(e($faq->answer)); ?>

                            </div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="fas fa-question-circle fa-3x text-muted mb-3"></i>
                    <h5>Aucune FAQ disponible</h5>
                    <p class="text-muted">Les questions fréquentes seront bientôt disponibles.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make($layout ?? 'frontend.templates.modern.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH R:\Adev\200  -  test\AdminLicence-4.5.1\resources\views\frontend\templates\modern\faqs.blade.php ENDPATH**/ ?>