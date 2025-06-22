/**
 * AdminLicence Installation - JavaScript
 * Version: 2.0.0
 * Modern installation wizard interactions
 */

// Variables globales
let currentStep = 1;
let isProcessing = false;

/**
 * Toggle language dropdown menu
 */
function toggleLanguageDropdown() {
    const dropdown = document.querySelector('.language-dropdown');
    const menu = document.getElementById('languageDropdownMenu');
    
    if (!dropdown || !menu) return;
    
    const isOpen = dropdown.classList.contains('open');
    
    if (isOpen) {
        dropdown.classList.remove('open');
        document.removeEventListener('click', closeLanguageDropdownOnOutsideClick);
    } else {
        dropdown.classList.add('open');
        // Close dropdown when clicking outside
        setTimeout(() => {
            document.addEventListener('click', closeLanguageDropdownOnOutsideClick);
        }, 10);
    }
}

/**
 * Close language dropdown when clicking outside
 */
function closeLanguageDropdownOnOutsideClick(event) {
    const dropdown = document.querySelector('.language-dropdown');
    if (dropdown && !dropdown.contains(event.target)) {
        dropdown.classList.remove('open');
        document.removeEventListener('click', closeLanguageDropdownOnOutsideClick);
    }
}

document.addEventListener('DOMContentLoaded', function() {
    // Initialize installation wizard
    initializeInstallation();
});

/**
 * Initialize all installation components
 */
function initializeInstallation() {
    initializeLanguageDropdown();
    initializeFormValidation();
    initializeLoadingStates();
    initializeAnimations();
    initializeAccessibility();
    initializeLicenseVerification();
}

/**
 * Language dropdown functionality
 */
function initializeLanguageDropdown() {
    const dropdown = document.getElementById('languageDropdown');
    const toggle = document.getElementById('languageToggle');
    const menu = document.getElementById('languageMenu');
    
    if (!dropdown || !toggle || !menu) return;
    
    // Toggle dropdown
    toggle.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        toggleDropdown();
    });
    
    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (!dropdown.contains(e.target)) {
            closeDropdown();
        }
    });
    
    // Handle language selection
    const languageLinks = menu.querySelectorAll('a');
    languageLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            showLoading();
            // Let the link navigate naturally
        });
    });
    
    // Keyboard navigation
    toggle.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' || e.key === ' ') {
            e.preventDefault();
            toggleDropdown();
        } else if (e.key === 'Escape') {
            closeDropdown();
        }
    });
    
    function toggleDropdown() {
        const isOpen = dropdown.classList.contains('open');
        if (isOpen) {
            closeDropdown();
        } else {
            openDropdown();
        }
    }
    
    function openDropdown() {
        dropdown.classList.add('open');
        toggle.setAttribute('aria-expanded', 'true');
        
        // Focus first menu item
        const firstLink = menu.querySelector('a');
        if (firstLink) {
            setTimeout(() => firstLink.focus(), 100);
        }
    }
    
    function closeDropdown() {
        dropdown.classList.remove('open');
        toggle.setAttribute('aria-expanded', 'false');
    }
}

/**
 * Form validation and enhancement
 */
function initializeFormValidation() {
    const forms = document.querySelectorAll('form');
    
    forms.forEach(form => {
        // Real-time validation
        const inputs = form.querySelectorAll('input, select, textarea');
        inputs.forEach(input => {
            input.addEventListener('blur', function() {
                validateField(this);
            });
            
            input.addEventListener('input', function() {
                clearFieldError(this);
            });
        });
        
        // Form submission
        form.addEventListener('submit', function(e) {
            if (!validateForm(this)) {
                e.preventDefault();
                return false;
            }
            
            // Show loading state
            showLoading();
            disableForm(this);
        });
    });
}

/**
 * Validate individual field
 */
