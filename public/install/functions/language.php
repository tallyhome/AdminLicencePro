<?php
/**
 * Fonctions de gestion des langues pour l'installateur
 */

/**
 * Initialise la langue en fonction des préférences de l'utilisateur
 * 
 * @return string Code de la langue sélectionnée
 */
function initLanguage() {
    // Vérifier si une langue est déjà définie en session
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    // Si la langue est spécifiée dans l'URL, l'utiliser
    if (isset($_GET['language']) && array_key_exists($_GET['language'], AVAILABLE_LANGUAGES)) {
        $_SESSION['installer_language'] = $_GET['language'];
        return $_SESSION['installer_language'];
    }
    
    // Si la langue est spécifiée dans le formulaire, l'utiliser
    if (isset($_POST['language']) && array_key_exists($_POST['language'], AVAILABLE_LANGUAGES)) {
        $_SESSION['installer_language'] = $_POST['language'];
        return $_SESSION['installer_language'];
    }
    
    // Si la langue est déjà définie en session, l'utiliser
    if (isset($_SESSION['installer_language']) && array_key_exists($_SESSION['installer_language'], AVAILABLE_LANGUAGES)) {
        return $_SESSION['installer_language'];
    }
    
    // Sinon, détecter la langue du navigateur
    $browserLang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? 'fr', 0, 2);
    $_SESSION['installer_language'] = array_key_exists($browserLang, AVAILABLE_LANGUAGES) ? $browserLang : DEFAULT_LANGUAGE;
    
    return $_SESSION['installer_language'];
}

/**
 * Charge les traductions pour une langue donnée
 * 
 * @param string $lang Code de la langue
 * @return array Tableau des traductions
 */
function loadTranslations($lang) {
    $langFile = INSTALL_PATH . '/languages/' . $lang . '.php';
    
    if (file_exists($langFile)) {
        return include $langFile;
    }
    
    // Fallback sur la langue par défaut
    $defaultLangFile = INSTALL_PATH . '/languages/' . DEFAULT_LANGUAGE . '.php';
    if (file_exists($defaultLangFile)) {
        return include $defaultLangFile;
    }
    
    // Si aucun fichier de langue n'est trouvé, retourner un tableau vide
    return [];
}

/**
 * Fonction de traduction simplifiée pour l'installateur
 * 
 * @param string $key Clé de traduction
 * @param array $replacements Remplacements à effectuer
 * @return string Texte traduit
 */
