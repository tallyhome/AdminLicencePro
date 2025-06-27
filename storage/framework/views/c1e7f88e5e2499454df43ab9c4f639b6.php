<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Nouveau message de contact - AdminLicence</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #0d6efd; color: white; padding: 20px; text-align: center; }
        .content { background: #f8f9fa; padding: 20px; }
        .field { margin-bottom: 15px; }
        .label { font-weight: bold; color: #495057; }
        .value { margin-top: 5px; padding: 10px; background: white; border-radius: 5px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Nouveau message de contact</h1>
        </div>
        <div class="content">
            <div class="field">
                <div class="label">Nom :</div>
                <div class="value"><?php echo e($name); ?></div>
            </div>
            
            <div class="field">
                <div class="label">Email :</div>
                <div class="value"><?php echo e($email); ?></div>
            </div>
            
            <div class="field">
                <div class="label">Sujet :</div>
                <div class="value"><?php echo e($subject); ?></div>
            </div>
            
            <div class="field">
                <div class="label">Message :</div>
                <div class="value"><?php echo e($message); ?></div>
            </div>
            
            <div class="field">
                <div class="label">Date :</div>
                <div class="value"><?php echo e(now()->format('d/m/Y H:i')); ?></div>
            </div>
        </div>
    </div>
</body>
</html> <?php /**PATH R:\Adev\200  -  test\AdminLicence-4.5.1\resources\views\emails\contact.blade.php ENDPATH**/ ?>