function validateField(field) {
    const value = field.value.trim();
    const type = field.type;
    const required = field.hasAttribute('required');
    
    clearFieldError(field);
    
    // Required field validation
    if (required && !value) {
        showFieldError(field, getTranslation('field_required'));
        return false;
    }
    
    // Email validation
    if (type === 'email' && value) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(value)) {
            showFieldError(field, getTranslation('invalid_email'));
            return false;
        }
    }
    
    // License key validation
    if (field.name === 'serial_key' && value) {
        const licenseRegex = /^[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}$/;
        if (!licenseRegex.test(value.toUpperCase())) {
            showFieldError(field, getTranslation('invalid_license_format'));
            return false;
        }
    }
    
    // Password confirmation
    if (field.name === 'admin_password_confirm') {
        const passwordField = document.querySelector('input[name="admin_password"]');
        if (passwordField && value !== passwordField.value) {
            showFieldError(field, getTranslation('password_mismatch'));
            return false;
        }
    }
    
    return true;
}

/**
 * Validate entire form
 */
function validateForm(form) {
    const inputs = form.querySelectorAll('input[required], select[required], textarea[required]');
    let isValid = true;
    
    inputs.forEach(input => {
        if (!validateField(input)) {
            isValid = false;
        }
    });
    
    return isValid;
}

/**
 * Show field error
 */
function showFieldError(field, message) {
    field.classList.add('error');
    
    // Remove existing error
    const existingError = field.parentNode.querySelector('.form-error');
    if (existingError) {
        existingError.remove();
    }
    
    // Add new error
    const errorElement = document.createElement('div');
    errorElement.className = 'form-error';
    errorElement.textContent = message;
    field.parentNode.appendChild(errorElement);
    
    // Add shake animation
    field.style.animation = 'shake 0.5s ease-in-out';
    setTimeout(() => {
        field.style.animation = '';
    }, 500);
}

/**
 * Clear field error
 */
function clearFieldError(field) {
    field.classList.remove('error');
    const errorElement = field.parentNode.querySelector('.form-error');
    if (errorElement) {
        errorElement.remove();
    }
}

/**
 * Loading states management
 */
function initializeLoadingStates() {
    // Auto-hide loading overlay after page load
    window.addEventListener('load', function() {
        setTimeout(hideLoading, 100);
    });
    
    // Force hide loading overlay after DOM ready
    hideLoading();
}

/**
 * Show loading overlay
 */
function showLoading() {
    const overlay = document.getElementById('loadingOverlay');
    if (overlay) {
        overlay.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }
}

/**
 * Hide loading overlay
 */
function hideLoading() {
    const overlay = document.getElementById('loadingOverlay');
    if (overlay) {
        overlay.style.display = 'none';
        document.body.style.overflow = '';
    }
}

/**
 * Disable form during submission
 */
function disableForm(form) {
    const inputs = form.querySelectorAll('input, select, textarea, button');
    inputs.forEach(input => {
        input.disabled = true;
    });
    
    // Add loading state to submit button
    const submitBtn = form.querySelector('button[type="submit"]');
    if (submitBtn) {
        submitBtn.classList.add('loading');
    }
}

/**
 * Initialize animations
 */
function initializeAnimations() {
    // Animate form elements on load
    const formGroups = document.querySelectorAll('.form-group');
    formGroups.forEach((group, index) => {
        group.style.opacity = '0';
        group.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            group.style.transition = 'all 0.3s ease';
            group.style.opacity = '1';
            group.style.transform = 'translateY(0)';
        }, index * 100 + 200);
    });
    
    // Animate step indicators
    const steps = document.querySelectorAll('.step');
    steps.forEach((step, index) => {
        setTimeout(() => {
            step.style.animation = 'fadeInUp 0.5s ease forwards';
        }, index * 100);
    });
}

/**
 * Initialize accessibility features
 */
function initializeAccessibility() {
    // Add ARIA labels and descriptions
    const inputs = document.querySelectorAll('input, select, textarea');
    inputs.forEach(input => {
        const label = input.parentNode.querySelector('label');
        const help = input.parentNode.querySelector('.form-help');
        
        if (label && !input.hasAttribute('aria-label')) {
            input.setAttribute('aria-labelledby', label.id || generateId());
            if (!label.id) label.id = input.getAttribute('aria-labelledby');
        }
        
        if (help) {
            const helpId = help.id || generateId();
            help.id = helpId;
            input.setAttribute('aria-describedby', helpId);
        }
    });
    
    // Keyboard navigation for buttons
    const buttons = document.querySelectorAll('.btn');
    buttons.forEach(button => {
        button.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                this.click();
            }
        });
    });
}