function t($key, $replacements = []) {
    $currentLang = getCurrentLanguage();
    
    // Traductions en dur pour l'installateur
    $translations = [
        'fr' => [
            // Général
            'installation' => 'Installation',
            'next' => 'Suivant',
            'back' => 'Retour',
            'finish' => 'Terminer',
            'cancel' => 'Annuler',
            'error' => 'Erreur',
            'success' => 'Succès',
            'warning' => 'Attention',
            'info' => 'Information',
            'required' => 'Requis',
            'optional' => 'Optionnel',
            'system_requirements' => 'Prérequis système',
            'system_requirements_description' => 'Vérification des extensions PHP et des permissions de fichiers',
            'php_version' => 'Version PHP',
            'php_extensions' => 'Extensions PHP',
            'file_permissions' => 'Permissions de fichiers',
            'extension_required' => 'Requis',
            'extension_optional' => 'Optionnel',
            'permission_writable' => 'Écriture',
            'permission_readable' => 'Lecture',
            'status_ok' => 'OK',
            'status_warning' => 'Attention',
            'status_error' => 'Erreur',
            'check_system_requirements' => 'Vérifier les prérequis',
            'requirements_met' => 'Tous les prérequis sont satisfaits',
            'requirements_issues' => 'Certains prérequis ne sont pas satisfaits',
            
            // Interface
            'installation_title' => 'Installation AdminLicence',
            'installation_assistant' => 'Assistant d\'installation pour AdminLicence',
            'license_api' => 'API de licence',
            'required_format' => 'Format requis',
            'all_rights_reserved' => 'Tous droits réservés.',
            'support' => 'Support',
            'documentation' => 'Documentation',
            'step_labels' => [
                1 => 'Licence',
                2 => 'Prérequis système',
                3 => 'Base de données',
                4 => 'Administrateur',
                5 => 'Finalisation'
            ],
            
            // Licence
            'license_key' => 'Clé de licence',
            'license_key_placeholder' => 'XXXX-XXXX-XXXX-XXXX',
            'license_verification' => 'Vérification de la licence',
            'license_verification_description' => 'Vérifiez votre clé de licence pour continuer',
            'verify_license' => 'Vérifier la licence',
            'license_valid' => 'Licence valide',
            'license_invalid' => 'Licence invalide',
            'license_required' => 'La clé de licence est requise',
            'license_key_required' => 'La clé de licence est requise',
            'license_valid_next_step' => 'Licence valide ! Passage à l\'étape suivante...',
            
            // Base de données
            'database_configuration' => 'Configuration de la base de données',
            'database_configuration_description' => 'Configurez la connexion à votre base de données MySQL',
            'db_host' => 'Hôte de la base de données',
            'db_port' => 'Port',
            'db_name' => 'Nom de la base de données',
            'db_user' => 'Utilisateur',
            'db_password' => 'Mot de passe',
            'database_will_be_created' => 'La base de données sera créée si elle n\'existe pas',
            'leave_empty_if_no_password' => 'Laissez vide si aucun mot de passe',
            'test_connection' => 'Tester la connexion',
            'connection_successful' => 'Connexion réussie',
            'connection_failed' => 'Échec de la connexion',
            
            // Admin
            'admin_setup' => 'Configuration de l\'administrateur',
            'admin_setup_description' => 'Créez le compte administrateur principal',
            'admin_name' => 'Nom de l\'administrateur',
            'admin_email' => 'Email de l\'administrateur',
            'admin_password' => 'Mot de passe',
            'admin_password_confirm' => 'Confirmer le mot de passe',
            'passwords_dont_match' => 'Les mots de passe ne correspondent pas',
            
            // Finalisation
            'finalization' => 'Finalisation',
            'finalization_description' => 'Installation et configuration finale',
            'installation_complete' => 'Installation terminée',
            'installation_success' => 'L\'installation s\'est déroulée avec succès',
            'go_to_admin' => 'Aller à l\'administration',
            
            // Erreurs
            'installation_failed' => 'L\'installation a échoué',
            'database_error' => 'Erreur de base de données',
            'file_permission_error' => 'Erreur de permissions de fichier',
            'already_installed' => 'Application déjà installée'
        ],
        'en' => [
            // General
            'installation' => 'Installation',
            'next' => 'Next',
            'back' => 'Back',
            'finish' => 'Finish',
            'cancel' => 'Cancel',
            'error' => 'Error',
            'success' => 'Success',
            'warning' => 'Warning',
            'info' => 'Information',
            'required' => 'Required',
            'optional' => 'Optional',
            'system_requirements' => 'System Requirements',
            'system_requirements_description' => 'Verify PHP extensions and file permissions',
            'php_version' => 'PHP Version',
            'php_extensions' => 'PHP Extensions',
            'file_permissions' => 'File Permissions',
            'extension_required' => 'Required',
            'extension_optional' => 'Optional',
            'permission_writable' => 'Writable',
            'permission_readable' => 'Readable',
            'status_ok' => 'OK',
            'status_warning' => 'Warning',
            'status_error' => 'Error',
            'check_system_requirements' => 'Check Requirements',
            'requirements_met' => 'All requirements are satisfied',
            'requirements_issues' => 'Some requirements are not satisfied',
            
            // Interface
            'installation_title' => 'AdminLicence Installation',
            'installation_assistant' => 'Installation assistant for AdminLicence',
            'license_api' => 'License API',
            'required_format' => 'Required format',
            'all_rights_reserved' => 'All rights reserved.',
            'support' => 'Support',
            'documentation' => 'Documentation',
            'step_labels' => [
                1 => 'License',
                2 => 'System Requirements',
                3 => 'Database',
                4 => 'Administrator',
                5 => 'Finalization'
            ],
            
            // License
            'license_key' => 'License Key',
            'license_key_placeholder' => 'XXXX-XXXX-XXXX-XXXX',
            'license_verification' => 'License Verification',
            'license_verification_description' => 'Verify your license key to continue',
            'verify_license' => 'Verify License',
            'license_valid' => 'License Valid',
            'license_invalid' => 'License Invalid',
            'license_required' => 'License key is required',
            'license_key_required' => 'License key is required',
            'license_valid_next_step' => 'License valid! Proceeding to next step...',
            
            // Database
            'database_configuration' => 'Database Configuration',
            'database_configuration_description' => 'Configure your MySQL database connection',
            'db_host' => 'Database Host',
            'db_port' => 'Port',
            'db_name' => 'Database Name',
            'db_user' => 'Username',
            'db_password' => 'Password',
            'database_will_be_created' => 'Database will be created if it doesn\'t exist',
            'leave_empty_if_no_password' => 'Leave empty if no password',
            'test_connection' => 'Test Connection',
            'connection_successful' => 'Connection Successful',
            'connection_failed' => 'Connection Failed',
            
            // Admin
            'admin_setup' => 'Admin Setup',
            'admin_setup_description' => 'Create the main administrator account',
            'admin_name' => 'Administrator Name',
            'admin_email' => 'Administrator Email',
            'admin_password' => 'Password',
            'admin_password_confirm' => 'Confirm Password',
            'passwords_dont_match' => 'Passwords do not match',
            
            // Finalization
            'finalization' => 'Finalization',
            'finalization_description' => 'Final installation and configuration',
            'installation_complete' => 'Installation Complete',
            'installation_success' => 'Installation completed successfully',
            'go_to_admin' => 'Go to Administration',
            
            // Errors
            'installation_failed' => 'Installation failed',
            'database_error' => 'Database error',
            'file_permission_error' => 'File permission error',
            'already_installed' => 'Application already installed'
        ],
        'es' => [
            // General
            'installation' => 'Instalación',
            'next' => 'Siguiente',
            'back' => 'Atrás',
            'finish' => 'Finalizar',
            'cancel' => 'Cancelar',
            'error' => 'Error',
            'success' => 'Éxito',
            'warning' => 'Advertencia',
            'info' => 'Información',
            'required' => 'Requerido',
            'optional' => 'Opcional',
            'system_requirements' => 'Requisitos del Sistema',
            'system_requirements_description' => 'Verificar extensiones PHP y permisos de archivos',
            'php_version' => 'Versión PHP',
            'php_extensions' => 'Extensiones PHP',
            'file_permissions' => 'Permisos de Archivos',
            'extension_required' => 'Requerido',
            'extension_optional' => 'Opcional',
            'permission_writable' => 'Escritura',
            'permission_readable' => 'Lectura',
            'status_ok' => 'OK',
            'status_warning' => 'Advertencia',
            'status_error' => 'Error',
            'check_system_requirements' => 'Verificar Requisitos',
            'requirements_met' => 'Todos los requisitos se cumplen',
            'requirements_issues' => 'Algunos requisitos no se cumplen',
            
            // Interface
            'installation_title' => 'Instalación AdminLicence',
            'installation_assistant' => 'Asistente de instalación para AdminLicence',
            'license_api' => 'API de licencia',
            'required_format' => 'Formato requerido',
            'all_rights_reserved' => 'Todos los derechos reservados.',
            'support' => 'Soporte',
            'documentation' => 'Documentación',
            'step_labels' => [
                1 => 'Licencia',
                2 => 'Requisitos del Sistema',
                3 => 'Base de datos',
                4 => 'Administrador',
                5 => 'Finalización'
            ],
            
            // License
            'license_key' => 'Clave de Licencia',
            'license_key_placeholder' => 'XXXX-XXXX-XXXX-XXXX',
            'license_verification' => 'Verificación de Licencia',
            'license_verification_description' => 'Verifique su clave de licencia para continuar',
            'verify_license' => 'Verificar Licencia',
            'license_valid' => 'Licencia Válida',
            'license_invalid' => 'Licencia Inválida',
            'license_required' => 'La clave de licencia es requerida',
            'license_key_required' => 'La clave de licencia es requerida',
            'license_valid_next_step' => '¡Licencia válida! Procediendo al siguiente paso...',
            
            // Database
            'database_configuration' => 'Configuración de Base de Datos',
            'database_configuration_description' => 'Configure su conexión de base de datos',
            'db_host' => 'Host de Base de Datos',
            'db_port' => 'Puerto',
            'db_name' => 'Nombre de Base de Datos',
            'db_user' => 'Usuario',
            'db_password' => 'Contraseña',
            'test_connection' => 'Probar Conexión',
            'connection_successful' => 'Conexión Exitosa',
            'connection_failed' => 'Conexión Fallida',
            
            // Admin
            'admin_setup' => 'Configuración de Administrador',
            'admin_setup_description' => 'Crear la cuenta principal de administrador',
            'admin_name' => 'Nombre del Administrador',
            'admin_email' => 'Email del Administrador',
            'admin_password' => 'Contraseña',
            'admin_password_confirm' => 'Confirmar Contraseña',
            'passwords_dont_match' => 'Las contraseñas no coinciden',
            
            // Finalization
            'finalization' => 'Finalización',
            'finalization_description' => 'Instalación y configuración final',
            'installation_complete' => 'Instalación Completa',
            'installation_success' => 'Instalación completada exitosamente',
            'go_to_admin' => 'Ir a Administración',
            
            // Errors
            'installation_failed' => 'La instalación falló',
            'database_error' => 'Error de base de datos',
            'file_permission_error' => 'Error de permisos de archivo',
            'already_installed' => 'Aplicación ya instalada'
        ],
        'de' => [
            // General
            'installation' => 'Installation',
            'next' => 'Weiter',
            'back' => 'Zurück',
            'finish' => 'Fertig',
            'cancel' => 'Abbrechen',
            'error' => 'Fehler',
            'success' => 'Erfolg',
            'warning' => 'Warnung',
            'info' => 'Information',
            'required' => 'Erforderlich',
            'optional' => 'Optional',
            'system_requirements' => 'Systemanforderungen',
            'system_requirements_description' => 'Überprüfen Sie PHP-Erweiterungen und Berechtigungen',
            'php_version' => 'PHP-Version',
            'php_extensions' => 'PHP-Erweiterungen',
            'file_permissions' => 'Dateiberechtigungen',
            'extension_required' => 'Erforderlich',
            'extension_optional' => 'Optional',
            'permission_writable' => 'Schreiben',
            'permission_readable' => 'Lesen',
            'status_ok' => 'OK',
            'status_warning' => 'Warnung',
            'status_error' => 'Fehler',
            'check_system_requirements' => 'Systemanforderungen überprüfen',
            'requirements_met' => 'Alle Systemanforderungen sind erfüllt',
            'requirements_issues' => 'Nicht alle Systemanforderungen sind erfüllt',
            
            // Interface
            'installation_title' => 'AdminLicence Installation',
            'installation_assistant' => 'Installations-Assistent für AdminLicence',
            'license_api' => 'Lizenz-API',
            'required_format' => 'Erforderliches Format',
            'all_rights_reserved' => 'Alle Rechte vorbehalten.',
            'support' => 'Support',
            'documentation' => 'Dokumentation',
            'step_labels' => [
                1 => 'Lizenz',
                2 => 'Systemanforderungen',
                3 => 'Datenbank',
                4 => 'Administrator',
                5 => 'Fertigstellung'
            ],
            
            // License
            'license_key' => 'Lizenzschlüssel',
            'license_key_placeholder' => 'XXXX-XXXX-XXXX-XXXX',
            'license_verification' => 'Lizenzverifizierung',
            'license_verification_description' => 'Verifizieren Sie Ihren Lizenzschlüssel, um fortzufahren',
            'verify_license' => 'Lizenz Verifizieren',
            'license_valid' => 'Lizenz Gültig',
            'license_invalid' => 'Lizenz Ungültig',
            'license_required' => 'Lizenzschlüssel ist erforderlich',
            'license_key_required' => 'Lizenzschlüssel ist erforderlich',
            'license_valid_next_step' => 'Lizenz gültig! Weiter zum nächsten Schritt...',
            
            // Database
            'database_configuration' => 'Datenbank-Konfiguration',
            'database_configuration_description' => 'Konfigurieren Sie Ihre Datenbankverbindung',
            'db_host' => 'Datenbank-Host',
            'db_port' => 'Port',
            'db_name' => 'Datenbankname',
            'db_user' => 'Benutzername',
            'db_password' => 'Passwort',
            'test_connection' => 'Verbindung Testen',
            'connection_successful' => 'Verbindung Erfolgreich',
            'connection_failed' => 'Verbindung Fehlgeschlagen',
            
            // Admin
            'admin_setup' => 'Administrator-Setup',
            'admin_setup_description' => 'Erstellen Sie das Haupt-Administratorkonto',
            'admin_name' => 'Administrator-Name',
            'admin_email' => 'Administrator-E-Mail',
            'admin_password' => 'Passwort',
            'admin_password_confirm' => 'Passwort Bestätigen',
            'passwords_dont_match' => 'Passwörter stimmen nicht überein',
            
            // Finalization
            'finalization' => 'Finalisierung',
            'finalization_description' => 'Abschließende Installation und Konfiguration',
            'installation_complete' => 'Installation Abgeschlossen',
            'installation_success' => 'Installation erfolgreich abgeschlossen',
            'go_to_admin' => 'Zur Administration',
            
            // Errors
            'installation_failed' => 'Installation fehlgeschlagen',
            'database_error' => 'Datenbankfehler',
            'file_permission_error' => 'Dateiberechtigungsfehler',
            'already_installed' => 'Anwendung bereits installiert'
        ],
        'it' => [
            // General
            'installation' => 'Installazione',
            'next' => 'Avanti',
            'back' => 'Indietro',
            'finish' => 'Termina',
            'cancel' => 'Annulla',
            'error' => 'Errore',
            'success' => 'Successo',
            'warning' => 'Avviso',
            'info' => 'Informazione',
            'required' => 'Richiesto',
            'optional' => 'Opzionale',
            'system_requirements' => 'Requisiti di Sistema',
            'system_requirements_description' => 'Verifica estensioni PHP e permessi',
            'php_version' => 'Versione PHP',
            'php_extensions' => 'Estensioni PHP',
            'file_permissions' => 'Permessi File',
            'extension_required' => 'Richiesto',
            'extension_optional' => 'Opzionale',
            'permission_writable' => 'Scrittura',
            'permission_readable' => 'Lettura',
            'status_ok' => 'OK',
            'status_warning' => 'Attenzione',
            'status_error' => 'Errore',
            'check_system_requirements' => 'Verifica Requisiti',
            'requirements_met' => 'Tutti i requisiti sono soddisfatti',
            'requirements_issues' => 'Alcuni requisiti non sono soddisfatti',
            
            // Interface
            'installation_title' => 'Installazione AdminLicence',
            'installation_assistant' => 'Assistente di installazione per AdminLicence',
            'license_api' => 'API di licenza',
            'required_format' => 'Formato richiesto',
            'all_rights_reserved' => 'Tutti i diritti riservati.',
            'support' => 'Supporto',
            'documentation' => 'Documentazione',
            'step_labels' => [
                1 => 'Licenza',
                2 => 'Requisiti di Sistema',
                3 => 'Database',
                4 => 'Amministratore',
                5 => 'Finalizzazione'
            ],
            
            // License
            'license_key' => 'Chiave di Licenza',
            'license_key_placeholder' => 'XXXX-XXXX-XXXX-XXXX',
            'license_verification' => 'Verifica Licenza',
            'license_verification_description' => 'Verifica la tua chiave di licenza per continuare',
            'verify_license' => 'Verifica Licenza',
            'license_valid' => 'Licenza Valida',
            'license_invalid' => 'Licenza Non Valida',
            'license_required' => 'La chiave di licenza è richiesta',
            'license_key_required' => 'La chiave di licenza è richiesta',
            'license_valid_next_step' => 'Licenza valida! Procedendo al passo successivo...',
            
            // Database
            'database_configuration' => 'Configurazione Database',
            'database_configuration_description' => 'Configura la connessione al database',
            'db_host' => 'Host Database',
            'db_port' => 'Porta',
            'db_name' => 'Nome Database',
            'db_user' => 'Utente',
            'db_password' => 'Password',
            'test_connection' => 'Testa Connessione',
            'connection_successful' => 'Connessione Riuscita',
            'connection_failed' => 'Connessione Fallita',
            
            // Admin
            'admin_setup' => 'Configurazione Amministratore',
            'admin_setup_description' => 'Crea l\'account amministratore principale',
            'admin_name' => 'Nome Amministratore',
            'admin_email' => 'Email Amministratore',
            'admin_password' => 'Password',
            'admin_password_confirm' => 'Conferma Password',
            'passwords_dont_match' => 'Le password non corrispondono',
            
            // Finalization
            'finalization' => 'Finalizzazione',
            'finalization_description' => 'Installazione e configurazione finale',
            'installation_complete' => 'Installazione Completata',
            'installation_success' => 'Installazione completata con successo',
            'go_to_admin' => 'Vai all\'Amministrazione',
            
            // Errors
            'installation_failed' => 'Installazione fallita',
            'database_error' => 'Errore database',
            'file_permission_error' => 'Errore permessi file',
            'already_installed' => 'Applicazione già installata'
        ],
        'pt' => [
            // General
            'installation' => 'Instalação',
            'next' => 'Próximo',
            'back' => 'Voltar',
            'finish' => 'Finalizar',
            'cancel' => 'Cancelar',
            'error' => 'Erro',
            'success' => 'Sucesso',
            'warning' => 'Aviso',
            'info' => 'Informação',
            'required' => 'Obrigatório',
            'optional' => 'Opcional',
            'system_requirements' => 'Requisitos de Sistema',
            'system_requirements_description' => 'Verificar extensões PHP e permissões',
            'php_version' => 'Versão PHP',
            'php_extensions' => 'Extensões PHP',
            'file_permissions' => 'Permissões de Arquivo',
            'extension_required' => 'Obrigatório',
            'extension_optional' => 'Opcional',
            'permission_writable' => 'Escrita',
            'permission_readable' => 'Leitura',
            'status_ok' => 'OK',
            'status_warning' => 'Atenção',
            'status_error' => 'Erro',
            'check_system_requirements' => 'Verificar Requisitos',
            'requirements_met' => 'Todos os requisitos são atendidos',
            'requirements_issues' => 'Alguns requisitos não são atendidos',
            
            // Interface
            'installation_title' => 'Instalação AdminLicence',
            'installation_assistant' => 'Assistente de instalação para AdminLicence',
            'license_api' => 'API de licença',
            'required_format' => 'Formato obrigatório',
            'all_rights_reserved' => 'Todos os direitos reservados.',
            'support' => 'Suporte',
            'documentation' => 'Documentação',
            'step_labels' => [
                1 => 'Licença',
                2 => 'Requisitos do Sistema',
                3 => 'Base de dados',
                4 => 'Administrador',
                5 => 'Finalização'
            ],
            
            // License
            'license_key' => 'Chave de Licença',
            'license_key_placeholder' => 'XXXX-XXXX-XXXX-XXXX',
            'license_verification' => 'Verificação de Licença',
            'license_verification_description' => 'Verifique sua chave de licença para continuar',
            'verify_license' => 'Verificar Licença',
            'license_valid' => 'Licença Válida',
            'license_invalid' => 'Licença Inválida',
            'license_required' => 'A chave de licença é obrigatória',
            'license_key_required' => 'A chave de licença é obrigatória',
            'license_valid_next_step' => 'Licença válida! Prosseguindo para o próximo passo...',
            
            // Database
            'database_configuration' => 'Configuração do Banco de Dados',
            'database_configuration_description' => 'Configure sua conexão com o banco de dados',
            'db_host' => 'Host do Banco de Dados',
            'db_port' => 'Porta',
            'db_name' => 'Nome do Banco de Dados',
            'db_user' => 'Usuário',
            'db_password' => 'Senha',
            'test_connection' => 'Testar Conexão',
            'connection_successful' => 'Conexão Bem-sucedida',
            'connection_failed' => 'Falha na Conexão',
            
            // Admin
            'admin_setup' => 'Configuração do Administrador',
            'admin_setup_description' => 'Criar a conta principal do administrador',
            'admin_name' => 'Nome do Administrador',
            'admin_email' => 'Email do Administrador',
            'admin_password' => 'Senha',
            'admin_password_confirm' => 'Confirmar Senha',
            'passwords_dont_match' => 'As senhas não coincidem',
            
            // Finalization
            'finalization' => 'Finalização',
            'finalization_description' => 'Instalação e configuração final',
            'installation_complete' => 'Instalação Completa',
            'installation_success' => 'Instalação concluída com sucesso',
            'go_to_admin' => 'Ir para Administração',
            
            // Errors
            'installation_failed' => 'A instalação falhou',
            'database_error' => 'Erro de banco de dados',
            'file_permission_error' => 'Erro de permissão de arquivo',
            'already_installed' => 'Aplicação já instalada'
        ],
        'nl' => [
            // General
            'installation' => 'Installatie',
            'next' => 'Volgende',
            'back' => 'Terug',
            'finish' => 'Voltooien',
            'cancel' => 'Annuleren',
            'error' => 'Fout',
            'success' => 'Succes',
            'warning' => 'Waarschuwing',
            'info' => 'Informatie',
            'required' => 'Vereist',
            'optional' => 'Optioneel',
            'system_requirements' => 'Systeemanforderingen',
            'system_requirements_description' => 'Controleer PHP-extensies en machtigingen',
            'php_version' => 'PHP-versie',
            'php_extensions' => 'PHP-extensies',
            'file_permissions' => 'Bestandsrechten',
            'extension_required' => 'Vereist',
            'extension_optional' => 'Optioneel',
            'permission_writable' => 'Schrijven',
            'permission_readable' => 'Lezen',
            'status_ok' => 'OK',
            'status_warning' => 'Waarschuwing',
            'status_error' => 'Fout',
            'check_system_requirements' => 'Systeemanforderingen controleren',
            'requirements_met' => 'Alle systeemanforderingen zijn voldaan',
            'requirements_issues' => 'Niet alle systeemanforderingen zijn voldaan',
            
            // Interface
            'installation_title' => 'AdminLicence Installatie',
            'installation_assistant' => 'Installatie-assistent voor AdminLicence',
            'license_api' => 'Licentie API',
            'required_format' => 'Vereist formaat',
            'all_rights_reserved' => 'Alle rechten voorbehouden.',
            'support' => 'Ondersteuning',
            'documentation' => 'Documentatie',
            'step_labels' => [
                1 => 'Licentie',
                2 => 'Systeemvereisten',
                3 => 'Database',
                4 => 'Beheerder',
                5 => 'Voltooiing'
            ],
            
            // License
            'license_key' => 'Licentiesleutel',
            'license_key_placeholder' => 'XXXX-XXXX-XXXX-XXXX',
            'license_verification' => 'Licentieverificatie',
            'license_verification_description' => 'Verifieer uw licentiesleutel om door te gaan',
            'verify_license' => 'Licentie Verifiëren',
            'license_valid' => 'Licentie Geldig',
            'license_invalid' => 'Licentie Ongeldig',
            'license_required' => 'Licentiesleutel is vereist',
            'license_key_required' => 'Licentiesleutel is vereist',
            'license_valid_next_step' => 'Licentie geldig! Doorgaan naar volgende stap...',
            
            // Database
            'database_configuration' => 'Database Configuratie',
            'database_configuration_description' => 'Configureer uw databaseverbinding',
            'db_host' => 'Database Host',
            'db_port' => 'Poort',
            'db_name' => 'Database Naam',
            'db_user' => 'Gebruikersnaam',
            'db_password' => 'Wachtwoord',
            'test_connection' => 'Verbinding Testen',
            'connection_successful' => 'Verbinding Succesvol',
            'connection_failed' => 'Verbinding Mislukt',
            
            // Admin
            'admin_setup' => 'Beheerder Setup',
            'admin_setup_description' => 'Maak het hoofdbeheerdersaccount aan',
            'admin_name' => 'Beheerder Naam',
            'admin_email' => 'Beheerder Email',
            'admin_password' => 'Wachtwoord',
            'admin_password_confirm' => 'Wachtwoord Bevestigen',
            'passwords_dont_match' => 'Wachtwoorden komen niet overeen',
            
            // Finalization
            'finalization' => 'Voltooiing',
            'finalization_description' => 'Definitieve installatie en configuratie',
            'installation_complete' => 'Installatie Voltooid',
            'installation_success' => 'Installatie succesvol voltooid',
            'go_to_admin' => 'Ga naar Beheer',
            
            // Errors
            'installation_failed' => 'Installatie mislukt',
            'database_error' => 'Database fout',
            'file_permission_error' => 'Bestandsrechten fout',
            'already_installed' => 'Applicatie al geïnstalleerd'
        ],
        'ru' => [
            // General
            'installation' => 'Установка',
            'next' => 'Далее',
            'back' => 'Назад',
            'finish' => 'Завершить',
            'cancel' => 'Отмена',
            'error' => 'Ошибка',
            'success' => 'Успех',
            'warning' => 'Предупреждение',
            'info' => 'Информация',
            'required' => 'Обязательно',
            'optional' => 'Необязательно',
            'system_requirements' => 'Системные требования',
            'system_requirements_description' => 'Проверка расширений PHP и разрешений',
            'php_version' => 'Версия PHP',
            'php_extensions' => 'PHP-расширения',
            'file_permissions' => 'Права доступа',
            'extension_required' => 'Обязательно',
            'extension_optional' => 'Необязательно',
            'permission_writable' => 'Запись',
            'permission_readable' => 'Чтение',
            'status_ok' => 'OK',
            'status_warning' => 'Предупреждение',
            'status_error' => 'Ошибка',
            'check_system_requirements' => 'Проверка системных требований',
            'requirements_met' => 'Все системные требования выполнены',
            'requirements_issues' => 'Не все системные требования выполнены',
            
            // Interface
            'installation_title' => 'Установка AdminLicence',
            'installation_assistant' => 'Помощник установки для AdminLicence',
            'license_api' => 'API лицензии',
            'required_format' => 'Обязательный формат',
            'all_rights_reserved' => 'Все права защищены.',
            'support' => 'Поддержка',
            'documentation' => 'Документация',
            'step_labels' => [
                1 => 'Лицензия',
                2 => 'Системные требования',
                3 => 'База данных',
                4 => 'Администратор',
                5 => 'Завершение'
            ],
            
            // License
            'license_key' => 'Лицензионный ключ',
            'license_key_placeholder' => 'XXXX-XXXX-XXXX-XXXX',
            'license_verification' => 'Проверка лицензии',
            'license_verification_description' => 'Проверьте ваш лицензионный ключ для продолжения',
            'verify_license' => 'Проверить лицензию',
            'license_valid' => 'Лицензия действительна',
            'license_invalid' => 'Лицензия недействительна',
            'license_required' => 'Лицензионный ключ обязателен',
            'license_key_required' => 'Лицензионный ключ обязателен',
            'license_valid_next_step' => 'Лицензия действительна! Переход к следующему шагу...',
            
            // Database
            'database_configuration' => 'Конфигурация базы данных',
            'database_configuration_description' => 'Настройте подключение к базе данных',
            'db_host' => 'Хост базы данных',
            'db_port' => 'Порт',
            'db_name' => 'Имя базы данных',
            'db_user' => 'Пользователь',
            'db_password' => 'Пароль',
            'test_connection' => 'Проверить соединение',
            'connection_successful' => 'Соединение успешно',
            'connection_failed' => 'Соединение не удалось',
            
            // Admin
            'admin_setup' => 'Настройка администратора',
            'admin_setup_description' => 'Создайте основную учетную запись администратора',
            'admin_name' => 'Имя администратора',
            'admin_email' => 'Email администратора',
            'admin_password' => 'Пароль',
            'admin_password_confirm' => 'Подтвердить пароль',
            'passwords_dont_match' => 'Пароли не совпадают',
            
            // Finalization
            'finalization' => 'Завершение',
            'finalization_description' => 'Финальная установка и конфигурация',
            'installation_complete' => 'Установка завершена',
            'installation_success' => 'Установка успешно завершена',
            'go_to_admin' => 'Перейти к администрированию',
            
            // Errors
            'installation_failed' => 'Установка не удалась',
            'database_error' => 'Ошибка базы данных',
            'file_permission_error' => 'Ошибка прав доступа к файлу',
            'already_installed' => 'Приложение уже установлено'
        ],
        'zh' => [
            // General
            'installation' => '安装',
            'next' => '下一步',
            'back' => '返回',
            'finish' => '完成',
            'cancel' => '取消',
            'error' => '错误',
            'success' => '成功',
            'warning' => '警告',
            'info' => '信息',
            'required' => '必填',
            'optional' => '可选',
            'system_requirements' => '系统要求',
            'system_requirements_description' => '检查PHP扩展和权限',
            'php_version' => 'PHP版本',
            'php_extensions' => 'PHP扩展',
            'file_permissions' => '文件权限',
            'extension_required' => '必填',
            'extension_optional' => '可选',
            'permission_writable' => '写入',
            'permission_readable' => '读取',
            'status_ok' => 'OK',
            'status_warning' => '警告',
            'status_error' => '错误',
            'check_system_requirements' => '检查系统要求',
            'requirements_met' => '所有系统要求都已满足',
            'requirements_issues' => '某些系统要求未满足',
            
            // Interface
            'installation_title' => 'AdminLicence 安装',
            'installation_assistant' => 'AdminLicence 安装助手',
            'license_api' => '许可证 API',
            'required_format' => '必需格式',
            'all_rights_reserved' => '版权所有。',
            'support' => '支持',
            'documentation' => '文档',
            'step_labels' => [
                1 => '许可证',
                2 => '系统要求',
                3 => '数据库',
                4 => '管理员',
                5 => '完成'
            ],
            
            // License
            'license_key' => '许可证密钥',
            'license_key_placeholder' => 'XXXX-XXXX-XXXX-XXXX',
            'license_verification' => '许可证验证',
            'license_verification_description' => '验证您的许可证密钥以继续',
            'verify_license' => '验证许可证',
            'license_valid' => '许可证有效',
            'license_invalid' => '许可证无效',
            'license_required' => '许可证密钥是必需的',
            'license_key_required' => '许可证密钥是必需的',
            'license_valid_next_step' => '许可证有效！进入下一步...',
            
            // Database
            'database_configuration' => '数据库配置',
            'database_configuration_description' => '配置您的数据库连接',
            'db_host' => '数据库主机',
            'db_port' => '端口',
            'db_name' => '数据库名称',
            'db_user' => '用户名',
            'db_password' => '密码',
            'test_connection' => '测试连接',
            'connection_successful' => '连接成功',
            'connection_failed' => '连接失败',
            
            // Admin
            'admin_setup' => '管理员设置',
            'admin_setup_description' => '创建主管理员账户',
            'admin_name' => '管理员姓名',
            'admin_email' => '管理员邮箱',
            'admin_password' => '密码',
            'admin_password_confirm' => '确认密码',
            'passwords_dont_match' => '密码不匹配',
            
            // Finalization
            'finalization' => '完成',
            'finalization_description' => '最终安装和配置',
            'installation_complete' => '安装完成',
            'installation_success' => '安装成功完成',
            'go_to_admin' => '转到管理',
            
            // Errors
            'installation_failed' => '安装失败',
            'database_error' => '数据库错误',
            'file_permission_error' => '文件权限错误',
            'already_installed' => '应用程序已安装'
        ],
        'ja' => [
            // General
            'installation' => 'インストール',
            'next' => '次へ',
            'back' => '戻る',
            'finish' => '完了',
            'cancel' => 'キャンセル',
            'error' => 'エラー',
            'success' => '成功',
            'warning' => '警告',
            'info' => '情報',
            'required' => '必須',
            'optional' => 'オプション',
            'system_requirements' => 'システム要件',
            'system_requirements_description' => 'PHP拡張機能と権限を確認',
            'php_version' => 'PHPバージョン',
            'php_extensions' => 'PHP拡張機能',
            'file_permissions' => 'ファイル権限',
            'extension_required' => '必須',
            'extension_optional' => 'オプション',
            'permission_writable' => '書き込み',
            'permission_readable' => '読み取り',
            'status_ok' => 'OK',
            'status_warning' => '警告',
            'status_error' => 'エラー',
            'check_system_requirements' => 'システム要件の確認',
            'requirements_met' => 'すべてのシステム要件が満たされています',
            'requirements_issues' => '一部のシステム要件が満たされていません',
            
            // License
            'license_key' => 'ライセンスキー',
            'license_key_placeholder' => 'XXXX-XXXX-XXXX-XXXX',
            'license_verification' => 'ライセンス検証',
            'license_verification_description' => '続行するにはライセンスキーを確認してください',
            'verify_license' => 'ライセンス検証',
            'license_valid' => 'ライセンス有効',
            'license_invalid' => 'ライセンス無効',
            'license_required' => 'ライセンスキーが必要です',
            'license_key_required' => 'ライセンスキーが必要です',
            'license_valid_next_step' => 'ライセンス有効！次のステップに進みます...',
            
            // Database
            'database_configuration' => 'データベース設定',
            'database_configuration_description' => 'データベース接続を設定してください',
            'db_host' => 'データベースホスト',
            'db_port' => 'ポート',
            'db_name' => 'データベース名',
            'db_user' => 'ユーザー名',
            'db_password' => 'パスワード',
            'test_connection' => '接続テスト',
            'connection_successful' => '接続成功',
            'connection_failed' => '接続失敗',
            
            // Admin
            'admin_setup' => '管理者設定',
            'admin_setup_description' => 'メイン管理者アカウントを作成してください',
            'admin_name' => '管理者名',
            'admin_email' => '管理者メール',
            'admin_password' => 'パスワード',
            'admin_password_confirm' => 'パスワード確認',
            'passwords_dont_match' => 'パスワードが一致しません',
            
            // Finalization
            'finalization' => '最終処理',
            'finalization_description' => '最終インストールと設定',
            'installation_complete' => 'インストール完了',
            'installation_success' => 'インストールが正常に完了しました',
            'go_to_admin' => '管理画面へ',
            
            // Errors
            'installation_failed' => 'インストール失敗',
            'database_error' => 'データベースエラー',
            'file_permission_error' => 'ファイル権限エラー',
            'already_installed' => 'アプリケーションは既にインストールされています'
        ],
        'tr' => [
            // General
            'installation' => 'Kurulum',
            'next' => 'İleri',
            'back' => 'Geri',
            'finish' => 'Bitir',
            'cancel' => 'İptal',
            'error' => 'Hata',
            'success' => 'Başarılı',
            'warning' => 'Uyarı',
            'info' => 'Bilgi',
            'required' => 'Gerekli',
            'optional' => 'İsteğe Bağlı',
            'system_requirements' => 'Sistem Gereksinimleri',
            'system_requirements_description' => 'PHP uzantılarını ve izinleri kontrol et',
            'php_version' => 'PHP Versiyonu',
            'php_extensions' => 'PHP Uzantıları',
            'file_permissions' => 'Dosya İzinleri',
            'extension_required' => 'Gerekli',
            'extension_optional' => 'İsteğe Bağlı',
            'permission_writable' => 'Yazma',
            'permission_readable' => 'Okuma',
            'status_ok' => 'OK',
            'status_warning' => 'Uyarı',
            'status_error' => 'Hata',
            'check_system_requirements' => 'Sistem Gereksinimlerini Kontrol Et',
            'requirements_met' => 'Tüm sistem gereksinimleri karşılandı',
            'requirements_issues' => 'Bazı sistem gereksinimleri karşılanmadı'
        ],
        'ar' => [
            // General
            'installation' => 'التثبيت',
            'next' => 'التالي',
            'back' => 'رجوع',
            'finish' => 'إنهاء',
            'cancel' => 'إلغاء',
            'error' => 'خطأ',
            'success' => 'نجح',
            'warning' => 'تحذير',
            'info' => 'معلومات',
            'required' => 'مطلوب',
            'optional' => 'اختياري',
            'system_requirements' => 'متطلبات النظام',
            'system_requirements_description' => 'فحص امتدادات PHP والأذونات',
            'php_version' => 'إصدار PHP',
            'php_extensions' => 'امتدادات PHP',
            'file_permissions' => 'أذونات الملف',
            'extension_required' => 'مطلوب',
            'extension_optional' => 'اختياري',
            'permission_writable' => 'كتابة',
            'permission_readable' => 'قراءة',
            'status_ok' => 'موجود',
            'status_warning' => 'تحذير',
            'status_error' => 'خطأ',
            'check_system_requirements' => 'فحص متطلبات النظام',
            'requirements_met' => 'جميع متطلبات النظام موجودة',
            'requirements_issues' => 'لم تتم معالجة كل المتطلبات'
        ]
    ];
    
    $text = $translations[$currentLang][$key] ?? $translations['fr'][$key] ?? $key;
    
    // Appliquer les remplacements
    if (!empty($replacements)) {
        foreach ($replacements as $placeholder => $value) {
            $text = str_replace(':' . $placeholder, $value, $text);
        }
    }
    
    return $text;
}

