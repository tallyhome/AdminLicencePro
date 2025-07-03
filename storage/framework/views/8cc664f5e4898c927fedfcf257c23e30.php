<?php $__env->startSection('title', 'Édition du template'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h3">Édition du template</h1>
                <div class="btn-group">
                    <a href="<?php echo e(route('admin.email.templates.preview', $template->id)); ?>" class="btn btn-info" target="_blank">
                        <i class="fas fa-eye me-2"></i> Prévisualiser
                    </a>
                    <a href="<?php echo e(route('admin.email.templates.index')); ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i> Retour
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="<?php echo e(route('admin.email.templates.update', $template->id)); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label">Nom du template</label>
                                <input type="text" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                    id="name" name="name" value="<?php echo e(old('name', $template->name)); ?>" required>
                                <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="col-md-6">
                                <label for="description" class="form-label">Description</label>
                                <input type="text" class="form-control <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                    id="description" name="description" value="<?php echo e(old('description', $template->description)); ?>">
                                <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>

                        <!-- Variables dynamiques -->
                        <div class="mb-3">
                            <label class="form-label">Variables disponibles</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="newVariable" placeholder="Nouvelle variable">
                                <button type="button" class="btn btn-primary" onclick="addVariable()">
                                    <i class="fas fa-plus me-2"></i> Ajouter
                                </button>
                            </div>
                            <div id="variablesList" class="mt-2">
                                <?php $__currentLoopData = json_decode($template->variables); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $variable): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <span class="badge bg-info me-2 mb-2">
                                        <?php echo e($variable); ?>

                                        <i class="fas fa-times ms-1" onclick="removeVariable(this)"></i>
                                        <input type="hidden" name="variables[]" value="<?php echo e($variable); ?>">
                                    </span>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>

                        <!-- Contenu multilingue -->
                        <div class="mb-3">
                            <label class="form-label">Contenu par langue</label>
                            <ul class="nav nav-tabs" id="languageTabs" role="tablist">
                                <?php $__currentLoopData = $languages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code => $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link <?php echo e($loop->first ? 'active' : ''); ?>" 
                                            id="<?php echo e($code); ?>-tab" 
                                            data-bs-toggle="tab" 
                                            data-bs-target="#<?php echo e($code); ?>-content" 
                                            type="button" 
                                            role="tab">
                                            <?php echo e($name); ?>

                                        </button>
                                    </li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>

                            <div class="tab-content mt-3" id="languageContent">
                                <?php $__currentLoopData = $languages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code => $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="tab-pane fade <?php echo e($loop->first ? 'show active' : ''); ?>" 
                                        id="<?php echo e($code); ?>-content" 
                                        role="tabpanel">
                                        <div class="mb-3">
                                            <label class="form-label">Sujet (<?php echo e($name); ?>)</label>
                                            <input type="text" 
                                                class="form-control <?php $__errorArgs = ['subject.' . $code];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                                name="subject[<?php echo e($code); ?>]" 
                                                value="<?php echo e(old('subject.' . $code, $template->subject[$code] ?? '')); ?>">
                                            <?php $__errorArgs = ['subject.' . $code];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Contenu (<?php echo e($name); ?>)</label>
                                            <textarea class="form-control editor <?php $__errorArgs = ['content.' . $code];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                                name="content[<?php echo e($code); ?>]" 
                                                rows="10"><?php echo e(old('content.' . $code, $template->content[$code] ?? '')); ?></textarea>
                                            <?php $__errorArgs = ['content.' . $code];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i> Enregistrer
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<script>
// Initialisation des éditeurs
const editors = {};
document.querySelectorAll('.editor').forEach(element => {
    const editor = new Quill(element, {
        theme: 'snow',
        modules: {
            toolbar: [
                [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
                ['bold', 'italic', 'underline', 'strike'],
                [{ 'color': [] }, { 'background': [] }],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                [{ 'align': [] }],
                ['link', 'image'],
                ['clean']
            ]
        }
    });
    editors[element.getAttribute('name')] = editor;

    // Synchroniser le contenu avec le textarea
    editor.on('text-change', function() {
        element.value = editor.root.innerHTML;
    });
});

// Gestion des variables
function addVariable() {
    const input = document.getElementById('newVariable');
    const variable = input.value.trim();
    
    if (variable) {
        const variablesList = document.getElementById('variablesList');
        const badge = document.createElement('span');
        badge.className = 'badge bg-info me-2 mb-2';
        badge.innerHTML = `
            ${variable}
            <i class="fas fa-times ms-1" onclick="removeVariable(this)"></i>
            <input type="hidden" name="variables[]" value="${variable}">
        `;
        variablesList.appendChild(badge);
        input.value = '';
    }
}

function removeVariable(element) {
    element.parentElement.remove();
}

// Soumission du formulaire
document.querySelector('form').addEventListener('submit', function() {
    // Synchroniser tous les éditeurs avant la soumission
    Object.entries(editors).forEach(([name, editor]) => {
        const textarea = document.querySelector(`textarea[name="${name}"]`);
        textarea.value = editor.root.innerHTML;
    });
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH R:\Adev\200  -  test\AdminLicence-4.5.1\resources\views\admin\email\templates\edit.blade.php ENDPATH**/ ?>