/**
 * Get translation (fallback to English)
 */
function getTranslation(key) {
    const translations = {
        'field_required': 'Ce champ est requis',
        'invalid_email': 'Adresse email invalide',
        'invalid_license_format': 'Format de licence invalide (XXXX-XXXX-XXXX-XXXX)',
        'password_mismatch': 'Les mots de passe ne correspondent pas'
    };
    
    return translations[key] || key;
}

/**
 * Generate unique ID
 */
function generateId() {
    return 'id_' + Math.random().toString(36).substr(2, 9);
}

/**
 * Auto-format license key input
 */
document.addEventListener('input', function(e) {
    if (e.target.name === 'serial_key') {
        let value = e.target.value.replace(/[^A-Z0-9]/g, '').toUpperCase();
        let formatted = '';
        
        for (let i = 0; i < value.length && i < 16; i++) {
            if (i > 0 && i % 4 === 0) {
                formatted += '-';
            }
            formatted += value[i];
        }
        
        e.target.value = formatted;
    }
});

/**
 * Add shake animation to CSS
 */
const style = document.createElement('style');
style.textContent = `
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
        20%, 40%, 60%, 80% { transform: translateX(5px); }
    }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
`;
document.head.appendChild(style);

/**
 * Initialize license verification with AJAX
 */
function initializeLicenseVerification() {
    const licenseForm = document.querySelector('form[data-step="1"]');
    if (licenseForm) {
        licenseForm.addEventListener('submit', function(e) {
            e.preventDefault(); // Empêcher la soumission normale
            
            // Utiliser AJAX pour la vérification
            verifyLicenseAjax(this);
        });
    }
    
    // CORRECTION: Ajouter la gestion AJAX pour l'étape 2
    const step2Form = document.querySelector('form[data-step="2"]');
    if (step2Form) {
        step2Form.addEventListener('submit', function(e) {
            e.preventDefault(); // Empêcher la soumission normale
            
            // Utiliser AJAX pour la vérification des prérequis
            verifyStep2Ajax(this);
        });
    }
    
    // CORRECTION: Ajouter la gestion AJAX pour l'étape 3
    const step3Form = document.querySelector('form[data-step="3"]');
    if (step3Form) {
        step3Form.addEventListener('submit', function(e) {
            e.preventDefault(); // Empêcher la soumission normale
            
            // Utiliser AJAX pour la configuration DB
            verifyStep3Ajax(this);
        });
        
        // Ajouter le gestionnaire pour le bouton de test DB
        const testDbBtn = step3Form.querySelector('#test-db-btn');
        if (testDbBtn) {
            testDbBtn.addEventListener('click', function(e) {
                e.preventDefault();
                testDatabaseConnection(step3Form);
            });
        }
    }
    
    // CORRECTION: Ajouter la gestion AJAX pour l'étape 4
    const step4Form = document.querySelector('form[data-step="4"]');
    if (step4Form) {
        step4Form.addEventListener('submit', function(e) {
            e.preventDefault(); // Empêcher la soumission normale
            
            // Utiliser AJAX pour la configuration admin
            verifyStep4Ajax(this);
        });
    }
    
    // CORRECTION: Ajouter la gestion AJAX pour l'étape 5
    const step5Form = document.querySelector('form[data-step="5"]');
    if (step5Form) {
        step5Form.addEventListener('submit', function(e) {
            e.preventDefault(); // Empêcher la soumission normale
            
            // Utiliser AJAX pour l'installation finale
            verifyStep5Ajax(this);
        });
    }
}

/**
 * Verify license via AJAX
 */