/**
 * Obtenir la langue actuelle
 * 
 * @return string Code de la langue actuelle
 */
function getCurrentLanguage() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    if (!isset($_SESSION['installer_language']) || !array_key_exists($_SESSION['installer_language'], AVAILABLE_LANGUAGES)) {
        return initLanguage();
    }
    
    return $_SESSION['installer_language'];
}

/**
 * Obtenir les langues disponibles avec leurs noms
 * 
 * @return array Tableau des langues disponibles
 */
function getAvailableLanguages() {
    return AVAILABLE_LANGUAGES;
}

/**
 * Générer les liens de changement de langue avec menu déroulant
 * 
 * @return string HTML du sélecteur de langue avec menu déroulant
 */
function getLanguageLinks() {
    $currentLang = getCurrentLanguage();
    $currentLangName = AVAILABLE_LANGUAGES[$currentLang] ?? 'Français';
    
    // Conserver les paramètres actuels de l'URL, y compris l'étape
    $params = $_GET;
    unset($params['language']); // Supprimer le paramètre language s'il existe
    $queryString = !empty($params) ? '&' . http_build_query($params) : '';
    
    // Obtenir le drapeau de la langue actuelle
    $currentFlag = getLanguageFlag($currentLang);
    
    $html = '<div class="language-dropdown">';
    $html .= '<button class="language-dropdown-btn" type="button" onclick="toggleLanguageDropdown()">';
    $html .= '<span class="language-flag">' . $currentFlag . '</span>';
    $html .= '<span class="language-name">' . htmlspecialchars($currentLangName) . '</span>';
    $html .= '<span class="dropdown-arrow">▼</span>';
    $html .= '</button>';
    
    $html .= '<div class="language-dropdown-menu" id="languageDropdownMenu">';
    
    foreach (AVAILABLE_LANGUAGES as $code => $name) {
        $active = $code === $currentLang ? ' class="active"' : '';
        $flag = getLanguageFlag($code);
        $html .= sprintf(
            '<a href="?language=%s%s"%s><span class="language-flag">%s</span><span class="language-name">%s</span></a>',
            $code,
            $queryString,
            $active,
            $flag,
            htmlspecialchars($name)
        );
    }
    
    $html .= '</div>';
    $html .= '</div>';
    
    return $html;
}

