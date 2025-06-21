<?php
/**
 * Template pour l'étape de configuration de la base de données
 * 
 * @var array $errors Erreurs éventuelles
 */
?>
<div class="step-content">
    <h2><?php echo t('database_configuration'); ?></h2>
    <p class="step-description"><?php echo t('database_configuration_description'); ?></p>
    
    <div class="database-info">
        <h3><?php echo t('database_requirements'); ?></h3>
        <ul>
            <li><?php echo t('mysql_version_requirement'); ?></li>
            <li><?php echo t('database_privileges_requirement'); ?></li>
            <li><?php echo t('utf8mb4_requirement'); ?></li>
        </ul>
    </div>
    
    <form method="POST" action="install_new.php" class="installation-form" data-step="3">
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? ''); ?>">
        <input type="hidden" name="step" value="3">
        
        <div class="form-row">
            <div class="form-group">
                <label for="db_host"><?php echo t('database_host'); ?> *</label>
                <input type="text" 
                       id="db_host" 
                       name="db_host" 
                       value="<?php echo htmlspecialchars($_POST['db_host'] ?? 'localhost'); ?>"
                       placeholder="localhost"
                       required
                       data-validation="hostname">
                <small class="form-help"><?php echo t('database_host_help'); ?></small>
            </div>
            
            <div class="form-group">
                <label for="db_port"><?php echo t('database_port'); ?></label>
                <input type="number" 
                       id="db_port" 
                       name="db_port" 
                       value="<?php echo htmlspecialchars($_POST['db_port'] ?? '3306'); ?>"
                       placeholder="3306"
                       min="1"
                       max="65535"
                       data-validation="port">
                <small class="form-help"><?php echo t('database_port_help'); ?></small>
            </div>
        </div>
        
        <div class="form-group">
            <label for="db_name"><?php echo t('database_name'); ?> *</label>
            <input type="text" 
                   id="db_name" 
                   name="db_name" 
                   value="<?php echo htmlspecialchars($_POST['db_name'] ?? ''); ?>"
                   placeholder="<?php echo t('database_name_placeholder'); ?>"
                   required
                   data-validation="database-name"
                   pattern="[a-zA-Z0-9_]+"
                   maxlength="64">
            <small class="form-help"><?php echo t('database_name_help'); ?></small>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label for="db_username"><?php echo t('database_username'); ?> *</label>
                <input type="text" 
                       id="db_username" 
                       name="db_username" 
                       value="<?php echo htmlspecialchars($_POST['db_username'] ?? ''); ?>"
                       placeholder="<?php echo t('database_username_placeholder'); ?>"
                       required
                       data-validation="username"
                       autocomplete="username">
                <small class="form-help"><?php echo t('database_username_help'); ?></small>
            </div>
            
            <div class="form-group">
                <label for="db_password"><?php echo t('database_password'); ?></label>
                <input type="password" 
                       id="db_password" 
                       name="db_password" 
                       value="<?php echo htmlspecialchars($_POST['db_password'] ?? ''); ?>"
                       placeholder="<?php echo t('database_password_placeholder'); ?>"
                       data-validation="password"
                       autocomplete="new-password">
                <small class="form-help"><?php echo t('database_password_help'); ?></small>
            </div>
        </div>
        
        <div class="form-group">
            <div class="checkbox-group">
                <input type="checkbox" 
                       id="create_database" 
                       name="create_database" 
                       value="1"
                       <?php echo isset($_POST['create_database']) ? 'checked' : ''; ?>>
                <label for="create_database"><?php echo t('create_database_if_not_exists'); ?></label>
                <small class="form-help"><?php echo t('create_database_help'); ?></small>
            </div>
        </div>
        
        <div class="form-actions">
            <button type="button" class="btn btn-secondary" onclick="testDatabaseConnection()">
                <span class="btn-text"><?php echo t('test_connection'); ?></span>
                <span class="btn-spinner" style="display: none;"></span>
            </button>
            
            <button type="submit" class="btn btn-primary" data-loading-text="<?php echo t('configuring'); ?>">
                <span class="btn-text"><?php echo t('configure_database'); ?></span>
                <span class="btn-spinner" style="display: none;"></span>
            </button>
        </div>
    </form>
    
    <div id="connection-result" class="connection-result" style="display: none;"></div>
    
    <div class="step-help">
        <h4><?php echo t('database_help_title'); ?></h4>
        <p><?php echo t('database_help_text'); ?></p>
        <details>
            <summary><?php echo t('advanced_options'); ?></summary>
            <div class="advanced-options">
                <p><?php echo t('advanced_database_info'); ?></p>
            </div>
        </details>
    </div>
</div>

<script>
function testDatabaseConnection() {
    const form = document.querySelector('.installation-form');
    const formData = new FormData(form);
    formData.set('action', 'test_connection');
    
    const button = event.target;
    const originalText = button.querySelector('.btn-text').textContent;
    const spinner = button.querySelector('.btn-spinner');
    
    button.disabled = true;
    button.querySelector('.btn-text').textContent = '<?php echo t('testing'); ?>';
    spinner.style.display = 'inline-block';
    
    fetch('ajax/test_database.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        const resultDiv = document.getElementById('connection-result');
        resultDiv.style.display = 'block';
        
        if (data.success) {
            resultDiv.className = 'connection-result success';
            resultDiv.innerHTML = '<i class="icon-check"></i> ' + data.message;
        } else {
            resultDiv.className = 'connection-result error';
            resultDiv.innerHTML = '<i class="icon-error"></i> ' + data.message;
        }
    })
    .catch(error => {
        const resultDiv = document.getElementById('connection-result');
        resultDiv.style.display = 'block';
        resultDiv.className = 'connection-result error';
        resultDiv.innerHTML = '<i class="icon-error"></i> <?php echo t('connection_test_failed'); ?>';
    })
    .finally(() => {
        button.disabled = false;
        button.querySelector('.btn-text').textContent = originalText;
        spinner.style.display = 'none';
    });
}
</script>