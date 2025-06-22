<?php
/**
 * Template de pied de page pour l'installation
 */
?>
        </div> <!-- Fin du contenu -->
        
        <!-- Footer -->
        <div class="footer">
            <div class="footer-content">
                <p>&copy; <?php echo date('Y'); ?> AdminLicence. <?php echo t('all_rights_reserved'); ?></p>
                <div class="footer-links">
                    <a href="#" onclick="return false;"><?php echo t('support'); ?></a>
                    <a href="#" onclick="return false;"><?php echo t('documentation'); ?></a>
                </div>
            </div>
        </div>
    </div> <!-- Fin du container -->
    
    <!-- JavaScript -->
    <script src="assets/js/install.js"></script>
    
    <!-- Additional JavaScript for modern interactions -->
    <script>
        // Auto-hide loading overlay after page load
        window.addEventListener('load', function() {
            setTimeout(function() {
                const overlay = document.getElementById('loadingOverlay');
                if (overlay) {
                    overlay.style.display = 'none';
                }
            }, 300);
        });
        
        // Add smooth transitions to form elements
        document.addEventListener('DOMContentLoaded', function() {
            const formElements = document.querySelectorAll('.form-group');
            formElements.forEach(function(element, index) {
                element.style.opacity = '0';
                element.style.transform = 'translateY(20px)';
                
                setTimeout(function() {
                    element.style.transition = 'all 0.4s ease';
                    element.style.opacity = '1';
                    element.style.transform = 'translateY(0)';
                }, index * 100 + 200);
            });
        });
    </script>
</body>
</html>