/**
 * Obtenir le drapeau emoji pour une langue
 * 
 * @param string $langCode Code de la langue
 * @return string Emoji du drapeau
 */
function getLanguageFlag($langCode) {
    $flags = [
        'fr' => '🇫🇷',
        'en' => '🇬🇧',
        'es' => '🇪🇸',
        'de' => '🇩🇪',
        'it' => '🇮🇹',
        'pt' => '🇵🇹',
        'nl' => '🇳🇱',
        'ru' => '🇷🇺',
        'zh' => '🇨🇳',
        'ja' => '🇯🇵',
        'tr' => '🇹🇷',
        'ar' => '🇸🇦'
    ];
    
    return $flags[$langCode] ?? '🌐';
}

/**
 * Obtenir le nom de la langue actuelle
 * 
 * @return string Nom de la langue actuelle
 */
function getCurrentLanguageName() {
    $currentLang = getCurrentLanguage();
    return AVAILABLE_LANGUAGES[$currentLang] ?? 'Français';
}

/**
 * Obtenir le titre de l'étape actuelle
 * 
 * @param int $step Numéro de l'étape
 * @return string Titre de l'étape
 */
function getStepTitle($step) {
    $currentLang = getCurrentLanguage();
    
    $titles = [
        'fr' => [
            1 => 'Vérification de la licence',
            2 => 'Vérification des prérequis système',
            3 => 'Configuration de la base de données',
            4 => 'Configuration de l\'administrateur',
            5 => 'Finalisation de l\'installation'
        ],
        'en' => [
            1 => 'License Verification',
            2 => 'System Requirements Check',
            3 => 'Database Configuration',
            4 => 'Administrator Setup',
            5 => 'Installation Finalization'
        ],
        'es' => [
            1 => 'Verificación de Licencia',
            2 => 'Verificación de Requisitos del Sistema',
            3 => 'Configuración de Base de Datos',
            4 => 'Configuración de Administrador',
            5 => 'Finalización de Instalación'
        ],
        'de' => [
            1 => 'Lizenzüberprüfung',
            2 => 'Systemanforderungen Prüfung',
            3 => 'Datenbank-Konfiguration',
            4 => 'Administrator-Einrichtung',
            5 => 'Installation Abschluss'
        ],
        'it' => [
            1 => 'Verifica Licenza',
            2 => 'Verifica Requisiti di Sistema',
            3 => 'Configurazione Database',
            4 => 'Configurazione Amministratore',
            5 => 'Finalizzazione Installazione'
        ],
        'pt' => [
            1 => 'Verificação de Licença',
            2 => 'Verificação de Requisitos do Sistema',
            3 => 'Configuração de Base de Dados',
            4 => 'Configuração de Administrador',
            5 => 'Finalização da Instalação'
        ],
        'nl' => [
            1 => 'Licentieverificatie',
            2 => 'Systeemvereisten Controle',
            3 => 'Database Configuratie',
            4 => 'Beheerder Configuratie',
            5 => 'Installatie Voltooiing'
        ],
        'ru' => [
            1 => 'Проверка лицензии',
            2 => 'Проверка системных требований',
            3 => 'Конфигурация базы данных',
            4 => 'Настройка администратора',
            5 => 'Завершение установки'
        ],
        'zh' => [
            1 => '许可证验证',
            2 => '系统要求检查',
            3 => '数据库配置',
            4 => '管理员设置',
            5 => '安装完成'
        ],
        'ja' => [
            1 => 'ライセンス検証',
            2 => 'システム要件確認',
            3 => 'データベース設定',
            4 => '管理者設定',
            5 => 'インストール完了'
        ],
        'tr' => [
            1 => 'Lisans Doğrulama',
            2 => 'Sistem Gereksinimleri Kontrolü',
            3 => 'Veritabanı Yapılandırması',
            4 => 'Yönetici Kurulumu',
            5 => 'Kurulum Tamamlama'
        ],
        'ar' => [
            1 => 'التحقق من الترخيص',
            2 => 'فحص امتدادات PHP والأذونات',
            3 => 'قم بتكوين اتصال قاعدة البيانات',
            4 => 'إنشاء حساب المدير الرئيسي',
            5 => 'إنهاء التثبيت'
        ]
    ];
    
    return $titles[$currentLang][$step] ?? $titles['fr'][$step] ?? 'Étape d\'installation';
}

