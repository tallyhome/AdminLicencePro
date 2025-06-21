<?php
/**
 * Template de pied de page pour l'installation
 */
?>
        </div> <!-- Fin du contenu -->
        
        <div class="footer">
            <div class="footer-content">
                <p>&copy; <?php echo date('Y'); ?> AdminLicence. Tous droits réservés.</p>
                <div class="footer-links">
                    <a href="https://adminlicence.com/support" target="_blank"><?php echo t('support'); ?></a>
                    <a href="https://adminlicence.com/documentation" target="_blank"><?php echo t('documentation'); ?></a>
                </div>
            </div>
        </div>
    </div> <!-- Fin du container -->
    
    <!-- Scripts JavaScript -->
    <script src="assets/js/install.js"></script>
    
    <!-- Script d'initialisation -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialiser le gestionnaire d'installation
            const installer = new InstallationManager();
            
            // Masquer l'overlay de chargement
            const loadingOverlay = document.querySelector('.loading-overlay');
            if (loadingOverlay) {
                setTimeout(() => {
                    loadingOverlay.style.opacity = '0';
                    setTimeout(() => {
                        loadingOverlay.style.display = 'none';
                    }, 300);
                }, 500);
            }
            
            // Auto-masquer les alertes après 5 secondes
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    hideAlert(alert);
                }, 5000);
            });
            
            // L'InstallationManager est déjà initialisé dans install.js
            // et gère automatiquement la validation des formulaires
        });
    </script>
</body>
</html>