async function verifyLicenseAjax(form) {
    if (isProcessing) {
        return;
    }
    
    const submitBtn = form.querySelector('button[type="submit"]');
    const serialKeyInput = form.querySelector('input[name="serial_key"]');
    const alertContainer = form.querySelector('.alert-container') || createAlertContainer(form);
    
    // Clear previous errors
    clearFormErrors(form);
    hideAlert(alertContainer);
    
    // Validate form
    if (!validateForm(form)) {
        return;
    }
    
    // Show loading state
    isProcessing = true;
    showLoadingButton(submitBtn);
    
    try {
        // Prepare form data - s'assurer que serial_key est bien inclus
        const formData = new FormData();
        const serialKeyValue = serialKeyInput.value.trim();
        
        // Vérifier que la clé n'est pas vide
        if (!serialKeyValue) {
            showAlert(alertContainer, 'Veuillez saisir une clé de licence', 'error');
            // Ne pas faire return ici, laisser le finally s'exécuter
            throw new Error('Clé de licence vide');
        }
        
        formData.append('serial_key', serialKeyValue);
        formData.append('ajax', '1');
        formData.append('step', '1');
        
        // Send AJAX request
        const response = await fetch('install_new.php', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        // Check if response is OK
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        // Get response text first to debug
        const responseText = await response.text();
        
        // Vérifier si c'est une redirection HTML au lieu de JSON
        if (responseText.includes('<html') || responseText.includes('<!DOCTYPE')) {
            // Vérifier si c'est vraiment un succès en cherchant des indicateurs dans le HTML
            if (responseText.includes('Configuration de la base de données') ||
                responseText.includes('step=2') ||
                responseText.includes('step active') && responseText.includes('2')) {
                // Redirection après nettoyage
                setTimeout(() => {
                    window.location.href = 'install_new.php?step=2';
                }, 100);
                return; // Ce return est OK car c'est un succès
            } else {
                // Si c'est du HTML mais pas l'étape 2, c'est probablement une erreur
                showAlert(alertContainer, 'Erreur de validation de la licence', 'error');
                // Ne pas faire return ici, laisser le finally s'exécuter
                throw new Error('HTML détecté mais pas de succès confirmé');
            }
        }
        
        // Try to parse as JSON
        let result;
        try {
            result = JSON.parse(responseText);
        } catch (e) {
            // Si on ne peut pas parser le JSON, essayer de détecter le succès dans le texte
            if (responseText.includes('success') || responseText.includes('valide')) {
                showAlert(alertContainer, 'Licence valide ! Redirection...', 'success');
                setTimeout(() => {
                    window.location.href = 'install_new.php?step=2';
                }, 1000);
                // Ne pas faire return ici, laisser le finally s'exécuter
                throw new Error('Succès détecté dans la réponse texte - redirection en cours');
            }
            
            throw new Error('Réponse serveur invalide: ' + responseText.substring(0, 100));
        }
        
        if (result.success) {
            // License valid - show success and proceed
            showAlert(alertContainer, result.message || 'Licence valide !', 'success');
            
            // Wait a bit then redirect to step 2
            setTimeout(() => {
                window.location.href = 'install_new.php?step=2';
            }, 1000);
            
        } else {
            // License invalid - show error
            showAlert(alertContainer, result.message || 'Licence invalide', 'error');
            serialKeyInput.focus();
        }
        
    } catch (error) {
        showAlert(alertContainer, 'Erreur de connexion au serveur de licence: ' + error.message, 'error');
    } finally {
        isProcessing = false;
        hideLoadingButton(submitBtn);
    }
}

/**
 * Verify step 2 system requirements via AJAX
 */
async function verifyStep2Ajax(form) {
    if (isProcessing) {
        return;
    }
    
    const submitBtn = form.querySelector('button[type="submit"]');
    const alertContainer = form.querySelector('.alert-container') || createAlertContainer(form);
    
    // Clear previous errors
    clearFormErrors(form);
    hideAlert(alertContainer);
    
    // Show loading state
    isProcessing = true;
    showLoadingButton(submitBtn);
    
    try {
        // Prepare form data
        const formData = new FormData();
        formData.append('ajax', '1');
        formData.append('step', '2');
        
        // Send AJAX request
        const response = await fetch('install_new.php', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        // Check if response is OK
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        // Get response text first to debug
        const responseText = await response.text();
        
        // Vérifier si c'est une redirection HTML au lieu de JSON
        if (responseText.includes('<html') || responseText.includes('<!DOCTYPE')) {
            // Vérifier si c'est vraiment un succès en cherchant des indicateurs dans le HTML
            if (responseText.includes('Configuration de la base de données') ||
                responseText.includes('step=3') ||
                responseText.includes('step active') && responseText.includes('3')) {
                // Redirection après nettoyage
                setTimeout(() => {
                    window.location.href = 'install_new.php?step=3';
                }, 100);
                return;
            } else {
                // Si c'est du HTML mais pas l'étape 3, c'est probablement une erreur
                showAlert(alertContainer, 'Erreur lors de la vérification des prérequis', 'error');
                throw new Error('HTML détecté mais pas de succès confirmé');
            }
        }
        
        // Try to parse as JSON
        let result;
        try {
            result = JSON.parse(responseText);
        } catch (e) {
            // Si on ne peut pas parser le JSON, essayer de détecter le succès dans le texte
            if (responseText.includes('success') || responseText.includes('validé')) {
                showAlert(alertContainer, 'Prérequis validés ! Redirection...', 'success');
                setTimeout(() => {
                    window.location.href = 'install_new.php?step=3';
                }, 1000);
                throw new Error('Succès détecté dans la réponse texte - redirection en cours');
            }
            
            throw new Error('Réponse serveur invalide: ' + responseText.substring(0, 100));
        }
        
        if (result.success) {
            // Requirements OK - show success and proceed
            showAlert(alertContainer, result.message || 'Prérequis système validés !', 'success');
            
            // Wait a bit then redirect to step 3
            setTimeout(() => {
                if (result.redirect) {
                    window.location.href = result.redirect;
                } else {
                    window.location.href = 'install_new.php?step=3';
                }
            }, 1000);
            
        } else {
            // Requirements failed - show error
            showAlert(alertContainer, result.message || 'Erreur lors de la vérification des prérequis', 'error');
        }
        
    } catch (error) {
        showAlert(alertContainer, 'Erreur lors de la vérification des prérequis: ' + error.message, 'error');
    } finally {
        isProcessing = false;
        hideLoadingButton(submitBtn);
    }
}

/**
 * Verify step 3 database configuration via AJAX
 */
async function verifyStep3Ajax(form) {
    if (isProcessing) {
        return;
    }
    
    const submitBtn = form.querySelector('button[type="submit"]');
    const alertContainer = form.querySelector('.alert-container') || createAlertContainer(form);
    
    // Clear previous errors
    clearFormErrors(form);
    hideAlert(alertContainer);
    
    // Validate form
    if (!validateForm(form)) {
        return;
    }
    
    // Show loading state
    isProcessing = true;
    showLoadingButton(submitBtn);
    
    try {
        // Prepare form data
        const formData = new FormData(form);
        formData.append('ajax', '1');
        formData.append('step', '3');
        
        // Send AJAX request
        const response = await fetch('install_new.php', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        // Check if response is OK
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        // Get response text first to debug
        const responseText = await response.text();
        
        // Vérifier si c'est une redirection HTML au lieu de JSON
        if (responseText.includes('<html') || responseText.includes('<!DOCTYPE')) {
            // Vérifier si c'est vraiment un succès en cherchant des indicateurs dans le HTML
            if (responseText.includes('Configuration du compte admin') ||
                responseText.includes('step=4') ||
                responseText.includes('step active') && responseText.includes('4')) {
                // Redirection après nettoyage
                setTimeout(() => {
                    window.location.href = 'install_new.php?step=4';
                }, 100);
                return;
            } else {
                // Si c'est du HTML mais pas l'étape 4, c'est probablement une erreur
                showAlert(alertContainer, 'Erreur lors de la configuration de la base de données', 'error');
                throw new Error('HTML détecté mais pas de succès confirmé');
            }
        }
        
        // Try to parse as JSON
        let result;
        try {
            result = JSON.parse(responseText);
        } catch (e) {
            // Si on ne peut pas parser le JSON, essayer de détecter le succès dans le texte
            if (responseText.includes('success') || responseText.includes('validé')) {
                showAlert(alertContainer, 'Configuration validée ! Redirection...', 'success');
                setTimeout(() => {
                    window.location.href = 'install_new.php?step=4';
                }, 1000);
                throw new Error('Succès détecté dans la réponse texte - redirection en cours');
            }
            
            throw new Error('Réponse serveur invalide: ' + responseText.substring(0, 100));
        }
        
        if (result.success) {
            // Database config OK - show success and proceed
            showAlert(alertContainer, result.message || 'Configuration de base de données validée !', 'success');
            
            // Wait a bit then redirect to step 4
            setTimeout(() => {
                if (result.redirect) {
                    window.location.href = result.redirect;
                } else {
                    window.location.href = 'install_new.php?step=4';
                }
            }, 1000);
            
        } else {
            // Database config failed - show error
            showAlert(alertContainer, result.message || 'Erreur lors de la configuration de la base de données', 'error');
        }
        
    } catch (error) {
        showAlert(alertContainer, 'Erreur lors de la configuration de la base de données: ' + error.message, 'error');
    } finally {
        isProcessing = false;
        hideLoadingButton(submitBtn);
    }
}

/**
 * Verify step 4 admin configuration via AJAX
 */
async function verifyStep4Ajax(form) {
    if (isProcessing) {
        return;
    }
    
    const submitBtn = form.querySelector('button[type="submit"]');
    const alertContainer = form.querySelector('.alert-container') || createAlertContainer(form);
    
    // Clear previous errors
    clearFormErrors(form);
    hideAlert(alertContainer);
    
    // Validate form
    if (!validateForm(form)) {
        return;
    }
    
    // Show loading state
    isProcessing = true;
    showLoadingButton(submitBtn);
    
    try {
        // Prepare form data
        const formData = new FormData(form);
        formData.append('ajax', '1');
        formData.append('step', '4');
        
        // Send AJAX request
        const response = await fetch('install_new.php', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        // Check if response is OK
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        // Get response text first to debug
        const responseText = await response.text();
        
        // Vérifier si c'est une redirection HTML au lieu de JSON
        if (responseText.includes('<html') || responseText.includes('<!DOCTYPE')) {
            // Vérifier si c'est vraiment un succès en cherchant des indicateurs dans le HTML
            if (responseText.includes('Installation finale') ||
                responseText.includes('step=5') ||
                responseText.includes('step active') && responseText.includes('5')) {
                // Redirection après nettoyage
                setTimeout(() => {
                    window.location.href = 'install_new.php?step=5';
                }, 100);
                return;
            } else {
                // Si c'est du HTML mais pas l'étape 5, c'est probablement une erreur
                showAlert(alertContainer, 'Erreur lors de la configuration administrateur', 'error');
                throw new Error('HTML détecté mais pas de succès confirmé');
            }
        }
        
        // Try to parse as JSON
        let result;
        try {
            result = JSON.parse(responseText);
        } catch (e) {
            // Si on ne peut pas parser le JSON, essayer de détecter le succès dans le texte
            if (responseText.includes('success') || responseText.includes('validé')) {
                showAlert(alertContainer, 'Configuration validée ! Redirection...', 'success');
                setTimeout(() => {
                    window.location.href = 'install_new.php?step=5';
                }, 1000);
                throw new Error('Succès détecté dans la réponse texte - redirection en cours');
            }
            
            throw new Error('Réponse serveur invalide: ' + responseText.substring(0, 100));
        }
        
        if (result.success) {
            // Admin config OK - show success and proceed
            showAlert(alertContainer, result.message || 'Configuration administrateur validée !', 'success');
            
            // Wait a bit then redirect to step 5
            setTimeout(() => {
                if (result.redirect) {
                    window.location.href = result.redirect;
                } else {
                    window.location.href = 'install_new.php?step=5';
                }
            }, 1000);
            
        } else {
            // Admin config failed - show error
            showAlert(alertContainer, result.message || 'Erreur lors de la configuration administrateur', 'error');
        }
        
    } catch (error) {
        showAlert(alertContainer, 'Erreur lors de la configuration administrateur: ' + error.message, 'error');
    } finally {
        isProcessing = false;
        hideLoadingButton(submitBtn);
    }
}

/**
 * Verify step 5 final installation via AJAX
 */
async function verifyStep5Ajax(form) {
    if (isProcessing) {
        return;
    }
    
    const submitBtn = form.querySelector('button[type="submit"]');
    const alertContainer = form.querySelector('.alert-container') || createAlertContainer(form);
    
    // Clear previous errors
    clearFormErrors(form);
    hideAlert(alertContainer);
    
    // Show loading state with specific message for installation
    isProcessing = true;
    showLoadingButton(submitBtn, 'Installation en cours...');
    
    // Show progress message
    showAlert(alertContainer, '🚀 Installation en cours... Cela peut prendre quelques minutes.', 'info');
    
    try {
        // Prepare form data
        const formData = new FormData(form);
        formData.append('ajax', '1');
        formData.append('step', '5');
        
        // Send AJAX request with longer timeout for installation
        const response = await fetch('install_new.php', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        // Check if response is OK
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        // Get response text first to debug
        const responseText = await response.text();
        
        // Vérifier si c'est une redirection HTML au lieu de JSON
        if (responseText.includes('<html') || responseText.includes('<!DOCTYPE')) {
            // Vérifier si c'est vraiment un succès en cherchant des indicateurs dans le HTML
            if (responseText.includes('Installation terminée') ||
                responseText.includes('success=1') ||
                responseText.includes('Félicitations')) {
                // Redirection après nettoyage
                showAlert(alertContainer, '🎉 Installation terminée ! Redirection vers la page de succès...', 'success');
                setTimeout(() => {
                    window.location.href = 'install_new.php?success=1';
                }, 2000);
                return;
            } else {
                // Si c'est du HTML mais pas de succès, c'est probablement une erreur
                showAlert(alertContainer, 'Erreur lors de l\'installation finale', 'error');
                throw new Error('HTML détecté mais pas de succès confirmé');
            }
        }
        
        // Try to parse as JSON
        let result;
        try {
            result = JSON.parse(responseText);
        } catch (e) {
            // Si on ne peut pas parser le JSON, essayer de détecter le succès dans le texte
            if (responseText.includes('success') || responseText.includes('terminé')) {
                showAlert(alertContainer, '🎉 Installation terminée ! Redirection...', 'success');
                setTimeout(() => {
                    window.location.href = 'install_new.php?success=1';
                }, 2000);
                throw new Error('Succès détecté dans la réponse texte - redirection en cours');
            }
            
            throw new Error('Réponse serveur invalide: ' + responseText.substring(0, 100));
        }
        
        if (result.success) {
            // Installation successful - show success and redirect
            showAlert(alertContainer, '🎉 ' + (result.message || 'Installation terminée avec succès !'), 'success');
            
            // Wait a bit longer then redirect to success page
            setTimeout(() => {
                if (result.redirect) {
                    window.location.href = result.redirect;
                } else {
                    window.location.href = 'install_new.php?success=1';
                }
            }, 2000);
            
        } else {
            // Installation failed - show error
            showAlert(alertContainer, '❌ ' + (result.message || 'Erreur lors de l\'installation finale'), 'error');
        }
        
    } catch (error) {
        if (!error.message.includes('Succès détecté')) {
            showAlert(alertContainer, 'Erreur lors de l\'installation finale: ' + error.message, 'error');
        }
    } finally {
        if (!error || !error.message.includes('Succès détecté')) {
            isProcessing = false;
            hideLoadingButton(submitBtn, 'Installer maintenant');
        }
    }
}

/**
 * Test database connection via AJAX
 */
async function testDatabaseConnection(form) {
    if (isProcessing) {
        return;
    }
    
    const testBtn = form.querySelector('#test-db-btn');
    const alertContainer = form.querySelector('.db-test-alert') || createDbTestAlertContainer(form);
    
    // Clear previous alerts
    hideAlert(alertContainer);
    
    // Validate required fields
    const requiredFields = ['db_host', 'db_port', 'db_name', 'db_user'];
    let hasErrors = false;
    
    requiredFields.forEach(fieldName => {
        const field = form.querySelector(`[name="${fieldName}"]`);
        if (field && !field.value.trim()) {
            showFieldError(field, 'Ce champ est requis');
            hasErrors = true;
        }
    });
    
    if (hasErrors) {
        showAlert(alertContainer, 'Veuillez remplir tous les champs requis avant de tester la connexion', 'error');
        return;
    }
    
    // Show loading state
    isProcessing = true;
    showLoadingButton(testBtn, 'Test en cours...');
    
    try {
        // Prepare form data
        const formData = new FormData(form);
        formData.append('action', 'test_db_connection');
        formData.append('ajax', '1');
        
        // Send AJAX request
        const response = await fetch('install_new.php', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        // Check if response is OK
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        // Parse JSON response
        const result = await response.json();
        
        if (result.success) {
            // Connection successful
            let message = result.message;
            if (result.details) {
                message += '<br><small>';
                Object.values(result.details).forEach(detail => {
                    message += `<br>• ${detail}`;
                });
                message += '</small>';
            }
            showAlert(alertContainer, message, 'success');
        } else {
            // Connection failed
            let message = result.message;
            if (result.details) {
                message += '<br><small>';
                Object.values(result.details).forEach(detail => {
                    message += `<br>• ${detail}`;
                });
                message += '</small>';
            }
            showAlert(alertContainer, message, 'error');
        }
        
    } catch (error) {
        showAlert(alertContainer, 'Erreur lors du test de connexion: ' + error.message, 'error');
    } finally {
        isProcessing = false;
        hideLoadingButton(testBtn, 'Tester la connexion');
    }
}

/**
 * Create DB test alert container if it doesn't exist
 */
function createDbTestAlertContainer(form) {
    let container = form.querySelector('.db-test-alert');
    if (!container) {
        container = document.createElement('div');
        container.className = 'db-test-alert';
        container.style.marginBottom = '1rem';
        
        // Insert after the form fields, before the buttons
        const actions = form.querySelector('.form-actions');
        if (actions) {
            actions.parentNode.insertBefore(container, actions);
        } else {
            form.appendChild(container);
        }
    }
    
    return container;
}

/**
 * Create alert container if it doesn't exist
 */
function createAlertContainer(form) {
    const container = document.createElement('div');
    container.className = 'alert-container';
    container.style.marginBottom = '1.5rem';
    
    // Insert after form title
    const title = form.querySelector('.step-title');
    if (title && title.nextSibling) {
        title.parentNode.insertBefore(container, title.nextSibling);
    } else {
        form.insertBefore(container, form.firstChild);
    }
    
    return container;
}

/**
 * Show alert message
 */
function showAlert(container, message, type = 'info') {
    container.innerHTML = `
        <div class="alert alert-${type}" style="animation: slideDown 0.3s ease;">
            ${message}
        </div>
    `;
}

/**
 * Hide alert message
 */
function hideAlert(container) {
    container.innerHTML = '';
}

/**
 * Show loading state on button
 */
function showLoadingButton(button, loadingText = 'Vérification en cours...') {
    button.disabled = true;
    button.classList.add('loading');
    
    const originalText = button.textContent;
    button.dataset.originalText = originalText;
    button.innerHTML = `
        <span class="loading-spinner" style="width: 16px; height: 16px; margin-right: 8px;"></span>
        ${loadingText}
    `;
}

/**
 * Hide loading state on button
 */
function hideLoadingButton(button, originalText = null) {
    button.disabled = false;
    button.classList.remove('loading');
    
    if (originalText) {
        button.textContent = originalText;
    } else {
        button.textContent = button.dataset.originalText || 'Vérifier la licence';
    }
}

/**
 * Clear all form errors
 */
function clearFormErrors(form) {
    const errorElements = form.querySelectorAll('.form-error');
    errorElements.forEach(error => error.remove());
    
    const errorInputs = form.querySelectorAll('.form-input.error');
    errorInputs.forEach(input => input.classList.remove('error'));
}