/**
 * Obtenir la description de l'étape actuelle
 * 
 * @param int $step Numéro de l'étape
 * @return string Description de l'étape
 */
function getStepDescription($step) {
    $currentLang = getCurrentLanguage();
    
    $descriptions = [
        'fr' => [
            1 => 'Vérifiez votre clé de licence pour continuer',
            2 => 'Vérification des extensions PHP et des permissions',
            3 => 'Configurez la connexion à votre base de données',
            4 => 'Créez le compte administrateur principal',
            5 => 'Installation et configuration finale'
        ],
        'en' => [
            1 => 'Verify your license key to continue',
            2 => 'Check PHP extensions and permissions',
            3 => 'Configure your database connection',
            4 => 'Create the main administrator account',
            5 => 'Final installation and configuration'
        ],
        'es' => [
            1 => 'Verifique su clave de licencia para continuar',
            2 => 'Verificar extensiones PHP y permisos',
            3 => 'Configure su conexión de base de datos',
            4 => 'Crear la cuenta principal de administrador',
            5 => 'Instalación y configuración final'
        ],
        'de' => [
            1 => 'Verifizieren Sie Ihren Lizenzschlüssel, um fortzufahren',
            2 => 'PHP-Erweiterungen und Berechtigungen prüfen',
            3 => 'Konfigurieren Sie Ihre Datenbankverbindung',
            4 => 'Erstellen Sie das Haupt-Administratorkonto',
            5 => 'Abschließende Installation und Konfiguration'
        ],
        'it' => [
            1 => 'Verifica la tua chiave di licenza per continuare',
            2 => 'Controlla estensioni PHP e permessi',
            3 => 'Configura la connessione al database',
            4 => 'Crea l\'account amministratore principale',
            5 => 'Installazione e configurazione finale'
        ],
        'pt' => [
            1 => 'Verifique sua chave de licença para continuar',
            2 => 'Verificar extensões PHP e permissões',
            3 => 'Configure sua conexão com o banco de dados',
            4 => 'Criar a conta principal do administrador',
            5 => 'Instalação e configuração final'
        ],
        'nl' => [
            1 => 'Verifieer uw licentiesleutel om door te gaan',
            2 => 'Controleer PHP-extensies en machtigingen',
            3 => 'Configureer uw databaseverbinding',
            4 => 'Maak het hoofdbeheerdersaccount aan',
            5 => 'Definitieve installatie en configuratie'
        ],
        'ru' => [
            1 => 'Проверьте ваш лицензионный ключ для продолжения',
            2 => 'Проверка расширений PHP и разрешений',
            3 => 'Настройте подключение к базе данных',
            4 => 'Создайте основную учетную запись администратора',
            5 => 'Финальная установка и конфигурация'
        ],
        'zh' => [
            1 => '验证您的许可证密钥以继续',
            2 => '检查PHP扩展和权限',
            3 => '配置您的数据库连接',
            4 => '创建主管理员账户',
            5 => '最终安装和配置'
        ],
        'ja' => [
            1 => '続行するにはライセンスキーを確認してください',
            2 => 'PHP拡張機能と権限を確認',
            3 => 'データベース接続を設定してください',
            4 => 'メイン管理者アカウントを作成してください',
            5 => '最終インストールと設定'
        ],
        'tr' => [
            1 => 'Devam etmek için lisans anahtarınızı doğrulayın',
            2 => 'PHP uzantıları ve izinleri kontrol et',
            3 => 'Veritabanı bağlantınızı yapılandırın',
            4 => 'Ana yönetici hesabını oluşturun',
            5 => 'Son kurulum ve yapılandırma'
        ],
        'ar' => [
            1 => 'تحقق من مفتاح الترخيص للمتابعة',
            2 => 'فحص امتدادات PHP والأذونات',
            3 => 'قم بتكوين اتصال قاعدة البيانات',
            4 => 'إنشاء حساب المدير الرئيسي',
            5 => 'التثبيت والتكوين النهائي'
        ]
    ];
    
    return $descriptions[$currentLang][$step] ?? $descriptions['fr'][$step] ?? 'Description de l\'étape d\'installation';
}

