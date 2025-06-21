/**
 * AdminLicence Installation - JavaScript
 * Version: 2.0.0
 * Modern installation wizard interactions
 */

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
        setTimeout(hideLoading, 500);
    });
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