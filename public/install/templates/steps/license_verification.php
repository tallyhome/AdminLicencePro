<?php
/**
 * Template pour l'étape de vérification de licence
 * 
 * @var array $errors Erreurs éventuelles
 */
?>
<div class="step-content">
    <h2><?php echo t('license_verification'); ?></h2>
    <p class="step-description"><?php echo t('license_verification_description'); ?></p>
    
    <div class="license-info">
        <h3><?php echo t('license_information'); ?></h3>
        <ul>
            <li><?php echo t('license_required_info'); ?></li>
            <li><?php echo t('license_validation_info'); ?></li>
            <li><?php echo t('license_support_info'); ?></li>
        </ul>
    </div>
    
    <form method="POST" action="install_new.php" class="installation-form" data-step="1">
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? ''); ?>">
        <input type="hidden" name="step" value="1">
        
        <div class="form-group">
            <label for="license_key"><?php echo t('license_key'); ?> *</label>
            <input type="text" 
                   id="license_key" 
                   name="license_key" 
                   value="<?php echo htmlspecialchars($_POST['license_key'] ?? ''); ?>"
                   placeholder="<?php echo t('license_key_placeholder'); ?>"
                   required
                   data-validation="license-key"
                   autocomplete="off">
            <small class="form-help"><?php echo t('license_key_help'); ?></small>
        </div>
        
        <div class="form-group">
            <label for="domain"><?php echo t('domain'); ?> *</label>
            <input type="text" 
                   id="domain" 
                   name="domain" 
                   value="<?php echo htmlspecialchars($_SERVER['HTTP_HOST'] ?? ''); ?>"
                   placeholder="<?php echo t('domain_placeholder'); ?>"
                   required
                   data-validation="domain"
                   readonly>
            <small class="form-help"><?php echo t('domain_help'); ?></small>
        </div>
        
        <div class="form-group">
            <label for="ip_address"><?php echo t('ip_address'); ?></label>
            <input type="text" 
                   id="ip_address" 
                   name="ip_address" 
                   value="<?php echo htmlspecialchars($_SERVER['SERVER_ADDR'] ?? ''); ?>"
                   placeholder="<?php echo t('ip_placeholder'); ?>"
                   data-validation="ip"
                   readonly>
            <small class="form-help"><?php echo t('ip_help'); ?></small>
        </div>
        
        <div class="form-actions">
            <button type="button" class="btn btn-primary" onclick="verifyLicenseAjax()" data-loading-text="<?php echo t('verifying'); ?>">
                <span class="btn-text"><?php echo t('verify_license'); ?></span>
                <span class="btn-spinner" style="display: none;"></span>
            </button>
        </div>
        
        <div id="license-result" class="license-result" style="display: none;"></div>
    </form>
    
    <div class="step-help">
        <h4><?php echo t('need_help'); ?></h4>
        <p><?php echo t('license_help_text'); ?></p>
        <a href="https://adminlicence.com/support" target="_blank" class="btn btn-secondary">
            <?php echo t('contact_support'); ?>
        </a>
    </div>
</div>

<script>
function verifyLicenseAjax() {
    const form = document.querySelector('.installation-form');
    const formData = new FormData(form);
    
    // Assurez-vous que le domaine et l'adresse IP sont inclus
    const licenseKey = document.getElementById('license_key').value;
    const domain = document.getElementById('domain').value;
    const ipAddress = document.getElementById('ip_address').value;
    
    formData.set('license_key', licenseKey);
    formData.set('domain', domain);
    formData.set('ip_address', ipAddress);
    
    const button = document.querySelector('.btn-primary');
    const originalText = button.querySelector('.btn-text').textContent;
    const spinner = button.querySelector('.btn-spinner');
    const resultDiv = document.getElementById('license-result');
    
    // Show loading state
    button.disabled = true;
    button.querySelector('.btn-text').textContent = '<?php echo t('verifying'); ?>';
    spinner.style.display = 'inline-block';
    resultDiv.style.display = 'none';
    
    // Utiliser le fichier AJAX dédié pour vérifier la licence sur le serveur distant
    fetch('ajax/verify_license.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.text();
    })
    .then(data => {
        // Check if the response contains success indicators
        // Analyser la réponse JSON
        const response = JSON.parse(data);
        
        if (response.status === true) {
            // Licence valide, rediriger vers l'étape suivante
            resultDiv.style.display = 'block';
            resultDiv.className = 'license-result success';
            resultDiv.innerHTML = '<i class="icon-check"></i> ' + (response.message || '<?php echo t('license_valid'); ?>');
            
            // Stocker les informations de licence dans un formulaire caché et le soumettre
            const hiddenForm = document.createElement('form');
            hiddenForm.method = 'POST';
            hiddenForm.action = 'install_new.php';
            
            const stepInput = document.createElement('input');
            stepInput.type = 'hidden';
            stepInput.name = 'step';
            stepInput.value = '1';
            
            const licenseKeyInput = document.createElement('input');
            licenseKeyInput.type = 'hidden';
            licenseKeyInput.name = 'serial_key';
            licenseKeyInput.value = licenseKey;
            
            hiddenForm.appendChild(stepInput);
            hiddenForm.appendChild(licenseKeyInput);
            document.body.appendChild(hiddenForm);
            
            setTimeout(() => {
                hiddenForm.submit();
            }, 1500);
        } else {
            // Licence invalide, afficher l'erreur
            resultDiv.style.display = 'block';
            resultDiv.className = 'license-result error';
            resultDiv.innerHTML = '<i class="icon-error"></i> ' + (response.message || '<?php echo t('license_key_invalid'); ?>');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        resultDiv.style.display = 'block';
        resultDiv.className = 'license-result error';
        resultDiv.innerHTML = '<i class="icon-error"></i> <?php echo t('verification_error'); ?>';
    })
    .finally(() => {
        // Reset button state
        button.disabled = false;
        button.querySelector('.btn-text').textContent = originalText;
        spinner.style.display = 'none';
    });
}
</script>

<style>
.license-result {
    margin-top: 15px;
    padding: 12px;
    border-radius: 4px;
    font-weight: 500;
}

.license-result.success {
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.license-result.error {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

.license-result i {
    margin-right: 8px;
}
</style>