/**
 * Obtenir les labels des étapes pour les indicateurs
 * 
 * @param int $step Numéro de l'étape
 * @return string Label de l'étape
 */
function getStepLabel($step) {
    $currentLang = getCurrentLanguage();
    
    $labels = [
        'fr' => [
            1 => 'Licence',
            2 => 'Prérequis système',
            3 => 'Base de données',
            4 => 'Administrateur',
            5 => 'Finalisation'
        ],
        'en' => [
            1 => 'License',
            2 => 'System Requirements',
            3 => 'Database',
            4 => 'Administrator',
            5 => 'Finalization'
        ],
        'es' => [
            1 => 'Licencia',
            2 => 'Requisitos del Sistema',
            3 => 'Base de datos',
            4 => 'Administrador',
            5 => 'Finalización'
        ],
        'de' => [
            1 => 'Lizenz',
            2 => 'Systemanforderungen',
            3 => 'Datenbank',
            4 => 'Administrator',
            5 => 'Fertigstellung'
        ],
        'it' => [
            1 => 'Licenza',
            2 => 'Requisiti di Sistema',
            3 => 'Database',
            4 => 'Amministratore',
            5 => 'Finalizzazione'
        ],
        'pt' => [
            1 => 'Licença',
            2 => 'Requisitos do Sistema',
            3 => 'Base de dados',
            4 => 'Administrador',
            5 => 'Finalização'
        ],
        'nl' => [
            1 => 'Licentie',
            2 => 'Systeemvereisten',
            3 => 'Database',
            4 => 'Beheerder',
            5 => 'Voltooiing'
        ],
        'ru' => [
            1 => 'Лицензия',
            2 => 'Системные требования',
            3 => 'База данных',
            4 => 'Администратор',
            5 => 'Завершение'
        ],
        'zh' => [
            1 => '许可证',
            2 => '系统要求',
            3 => '数据库',
            4 => '管理员',
            5 => '完成'
        ],
        'ja' => [
            1 => 'ライセンス',
            2 => 'システム要件',
            3 => 'データベース',
            4 => '管理者',
            5 => '最終処理'
        ],
        'tr' => [
            1 => 'Lisans',
            2 => 'Sistem Gereksinimleri',
            3 => 'Veritabanı',
            4 => 'Yönetici',
            5 => 'Sonlandırma'
        ],
        'ar' => [
            1 => 'الترخيص',
            2 => 'متطلبات النظام',
            3 => 'قاعدة البيانات',
            4 => 'المدير',
            5 => 'الإنهاء'
        ]
    ];
    
    return $labels[$currentLang][$step] ?? $labels['fr'][$step] ?? 'Étape ' . $step;
}
