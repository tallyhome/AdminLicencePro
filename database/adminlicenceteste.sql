-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mar. 17 juin 2025 à 23:13
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `adminlicenceteste`
--

-- --------------------------------------------------------

--
-- Structure de la table `admins`
--

CREATE TABLE `admins` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `is_super_admin` tinyint(1) NOT NULL DEFAULT 0,
  `two_factor_secret` text DEFAULT NULL,
  `two_factor_recovery_codes` text DEFAULT NULL,
  `two_factor_enabled` tinyint(1) NOT NULL DEFAULT 0,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `admins`
--

INSERT INTO `admins` (`id`, `name`, `email`, `email_verified_at`, `password`, `is_super_admin`, `two_factor_secret`, `two_factor_recovery_codes`, `two_factor_enabled`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Super Administrateur', 'superadmin@example.com', NULL, '$2y$12$uiZ6CmS4knuQxvv88zSn2.6i292oiWgyfbfWlRyorsaF3ViPX2pYm', 0, 'GIE7BTTD2LDENJIK', NULL, 0, NULL, '2025-04-15 18:14:54', '2025-04-22 12:37:27'),
(2, 'Administrateur', 'admin@example.com', NULL, '$2y$12$mSRcihRsDoLjNhZHF2KGK.lbJ1CEcyC0DthFi9jAnNPVK8hjJm9KC', 0, 'F6U2MUQFHJU4RGS5', NULL, 0, NULL, '2025-04-15 18:14:55', '2025-05-11 23:28:40');

-- --------------------------------------------------------

--
-- Structure de la table `admin_password_reset_tokens`
--

CREATE TABLE `admin_password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `admin_role`
--

CREATE TABLE `admin_role` (
  `admin_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `admin_role`
--

INSERT INTO `admin_role` (`admin_id`, `role_id`) VALUES
(1, 1);

-- --------------------------------------------------------

--
-- Structure de la table `api_keys`
--

CREATE TABLE `api_keys` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `key` varchar(64) NOT NULL,
  `secret` varchar(64) NOT NULL,
  `permissions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`permissions`)),
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `revoked_at` timestamp NULL DEFAULT NULL,
  `usage_count` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `api_keys`
--

INSERT INTO `api_keys` (`id`, `project_id`, `name`, `key`, `secret`, `permissions`, `last_used_at`, `expires_at`, `revoked_at`, `usage_count`, `created_at`, `updated_at`) VALUES
(1, 6, 'install', 'sk_Tc53GD4UycO0kMyQsnv3QEGpCLGKh6Xe', 'sk_MKTv5hxEnaM5kceIPrVeB6KwhyZuEO3s', '[\"licences:read\",\"licences:write\",\"licences:delete\",\"projects:read\",\"projects:write\",\"projects:delete\",\"users:read\",\"users:write\",\"users:delete\",\"statistics:read\"]', NULL, NULL, NULL, 0, '2025-04-22 12:55:17', '2025-04-22 12:55:17'),
(2, 6, 'TestAPIKey', 'sk_VZwQ9VzvuRwtlnsrIbkKPXgNicuR0Dx1', 'sk_Ru5rImVsKzQinOONNpVhTNRUQwyGPHyY', '[\"licences:read\"]', NULL, NULL, NULL, 0, '2025-05-28 14:45:25', '2025-05-28 14:45:25');

-- --------------------------------------------------------

--
-- Structure de la table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('adminlicence_cache_last_license_check_2e4a91e669357f4418d4179b5245cd2e', 'i:1750193705;', 1750203785),
('adminlicence_cache_license_verification_2e4a91e669357f4418d4179b5245cd2e', 'b:1;', 1750196197);
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('adminlicence_cache_translations.en', 'a:25:{s:6:\"common\";a:48:{s:9:\"dashboard\";s:9:\"Dashboard\";s:8:\"projects\";s:8:\"Projects\";s:11:\"serial_keys\";s:11:\"Serial Keys\";s:8:\"api_keys\";s:8:\"API Keys\";s:17:\"api_documentation\";s:17:\"API Documentation\";s:5:\"email\";s:5:\"Email\";s:6:\"logout\";s:6:\"Logout\";s:7:\"version\";s:7:\"Version\";s:4:\"save\";s:4:\"Save\";s:6:\"cancel\";s:6:\"Cancel\";s:6:\"delete\";s:6:\"Delete\";s:4:\"edit\";s:4:\"Edit\";s:6:\"create\";s:6:\"Create\";s:4:\"back\";s:16:\"Back to Settings\";s:7:\"actions\";s:7:\"Actions\";s:6:\"status\";s:6:\"Status\";s:4:\"name\";s:4:\"Name\";s:11:\"description\";s:11:\"Description\";s:4:\"date\";s:4:\"Date\";s:7:\"success\";s:7:\"Success\";s:5:\"error\";s:5:\"Error\";s:7:\"warning\";s:7:\"Warning\";s:4:\"info\";s:11:\"Information\";s:5:\"total\";s:5:\"Total\";s:4:\"next\";s:4:\"Next\";s:8:\"previous\";s:8:\"Previous\";s:6:\"search\";s:6:\"Search\";s:6:\"filter\";s:6:\"Filter\";s:4:\"sort\";s:4:\"Sort\";s:4:\"view\";s:4:\"View\";s:8:\"settings\";s:8:\"Settings\";s:7:\"profile\";s:7:\"Profile\";s:13:\"notifications\";s:13:\"Notifications\";s:5:\"login\";s:5:\"Login\";s:6:\"submit\";s:6:\"Submit\";s:7:\"loading\";s:10:\"Loading...\";s:6:\"manage\";s:6:\"Manage\";s:11:\"information\";s:11:\"Information\";s:14:\"no_description\";s:14:\"No description\";s:3:\"all\";s:3:\"All\";s:4:\"used\";s:4:\"Used\";s:6:\"unused\";s:6:\"Unused\";s:6:\"active\";s:6:\"Active\";s:8:\"inactive\";s:8:\"Inactive\";s:9:\"suspended\";s:9:\"Suspended\";s:7:\"revoked\";s:7:\"Revoked\";s:7:\"expired\";s:7:\"Expired\";s:7:\"pending\";s:7:\"Pending\";}s:12:\"translations\";a:19:{s:5:\"title\";s:22:\"Translation Management\";s:19:\"manage_translations\";s:19:\"Manage Translations\";s:15:\"add_translation\";s:15:\"Add Translation\";s:19:\"add_new_translation\";s:19:\"Add New Translation\";s:7:\"add_new\";s:7:\"Add New\";s:22:\"available_translations\";s:22:\"Available Translations\";s:19:\"available_languages\";s:19:\"Available Languages\";s:4:\"file\";s:4:\"File\";s:3:\"key\";s:3:\"Key\";s:11:\"translation\";s:11:\"Translation\";s:16:\"file_placeholder\";s:35:\"File name (e.g. common, validation)\";s:15:\"key_placeholder\";s:17:\"Unique identifier\";s:17:\"value_placeholder\";s:15:\"Translated text\";s:14:\"delete_confirm\";s:49:\"Are you sure you want to delete this translation?\";s:13:\"file_required\";s:24:\"Please enter a file name\";s:12:\"key_required\";s:18:\"Please enter a key\";s:14:\"value_required\";s:26:\"Please enter a translation\";s:8:\"language\";s:8:\"Language\";s:15:\"select_language\";s:17:\"Select a language\";}s:6:\"layout\";a:14:{s:7:\"support\";s:7:\"Support\";s:7:\"tickets\";s:7:\"Tickets\";s:19:\"super_admin_tickets\";s:19:\"Super Admin Tickets\";s:13:\"documentation\";s:13:\"Documentation\";s:18:\"keys_documentation\";s:18:\"Keys Documentation\";s:29:\"email_providers_documentation\";s:29:\"Email Providers Documentation\";s:18:\"saas_documentation\";s:28:\"SaaS Multiuser Documentation\";s:12:\"version_info\";s:19:\"Version Information\";s:7:\"general\";s:7:\"General\";s:15:\"two_factor_auth\";s:25:\"Two-Factor Authentication\";s:7:\"version\";s:7:\"Version\";s:17:\"toggle_navigation\";s:17:\"Toggle navigation\";s:10:\"created_by\";s:10:\"Created by\";s:19:\"all_rights_reserved\";s:19:\"All rights reserved\";}s:4:\"auth\";a:16:{s:5:\"login\";s:5:\"Login\";s:5:\"email\";s:5:\"Email\";s:8:\"password\";s:8:\"Password\";s:11:\"remember_me\";s:11:\"Remember me\";s:15:\"forgot_password\";s:16:\"Forgot password?\";s:12:\"login_button\";s:7:\"Sign in\";s:8:\"register\";s:8:\"Register\";s:6:\"logout\";s:6:\"Logout\";s:14:\"reset_password\";s:14:\"Reset password\";s:16:\"confirm_password\";s:16:\"Confirm password\";s:16:\"current_password\";s:16:\"Current Password\";s:12:\"new_password\";s:12:\"New Password\";s:21:\"password_confirmation\";s:21:\"Password Confirmation\";s:4:\"name\";s:4:\"Name\";s:18:\"already_registered\";s:19:\"Already registered?\";s:24:\"send_password_reset_link\";s:24:\"Send password reset link\";}s:9:\"dashboard\";a:30:{s:5:\"title\";s:9:\"Dashboard\";s:7:\"welcome\";s:25:\"Welcome to your dashboard\";s:14:\"total_projects\";s:14:\"Total Projects\";s:10:\"total_keys\";s:10:\"Total Keys\";s:11:\"active_keys\";s:11:\"Active keys\";s:9:\"used_keys\";s:9:\"Used Keys\";s:14:\"suspended_keys\";s:14:\"Suspended Keys\";s:12:\"revoked_keys\";s:12:\"Expired keys\";s:21:\"keys_usage_by_project\";s:21:\"Keys Usage by Project\";s:14:\"available_keys\";s:14:\"Available Keys\";s:23:\"keys_usage_last_30_days\";s:25:\"Keys Usage (Last 30 Days)\";s:23:\"distribution_by_project\";s:23:\"Distribution by Project\";s:4:\"used\";s:4:\"used\";s:9:\"available\";s:9:\"available\";s:9:\"low_stock\";s:9:\"Low Stock\";s:16:\"sufficient_stock\";s:16:\"Sufficient Stock\";s:7:\"no_keys\";s:7:\"No Keys\";s:11:\"activations\";s:11:\"Activations\";s:13:\"deactivations\";s:13:\"Deactivations\";s:15:\"recent_activity\";s:15:\"Recent Activity\";s:10:\"statistics\";s:10:\"Statistics\";s:13:\"quick_actions\";s:13:\"Quick Actions\";s:13:\"system_status\";s:13:\"System Status\";s:10:\"last_login\";s:10:\"Last Login\";s:11:\"recent_keys\";s:11:\"Recent Keys\";s:5:\"usage\";s:5:\"Usage\";s:6:\"charts\";s:6:\"Charts\";s:11:\"usage_chart\";s:11:\"Usage Chart\";s:26:\"project_distribution_chart\";s:26:\"Project Distribution Chart\";s:14:\"projects_count\";s:8:\"Projects\";}s:10:\"pagination\";a:9:{s:8:\"per_page\";s:16:\":number per page\";s:7:\"showing\";s:7:\"Showing\";s:2:\"to\";s:2:\"to\";s:2:\"of\";s:2:\"of\";s:7:\"results\";s:7:\"results\";s:4:\"next\";s:4:\"Next\";s:8:\"previous\";s:8:\"Previous\";s:5:\"first\";s:5:\"First\";s:4:\"last\";s:4:\"Last\";}s:8:\"projects\";a:49:{s:5:\"title\";s:19:\"Projects Management\";s:14:\"create_project\";s:14:\"Create Project\";s:12:\"edit_project\";s:12:\"Edit Project\";s:12:\"project_name\";s:12:\"Project Name\";s:19:\"project_description\";s:19:\"Project Description\";s:14:\"project_status\";s:14:\"Project Status\";s:7:\"project\";s:7:\"Project\";s:6:\"active\";s:6:\"Active\";s:8:\"inactive\";s:8:\"Inactive\";s:11:\"no_projects\";s:34:\"No projects have been created yet.\";s:15:\"project_details\";s:15:\"Project Details\";s:16:\"project_settings\";s:16:\"Project Settings\";s:12:\"project_keys\";s:12:\"Project Keys\";s:16:\"project_activity\";s:16:\"Project Activity\";s:19:\"project_information\";s:19:\"Project Information\";s:14:\"no_description\";s:14:\"No description\";s:11:\"website_url\";s:11:\"Website URL\";s:13:\"not_specified\";s:13:\"Not specified\";s:13:\"creation_date\";s:13:\"Creation Date\";s:11:\"last_update\";s:11:\"Last Update\";s:14:\"delete_project\";s:14:\"Delete Project\";s:19:\"delete_confirmation\";s:75:\"Are you sure you want to delete this project? This action cannot be undone.\";s:13:\"creation_form\";s:13:\"Creation Form\";s:9:\"edit_form\";s:9:\"Edit Form\";s:12:\"save_changes\";s:12:\"Save Changes\";s:4:\"name\";s:4:\"Name\";s:11:\"description\";s:11:\"Description\";s:10:\"created_at\";s:10:\"Created at\";s:7:\"actions\";s:7:\"Actions\";s:10:\"create_new\";s:18:\"Create New Project\";s:7:\"details\";s:15:\"Project Details\";s:6:\"manage\";s:6:\"Manage\";s:4:\"view\";s:4:\"View\";s:4:\"edit\";a:10:{s:5:\"title\";s:12:\"Edit Project\";s:10:\"form_title\";s:9:\"Edit Form\";s:10:\"name_label\";s:12:\"Project Name\";s:17:\"description_label\";s:11:\"Description\";s:17:\"website_url_label\";s:11:\"Website URL\";s:23:\"website_url_placeholder\";s:19:\"https://example.com\";s:12:\"status_label\";s:6:\"Status\";s:13:\"status_active\";s:6:\"Active\";s:15:\"status_inactive\";s:8:\"Inactive\";s:13:\"submit_button\";s:12:\"Save Changes\";}s:6:\"delete\";s:6:\"Delete\";s:6:\"status\";s:6:\"Status\";s:14:\"success_create\";s:28:\"Project created successfully\";s:14:\"success_update\";s:28:\"Project updated successfully\";s:14:\"success_delete\";s:28:\"Project deleted successfully\";s:14:\"confirm_delete\";s:45:\"Are you sure you want to delete this project?\";s:12:\"error_create\";s:22:\"Error creating project\";s:12:\"error_update\";s:22:\"Error updating project\";s:12:\"error_delete\";s:22:\"Error deleting project\";s:10:\"management\";s:19:\"Projects Management\";s:11:\"new_project\";s:11:\"New Project\";s:12:\"license_keys\";s:12:\"License Keys\";s:8:\"api_keys\";s:8:\"API Keys\";s:6:\"create\";a:10:{s:5:\"title\";s:14:\"Create Project\";s:10:\"form_title\";s:13:\"Creation Form\";s:10:\"name_label\";s:12:\"Project Name\";s:17:\"description_label\";s:11:\"Description\";s:17:\"website_url_label\";s:11:\"Website URL\";s:23:\"website_url_placeholder\";s:19:\"https://example.com\";s:12:\"status_label\";s:6:\"Status\";s:13:\"status_active\";s:6:\"Active\";s:15:\"status_inactive\";s:8:\"Inactive\";s:13:\"submit_button\";s:14:\"Create Project\";}s:4:\"show\";a:18:{s:5:\"title\";s:15:\"Project Details\";s:7:\"details\";s:19:\"Project Information\";s:4:\"name\";s:4:\"Name\";s:11:\"description\";s:11:\"Description\";s:11:\"website_url\";s:11:\"Website URL\";s:6:\"status\";s:6:\"Status\";s:13:\"status_active\";s:6:\"Active\";s:15:\"status_inactive\";s:8:\"Inactive\";s:10:\"created_at\";s:10:\"Created at\";s:10:\"updated_at\";s:12:\"Last updated\";s:10:\"statistics\";s:10:\"Statistics\";s:18:\"total_license_keys\";s:18:\"Total License Keys\";s:14:\"total_api_keys\";s:14:\"Total API Keys\";s:7:\"actions\";s:7:\"Actions\";s:18:\"create_license_key\";s:18:\"Create License Key\";s:14:\"create_api_key\";s:14:\"Create API Key\";s:14:\"delete_project\";s:14:\"Delete Project\";s:14:\"confirm_delete\";s:74:\"Are you sure you want to delete this project? This action is irreversible.\";}}s:11:\"serial_keys\";a:51:{s:5:\"title\";s:23:\"License Keys Management\";s:10:\"create_key\";s:10:\"Create Key\";s:8:\"edit_key\";s:8:\"Edit Key\";s:13:\"search_by_key\";s:13:\"Search by Key\";s:18:\"search_placeholder\";s:30:\"Search by key, domain or IP...\";s:3:\"key\";s:3:\"Key\";s:7:\"project\";s:7:\"Project\";s:6:\"status\";s:6:\"Status\";s:10:\"expiration\";s:10:\"Expiration\";s:7:\"no_keys\";s:13:\"No keys found\";s:8:\"generate\";s:8:\"Generate\";s:6:\"active\";s:6:\"Active\";s:8:\"inactive\";s:8:\"Inactive\";s:7:\"expired\";s:7:\"Expired\";s:9:\"suspended\";s:9:\"Suspended\";s:7:\"revoked\";s:7:\"Revoked\";s:11:\"key_details\";s:11:\"Key Details\";s:9:\"key_usage\";s:9:\"Key Usage\";s:8:\"key_used\";s:8:\"Key Used\";s:11:\"key_history\";s:11:\"Key History\";s:11:\"hardware_id\";s:11:\"Hardware ID\";s:10:\"created_at\";s:10:\"Created at\";s:7:\"actions\";s:7:\"Actions\";s:10:\"create_new\";s:14:\"Create New Key\";s:7:\"details\";s:11:\"Key Details\";s:8:\"activate\";s:8:\"Activate\";s:10:\"deactivate\";s:10:\"Deactivate\";s:4:\"list\";s:17:\"License Keys List\";s:6:\"domain\";s:6:\"Domain\";s:10:\"ip_address\";s:10:\"IP Address\";s:13:\"not_specified\";s:13:\"Not specified\";s:13:\"no_expiration\";s:13:\"No expiration\";s:15:\"confirm_suspend\";s:42:\"Are you sure you want to suspend this key?\";s:14:\"confirm_revoke\";s:41:\"Are you sure you want to revoke this key?\";s:7:\"suspend\";s:7:\"Suspend\";s:6:\"revoke\";s:6:\"Revoke\";s:4:\"edit\";s:4:\"Edit\";s:4:\"view\";s:4:\"View\";s:11:\"information\";s:15:\"Key Information\";s:11:\"license_key\";s:11:\"License Key\";s:15:\"expiration_date\";s:15:\"Expiration Date\";s:13:\"status_active\";s:6:\"Active\";s:16:\"status_suspended\";s:9:\"Suspended\";s:14:\"status_revoked\";s:7:\"Revoked\";s:14:\"status_expired\";s:7:\"Expired\";s:14:\"create_success\";s:35:\"License key(s) created successfully\";s:14:\"update_success\";s:32:\"License key updated successfully\";s:14:\"delete_success\";s:32:\"License key deleted successfully\";s:15:\"suspend_success\";s:34:\"License key suspended successfully\";s:14:\"revoke_success\";s:32:\"License key revoked successfully\";s:18:\"reactivate_success\";s:36:\"License key reactivated successfully\";}s:3:\"api\";a:19:{s:5:\"title\";s:17:\"API Documentation\";s:11:\"description\";s:67:\"Use these endpoints to integrate AdminLicence into your application\";s:8:\"endpoint\";s:8:\"Endpoint\";s:6:\"method\";s:6:\"Method\";s:10:\"parameters\";s:10:\"Parameters\";s:8:\"response\";s:8:\"Response\";s:8:\"api_keys\";s:8:\"API Keys\";s:11:\"active_keys\";s:6:\"Active\";s:12:\"revoked_keys\";s:7:\"Revoked\";s:10:\"create_key\";s:14:\"Create API Key\";s:7:\"example\";s:7:\"Example\";s:14:\"authentication\";s:14:\"Authentication\";s:11:\"rate_limits\";s:11:\"Rate Limits\";s:11:\"error_codes\";s:11:\"Error Codes\";s:7:\"testing\";s:11:\"API Testing\";s:13:\"documentation\";s:17:\"API Documentation\";s:9:\"endpoints\";s:9:\"Endpoints\";s:7:\"request\";s:7:\"Request\";s:14:\"best_practices\";s:14:\"Best Practices\";}s:5:\"email\";a:19:{s:5:\"title\";s:19:\"Email Configuration\";s:8:\"settings\";s:13:\"SMTP Settings\";s:6:\"driver\";s:6:\"Driver\";s:4:\"host\";s:11:\"SMTP Server\";s:4:\"port\";s:4:\"Port\";s:10:\"encryption\";s:10:\"Encryption\";s:8:\"username\";s:8:\"Username\";s:8:\"password\";s:8:\"Password\";s:12:\"from_address\";s:12:\"From Address\";s:9:\"from_name\";s:9:\"From Name\";s:10:\"test_email\";s:15:\"Send Test Email\";s:9:\"templates\";s:9:\"Templates\";s:4:\"logs\";s:4:\"Logs\";s:5:\"queue\";s:5:\"Queue\";s:8:\"greeting\";s:6:\"Hello!\";s:7:\"regards\";s:7:\"Regards\";s:16:\"trouble_clicking\";s:97:\"If you\'re having trouble clicking the button, copy and paste the URL below into your web browser:\";s:19:\"all_rights_reserved\";s:20:\"All rights reserved.\";s:9:\"providers\";a:3:{s:7:\"phpmail\";a:11:{s:5:\"title\";s:21:\"PHPMail Configuration\";s:8:\"settings\";s:16:\"Sending Settings\";s:11:\"description\";s:85:\"Advanced SMTP management for email sending with custom templates and sending tracking\";s:18:\"test_configuration\";s:18:\"Test Configuration\";s:18:\"save_configuration\";s:18:\"Save Configuration\";s:10:\"statistics\";s:10:\"Statistics\";s:17:\"emails_sent_today\";s:17:\"Emails sent today\";s:17:\"emails_sent_month\";s:22:\"Emails sent this month\";s:4:\"logs\";s:4:\"Logs\";s:17:\"no_logs_available\";s:17:\"No logs available\";s:10:\"clear_logs\";s:10:\"Clear logs\";}s:7:\"mailgun\";a:5:{s:5:\"title\";s:21:\"Mailgun Configuration\";s:8:\"settings\";s:16:\"Mailgun Settings\";s:11:\"description\";s:43:\"Transactional email service via Mailgun API\";s:6:\"domain\";s:14:\"Mailgun Domain\";s:7:\"api_key\";s:15:\"Mailgun API Key\";}s:9:\"mailchimp\";a:5:{s:5:\"title\";s:23:\"Mailchimp Configuration\";s:8:\"settings\";s:18:\"Mailchimp Settings\";s:11:\"description\";s:81:\"Email campaign management, mailing lists and templates with Mailchimp integration\";s:7:\"api_key\";s:17:\"Mailchimp API Key\";s:7:\"list_id\";s:7:\"List ID\";}}}s:12:\"subscription\";a:13:{s:5:\"plans\";s:18:\"Subscription Plans\";s:12:\"current_plan\";s:12:\"Current plan\";s:7:\"upgrade\";s:7:\"Upgrade\";s:9:\"downgrade\";s:9:\"Downgrade\";s:6:\"cancel\";s:19:\"Cancel Subscription\";s:6:\"resume\";s:19:\"Resume Subscription\";s:15:\"payment_methods\";s:15:\"Payment Methods\";s:8:\"invoices\";s:8:\"Invoices\";s:7:\"billing\";s:7:\"Billing\";s:14:\"payment_method\";s:14:\"Payment method\";s:21:\"update_payment_method\";s:21:\"Update payment method\";s:15:\"billing_history\";s:15:\"Billing history\";s:7:\"invoice\";s:7:\"Invoice\";}s:8:\"language\";a:18:{s:6:\"select\";s:15:\"Select Language\";s:2:\"en\";s:7:\"English\";s:2:\"fr\";s:6:\"French\";s:2:\"es\";s:7:\"Spanish\";s:2:\"de\";s:6:\"German\";s:2:\"it\";s:7:\"Italian\";s:2:\"pt\";s:10:\"Portuguese\";s:2:\"nl\";s:5:\"Dutch\";s:2:\"ru\";s:7:\"Russian\";s:2:\"zh\";s:7:\"Chinese\";s:2:\"ja\";s:8:\"Japanese\";s:2:\"tr\";s:7:\"Turkish\";s:2:\"ar\";s:6:\"Arabic\";s:20:\"changed_successfully\";s:38:\"Language has been changed successfully\";s:14:\"invalid_locale\";s:38:\"The selected language is not available\";s:14:\"already_active\";s:31:\"This language is already active\";s:15:\"change_language\";s:15:\"Change language\";s:16:\"language_changed\";s:38:\"Language has been changed successfully\";}s:7:\"install\";a:42:{s:5:\"title\";s:19:\"Installation Wizard\";s:7:\"welcome\";s:34:\"Welcome to the installation wizard\";s:15:\"welcome_message\";s:123:\"This wizard will guide you through the installation process of AdminLicence. Please follow the steps to complete the setup.\";s:17:\"already_installed\";s:103:\"AdminLicence is already installed. If you want to reinstall, please delete the .env file and try again.\";s:12:\"requirements\";s:12:\"Requirements\";s:20:\"requirements_message\";s:102:\"Please make sure your server meets the following requirements before proceeding with the installation.\";s:11:\"php_version\";s:11:\"PHP Version\";s:20:\"php_version_required\";s:29:\"PHP 8.2 or higher is required\";s:13:\"database_step\";s:22:\"Database Configuration\";s:16:\"database_message\";s:54:\"Please provide your database connection details below.\";s:13:\"db_connection\";s:19:\"Database Connection\";s:7:\"db_host\";s:13:\"Database Host\";s:7:\"db_port\";s:13:\"Database Port\";s:11:\"db_database\";s:13:\"Database Name\";s:11:\"db_username\";s:17:\"Database Username\";s:11:\"db_password\";s:17:\"Database Password\";s:20:\"db_connection_failed\";s:33:\"Failed to connect to the database\";s:13:\"language_step\";s:22:\"Language Configuration\";s:16:\"language_message\";s:58:\"Please select your preferred language for the application.\";s:9:\"mail_step\";s:19:\"Email Configuration\";s:12:\"mail_message\";s:47:\"Please provide your email server details below.\";s:11:\"mail_driver\";s:11:\"Mail Driver\";s:9:\"mail_host\";s:9:\"Mail Host\";s:9:\"mail_port\";s:9:\"Mail Port\";s:13:\"mail_username\";s:13:\"Mail Username\";s:13:\"mail_password\";s:13:\"Mail Password\";s:15:\"mail_encryption\";s:15:\"Mail Encryption\";s:17:\"mail_from_address\";s:12:\"From Address\";s:14:\"mail_from_name\";s:9:\"From Name\";s:10:\"admin_step\";s:13:\"Admin Account\";s:13:\"admin_message\";s:39:\"Please create your admin account below.\";s:10:\"admin_name\";s:4:\"Name\";s:11:\"admin_email\";s:5:\"Email\";s:14:\"admin_password\";s:8:\"Password\";s:27:\"admin_password_confirmation\";s:16:\"Confirm Password\";s:13:\"complete_step\";s:21:\"Installation Complete\";s:16:\"complete_message\";s:86:\"AdminLicence has been successfully installed. You can now login to your admin account.\";s:11:\"go_to_login\";s:11:\"Go to Login\";s:6:\"finish\";s:6:\"Finish\";s:9:\"next_step\";s:9:\"Next step\";s:13:\"previous_step\";s:13:\"Previous step\";s:7:\"install\";s:7:\"Install\";}s:8:\"api_keys\";a:38:{s:10:\"management\";s:19:\"API Keys Management\";s:6:\"create\";s:14:\"Create API Key\";s:7:\"details\";s:15:\"API Key Details\";s:4:\"edit\";s:12:\"Edit API Key\";s:7:\"project\";s:7:\"Project\";s:12:\"all_projects\";s:12:\"All projects\";s:6:\"status\";s:6:\"Status\";s:10:\"all_status\";s:12:\"All statuses\";s:6:\"active\";s:6:\"Active\";s:13:\"active_plural\";s:6:\"Active\";s:7:\"revoked\";s:7:\"Revoked\";s:14:\"revoked_plural\";s:7:\"Revoked\";s:7:\"expired\";s:7:\"Expired\";s:14:\"expired_plural\";s:7:\"Expired\";s:11:\"used_plural\";s:4:\"Used\";s:4:\"used\";s:4:\"Used\";s:3:\"key\";s:3:\"Key\";s:8:\"key_name\";s:8:\"Key Name\";s:6:\"secret\";s:6:\"Secret\";s:4:\"name\";s:4:\"Name\";s:9:\"last_used\";s:9:\"Last Used\";s:5:\"never\";s:5:\"Never\";s:14:\"confirm_revoke\";s:45:\"Are you sure you want to revoke this API key?\";s:18:\"confirm_reactivate\";s:49:\"Are you sure you want to reactivate this API key?\";s:14:\"confirm_delete\";s:45:\"Are you sure you want to delete this API key?\";s:6:\"revoke\";s:6:\"Revoke\";s:10:\"reactivate\";s:10:\"Reactivate\";s:11:\"permissions\";s:11:\"Permissions\";s:16:\"save_permissions\";s:16:\"Save Permissions\";s:10:\"basic_info\";s:17:\"Basic Information\";s:11:\"usage_stats\";s:16:\"Usage Statistics\";s:11:\"total_usage\";s:11:\"Total Usage\";s:10:\"created_at\";s:10:\"Created At\";s:15:\"expiration_date\";s:15:\"Expiration Date\";s:18:\"no_expiration_hint\";s:29:\"Leave empty for no expiration\";s:14:\"select_project\";s:16:\"Select a project\";s:7:\"no_keys\";s:18:\"No API keys found.\";s:4:\"none\";s:4:\"None\";}s:11:\"permissions\";a:10:{s:12:\"license_read\";s:12:\"License read\";s:13:\"license_write\";s:13:\"License write\";s:14:\"license_delete\";s:14:\"License delete\";s:12:\"project_read\";s:12:\"Project read\";s:13:\"project_write\";s:13:\"Project write\";s:14:\"project_delete\";s:14:\"Project delete\";s:9:\"user_read\";s:9:\"User read\";s:10:\"user_write\";s:10:\"User write\";s:11:\"user_delete\";s:11:\"User delete\";s:10:\"stats_read\";s:15:\"Statistics read\";}s:14:\"api_diagnostic\";a:15:{s:5:\"title\";s:14:\"API Diagnostic\";s:10:\"tool_title\";s:19:\"API Diagnostic Tool\";s:15:\"open_new_window\";s:18:\"Open in new window\";s:11:\"access_info\";s:18:\"Access Information\";s:8:\"tool_url\";s:58:\"The API diagnostic tool is accessible at the following URL\";s:19:\"default_credentials\";s:19:\"Default credentials\";s:18:\"available_features\";s:18:\"Available Features\";s:8:\"features\";a:6:{s:12:\"general_info\";a:2:{s:5:\"title\";s:19:\"General Information\";s:11:\"description\";s:44:\"Overview of the API and server configuration\";}s:15:\"serial_key_test\";a:2:{s:5:\"title\";s:15:\"Serial Key Test\";s:11:\"description\";s:36:\"Verify the validity of a license key\";}s:15:\"connection_test\";a:2:{s:5:\"title\";s:15:\"Connection Test\";s:11:\"description\";s:40:\"Check connectivity with the external API\";}s:13:\"database_test\";a:2:{s:5:\"title\";s:13:\"Database Test\";s:11:\"description\";s:26:\"Verify database connection\";}s:17:\"permissions_check\";a:2:{s:5:\"title\";s:17:\"Permissions Check\";s:11:\"description\";s:35:\"Check permissions of critical files\";}s:12:\"logs_display\";a:2:{s:5:\"title\";s:12:\"Logs Display\";s:11:\"description\";s:27:\"View the latest log entries\";}}s:15:\"serial_key_test\";a:10:{s:7:\"section\";s:15:\"Serial Key Test\";s:5:\"title\";s:15:\"Serial Key Test\";s:9:\"key_label\";s:10:\"Serial Key\";s:10:\"select_key\";s:12:\"Select a key\";s:10:\"custom_key\";s:18:\"Enter a custom key\";s:16:\"custom_key_label\";s:10:\"Custom Key\";s:12:\"domain_label\";s:17:\"Domain (optional)\";s:8:\"ip_label\";s:21:\"IP Address (optional)\";s:11:\"test_button\";s:8:\"Test Key\";s:12:\"result_title\";s:6:\"Result\";}s:11:\"server_info\";a:9:{s:7:\"section\";s:18:\"Server Information\";s:5:\"title\";s:18:\"Server Information\";s:11:\"php_version\";s:11:\"PHP Version\";s:15:\"laravel_version\";s:15:\"Laravel Version\";s:10:\"web_server\";s:10:\"Web Server\";s:2:\"os\";s:16:\"Operating System\";s:8:\"database\";s:8:\"Database\";s:8:\"timezone\";s:8:\"Timezone\";s:14:\"php_extensions\";s:14:\"PHP Extensions\";}s:7:\"buttons\";a:4:{s:19:\"test_api_connection\";s:19:\"Test API Connection\";s:13:\"test_database\";s:13:\"Test Database\";s:17:\"check_permissions\";s:17:\"Check Permissions\";s:5:\"close\";s:5:\"Close\";}s:14:\"database_stats\";a:13:{s:7:\"section\";s:19:\"Database Statistics\";s:5:\"title\";s:19:\"Database Statistics\";s:11:\"serial_keys\";s:11:\"Serial Keys\";s:20:\"view_all_serial_keys\";s:20:\"View all serial keys\";s:13:\"view_all_keys\";s:13:\"View all keys\";s:8:\"projects\";s:8:\"Projects\";s:17:\"view_all_projects\";s:17:\"View all projects\";s:6:\"admins\";s:14:\"Administrators\";s:15:\"view_all_admins\";s:23:\"View all administrators\";s:11:\"active_keys\";s:11:\"Active Keys\";s:16:\"view_active_keys\";s:16:\"View active keys\";s:8:\"api_keys\";s:8:\"API Keys\";s:17:\"view_all_api_keys\";s:17:\"View all API keys\";}s:4:\"logs\";a:4:{s:7:\"section\";s:11:\"Latest Logs\";s:5:\"title\";s:11:\"Latest Logs\";s:7:\"refresh\";s:7:\"Refresh\";s:10:\"no_entries\";s:24:\"No log entries available\";}s:6:\"modals\";a:2:{s:17:\"serial_keys_title\";s:11:\"Serial Keys\";s:17:\"test_result_title\";s:11:\"Test Result\";}s:2:\"js\";a:55:{s:17:\"please_select_key\";s:36:\"Please select or enter a serial key.\";s:24:\"verification_in_progress\";s:27:\"Verification in progress...\";s:5:\"valid\";s:5:\"Valid\";s:7:\"invalid\";s:7:\"Invalid\";s:6:\"status\";s:6:\"Status\";s:10:\"serial_key\";s:10:\"Serial Key\";s:7:\"project\";s:7:\"Project\";s:15:\"expiration_date\";s:15:\"Expiration Date\";s:6:\"domain\";s:6:\"Domain\";s:10:\"ip_address\";s:10:\"IP Address\";s:5:\"token\";s:5:\"Token\";s:13:\"not_specified\";s:13:\"Not specified\";s:13:\"not_generated\";s:13:\"Not generated\";s:5:\"error\";s:5:\"Error\";s:12:\"server_error\";s:54:\"An error occurred while communicating with the server.\";s:19:\"loading_serial_keys\";s:22:\"Loading serial keys...\";s:6:\"active\";s:6:\"Active\";s:7:\"revoked\";s:7:\"Revoked\";s:9:\"suspended\";s:9:\"Suspended\";s:7:\"expired\";s:7:\"Expired\";s:20:\"no_serial_keys_found\";s:21:\"No serial keys found.\";s:25:\"error_loading_serial_keys\";s:26:\"Error loading serial keys.\";s:16:\"test_in_progress\";s:19:\"Test in progress...\";s:7:\"success\";s:7:\"Success\";s:7:\"failure\";s:7:\"Failure\";s:11:\"test_result\";s:11:\"Test Result\";s:3:\"yes\";s:3:\"Yes\";s:2:\"no\";s:2:\"No\";s:13:\"not_available\";s:13:\"Not available\";s:16:\"loading_projects\";s:19:\"Loading projects...\";s:12:\"project_name\";s:12:\"Project Name\";s:11:\"serial_keys\";s:11:\"Serial Keys\";s:10:\"created_at\";s:10:\"Created At\";s:17:\"no_projects_found\";s:18:\"No projects found.\";s:22:\"error_loading_projects\";s:23:\"Error loading projects.\";s:14:\"loading_admins\";s:25:\"Loading administrators...\";s:4:\"name\";s:4:\"Name\";s:5:\"email\";s:5:\"Email\";s:10:\"last_login\";s:10:\"Last Login\";s:5:\"never\";s:5:\"Never\";s:15:\"no_admins_found\";s:24:\"No administrators found.\";s:20:\"error_loading_admins\";s:29:\"Error loading administrators.\";s:19:\"loading_active_keys\";s:22:\"Loading active keys...\";s:10:\"expires_at\";s:10:\"Expires At\";s:3:\"all\";s:3:\"All\";s:4:\"none\";s:4:\"None\";s:20:\"no_active_keys_found\";s:21:\"No active keys found.\";s:25:\"error_loading_active_keys\";s:26:\"Error loading active keys.\";s:16:\"loading_api_keys\";s:19:\"Loading API keys...\";s:3:\"key\";s:3:\"Key\";s:12:\"last_used_at\";s:12:\"Last Used At\";s:17:\"no_api_keys_found\";s:18:\"No API keys found.\";s:22:\"error_loading_api_keys\";s:23:\"Error loading API keys.\";s:7:\"message\";s:7:\"Message\";s:7:\"details\";s:7:\"Details\";}}s:8:\"settings\";a:6:{s:5:\"title\";s:16:\"General Settings\";s:7:\"general\";a:6:{s:5:\"title\";s:19:\"General Information\";s:9:\"site_name\";s:9:\"Site Name\";s:16:\"site_description\";s:16:\"Site Description\";s:13:\"contact_email\";s:13:\"Contact Email\";s:12:\"contact_name\";s:12:\"Contact Name\";s:14:\"update_success\";s:29:\"Settings updated successfully\";}s:8:\"password\";a:5:{s:5:\"title\";s:15:\"Change Password\";s:16:\"current_password\";s:16:\"Current Password\";s:12:\"new_password\";s:12:\"New Password\";s:16:\"confirm_password\";s:16:\"Confirm Password\";s:13:\"update_button\";s:15:\"Update Password\";}s:7:\"favicon\";a:5:{s:5:\"title\";s:7:\"Favicon\";s:7:\"current\";s:15:\"Current Favicon\";s:3:\"new\";s:11:\"New Favicon\";s:16:\"accepted_formats\";s:63:\"Accepted formats: ICO, PNG, JPG, JPEG, SVG. Maximum size: 2 MB.\";s:13:\"update_button\";s:14:\"Update Favicon\";}s:5:\"theme\";a:3:{s:5:\"title\";s:5:\"Theme\";s:9:\"dark_mode\";s:16:\"Enable Dark Mode\";s:12:\"apply_button\";s:5:\"Apply\";}s:10:\"two_factor\";a:52:{s:5:\"title\";s:25:\"Two-Factor Authentication\";s:11:\"description\";s:79:\"Two-factor authentication adds an additional layer of security to your account.\";s:7:\"enabled\";s:36:\"Two-factor authentication is enabled\";s:8:\"disabled\";s:37:\"Two-factor authentication is disabled\";s:6:\"enable\";s:32:\"Enable two-factor authentication\";s:7:\"disable\";s:33:\"Disable two-factor authentication\";s:5:\"setup\";s:32:\"Set up two-factor authentication\";s:7:\"scan_qr\";s:45:\"Scan the QR code with your authentication app\";s:10:\"enter_code\";s:23:\"Enter verification code\";s:17:\"verification_code\";s:17:\"Verification code\";s:14:\"recovery_codes\";s:14:\"Recovery codes\";s:26:\"recovery_codes_description\";s:136:\"Keep these recovery codes in a safe place. They will allow you to recover access to your account if you lose your authentication device.\";s:16:\"regenerate_codes\";s:25:\"Regenerate recovery codes\";s:10:\"show_codes\";s:19:\"Show recovery codes\";s:15:\"confirm_disable\";s:59:\"Are you sure you want to disable two-factor authentication?\";s:14:\"confirm_enable\";s:58:\"Are you sure you want to enable two-factor authentication?\";s:15:\"success_enabled\";s:56:\"Two-factor authentication has been successfully enabled.\";s:16:\"success_disabled\";s:57:\"Two-factor authentication has been successfully disabled.\";s:12:\"error_enable\";s:59:\"An error occurred while enabling two-factor authentication.\";s:13:\"error_disable\";s:60:\"An error occurred while disabling two-factor authentication.\";s:12:\"invalid_code\";s:33:\"The verification code is invalid.\";s:12:\"backup_codes\";s:12:\"Backup codes\";s:24:\"backup_codes_description\";s:83:\"These backup codes will allow you to log in if you lose your authentication device.\";s:14:\"download_codes\";s:21:\"Download backup codes\";s:11:\"print_codes\";s:18:\"Print backup codes\";s:23:\"regenerate_backup_codes\";s:23:\"Regenerate backup codes\";s:35:\"regenerate_backup_codes_description\";s:61:\"Regenerating backup codes will invalidate all previous codes.\";s:18:\"confirm_regenerate\";s:49:\"Are you sure you want to regenerate backup codes?\";s:6:\"status\";a:2:{s:7:\"enabled\";s:7:\"Enabled\";s:8:\"disabled\";s:8:\"Disabled\";}s:9:\"configure\";s:9:\"Configure\";s:4:\"test\";s:4:\"Test\";s:6:\"verify\";s:6:\"Verify\";s:12:\"verification\";s:12:\"Verification\";s:20:\"verification_success\";s:23:\"Verification successful\";s:19:\"verification_failed\";s:19:\"Verification failed\";s:14:\"setup_complete\";s:14:\"Setup complete\";s:12:\"setup_failed\";s:12:\"Setup failed\";s:7:\"qr_code\";s:7:\"QR Code\";s:11:\"manual_code\";s:11:\"Manual code\";s:23:\"manual_code_description\";s:84:\"If you cannot scan the QR code, enter this code manually in your authentication app.\";s:19:\"app_recommendations\";s:16:\"Recommended apps\";s:31:\"app_recommendations_description\";s:75:\"We recommend using one of the following apps for two-factor authentication:\";s:20:\"google_authenticator\";s:20:\"Google Authenticator\";s:23:\"microsoft_authenticator\";s:23:\"Microsoft Authenticator\";s:5:\"authy\";s:5:\"Authy\";s:9:\"last_used\";s:9:\"Last used\";s:10:\"never_used\";s:10:\"Never used\";s:18:\"regenerate_warning\";s:110:\"Warning: Regenerating backup codes will invalidate all previous codes. Make sure to keep them in a safe place.\";s:13:\"configuration\";a:2:{s:5:\"title\";s:39:\"Two-Factor Authentication Configuration\";s:11:\"description\";s:73:\"Configure your two-factor authentication to enhance your account security\";}s:11:\"status_info\";a:1:{s:16:\"disabled_message\";s:47:\"Two-factor authentication is currently disabled\";}s:11:\"setup_steps\";a:3:{s:5:\"step1\";s:24:\"Step 1: Scan the QR code\";s:5:\"step2\";s:35:\"Step 2: Enter the verification code\";s:5:\"step3\";s:26:\"Step 3: Confirm activation\";}s:9:\"auth_code\";s:19:\"Authentication code\";}}s:17:\"api_documentation\";a:49:{s:5:\"title\";s:17:\"API Documentation\";s:11:\"description\";s:93:\"This documentation will help you integrate the license validation API into your applications.\";s:17:\"table_of_contents\";s:17:\"Table of Contents\";s:12:\"introduction\";s:12:\"Introduction\";s:14:\"authentication\";s:14:\"Authentication\";s:19:\"available_endpoints\";s:19:\"Available Endpoints\";s:22:\"integration_approaches\";s:22:\"Integration Approaches\";s:20:\"integration_examples\";s:20:\"Integration Examples\";s:20:\"best_practices_label\";s:14:\"Best Practices\";s:7:\"support\";a:2:{s:5:\"title\";s:7:\"Support\";s:11:\"description\";s:129:\"If you encounter issues while integrating the API or have questions, please don\'t hesitate to contact our technical support team.\";}s:17:\"introduction_text\";s:194:\"The AdminLicence API allows you to validate your users\' licenses directly from your applications. It provides secure endpoints to verify serial keys and obtain dynamic codes to enhance security.\";s:21:\"introduction_security\";s:79:\"All requests must be made via HTTPS to ensure the security of transmitted data.\";s:19:\"authentication_text\";s:134:\"To use the API, you must have a valid API key. You can create and manage your API keys in the API Keys section of the admin interface.\";s:21:\"authentication_header\";s:95:\"For endpoints that require authentication, you must include your API key in the request header:\";s:21:\"endpoint_check_serial\";s:23:\"Serial Key Verification\";s:20:\"endpoint_secure_code\";s:29:\"Secure Dynamic Code Retrieval\";s:12:\"endpoint_url\";s:3:\"URL\";s:15:\"endpoint_method\";s:6:\"Method\";s:13:\"endpoint_auth\";s:14:\"Authentication\";s:15:\"endpoint_params\";s:10:\"Parameters\";s:17:\"endpoint_response\";s:8:\"Response\";s:17:\"endpoint_required\";s:8:\"required\";s:17:\"endpoint_optional\";s:8:\"optional\";s:16:\"endpoint_success\";s:11:\"On success:\";s:14:\"endpoint_error\";s:9:\"On error:\";s:16:\"integration_text\";s:148:\"Several approaches are available to integrate our license system into your applications. Choose the one that best fits your development environment.\";s:8:\"approach\";s:8:\"Approach\";s:10:\"advantages\";s:10:\"Advantages\";s:15:\"recommended_use\";s:21:\"Recommended Use Cases\";s:12:\"php_standard\";s:12:\"PHP Standard\";s:14:\"php_advantages\";a:3:{i:0;s:32:\"Compatible with all PHP projects\";i:1;s:33:\"No external dependencies required\";i:2;s:33:\"Easy to adapt to any architecture\";}s:12:\"php_use_case\";s:78:\"PHP applications without specific framework, CMS integrations, legacy projects\";s:22:\"serial_key_description\";s:24:\"The serial key to verify\";s:18:\"domain_description\";s:36:\"The domain where the license is used\";s:14:\"ip_description\";s:45:\"The IP address from which the request is made\";s:15:\"jwt_description\";s:32:\"obtained during key verification\";s:17:\"token_description\";s:46:\"The JWT token obtained during key verification\";s:7:\"laravel\";s:7:\"Laravel\";s:10:\"javascript\";s:10:\"JavaScript\";s:7:\"flutter\";s:12:\"Flutter/Dart\";s:18:\"laravel_advantages\";a:3:{i:0;s:41:\"Native integration with Laravel framework\";i:1;s:32:\"Use of Laravel security features\";i:2;s:34:\"Support for middlewares and events\";}s:16:\"laravel_use_case\";s:58:\"Laravel applications, projects using the Laravel ecosystem\";s:21:\"javascript_advantages\";a:3:{i:0;s:35:\"Compatible with all modern browsers\";i:1;s:44:\"Easy to integrate into frontend applications\";i:2;s:36:\"Support for promises and async/await\";}s:19:\"javascript_use_case\";s:47:\"Frontend web applications, Node.js applications\";s:18:\"flutter_advantages\";a:3:{i:0;s:42:\"Cross-platform support (iOS, Android, Web)\";i:1;s:18:\"Native performance\";i:2;s:37:\"Easy integration with Flutter widgets\";}s:16:\"flutter_use_case\";s:56:\"Flutter mobile applications, cross-platform applications\";s:8:\"examples\";a:4:{s:5:\"title\";s:20:\"Integration Examples\";s:12:\"php_standard\";a:2:{s:5:\"title\";s:55:\"Approach 1: PHP Standard (Compatible with all projects)\";s:11:\"description\";s:113:\"This approach uses native PHP functions and is compatible with any PHP project, regardless of the framework used.\";}s:7:\"laravel\";a:2:{s:5:\"title\";s:44:\"Approach 2: Laravel (Simplified integration)\";s:11:\"description\";s:102:\"This approach uses Laravel\'s HTTP client for simpler and more elegant integration in Laravel projects.\";}s:7:\"flutter\";a:2:{s:5:\"title\";s:34:\"Flutter/Dart (Mobile applications)\";s:11:\"description\";s:100:\"This approach allows integrating the license system into mobile applications developed with Flutter.\";}}s:22:\"best_practices_section\";a:2:{s:5:\"title\";s:14:\"Best Practices\";s:5:\"items\";a:6:{i:0;s:25:\"Store JWT tokens securely\";i:1;s:35:\"Implement a token refresh mechanism\";i:2;s:36:\"Use HTTPS for all API communications\";i:3;s:61:\"Set up an error handling system to handle API error responses\";i:4;s:60:\"Limit access to your API key and never expose it client-side\";i:5;s:48:\"Implement a caching mechanism to limit API calls\";}}s:11:\"api_support\";a:2:{s:5:\"title\";s:7:\"Support\";s:11:\"description\";s:133:\"If you encounter any issues while integrating the API or have questions, please don\'t hesitate to contact our technical support team.\";}}s:15:\"email_providers\";a:3:{s:10:\"page_title\";s:29:\"Email Providers Documentation\";s:5:\"title\";s:29:\"Email Providers Documentation\";s:13:\"not_available\";s:45:\"Documentation is not available at the moment.\";}s:16:\"settings_license\";a:1:{s:7:\"license\";a:52:{s:5:\"title\";s:18:\"License Management\";s:25:\"no_license_key_configured\";s:46:\"No license key is configured in the .env file.\";s:22:\"api_verification_error\";s:36:\"Error during direct API verification\";s:20:\"valid_via_direct_api\";s:28:\"License valid via direct API\";s:22:\"invalid_via_direct_api\";s:30:\"License invalid via direct API\";s:13:\"status_detail\";s:15:\"Status: :status\";s:10:\"expired_on\";s:16:\"expired on :date\";s:15:\"expires_on_date\";s:16:\"expires on :date\";s:13:\"expiry_detail\";s:19:\"Expiration: :expiry\";s:17:\"registered_domain\";s:26:\"Registered domain: :domain\";s:13:\"registered_ip\";s:26:\"Registered IP address: :ip\";s:13:\"license_valid\";s:21:\"The license is valid.\";s:25:\"api_valid_service_invalid\";s:121:\"The API indicates that the license is valid, but the license service considers it invalid. Potential configuration issue.\";s:32:\"license_invalid_with_api_message\";s:80:\"The license is not valid according to the API and service. API message: :message\";s:22:\"license_details_header\";s:16:\"License details:\";s:18:\"verification_error\";s:53:\"An error occurred while verifying the license: :error\";s:10:\"info_title\";s:19:\"License Information\";s:16:\"installation_key\";s:24:\"Installation License Key\";s:8:\"copy_key\";s:8:\"Copy Key\";s:6:\"status\";s:14:\"License Status\";s:12:\"status_label\";s:6:\"Status\";s:5:\"valid\";s:5:\"Valid\";s:7:\"invalid\";s:7:\"Invalid\";s:11:\"expiry_date\";s:15:\"Expiration Date\";s:17:\"expiry_date_label\";s:15:\"Expiration Date\";s:10:\"expires_on\";s:10:\"Expires on\";s:10:\"last_check\";s:10:\"Last Check\";s:5:\"never\";s:5:\"Never\";s:9:\"check_now\";s:9:\"Check Now\";s:7:\"details\";s:15:\"License Details\";s:6:\"domain\";s:6:\"Domain\";s:10:\"ip_address\";s:10:\"IP Address\";s:11:\"not_defined\";s:11:\"Not defined\";s:13:\"status_active\";s:6:\"Active\";s:16:\"status_suspended\";s:9:\"Suspended\";s:14:\"status_revoked\";s:7:\"Revoked\";s:10:\"no_details\";s:55:\"No detailed information available for this license key.\";s:13:\"configuration\";s:21:\"License Configuration\";s:16:\"key_saved_in_env\";s:55:\"This key will be saved in your application\'s .env file.\";s:14:\"env_not_exists\";s:66:\"The .env file doesn\'t exist yet. It will be created automatically.\";s:13:\"save_settings\";s:13:\"Save Settings\";s:19:\"manual_verification\";s:19:\"Manual Verification\";s:24:\"manual_verification_desc\";s:133:\"You can force an immediate verification of the installation license. This will update the validity status and associated information.\";s:10:\"debug_info\";s:17:\"Debug Information\";s:14:\"detected_value\";s:14:\"Detected value\";s:9:\"not_found\";s:9:\"Not found\";s:9:\"http_code\";s:9:\"HTTP Code\";s:16:\"raw_api_response\";s:16:\"Raw API Response\";s:11:\"no_response\";s:11:\"No response\";s:17:\"unviewable_format\";s:17:\"Unviewable format\";s:19:\"auto_dismiss_alerts\";s:35:\"Auto-dismiss alerts after 5 seconds\";s:6:\"copied\";s:7:\"Copied!\";}}s:12:\"optimization\";a:53:{s:5:\"title\";s:18:\"Optimization Tools\";s:16:\"operation_result\";s:16:\"Operation Result\";s:12:\"example_code\";s:12:\"Example Code\";s:9:\"copy_code\";s:46:\"Copy this code and use it in your Blade views:\";s:13:\"logs_cleaning\";s:12:\"Log Cleaning\";s:17:\"current_logs_size\";s:15:\"Total logs size\";s:25:\"logs_cleaning_description\";s:105:\"This operation will clean unnecessary log files. Important logs will be archived, others will be deleted.\";s:8:\"all_logs\";s:8:\"All logs\";s:17:\"installation_logs\";s:17:\"Installation logs\";s:12:\"laravel_logs\";s:12:\"Laravel logs\";s:14:\"clean_all_logs\";s:14:\"Clean all logs\";s:15:\"delete_all_logs\";s:15:\"Delete all logs\";s:23:\"delete_all_logs_confirm\";s:82:\"Warning: This action will delete all log files. Are you sure you want to continue?\";s:23:\"clean_installation_logs\";s:23:\"Clean installation logs\";s:24:\"delete_installation_logs\";s:24:\"Delete installation logs\";s:32:\"delete_installation_logs_confirm\";s:95:\"Warning: This action will delete all installation log files. Are you sure you want to continue?\";s:18:\"clean_laravel_logs\";s:18:\"Clean Laravel logs\";s:19:\"delete_laravel_logs\";s:19:\"Delete Laravel logs\";s:27:\"delete_laravel_logs_confirm\";s:90:\"Warning: This action will delete all Laravel log files. Are you sure you want to continue?\";s:8:\"filename\";s:8:\"Filename\";s:4:\"size\";s:4:\"Size\";s:17:\"modification_date\";s:17:\"Modification date\";s:4:\"view\";s:4:\"View\";s:26:\"no_installation_logs_found\";s:31:\"No installation log files found\";s:21:\"no_laravel_logs_found\";s:26:\"No Laravel log files found\";s:18:\"image_optimization\";s:18:\"Image Optimization\";s:19:\"current_images_size\";s:19:\"Current images size\";s:31:\"images_optimization_description\";s:123:\"This operation will optimize images in the public/images/ folder to reduce their size while maintaining acceptable quality.\";s:7:\"quality\";s:15:\"Quality (0-100)\";s:16:\"high_compression\";s:16:\"High compression\";s:12:\"high_quality\";s:12:\"High quality\";s:18:\"force_optimization\";s:46:\"Force optimization (even if already optimized)\";s:15:\"optimize_images\";s:15:\"Optimize images\";s:19:\"api_diagnostic_tool\";s:19:\"API Diagnostic Tool\";s:26:\"api_diagnostic_description\";s:90:\"This tool allows you to diagnose and solve problems related to the license validation API.\";s:16:\"api_general_info\";s:23:\"General API information\";s:19:\"api_serial_key_test\";s:26:\"Serial key validation test\";s:19:\"api_connection_test\";s:28:\"External API connection test\";s:17:\"api_database_test\";s:24:\"Database connection test\";s:20:\"api_file_permissions\";s:22:\"File permissions check\";s:15:\"api_log_entries\";s:26:\"Display latest log entries\";s:23:\"api_default_credentials\";s:19:\"Default credentials\";s:19:\"open_api_diagnostic\";s:24:\"Open API Diagnostic Tool\";s:16:\"asset_versioning\";s:16:\"Asset Versioning\";s:28:\"asset_versioning_description\";s:164:\"The asset versioning system optimizes caching for CSS, JavaScript, and image files. It automatically adds a version parameter based on the file\'s modification date.\";s:10:\"asset_type\";s:10:\"Asset type\";s:8:\"css_file\";s:8:\"CSS File\";s:7:\"js_file\";s:15:\"JavaScript File\";s:5:\"image\";s:5:\"Image\";s:10:\"image_path\";s:10:\"Image path\";s:16:\"generate_example\";s:16:\"Generate example\";s:10:\"how_to_use\";s:10:\"How to use\";s:28:\"blade_directives_description\";s:50:\"In your Blade files, use the following directives:\";}s:21:\"licence_documentation\";a:5:{s:5:\"title\";s:21:\"License Documentation\";s:17:\"integration_guide\";a:2:{s:5:\"title\";s:25:\"License Integration Guide\";s:11:\"description\";s:110:\"This documentation will guide you through the process of integrating the license system into your application.\";}s:12:\"installation\";a:6:{s:5:\"title\";s:12:\"Installation\";s:11:\"description\";s:65:\"Detailed instructions for installation and initial configuration.\";s:4:\"tabs\";a:3:{s:3:\"php\";s:10:\"Simple PHP\";s:7:\"laravel\";s:7:\"Laravel\";s:7:\"flutter\";s:7:\"Flutter\";}s:3:\"php\";a:2:{s:5:\"title\";s:20:\"Installation for PHP\";s:11:\"description\";s:64:\"To integrate the license system into a standard PHP application:\";}s:7:\"laravel\";a:6:{s:5:\"title\";s:24:\"Installation for Laravel\";s:11:\"description\";s:59:\"To integrate the license system into a Laravel application:\";s:5:\"step1\";s:28:\"1. Create a Service Provider\";s:5:\"step2\";s:27:\"2. Create a License Service\";s:5:\"step3\";s:30:\"3. Create a configuration file\";s:5:\"step4\";s:45:\"4. Add the Service Provider to config/app.php\";}s:7:\"flutter\";a:5:{s:5:\"title\";s:24:\"Installation for Flutter\";s:11:\"description\";s:59:\"To integrate the license system into a Flutter application:\";s:5:\"step1\";s:35:\"1. Add dependencies to pubspec.yaml\";s:5:\"step2\";s:27:\"2. Create a license service\";s:5:\"step3\";s:39:\"3. Example of usage in your application\";}}s:12:\"verification\";a:4:{s:5:\"title\";s:20:\"License Verification\";s:11:\"description\";s:58:\"How to implement license verification in your application.\";s:12:\"security_tip\";s:117:\"For optimal security, we recommend verifying the license at each application startup and periodically during its use.\";s:14:\"best_practices\";a:6:{s:5:\"title\";s:14:\"Best Practices\";s:5:\"item1\";s:71:\"Store the license key securely (encrypted configuration file, database)\";s:5:\"item2\";s:56:\"Cache the verification result to avoid too many requests\";s:5:\"item3\";s:78:\"Plan for degraded behavior in case of connection failure to the license server\";s:5:\"item4\";s:61:\"Implement periodic verification for long-running applications\";s:5:\"item5\";s:56:\"Use HTTPS for all communications with the license server\";}}s:3:\"api\";a:4:{s:5:\"title\";s:22:\"License Management API\";s:11:\"description\";s:53:\"Complete documentation of the license management API.\";s:9:\"link_text\";s:51:\"For detailed API documentation, please refer to the\";s:10:\"link_title\";s:17:\"API Documentation\";}}s:6:\"admins\";a:14:{s:5:\"title\";s:25:\"Administrators Management\";s:14:\"administrators\";s:14:\"Administrators\";s:4:\"list\";s:19:\"Administrators List\";s:9:\"new_admin\";s:17:\"New Administrator\";s:2:\"id\";s:2:\"ID\";s:4:\"role\";s:4:\"Role\";s:13:\"creation_date\";s:13:\"Creation Date\";s:11:\"super_admin\";s:11:\"Super Admin\";s:5:\"admin\";s:5:\"Admin\";s:19:\"delete_confirmation\";s:19:\"Delete Confirmation\";s:22:\"delete_confirm_message\";s:69:\"Are you sure you want to delete administrator <strong>:name</strong>?\";s:6:\"create\";a:8:{s:5:\"title\";s:20:\"Create Administrator\";s:10:\"form_title\";s:13:\"Creation Form\";s:10:\"name_label\";s:4:\"Name\";s:11:\"email_label\";s:5:\"Email\";s:14:\"password_label\";s:8:\"Password\";s:27:\"password_confirmation_label\";s:21:\"Password Confirmation\";s:10:\"role_label\";s:4:\"Role\";s:13:\"submit_button\";s:20:\"Create Administrator\";}s:4:\"edit\";a:8:{s:5:\"title\";s:18:\"Edit Administrator\";s:10:\"form_title\";s:9:\"Edit Form\";s:10:\"name_label\";s:4:\"Name\";s:11:\"email_label\";s:5:\"Email\";s:14:\"password_label\";s:38:\"Password (leave empty to keep current)\";s:27:\"password_confirmation_label\";s:21:\"Password Confirmation\";s:10:\"role_label\";s:4:\"Role\";s:13:\"submit_button\";s:20:\"Update Administrator\";}s:8:\"messages\";a:4:{s:7:\"created\";s:35:\"Administrator created successfully.\";s:7:\"updated\";s:35:\"Administrator updated successfully.\";s:7:\"deleted\";s:35:\"Administrator deleted successfully.\";s:18:\"cannot_delete_self\";s:35:\"You cannot delete your own account.\";}}s:11:\"admin_login\";a:11:{s:5:\"title\";s:19:\"Administrator Login\";s:7:\"welcome\";s:10:\"Welcome to\";s:8:\"app_name\";s:12:\"AdminLicence\";s:8:\"subtitle\";s:43:\"Manage your licenses and projects with ease\";s:5:\"login\";s:5:\"Login\";s:5:\"email\";s:13:\"Email address\";s:8:\"password\";s:8:\"Password\";s:11:\"remember_me\";s:11:\"Remember me\";s:12:\"login_button\";s:6:\"Log in\";s:15:\"forgot_password\";s:16:\"Forgot password?\";s:8:\"features\";a:3:{s:17:\"secure_management\";s:25:\"Secure license management\";s:17:\"tracking_analysis\";s:27:\"Usage tracking and analysis\";s:8:\"and_more\";s:16:\"And much more...\";}}s:19:\"email_documentation\";a:13:{s:10:\"page_title\";s:29:\"Email Providers Documentation\";s:5:\"title\";s:29:\"Email Providers Documentation\";s:13:\"not_available\";s:45:\"Documentation is not available at the moment.\";s:11:\"description\";s:107:\"This document explains how to configure and use the different email providers available in the application.\";s:17:\"table_of_contents\";s:17:\"Table of Contents\";s:12:\"introduction\";a:2:{s:5:\"title\";s:12:\"Introduction\";s:11:\"description\";s:196:\"The application supports multiple email providers for sending notifications, alerts, and communications to users. Each provider has its own advantages, limitations, and configuration requirements.\";}s:4:\"smtp\";a:9:{s:5:\"title\";s:4:\"SMTP\";s:11:\"description\";s:173:\"SMTP (Simple Mail Transfer Protocol) is the standard method for sending emails over the Internet. It\'s a reliable and universal solution that works with most email services.\";s:13:\"configuration\";s:22:\"Required Configuration\";s:12:\"config_items\";a:7:{i:0;s:52:\"SMTP Host (e.g., smtp.gmail.com, smtp.office365.com)\";i:1;s:39:\"Port (usually 587 for TLS, 465 for SSL)\";i:2;s:35:\"Username (often your email address)\";i:3;s:8:\"Password\";i:4;s:27:\"Encryption method (TLS/SSL)\";i:5;s:12:\"From address\";i:6;s:9:\"From name\";}s:10:\"advantages\";s:10:\"Advantages\";s:15:\"advantages_list\";a:3:{i:0;s:41:\"Compatible with almost all email services\";i:1;s:41:\"Complete control over the sending process\";i:2;s:33:\"No dependency on third-party APIs\";}s:13:\"disadvantages\";s:13:\"Disadvantages\";s:18:\"disadvantages_list\";a:3:{i:0;s:31:\"Sometimes complex configuration\";i:1;s:50:\"May require security adjustments on some providers\";i:2;s:45:\"Sending limits depending on the SMTP provider\";}s:7:\"example\";s:21:\"Configuration Example\";}s:7:\"phpmail\";a:9:{s:5:\"title\";s:7:\"PHPMail\";s:11:\"description\";s:134:\"PHPMail uses the PHPMailer library to send emails. It\'s a robust solution that offers more features than PHP\'s native mail() function.\";s:13:\"configuration\";s:22:\"Required Configuration\";s:12:\"config_items\";a:1:{i:0;s:69:\"Same parameters as SMTP (since PHPMailer uses SMTP in the background)\";}s:10:\"advantages\";s:10:\"Advantages\";s:15:\"advantages_list\";a:3:{i:0;s:28:\"Advanced attachment handling\";i:1;s:20:\"Multilingual support\";i:2;s:40:\"Better error handling than native mail()\";}s:13:\"disadvantages\";s:13:\"Disadvantages\";s:18:\"disadvantages_list\";a:1:{i:0;s:15:\"Similar to SMTP\";}s:7:\"example\";s:21:\"Configuration Example\";}s:7:\"mailgun\";a:9:{s:5:\"title\";s:7:\"Mailgun\";s:11:\"description\";s:134:\"Mailgun is an email API service designed for developers. It offers high deliverability and advanced features for transactional emails.\";s:13:\"configuration\";s:22:\"Required Configuration\";s:12:\"config_items\";a:4:{i:0;s:15:\"Mailgun API key\";i:1;s:26:\"Verified domain on Mailgun\";i:2;s:12:\"From address\";i:3;s:9:\"From name\";}s:10:\"advantages\";s:10:\"Advantages\";s:15:\"advantages_list\";a:4:{i:0;s:19:\"High deliverability\";i:1;s:33:\"Detailed tracking (opens, clicks)\";i:2;s:30:\"Simple and well-documented API\";i:3;s:38:\"Generous free tier (1000 emails/month)\";}s:13:\"disadvantages\";s:13:\"Disadvantages\";s:18:\"disadvantages_list\";a:2:{i:0;s:28:\"Requires domain verification\";i:1;s:26:\"Paid beyond the free quota\";}s:7:\"example\";s:21:\"Configuration Example\";}s:9:\"mailchimp\";a:9:{s:5:\"title\";s:9:\"Mailchimp\";s:11:\"description\";s:158:\"Mailchimp Transactional (formerly Mandrill) is a transactional email service offered by Mailchimp, particularly suitable for marketing emails and newsletters.\";s:13:\"configuration\";s:22:\"Required Configuration\";s:12:\"config_items\";a:3:{i:0;s:31:\"Mailchimp Transactional API key\";i:1;s:23:\"Verified sender address\";i:2;s:9:\"From name\";}s:10:\"advantages\";s:10:\"Advantages\";s:15:\"advantages_list\";a:4:{i:0;s:24:\"Excellent deliverability\";i:1;s:37:\"Advanced tracking and analytics tools\";i:2;s:29:\"Sophisticated email templates\";i:3;s:40:\"Integration with the Mailchimp ecosystem\";}s:13:\"disadvantages\";s:13:\"Disadvantages\";s:18:\"disadvantages_list\";a:2:{i:0;s:12:\"Paid service\";i:1;s:34:\"More complex initial configuration\";}s:7:\"example\";s:21:\"Configuration Example\";}s:9:\"rapidmail\";a:9:{s:5:\"title\";s:9:\"Rapidmail\";s:11:\"description\";s:191:\"Rapidmail is a German email marketing service that strictly complies with GDPR. It is particularly suitable for European companies concerned about compliance with data protection regulations.\";s:13:\"configuration\";s:22:\"Required Configuration\";s:12:\"config_items\";a:3:{i:0;s:17:\"Rapidmail API key\";i:1;s:23:\"Verified sender address\";i:2;s:9:\"From name\";}s:10:\"advantages\";s:10:\"Advantages\";s:15:\"advantages_list\";a:4:{i:0;s:15:\"GDPR compliance\";i:1;s:23:\"Servers based in Europe\";i:2;s:31:\"Interface in multiple languages\";i:3;s:19:\"Good deliverability\";}s:13:\"disadvantages\";s:13:\"Disadvantages\";s:18:\"disadvantages_list\";a:2:{i:0;s:30:\"Less known than other services\";i:1;s:28:\"Less extensive documentation\";}s:7:\"example\";s:21:\"Configuration Example\";}s:10:\"comparison\";a:20:{s:5:\"title\";s:19:\"Provider Comparison\";s:14:\"deliverability\";s:14:\"Deliverability\";s:5:\"price\";s:5:\"Price\";s:13:\"ease_of_setup\";s:13:\"Ease of Setup\";s:17:\"advanced_features\";s:17:\"Advanced Features\";s:15:\"gdpr_compliance\";s:15:\"GDPR Compliance\";s:8:\"variable\";s:8:\"Variable\";s:4:\"free\";s:4:\"Free\";s:8:\"moderate\";s:8:\"Moderate\";s:7:\"limited\";s:7:\"Limited\";s:7:\"depends\";s:21:\"Depends on the server\";s:4:\"high\";s:4:\"High\";s:8:\"freemium\";s:8:\"Freemium\";s:4:\"easy\";s:4:\"Easy\";s:8:\"numerous\";s:8:\"Numerous\";s:4:\"good\";s:4:\"Good\";s:9:\"very_high\";s:9:\"Very high\";s:4:\"paid\";s:4:\"Paid\";s:13:\"very_numerous\";s:13:\"Very numerous\";s:9:\"excellent\";s:9:\"Excellent\";}s:15:\"troubleshooting\";a:7:{s:5:\"title\";s:15:\"Troubleshooting\";s:15:\"common_problems\";s:15:\"Common Problems\";s:15:\"emails_not_sent\";s:25:\"Emails are not being sent\";s:20:\"emails_not_sent_tips\";a:4:{i:0;s:22:\"Check your credentials\";i:1;s:46:\"Make sure the provider is correctly configured\";i:2;s:20:\"Check sending quotas\";i:3;s:18:\"Consult error logs\";}s:14:\"emails_as_spam\";s:23:\"Emails received as spam\";s:19:\"emails_as_spam_tips\";a:4:{i:0;s:54:\"Check your domain\'s SPF, DKIM, and DMARC configuration\";i:1;s:29:\"Use a verified sender address\";i:2;s:51:\"Avoid spam trigger words in the subject and content\";i:3;s:43:\"Make sure your domain has a good reputation\";}s:20:\"configuration_issues\";s:20:\"Configuration Issues\";}}}', 1750195079);
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('adminlicence_cache_translations.fr', 'a:38:{s:6:\"common\";a:49:{s:3:\"add\";s:7:\"Ajouter\";s:9:\"dashboard\";s:15:\"Tableau de bord\";s:8:\"projects\";s:7:\"Projets\";s:11:\"serial_keys\";s:15:\"Clés de série\";s:8:\"api_keys\";s:9:\"Clés API\";s:17:\"api_documentation\";s:17:\"Documentation API\";s:5:\"email\";s:5:\"Email\";s:6:\"logout\";s:12:\"Déconnexion\";s:7:\"version\";s:7:\"Version\";s:4:\"save\";s:11:\"Enregistrer\";s:6:\"cancel\";s:7:\"Annuler\";s:6:\"delete\";s:9:\"Supprimer\";s:4:\"edit\";s:8:\"Modifier\";s:6:\"create\";s:6:\"Créer\";s:4:\"back\";s:22:\"Retour aux paramètres\";s:7:\"actions\";s:7:\"Actions\";s:6:\"status\";s:6:\"Statut\";s:4:\"name\";s:3:\"Nom\";s:11:\"description\";s:11:\"Description\";s:4:\"date\";s:4:\"Date\";s:7:\"success\";s:7:\"Succès\";s:5:\"error\";s:6:\"Erreur\";s:7:\"warning\";s:13:\"Avertissement\";s:4:\"info\";s:11:\"Information\";s:5:\"total\";s:5:\"Total\";s:4:\"next\";s:7:\"Suivant\";s:8:\"previous\";s:11:\"Précédent\";s:6:\"search\";s:10:\"Rechercher\";s:6:\"filter\";s:7:\"Filtrer\";s:4:\"sort\";s:5:\"Trier\";s:4:\"view\";s:4:\"Voir\";s:8:\"settings\";s:11:\"Paramètres\";s:7:\"profile\";s:6:\"Profil\";s:13:\"notifications\";s:13:\"Notifications\";s:5:\"login\";s:9:\"Connexion\";s:6:\"submit\";s:7:\"Envoyer\";s:7:\"loading\";s:13:\"Chargement...\";s:6:\"manage\";s:6:\"Gérer\";s:11:\"information\";s:11:\"Information\";s:14:\"no_description\";s:18:\"Aucune description\";s:3:\"all\";s:4:\"Tous\";s:4:\"used\";s:8:\"Utilisé\";s:6:\"unused\";s:12:\"Non utilisé\";s:6:\"active\";s:5:\"Actif\";s:8:\"inactive\";s:7:\"Inactif\";s:9:\"suspended\";s:8:\"Suspendu\";s:7:\"revoked\";s:9:\"Révoqué\";s:7:\"expired\";s:7:\"Expiré\";s:7:\"pending\";s:10:\"En attente\";}s:12:\"translations\";a:19:{s:5:\"title\";s:23:\"Gestion des traductions\";s:19:\"manage_translations\";s:22:\"Gérer les traductions\";s:15:\"add_translation\";s:22:\"Ajouter une traduction\";s:19:\"add_new_translation\";s:31:\"Ajouter une nouvelle traduction\";s:7:\"add_new\";s:15:\"Ajouter nouveau\";s:22:\"available_translations\";s:23:\"Traductions disponibles\";s:19:\"available_languages\";s:19:\"Langues disponibles\";s:4:\"file\";s:7:\"Fichier\";s:3:\"key\";s:4:\"Clé\";s:11:\"translation\";s:10:\"Traduction\";s:16:\"file_placeholder\";s:39:\"Nom du fichier (ex: common, validation)\";s:15:\"key_placeholder\";s:18:\"Identifiant unique\";s:17:\"value_placeholder\";s:13:\"Texte traduit\";s:14:\"delete_confirm\";s:55:\"Êtes-vous sûr de vouloir supprimer cette traduction ?\";s:13:\"file_required\";s:33:\"Veuillez saisir un nom de fichier\";s:12:\"key_required\";s:24:\"Veuillez saisir une clé\";s:14:\"value_required\";s:30:\"Veuillez saisir une traduction\";s:8:\"language\";s:6:\"Langue\";s:15:\"select_language\";s:24:\"Sélectionner une langue\";}s:6:\"layout\";a:14:{s:7:\"support\";s:7:\"Support\";s:7:\"tickets\";s:7:\"Tickets\";s:19:\"super_admin_tickets\";s:19:\"Tickets Super Admin\";s:13:\"documentation\";s:13:\"Documentation\";s:18:\"keys_documentation\";s:23:\"Documentation des clés\";s:29:\"email_providers_documentation\";s:38:\"Documentation des fournisseurs d\'email\";s:18:\"saas_documentation\";s:35:\"Documentation SaaS multiutilisateur\";s:12:\"version_info\";s:23:\"Informations de version\";s:7:\"general\";s:9:\"Général\";s:15:\"two_factor_auth\";s:33:\"Authentification à deux facteurs\";s:7:\"version\";s:7:\"Version\";s:17:\"toggle_navigation\";s:17:\"Toggle navigation\";s:10:\"created_by\";s:10:\"Created by\";s:19:\"all_rights_reserved\";s:19:\"All rights reserved\";}s:4:\"auth\";a:16:{s:5:\"login\";s:9:\"Connexion\";s:5:\"email\";s:13:\"Adresse email\";s:8:\"password\";s:12:\"Mot de passe\";s:11:\"remember_me\";s:18:\"Se souvenir de moi\";s:15:\"forgot_password\";s:22:\"Mot de passe oublié ?\";s:12:\"login_button\";s:12:\"Se connecter\";s:8:\"register\";s:10:\"S\'inscrire\";s:6:\"logout\";s:15:\"Se déconnecter\";s:14:\"reset_password\";s:30:\"Réinitialiser le mot de passe\";s:16:\"confirm_password\";s:25:\"Confirmer le mot de passe\";s:16:\"current_password\";s:19:\"Mot de passe actuel\";s:12:\"new_password\";s:20:\"Nouveau mot de passe\";s:21:\"password_confirmation\";s:28:\"Confirmation du mot de passe\";s:4:\"name\";s:4:\"Name\";s:18:\"already_registered\";s:19:\"Already registered?\";s:24:\"send_password_reset_link\";s:24:\"Send password reset link\";}s:9:\"dashboard\";a:30:{s:5:\"title\";s:15:\"Tableau de bord\";s:7:\"welcome\";s:27:\"Bienvenue dans AdminLicence\";s:14:\"total_projects\";s:14:\"Projets totaux\";s:10:\"total_keys\";s:13:\"Clés totales\";s:11:\"active_keys\";s:13:\"Clés actives\";s:9:\"used_keys\";s:16:\"Clés utilisées\";s:14:\"suspended_keys\";s:16:\"Clés suspendues\";s:12:\"revoked_keys\";s:17:\"Clés révoquées\";s:21:\"keys_usage_by_project\";s:32:\"Utilisation des clés par projet\";s:14:\"available_keys\";s:17:\"Clés disponibles\";s:23:\"keys_usage_last_30_days\";s:41:\"Utilisation des clés (30 derniers jours)\";s:23:\"distribution_by_project\";s:23:\"Répartition par projet\";s:4:\"used\";s:10:\"utilisées\";s:9:\"available\";s:11:\"disponibles\";s:9:\"low_stock\";s:12:\"Stock faible\";s:16:\"sufficient_stock\";s:15:\"Stock suffisant\";s:7:\"no_keys\";s:11:\"Aucune clé\";s:11:\"activations\";s:11:\"Activations\";s:13:\"deactivations\";s:15:\"Désactivations\";s:15:\"recent_activity\";s:18:\"Activité récente\";s:10:\"statistics\";s:12:\"Statistiques\";s:13:\"quick_actions\";s:15:\"Actions rapides\";s:13:\"system_status\";s:17:\"État du système\";s:10:\"last_login\";s:19:\"Dernière connexion\";s:11:\"recent_keys\";s:15:\"Clés récentes\";s:5:\"usage\";s:11:\"Utilisation\";s:6:\"charts\";s:10:\"Graphiques\";s:11:\"usage_chart\";s:23:\"Graphique d\'utilisation\";s:26:\"project_distribution_chart\";s:36:\"Graphique de répartition par projet\";s:14:\"projects_count\";s:8:\"Projects\";}s:10:\"pagination\";a:9:{s:8:\"per_page\";s:16:\":number par page\";s:7:\"showing\";s:50:\"Affichage de :first à :last sur :total résultats\";s:4:\"next\";s:7:\"Suivant\";s:8:\"previous\";s:11:\"Précédent\";s:5:\"first\";s:7:\"Premier\";s:4:\"last\";s:7:\"Dernier\";s:2:\"to\";s:2:\"to\";s:2:\"of\";s:2:\"of\";s:7:\"results\";s:7:\"results\";}s:8:\"projects\";a:39:{s:5:\"title\";s:19:\"Gestion des Projets\";s:14:\"create_project\";s:16:\"Créer un projet\";s:12:\"edit_project\";s:18:\"Modifier le projet\";s:12:\"project_name\";s:13:\"Nom du projet\";s:19:\"project_description\";s:21:\"Description du projet\";s:14:\"project_status\";s:16:\"Statut du projet\";s:7:\"project\";s:6:\"Projet\";s:6:\"active\";s:5:\"Actif\";s:8:\"inactive\";s:7:\"Inactif\";s:11:\"no_projects\";s:20:\"Aucun projet trouvé\";s:15:\"project_details\";s:18:\"Détails du projet\";s:16:\"project_settings\";s:21:\"Paramètres du projet\";s:12:\"project_keys\";s:15:\"Clés du projet\";s:16:\"project_activity\";s:19:\"Activité du projet\";s:19:\"project_information\";s:22:\"Informations du projet\";s:14:\"no_description\";s:18:\"Aucune description\";s:11:\"website_url\";s:11:\"URL du site\";s:13:\"not_specified\";s:15:\"Non spécifiée\";s:13:\"creation_date\";s:17:\"Date de création\";s:11:\"last_update\";s:22:\"Dernière mise à jour\";s:14:\"delete_project\";s:19:\"Supprimer le projet\";s:19:\"delete_confirmation\";s:80:\"Êtes-vous sûr de vouloir supprimer ce projet ? Cette action est irréversible.\";s:13:\"creation_form\";s:23:\"Formulaire de création\";s:9:\"edit_form\";s:26:\"Formulaire de modification\";s:12:\"save_changes\";s:29:\"Enregistrer les modifications\";s:4:\"name\";s:3:\"Nom\";s:11:\"description\";s:11:\"Description\";s:10:\"created_at\";s:9:\"Créé le\";s:7:\"actions\";s:7:\"Actions\";s:10:\"create_new\";s:24:\"Créer un nouveau projet\";s:7:\"details\";s:18:\"Détails du projet\";s:10:\"management\";s:19:\"Gestion des projets\";s:11:\"new_project\";s:14:\"Nouveau projet\";s:12:\"license_keys\";s:16:\"Clés de licence\";s:8:\"api_keys\";s:9:\"Clés API\";s:14:\"confirm_delete\";s:48:\"Êtes-vous sûr de vouloir supprimer ce projet ?\";s:6:\"create\";a:10:{s:5:\"title\";s:16:\"Créer un projet\";s:10:\"form_title\";s:23:\"Formulaire de création\";s:10:\"name_label\";s:13:\"Nom du projet\";s:17:\"description_label\";s:11:\"Description\";s:17:\"website_url_label\";s:11:\"URL du site\";s:23:\"website_url_placeholder\";s:19:\"https://exemple.com\";s:12:\"status_label\";s:6:\"Statut\";s:13:\"status_active\";s:5:\"Actif\";s:15:\"status_inactive\";s:7:\"Inactif\";s:13:\"submit_button\";s:16:\"Créer le projet\";}s:4:\"edit\";a:10:{s:5:\"title\";s:18:\"Modifier le projet\";s:10:\"form_title\";s:26:\"Formulaire de modification\";s:10:\"name_label\";s:13:\"Nom du projet\";s:17:\"description_label\";s:11:\"Description\";s:17:\"website_url_label\";s:11:\"URL du site\";s:23:\"website_url_placeholder\";s:19:\"https://exemple.com\";s:12:\"status_label\";s:6:\"Statut\";s:13:\"status_active\";s:5:\"Actif\";s:15:\"status_inactive\";s:7:\"Inactif\";s:13:\"submit_button\";s:29:\"Enregistrer les modifications\";}s:4:\"show\";a:18:{s:5:\"title\";s:18:\"Détails du projet\";s:7:\"details\";s:22:\"Informations du projet\";s:4:\"name\";s:3:\"Nom\";s:11:\"description\";s:11:\"Description\";s:11:\"website_url\";s:11:\"URL du site\";s:6:\"status\";s:6:\"Statut\";s:13:\"status_active\";s:5:\"Actif\";s:15:\"status_inactive\";s:7:\"Inactif\";s:10:\"created_at\";s:17:\"Date de création\";s:10:\"updated_at\";s:22:\"Dernière mise à jour\";s:10:\"statistics\";s:12:\"Statistiques\";s:18:\"total_license_keys\";s:26:\"Total des clés de licence\";s:14:\"total_api_keys\";s:19:\"Total des clés API\";s:7:\"actions\";s:7:\"Actions\";s:18:\"create_license_key\";s:26:\"Créer une clé de licence\";s:14:\"create_api_key\";s:19:\"Créer une clé API\";s:14:\"delete_project\";s:19:\"Supprimer le projet\";s:14:\"confirm_delete\";s:80:\"Êtes-vous sûr de vouloir supprimer ce projet ? Cette action est irréversible.\";}}s:11:\"serial_keys\";a:58:{s:5:\"title\";s:28:\"Gestion des clés de licence\";s:10:\"create_key\";s:15:\"Créer une clé\";s:8:\"edit_key\";s:16:\"Modifier la clé\";s:3:\"key\";s:4:\"Clé\";s:7:\"project\";s:6:\"Projet\";s:6:\"status\";s:6:\"Statut\";s:10:\"expiration\";s:10:\"Expiration\";s:7:\"no_keys\";s:20:\"Aucune clé trouvée\";s:8:\"generate\";s:9:\"Générer\";s:6:\"active\";s:6:\"Active\";s:8:\"inactive\";s:8:\"Inactive\";s:7:\"expired\";s:8:\"Expirée\";s:9:\"suspended\";s:9:\"Suspendue\";s:7:\"revoked\";s:10:\"Révoquée\";s:11:\"key_details\";s:19:\"Détails de la clé\";s:9:\"key_usage\";s:22:\"Utilisation de la clé\";s:11:\"key_history\";s:21:\"Historique de la clé\";s:11:\"hardware_id\";s:12:\"ID Matériel\";s:10:\"created_at\";s:9:\"Créé le\";s:7:\"actions\";s:7:\"Actions\";s:10:\"create_new\";s:24:\"Créer une nouvelle clé\";s:7:\"details\";s:19:\"Détails de la clé\";s:8:\"activate\";s:7:\"Activer\";s:10:\"deactivate\";s:11:\"Désactiver\";s:4:\"list\";s:26:\"Liste des clés de licence\";s:6:\"domain\";s:7:\"Domaine\";s:10:\"ip_address\";s:10:\"Adresse IP\";s:13:\"not_specified\";s:14:\"Non spécifié\";s:13:\"no_expiration\";s:15:\"Sans expiration\";s:15:\"confirm_suspend\";s:49:\"Êtes-vous sûr de vouloir suspendre cette clé ?\";s:14:\"confirm_revoke\";s:49:\"Êtes-vous sûr de vouloir révoquer cette clé ?\";s:7:\"suspend\";s:9:\"Suspendre\";s:6:\"revoke\";s:9:\"Révoquer\";s:4:\"edit\";s:8:\"Modifier\";s:4:\"view\";s:4:\"Voir\";s:11:\"information\";s:23:\"Informations de la clé\";s:11:\"license_key\";s:15:\"Clé de licence\";s:15:\"expiration_date\";s:17:\"Date d\'expiration\";s:13:\"status_active\";s:6:\"Active\";s:16:\"status_suspended\";s:9:\"Suspendue\";s:14:\"status_revoked\";s:10:\"Révoquée\";s:14:\"status_expired\";s:8:\"Expirée\";s:14:\"create_success\";s:42:\"Clé(s) de licence créée(s) avec succès\";s:14:\"update_success\";s:41:\"Clé de licence mise à jour avec succès\";s:14:\"delete_success\";s:39:\"Clé de licence supprimée avec succès\";s:15:\"suspend_success\";s:38:\"Clé de licence suspendue avec succès\";s:14:\"revoke_success\";s:39:\"Clé de licence révoquée avec succès\";s:18:\"reactivate_success\";s:40:\"Clé de licence réactivée avec succès\";s:11:\"create_form\";s:23:\"Formulaire de création\";s:14:\"select_project\";s:23:\"Sélectionnez un projet\";s:8:\"quantity\";s:28:\"Nombre de clés à générer\";s:15:\"domain_optional\";s:19:\"Domaine (optionnel)\";s:19:\"ip_address_optional\";s:22:\"Adresse IP (optionnel)\";s:24:\"expiration_date_optional\";s:29:\"Date d\'expiration (optionnel)\";s:11:\"create_keys\";s:16:\"Créer les clés\";s:13:\"search_by_key\";s:18:\"Recherche par clé\";s:8:\"key_used\";s:14:\"Clé utilisée\";s:18:\"search_placeholder\";s:40:\"Rechercher par clé, domaine, IP, etc...\";}s:3:\"api\";a:18:{s:5:\"title\";s:17:\"Documentation API\";s:11:\"description\";s:73:\"Utilisez ces endpoints pour intégrer AdminLicence dans votre application\";s:8:\"endpoint\";s:8:\"Endpoint\";s:6:\"method\";s:8:\"Méthode\";s:10:\"parameters\";s:11:\"Paramètres\";s:8:\"response\";s:8:\"Réponse\";s:8:\"api_keys\";s:9:\"Clés API\";s:11:\"active_keys\";s:7:\"Actives\";s:12:\"revoked_keys\";s:11:\"Révoquées\";s:10:\"create_key\";s:19:\"Créer une clé API\";s:7:\"example\";s:7:\"Exemple\";s:14:\"authentication\";s:16:\"Authentification\";s:11:\"rate_limits\";s:15:\"Limites de taux\";s:11:\"error_codes\";s:14:\"Codes d\'erreur\";s:7:\"testing\";s:13:\"Test de l\'API\";s:13:\"documentation\";s:17:\"Documentation API\";s:9:\"endpoints\";s:9:\"Endpoints\";s:7:\"request\";s:8:\"Requête\";}s:5:\"email\";a:19:{s:5:\"title\";s:24:\"Configuration des emails\";s:8:\"settings\";s:16:\"Paramètres SMTP\";s:6:\"driver\";s:6:\"Driver\";s:4:\"host\";s:12:\"Serveur SMTP\";s:4:\"port\";s:4:\"Port\";s:10:\"encryption\";s:11:\"Chiffrement\";s:8:\"username\";s:17:\"Nom d\'utilisateur\";s:8:\"password\";s:12:\"Mot de passe\";s:12:\"from_address\";s:21:\"Adresse d\'expédition\";s:9:\"from_name\";s:17:\"Nom d\'expédition\";s:10:\"test_email\";s:24:\"Envoyer un email de test\";s:9:\"templates\";s:9:\"Templates\";s:4:\"logs\";s:8:\"Journaux\";s:5:\"queue\";s:14:\"File d\'attente\";s:8:\"greeting\";s:9:\"Bonjour !\";s:7:\"regards\";s:12:\"Cordialement\";s:16:\"trouble_clicking\";s:117:\"Si vous avez des difficultés à cliquer sur le bouton, copiez et collez l\'URL ci-dessous dans votre navigateur web :\";s:19:\"all_rights_reserved\";s:23:\"Tous droits réservés.\";s:9:\"providers\";a:3:{s:7:\"phpmail\";a:11:{s:5:\"title\";s:21:\"Configuration PHPMail\";s:8:\"settings\";s:19:\"Paramètres d\'envoi\";s:11:\"description\";s:105:\"Gestion SMTP avancée pour l\'envoi d\'emails avec support des templates personnalisés et suivi des envois\";s:18:\"test_configuration\";s:23:\"Tester la configuration\";s:18:\"save_configuration\";s:28:\"Enregistrer la configuration\";s:10:\"statistics\";s:12:\"Statistiques\";s:17:\"emails_sent_today\";s:27:\"Emails envoyés aujourd\'hui\";s:17:\"emails_sent_month\";s:23:\"Emails envoyés ce mois\";s:4:\"logs\";s:8:\"Journaux\";s:17:\"no_logs_available\";s:24:\"Aucun journal disponible\";s:10:\"clear_logs\";s:18:\"Vider les journaux\";}s:7:\"mailgun\";a:5:{s:5:\"title\";s:21:\"Configuration Mailgun\";s:8:\"settings\";s:19:\"Paramètres Mailgun\";s:11:\"description\";s:58:\"Service d\'envoi d\'emails transactionnels via l\'API Mailgun\";s:6:\"domain\";s:15:\"Domaine Mailgun\";s:7:\"api_key\";s:16:\"Clé API Mailgun\";}s:9:\"mailchimp\";a:5:{s:5:\"title\";s:23:\"Configuration Mailchimp\";s:8:\"settings\";s:21:\"Paramètres Mailchimp\";s:11:\"description\";s:94:\"Gestion des campagnes d\'emailing, listes de diffusion et templates avec intégration Mailchimp\";s:7:\"api_key\";s:18:\"Clé API Mailchimp\";s:7:\"list_id\";s:14:\"ID de la liste\";}}}s:12:\"subscription\";a:16:{s:5:\"plans\";s:18:\"Plans d\'abonnement\";s:12:\"current_plan\";s:11:\"Plan actuel\";s:7:\"upgrade\";s:16:\"Mettre à niveau\";s:9:\"downgrade\";s:12:\"Rétrograder\";s:6:\"cancel\";s:20:\"Annuler l\'abonnement\";s:6:\"resume\";s:22:\"Reprendre l\'abonnement\";s:15:\"payment_methods\";s:21:\"Méthodes de paiement\";s:8:\"invoices\";s:8:\"Factures\";s:7:\"billing\";s:11:\"Facturation\";s:5:\"usage\";s:11:\"Utilisation\";s:6:\"limits\";s:7:\"Limites\";s:7:\"history\";s:10:\"Historique\";s:14:\"payment_method\";s:14:\"Payment method\";s:21:\"update_payment_method\";s:21:\"Update payment method\";s:15:\"billing_history\";s:15:\"Billing history\";s:7:\"invoice\";s:7:\"Invoice\";}s:8:\"language\";a:18:{s:6:\"select\";s:23:\"Sélectionner la langue\";s:2:\"en\";s:7:\"Anglais\";s:2:\"fr\";s:9:\"Français\";s:2:\"es\";s:8:\"Espagnol\";s:2:\"de\";s:8:\"Allemand\";s:2:\"it\";s:7:\"Italien\";s:2:\"pt\";s:9:\"Portugais\";s:2:\"nl\";s:12:\"Néerlandais\";s:2:\"ru\";s:5:\"Russe\";s:2:\"zh\";s:7:\"Chinois\";s:2:\"ja\";s:8:\"Japonais\";s:2:\"tr\";s:4:\"Turc\";s:2:\"ar\";s:5:\"Arabe\";s:20:\"changed_successfully\";s:39:\"La langue a été changée avec succès\";s:14:\"invalid_locale\";s:45:\"La langue sélectionnée n\'est pas disponible\";s:14:\"already_active\";s:30:\"Cette langue est déjà active\";s:15:\"change_language\";s:17:\"Changer de langue\";s:16:\"language_changed\";s:39:\"La langue a été changée avec succès\";}s:7:\"install\";a:46:{s:5:\"title\";s:24:\"Assistant d\'installation\";s:7:\"welcome\";s:44:\"Bienvenue dans l\'installation d\'AdminLicence\";s:15:\"welcome_message\";s:143:\"Cet assistant vous guidera à travers le processus d\'installation d\'AdminLicence. Veuillez suivre les étapes pour compléter la configuration.\";s:17:\"already_installed\";s:116:\"AdminLicence est déjà installé. Si vous souhaitez réinstaller, veuillez supprimer le fichier .env et réessayer.\";s:12:\"requirements\";s:10:\"Prérequis\";s:20:\"requirements_message\";s:107:\"Veuillez vous assurer que votre serveur répond aux exigences suivantes avant de poursuivre l\'installation.\";s:11:\"php_version\";s:11:\"Version PHP\";s:20:\"php_version_required\";s:32:\"PHP 8.2 ou supérieur est requis\";s:13:\"database_step\";s:36:\"Configuration de la base de données\";s:16:\"database_message\";s:80:\"Veuillez fournir les détails de connexion à votre base de données ci-dessous.\";s:13:\"db_connection\";s:32:\"Connexion à la base de données\";s:7:\"db_host\";s:28:\"Hôte de la base de données\";s:7:\"db_port\";s:27:\"Port de la base de données\";s:11:\"db_database\";s:26:\"Nom de la base de données\";s:11:\"db_username\";s:40:\"Nom d\'utilisateur de la base de données\";s:11:\"db_password\";s:35:\"Mot de passe de la base de données\";s:20:\"db_connection_failed\";s:45:\"Échec de la connexion à la base de données\";s:13:\"language_step\";s:26:\"Configuration de la langue\";s:16:\"language_message\";s:67:\"Veuillez sélectionner votre langue préférée pour l\'application.\";s:9:\"mail_step\";s:24:\"Configuration des emails\";s:12:\"mail_message\";s:72:\"Veuillez fournir les détails de votre serveur de messagerie ci-dessous.\";s:11:\"mail_driver\";s:20:\"Driver de messagerie\";s:9:\"mail_host\";s:19:\"Hôte de messagerie\";s:9:\"mail_port\";s:18:\"Port de messagerie\";s:13:\"mail_username\";s:31:\"Nom d\'utilisateur de messagerie\";s:13:\"mail_password\";s:26:\"Mot de passe de messagerie\";s:15:\"mail_encryption\";s:25:\"Chiffrement de messagerie\";s:17:\"mail_from_address\";s:21:\"Adresse d\'expédition\";s:14:\"mail_from_name\";s:17:\"Nom d\'expédition\";s:10:\"admin_step\";s:21:\"Compte administrateur\";s:13:\"admin_message\";s:55:\"Veuillez créer votre compte administrateur ci-dessous.\";s:10:\"admin_name\";s:3:\"Nom\";s:11:\"admin_email\";s:5:\"Email\";s:14:\"admin_password\";s:12:\"Mot de passe\";s:27:\"admin_password_confirmation\";s:25:\"Confirmer le mot de passe\";s:10:\"enable_2fa\";s:43:\"Activer l\'authentification à deux facteurs\";s:22:\"enable_2fa_description\";s:96:\"Renforce la sécurité de votre compte en exigeant un code supplémentaire lors de la connexion.\";s:13:\"complete_step\";s:22:\"Installation terminée\";s:16:\"complete_message\";s:114:\"AdminLicence a été installé avec succès. Vous pouvez maintenant vous connecter à votre compte administrateur.\";s:11:\"go_to_login\";s:29:\"Aller à la page de connexion\";s:21:\"installation_complete\";s:37:\"Installation terminée avec succès !\";s:10:\"go_to_site\";s:13:\"Aller au site\";s:6:\"finish\";s:6:\"Finish\";s:9:\"next_step\";s:9:\"Next step\";s:13:\"previous_step\";s:13:\"Previous step\";s:7:\"install\";s:7:\"Install\";}s:8:\"api_keys\";a:38:{s:10:\"management\";s:21:\"Gestion des clés API\";s:6:\"create\";s:19:\"Créer une clé API\";s:7:\"details\";s:23:\"Détails de la clé API\";s:4:\"edit\";s:20:\"Modifier la clé API\";s:7:\"project\";s:6:\"Projet\";s:12:\"all_projects\";s:16:\"Tous les projets\";s:6:\"status\";s:6:\"Statut\";s:10:\"all_status\";s:16:\"Tous les statuts\";s:6:\"active\";s:6:\"Active\";s:13:\"active_plural\";s:7:\"Actives\";s:7:\"revoked\";s:10:\"Révoquée\";s:14:\"revoked_plural\";s:11:\"Révoquées\";s:7:\"expired\";s:8:\"Expirée\";s:14:\"expired_plural\";s:9:\"Expirées\";s:11:\"used_plural\";s:10:\"Utilisées\";s:4:\"used\";s:9:\"Utilisée\";s:3:\"key\";s:4:\"Clé\";s:8:\"key_name\";s:14:\"Nom de la clé\";s:6:\"secret\";s:6:\"Secret\";s:4:\"name\";s:3:\"Nom\";s:9:\"last_used\";s:21:\"Dernière utilisation\";s:5:\"never\";s:6:\"Jamais\";s:14:\"confirm_revoke\";s:53:\"Êtes-vous sûr de vouloir révoquer cette clé API ?\";s:18:\"confirm_reactivate\";s:54:\"Êtes-vous sûr de vouloir réactiver cette clé API ?\";s:14:\"confirm_delete\";s:53:\"Êtes-vous sûr de vouloir supprimer cette clé API ?\";s:6:\"revoke\";s:9:\"Révoquer\";s:10:\"reactivate\";s:10:\"Réactiver\";s:11:\"permissions\";s:11:\"Permissions\";s:16:\"save_permissions\";s:27:\"Enregistrer les permissions\";s:10:\"basic_info\";s:20:\"Informations de base\";s:11:\"usage_stats\";s:26:\"Statistiques d\'utilisation\";s:11:\"total_usage\";s:18:\"Utilisation totale\";s:10:\"created_at\";s:10:\"Créée le\";s:15:\"expiration_date\";s:17:\"Date d\'expiration\";s:18:\"no_expiration_hint\";s:35:\"Laisser vide pour aucune expiration\";s:14:\"select_project\";s:23:\"Sélectionner un projet\";s:7:\"no_keys\";s:25:\"Aucune clé API trouvée.\";s:4:\"none\";s:6:\"Aucune\";}s:11:\"permissions\";a:10:{s:12:\"license_read\";s:20:\"Lecture des licences\";s:13:\"license_write\";s:22:\"Écriture des licences\";s:14:\"license_delete\";s:24:\"Suppression des licences\";s:12:\"project_read\";s:19:\"Lecture des projets\";s:13:\"project_write\";s:21:\"Écriture des projets\";s:14:\"project_delete\";s:23:\"Suppression des projets\";s:9:\"user_read\";s:24:\"Lecture des utilisateurs\";s:10:\"user_write\";s:26:\"Écriture des utilisateurs\";s:11:\"user_delete\";s:28:\"Suppression des utilisateurs\";s:10:\"stats_read\";s:24:\"Lecture des statistiques\";}s:14:\"api_diagnostic\";a:15:{s:5:\"title\";s:14:\"Diagnostic API\";s:10:\"tool_title\";s:23:\"Outil de diagnostic API\";s:15:\"open_new_window\";s:33:\"Ouvrir dans une nouvelle fenêtre\";s:11:\"access_info\";s:21:\"Informations d\'accès\";s:8:\"tool_url\";s:58:\"L\'outil de diagnostic API est accessible à l\'URL suivante\";s:19:\"default_credentials\";s:24:\"Identifiants par défaut\";s:18:\"available_features\";s:28:\"Fonctionnalités disponibles\";s:8:\"features\";a:6:{s:12:\"general_info\";a:2:{s:5:\"title\";s:24:\"Informations générales\";s:11:\"description\";s:57:\"Vue d\'ensemble de l\'API et de la configuration du serveur\";}s:15:\"serial_key_test\";a:2:{s:5:\"title\";s:22:\"Test de clé de série\";s:11:\"description\";s:44:\"Vérifiez la validité d\'une clé de licence\";}s:15:\"connection_test\";a:2:{s:5:\"title\";s:17:\"Test de connexion\";s:11:\"description\";s:45:\"Vérifiez la connectivité avec l\'API externe\";}s:13:\"database_test\";a:2:{s:5:\"title\";s:24:\"Test de base de données\";s:11:\"description\";s:45:\"Vérifiez la connexion à la base de données\";}s:17:\"permissions_check\";a:2:{s:5:\"title\";s:29:\"Vérification des permissions\";s:11:\"description\";s:49:\"Contrôlez les permissions des fichiers critiques\";}s:12:\"logs_display\";a:2:{s:5:\"title\";s:18:\"Affichage des logs\";s:11:\"description\";s:40:\"Consultez les dernières entrées de log\";}}s:15:\"serial_key_test\";a:10:{s:7:\"section\";s:22:\"Test de clé de série\";s:5:\"title\";s:22:\"Test de clé de série\";s:9:\"key_label\";s:14:\"Clé de série\";s:10:\"select_key\";s:22:\"Sélectionnez une clé\";s:10:\"custom_key\";s:30:\"Saisir une clé personnalisée\";s:16:\"custom_key_label\";s:19:\"Clé personnalisée\";s:12:\"domain_label\";s:19:\"Domaine (optionnel)\";s:8:\"ip_label\";s:22:\"Adresse IP (optionnel)\";s:11:\"test_button\";s:14:\"Tester la clé\";s:12:\"result_title\";s:9:\"Résultat\";}s:11:\"server_info\";a:9:{s:7:\"section\";s:27:\"Informations sur le serveur\";s:5:\"title\";s:27:\"Informations sur le serveur\";s:11:\"php_version\";s:11:\"Version PHP\";s:15:\"laravel_version\";s:15:\"Version Laravel\";s:10:\"web_server\";s:11:\"Serveur Web\";s:2:\"os\";s:23:\"Système d\'exploitation\";s:8:\"database\";s:16:\"Base de données\";s:8:\"timezone\";s:14:\"Fuseau horaire\";s:14:\"php_extensions\";s:14:\"Extensions PHP\";}s:7:\"buttons\";a:4:{s:19:\"test_api_connection\";s:23:\"Tester la connexion API\";s:13:\"test_database\";s:26:\"Tester la base de données\";s:17:\"check_permissions\";s:25:\"Vérifier les permissions\";s:5:\"close\";s:6:\"Fermer\";}s:14:\"database_stats\";a:13:{s:7:\"section\";s:35:\"Statistiques de la base de données\";s:5:\"title\";s:35:\"Statistiques de la base de données\";s:11:\"serial_keys\";s:15:\"Clés de série\";s:20:\"view_all_serial_keys\";s:21:\"Voir toutes les clés\";s:13:\"view_all_keys\";s:21:\"Voir toutes les clés\";s:8:\"projects\";s:7:\"Projets\";s:17:\"view_all_projects\";s:21:\"Voir tous les projets\";s:6:\"admins\";s:15:\"Administrateurs\";s:15:\"view_all_admins\";s:29:\"Voir tous les administrateurs\";s:11:\"active_keys\";s:13:\"Clés actives\";s:16:\"view_active_keys\";s:22:\"Voir les clés actives\";s:8:\"api_keys\";s:9:\"Clés API\";s:17:\"view_all_api_keys\";s:25:\"Voir toutes les clés API\";}s:4:\"logs\";a:4:{s:7:\"section\";s:13:\"Derniers logs\";s:5:\"title\";s:13:\"Derniers logs\";s:7:\"refresh\";s:11:\"Rafraîchir\";s:10:\"no_entries\";s:32:\"Aucune entrée de log disponible\";}s:6:\"modals\";a:2:{s:17:\"serial_keys_title\";s:15:\"Clés de série\";s:17:\"test_result_title\";s:17:\"Résultat du test\";}s:2:\"js\";a:55:{s:17:\"please_select_key\";s:52:\"Veuillez sélectionner ou saisir une clé de série.\";s:24:\"verification_in_progress\";s:25:\"Vérification en cours...\";s:5:\"valid\";s:6:\"Valide\";s:7:\"invalid\";s:8:\"Invalide\";s:6:\"status\";s:6:\"Statut\";s:10:\"serial_key\";s:14:\"Clé de série\";s:7:\"project\";s:6:\"Projet\";s:15:\"expiration_date\";s:17:\"Date d\'expiration\";s:6:\"domain\";s:7:\"Domaine\";s:10:\"ip_address\";s:10:\"Adresse IP\";s:5:\"token\";s:5:\"Token\";s:13:\"not_specified\";s:14:\"Non spécifié\";s:13:\"not_generated\";s:13:\"Non généré\";s:5:\"error\";s:6:\"Erreur\";s:12:\"server_error\";s:67:\"Une erreur s\'est produite lors de la communication avec le serveur.\";s:19:\"loading_serial_keys\";s:33:\"Chargement des clés de série...\";s:6:\"active\";s:5:\"Actif\";s:7:\"revoked\";s:9:\"Révoqué\";s:9:\"suspended\";s:8:\"Suspendu\";s:7:\"expired\";s:7:\"Expiré\";s:20:\"no_serial_keys_found\";s:31:\"Aucune clé de série trouvée.\";s:25:\"error_loading_serial_keys\";s:46:\"Erreur lors du chargement des clés de série.\";s:16:\"test_in_progress\";s:16:\"Test en cours...\";s:7:\"success\";s:7:\"Succès\";s:7:\"failure\";s:6:\"Échec\";s:11:\"test_result\";s:17:\"Résultat du test\";s:3:\"yes\";s:3:\"Oui\";s:2:\"no\";s:3:\"Non\";s:13:\"not_available\";s:14:\"Non disponible\";s:16:\"loading_projects\";s:25:\"Chargement des projets...\";s:12:\"project_name\";s:13:\"Nom du projet\";s:11:\"serial_keys\";s:15:\"Clés de série\";s:10:\"created_at\";s:17:\"Date de création\";s:17:\"no_projects_found\";s:21:\"Aucun projet trouvé.\";s:22:\"error_loading_projects\";s:38:\"Erreur lors du chargement des projets.\";s:14:\"loading_admins\";s:33:\"Chargement des administrateurs...\";s:4:\"name\";s:3:\"Nom\";s:5:\"email\";s:5:\"Email\";s:10:\"last_login\";s:19:\"Dernière connexion\";s:5:\"never\";s:6:\"Jamais\";s:15:\"no_admins_found\";s:29:\"Aucun administrateur trouvé.\";s:20:\"error_loading_admins\";s:46:\"Erreur lors du chargement des administrateurs.\";s:19:\"loading_active_keys\";s:31:\"Chargement des clés actives...\";s:10:\"expires_at\";s:17:\"Date d\'expiration\";s:3:\"all\";s:4:\"Tous\";s:4:\"none\";s:6:\"Aucune\";s:20:\"no_active_keys_found\";s:28:\"Aucune clé active trouvée.\";s:25:\"error_loading_active_keys\";s:44:\"Erreur lors du chargement des clés actives.\";s:16:\"loading_api_keys\";s:27:\"Chargement des clés API...\";s:3:\"key\";s:4:\"Clé\";s:12:\"last_used_at\";s:21:\"Dernière utilisation\";s:17:\"no_api_keys_found\";s:25:\"Aucune clé API trouvée.\";s:22:\"error_loading_api_keys\";s:40:\"Erreur lors du chargement des clés API.\";s:7:\"message\";s:7:\"Message\";s:7:\"details\";s:8:\"Détails\";}}s:8:\"settings\";a:6:{s:5:\"title\";s:22:\"Paramètres généraux\";s:7:\"general\";a:6:{s:5:\"title\";s:24:\"Informations générales\";s:9:\"site_name\";s:11:\"Nom du site\";s:16:\"site_description\";s:19:\"Description du site\";s:13:\"contact_email\";s:16:\"Email de contact\";s:12:\"contact_name\";s:14:\"Nom de contact\";s:14:\"update_success\";s:50:\"Les paramètres ont été mis à jour avec succès\";}s:8:\"password\";a:5:{s:5:\"title\";s:23:\"Changer le mot de passe\";s:16:\"current_password\";s:19:\"Mot de passe actuel\";s:12:\"new_password\";s:20:\"Nouveau mot de passe\";s:16:\"confirm_password\";s:25:\"Confirmer le mot de passe\";s:13:\"update_button\";s:30:\"Mettre à jour le mot de passe\";}s:7:\"favicon\";a:5:{s:5:\"title\";s:7:\"Favicon\";s:7:\"current\";s:14:\"Favicon actuel\";s:3:\"new\";s:15:\"Nouveau favicon\";s:16:\"accepted_formats\";s:69:\"Formats acceptés : ICO, PNG, JPG, JPEG, SVG. Taille maximale : 2 Mo.\";s:13:\"update_button\";s:25:\"Mettre à jour le favicon\";}s:5:\"theme\";a:3:{s:5:\"title\";s:6:\"Thème\";s:9:\"dark_mode\";s:24:\"Activer le thème sombre\";s:12:\"apply_button\";s:9:\"Appliquer\";}s:10:\"two_factor\";a:50:{s:5:\"title\";s:33:\"Authentification à deux facteurs\";s:11:\"description\";s:100:\"L\'authentification à deux facteurs ajoute une couche de sécurité supplémentaire à votre compte.\";s:7:\"enabled\";s:42:\"Authentification à deux facteurs activée\";s:8:\"disabled\";s:46:\"Authentification à deux facteurs désactivée\";s:6:\"enable\";s:43:\"Activer l\'authentification à deux facteurs\";s:7:\"disable\";s:47:\"Désactiver l\'authentification à deux facteurs\";s:5:\"setup\";a:3:{s:5:\"step1\";s:29:\"Étape 1 : Scannez le code QR\";s:5:\"step2\";s:42:\"Étape 2 : Entrez le code de vérification\";s:5:\"step3\";s:33:\"Étape 3 : Confirmez l\'activation\";}s:7:\"scan_qr\";s:60:\"Scannez le code QR avec votre application d\'authentification\";s:10:\"enter_code\";s:31:\"Entrez le code de vérification\";s:17:\"verification_code\";s:21:\"Code de vérification\";s:14:\"recovery_codes\";s:23:\"Codes de récupération\";s:26:\"recovery_codes_description\";s:170:\"Conservez ces codes de récupération dans un endroit sûr. Ils vous permettront de récupérer l\'accès à votre compte si vous perdez votre appareil d\'authentification.\";s:16:\"regenerate_codes\";s:40:\"Régénérer les codes de récupération\";s:10:\"show_codes\";s:36:\"Afficher les codes de récupération\";s:15:\"confirm_disable\";s:76:\"Êtes-vous sûr de vouloir désactiver l\'authentification à deux facteurs ?\";s:14:\"confirm_enable\";s:72:\"Êtes-vous sûr de vouloir activer l\'authentification à deux facteurs ?\";s:15:\"success_enabled\";s:66:\"L\'authentification à deux facteurs a été activée avec succès.\";s:16:\"success_disabled\";s:70:\"L\'authentification à deux facteurs a été désactivée avec succès.\";s:12:\"error_enable\";s:86:\"Une erreur s\'est produite lors de l\'activation de l\'authentification à deux facteurs.\";s:13:\"error_disable\";s:91:\"Une erreur s\'est produite lors de la désactivation de l\'authentification à deux facteurs.\";s:12:\"invalid_code\";s:38:\"Le code de vérification est invalide.\";s:12:\"backup_codes\";s:16:\"Codes de secours\";s:24:\"backup_codes_description\";s:105:\"Ces codes de secours vous permettront de vous connecter si vous perdez votre appareil d\'authentification.\";s:14:\"download_codes\";s:34:\"Télécharger les codes de secours\";s:11:\"print_codes\";s:29:\"Imprimer les codes de secours\";s:23:\"regenerate_backup_codes\";s:33:\"Régénérer les codes de secours\";s:35:\"regenerate_backup_codes_description\";s:79:\"La régénération des codes de secours invalidera tous les codes précédents.\";s:18:\"confirm_regenerate\";s:62:\"Êtes-vous sûr de vouloir régénérer les codes de secours ?\";s:6:\"status\";a:3:{s:16:\"disabled_message\";s:65:\"L\'authentification à deux facteurs est actuellement désactivée\";s:8:\"disabled\";s:12:\"Désactivée\";s:7:\"enabled\";s:8:\"Activée\";}s:9:\"configure\";s:10:\"Configurer\";s:4:\"test\";s:6:\"Tester\";s:6:\"verify\";s:9:\"Vérifier\";s:12:\"verification\";s:13:\"Vérification\";s:20:\"verification_success\";s:22:\"Vérification réussie\";s:19:\"verification_failed\";s:26:\"Échec de la vérification\";s:14:\"setup_complete\";s:23:\"Configuration terminée\";s:12:\"setup_failed\";s:26:\"Échec de la configuration\";s:7:\"qr_code\";s:7:\"Code QR\";s:11:\"manual_code\";s:11:\"Code manuel\";s:23:\"manual_code_description\";s:112:\"Si vous ne pouvez pas scanner le code QR, entrez ce code manuellement dans votre application d\'authentification.\";s:19:\"app_recommendations\";s:26:\"Applications recommandées\";s:31:\"app_recommendations_description\";s:104:\"Nous recommandons d\'utiliser l\'une des applications suivantes pour l\'authentification à deux facteurs :\";s:20:\"google_authenticator\";s:20:\"Google Authenticator\";s:23:\"microsoft_authenticator\";s:23:\"Microsoft Authenticator\";s:5:\"authy\";s:5:\"Authy\";s:9:\"last_used\";s:21:\"Dernière utilisation\";s:10:\"never_used\";s:15:\"Jamais utilisé\";s:18:\"regenerate_warning\";s:143:\"Attention : La régénération des codes de secours invalidera tous les codes précédents. Assurez-vous de les conserver dans un endroit sûr.\";s:13:\"configuration\";a:2:{s:5:\"title\";s:52:\"Configuration de l\'authentification à deux facteurs\";s:11:\"description\";s:95:\"Configurez votre authentification à deux facteurs pour renforcer la sécurité de votre compte\";}s:9:\"auth_code\";s:23:\"Code d\'authentification\";}}s:17:\"api_documentation\";a:47:{s:5:\"title\";s:17:\"Documentation API\";s:11:\"description\";s:100:\"Cette documentation vous aidera à intégrer l\'API de validation des licences dans vos applications.\";s:17:\"table_of_contents\";s:19:\"Table des matières\";s:12:\"introduction\";s:12:\"Introduction\";s:14:\"authentication\";s:16:\"Authentification\";s:19:\"available_endpoints\";s:21:\"Endpoints disponibles\";s:22:\"integration_approaches\";s:24:\"Approches d\'intégration\";s:20:\"integration_examples\";s:23:\"Exemples d\'intégration\";s:14:\"best_practices\";a:2:{s:5:\"title\";s:16:\"Bonnes pratiques\";s:5:\"items\";a:6:{i:0;s:46:\"Stockez les tokens JWT de manière sécurisée\";i:1;s:58:\"Implémentez un mécanisme de rafraîchissement des tokens\";i:2;s:56:\"Utilisez HTTPS pour toutes les communications avec l\'API\";i:3;s:95:\"Mettez en place un système de gestion des erreurs pour traiter les réponses d\'erreur de l\'API\";i:4;s:71:\"Limitez l\'accès à votre clé API et ne l\'exposez jamais côté client\";i:5;s:68:\"Implémentez un mécanisme de cache pour limiter les appels à l\'API\";}}s:17:\"introduction_text\";s:258:\"L\'API AdminLicence vous permet de valider les licences de vos utilisateurs directement depuis vos applications. Elle offre des endpoints sécurisés pour vérifier la validité des clés de série et obtenir des codes dynamiques pour renforcer la sécurité.\";s:21:\"introduction_security\";s:109:\"Toutes les requêtes doivent être effectuées via HTTPS pour garantir la sécurité des données transmises.\";s:19:\"authentication_text\";s:165:\"Pour utiliser l\'API, vous devez disposer d\'une clé API valide. Vous pouvez créer et gérer vos clés API dans la section Clés API de l\'interface d\'administration.\";s:21:\"authentication_header\";s:124:\"Pour les endpoints qui nécessitent une authentification, vous devez inclure votre clé API dans l\'en-tête de la requête :\";s:21:\"endpoint_check_serial\";s:34:\"Vérification d\'une clé de série\";s:20:\"endpoint_secure_code\";s:43:\"Récupération du code dynamique sécurisé\";s:12:\"endpoint_url\";s:3:\"URL\";s:15:\"endpoint_method\";s:8:\"Méthode\";s:13:\"endpoint_auth\";s:16:\"Authentification\";s:15:\"endpoint_params\";s:11:\"Paramètres\";s:17:\"endpoint_response\";s:8:\"Réponse\";s:17:\"endpoint_required\";s:11:\"obligatoire\";s:17:\"endpoint_optional\";s:9:\"optionnel\";s:16:\"endpoint_success\";s:19:\"En cas de succès :\";s:14:\"endpoint_error\";s:17:\"En cas d\'erreur :\";s:16:\"integration_text\";s:183:\"Plusieurs approches sont disponibles pour intégrer notre système de licence dans vos applications. Choisissez celle qui correspond le mieux à votre environnement de développement.\";s:8:\"approach\";s:8:\"Approche\";s:10:\"advantages\";s:9:\"Avantages\";s:15:\"recommended_use\";s:30:\"Cas d\'utilisation recommandés\";s:12:\"php_standard\";s:12:\"PHP Standard\";s:14:\"php_advantages\";a:3:{i:0;s:36:\"Compatible avec tous les projets PHP\";i:1;s:34:\"Aucune dépendance externe requise\";i:2;s:50:\"Facile à adapter à n\'importe quelle architecture\";}s:12:\"php_use_case\";s:87:\"Applications PHP sans framework spécifique, intégrations dans des CMS, projets legacy\";s:22:\"serial_key_description\";s:30:\"La clé de série à vérifier\";s:18:\"domain_description\";s:46:\"Le domaine sur lequel la licence est utilisée\";s:14:\"ip_description\";s:55:\"L\'adresse IP depuis laquelle la requête est effectuée\";s:15:\"jwt_description\";s:42:\"obtenu lors de la vérification de la clé\";s:17:\"token_description\";s:55:\"Le token JWT obtenu lors de la vérification de la clé\";s:7:\"laravel\";s:7:\"Laravel\";s:10:\"javascript\";s:10:\"JavaScript\";s:7:\"flutter\";s:12:\"Flutter/Dart\";s:18:\"laravel_advantages\";a:3:{i:0;s:45:\"Intégration native avec le framework Laravel\";i:1;s:57:\"Utilisation des fonctionnalités de sécurité de Laravel\";i:2;s:43:\"Support des middlewares et des événements\";}s:16:\"laravel_use_case\";s:62:\"Applications Laravel, projets utilisant l\'écosystème Laravel\";s:21:\"javascript_advantages\";a:3:{i:0;s:45:\"Compatible avec tous les navigateurs modernes\";i:1;s:50:\"Facile à intégrer dans les applications frontend\";i:2;s:36:\"Support des promesses et async/await\";}s:19:\"javascript_use_case\";s:47:\"Applications web frontend, applications Node.js\";s:18:\"flutter_advantages\";a:3:{i:0;s:43:\"Support multiplateforme (iOS, Android, Web)\";i:1;s:18:\"Performance native\";i:2;s:44:\"Intégration facile avec les widgets Flutter\";}s:16:\"flutter_use_case\";s:59:\"Applications mobiles Flutter, applications multiplateformes\";s:8:\"examples\";a:4:{s:5:\"title\";s:23:\"Exemples d\'intégration\";s:12:\"php_standard\";a:2:{s:5:\"title\";s:59:\"Approche 1: PHP Standard (Compatible avec tous les projets)\";s:11:\"description\";s:137:\"Cette approche utilise les fonctions PHP natives et est compatible avec n\'importe quel projet PHP, indépendamment du framework utilisé.\";}s:7:\"laravel\";a:2:{s:5:\"title\";s:46:\"Approche 2: Laravel (Intégration simplifiée)\";s:11:\"description\";s:122:\"Cette approche utilise le client HTTP de Laravel pour une intégration plus simple et élégante dans les projets Laravel.\";}s:7:\"flutter\";a:2:{s:5:\"title\";s:35:\"Flutter/Dart (Applications mobiles)\";s:11:\"description\";s:114:\"Cette approche permet d\'intégrer le système de licence dans des applications mobiles développées avec Flutter.\";}}s:7:\"support\";a:2:{s:5:\"title\";s:7:\"Support\";s:11:\"description\";s:160:\"Si vous rencontrez des problèmes lors de l\'intégration de l\'API ou si vous avez des questions, n\'hésitez pas à contacter notre équipe de support technique.\";}}s:15:\"email_providers\";a:3:{s:10:\"page_title\";s:38:\"Documentation des fournisseurs d\'email\";s:5:\"title\";s:38:\"Documentation des fournisseurs d\'email\";s:13:\"not_available\";s:53:\"La documentation n\'est pas disponible pour le moment.\";}s:21:\"licence_documentation\";a:5:{s:5:\"title\";s:26:\"Documentation des licences\";s:17:\"integration_guide\";a:2:{s:5:\"title\";s:33:\"Guide d\'intégration des licences\";s:11:\"description\";s:118:\"Cette documentation vous guidera à travers le processus d\'intégration du système de licence dans votre application.\";}s:12:\"installation\";a:6:{s:5:\"title\";s:12:\"Installation\";s:11:\"description\";s:75:\"Instructions détaillées pour l\'installation et la configuration initiale.\";s:4:\"tabs\";a:3:{s:3:\"php\";s:10:\"PHP Simple\";s:7:\"laravel\";s:7:\"Laravel\";s:7:\"flutter\";s:7:\"Flutter\";}s:3:\"php\";a:2:{s:5:\"title\";s:21:\"Installation pour PHP\";s:11:\"description\";s:73:\"Pour intégrer le système de licence dans une application PHP standard :\";}s:7:\"laravel\";a:6:{s:5:\"title\";s:25:\"Installation pour Laravel\";s:11:\"description\";s:68:\"Pour intégrer le système de licence dans une application Laravel :\";s:5:\"step1\";s:29:\"1. Créez un Service Provider\";s:5:\"step2\";s:31:\"2. Créez un Service de Licence\";s:5:\"step3\";s:37:\"3. Créez un fichier de configuration\";s:5:\"step4\";s:50:\"4. Ajoutez le Service Provider dans config/app.php\";}s:7:\"flutter\";a:5:{s:5:\"title\";s:25:\"Installation pour Flutter\";s:11:\"description\";s:68:\"Pour intégrer le système de licence dans une application Flutter :\";s:5:\"step1\";s:45:\"1. Ajoutez les dépendances dans pubspec.yaml\";s:5:\"step2\";s:31:\"2. Créez un service de licence\";s:5:\"step3\";s:47:\"3. Exemple d\'utilisation dans votre application\";}}s:12:\"verification\";a:4:{s:5:\"title\";s:26:\"Vérification des licences\";s:11:\"description\";s:74:\"Comment implémenter la vérification des licences dans votre application.\";s:12:\"security_tip\";s:153:\"Pour une sécurité optimale, nous recommandons de vérifier la licence à chaque démarrage de l\'application et périodiquement pendant son utilisation.\";s:14:\"best_practices\";a:6:{s:5:\"title\";s:16:\"Bonnes pratiques\";s:5:\"item1\";s:104:\"Stockez la clé de licence de manière sécurisée (fichier de configuration chiffré, base de données)\";s:5:\"item2\";s:79:\"Mettez en cache le résultat de la vérification pour éviter trop de requêtes\";s:5:\"item3\";s:87:\"Prévoyez un comportement dégradé en cas d\'échec de connexion au serveur de licences\";s:5:\"item4\";s:94:\"Implémentez une vérification périodique pour les applications à longue durée d\'exécution\";s:5:\"item5\";s:73:\"Utilisez HTTPS pour toutes les communications avec le serveur de licences\";}}s:3:\"api\";a:4:{s:5:\"title\";s:27:\"API de gestion des licences\";s:11:\"description\";s:57:\"Documentation complète de l\'API de gestion des licences.\";s:9:\"link_text\";s:66:\"Pour une documentation détaillée de l\'API, veuillez consulter la\";s:10:\"link_title\";s:17:\"Documentation API\";}}s:4:\"menu\";a:6:{s:4:\"home\";s:7:\"Accueil\";s:8:\"features\";s:16:\"Fonctionnalités\";s:7:\"pricing\";s:6:\"Tarifs\";s:3:\"faq\";s:3:\"FAQ\";s:7:\"support\";s:7:\"Support\";s:5:\"login\";s:9:\"Connexion\";}s:6:\"footer\";a:1:{s:6:\"rights\";s:23:\"Tous droits réservés.\";}s:7:\"landing\";a:42:{s:10:\"hero_title\";s:68:\"La solution simple et puissante pour gérer vos licences logicielles\";s:13:\"hero_subtitle\";s:104:\"AdminLicence vous permet de gérer, vérifier et distribuer vos licences facilement via une API moderne.\";s:9:\"cta_login\";s:25:\"Se connecter au Dashboard\";s:13:\"stats_clients\";s:26:\"PME utilisent AdminLicence\";s:13:\"stats_ratings\";s:15:\"Avis 5 étoiles\";s:14:\"stats_projects\";s:30:\"Projets personnalisés livrés\";s:14:\"features_title\";s:28:\"Fonctionnalités principales\";s:16:\"feature_licenses\";s:20:\"Gestion des licences\";s:21:\"feature_licenses_desc\";s:63:\"Créez, gérez et suivez toutes vos licences en quelques clics.\";s:11:\"feature_api\";s:18:\"API REST puissante\";s:16:\"feature_api_desc\";s:87:\"Intégrez facilement la vérification et la gestion des licences dans vos applications.\";s:15:\"feature_updates\";s:25:\"Gestion des mises à jour\";s:20:\"feature_updates_desc\";s:80:\"Distribuez et contrôlez les mises à jour de vos produits en toute simplicité.\";s:9:\"api_title\";s:23:\"API simple à intégrer\";s:10:\"api_verify\";s:24:\"Vérification de licence\";s:10:\"api_update\";s:29:\"Vérification de mise à jour\";s:13:\"pricing_title\";s:35:\"Tarification simple et transparente\";s:13:\"pricing_basic\";s:8:\"Standard\";s:15:\"pricing_basic_1\";s:29:\"Hébergement sur vos serveurs\";s:15:\"pricing_basic_2\";s:22:\"Mises à jour incluses\";s:15:\"pricing_basic_3\";s:30:\"Gestion illimitée de produits\";s:16:\"pricing_extended\";s:8:\"Étendue\";s:18:\"pricing_extended_1\";s:36:\"Toutes les fonctionnalités Standard\";s:18:\"pricing_extended_2\";s:26:\"Support prioritaire 6 mois\";s:18:\"pricing_extended_3\";s:39:\"Vente de produits licenciés autorisée\";s:11:\"pricing_buy\";s:7:\"Acheter\";s:18:\"testimonials_title\";s:19:\"Avis de nos clients\";s:18:\"testimonial_1_name\";s:7:\"Marc B.\";s:18:\"testimonial_1_text\";s:51:\"Produit fiable et support réactif. Je recommande !\";s:18:\"testimonial_2_name\";s:9:\"Sophie L.\";s:18:\"testimonial_2_text\";s:64:\"Intégration facile, interface claire, parfait pour nos besoins.\";s:18:\"testimonial_3_name\";s:12:\"IT Solutions\";s:18:\"testimonial_3_text\";s:54:\"La gestion des licences n\'a jamais été aussi simple.\";s:9:\"faq_title\";s:21:\"Questions fréquentes\";s:6:\"faq_q1\";s:30:\"Comment acheter AdminLicence ?\";s:6:\"faq_a1\";s:50:\"Directement sur notre site ou via nos partenaires.\";s:6:\"faq_q2\";s:35:\"Puis-je gérer plusieurs produits ?\";s:6:\"faq_a2\";s:43:\"Oui, la gestion multi-produits est incluse.\";s:6:\"faq_q3\";s:50:\"L\'API est-elle compatible avec tous les langages ?\";s:6:\"faq_a3\";s:53:\"Oui, l\'API REST fonctionne avec tout langage moderne.\";s:6:\"faq_q4\";s:36:\"Proposez-vous un support technique ?\";s:6:\"faq_a4\";s:35:\"Oui, par email et via le dashboard.\";}s:8:\"features\";a:13:{s:5:\"title\";s:31:\"Fonctionnalités d\'AdminLicence\";s:18:\"license_management\";s:20:\"Gestion des licences\";s:23:\"license_management_desc\";s:70:\"Créez, attribuez, suspendez ou révoquez des licences en temps réel.\";s:3:\"api\";s:8:\"API REST\";s:8:\"api_desc\";s:79:\"Une API documentée pour intégrer la gestion des licences à vos applications.\";s:7:\"updates\";s:25:\"Gestion des mises à jour\";s:12:\"updates_desc\";s:59:\"Déployez et contrôlez les mises à jour de vos logiciels.\";s:8:\"security\";s:19:\"Sécurité avancée\";s:13:\"security_desc\";s:55:\"Chiffrement, restrictions IP/domaine, logs détaillés.\";s:12:\"integrations\";s:13:\"Intégrations\";s:17:\"integrations_desc\";s:47:\"Compatibilité avec PHP, Python, Node, et plus.\";s:7:\"support\";s:15:\"Support dédié\";s:12:\"support_desc\";s:48:\"Assistance par email et documentation complète.\";}s:7:\"pricing\";a:10:{s:5:\"title\";s:20:\"Nos offres et tarifs\";s:5:\"basic\";s:8:\"Standard\";s:7:\"basic_1\";s:29:\"Hébergement sur vos serveurs\";s:7:\"basic_2\";s:22:\"Mises à jour incluses\";s:7:\"basic_3\";s:30:\"Gestion illimitée de produits\";s:8:\"extended\";s:8:\"Étendue\";s:10:\"extended_1\";s:36:\"Toutes les fonctionnalités Standard\";s:10:\"extended_2\";s:26:\"Support prioritaire 6 mois\";s:10:\"extended_3\";s:39:\"Vente de produits licenciés autorisée\";s:3:\"buy\";s:7:\"Acheter\";}s:3:\"faq\";a:9:{s:5:\"title\";s:19:\"Foire aux questions\";s:2:\"q1\";s:30:\"Comment acheter AdminLicence ?\";s:2:\"a1\";s:50:\"Directement sur notre site ou via nos partenaires.\";s:2:\"q2\";s:35:\"Puis-je gérer plusieurs produits ?\";s:2:\"a2\";s:43:\"Oui, la gestion multi-produits est incluse.\";s:2:\"q3\";s:50:\"L\'API est-elle compatible avec tous les langages ?\";s:2:\"a3\";s:53:\"Oui, l\'API REST fonctionne avec tout langage moderne.\";s:2:\"q4\";s:36:\"Proposez-vous un support technique ?\";s:2:\"a4\";s:35:\"Oui, par email et via le dashboard.\";}s:16:\"settings_license\";a:1:{s:7:\"license\";a:52:{s:5:\"title\";s:18:\"Gestion de licence\";s:25:\"no_license_key_configured\";s:62:\"Aucune clé de licence n\'est configurée dans le fichier .env.\";s:22:\"api_verification_error\";s:48:\"Erreur lors de la vérification directe de l\'API\";s:20:\"valid_via_direct_api\";s:30:\"Licence valide via API directe\";s:22:\"invalid_via_direct_api\";s:32:\"Licence invalide via API directe\";s:13:\"status_detail\";s:15:\"Statut: :status\";s:10:\"expired_on\";s:17:\"expirée le :date\";s:15:\"expires_on_date\";s:15:\"expire le :date\";s:13:\"expiry_detail\";s:19:\"Expiration: :expiry\";s:17:\"registered_domain\";s:28:\"Domaine enregistré: :domain\";s:13:\"registered_ip\";s:28:\"Adresse IP enregistrée: :ip\";s:13:\"license_valid\";s:22:\"La licence est valide.\";s:25:\"api_valid_service_invalid\";s:135:\"L\'API indique que la licence est valide, mais le service de licence la considère comme invalide. Problème de configuration potentiel.\";s:32:\"license_invalid_with_api_message\";s:76:\"La licence n\'est pas valide selon l\'API et le service. Message API: :message\";s:22:\"license_details_header\";s:24:\"Détails de la licence :\";s:18:\"verification_error\";s:70:\"Une erreur est survenue lors de la vérification de la licence: :error\";s:10:\"info_title\";s:23:\"Informations de licence\";s:16:\"installation_key\";s:30:\"Clé de licence d\'installation\";s:8:\"copy_key\";s:14:\"Copier la clé\";s:6:\"status\";s:20:\"Statut de la licence\";s:12:\"status_label\";s:6:\"Statut\";s:5:\"valid\";s:6:\"Valide\";s:7:\"invalid\";s:10:\"Non valide\";s:11:\"expiry_date\";s:17:\"Date d\'expiration\";s:17:\"expiry_date_label\";s:17:\"Date d\'expiration\";s:10:\"expires_on\";s:9:\"Expire le\";s:10:\"last_check\";s:23:\"Dernière vérification\";s:5:\"never\";s:6:\"Jamais\";s:9:\"check_now\";s:20:\"Vérifier maintenant\";s:7:\"details\";s:22:\"Détails de la licence\";s:6:\"domain\";s:7:\"Domaine\";s:10:\"ip_address\";s:10:\"Adresse IP\";s:11:\"not_defined\";s:11:\"Non défini\";s:13:\"status_active\";s:5:\"Actif\";s:16:\"status_suspended\";s:8:\"Suspendu\";s:14:\"status_revoked\";s:9:\"Révoqué\";s:10:\"no_details\";s:69:\"Aucune information détaillée disponible pour cette clé de licence.\";s:13:\"configuration\";s:27:\"Configuration de la licence\";s:16:\"key_saved_in_env\";s:71:\"Cette clé sera enregistrée dans le fichier .env de votre application.\";s:14:\"env_not_exists\";s:68:\"Le fichier .env n\'existe pas encore. Il sera créé automatiquement.\";s:13:\"save_settings\";s:27:\"Enregistrer les paramètres\";s:19:\"manual_verification\";s:22:\"Vérification manuelle\";s:24:\"manual_verification_desc\";s:152:\"Vous pouvez forcer une vérification immédiate de la licence d\'installation. Cela mettra à jour le statut de validité et les informations associées.\";s:10:\"debug_info\";s:25:\"Informations de débogage\";s:14:\"detected_value\";s:17:\"Valeur détectée\";s:9:\"not_found\";s:11:\"Non trouvé\";s:9:\"http_code\";s:9:\"Code HTTP\";s:16:\"raw_api_response\";s:18:\"Réponse API brute\";s:11:\"no_response\";s:15:\"Aucune réponse\";s:17:\"unviewable_format\";s:21:\"Format non affichable\";s:19:\"auto_dismiss_alerts\";s:42:\"Auto-dismiss des alertes après 5 secondes\";s:6:\"copied\";s:8:\"Copié !\";}}s:7:\"support\";a:7:{s:5:\"title\";s:18:\"Support et contact\";s:4:\"name\";s:3:\"Nom\";s:5:\"email\";s:5:\"Email\";s:7:\"message\";s:7:\"Message\";s:4:\"send\";s:7:\"Envoyer\";s:12:\"contact_info\";s:23:\"Informations de contact\";s:12:\"contact_desc\";s:67:\"Pour toute question, contactez-nous via ce formulaire ou par email.\";}s:12:\"optimization\";a:53:{s:5:\"title\";s:21:\"Outils d\'optimisation\";s:16:\"operation_result\";s:25:\"Résultat de l\'opération\";s:12:\"example_code\";s:15:\"Exemple de code\";s:9:\"copy_code\";s:51:\"Copiez ce code et utilisez-le dans vos vues Blade :\";s:13:\"logs_cleaning\";s:18:\"Nettoyage des logs\";s:17:\"current_logs_size\";s:22:\"Taille totale des logs\";s:25:\"logs_cleaning_description\";s:127:\"Cette opération va nettoyer les fichiers de logs inutiles. Les logs importants seront archivés, les autres seront supprimés.\";s:8:\"all_logs\";s:13:\"Tous les logs\";s:17:\"installation_logs\";s:19:\"Logs d\'installation\";s:12:\"laravel_logs\";s:12:\"Logs Laravel\";s:14:\"clean_all_logs\";s:22:\"Nettoyer tous les logs\";s:15:\"delete_all_logs\";s:23:\"Supprimer tous les logs\";s:23:\"delete_all_logs_confirm\";s:103:\"Attention : Cette action va supprimer tous les fichiers de logs. Êtes-vous sûr de vouloir continuer ?\";s:23:\"clean_installation_logs\";s:32:\"Nettoyer les logs d\'installation\";s:24:\"delete_installation_logs\";s:33:\"Supprimer les logs d\'installation\";s:32:\"delete_installation_logs_confirm\";s:118:\"Attention : Cette action va supprimer tous les fichiers de logs d\'installation. Êtes-vous sûr de vouloir continuer ?\";s:18:\"clean_laravel_logs\";s:25:\"Nettoyer les logs Laravel\";s:19:\"delete_laravel_logs\";s:26:\"Supprimer les logs Laravel\";s:27:\"delete_laravel_logs_confirm\";s:111:\"Attention : Cette action va supprimer tous les fichiers de logs Laravel. Êtes-vous sûr de vouloir continuer ?\";s:8:\"filename\";s:14:\"Nom du fichier\";s:4:\"size\";s:6:\"Taille\";s:17:\"modification_date\";s:20:\"Date de modification\";s:4:\"view\";s:4:\"Voir\";s:26:\"no_installation_logs_found\";s:43:\"Aucun fichier de log d\'installation trouvé\";s:21:\"no_laravel_logs_found\";s:36:\"Aucun fichier de log Laravel trouvé\";s:18:\"image_optimization\";s:23:\"Optimisation des images\";s:19:\"current_images_size\";s:26:\"Taille actuelle des images\";s:31:\"images_optimization_description\";s:141:\"Cette opération va optimiser les images dans le dossier public/images/ pour réduire leur taille tout en maintenant une qualité acceptable.\";s:7:\"quality\";s:16:\"Qualité (0-100)\";s:16:\"high_compression\";s:20:\"Compression élevée\";s:12:\"high_quality\";s:14:\"Haute qualité\";s:18:\"force_optimization\";s:51:\"Forcer l\'optimisation des images déjà optimisées\";s:15:\"optimize_images\";s:20:\"Optimiser les images\";s:19:\"api_diagnostic_tool\";s:23:\"Outil de diagnostic API\";s:26:\"api_diagnostic_description\";s:109:\"Cet outil vous permet de diagnostiquer et résoudre les problèmes liés à l\'API de validation des licences.\";s:16:\"api_general_info\";s:34:\"Informations générales sur l\'API\";s:19:\"api_serial_key_test\";s:38:\"Test de validation des clés de série\";s:19:\"api_connection_test\";s:34:\"Test de connexion à l\'API externe\";s:17:\"api_database_test\";s:40:\"Test de connexion à la base de données\";s:20:\"api_file_permissions\";s:42:\"Vérification des permissions des fichiers\";s:15:\"api_log_entries\";s:40:\"Affichage des dernières entrées de log\";s:23:\"api_default_credentials\";s:24:\"Identifiants par défaut\";s:19:\"open_api_diagnostic\";s:32:\"Ouvrir l\'outil de diagnostic API\";s:16:\"asset_versioning\";s:21:\"Versioning des assets\";s:28:\"asset_versioning_description\";s:209:\"Le système de versioning des assets permet d\'optimiser la mise en cache des fichiers CSS, JavaScript et images. Il ajoute automatiquement un paramètre de version basé sur la date de modification du fichier.\";s:10:\"asset_type\";s:12:\"Type d\'asset\";s:8:\"css_file\";s:11:\"Fichier CSS\";s:7:\"js_file\";s:18:\"Fichier JavaScript\";s:5:\"image\";s:5:\"Image\";s:10:\"image_path\";s:17:\"Chemin de l\'image\";s:16:\"generate_example\";s:20:\"Générer un exemple\";s:10:\"how_to_use\";s:16:\"Comment utiliser\";s:28:\"blade_directives_description\";s:60:\"Dans vos fichiers Blade, utilisez les directives suivantes :\";}s:3:\"cgv\";a:12:{s:5:\"title\";s:31:\"Conditions Générales de Vente\";s:7:\"1_title\";s:5:\"Objet\";s:6:\"1_text\";s:70:\"Les présentes conditions régissent la vente du service AdminLicence.\";s:7:\"2_title\";s:16:\"Prix et paiement\";s:6:\"2_text\";s:76:\"Les prix sont indiqués en euros et le paiement est exigible à la commande.\";s:7:\"3_title\";s:21:\"Obligations du client\";s:6:\"3_text\";s:100:\"Le client s\'engage à fournir des informations exactes et à respecter les conditions d\'utilisation.\";s:3:\"3_1\";s:32:\"Fournir des informations exactes\";s:3:\"3_2\";s:38:\"Respecter les conditions d\'utilisation\";s:3:\"3_3\";s:33:\"Ne pas revendre sans autorisation\";s:7:\"4_title\";s:24:\"Support et réclamations\";s:6:\"4_text\";s:49:\"Pour toute réclamation, contactez notre support.\";}s:7:\"privacy\";a:11:{s:5:\"title\";s:29:\"Politique de confidentialité\";s:7:\"1_title\";s:21:\"Collecte des données\";s:6:\"1_text\";s:80:\"Nous collectons uniquement les données nécessaires à la gestion des licences.\";s:7:\"2_title\";s:24:\"Utilisation des données\";s:6:\"2_text\";s:51:\"Vos données ne sont jamais revendues à des tiers.\";s:7:\"3_title\";s:10:\"Vos droits\";s:3:\"3_1\";s:22:\"Accès à vos données\";s:3:\"3_2\";s:13:\"Rectification\";s:3:\"3_3\";s:23:\"Suppression sur demande\";s:7:\"4_title\";s:7:\"Contact\";s:6:\"4_text\";s:68:\"Pour toute question, contactez notre DPO à privacy@adminlicence.fr.\";}s:6:\"admins\";a:14:{s:5:\"title\";s:27:\"Gestion des administrateurs\";s:14:\"administrators\";s:15:\"Administrateurs\";s:4:\"list\";s:25:\"Liste des administrateurs\";s:9:\"new_admin\";s:21:\"Nouvel administrateur\";s:2:\"id\";s:2:\"ID\";s:4:\"role\";s:5:\"Rôle\";s:13:\"creation_date\";s:17:\"Date de création\";s:11:\"super_admin\";s:11:\"Super Admin\";s:5:\"admin\";s:5:\"Admin\";s:19:\"delete_confirmation\";s:27:\"Confirmation de suppression\";s:22:\"delete_confirm_message\";s:78:\"Êtes-vous sûr de vouloir supprimer l\'administrateur <strong>:name</strong> ?\";s:6:\"create\";a:8:{s:5:\"title\";s:24:\"Créer un administrateur\";s:10:\"form_title\";s:23:\"Formulaire de création\";s:10:\"name_label\";s:3:\"Nom\";s:11:\"email_label\";s:5:\"Email\";s:14:\"password_label\";s:12:\"Mot de passe\";s:27:\"password_confirmation_label\";s:28:\"Confirmation du mot de passe\";s:10:\"role_label\";s:5:\"Rôle\";s:13:\"submit_button\";s:23:\"Créer l\'administrateur\";}s:4:\"edit\";a:8:{s:5:\"title\";s:25:\"Modifier l\'administrateur\";s:10:\"form_title\";s:26:\"Formulaire de modification\";s:10:\"name_label\";s:3:\"Nom\";s:11:\"email_label\";s:5:\"Email\";s:14:\"password_label\";s:47:\"Mot de passe (laisser vide pour ne pas changer)\";s:27:\"password_confirmation_label\";s:28:\"Confirmation du mot de passe\";s:10:\"role_label\";s:5:\"Rôle\";s:13:\"submit_button\";s:31:\"Mettre à jour l\'administrateur\";}s:8:\"messages\";a:4:{s:7:\"created\";s:35:\"Administrateur créé avec succès.\";s:7:\"updated\";s:40:\"Administrateur mis à jour avec succès.\";s:7:\"deleted\";s:38:\"Administrateur supprimé avec succès.\";s:18:\"cannot_delete_self\";s:49:\"Vous ne pouvez pas supprimer votre propre compte.\";}}s:11:\"admin_login\";a:11:{s:5:\"title\";s:24:\"Connexion Administrateur\";s:7:\"welcome\";s:13:\"Bienvenue sur\";s:8:\"app_name\";s:12:\"AdminLicence\";s:8:\"subtitle\";s:55:\"Gérez vos licences et vos projets en toute simplicité\";s:5:\"login\";s:9:\"Connexion\";s:5:\"email\";s:14:\"Adresse e-mail\";s:8:\"password\";s:12:\"Mot de passe\";s:11:\"remember_me\";s:18:\"Se souvenir de moi\";s:12:\"login_button\";s:12:\"Se connecter\";s:15:\"forgot_password\";s:22:\"Mot de passe oublié ?\";s:8:\"features\";a:3:{s:17:\"secure_management\";s:32:\"Gestion sécurisée des licences\";s:17:\"tracking_analysis\";s:33:\"Suivi et analyse des utilisations\";s:8:\"and_more\";s:22:\"Et bien plus encore...\";}}s:19:\"email_documentation\";a:13:{s:10:\"page_title\";s:38:\"Documentation des fournisseurs d\'email\";s:5:\"title\";s:38:\"Documentation des fournisseurs d\'email\";s:13:\"not_available\";s:53:\"La documentation n\'est pas disponible pour le moment.\";s:11:\"description\";s:120:\"Ce document explique comment configurer et utiliser les différents fournisseurs d\'email disponibles dans l\'application.\";s:17:\"table_of_contents\";s:19:\"Table des matières\";s:12:\"introduction\";a:2:{s:5:\"title\";s:12:\"Introduction\";s:11:\"description\";s:223:\"L\'application prend en charge plusieurs fournisseurs d\'email pour l\'envoi de notifications, d\'alertes et de communications aux utilisateurs. Chaque fournisseur a ses propres avantages, limites et exigences de configuration.\";}s:4:\"smtp\";a:9:{s:5:\"title\";s:4:\"SMTP\";s:11:\"description\";s:193:\"SMTP (Simple Mail Transfer Protocol) est la méthode standard pour envoyer des emails via Internet. C\'est une solution fiable et universelle qui fonctionne avec la plupart des services d\'email.\";s:13:\"configuration\";s:21:\"Configuration requise\";s:12:\"config_items\";a:7:{i:0;s:51:\"Hôte SMTP (ex: smtp.gmail.com, smtp.office365.com)\";i:1;s:48:\"Port (généralement 587 pour TLS, 465 pour SSL)\";i:2;s:47:\"Nom d\'utilisateur (souvent votre adresse email)\";i:3;s:12:\"Mot de passe\";i:4;s:33:\"Méthode de chiffrement (TLS/SSL)\";i:5;s:21:\"Adresse d\'expéditeur\";i:6;s:17:\"Nom d\'expéditeur\";}s:10:\"advantages\";s:9:\"Avantages\";s:15:\"advantages_list\";a:3:{i:0;s:49:\"Compatible avec presque tous les services d\'email\";i:1;s:40:\"Contrôle total sur le processus d\'envoi\";i:2;s:37:\"Pas de dépendance à des API tierces\";}s:13:\"disadvantages\";s:14:\"Inconvénients\";s:18:\"disadvantages_list\";a:3:{i:0;s:30:\"Configuration parfois complexe\";i:1;s:72:\"Peut nécessiter des ajustements de sécurité sur certains fournisseurs\";i:2;s:41:\"Limites d\'envoi selon le fournisseur SMTP\";}s:7:\"example\";s:24:\"Exemple de configuration\";}s:7:\"phpmail\";a:9:{s:5:\"title\";s:7:\"PHPMail\";s:11:\"description\";s:167:\"PHPMail utilise la bibliothèque PHPMailer pour envoyer des emails. C\'est une solution robuste qui offre plus de fonctionnalités que la fonction mail() native de PHP.\";s:13:\"configuration\";s:21:\"Configuration requise\";s:12:\"config_items\";a:1:{i:0;s:73:\"Mêmes paramètres que SMTP (car PHPMailer utilise SMTP en arrière-plan)\";}s:10:\"advantages\";s:9:\"Avantages\";s:15:\"advantages_list\";a:3:{i:0;s:36:\"Gestion avancée des pièces jointes\";i:1;s:19:\"Support multilingue\";i:2;s:46:\"Meilleure gestion des erreurs que mail() natif\";}s:13:\"disadvantages\";s:14:\"Inconvénients\";s:18:\"disadvantages_list\";a:1:{i:0;s:18:\"Similaires à SMTP\";}s:7:\"example\";s:24:\"Exemple de configuration\";}s:7:\"mailgun\";a:9:{s:5:\"title\";s:7:\"Mailgun\";s:11:\"description\";s:169:\"Mailgun est un service d\'API d\'email conçu pour les développeurs. Il offre une haute délivrabilité et des fonctionnalités avancées pour les emails transactionnels.\";s:13:\"configuration\";s:21:\"Configuration requise\";s:12:\"config_items\";a:4:{i:0;s:16:\"Clé API Mailgun\";i:1;s:29:\"Domaine vérifié sur Mailgun\";i:2;s:21:\"Adresse d\'expéditeur\";i:3;s:17:\"Nom d\'expéditeur\";}s:10:\"advantages\";s:9:\"Avantages\";s:15:\"advantages_list\";a:4:{i:0;s:21:\"Haute délivrabilité\";i:1;s:36:\"Suivi détaillé (ouvertures, clics)\";i:2;s:30:\"API simple et bien documentée\";i:3;s:55:\"Quota généreux en version gratuite (1000 emails/mois)\";}s:13:\"disadvantages\";s:14:\"Inconvénients\";s:18:\"disadvantages_list\";a:2:{i:0;s:39:\"Nécessite une vérification de domaine\";i:1;s:32:\"Payant au-delà du quota gratuit\";}s:7:\"example\";s:24:\"Exemple de configuration\";}s:9:\"mailchimp\";a:9:{s:5:\"title\";s:9:\"Mailchimp\";s:11:\"description\";s:191:\"Mailchimp Transactional (anciennement Mandrill) est un service d\'envoi d\'emails transactionnels proposé par Mailchimp, particulièrement adapté pour les emails marketing et les newsletters.\";s:13:\"configuration\";s:21:\"Configuration requise\";s:12:\"config_items\";a:3:{i:0;s:32:\"Clé API Mailchimp Transactional\";i:1;s:32:\"Adresse d\'expéditeur vérifiée\";i:2;s:17:\"Nom d\'expéditeur\";}s:10:\"advantages\";s:9:\"Avantages\";s:15:\"advantages_list\";a:4:{i:0;s:26:\"Excellente délivrabilité\";i:1;s:37:\"Outils avancés de suivi et d\'analyse\";i:2;s:31:\"Modèles d\'emails sophistiqués\";i:3;s:42:\"Intégration avec l\'écosystème Mailchimp\";}s:13:\"disadvantages\";s:14:\"Inconvénients\";s:18:\"disadvantages_list\";a:2:{i:0;s:14:\"Service payant\";i:1;s:36:\"Configuration initiale plus complexe\";}s:7:\"example\";s:24:\"Exemple de configuration\";}s:9:\"rapidmail\";a:9:{s:5:\"title\";s:9:\"Rapidmail\";s:11:\"description\";s:234:\"Rapidmail est un service d\'email marketing allemand qui respecte strictement le RGPD. Il est particulièrement adapté pour les entreprises européennes soucieuses de la conformité aux réglementations sur la protection des données.\";s:13:\"configuration\";s:21:\"Configuration requise\";s:12:\"config_items\";a:3:{i:0;s:18:\"Clé API Rapidmail\";i:1;s:32:\"Adresse d\'expéditeur vérifiée\";i:2;s:17:\"Nom d\'expéditeur\";}s:10:\"advantages\";s:9:\"Avantages\";s:15:\"advantages_list\";a:4:{i:0;s:16:\"Conformité RGPD\";i:1;s:25:\"Serveurs basés en Europe\";i:2;s:22:\"Interface en français\";i:3;s:21:\"Bonne délivrabilité\";}s:13:\"disadvantages\";s:14:\"Inconvénients\";s:18:\"disadvantages_list\";a:2:{i:0;s:33:\"Moins connu que d\'autres services\";i:1;s:28:\"Documentation moins étendue\";}s:7:\"example\";s:24:\"Exemple de configuration\";}s:10:\"comparison\";a:20:{s:5:\"title\";s:28:\"Comparaison des fournisseurs\";s:14:\"deliverability\";s:15:\"Délivrabilité\";s:5:\"price\";s:4:\"Prix\";s:13:\"ease_of_setup\";s:26:\"Facilité de configuration\";s:17:\"advanced_features\";s:26:\"Fonctionnalités avancées\";s:15:\"gdpr_compliance\";s:16:\"Conformité RGPD\";s:8:\"variable\";s:8:\"Variable\";s:4:\"free\";s:7:\"Gratuit\";s:8:\"moderate\";s:9:\"Modérée\";s:7:\"limited\";s:9:\"Limitées\";s:7:\"depends\";s:18:\"Dépend du serveur\";s:4:\"high\";s:8:\"Élevée\";s:8:\"freemium\";s:8:\"Freemium\";s:4:\"easy\";s:6:\"Facile\";s:8:\"numerous\";s:10:\"Nombreuses\";s:4:\"good\";s:5:\"Bonne\";s:9:\"very_high\";s:14:\"Très élevée\";s:4:\"paid\";s:6:\"Payant\";s:13:\"very_numerous\";s:16:\"Très nombreuses\";s:9:\"excellent\";s:10:\"Excellente\";}s:15:\"troubleshooting\";a:7:{s:5:\"title\";s:10:\"Dépannage\";s:15:\"common_problems\";s:19:\"Problèmes courants\";s:15:\"emails_not_sent\";s:31:\"Les emails ne sont pas envoyés\";s:20:\"emails_not_sent_tips\";a:4:{i:0;s:43:\"Vérifiez les informations d\'identification\";i:1;s:59:\"Assurez-vous que le fournisseur est correctement configuré\";i:2;s:28:\"Vérifiez les quotas d\'envoi\";i:3;s:27:\"Consultez les logs d\'erreur\";}s:14:\"emails_as_spam\";s:24:\"Emails reçus comme spam\";s:19:\"emails_as_spam_tips\";a:4:{i:0;s:62:\"Vérifiez la configuration SPF, DKIM et DMARC de votre domaine\";i:1;s:45:\"Utilisez une adresse d\'expéditeur vérifiée\";i:2;s:66:\"Évitez les mots déclencheurs de spam dans le sujet et le contenu\";i:3;s:54:\"Assurez-vous que votre domaine a une bonne réputation\";}s:20:\"configuration_issues\";s:27:\"Problèmes de configuration\";}}s:21:\"translation.corrected\";a:2:{s:6:\"common\";a:46:{s:3:\"add\";s:7:\"Ajouter\";s:9:\"dashboard\";s:15:\"Tableau de bord\";s:8:\"projects\";s:7:\"Projets\";s:11:\"serial_keys\";s:15:\"Clés de série\";s:8:\"api_keys\";s:9:\"Clés API\";s:17:\"api_documentation\";s:17:\"Documentation API\";s:5:\"email\";s:5:\"Email\";s:6:\"logout\";s:12:\"Déconnexion\";s:7:\"version\";s:7:\"Version\";s:4:\"save\";s:11:\"Enregistrer\";s:6:\"cancel\";s:7:\"Annuler\";s:6:\"delete\";s:9:\"Supprimer\";s:4:\"edit\";s:8:\"Modifier\";s:6:\"create\";s:6:\"Créer\";s:4:\"back\";s:22:\"Retour aux paramètres\";s:7:\"actions\";s:7:\"Actions\";s:6:\"status\";s:6:\"Statut\";s:4:\"name\";s:3:\"Nom\";s:11:\"description\";s:11:\"Description\";s:10:\"created_at\";s:9:\"Créé le\";s:10:\"updated_at\";s:14:\"Mis à jour le\";s:12:\"suspended_at\";s:11:\"Suspendu le\";s:10:\"revoked_at\";s:12:\"Révoqué le\";s:4:\"used\";s:8:\"Utilisé\";s:3:\"yes\";s:3:\"Oui\";s:2:\"no\";s:3:\"Non\";s:6:\"domain\";s:7:\"Domaine\";s:2:\"ip\";s:10:\"Adresse IP\";s:4:\"date\";s:4:\"Date\";s:5:\"token\";s:5:\"Token\";s:6:\"submit\";s:9:\"Soumettre\";s:4:\"none\";s:5:\"Aucun\";s:3:\"all\";s:4:\"Tous\";s:4:\"type\";s:4:\"Type\";s:5:\"value\";s:6:\"Valeur\";s:6:\"active\";s:5:\"Actif\";s:8:\"inactive\";s:7:\"Inactif\";s:9:\"suspended\";s:8:\"Suspendu\";s:7:\"revoked\";s:9:\"Révoqué\";s:7:\"support\";s:7:\"Support\";s:8:\"settings\";s:11:\"Paramètres\";s:7:\"license\";s:7:\"Licence\";s:4:\"copy\";s:6:\"Copier\";s:6:\"copied\";s:8:\"Copié !\";s:6:\"search\";s:10:\"Rechercher\";s:6:\"export\";s:8:\"Exporter\";}s:17:\"api_documentation\";a:10:{s:5:\"title\";s:17:\"Documentation API\";s:11:\"description\";s:91:\"Cette documentation vous guidera à travers l\'intégration de l\'API dans votre application.\";s:17:\"table_of_contents\";s:19:\"Table des matières\";s:12:\"introduction\";s:12:\"Introduction\";s:14:\"authentication\";s:16:\"Authentification\";s:19:\"available_endpoints\";s:21:\"Endpoints disponibles\";s:22:\"integration_approaches\";s:24:\"Approches d\'intégration\";s:20:\"integration_examples\";s:23:\"Exemples d\'intégration\";s:14:\"best_practices\";s:20:\"Meilleures pratiques\";s:7:\"support\";a:2:{s:5:\"title\";s:7:\"Support\";s:11:\"description\";s:160:\"Si vous rencontrez des problèmes lors de l\'intégration de l\'API ou si vous avez des questions, n\'hésitez pas à contacter notre équipe de support technique.\";}}}s:17:\"translation.fixed\";a:2:{s:6:\"common\";a:19:{s:3:\"add\";s:7:\"Ajouter\";s:9:\"dashboard\";s:15:\"Tableau de bord\";s:8:\"projects\";s:7:\"Projets\";s:11:\"serial_keys\";s:15:\"Clés de série\";s:8:\"api_keys\";s:9:\"Clés API\";s:17:\"api_documentation\";s:17:\"Documentation API\";s:5:\"email\";s:5:\"Email\";s:6:\"logout\";s:12:\"Déconnexion\";s:7:\"version\";s:7:\"Version\";s:4:\"save\";s:11:\"Enregistrer\";s:6:\"cancel\";s:7:\"Annuler\";s:6:\"delete\";s:9:\"Supprimer\";s:4:\"edit\";s:8:\"Modifier\";s:6:\"create\";s:6:\"Créer\";s:4:\"back\";s:22:\"Retour aux paramètres\";s:7:\"actions\";s:7:\"Actions\";s:6:\"status\";s:6:\"Statut\";s:4:\"name\";s:3:\"Nom\";s:11:\"description\";s:11:\"Description\";}s:17:\"api_documentation\";a:10:{s:5:\"title\";s:17:\"Documentation API\";s:11:\"description\";s:91:\"Cette documentation vous guidera à travers l\'intégration de l\'API dans votre application.\";s:17:\"table_of_contents\";s:19:\"Table des matières\";s:12:\"introduction\";s:12:\"Introduction\";s:14:\"authentication\";s:16:\"Authentification\";s:19:\"available_endpoints\";s:21:\"Endpoints disponibles\";s:22:\"integration_approaches\";s:24:\"Approches d\'intégration\";s:20:\"integration_examples\";s:23:\"Exemples d\'intégration\";s:14:\"best_practices\";s:20:\"Meilleures pratiques\";s:7:\"support\";a:2:{s:5:\"title\";s:7:\"Support\";s:11:\"description\";s:160:\"Si vous rencontrez des problèmes lors de l\'intégration de l\'API ou si vous avez des questions, n\'hésitez pas à contacter notre équipe de support technique.\";}}}s:16:\"translation.full\";a:9:{s:6:\"common\";a:62:{s:3:\"add\";s:7:\"Ajouter\";s:9:\"dashboard\";s:15:\"Tableau de bord\";s:8:\"projects\";s:7:\"Projets\";s:11:\"serial_keys\";s:15:\"Clés de série\";s:8:\"api_keys\";s:9:\"Clés API\";s:17:\"api_documentation\";s:17:\"Documentation API\";s:5:\"email\";s:5:\"Email\";s:6:\"logout\";s:12:\"Déconnexion\";s:7:\"version\";s:7:\"Version\";s:4:\"save\";s:11:\"Enregistrer\";s:6:\"cancel\";s:7:\"Annuler\";s:6:\"delete\";s:9:\"Supprimer\";s:4:\"edit\";s:8:\"Modifier\";s:6:\"create\";s:6:\"Créer\";s:4:\"back\";s:22:\"Retour aux paramètres\";s:7:\"actions\";s:7:\"Actions\";s:6:\"status\";s:6:\"Statut\";s:4:\"name\";s:3:\"Nom\";s:11:\"description\";s:11:\"Description\";s:10:\"created_at\";s:9:\"Créé le\";s:10:\"updated_at\";s:14:\"Mis à jour le\";s:12:\"suspended_at\";s:11:\"Suspendu le\";s:10:\"revoked_at\";s:12:\"Révoqué le\";s:4:\"used\";s:8:\"Utilisé\";s:3:\"yes\";s:3:\"Oui\";s:2:\"no\";s:3:\"Non\";s:6:\"domain\";s:7:\"Domaine\";s:2:\"ip\";s:10:\"Adresse IP\";s:4:\"date\";s:4:\"Date\";s:5:\"token\";s:5:\"Token\";s:6:\"submit\";s:9:\"Soumettre\";s:4:\"none\";s:5:\"Aucun\";s:3:\"all\";s:4:\"Tous\";s:4:\"type\";s:4:\"Type\";s:5:\"value\";s:6:\"Valeur\";s:6:\"active\";s:5:\"Actif\";s:8:\"inactive\";s:7:\"Inactif\";s:9:\"suspended\";s:8:\"Suspendu\";s:7:\"revoked\";s:9:\"Révoqué\";s:7:\"support\";s:7:\"Support\";s:8:\"settings\";s:11:\"Paramètres\";s:7:\"license\";s:7:\"Licence\";s:4:\"copy\";s:6:\"Copier\";s:6:\"copied\";s:8:\"Copié !\";s:6:\"search\";s:10:\"Rechercher\";s:6:\"export\";s:8:\"Exporter\";s:8:\"language\";s:6:\"Langue\";s:6:\"enable\";s:7:\"Activer\";s:7:\"disable\";s:11:\"Désactiver\";s:7:\"enabled\";s:7:\"Activé\";s:8:\"disabled\";s:11:\"Désactivé\";s:12:\"save_changes\";s:29:\"Enregistrer les modifications\";s:6:\"select\";s:13:\"Sélectionner\";s:7:\"success\";s:7:\"Succès\";s:5:\"error\";s:6:\"Erreur\";s:7:\"warning\";s:13:\"Avertissement\";s:4:\"info\";s:11:\"Information\";s:7:\"loading\";s:13:\"Chargement...\";s:5:\"close\";s:6:\"Fermer\";s:7:\"confirm\";s:9:\"Confirmer\";s:4:\"show\";s:8:\"Afficher\";s:4:\"hide\";s:7:\"Masquer\";}s:9:\"dashboard\";a:13:{s:5:\"title\";s:15:\"Tableau de bord\";s:8:\"overview\";s:14:\"Vue d\'ensemble\";s:14:\"total_projects\";s:14:\"Projets totaux\";s:14:\"total_licenses\";s:16:\"Licences totales\";s:15:\"active_licenses\";s:16:\"Licences actives\";s:17:\"inactive_licenses\";s:18:\"Licences inactives\";s:15:\"recent_activity\";s:18:\"Activité récente\";s:13:\"activity_type\";s:16:\"Type d\'activité\";s:13:\"license_usage\";s:24:\"Utilisation des licences\";s:12:\"more_details\";s:16:\"Plus de détails\";s:5:\"stats\";s:12:\"Statistiques\";s:7:\"welcome\";s:26:\"Bienvenue sur AdminLicence\";s:15:\"welcome_message\";s:41:\"Gérez vos licences et projets facilement\";}s:7:\"project\";a:12:{s:5:\"title\";s:7:\"Projets\";s:12:\"all_projects\";s:16:\"Tous les projets\";s:14:\"create_project\";s:16:\"Créer un projet\";s:12:\"edit_project\";s:18:\"Modifier le projet\";s:14:\"delete_project\";s:19:\"Supprimer le projet\";s:19:\"delete_confirmation\";s:110:\"Êtes-vous sûr de vouloir supprimer ce projet ? Toutes les licences associées seront également supprimées.\";s:15:\"project_details\";s:18:\"Détails du projet\";s:15:\"project_created\";s:26:\"Projet créé avec succès\";s:15:\"project_updated\";s:31:\"Projet mis à jour avec succès\";s:15:\"project_deleted\";s:29:\"Projet supprimé avec succès\";s:8:\"licenses\";s:8:\"Licences\";s:13:\"view_licenses\";s:17:\"Voir les licences\";}s:10:\"serial_key\";a:23:{s:5:\"title\";s:15:\"Clés de série\";s:8:\"all_keys\";s:16:\"Toutes les clés\";s:10:\"create_key\";s:15:\"Créer une clé\";s:8:\"edit_key\";s:16:\"Modifier la clé\";s:10:\"delete_key\";s:17:\"Supprimer la clé\";s:10:\"revoke_key\";s:17:\"Révoquer la clé\";s:11:\"suspend_key\";s:17:\"Suspendre la clé\";s:14:\"reactivate_key\";s:18:\"Réactiver la clé\";s:11:\"key_details\";s:19:\"Détails de la clé\";s:11:\"key_created\";s:25:\"Clé créée avec succès\";s:11:\"key_updated\";s:30:\"Clé mise à jour avec succès\";s:11:\"key_deleted\";s:28:\"Clé supprimée avec succès\";s:11:\"key_revoked\";s:28:\"Clé révoquée avec succès\";s:13:\"key_suspended\";s:27:\"Clé suspendue avec succès\";s:15:\"key_reactivated\";s:29:\"Clé réactivée avec succès\";s:14:\"select_project\";s:23:\"Sélectionner un projet\";s:15:\"expiration_date\";s:17:\"Date d\'expiration\";s:11:\"usage_limit\";s:20:\"Limite d\'utilisation\";s:9:\"unlimited\";s:9:\"Illimité\";s:13:\"usage_history\";s:24:\"Historique d\'utilisation\";s:12:\"generate_key\";s:18:\"Générer une clé\";s:13:\"serial_number\";s:17:\"Numéro de série\";s:11:\"bulk_create\";s:18:\"Création en masse\";}s:7:\"api_key\";a:16:{s:5:\"title\";s:9:\"Clés API\";s:8:\"all_keys\";s:16:\"Toutes les clés\";s:10:\"create_key\";s:19:\"Créer une clé API\";s:8:\"edit_key\";s:20:\"Modifier la clé API\";s:10:\"delete_key\";s:21:\"Supprimer la clé API\";s:10:\"revoke_key\";s:21:\"Révoquer la clé API\";s:11:\"key_details\";s:23:\"Détails de la clé API\";s:11:\"key_created\";s:29:\"Clé API créée avec succès\";s:11:\"key_updated\";s:34:\"Clé API mise à jour avec succès\";s:11:\"key_deleted\";s:32:\"Clé API supprimée avec succès\";s:11:\"key_revoked\";s:32:\"Clé API révoquée avec succès\";s:11:\"permissions\";s:11:\"Permissions\";s:9:\"last_used\";s:21:\"Dernière utilisation\";s:10:\"never_used\";s:16:\"Jamais utilisée\";s:10:\"expires_at\";s:9:\"Expire le\";s:13:\"no_expiration\";s:16:\"Pas d\'expiration\";}s:3:\"api\";a:18:{s:5:\"title\";s:17:\"Documentation API\";s:11:\"description\";s:73:\"Utilisez ces endpoints pour intégrer AdminLicence dans votre application\";s:8:\"endpoint\";s:8:\"Endpoint\";s:6:\"method\";s:8:\"Méthode\";s:10:\"parameters\";s:11:\"Paramètres\";s:8:\"response\";s:8:\"Réponse\";s:14:\"authentication\";s:16:\"Authentification\";s:26:\"authentication_description\";s:91:\"Toutes les requêtes à l\'API doivent inclure votre clé API dans l\'en-tête d\'autorisation\";s:13:\"rate_limiting\";s:18:\"Limitation de taux\";s:25:\"rate_limiting_description\";s:63:\"Les requêtes API sont limitées à 100 par minute par clé API\";s:6:\"errors\";s:7:\"Erreurs\";s:18:\"errors_description\";s:97:\"L\'API renvoie des codes d\'état HTTP standard pour indiquer le succès ou l\'échec d\'une requête\";s:9:\"endpoints\";s:9:\"Endpoints\";s:7:\"request\";s:8:\"Requête\";s:14:\"best_practices\";s:20:\"Meilleures pratiques\";s:13:\"licence_title\";s:27:\"API de gestion des licences\";s:9:\"link_text\";s:57:\"Pour une documentation détaillée de l\'API, consultez la\";s:10:\"link_title\";s:17:\"Documentation API\";}s:17:\"api_documentation\";a:17:{s:5:\"title\";s:17:\"Documentation API\";s:11:\"description\";s:91:\"Cette documentation vous guidera à travers l\'intégration de l\'API dans votre application.\";s:17:\"table_of_contents\";s:19:\"Table des matières\";s:12:\"introduction\";s:12:\"Introduction\";s:14:\"authentication\";s:16:\"Authentification\";s:19:\"available_endpoints\";s:21:\"Endpoints disponibles\";s:22:\"integration_approaches\";s:24:\"Approches d\'intégration\";s:20:\"integration_examples\";s:23:\"Exemples d\'intégration\";s:14:\"best_practices\";s:20:\"Meilleures pratiques\";s:7:\"support\";a:2:{s:5:\"title\";s:7:\"Support\";s:11:\"description\";s:160:\"Si vous rencontrez des problèmes lors de l\'intégration de l\'API ou si vous avez des questions, n\'hésitez pas à contacter notre équipe de support technique.\";}s:20:\"best_practices_items\";a:5:{i:0;s:46:\"Stockez les tokens JWT de manière sécurisée\";i:1;s:58:\"Implémentez un mécanisme de rafraîchissement des tokens\";i:2;s:56:\"Utilisez HTTPS pour toutes les communications avec l\'API\";i:3;s:62:\"Mettez en cache les réponses pour améliorer les performances\";i:4;s:49:\"Gérez correctement les erreurs et les tentatives\";}s:17:\"introduction_text\";s:247:\"L\'API AdminLicence vous permet de valider les licences de vos utilisateurs directement depuis vos applications. Elle fournit des endpoints sécurisés pour vérifier les clés de série et obtenir des codes dynamiques pour renforcer la sécurité.\";s:21:\"introduction_security\";s:108:\"Toutes les requêtes doivent être effectuées via HTTPS pour assurer la sécurité des données transmises.\";s:19:\"authentication_text\";s:165:\"Pour utiliser l\'API, vous devez disposer d\'une clé API valide. Vous pouvez créer et gérer vos clés API dans la section Clés API de l\'interface d\'administration.\";s:21:\"authentication_header\";s:124:\"Pour les endpoints qui nécessitent une authentification, vous devez inclure votre clé API dans l\'en-tête de la requête :\";s:21:\"endpoint_check_serial\";s:31:\"Vérification de clé de série\";s:20:\"endpoint_secure_code\";s:38:\"Obtention de code dynamique sécurisé\";}s:8:\"settings\";a:19:{s:5:\"title\";s:11:\"Paramètres\";s:7:\"general\";s:9:\"Général\";s:10:\"appearance\";s:9:\"Apparence\";s:13:\"notifications\";s:13:\"Notifications\";s:8:\"security\";s:10:\"Sécurité\";s:8:\"language\";s:6:\"Langue\";s:8:\"timezone\";s:14:\"Fuseau horaire\";s:11:\"date_format\";s:14:\"Format de date\";s:5:\"theme\";s:6:\"Thème\";s:5:\"light\";s:5:\"Clair\";s:4:\"dark\";s:6:\"Sombre\";s:4:\"auto\";s:4:\"Auto\";s:19:\"email_notifications\";s:23:\"Notifications par email\";s:21:\"browser_notifications\";s:27:\"Notifications du navigateur\";s:15:\"two_factor_auth\";s:33:\"Authentification à deux facteurs\";s:15:\"change_password\";s:23:\"Changer le mot de passe\";s:12:\"api_settings\";s:15:\"Paramètres API\";s:16:\"license_settings\";s:22:\"Paramètres de licence\";s:5:\"saved\";s:37:\"Paramètres enregistrés avec succès\";}s:4:\"auth\";a:15:{s:5:\"login\";s:9:\"Connexion\";s:8:\"register\";s:11:\"Inscription\";s:15:\"forgot_password\";s:20:\"Mot de passe oublié\";s:14:\"reset_password\";s:30:\"Réinitialiser le mot de passe\";s:13:\"email_address\";s:13:\"Adresse email\";s:8:\"password\";s:12:\"Mot de passe\";s:11:\"remember_me\";s:18:\"Se souvenir de moi\";s:12:\"login_button\";s:12:\"Se connecter\";s:6:\"logout\";s:12:\"Déconnexion\";s:4:\"name\";s:3:\"Nom\";s:16:\"confirm_password\";s:25:\"Confirmer le mot de passe\";s:15:\"register_button\";s:10:\"S\'inscrire\";s:12:\"reset_button\";s:14:\"Réinitialiser\";s:15:\"send_reset_link\";s:36:\"Envoyer le lien de réinitialisation\";s:12:\"login_failed\";s:59:\"Échec de la connexion, veuillez vérifier vos identifiants\";}}s:15:\"translation.new\";a:1:{s:17:\"api_documentation\";a:2:{s:14:\"best_practices\";s:20:\"Meilleures pratiques\";s:7:\"support\";a:2:{s:5:\"title\";s:7:\"Support\";s:11:\"description\";s:160:\"Si vous rencontrez des problèmes lors de l\'intégration de l\'API ou si vous avez des questions, n\'hésitez pas à contacter notre équipe de support technique.\";}}}}', 1750195092);

-- --------------------------------------------------------

--
-- Structure de la table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `clients`
--

CREATE TABLE `clients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tenant_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `company_name` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
  `postal_code` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'active',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `email_templates`
--

CREATE TABLE `email_templates` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`content`)),
  `html_content` text NOT NULL,
  `text_content` text DEFAULT NULL,
  `variables` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`variables`)),
  `description` varchar(255) DEFAULT NULL,
  `is_system` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `email_variables`
--

CREATE TABLE `email_variables` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `example` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `email_variables`
--

INSERT INTO `email_variables` (`id`, `name`, `description`, `example`, `created_at`, `updated_at`) VALUES
(1, '{name}', 'Nom du destinataire', 'John Doe', '2025-04-15 18:14:51', '2025-04-15 18:14:51'),
(2, '{email}', 'Adresse email du destinataire', 'john@example.com', '2025-04-15 18:14:51', '2025-04-15 18:14:51'),
(3, '{company}', 'Nom de l\'entreprise', 'ACME Corp', '2025-04-15 18:14:51', '2025-04-15 18:14:51'),
(4, '{date}', 'Date courante', '01/01/2024', '2025-04-15 18:14:51', '2025-04-15 18:14:51'),
(5, '{unsubscribe_link}', 'Lien de désabonnement', 'https://example.com/unsubscribe', '2025-04-15 18:14:51', '2025-04-15 18:14:51');

-- --------------------------------------------------------

--
-- Structure de la table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `invoices`
--

CREATE TABLE `invoices` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tenant_id` bigint(20) UNSIGNED NOT NULL,
  `subscription_id` bigint(20) UNSIGNED DEFAULT NULL,
  `provider` varchar(255) NOT NULL,
  `provider_id` varchar(255) DEFAULT NULL,
  `number` varchar(255) DEFAULT NULL,
  `total` int(11) NOT NULL,
  `currency` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `billing_reason` varchar(255) DEFAULT NULL,
  `billing_details` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`billing_details`)),
  `payment_method_id` bigint(20) UNSIGNED DEFAULT NULL,
  `payment_method_type` varchar(255) DEFAULT NULL,
  `paid_at` timestamp NULL DEFAULT NULL,
  `due_at` timestamp NULL DEFAULT NULL,
  `refunded_at` timestamp NULL DEFAULT NULL,
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`metadata`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `invoice_items`
--

CREATE TABLE `invoice_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `invoice_id` bigint(20) UNSIGNED NOT NULL,
  `description` varchar(255) NOT NULL,
  `amount` int(11) NOT NULL,
  `currency` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `period_start` timestamp NULL DEFAULT NULL,
  `period_end` timestamp NULL DEFAULT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'invoice_item',
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`metadata`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `licence_histories`
--

CREATE TABLE `licence_histories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `serial_key_id` bigint(20) UNSIGNED NOT NULL,
  `action` varchar(255) NOT NULL,
  `details` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`details`)),
  `performed_by` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `licence_histories`
--

INSERT INTO `licence_histories` (`id`, `serial_key_id`, `action`, `details`, `performed_by`, `ip_address`, `created_at`, `updated_at`) VALUES
(1, 8, 'activation', NULL, NULL, '10.0.0.1', '2025-04-27 11:37:57', '2025-04-27 11:37:57'),
(2, 95, 'activation', NULL, NULL, '172.16.0.1', '2025-04-27 16:01:30', '2025-04-27 16:01:30'),
(3, 106, 'validation', NULL, NULL, '8.8.8.8', '2025-04-27 15:50:21', '2025-04-27 15:50:21'),
(4, 81, 'verify', NULL, NULL, '8.8.8.8', '2025-04-27 06:08:06', '2025-04-27 06:08:06'),
(5, 94, 'activation', NULL, NULL, '8.8.8.8', '2025-04-27 07:35:08', '2025-04-27 07:35:08'),
(6, 53, 'validation', NULL, NULL, '192.168.1.1', '2025-04-28 07:55:18', '2025-04-28 07:55:18'),
(7, 35, 'verify', NULL, NULL, '10.0.0.1', '2025-04-28 06:49:36', '2025-04-28 06:49:36'),
(8, 86, 'verify', NULL, NULL, '192.168.1.1', '2025-04-28 09:55:29', '2025-04-28 09:55:29'),
(9, 43, 'validation', NULL, NULL, '10.0.0.1', '2025-04-28 09:52:31', '2025-04-28 09:52:31'),
(10, 83, 'validation', NULL, NULL, '10.0.0.1', '2025-04-28 21:06:58', '2025-04-28 21:06:58'),
(11, 40, 'validation', NULL, NULL, '10.0.0.1', '2025-04-28 07:32:39', '2025-04-28 07:32:39'),
(12, 85, 'check', NULL, NULL, '10.0.0.1', '2025-04-28 14:23:44', '2025-04-28 14:23:44'),
(13, 91, 'verify', NULL, NULL, '172.16.0.1', '2025-04-28 09:51:24', '2025-04-28 09:51:24'),
(14, 57, 'check', NULL, NULL, '172.16.0.1', '2025-04-28 08:29:14', '2025-04-28 08:29:14'),
(15, 81, 'verify', NULL, NULL, '10.0.0.1', '2025-04-28 19:26:18', '2025-04-28 19:26:18'),
(16, 107, 'validation', NULL, NULL, '192.168.1.1', '2025-04-28 18:17:59', '2025-04-28 18:17:59'),
(17, 110, 'activation', NULL, NULL, '172.16.0.1', '2025-04-28 08:13:10', '2025-04-28 08:13:10'),
(18, 57, 'validation', NULL, NULL, '8.8.8.8', '2025-04-28 06:21:50', '2025-04-28 06:21:50'),
(19, 67, 'check', NULL, NULL, '192.168.1.1', '2025-04-28 13:07:38', '2025-04-28 13:07:38'),
(20, 51, 'verify', NULL, NULL, '192.168.1.1', '2025-04-28 18:31:07', '2025-04-28 18:31:07'),
(21, 2, 'verify', NULL, NULL, '8.8.8.8', '2025-04-28 10:41:06', '2025-04-28 10:41:06'),
(22, 54, 'activation', NULL, NULL, '8.8.8.8', '2025-04-28 16:45:25', '2025-04-28 16:45:25'),
(23, 3, 'verify', NULL, NULL, '10.0.0.1', '2025-04-28 08:19:14', '2025-04-28 08:19:14'),
(24, 4, 'check', NULL, NULL, '10.0.0.1', '2025-04-28 14:15:32', '2025-04-28 14:15:32'),
(25, 110, 'validation', NULL, NULL, '10.0.0.1', '2025-04-28 08:21:32', '2025-04-28 08:21:32'),
(26, 30, 'check', NULL, NULL, '8.8.8.8', '2025-04-28 07:29:31', '2025-04-28 07:29:31'),
(27, 44, 'check', NULL, NULL, '192.168.1.1', '2025-04-28 18:33:33', '2025-04-28 18:33:33'),
(28, 10, 'activation', NULL, NULL, '172.16.0.1', '2025-04-28 08:41:49', '2025-04-28 08:41:49'),
(29, 73, 'check', NULL, NULL, '10.0.0.1', '2025-04-28 06:32:20', '2025-04-28 06:32:20'),
(30, 69, 'check', NULL, NULL, '192.168.1.1', '2025-04-28 17:15:24', '2025-04-28 17:15:24'),
(31, 106, 'verify', NULL, NULL, '192.168.1.1', '2025-04-28 13:51:28', '2025-04-28 13:51:28'),
(32, 46, 'verify', NULL, NULL, '192.168.1.1', '2025-04-28 13:15:46', '2025-04-28 13:15:46'),
(33, 50, 'activation', NULL, NULL, '10.0.0.1', '2025-04-28 10:30:29', '2025-04-28 10:30:29'),
(34, 52, 'check', NULL, NULL, '10.0.0.1', '2025-04-28 07:34:40', '2025-04-28 07:34:40'),
(35, 64, 'check', NULL, NULL, '8.8.8.8', '2025-04-28 11:48:53', '2025-04-28 11:48:53'),
(36, 105, 'activation', NULL, NULL, '10.0.0.1', '2025-04-28 10:50:23', '2025-04-28 10:50:23'),
(37, 85, 'verify', NULL, NULL, '10.0.0.1', '2025-04-28 10:24:48', '2025-04-28 10:24:48'),
(38, 90, 'verify', NULL, NULL, '10.0.0.1', '2025-04-28 16:00:49', '2025-04-28 16:00:49'),
(39, 37, 'verify', NULL, NULL, '8.8.8.8', '2025-04-28 13:49:37', '2025-04-28 13:49:37'),
(40, 63, 'activation', NULL, NULL, '172.16.0.1', '2025-04-28 12:54:53', '2025-04-28 12:54:53'),
(41, 92, 'activation', NULL, NULL, '192.168.1.1', '2025-04-29 15:51:33', '2025-04-29 15:51:33'),
(42, 28, 'validation', NULL, NULL, '10.0.0.1', '2025-04-29 10:26:33', '2025-04-29 10:26:33'),
(43, 45, 'check', NULL, NULL, '172.16.0.1', '2025-04-29 11:06:09', '2025-04-29 11:06:09'),
(44, 62, 'check', NULL, NULL, '10.0.0.1', '2025-04-29 15:21:46', '2025-04-29 15:21:46'),
(45, 30, 'activation', NULL, NULL, '10.0.0.1', '2025-04-29 14:49:54', '2025-04-29 14:49:54'),
(46, 43, 'check', NULL, NULL, '8.8.8.8', '2025-04-29 12:29:45', '2025-04-29 12:29:45'),
(47, 82, 'check', NULL, NULL, '8.8.8.8', '2025-04-29 08:35:14', '2025-04-29 08:35:14'),
(48, 107, 'activation', NULL, NULL, '172.16.0.1', '2025-04-29 15:50:51', '2025-04-29 15:50:51'),
(49, 52, 'verify', NULL, NULL, '10.0.0.1', '2025-04-29 06:10:56', '2025-04-29 06:10:56'),
(50, 90, 'activation', NULL, NULL, '10.0.0.1', '2025-04-29 14:29:40', '2025-04-29 14:29:40'),
(51, 31, 'check', NULL, NULL, '10.0.0.1', '2025-04-29 16:48:30', '2025-04-29 16:48:30'),
(52, 25, 'check', NULL, NULL, '8.8.8.8', '2025-04-29 07:03:13', '2025-04-29 07:03:13'),
(53, 32, 'check', NULL, NULL, '10.0.0.1', '2025-04-29 10:07:10', '2025-04-29 10:07:10'),
(54, 83, 'verify', NULL, NULL, '8.8.8.8', '2025-04-29 16:39:14', '2025-04-29 16:39:14'),
(55, 66, 'verify', NULL, NULL, '172.16.0.1', '2025-04-29 12:45:01', '2025-04-29 12:45:01'),
(56, 10, 'verify', NULL, NULL, '172.16.0.1', '2025-04-29 08:47:27', '2025-04-29 08:47:27'),
(57, 31, 'validation', NULL, NULL, '8.8.8.8', '2025-04-29 20:19:17', '2025-04-29 20:19:17'),
(58, 39, 'validation', NULL, NULL, '8.8.8.8', '2025-04-29 13:10:59', '2025-04-29 13:10:59'),
(59, 95, 'activation', NULL, NULL, '10.0.0.1', '2025-04-29 21:59:39', '2025-04-29 21:59:39'),
(60, 103, 'verify', NULL, NULL, '172.16.0.1', '2025-04-29 14:04:11', '2025-04-29 14:04:11'),
(61, 29, 'activation', NULL, NULL, '172.16.0.1', '2025-04-29 19:48:27', '2025-04-29 19:48:27'),
(62, 100, 'check', NULL, NULL, '10.0.0.1', '2025-04-29 14:53:00', '2025-04-29 14:53:00'),
(63, 92, 'validation', NULL, NULL, '10.0.0.1', '2025-04-29 12:34:14', '2025-04-29 12:34:14'),
(64, 95, 'validation', NULL, NULL, '192.168.1.1', '2025-04-29 08:53:20', '2025-04-29 08:53:20'),
(65, 28, 'verify', NULL, NULL, '10.0.0.1', '2025-04-29 10:59:58', '2025-04-29 10:59:58'),
(66, 75, 'validation', NULL, NULL, '172.16.0.1', '2025-04-29 11:23:45', '2025-04-29 11:23:45'),
(67, 70, 'verify', NULL, NULL, '172.16.0.1', '2025-04-29 10:58:44', '2025-04-29 10:58:44'),
(68, 57, 'verify', NULL, NULL, '172.16.0.1', '2025-04-29 09:49:45', '2025-04-29 09:49:45'),
(69, 72, 'validation', NULL, NULL, '192.168.1.1', '2025-04-29 10:03:57', '2025-04-29 10:03:57'),
(70, 43, 'verify', NULL, NULL, '10.0.0.1', '2025-04-29 15:51:04', '2025-04-29 15:51:04'),
(71, 38, 'verify', NULL, NULL, '172.16.0.1', '2025-04-29 19:09:33', '2025-04-29 19:09:33'),
(72, 46, 'check', NULL, NULL, '10.0.0.1', '2025-04-29 10:00:59', '2025-04-29 10:00:59'),
(73, 55, 'activation', NULL, NULL, '192.168.1.1', '2025-04-29 10:22:05', '2025-04-29 10:22:05'),
(74, 27, 'verify', NULL, NULL, '8.8.8.8', '2025-04-29 10:32:53', '2025-04-29 10:32:53'),
(75, 91, 'check', NULL, NULL, '172.16.0.1', '2025-04-30 12:52:45', '2025-04-30 12:52:45'),
(76, 87, 'activation', NULL, NULL, '172.16.0.1', '2025-04-30 12:33:24', '2025-04-30 12:33:24'),
(77, 32, 'check', NULL, NULL, '192.168.1.1', '2025-04-30 12:00:13', '2025-04-30 12:00:13'),
(78, 77, 'check', NULL, NULL, '10.0.0.1', '2025-04-30 19:15:28', '2025-04-30 19:15:28'),
(79, 85, 'check', NULL, NULL, '192.168.1.1', '2025-04-30 18:46:35', '2025-04-30 18:46:35'),
(80, 52, 'validation', NULL, NULL, '8.8.8.8', '2025-04-30 06:36:29', '2025-04-30 06:36:29'),
(81, 10, 'validation', NULL, NULL, '10.0.0.1', '2025-04-30 16:00:14', '2025-04-30 16:00:14'),
(82, 108, 'check', NULL, NULL, '10.0.0.1', '2025-04-30 09:02:01', '2025-04-30 09:02:01'),
(83, 77, 'activation', NULL, NULL, '172.16.0.1', '2025-04-30 18:32:06', '2025-04-30 18:32:06'),
(84, 28, 'check', NULL, NULL, '172.16.0.1', '2025-04-30 16:42:18', '2025-04-30 16:42:18'),
(85, 27, 'activation', NULL, NULL, '10.0.0.1', '2025-04-30 16:55:01', '2025-04-30 16:55:01'),
(86, 70, 'activation', NULL, NULL, '10.0.0.1', '2025-04-30 11:24:48', '2025-04-30 11:24:48'),
(87, 68, 'validation', NULL, NULL, '172.16.0.1', '2025-04-30 21:56:17', '2025-04-30 21:56:17'),
(88, 2, 'verify', NULL, NULL, '192.168.1.1', '2025-04-30 10:52:46', '2025-04-30 10:52:46'),
(89, 82, 'check', NULL, NULL, '172.16.0.1', '2025-04-30 13:03:19', '2025-04-30 13:03:19'),
(90, 107, 'activation', NULL, NULL, '172.16.0.1', '2025-04-30 08:36:55', '2025-04-30 08:36:55'),
(91, 69, 'validation', NULL, NULL, '8.8.8.8', '2025-04-30 08:10:47', '2025-04-30 08:10:47'),
(92, 58, 'check', NULL, NULL, '172.16.0.1', '2025-04-30 15:17:04', '2025-04-30 15:17:04'),
(93, 109, 'check', NULL, NULL, '10.0.0.1', '2025-04-30 08:22:40', '2025-04-30 08:22:40'),
(94, 91, 'verify', NULL, NULL, '192.168.1.1', '2025-04-30 20:17:58', '2025-04-30 20:17:58'),
(95, 54, 'validation', NULL, NULL, '172.16.0.1', '2025-04-30 12:06:17', '2025-04-30 12:06:17'),
(96, 88, 'check', NULL, NULL, '10.0.0.1', '2025-04-30 09:14:05', '2025-04-30 09:14:05'),
(97, 31, 'verify', NULL, NULL, '8.8.8.8', '2025-05-01 18:15:17', '2025-05-01 18:15:17'),
(98, 67, 'validation', NULL, NULL, '8.8.8.8', '2025-05-01 14:29:07', '2025-05-01 14:29:07'),
(99, 76, 'activation', NULL, NULL, '10.0.0.1', '2025-05-01 13:18:55', '2025-05-01 13:18:55'),
(100, 72, 'check', NULL, NULL, '10.0.0.1', '2025-05-01 19:50:03', '2025-05-01 19:50:03'),
(101, 40, 'activation', NULL, NULL, '192.168.1.1', '2025-05-01 08:49:37', '2025-05-01 08:49:37'),
(102, 72, 'validation', NULL, NULL, '172.16.0.1', '2025-05-01 21:37:15', '2025-05-01 21:37:15'),
(103, 109, 'check', NULL, NULL, '10.0.0.1', '2025-05-01 17:17:21', '2025-05-01 17:17:21'),
(104, 56, 'validation', NULL, NULL, '8.8.8.8', '2025-05-01 14:09:57', '2025-05-01 14:09:57'),
(105, 34, 'validation', NULL, NULL, '8.8.8.8', '2025-05-01 10:27:51', '2025-05-01 10:27:51'),
(106, 62, 'activation', NULL, NULL, '192.168.1.1', '2025-05-01 08:24:27', '2025-05-01 08:24:27'),
(107, 25, 'check', NULL, NULL, '8.8.8.8', '2025-05-01 13:50:20', '2025-05-01 13:50:20'),
(108, 61, 'check', NULL, NULL, '10.0.0.1', '2025-05-01 09:59:09', '2025-05-01 09:59:09'),
(109, 65, 'validation', NULL, NULL, '172.16.0.1', '2025-05-01 18:45:53', '2025-05-01 18:45:53'),
(110, 36, 'validation', NULL, NULL, '8.8.8.8', '2025-05-01 09:01:04', '2025-05-01 09:01:04'),
(111, 36, 'verify', NULL, NULL, '10.0.0.1', '2025-05-01 15:11:39', '2025-05-01 15:11:39'),
(112, 1, 'verify', NULL, NULL, '172.16.0.1', '2025-05-01 20:14:17', '2025-05-01 20:14:17'),
(113, 21, 'activation', NULL, NULL, '172.16.0.1', '2025-05-01 14:55:08', '2025-05-01 14:55:08'),
(114, 32, 'verify', NULL, NULL, '10.0.0.1', '2025-05-02 20:56:15', '2025-05-02 20:56:15'),
(115, 57, 'check', NULL, NULL, '10.0.0.1', '2025-05-02 10:26:12', '2025-05-02 10:26:12'),
(116, 70, 'activation', NULL, NULL, '192.168.1.1', '2025-05-02 20:07:35', '2025-05-02 20:07:35'),
(117, 66, 'activation', NULL, NULL, '10.0.0.1', '2025-05-02 21:29:31', '2025-05-02 21:29:31'),
(118, 1, 'check', NULL, NULL, '8.8.8.8', '2025-05-02 07:27:17', '2025-05-02 07:27:17'),
(119, 48, 'verify', NULL, NULL, '192.168.1.1', '2025-05-02 20:46:03', '2025-05-02 20:46:03'),
(120, 88, 'validation', NULL, NULL, '10.0.0.1', '2025-05-02 15:00:44', '2025-05-02 15:00:44'),
(121, 63, 'activation', NULL, NULL, '172.16.0.1', '2025-05-02 07:58:51', '2025-05-02 07:58:51'),
(122, 9, 'check', NULL, NULL, '192.168.1.1', '2025-05-02 18:16:17', '2025-05-02 18:16:17'),
(123, 89, 'verify', NULL, NULL, '192.168.1.1', '2025-05-02 15:34:42', '2025-05-02 15:34:42'),
(124, 23, 'activation', NULL, NULL, '192.168.1.1', '2025-05-02 16:42:24', '2025-05-02 16:42:24'),
(125, 10, 'verify', NULL, NULL, '8.8.8.8', '2025-05-02 15:00:39', '2025-05-02 15:00:39'),
(126, 35, 'activation', NULL, NULL, '192.168.1.1', '2025-05-03 13:20:41', '2025-05-03 13:20:41'),
(127, 62, 'validation', NULL, NULL, '172.16.0.1', '2025-05-03 14:42:52', '2025-05-03 14:42:52'),
(128, 56, 'activation', NULL, NULL, '8.8.8.8', '2025-05-03 15:48:18', '2025-05-03 15:48:18'),
(129, 10, 'verify', NULL, NULL, '8.8.8.8', '2025-05-03 15:56:31', '2025-05-03 15:56:31'),
(130, 4, 'activation', NULL, NULL, '172.16.0.1', '2025-05-03 14:55:45', '2025-05-03 14:55:45'),
(131, 49, 'activation', NULL, NULL, '8.8.8.8', '2025-05-03 08:20:26', '2025-05-03 08:20:26'),
(132, 25, 'check', NULL, NULL, '10.0.0.1', '2025-05-03 20:34:04', '2025-05-03 20:34:04'),
(133, 76, 'validation', NULL, NULL, '8.8.8.8', '2025-05-03 21:57:18', '2025-05-03 21:57:18'),
(134, 91, 'validation', NULL, NULL, '10.0.0.1', '2025-05-03 14:10:40', '2025-05-03 14:10:40'),
(135, 104, 'validation', NULL, NULL, '10.0.0.1', '2025-05-03 16:51:23', '2025-05-03 16:51:23'),
(136, 86, 'check', NULL, NULL, '172.16.0.1', '2025-05-03 09:46:46', '2025-05-03 09:46:46'),
(137, 57, 'activation', NULL, NULL, '10.0.0.1', '2025-05-03 16:58:43', '2025-05-03 16:58:43'),
(138, 100, 'check', NULL, NULL, '10.0.0.1', '2025-05-04 17:29:41', '2025-05-04 17:29:41'),
(139, 109, 'validation', NULL, NULL, '172.16.0.1', '2025-05-04 09:11:24', '2025-05-04 09:11:24'),
(140, 53, 'check', NULL, NULL, '10.0.0.1', '2025-05-04 13:03:21', '2025-05-04 13:03:21'),
(141, 89, 'check', NULL, NULL, '8.8.8.8', '2025-05-04 08:26:49', '2025-05-04 08:26:49'),
(142, 27, 'activation', NULL, NULL, '10.0.0.1', '2025-05-05 19:31:33', '2025-05-05 19:31:33'),
(143, 76, 'check', NULL, NULL, '8.8.8.8', '2025-05-05 11:06:47', '2025-05-05 11:06:47'),
(144, 102, 'activation', NULL, NULL, '172.16.0.1', '2025-05-05 18:25:47', '2025-05-05 18:25:47'),
(145, 25, 'verify', NULL, NULL, '10.0.0.1', '2025-05-05 15:57:31', '2025-05-05 15:57:31'),
(146, 76, 'check', NULL, NULL, '192.168.1.1', '2025-05-05 16:22:20', '2025-05-05 16:22:20'),
(147, 21, 'verify', NULL, NULL, '8.8.8.8', '2025-05-05 18:23:21', '2025-05-05 18:23:21'),
(148, 80, 'validation', NULL, NULL, '172.16.0.1', '2025-05-05 11:19:50', '2025-05-05 11:19:50'),
(149, 35, 'activation', NULL, NULL, '10.0.0.1', '2025-05-05 06:21:33', '2025-05-05 06:21:33'),
(150, 75, 'check', NULL, NULL, '192.168.1.1', '2025-05-05 19:38:28', '2025-05-05 19:38:28'),
(151, 38, 'verify', NULL, NULL, '192.168.1.1', '2025-05-05 10:54:28', '2025-05-05 10:54:28'),
(152, 96, 'activation', NULL, NULL, '172.16.0.1', '2025-05-05 14:11:22', '2025-05-05 14:11:22'),
(153, 10, 'validation', NULL, NULL, '172.16.0.1', '2025-05-05 19:11:39', '2025-05-05 19:11:39'),
(154, 71, 'validation', NULL, NULL, '10.0.0.1', '2025-05-05 21:20:11', '2025-05-05 21:20:11'),
(155, 47, 'check', NULL, NULL, '192.168.1.1', '2025-05-05 20:18:55', '2025-05-05 20:18:55'),
(156, 107, 'activation', NULL, NULL, '192.168.1.1', '2025-05-05 09:29:46', '2025-05-05 09:29:46'),
(157, 37, 'check', NULL, NULL, '172.16.0.1', '2025-05-05 09:33:00', '2025-05-05 09:33:00'),
(158, 78, 'check', NULL, NULL, '8.8.8.8', '2025-05-05 14:53:37', '2025-05-05 14:53:37'),
(159, 31, 'verify', NULL, NULL, '8.8.8.8', '2025-05-05 08:26:15', '2025-05-05 08:26:15'),
(160, 82, 'check', NULL, NULL, '8.8.8.8', '2025-05-05 11:26:06', '2025-05-05 11:26:06'),
(161, 38, 'check', NULL, NULL, '10.0.0.1', '2025-05-05 15:56:46', '2025-05-05 15:56:46'),
(162, 96, 'check', NULL, NULL, '172.16.0.1', '2025-05-05 07:24:34', '2025-05-05 07:24:34'),
(163, 48, 'validation', NULL, NULL, '172.16.0.1', '2025-05-05 10:58:48', '2025-05-05 10:58:48'),
(164, 107, 'check', NULL, NULL, '172.16.0.1', '2025-05-05 19:06:44', '2025-05-05 19:06:44'),
(165, 10, 'activation', NULL, NULL, '10.0.0.1', '2025-05-05 21:29:42', '2025-05-05 21:29:42'),
(166, 74, 'check', NULL, NULL, '192.168.1.1', '2025-05-05 09:16:36', '2025-05-05 09:16:36'),
(167, 93, 'activation', NULL, NULL, '10.0.0.1', '2025-05-05 20:32:59', '2025-05-05 20:32:59'),
(168, 66, 'activation', NULL, NULL, '192.168.1.1', '2025-05-05 15:49:30', '2025-05-05 15:49:30'),
(169, 27, 'verify', NULL, NULL, '10.0.0.1', '2025-05-05 11:36:40', '2025-05-05 11:36:40'),
(170, 110, 'validation', NULL, NULL, '8.8.8.8', '2025-05-05 06:47:42', '2025-05-05 06:47:42'),
(171, 82, 'check', NULL, NULL, '8.8.8.8', '2025-05-05 16:38:41', '2025-05-05 16:38:41'),
(172, 57, 'validation', NULL, NULL, '172.16.0.1', '2025-05-05 06:57:41', '2025-05-05 06:57:41'),
(173, 64, 'activation', NULL, NULL, '192.168.1.1', '2025-05-05 16:17:54', '2025-05-05 16:17:54'),
(174, 65, 'validation', NULL, NULL, '172.16.0.1', '2025-05-05 20:14:24', '2025-05-05 20:14:24'),
(175, 5, 'activation', NULL, NULL, '172.16.0.1', '2025-05-05 21:41:57', '2025-05-05 21:41:57'),
(176, 64, 'activation', NULL, NULL, '8.8.8.8', '2025-05-06 21:56:08', '2025-05-06 21:56:08'),
(177, 33, 'check', NULL, NULL, '8.8.8.8', '2025-05-06 14:39:34', '2025-05-06 14:39:34'),
(178, 65, 'validation', NULL, NULL, '192.168.1.1', '2025-05-06 12:14:15', '2025-05-06 12:14:15'),
(179, 69, 'verify', NULL, NULL, '10.0.0.1', '2025-05-06 20:17:56', '2025-05-06 20:17:56'),
(180, 93, 'validation', NULL, NULL, '10.0.0.1', '2025-05-06 09:25:33', '2025-05-06 09:25:33'),
(181, 60, 'activation', NULL, NULL, '10.0.0.1', '2025-05-06 10:53:14', '2025-05-06 10:53:14'),
(182, 95, 'validation', NULL, NULL, '192.168.1.1', '2025-05-06 12:37:42', '2025-05-06 12:37:42'),
(183, 101, 'activation', NULL, NULL, '10.0.0.1', '2025-05-06 15:21:05', '2025-05-06 15:21:05'),
(184, 76, 'check', NULL, NULL, '172.16.0.1', '2025-05-06 06:12:41', '2025-05-06 06:12:41'),
(185, 65, 'activation', NULL, NULL, '172.16.0.1', '2025-05-06 12:13:14', '2025-05-06 12:13:14'),
(186, 104, 'validation', NULL, NULL, '10.0.0.1', '2025-05-06 07:18:25', '2025-05-06 07:18:25'),
(187, 74, 'activation', NULL, NULL, '10.0.0.1', '2025-05-06 21:11:41', '2025-05-06 21:11:41'),
(188, 105, 'verify', NULL, NULL, '192.168.1.1', '2025-05-06 15:05:43', '2025-05-06 15:05:43'),
(189, 71, 'verify', NULL, NULL, '8.8.8.8', '2025-05-06 10:31:37', '2025-05-06 10:31:37'),
(190, 104, 'verify', NULL, NULL, '192.168.1.1', '2025-05-06 19:31:57', '2025-05-06 19:31:57'),
(191, 47, 'check', NULL, NULL, '10.0.0.1', '2025-05-06 12:09:02', '2025-05-06 12:09:02'),
(192, 58, 'activation', NULL, NULL, '172.16.0.1', '2025-05-06 11:02:53', '2025-05-06 11:02:53'),
(193, 82, 'verify', NULL, NULL, '8.8.8.8', '2025-05-06 15:12:27', '2025-05-06 15:12:27'),
(194, 39, 'check', NULL, NULL, '192.168.1.1', '2025-05-06 16:10:35', '2025-05-06 16:10:35'),
(195, 23, 'verify', NULL, NULL, '10.0.0.1', '2025-05-06 18:20:54', '2025-05-06 18:20:54'),
(196, 35, 'validation', NULL, NULL, '172.16.0.1', '2025-05-06 11:47:24', '2025-05-06 11:47:24'),
(197, 55, 'validation', NULL, NULL, '10.0.0.1', '2025-05-07 17:18:46', '2025-05-07 17:18:46'),
(198, 4, 'check', NULL, NULL, '172.16.0.1', '2025-05-07 08:57:50', '2025-05-07 08:57:50'),
(199, 95, 'verify', NULL, NULL, '172.16.0.1', '2025-05-07 16:10:59', '2025-05-07 16:10:59'),
(200, 85, 'validation', NULL, NULL, '8.8.8.8', '2025-05-07 07:39:41', '2025-05-07 07:39:41'),
(201, 38, 'validation', NULL, NULL, '8.8.8.8', '2025-05-07 19:30:22', '2025-05-07 19:30:22'),
(202, 33, 'validation', NULL, NULL, '8.8.8.8', '2025-05-07 20:39:36', '2025-05-07 20:39:36'),
(203, 87, 'validation', NULL, NULL, '8.8.8.8', '2025-05-07 14:43:06', '2025-05-07 14:43:06'),
(204, 109, 'verify', NULL, NULL, '8.8.8.8', '2025-05-07 18:02:55', '2025-05-07 18:02:55'),
(205, 9, 'activation', NULL, NULL, '8.8.8.8', '2025-05-07 19:27:13', '2025-05-07 19:27:13'),
(206, 77, 'verify', NULL, NULL, '172.16.0.1', '2025-05-07 16:19:16', '2025-05-07 16:19:16'),
(207, 33, 'activation', NULL, NULL, '10.0.0.1', '2025-05-07 12:17:15', '2025-05-07 12:17:15'),
(208, 68, 'activation', NULL, NULL, '192.168.1.1', '2025-05-07 16:52:48', '2025-05-07 16:52:48'),
(209, 6, 'activation', NULL, NULL, '172.16.0.1', '2025-05-07 08:42:42', '2025-05-07 08:42:42'),
(210, 7, 'validation', NULL, NULL, '192.168.1.1', '2025-05-07 16:16:11', '2025-05-07 16:16:11'),
(211, 76, 'activation', NULL, NULL, '8.8.8.8', '2025-05-07 19:38:22', '2025-05-07 19:38:22'),
(212, 110, 'activation', NULL, NULL, '192.168.1.1', '2025-05-07 06:14:02', '2025-05-07 06:14:02'),
(213, 66, 'check', NULL, NULL, '8.8.8.8', '2025-05-07 12:11:29', '2025-05-07 12:11:29'),
(214, 93, 'verify', NULL, NULL, '8.8.8.8', '2025-05-07 08:08:21', '2025-05-07 08:08:21'),
(215, 95, 'check', NULL, NULL, '172.16.0.1', '2025-05-07 08:38:16', '2025-05-07 08:38:16'),
(216, 40, 'activation', NULL, NULL, '8.8.8.8', '2025-05-07 19:31:56', '2025-05-07 19:31:56'),
(217, 47, 'activation', NULL, NULL, '10.0.0.1', '2025-05-08 10:59:52', '2025-05-08 10:59:52'),
(218, 2, 'activation', NULL, NULL, '8.8.8.8', '2025-05-08 06:32:56', '2025-05-08 06:32:56'),
(219, 57, 'validation', NULL, NULL, '192.168.1.1', '2025-05-08 14:56:40', '2025-05-08 14:56:40'),
(220, 54, 'verify', NULL, NULL, '10.0.0.1', '2025-05-08 08:19:08', '2025-05-08 08:19:08'),
(221, 43, 'verify', NULL, NULL, '172.16.0.1', '2025-05-08 19:50:19', '2025-05-08 19:50:19'),
(222, 24, 'validation', NULL, NULL, '10.0.0.1', '2025-05-08 15:20:15', '2025-05-08 15:20:15'),
(223, 87, 'activation', NULL, NULL, '10.0.0.1', '2025-05-08 13:39:33', '2025-05-08 13:39:33'),
(224, 93, 'activation', NULL, NULL, '10.0.0.1', '2025-05-08 14:01:59', '2025-05-08 14:01:59'),
(225, 65, 'activation', NULL, NULL, '8.8.8.8', '2025-05-08 21:53:28', '2025-05-08 21:53:28'),
(226, 75, 'check', NULL, NULL, '172.16.0.1', '2025-05-08 08:42:17', '2025-05-08 08:42:17'),
(227, 65, 'verify', NULL, NULL, '10.0.0.1', '2025-05-08 06:02:43', '2025-05-08 06:02:43'),
(228, 62, 'check', NULL, NULL, '192.168.1.1', '2025-05-08 19:18:32', '2025-05-08 19:18:32'),
(229, 52, 'validation', NULL, NULL, '192.168.1.1', '2025-05-08 16:44:59', '2025-05-08 16:44:59'),
(230, 32, 'activation', NULL, NULL, '10.0.0.1', '2025-05-08 20:36:55', '2025-05-08 20:36:55'),
(231, 29, 'verify', NULL, NULL, '10.0.0.1', '2025-05-08 11:39:35', '2025-05-08 11:39:35'),
(232, 75, 'validation', NULL, NULL, '10.0.0.1', '2025-05-08 09:44:11', '2025-05-08 09:44:11'),
(233, 22, 'validation', NULL, NULL, '172.16.0.1', '2025-05-08 06:35:09', '2025-05-08 06:35:09'),
(234, 93, 'validation', NULL, NULL, '8.8.8.8', '2025-05-08 14:43:54', '2025-05-08 14:43:54'),
(235, 82, 'verify', NULL, NULL, '192.168.1.1', '2025-05-08 16:06:47', '2025-05-08 16:06:47'),
(236, 76, 'activation', NULL, NULL, '10.0.0.1', '2025-05-08 09:20:48', '2025-05-08 09:20:48'),
(237, 42, 'validation', NULL, NULL, '192.168.1.1', '2025-05-08 16:30:36', '2025-05-08 16:30:36'),
(238, 89, 'activation', NULL, NULL, '8.8.8.8', '2025-05-08 09:53:45', '2025-05-08 09:53:45'),
(239, 93, 'validation', NULL, NULL, '10.0.0.1', '2025-05-08 06:53:30', '2025-05-08 06:53:30'),
(240, 98, 'validation', NULL, NULL, '172.16.0.1', '2025-05-08 10:18:11', '2025-05-08 10:18:11'),
(241, 74, 'verify', NULL, NULL, '10.0.0.1', '2025-05-08 14:08:11', '2025-05-08 14:08:11'),
(242, 63, 'check', NULL, NULL, '192.168.1.1', '2025-05-08 21:37:42', '2025-05-08 21:37:42'),
(243, 30, 'validation', NULL, NULL, '192.168.1.1', '2025-05-08 20:01:04', '2025-05-08 20:01:04'),
(244, 97, 'activation', NULL, NULL, '8.8.8.8', '2025-05-08 09:10:25', '2025-05-08 09:10:25'),
(245, 106, 'activation', NULL, NULL, '172.16.0.1', '2025-05-08 17:01:05', '2025-05-08 17:01:05'),
(246, 103, 'validation', NULL, NULL, '8.8.8.8', '2025-05-08 17:39:53', '2025-05-08 17:39:53'),
(247, 38, 'check', NULL, NULL, '10.0.0.1', '2025-05-08 18:05:29', '2025-05-08 18:05:29'),
(248, 83, 'verify', NULL, NULL, '8.8.8.8', '2025-05-08 10:38:46', '2025-05-08 10:38:46'),
(249, 33, 'activation', NULL, NULL, '8.8.8.8', '2025-05-08 11:10:29', '2025-05-08 11:10:29'),
(250, 37, 'verify', NULL, NULL, '192.168.1.1', '2025-05-08 07:12:28', '2025-05-08 07:12:28'),
(251, 31, 'check', NULL, NULL, '10.0.0.1', '2025-05-08 10:36:28', '2025-05-08 10:36:28'),
(252, 47, 'verify', NULL, NULL, '172.16.0.1', '2025-05-08 15:33:50', '2025-05-08 15:33:50'),
(253, 26, 'activation', NULL, NULL, '192.168.1.1', '2025-05-08 08:49:15', '2025-05-08 08:49:15'),
(254, 44, 'activation', NULL, NULL, '10.0.0.1', '2025-05-08 12:21:22', '2025-05-08 12:21:22'),
(255, 59, 'verify', NULL, NULL, '172.16.0.1', '2025-05-08 13:55:42', '2025-05-08 13:55:42'),
(256, 34, 'activation', NULL, NULL, '192.168.1.1', '2025-05-08 21:23:31', '2025-05-08 21:23:31'),
(257, 42, 'check', NULL, NULL, '8.8.8.8', '2025-05-08 18:21:25', '2025-05-08 18:21:25'),
(258, 49, 'validation', NULL, NULL, '192.168.1.1', '2025-05-08 06:41:28', '2025-05-08 06:41:28'),
(259, 64, 'verify', NULL, NULL, '172.16.0.1', '2025-05-08 09:03:46', '2025-05-08 09:03:46'),
(260, 67, 'activation', NULL, NULL, '172.16.0.1', '2025-05-08 13:21:27', '2025-05-08 13:21:27'),
(261, 78, 'validation', NULL, NULL, '8.8.8.8', '2025-05-08 21:13:28', '2025-05-08 21:13:28'),
(262, 84, 'verify', NULL, NULL, '8.8.8.8', '2025-05-08 11:31:58', '2025-05-08 11:31:58'),
(263, 30, 'verify', NULL, NULL, '10.0.0.1', '2025-05-08 20:40:40', '2025-05-08 20:40:40'),
(264, 54, 'verify', NULL, NULL, '8.8.8.8', '2025-05-08 17:56:29', '2025-05-08 17:56:29'),
(265, 89, 'check', NULL, NULL, '192.168.1.1', '2025-05-09 12:46:53', '2025-05-09 12:46:53'),
(266, 2, 'validation', NULL, NULL, '8.8.8.8', '2025-05-09 20:45:24', '2025-05-09 20:45:24'),
(267, 8, 'verify', NULL, NULL, '172.16.0.1', '2025-05-09 18:50:54', '2025-05-09 18:50:54'),
(268, 65, 'check', NULL, NULL, '8.8.8.8', '2025-05-09 07:43:54', '2025-05-09 07:43:54'),
(269, 52, 'check', NULL, NULL, '10.0.0.1', '2025-05-09 21:42:08', '2025-05-09 21:42:08'),
(270, 79, 'verify', NULL, NULL, '192.168.1.1', '2025-05-09 09:55:13', '2025-05-09 09:55:13'),
(271, 68, 'validation', NULL, NULL, '192.168.1.1', '2025-05-09 16:07:27', '2025-05-09 16:07:27'),
(272, 8, 'activation', NULL, NULL, '8.8.8.8', '2025-05-09 16:18:59', '2025-05-09 16:18:59'),
(273, 4, 'activation', NULL, NULL, '8.8.8.8', '2025-05-09 19:29:29', '2025-05-09 19:29:29'),
(274, 92, 'check', NULL, NULL, '172.16.0.1', '2025-05-09 14:44:22', '2025-05-09 14:44:22'),
(275, 67, 'verify', NULL, NULL, '192.168.1.1', '2025-05-09 07:04:48', '2025-05-09 07:04:48'),
(276, 47, 'verify', NULL, NULL, '172.16.0.1', '2025-05-09 11:02:07', '2025-05-09 11:02:07'),
(277, 4, 'activation', NULL, NULL, '172.16.0.1', '2025-05-09 08:29:51', '2025-05-09 08:29:51'),
(278, 96, 'activation', NULL, NULL, '10.0.0.1', '2025-05-09 07:39:07', '2025-05-09 07:39:07'),
(279, 66, 'check', NULL, NULL, '8.8.8.8', '2025-05-09 16:16:12', '2025-05-09 16:16:12'),
(280, 78, 'verify', NULL, NULL, '8.8.8.8', '2025-05-09 06:09:31', '2025-05-09 06:09:31'),
(281, 72, 'verify', NULL, NULL, '10.0.0.1', '2025-05-09 13:02:08', '2025-05-09 13:02:08'),
(282, 38, 'check', NULL, NULL, '192.168.1.1', '2025-05-09 11:27:01', '2025-05-09 11:27:01'),
(283, 76, 'verify', NULL, NULL, '10.0.0.1', '2025-05-09 16:07:34', '2025-05-09 16:07:34'),
(284, 56, 'check', NULL, NULL, '172.16.0.1', '2025-05-09 07:26:39', '2025-05-09 07:26:39'),
(285, 4, 'activation', NULL, NULL, '172.16.0.1', '2025-05-09 10:14:47', '2025-05-09 10:14:47'),
(286, 89, 'verify', NULL, NULL, '10.0.0.1', '2025-05-09 09:43:43', '2025-05-09 09:43:43'),
(287, 36, 'validation', NULL, NULL, '10.0.0.1', '2025-05-09 12:48:46', '2025-05-09 12:48:46'),
(288, 44, 'activation', NULL, NULL, '172.16.0.1', '2025-05-09 19:06:37', '2025-05-09 19:06:37'),
(289, 36, 'check', NULL, NULL, '8.8.8.8', '2025-05-09 11:44:59', '2025-05-09 11:44:59'),
(290, 74, 'validation', NULL, NULL, '10.0.0.1', '2025-05-09 08:14:00', '2025-05-09 08:14:00'),
(291, 36, 'activation', NULL, NULL, '8.8.8.8', '2025-05-09 17:55:37', '2025-05-09 17:55:37'),
(292, 9, 'activation', NULL, NULL, '172.16.0.1', '2025-05-09 15:08:40', '2025-05-09 15:08:40'),
(293, 74, 'check', NULL, NULL, '8.8.8.8', '2025-05-09 16:35:37', '2025-05-09 16:35:37'),
(294, 98, 'activation', NULL, NULL, '8.8.8.8', '2025-05-09 09:43:22', '2025-05-09 09:43:22'),
(295, 78, 'verify', NULL, NULL, '8.8.8.8', '2025-05-09 07:06:08', '2025-05-09 07:06:08'),
(296, 73, 'verify', NULL, NULL, '192.168.1.1', '2025-05-09 12:25:20', '2025-05-09 12:25:20'),
(297, 102, 'validation', NULL, NULL, '8.8.8.8', '2025-05-09 14:56:58', '2025-05-09 14:56:58'),
(298, 68, 'activation', NULL, NULL, '172.16.0.1', '2025-05-09 12:57:49', '2025-05-09 12:57:49'),
(299, 103, 'verify', NULL, NULL, '172.16.0.1', '2025-05-09 14:31:05', '2025-05-09 14:31:05'),
(300, 98, 'validation', NULL, NULL, '8.8.8.8', '2025-05-09 08:04:22', '2025-05-09 08:04:22'),
(301, 4, 'validation', NULL, NULL, '192.168.1.1', '2025-05-09 18:46:05', '2025-05-09 18:46:05'),
(302, 95, 'activation', NULL, NULL, '192.168.1.1', '2025-05-09 15:51:20', '2025-05-09 15:51:20'),
(303, 67, 'activation', NULL, NULL, '192.168.1.1', '2025-05-09 12:00:13', '2025-05-09 12:00:13'),
(304, 33, 'activation', NULL, NULL, '172.16.0.1', '2025-05-09 17:28:45', '2025-05-09 17:28:45'),
(305, 105, 'verify', NULL, NULL, '192.168.1.1', '2025-05-09 13:31:15', '2025-05-09 13:31:15'),
(306, 49, 'check', NULL, NULL, '8.8.8.8', '2025-05-09 08:20:06', '2025-05-09 08:20:06'),
(307, 31, 'validation', NULL, NULL, '10.0.0.1', '2025-05-09 19:13:20', '2025-05-09 19:13:20'),
(308, 61, 'verify', NULL, NULL, '8.8.8.8', '2025-05-09 13:02:46', '2025-05-09 13:02:46'),
(309, 21, 'check', NULL, NULL, '192.168.1.1', '2025-05-09 11:46:00', '2025-05-09 11:46:00'),
(310, 108, 'verify', NULL, NULL, '172.16.0.1', '2025-05-09 17:08:20', '2025-05-09 17:08:20'),
(311, 69, 'verify', NULL, NULL, '10.0.0.1', '2025-05-09 15:08:37', '2025-05-09 15:08:37'),
(312, 110, 'verify', NULL, NULL, '192.168.1.1', '2025-05-09 18:57:42', '2025-05-09 18:57:42'),
(313, 107, 'check', NULL, NULL, '172.16.0.1', '2025-05-10 17:18:05', '2025-05-10 17:18:05'),
(314, 79, 'verify', NULL, NULL, '192.168.1.1', '2025-05-10 14:17:06', '2025-05-10 14:17:06'),
(315, 91, 'check', NULL, NULL, '192.168.1.1', '2025-05-10 10:49:01', '2025-05-10 10:49:01'),
(316, 102, 'activation', NULL, NULL, '8.8.8.8', '2025-05-10 15:55:08', '2025-05-10 15:55:08'),
(317, 44, 'validation', NULL, NULL, '10.0.0.1', '2025-05-10 07:27:21', '2025-05-10 07:27:21'),
(318, 24, 'activation', NULL, NULL, '192.168.1.1', '2025-05-10 11:44:13', '2025-05-10 11:44:13'),
(319, 82, 'check', NULL, NULL, '8.8.8.8', '2025-05-10 18:33:14', '2025-05-10 18:33:14'),
(320, 102, 'activation', NULL, NULL, '172.16.0.1', '2025-05-10 07:08:17', '2025-05-10 07:08:17'),
(321, 50, 'activation', NULL, NULL, '192.168.1.1', '2025-05-10 16:13:47', '2025-05-10 16:13:47'),
(322, 50, 'verify', NULL, NULL, '172.16.0.1', '2025-05-11 18:56:47', '2025-05-11 18:56:47'),
(323, 41, 'validation', NULL, NULL, '192.168.1.1', '2025-05-11 16:43:27', '2025-05-11 16:43:27'),
(324, 57, 'validation', NULL, NULL, '10.0.0.1', '2025-05-11 06:07:27', '2025-05-11 06:07:27'),
(325, 37, 'verify', NULL, NULL, '192.168.1.1', '2025-05-11 19:01:23', '2025-05-11 19:01:23'),
(326, 102, 'check', NULL, NULL, '192.168.1.1', '2025-05-11 19:15:18', '2025-05-11 19:15:18'),
(327, 87, 'validation', NULL, NULL, '8.8.8.8', '2025-05-11 19:14:30', '2025-05-11 19:14:30'),
(328, 55, 'verify', NULL, NULL, '192.168.1.1', '2025-05-11 16:29:58', '2025-05-11 16:29:58'),
(329, 98, 'activation', NULL, NULL, '8.8.8.8', '2025-05-11 16:28:46', '2025-05-11 16:28:46'),
(330, 110, 'validation', NULL, NULL, '10.0.0.1', '2025-05-11 07:32:14', '2025-05-11 07:32:14'),
(331, 49, 'activation', NULL, NULL, '172.16.0.1', '2025-05-11 16:41:15', '2025-05-11 16:41:15'),
(332, 3, 'validation', NULL, NULL, '8.8.8.8', '2025-05-11 13:27:58', '2025-05-11 13:27:58'),
(333, 101, 'validation', NULL, NULL, '8.8.8.8', '2025-05-11 21:44:59', '2025-05-11 21:44:59'),
(334, 63, 'verify', NULL, NULL, '8.8.8.8', '2025-05-11 12:10:51', '2025-05-11 12:10:51'),
(335, 22, 'activation', NULL, NULL, '8.8.8.8', '2025-05-12 07:53:51', '2025-05-12 07:53:51'),
(336, 97, 'verify', NULL, NULL, '172.16.0.1', '2025-05-12 15:33:43', '2025-05-12 15:33:43'),
(337, 78, 'check', NULL, NULL, '172.16.0.1', '2025-05-12 20:32:33', '2025-05-12 20:32:33'),
(338, 27, 'verify', NULL, NULL, '8.8.8.8', '2025-05-12 20:33:26', '2025-05-12 20:33:26'),
(339, 29, 'check', NULL, NULL, '10.0.0.1', '2025-05-12 19:52:36', '2025-05-12 19:52:36'),
(340, 95, 'activation', NULL, NULL, '10.0.0.1', '2025-05-12 16:05:08', '2025-05-12 16:05:08'),
(341, 96, 'check', NULL, NULL, '8.8.8.8', '2025-05-12 20:31:57', '2025-05-12 20:31:57'),
(342, 63, 'verify', NULL, NULL, '192.168.1.1', '2025-05-12 10:21:56', '2025-05-12 10:21:56'),
(343, 25, 'activation', NULL, NULL, '10.0.0.1', '2025-05-12 14:21:46', '2025-05-12 14:21:46'),
(344, 35, 'check', NULL, NULL, '10.0.0.1', '2025-05-12 19:26:47', '2025-05-12 19:26:47'),
(345, 35, 'check', NULL, NULL, '10.0.0.1', '2025-05-12 15:38:29', '2025-05-12 15:38:29'),
(346, 69, 'verify', NULL, NULL, '192.168.1.1', '2025-05-12 18:50:39', '2025-05-12 18:50:39'),
(347, 8, 'activation', NULL, NULL, '192.168.1.1', '2025-05-12 16:02:05', '2025-05-12 16:02:05'),
(348, 26, 'verify', NULL, NULL, '10.0.0.1', '2025-05-12 16:49:57', '2025-05-12 16:49:57'),
(349, 30, 'check', NULL, NULL, '172.16.0.1', '2025-05-12 15:30:10', '2025-05-12 15:30:10'),
(350, 44, 'validation', NULL, NULL, '10.0.0.1', '2025-05-12 11:50:19', '2025-05-12 11:50:19'),
(351, 59, 'check', NULL, NULL, '192.168.1.1', '2025-05-12 17:04:21', '2025-05-12 17:04:21'),
(352, 40, 'validation', NULL, NULL, '172.16.0.1', '2025-05-12 19:13:30', '2025-05-12 19:13:30'),
(353, 40, 'check', NULL, NULL, '8.8.8.8', '2025-05-12 18:33:36', '2025-05-12 18:33:36'),
(354, 59, 'activation', NULL, NULL, '8.8.8.8', '2025-05-12 06:41:19', '2025-05-12 06:41:19'),
(355, 96, 'check', NULL, NULL, '192.168.1.1', '2025-05-12 20:24:57', '2025-05-12 20:24:57'),
(356, 24, 'validation', NULL, NULL, '172.16.0.1', '2025-05-12 15:54:21', '2025-05-12 15:54:21'),
(357, 99, 'validation', NULL, NULL, '172.16.0.1', '2025-05-12 09:42:36', '2025-05-12 09:42:36'),
(358, 51, 'verify', NULL, NULL, '192.168.1.1', '2025-05-12 08:14:41', '2025-05-12 08:14:41'),
(359, 30, 'activation', NULL, NULL, '10.0.0.1', '2025-05-12 09:21:33', '2025-05-12 09:21:33'),
(360, 102, 'validation', NULL, NULL, '192.168.1.1', '2025-05-12 20:03:07', '2025-05-12 20:03:07'),
(361, 38, 'activation', NULL, NULL, '172.16.0.1', '2025-05-12 12:25:54', '2025-05-12 12:25:54'),
(362, 46, 'verify', NULL, NULL, '8.8.8.8', '2025-05-12 08:46:20', '2025-05-12 08:46:20'),
(363, 10, 'check', NULL, NULL, '172.16.0.1', '2025-05-12 18:12:30', '2025-05-12 18:12:30'),
(364, 105, 'verify', NULL, NULL, '8.8.8.8', '2025-05-12 14:25:40', '2025-05-12 14:25:40'),
(365, 28, 'check', NULL, NULL, '10.0.0.1', '2025-05-12 07:49:03', '2025-05-12 07:49:03'),
(366, 3, 'validation', NULL, NULL, '10.0.0.1', '2025-05-12 15:24:59', '2025-05-12 15:24:59'),
(367, 23, 'verify', NULL, NULL, '172.16.0.1', '2025-05-12 13:25:48', '2025-05-12 13:25:48'),
(368, 87, 'validation', NULL, NULL, '192.168.1.1', '2025-05-12 18:13:28', '2025-05-12 18:13:28'),
(369, 73, 'validation', NULL, NULL, '192.168.1.1', '2025-05-12 18:45:09', '2025-05-12 18:45:09'),
(370, 30, 'activation', NULL, NULL, '10.0.0.1', '2025-05-12 14:40:07', '2025-05-12 14:40:07'),
(371, 48, 'check', NULL, NULL, '192.168.1.1', '2025-05-12 19:49:04', '2025-05-12 19:49:04'),
(372, 97, 'verify', NULL, NULL, '172.16.0.1', '2025-05-12 16:56:14', '2025-05-12 16:56:14'),
(373, 49, 'validation', NULL, NULL, '192.168.1.1', '2025-05-12 06:40:25', '2025-05-12 06:40:25'),
(374, 103, 'check', NULL, NULL, '10.0.0.1', '2025-05-12 07:49:44', '2025-05-12 07:49:44'),
(375, 37, 'verify', NULL, NULL, '10.0.0.1', '2025-05-12 11:46:58', '2025-05-12 11:46:58'),
(376, 55, 'activation', NULL, NULL, '192.168.1.1', '2025-05-12 08:04:41', '2025-05-12 08:04:41'),
(377, 99, 'validation', NULL, NULL, '172.16.0.1', '2025-05-13 19:18:01', '2025-05-13 19:18:01'),
(378, 108, 'check', NULL, NULL, '192.168.1.1', '2025-05-13 07:55:07', '2025-05-13 07:55:07'),
(379, 47, 'activation', NULL, NULL, '172.16.0.1', '2025-05-13 07:52:23', '2025-05-13 07:52:23'),
(380, 54, 'verify', NULL, NULL, '8.8.8.8', '2025-05-13 19:07:11', '2025-05-13 19:07:11'),
(381, 103, 'validation', NULL, NULL, '172.16.0.1', '2025-05-13 18:07:04', '2025-05-13 18:07:04'),
(382, 80, 'activation', NULL, NULL, '172.16.0.1', '2025-05-13 06:58:57', '2025-05-13 06:58:57'),
(383, 94, 'check', NULL, NULL, '172.16.0.1', '2025-05-13 08:09:59', '2025-05-13 08:09:59'),
(384, 1, 'check', NULL, NULL, '10.0.0.1', '2025-05-13 20:14:21', '2025-05-13 20:14:21'),
(385, 76, 'activation', NULL, NULL, '10.0.0.1', '2025-05-13 13:58:45', '2025-05-13 13:58:45'),
(386, 74, 'verify', NULL, NULL, '8.8.8.8', '2025-05-13 09:49:58', '2025-05-13 09:49:58'),
(387, 31, 'validation', NULL, NULL, '172.16.0.1', '2025-05-13 07:31:41', '2025-05-13 07:31:41'),
(388, 68, 'verify', NULL, NULL, '8.8.8.8', '2025-05-13 21:10:35', '2025-05-13 21:10:35'),
(389, 61, 'verify', NULL, NULL, '192.168.1.1', '2025-05-13 07:23:26', '2025-05-13 07:23:26'),
(390, 57, 'validation', NULL, NULL, '192.168.1.1', '2025-05-13 16:06:51', '2025-05-13 16:06:51'),
(391, 47, 'check', NULL, NULL, '192.168.1.1', '2025-05-13 17:56:39', '2025-05-13 17:56:39'),
(392, 72, 'activation', NULL, NULL, '10.0.0.1', '2025-05-13 15:56:06', '2025-05-13 15:56:06'),
(393, 87, 'verify', NULL, NULL, '172.16.0.1', '2025-05-13 07:02:58', '2025-05-13 07:02:58'),
(394, 88, 'check', NULL, NULL, '8.8.8.8', '2025-05-13 14:53:32', '2025-05-13 14:53:32'),
(395, 53, 'check', NULL, NULL, '10.0.0.1', '2025-05-13 15:10:23', '2025-05-13 15:10:23'),
(396, 110, 'check', NULL, NULL, '8.8.8.8', '2025-05-13 13:12:50', '2025-05-13 13:12:50'),
(397, 77, 'activation', NULL, NULL, '172.16.0.1', '2025-05-13 12:21:43', '2025-05-13 12:21:43'),
(398, 7, 'validation', NULL, NULL, '10.0.0.1', '2025-05-13 16:27:30', '2025-05-13 16:27:30'),
(399, 46, 'verify', NULL, NULL, '8.8.8.8', '2025-05-13 20:07:46', '2025-05-13 20:07:46'),
(400, 68, 'check', NULL, NULL, '10.0.0.1', '2025-05-13 09:26:43', '2025-05-13 09:26:43'),
(401, 8, 'validation', NULL, NULL, '192.168.1.1', '2025-05-13 13:42:00', '2025-05-13 13:42:00'),
(402, 28, 'activation', NULL, NULL, '172.16.0.1', '2025-05-13 15:46:21', '2025-05-13 15:46:21'),
(403, 38, 'check', NULL, NULL, '8.8.8.8', '2025-05-14 15:12:18', '2025-05-14 15:12:18'),
(404, 75, 'validation', NULL, NULL, '192.168.1.1', '2025-05-14 12:47:49', '2025-05-14 12:47:49'),
(405, 8, 'check', NULL, NULL, '192.168.1.1', '2025-05-14 12:04:49', '2025-05-14 12:04:49'),
(406, 53, 'check', NULL, NULL, '10.0.0.1', '2025-05-14 13:39:55', '2025-05-14 13:39:55'),
(407, 37, 'check', NULL, NULL, '8.8.8.8', '2025-05-14 14:18:16', '2025-05-14 14:18:16'),
(408, 7, 'activation', NULL, NULL, '192.168.1.1', '2025-05-14 07:45:36', '2025-05-14 07:45:36'),
(409, 63, 'activation', NULL, NULL, '172.16.0.1', '2025-05-14 16:02:53', '2025-05-14 16:02:53'),
(410, 82, 'verify', NULL, NULL, '8.8.8.8', '2025-05-14 07:52:22', '2025-05-14 07:52:22'),
(411, 90, 'check', NULL, NULL, '192.168.1.1', '2025-05-14 06:43:56', '2025-05-14 06:43:56'),
(412, 89, 'check', NULL, NULL, '10.0.0.1', '2025-05-14 21:34:46', '2025-05-14 21:34:46'),
(413, 41, 'validation', NULL, NULL, '172.16.0.1', '2025-05-14 17:06:35', '2025-05-14 17:06:35'),
(414, 37, 'activation', NULL, NULL, '172.16.0.1', '2025-05-14 15:17:32', '2025-05-14 15:17:32'),
(415, 87, 'activation', NULL, NULL, '8.8.8.8', '2025-05-14 18:37:59', '2025-05-14 18:37:59'),
(416, 4, 'check', NULL, NULL, '192.168.1.1', '2025-05-14 19:23:10', '2025-05-14 19:23:10'),
(417, 85, 'verify', NULL, NULL, '10.0.0.1', '2025-05-14 15:40:06', '2025-05-14 15:40:06'),
(418, 58, 'check', NULL, NULL, '8.8.8.8', '2025-05-14 18:13:57', '2025-05-14 18:13:57'),
(419, 28, 'check', NULL, NULL, '8.8.8.8', '2025-05-14 17:07:32', '2025-05-14 17:07:32'),
(420, 24, 'check', NULL, NULL, '8.8.8.8', '2025-05-14 18:06:06', '2025-05-14 18:06:06'),
(421, 59, 'validation', NULL, NULL, '8.8.8.8', '2025-05-14 07:03:29', '2025-05-14 07:03:29'),
(422, 61, 'check', NULL, NULL, '172.16.0.1', '2025-05-14 15:36:58', '2025-05-14 15:36:58'),
(423, 63, 'activation', NULL, NULL, '10.0.0.1', '2025-05-14 15:31:12', '2025-05-14 15:31:12'),
(424, 57, 'validation', NULL, NULL, '8.8.8.8', '2025-05-14 15:35:08', '2025-05-14 15:35:08'),
(425, 46, 'validation', NULL, NULL, '172.16.0.1', '2025-05-14 10:17:03', '2025-05-14 10:17:03'),
(426, 101, 'activation', NULL, NULL, '10.0.0.1', '2025-05-14 21:50:06', '2025-05-14 21:50:06'),
(427, 94, 'check', NULL, NULL, '10.0.0.1', '2025-05-14 16:04:48', '2025-05-14 16:04:48'),
(428, 94, 'activation', NULL, NULL, '8.8.8.8', '2025-05-14 10:19:13', '2025-05-14 10:19:13'),
(429, 10, 'validation', NULL, NULL, '8.8.8.8', '2025-05-14 20:11:30', '2025-05-14 20:11:30'),
(430, 50, 'activation', NULL, NULL, '10.0.0.1', '2025-05-15 18:45:20', '2025-05-15 18:45:20'),
(431, 67, 'verify', NULL, NULL, '172.16.0.1', '2025-05-15 20:46:46', '2025-05-15 20:46:46'),
(432, 8, 'activation', NULL, NULL, '192.168.1.1', '2025-05-15 10:19:29', '2025-05-15 10:19:29'),
(433, 54, 'activation', NULL, NULL, '172.16.0.1', '2025-05-15 09:58:48', '2025-05-15 09:58:48'),
(434, 67, 'validation', NULL, NULL, '192.168.1.1', '2025-05-15 07:48:35', '2025-05-15 07:48:35'),
(435, 42, 'activation', NULL, NULL, '192.168.1.1', '2025-05-15 12:02:47', '2025-05-15 12:02:47'),
(436, 53, 'activation', NULL, NULL, '10.0.0.1', '2025-05-15 06:21:44', '2025-05-15 06:21:44'),
(437, 66, 'activation', NULL, NULL, '8.8.8.8', '2025-05-15 21:28:10', '2025-05-15 21:28:10'),
(438, 59, 'check', NULL, NULL, '192.168.1.1', '2025-05-15 13:45:36', '2025-05-15 13:45:36'),
(439, 70, 'check', NULL, NULL, '8.8.8.8', '2025-05-15 12:02:32', '2025-05-15 12:02:32'),
(440, 95, 'activation', NULL, NULL, '8.8.8.8', '2025-05-15 10:00:07', '2025-05-15 10:00:07'),
(441, 96, 'verify', NULL, NULL, '172.16.0.1', '2025-05-15 15:01:39', '2025-05-15 15:01:39'),
(442, 23, 'verify', NULL, NULL, '10.0.0.1', '2025-05-15 06:10:26', '2025-05-15 06:10:26'),
(443, 68, 'verify', NULL, NULL, '10.0.0.1', '2025-05-15 14:27:58', '2025-05-15 14:27:58'),
(444, 30, 'check', NULL, NULL, '192.168.1.1', '2025-05-15 06:30:42', '2025-05-15 06:30:42'),
(445, 76, 'verify', NULL, NULL, '10.0.0.1', '2025-05-15 08:15:19', '2025-05-15 08:15:19'),
(446, 25, 'verify', NULL, NULL, '10.0.0.1', '2025-05-15 06:46:39', '2025-05-15 06:46:39'),
(447, 101, 'validation', NULL, NULL, '192.168.1.1', '2025-05-15 17:59:55', '2025-05-15 17:59:55'),
(448, 8, 'verify', NULL, NULL, '192.168.1.1', '2025-05-15 10:39:35', '2025-05-15 10:39:35'),
(449, 48, 'check', NULL, NULL, '8.8.8.8', '2025-05-15 12:32:39', '2025-05-15 12:32:39'),
(450, 69, 'activation', NULL, NULL, '172.16.0.1', '2025-05-15 16:04:31', '2025-05-15 16:04:31'),
(451, 29, 'activation', NULL, NULL, '8.8.8.8', '2025-05-15 06:14:46', '2025-05-15 06:14:46'),
(452, 90, 'activation', NULL, NULL, '192.168.1.1', '2025-05-15 06:12:50', '2025-05-15 06:12:50'),
(453, 92, 'activation', NULL, NULL, '8.8.8.8', '2025-05-15 10:28:52', '2025-05-15 10:28:52'),
(454, 67, 'activation', NULL, NULL, '8.8.8.8', '2025-05-15 09:45:28', '2025-05-15 09:45:28'),
(455, 80, 'verify', NULL, NULL, '8.8.8.8', '2025-05-15 14:29:19', '2025-05-15 14:29:19'),
(456, 35, 'activation', NULL, NULL, '172.16.0.1', '2025-05-15 19:11:26', '2025-05-15 19:11:26'),
(457, 62, 'check', NULL, NULL, '10.0.0.1', '2025-05-15 08:09:43', '2025-05-15 08:09:43'),
(458, 99, 'check', NULL, NULL, '172.16.0.1', '2025-05-15 12:57:07', '2025-05-15 12:57:07'),
(459, 23, 'verify', NULL, NULL, '172.16.0.1', '2025-05-15 07:23:30', '2025-05-15 07:23:30'),
(460, 73, 'verify', NULL, NULL, '172.16.0.1', '2025-05-15 21:31:50', '2025-05-15 21:31:50'),
(461, 22, 'validation', NULL, NULL, '192.168.1.1', '2025-05-15 13:59:56', '2025-05-15 13:59:56'),
(462, 48, 'activation', NULL, NULL, '172.16.0.1', '2025-05-15 21:36:11', '2025-05-15 21:36:11'),
(463, 63, 'validation', NULL, NULL, '172.16.0.1', '2025-05-15 19:48:31', '2025-05-15 19:48:31'),
(464, 89, 'verify', NULL, NULL, '10.0.0.1', '2025-05-15 16:24:30', '2025-05-15 16:24:30'),
(465, 91, 'validation', NULL, NULL, '192.168.1.1', '2025-05-15 06:02:48', '2025-05-15 06:02:48'),
(466, 2, 'validation', NULL, NULL, '10.0.0.1', '2025-05-15 19:35:54', '2025-05-15 19:35:54'),
(467, 50, 'verify', NULL, NULL, '172.16.0.1', '2025-05-15 15:38:06', '2025-05-15 15:38:06'),
(468, 64, 'activation', NULL, NULL, '8.8.8.8', '2025-05-15 13:41:24', '2025-05-15 13:41:24'),
(469, 77, 'validation', NULL, NULL, '172.16.0.1', '2025-05-15 11:12:25', '2025-05-15 11:12:25'),
(470, 38, 'check', NULL, NULL, '10.0.0.1', '2025-05-15 16:40:43', '2025-05-15 16:40:43'),
(471, 33, 'validation', NULL, NULL, '192.168.1.1', '2025-05-15 06:41:16', '2025-05-15 06:41:16'),
(472, 64, 'check', NULL, NULL, '10.0.0.1', '2025-05-15 14:07:05', '2025-05-15 14:07:05'),
(473, 47, 'validation', NULL, NULL, '10.0.0.1', '2025-05-15 07:34:11', '2025-05-15 07:34:11'),
(474, 52, 'check', NULL, NULL, '10.0.0.1', '2025-05-15 18:11:21', '2025-05-15 18:11:21'),
(475, 74, 'validation', NULL, NULL, '8.8.8.8', '2025-05-15 20:42:28', '2025-05-15 20:42:28'),
(476, 85, 'verify', NULL, NULL, '172.16.0.1', '2025-05-15 19:42:58', '2025-05-15 19:42:58'),
(477, 73, 'verify', NULL, NULL, '8.8.8.8', '2025-05-15 21:39:05', '2025-05-15 21:39:05'),
(478, 62, 'verify', NULL, NULL, '172.16.0.1', '2025-05-15 16:58:23', '2025-05-15 16:58:23'),
(479, 97, 'validation', NULL, NULL, '10.0.0.1', '2025-05-15 19:35:58', '2025-05-15 19:35:58'),
(480, 59, 'activation', NULL, NULL, '172.16.0.1', '2025-05-15 07:27:43', '2025-05-15 07:27:43'),
(481, 89, 'activation', NULL, NULL, '8.8.8.8', '2025-05-15 08:57:52', '2025-05-15 08:57:52'),
(482, 82, 'verify', NULL, NULL, '8.8.8.8', '2025-05-15 11:23:36', '2025-05-15 11:23:36'),
(483, 47, 'check', NULL, NULL, '172.16.0.1', '2025-05-15 11:44:56', '2025-05-15 11:44:56'),
(484, 86, 'check', NULL, NULL, '10.0.0.1', '2025-05-15 20:01:00', '2025-05-15 20:01:00'),
(485, 34, 'check', NULL, NULL, '192.168.1.1', '2025-05-15 06:05:36', '2025-05-15 06:05:36'),
(486, 4, 'check', NULL, NULL, '192.168.1.1', '2025-05-15 16:49:12', '2025-05-15 16:49:12'),
(487, 104, 'validation', NULL, NULL, '8.8.8.8', '2025-05-15 17:08:42', '2025-05-15 17:08:42'),
(488, 91, 'check', NULL, NULL, '8.8.8.8', '2025-05-15 19:51:16', '2025-05-15 19:51:16'),
(489, 35, 'activation', NULL, NULL, '8.8.8.8', '2025-05-16 08:59:17', '2025-05-16 08:59:17'),
(490, 24, 'validation', NULL, NULL, '172.16.0.1', '2025-05-16 13:29:49', '2025-05-16 13:29:49'),
(491, 107, 'check', NULL, NULL, '8.8.8.8', '2025-05-16 21:54:37', '2025-05-16 21:54:37'),
(492, 8, 'validation', NULL, NULL, '172.16.0.1', '2025-05-16 18:52:36', '2025-05-16 18:52:36'),
(493, 95, 'validation', NULL, NULL, '10.0.0.1', '2025-05-16 17:38:58', '2025-05-16 17:38:58'),
(494, 77, 'verify', NULL, NULL, '192.168.1.1', '2025-05-16 16:04:47', '2025-05-16 16:04:47'),
(495, 22, 'activation', NULL, NULL, '8.8.8.8', '2025-05-16 08:20:24', '2025-05-16 08:20:24'),
(496, 80, 'validation', NULL, NULL, '172.16.0.1', '2025-05-16 07:47:26', '2025-05-16 07:47:26'),
(497, 5, 'check', NULL, NULL, '172.16.0.1', '2025-05-16 15:21:56', '2025-05-16 15:21:56'),
(498, 26, 'activation', NULL, NULL, '172.16.0.1', '2025-05-16 10:49:55', '2025-05-16 10:49:55'),
(499, 95, 'verify', NULL, NULL, '8.8.8.8', '2025-05-16 06:46:37', '2025-05-16 06:46:37'),
(500, 67, 'validation', NULL, NULL, '8.8.8.8', '2025-05-16 14:04:32', '2025-05-16 14:04:32'),
(501, 95, 'activation', NULL, NULL, '10.0.0.1', '2025-05-16 19:16:20', '2025-05-16 19:16:20'),
(502, 67, 'activation', NULL, NULL, '192.168.1.1', '2025-05-16 16:20:08', '2025-05-16 16:20:08'),
(503, 60, 'validation', NULL, NULL, '8.8.8.8', '2025-05-16 21:32:17', '2025-05-16 21:32:17'),
(504, 4, 'verify', NULL, NULL, '172.16.0.1', '2025-05-16 15:39:10', '2025-05-16 15:39:10'),
(505, 83, 'activation', NULL, NULL, '8.8.8.8', '2025-05-16 12:47:35', '2025-05-16 12:47:35'),
(506, 87, 'verify', NULL, NULL, '172.16.0.1', '2025-05-16 15:07:12', '2025-05-16 15:07:12'),
(507, 110, 'validation', NULL, NULL, '192.168.1.1', '2025-05-16 12:55:49', '2025-05-16 12:55:49'),
(508, 76, 'validation', NULL, NULL, '172.16.0.1', '2025-05-16 17:37:54', '2025-05-16 17:37:54'),
(509, 51, 'verify', NULL, NULL, '192.168.1.1', '2025-05-16 17:58:06', '2025-05-16 17:58:06'),
(510, 94, 'check', NULL, NULL, '8.8.8.8', '2025-05-16 21:51:44', '2025-05-16 21:51:44'),
(511, 23, 'verify', NULL, NULL, '192.168.1.1', '2025-05-16 15:30:33', '2025-05-16 15:30:33'),
(512, 39, 'activation', NULL, NULL, '172.16.0.1', '2025-05-16 12:58:17', '2025-05-16 12:58:17'),
(513, 36, 'verify', NULL, NULL, '8.8.8.8', '2025-05-16 10:06:16', '2025-05-16 10:06:16'),
(514, 73, 'check', NULL, NULL, '8.8.8.8', '2025-05-16 18:00:11', '2025-05-16 18:00:11'),
(515, 61, 'activation', NULL, NULL, '10.0.0.1', '2025-05-16 20:06:43', '2025-05-16 20:06:43'),
(516, 94, 'activation', NULL, NULL, '8.8.8.8', '2025-05-16 14:01:08', '2025-05-16 14:01:08'),
(517, 103, 'check', NULL, NULL, '8.8.8.8', '2025-05-16 12:14:20', '2025-05-16 12:14:20'),
(518, 5, 'activation', NULL, NULL, '192.168.1.1', '2025-05-16 13:59:01', '2025-05-16 13:59:01'),
(519, 53, 'activation', NULL, NULL, '8.8.8.8', '2025-05-16 09:23:22', '2025-05-16 09:23:22'),
(520, 37, 'verify', NULL, NULL, '172.16.0.1', '2025-05-16 06:04:29', '2025-05-16 06:04:29'),
(521, 84, 'check', NULL, NULL, '172.16.0.1', '2025-05-16 07:38:06', '2025-05-16 07:38:06'),
(522, 80, 'activation', NULL, NULL, '192.168.1.1', '2025-05-16 06:53:18', '2025-05-16 06:53:18'),
(523, 72, 'validation', NULL, NULL, '172.16.0.1', '2025-05-16 16:40:22', '2025-05-16 16:40:22'),
(524, 31, 'activation', NULL, NULL, '172.16.0.1', '2025-05-16 21:15:16', '2025-05-16 21:15:16'),
(525, 101, 'verify', NULL, NULL, '192.168.1.1', '2025-05-16 20:25:56', '2025-05-16 20:25:56'),
(526, 103, 'check', NULL, NULL, '192.168.1.1', '2025-05-16 14:12:22', '2025-05-16 14:12:22'),
(527, 53, 'check', NULL, NULL, '8.8.8.8', '2025-05-17 21:46:03', '2025-05-17 21:46:03'),
(528, 23, 'activation', NULL, NULL, '8.8.8.8', '2025-05-17 06:18:31', '2025-05-17 06:18:31'),
(529, 79, 'validation', NULL, NULL, '8.8.8.8', '2025-05-17 16:48:00', '2025-05-17 16:48:00'),
(530, 78, 'activation', NULL, NULL, '192.168.1.1', '2025-05-17 20:03:24', '2025-05-17 20:03:24'),
(531, 56, 'activation', NULL, NULL, '10.0.0.1', '2025-05-17 21:48:23', '2025-05-17 21:48:23'),
(532, 56, 'activation', NULL, NULL, '8.8.8.8', '2025-05-18 08:52:53', '2025-05-18 08:52:53'),
(533, 25, 'activation', NULL, NULL, '10.0.0.1', '2025-05-18 07:38:43', '2025-05-18 07:38:43'),
(534, 109, 'check', NULL, NULL, '10.0.0.1', '2025-05-18 12:44:36', '2025-05-18 12:44:36'),
(535, 68, 'verify', NULL, NULL, '172.16.0.1', '2025-05-18 06:32:27', '2025-05-18 06:32:27'),
(536, 2, 'validation', NULL, NULL, '8.8.8.8', '2025-05-18 20:06:57', '2025-05-18 20:06:57'),
(537, 100, 'validation', NULL, NULL, '192.168.1.1', '2025-05-18 07:15:03', '2025-05-18 07:15:03'),
(538, 61, 'check', NULL, NULL, '10.0.0.1', '2025-05-18 14:48:43', '2025-05-18 14:48:43'),
(539, 97, 'check', NULL, NULL, '10.0.0.1', '2025-05-18 06:16:00', '2025-05-18 06:16:00'),
(540, 23, 'validation', NULL, NULL, '172.16.0.1', '2025-05-18 12:36:21', '2025-05-18 12:36:21'),
(541, 97, 'check', NULL, NULL, '10.0.0.1', '2025-05-18 07:00:40', '2025-05-18 07:00:40'),
(542, 24, 'verify', NULL, NULL, '10.0.0.1', '2025-05-18 12:51:27', '2025-05-18 12:51:27'),
(543, 49, 'activation', NULL, NULL, '192.168.1.1', '2025-05-18 19:44:45', '2025-05-18 19:44:45'),
(544, 55, 'activation', NULL, NULL, '192.168.1.1', '2025-05-19 10:58:36', '2025-05-19 10:58:36'),
(545, 96, 'check', NULL, NULL, '10.0.0.1', '2025-05-19 17:52:24', '2025-05-19 17:52:24');
INSERT INTO `licence_histories` (`id`, `serial_key_id`, `action`, `details`, `performed_by`, `ip_address`, `created_at`, `updated_at`) VALUES
(546, 38, 'verify', NULL, NULL, '172.16.0.1', '2025-05-19 14:04:37', '2025-05-19 14:04:37'),
(547, 69, 'verify', NULL, NULL, '192.168.1.1', '2025-05-19 16:49:07', '2025-05-19 16:49:07'),
(548, 25, 'activation', NULL, NULL, '8.8.8.8', '2025-05-19 15:54:38', '2025-05-19 15:54:38'),
(549, 51, 'activation', NULL, NULL, '192.168.1.1', '2025-05-19 16:24:28', '2025-05-19 16:24:28'),
(550, 74, 'validation', NULL, NULL, '8.8.8.8', '2025-05-19 09:04:18', '2025-05-19 09:04:18'),
(551, 9, 'verify', NULL, NULL, '172.16.0.1', '2025-05-19 11:52:35', '2025-05-19 11:52:35'),
(552, 71, 'activation', NULL, NULL, '10.0.0.1', '2025-05-19 18:26:17', '2025-05-19 18:26:17'),
(553, 75, 'activation', NULL, NULL, '192.168.1.1', '2025-05-19 10:35:18', '2025-05-19 10:35:18'),
(554, 35, 'check', NULL, NULL, '10.0.0.1', '2025-05-19 11:34:13', '2025-05-19 11:34:13'),
(555, 42, 'validation', NULL, NULL, '192.168.1.1', '2025-05-19 11:08:34', '2025-05-19 11:08:34'),
(556, 29, 'check', NULL, NULL, '192.168.1.1', '2025-05-19 16:12:21', '2025-05-19 16:12:21'),
(557, 37, 'check', NULL, NULL, '10.0.0.1', '2025-05-19 06:48:25', '2025-05-19 06:48:25'),
(558, 92, 'verify', NULL, NULL, '10.0.0.1', '2025-05-19 13:59:57', '2025-05-19 13:59:57'),
(559, 74, 'validation', NULL, NULL, '192.168.1.1', '2025-05-19 16:48:55', '2025-05-19 16:48:55'),
(560, 98, 'check', NULL, NULL, '172.16.0.1', '2025-05-19 08:34:44', '2025-05-19 08:34:44'),
(561, 38, 'activation', NULL, NULL, '172.16.0.1', '2025-05-19 16:00:42', '2025-05-19 16:00:42'),
(562, 104, 'activation', NULL, NULL, '172.16.0.1', '2025-05-19 13:12:36', '2025-05-19 13:12:36'),
(563, 39, 'check', NULL, NULL, '8.8.8.8', '2025-05-19 14:34:05', '2025-05-19 14:34:05'),
(564, 77, 'check', NULL, NULL, '10.0.0.1', '2025-05-19 07:28:59', '2025-05-19 07:28:59'),
(565, 38, 'verify', NULL, NULL, '192.168.1.1', '2025-05-19 18:46:33', '2025-05-19 18:46:33'),
(566, 82, 'activation', NULL, NULL, '192.168.1.1', '2025-05-19 13:45:49', '2025-05-19 13:45:49'),
(567, 46, 'validation', NULL, NULL, '172.16.0.1', '2025-05-19 12:47:56', '2025-05-19 12:47:56'),
(568, 29, 'validation', NULL, NULL, '10.0.0.1', '2025-05-19 11:52:23', '2025-05-19 11:52:23'),
(569, 65, 'check', NULL, NULL, '192.168.1.1', '2025-05-19 17:52:15', '2025-05-19 17:52:15'),
(570, 2, 'verify', NULL, NULL, '192.168.1.1', '2025-05-19 08:25:32', '2025-05-19 08:25:32'),
(571, 58, 'activation', NULL, NULL, '10.0.0.1', '2025-05-19 12:09:01', '2025-05-19 12:09:01'),
(572, 72, 'validation', NULL, NULL, '192.168.1.1', '2025-05-19 10:45:36', '2025-05-19 10:45:36'),
(573, 65, 'validation', NULL, NULL, '10.0.0.1', '2025-05-19 12:57:30', '2025-05-19 12:57:30'),
(574, 7, 'activation', NULL, NULL, '10.0.0.1', '2025-05-19 11:43:25', '2025-05-19 11:43:25'),
(575, 47, 'check', NULL, NULL, '172.16.0.1', '2025-05-19 07:24:52', '2025-05-19 07:24:52'),
(576, 80, 'activation', NULL, NULL, '172.16.0.1', '2025-05-19 09:41:31', '2025-05-19 09:41:31'),
(577, 28, 'check', NULL, NULL, '192.168.1.1', '2025-05-19 09:43:29', '2025-05-19 09:43:29'),
(578, 75, 'validation', NULL, NULL, '10.0.0.1', '2025-05-19 13:47:43', '2025-05-19 13:47:43'),
(579, 74, 'activation', NULL, NULL, '10.0.0.1', '2025-05-19 09:18:49', '2025-05-19 09:18:49'),
(580, 4, 'verify', NULL, NULL, '192.168.1.1', '2025-05-19 18:32:39', '2025-05-19 18:32:39'),
(581, 33, 'activation', NULL, NULL, '172.16.0.1', '2025-05-19 14:05:56', '2025-05-19 14:05:56'),
(582, 87, 'verify', NULL, NULL, '172.16.0.1', '2025-05-19 16:48:59', '2025-05-19 16:48:59'),
(583, 9, 'verify', NULL, NULL, '172.16.0.1', '2025-05-19 15:27:52', '2025-05-19 15:27:52'),
(584, 74, 'validation', NULL, NULL, '172.16.0.1', '2025-05-19 15:39:36', '2025-05-19 15:39:36'),
(585, 70, 'check', NULL, NULL, '10.0.0.1', '2025-05-19 18:53:45', '2025-05-19 18:53:45'),
(586, 86, 'verify', NULL, NULL, '8.8.8.8', '2025-05-19 20:20:15', '2025-05-19 20:20:15'),
(587, 30, 'verify', NULL, NULL, '172.16.0.1', '2025-05-19 18:58:08', '2025-05-19 18:58:08'),
(588, 72, 'check', NULL, NULL, '10.0.0.1', '2025-05-19 19:21:29', '2025-05-19 19:21:29'),
(589, 31, 'check', NULL, NULL, '172.16.0.1', '2025-05-19 14:47:51', '2025-05-19 14:47:51'),
(590, 104, 'check', NULL, NULL, '192.168.1.1', '2025-05-19 14:24:18', '2025-05-19 14:24:18'),
(591, 6, 'check', NULL, NULL, '10.0.0.1', '2025-05-19 17:47:49', '2025-05-19 17:47:49'),
(592, 56, 'validation', NULL, NULL, '192.168.1.1', '2025-05-19 19:45:30', '2025-05-19 19:45:30'),
(593, 42, 'check', NULL, NULL, '172.16.0.1', '2025-05-19 16:59:23', '2025-05-19 16:59:23'),
(594, 21, 'check', NULL, NULL, '8.8.8.8', '2025-05-19 16:29:57', '2025-05-19 16:29:57'),
(595, 50, 'activation', NULL, NULL, '192.168.1.1', '2025-05-19 21:52:40', '2025-05-19 21:52:40'),
(596, 40, 'validation', NULL, NULL, '192.168.1.1', '2025-05-19 09:47:56', '2025-05-19 09:47:56'),
(597, 81, 'check', NULL, NULL, '192.168.1.1', '2025-05-19 18:32:37', '2025-05-19 18:32:37'),
(598, 32, 'verify', NULL, NULL, '192.168.1.1', '2025-05-19 08:22:49', '2025-05-19 08:22:49'),
(599, 95, 'validation', NULL, NULL, '192.168.1.1', '2025-05-19 07:29:15', '2025-05-19 07:29:15'),
(600, 79, 'verify', NULL, NULL, '10.0.0.1', '2025-05-19 21:54:07', '2025-05-19 21:54:07'),
(601, 66, 'activation', NULL, NULL, '8.8.8.8', '2025-05-19 16:26:20', '2025-05-19 16:26:20'),
(602, 60, 'validation', NULL, NULL, '172.16.0.1', '2025-05-19 18:35:33', '2025-05-19 18:35:33'),
(603, 34, 'validation', NULL, NULL, '10.0.0.1', '2025-05-19 19:43:48', '2025-05-19 19:43:48'),
(604, 107, 'validation', NULL, NULL, '8.8.8.8', '2025-05-19 17:02:03', '2025-05-19 17:02:03'),
(605, 2, 'activation', NULL, NULL, '10.0.0.1', '2025-05-19 14:52:37', '2025-05-19 14:52:37'),
(606, 99, 'validation', NULL, NULL, '8.8.8.8', '2025-05-19 08:58:05', '2025-05-19 08:58:05'),
(607, 109, 'validation', NULL, NULL, '8.8.8.8', '2025-05-19 08:05:58', '2025-05-19 08:05:58'),
(608, 89, 'check', NULL, NULL, '192.168.1.1', '2025-05-19 20:33:22', '2025-05-19 20:33:22'),
(609, 74, 'check', NULL, NULL, '192.168.1.1', '2025-05-19 16:07:39', '2025-05-19 16:07:39'),
(610, 94, 'verify', NULL, NULL, '172.16.0.1', '2025-05-20 20:14:13', '2025-05-20 20:14:13'),
(611, 32, 'validation', NULL, NULL, '8.8.8.8', '2025-05-20 07:32:00', '2025-05-20 07:32:00'),
(612, 85, 'activation', NULL, NULL, '192.168.1.1', '2025-05-20 15:11:02', '2025-05-20 15:11:02'),
(613, 10, 'check', NULL, NULL, '192.168.1.1', '2025-05-20 09:18:20', '2025-05-20 09:18:20'),
(614, 46, 'verify', NULL, NULL, '172.16.0.1', '2025-05-20 17:44:51', '2025-05-20 17:44:51'),
(615, 83, 'check', NULL, NULL, '172.16.0.1', '2025-05-20 15:49:38', '2025-05-20 15:49:38'),
(616, 51, 'check', NULL, NULL, '172.16.0.1', '2025-05-20 08:58:08', '2025-05-20 08:58:08'),
(617, 94, 'verify', NULL, NULL, '172.16.0.1', '2025-05-20 11:02:25', '2025-05-20 11:02:25'),
(618, 52, 'verify', NULL, NULL, '8.8.8.8', '2025-05-20 21:11:56', '2025-05-20 21:11:56'),
(619, 39, 'verify', NULL, NULL, '10.0.0.1', '2025-05-20 08:09:44', '2025-05-20 08:09:44'),
(620, 60, 'validation', NULL, NULL, '10.0.0.1', '2025-05-20 14:25:12', '2025-05-20 14:25:12'),
(621, 52, 'verify', NULL, NULL, '8.8.8.8', '2025-05-20 14:32:03', '2025-05-20 14:32:03'),
(622, 8, 'verify', NULL, NULL, '10.0.0.1', '2025-05-20 20:58:42', '2025-05-20 20:58:42'),
(623, 8, 'activation', NULL, NULL, '8.8.8.8', '2025-05-20 06:41:06', '2025-05-20 06:41:06'),
(624, 98, 'activation', NULL, NULL, '172.16.0.1', '2025-05-20 12:19:13', '2025-05-20 12:19:13'),
(625, 24, 'validation', NULL, NULL, '10.0.0.1', '2025-05-20 21:36:50', '2025-05-20 21:36:50'),
(626, 88, 'verify', NULL, NULL, '192.168.1.1', '2025-05-20 11:01:57', '2025-05-20 11:01:57'),
(627, 59, 'verify', NULL, NULL, '8.8.8.8', '2025-05-20 19:48:24', '2025-05-20 19:48:24'),
(628, 65, 'verify', NULL, NULL, '10.0.0.1', '2025-05-20 15:54:58', '2025-05-20 15:54:58'),
(629, 2, 'check', NULL, NULL, '10.0.0.1', '2025-05-20 11:22:59', '2025-05-20 11:22:59'),
(630, 60, 'validation', NULL, NULL, '172.16.0.1', '2025-05-20 20:13:56', '2025-05-20 20:13:56'),
(631, 103, 'activation', NULL, NULL, '10.0.0.1', '2025-05-20 06:14:39', '2025-05-20 06:14:39'),
(632, 97, 'verify', NULL, NULL, '10.0.0.1', '2025-05-20 20:36:51', '2025-05-20 20:36:51'),
(633, 56, 'validation', NULL, NULL, '8.8.8.8', '2025-05-20 18:28:06', '2025-05-20 18:28:06'),
(634, 69, 'verify', NULL, NULL, '8.8.8.8', '2025-05-20 21:41:06', '2025-05-20 21:41:06'),
(635, 49, 'check', NULL, NULL, '10.0.0.1', '2025-05-21 19:33:47', '2025-05-21 19:33:47'),
(636, 72, 'check', NULL, NULL, '172.16.0.1', '2025-05-21 08:46:36', '2025-05-21 08:46:36'),
(637, 79, 'verify', NULL, NULL, '172.16.0.1', '2025-05-21 11:32:00', '2025-05-21 11:32:00'),
(638, 64, 'verify', NULL, NULL, '172.16.0.1', '2025-05-21 21:55:32', '2025-05-21 21:55:32'),
(639, 49, 'verify', NULL, NULL, '8.8.8.8', '2025-05-21 19:15:11', '2025-05-21 19:15:11'),
(640, 22, 'check', NULL, NULL, '172.16.0.1', '2025-05-21 17:33:36', '2025-05-21 17:33:36'),
(641, 110, 'verify', NULL, NULL, '192.168.1.1', '2025-05-21 10:31:39', '2025-05-21 10:31:39'),
(642, 96, 'validation', NULL, NULL, '172.16.0.1', '2025-05-21 10:45:12', '2025-05-21 10:45:12'),
(643, 99, 'check', NULL, NULL, '192.168.1.1', '2025-05-21 11:02:18', '2025-05-21 11:02:18'),
(644, 72, 'validation', NULL, NULL, '192.168.1.1', '2025-05-21 12:55:34', '2025-05-21 12:55:34'),
(645, 91, 'validation', NULL, NULL, '192.168.1.1', '2025-05-21 10:19:54', '2025-05-21 10:19:54'),
(646, 90, 'activation', NULL, NULL, '10.0.0.1', '2025-05-21 08:16:58', '2025-05-21 08:16:58'),
(647, 7, 'verify', NULL, NULL, '10.0.0.1', '2025-05-21 15:27:39', '2025-05-21 15:27:39'),
(648, 77, 'activation', NULL, NULL, '172.16.0.1', '2025-05-21 19:45:58', '2025-05-21 19:45:58'),
(649, 85, 'check', NULL, NULL, '8.8.8.8', '2025-05-21 17:08:33', '2025-05-21 17:08:33'),
(650, 4, 'validation', NULL, NULL, '10.0.0.1', '2025-05-21 09:47:42', '2025-05-21 09:47:42'),
(651, 56, 'activation', NULL, NULL, '192.168.1.1', '2025-05-21 14:14:00', '2025-05-21 14:14:00'),
(652, 69, 'verify', NULL, NULL, '8.8.8.8', '2025-05-21 10:21:37', '2025-05-21 10:21:37'),
(653, 94, 'validation', NULL, NULL, '10.0.0.1', '2025-05-21 12:56:37', '2025-05-21 12:56:37'),
(654, 65, 'validation', NULL, NULL, '172.16.0.1', '2025-05-21 06:36:16', '2025-05-21 06:36:16'),
(655, 109, 'activation', NULL, NULL, '8.8.8.8', '2025-05-21 06:10:53', '2025-05-21 06:10:53'),
(656, 98, 'check', NULL, NULL, '10.0.0.1', '2025-05-21 10:44:26', '2025-05-21 10:44:26'),
(657, 80, 'check', NULL, NULL, '8.8.8.8', '2025-05-21 14:11:58', '2025-05-21 14:11:58'),
(658, 31, 'validation', NULL, NULL, '172.16.0.1', '2025-05-21 15:22:19', '2025-05-21 15:22:19'),
(659, 43, 'check', NULL, NULL, '8.8.8.8', '2025-05-21 14:17:15', '2025-05-21 14:17:15'),
(660, 63, 'activation', NULL, NULL, '172.16.0.1', '2025-05-21 10:39:07', '2025-05-21 10:39:07'),
(661, 85, 'check', NULL, NULL, '8.8.8.8', '2025-05-21 21:12:39', '2025-05-21 21:12:39'),
(662, 87, 'activation', NULL, NULL, '10.0.0.1', '2025-05-21 13:17:57', '2025-05-21 13:17:57'),
(663, 43, 'activation', NULL, NULL, '10.0.0.1', '2025-05-21 12:32:10', '2025-05-21 12:32:10'),
(664, 105, 'validation', NULL, NULL, '172.16.0.1', '2025-05-21 07:59:26', '2025-05-21 07:59:26'),
(665, 25, 'validation', NULL, NULL, '172.16.0.1', '2025-05-21 14:20:50', '2025-05-21 14:20:50'),
(666, 86, 'validation', NULL, NULL, '10.0.0.1', '2025-05-21 08:10:48', '2025-05-21 08:10:48'),
(667, 22, 'activation', NULL, NULL, '172.16.0.1', '2025-05-21 12:59:33', '2025-05-21 12:59:33'),
(668, 105, 'activation', NULL, NULL, '8.8.8.8', '2025-05-21 21:08:37', '2025-05-21 21:08:37'),
(669, 93, 'verify', NULL, NULL, '10.0.0.1', '2025-05-21 11:48:27', '2025-05-21 11:48:27'),
(670, 56, 'activation', NULL, NULL, '10.0.0.1', '2025-05-21 06:03:58', '2025-05-21 06:03:58'),
(671, 23, 'check', NULL, NULL, '192.168.1.1', '2025-05-21 15:05:24', '2025-05-21 15:05:24'),
(672, 36, 'verify', NULL, NULL, '172.16.0.1', '2025-05-21 08:27:31', '2025-05-21 08:27:31'),
(673, 86, 'check', NULL, NULL, '172.16.0.1', '2025-05-21 21:06:36', '2025-05-21 21:06:36'),
(674, 77, 'activation', NULL, NULL, '172.16.0.1', '2025-05-21 14:53:48', '2025-05-21 14:53:48'),
(675, 81, 'check', NULL, NULL, '192.168.1.1', '2025-05-21 11:49:02', '2025-05-21 11:49:02'),
(676, 27, 'activation', NULL, NULL, '192.168.1.1', '2025-05-21 15:49:34', '2025-05-21 15:49:34'),
(677, 35, 'validation', NULL, NULL, '192.168.1.1', '2025-05-21 06:06:21', '2025-05-21 06:06:21'),
(678, 52, 'validation', NULL, NULL, '8.8.8.8', '2025-05-21 15:38:57', '2025-05-21 15:38:57'),
(679, 98, 'verify', NULL, NULL, '10.0.0.1', '2025-05-21 08:33:09', '2025-05-21 08:33:09'),
(680, 49, 'validation', NULL, NULL, '172.16.0.1', '2025-05-21 21:45:10', '2025-05-21 21:45:10'),
(681, 74, 'verify', NULL, NULL, '8.8.8.8', '2025-05-21 10:31:59', '2025-05-21 10:31:59'),
(682, 86, 'check', NULL, NULL, '172.16.0.1', '2025-05-21 16:45:41', '2025-05-21 16:45:41'),
(683, 81, 'check', NULL, NULL, '10.0.0.1', '2025-05-21 08:02:13', '2025-05-21 08:02:13'),
(684, 4, 'activation', NULL, NULL, '10.0.0.1', '2025-05-21 07:59:19', '2025-05-21 07:59:19'),
(685, 27, 'check', NULL, NULL, '8.8.8.8', '2025-05-21 19:37:42', '2025-05-21 19:37:42'),
(686, 94, 'check', NULL, NULL, '10.0.0.1', '2025-05-21 06:48:27', '2025-05-21 06:48:27'),
(687, 99, 'check', NULL, NULL, '10.0.0.1', '2025-05-21 21:07:58', '2025-05-21 21:07:58'),
(688, 73, 'activation', NULL, NULL, '192.168.1.1', '2025-05-21 15:12:31', '2025-05-21 15:12:31'),
(689, 97, 'verify', NULL, NULL, '172.16.0.1', '2025-05-21 17:53:22', '2025-05-21 17:53:22'),
(690, 102, 'activation', NULL, NULL, '172.16.0.1', '2025-05-21 18:41:26', '2025-05-21 18:41:26'),
(691, 46, 'verify', NULL, NULL, '8.8.8.8', '2025-05-21 20:56:11', '2025-05-21 20:56:11'),
(692, 35, 'check', NULL, NULL, '192.168.1.1', '2025-05-21 11:55:20', '2025-05-21 11:55:20'),
(693, 73, 'activation', NULL, NULL, '8.8.8.8', '2025-05-21 16:37:44', '2025-05-21 16:37:44'),
(694, 56, 'verify', NULL, NULL, '192.168.1.1', '2025-05-21 18:16:58', '2025-05-21 18:16:58'),
(695, 34, 'activation', NULL, NULL, '172.16.0.1', '2025-05-21 13:36:49', '2025-05-21 13:36:49'),
(696, 86, 'check', NULL, NULL, '172.16.0.1', '2025-05-21 15:04:36', '2025-05-21 15:04:36'),
(697, 83, 'check', NULL, NULL, '8.8.8.8', '2025-05-21 07:25:04', '2025-05-21 07:25:04'),
(698, 64, 'validation', NULL, NULL, '172.16.0.1', '2025-05-21 21:54:37', '2025-05-21 21:54:37'),
(699, 88, 'check', NULL, NULL, '172.16.0.1', '2025-05-21 20:00:09', '2025-05-21 20:00:09'),
(700, 98, 'activation', NULL, NULL, '8.8.8.8', '2025-05-22 12:21:14', '2025-05-22 12:21:14'),
(701, 44, 'validation', NULL, NULL, '192.168.1.1', '2025-05-22 16:39:58', '2025-05-22 16:39:58'),
(702, 22, 'validation', NULL, NULL, '8.8.8.8', '2025-05-22 14:03:14', '2025-05-22 14:03:14'),
(703, 60, 'verify', NULL, NULL, '10.0.0.1', '2025-05-22 12:20:25', '2025-05-22 12:20:25'),
(704, 70, 'activation', NULL, NULL, '8.8.8.8', '2025-05-22 07:47:30', '2025-05-22 07:47:30'),
(705, 91, 'activation', NULL, NULL, '192.168.1.1', '2025-05-22 20:25:08', '2025-05-22 20:25:08'),
(706, 62, 'verify', NULL, NULL, '192.168.1.1', '2025-05-22 13:39:56', '2025-05-22 13:39:56'),
(707, 58, 'check', NULL, NULL, '192.168.1.1', '2025-05-22 13:56:26', '2025-05-22 13:56:26'),
(708, 97, 'validation', NULL, NULL, '172.16.0.1', '2025-05-22 09:07:48', '2025-05-22 09:07:48'),
(709, 107, 'check', NULL, NULL, '8.8.8.8', '2025-05-22 06:55:54', '2025-05-22 06:55:54'),
(710, 78, 'validation', NULL, NULL, '8.8.8.8', '2025-05-22 17:55:03', '2025-05-22 17:55:03'),
(711, 101, 'activation', NULL, NULL, '192.168.1.1', '2025-05-22 09:11:03', '2025-05-22 09:11:03'),
(712, 106, 'validation', NULL, NULL, '8.8.8.8', '2025-05-22 11:37:54', '2025-05-22 11:37:54'),
(713, 85, 'verify', NULL, NULL, '172.16.0.1', '2025-05-22 09:39:08', '2025-05-22 09:39:08'),
(714, 73, 'activation', NULL, NULL, '172.16.0.1', '2025-05-22 21:30:56', '2025-05-22 21:30:56'),
(715, 47, 'verify', NULL, NULL, '8.8.8.8', '2025-05-22 15:43:18', '2025-05-22 15:43:18'),
(716, 101, 'validation', NULL, NULL, '10.0.0.1', '2025-05-22 07:53:15', '2025-05-22 07:53:15'),
(717, 56, 'activation', NULL, NULL, '172.16.0.1', '2025-05-22 12:10:59', '2025-05-22 12:10:59'),
(718, 71, 'validation', NULL, NULL, '10.0.0.1', '2025-05-22 15:16:40', '2025-05-22 15:16:40'),
(719, 97, 'check', NULL, NULL, '8.8.8.8', '2025-05-22 09:35:02', '2025-05-22 09:35:02'),
(720, 97, 'validation', NULL, NULL, '172.16.0.1', '2025-05-22 13:19:40', '2025-05-22 13:19:40'),
(721, 35, 'activation', NULL, NULL, '172.16.0.1', '2025-05-22 16:19:29', '2025-05-22 16:19:29'),
(722, 56, 'activation', NULL, NULL, '192.168.1.1', '2025-05-22 21:04:29', '2025-05-22 21:04:29'),
(723, 88, 'check', NULL, NULL, '192.168.1.1', '2025-05-22 17:06:53', '2025-05-22 17:06:53'),
(724, 72, 'check', NULL, NULL, '192.168.1.1', '2025-05-22 17:20:35', '2025-05-22 17:20:35'),
(725, 50, 'verify', NULL, NULL, '192.168.1.1', '2025-05-22 18:44:44', '2025-05-22 18:44:44'),
(726, 88, 'activation', NULL, NULL, '172.16.0.1', '2025-05-22 08:00:24', '2025-05-22 08:00:24'),
(727, 84, 'validation', NULL, NULL, '10.0.0.1', '2025-05-22 09:15:05', '2025-05-22 09:15:05'),
(728, 97, 'validation', NULL, NULL, '192.168.1.1', '2025-05-22 09:39:15', '2025-05-22 09:39:15'),
(729, 36, 'activation', NULL, NULL, '10.0.0.1', '2025-05-22 11:13:02', '2025-05-22 11:13:02'),
(730, 49, 'verify', NULL, NULL, '172.16.0.1', '2025-05-22 06:50:28', '2025-05-22 06:50:28'),
(731, 6, 'check', NULL, NULL, '10.0.0.1', '2025-05-22 16:34:12', '2025-05-22 16:34:12'),
(732, 101, 'verify', NULL, NULL, '172.16.0.1', '2025-05-22 16:58:51', '2025-05-22 16:58:51'),
(733, 34, 'activation', NULL, NULL, '172.16.0.1', '2025-05-22 17:36:37', '2025-05-22 17:36:37'),
(734, 96, 'verify', NULL, NULL, '8.8.8.8', '2025-05-22 07:01:31', '2025-05-22 07:01:31'),
(735, 92, 'check', NULL, NULL, '8.8.8.8', '2025-05-22 09:17:52', '2025-05-22 09:17:52'),
(736, 86, 'check', NULL, NULL, '10.0.0.1', '2025-05-22 17:03:54', '2025-05-22 17:03:54'),
(737, 32, 'check', NULL, NULL, '192.168.1.1', '2025-05-22 08:25:23', '2025-05-22 08:25:23'),
(738, 36, 'validation', NULL, NULL, '8.8.8.8', '2025-05-22 14:36:58', '2025-05-22 14:36:58'),
(739, 80, 'validation', NULL, NULL, '192.168.1.1', '2025-05-22 11:47:21', '2025-05-22 11:47:21'),
(740, 46, 'validation', NULL, NULL, '192.168.1.1', '2025-05-23 11:21:17', '2025-05-23 11:21:17'),
(741, 7, 'verify', NULL, NULL, '8.8.8.8', '2025-05-23 07:39:39', '2025-05-23 07:39:39'),
(742, 87, 'verify', NULL, NULL, '8.8.8.8', '2025-05-23 21:37:14', '2025-05-23 21:37:14'),
(743, 39, 'verify', NULL, NULL, '8.8.8.8', '2025-05-23 18:39:52', '2025-05-23 18:39:52'),
(744, 26, 'validation', NULL, NULL, '192.168.1.1', '2025-05-23 12:56:25', '2025-05-23 12:56:25'),
(745, 8, 'validation', NULL, NULL, '172.16.0.1', '2025-05-23 11:21:19', '2025-05-23 11:21:19'),
(746, 91, 'validation', NULL, NULL, '172.16.0.1', '2025-05-23 07:24:14', '2025-05-23 07:24:14'),
(747, 24, 'verify', NULL, NULL, '8.8.8.8', '2025-05-23 08:20:25', '2025-05-23 08:20:25'),
(748, 105, 'activation', NULL, NULL, '172.16.0.1', '2025-05-23 07:49:25', '2025-05-23 07:49:25'),
(749, 35, 'validation', NULL, NULL, '8.8.8.8', '2025-05-23 11:21:22', '2025-05-23 11:21:22'),
(750, 106, 'validation', NULL, NULL, '172.16.0.1', '2025-05-23 12:28:09', '2025-05-23 12:28:09'),
(751, 107, 'verify', NULL, NULL, '10.0.0.1', '2025-05-23 06:12:19', '2025-05-23 06:12:19'),
(752, 64, 'check', NULL, NULL, '172.16.0.1', '2025-05-23 07:12:22', '2025-05-23 07:12:22'),
(753, 9, 'check', NULL, NULL, '192.168.1.1', '2025-05-23 17:43:00', '2025-05-23 17:43:00'),
(754, 72, 'validation', NULL, NULL, '10.0.0.1', '2025-05-23 07:15:35', '2025-05-23 07:15:35'),
(755, 91, 'validation', NULL, NULL, '10.0.0.1', '2025-05-23 18:27:02', '2025-05-23 18:27:02'),
(756, 50, 'activation', NULL, NULL, '192.168.1.1', '2025-05-23 14:31:35', '2025-05-23 14:31:35'),
(757, 54, 'verify', NULL, NULL, '8.8.8.8', '2025-05-23 08:22:38', '2025-05-23 08:22:38'),
(758, 109, 'activation', NULL, NULL, '8.8.8.8', '2025-05-23 15:18:29', '2025-05-23 15:18:29'),
(759, 55, 'verify', NULL, NULL, '8.8.8.8', '2025-05-23 19:09:47', '2025-05-23 19:09:47'),
(760, 31, 'activation', NULL, NULL, '192.168.1.1', '2025-05-23 18:56:47', '2025-05-23 18:56:47'),
(761, 64, 'verify', NULL, NULL, '172.16.0.1', '2025-05-23 13:44:24', '2025-05-23 13:44:24'),
(762, 25, 'validation', NULL, NULL, '192.168.1.1', '2025-05-23 10:14:45', '2025-05-23 10:14:45'),
(763, 85, 'activation', NULL, NULL, '8.8.8.8', '2025-05-23 18:17:37', '2025-05-23 18:17:37'),
(764, 34, 'activation', NULL, NULL, '8.8.8.8', '2025-05-23 13:27:00', '2025-05-23 13:27:00'),
(765, 10, 'activation', NULL, NULL, '10.0.0.1', '2025-05-23 07:26:44', '2025-05-23 07:26:44'),
(766, 106, 'check', NULL, NULL, '192.168.1.1', '2025-05-23 17:40:20', '2025-05-23 17:40:20'),
(767, 46, 'validation', NULL, NULL, '172.16.0.1', '2025-05-23 17:21:51', '2025-05-23 17:21:51'),
(768, 61, 'activation', NULL, NULL, '172.16.0.1', '2025-05-23 14:35:43', '2025-05-23 14:35:43'),
(769, 10, 'verify', NULL, NULL, '10.0.0.1', '2025-05-23 07:16:29', '2025-05-23 07:16:29'),
(770, 23, 'activation', NULL, NULL, '10.0.0.1', '2025-05-23 10:13:15', '2025-05-23 10:13:15'),
(771, 51, 'activation', NULL, NULL, '192.168.1.1', '2025-05-23 09:35:15', '2025-05-23 09:35:15'),
(772, 51, 'validation', NULL, NULL, '172.16.0.1', '2025-05-23 14:38:46', '2025-05-23 14:38:46'),
(773, 71, 'check', NULL, NULL, '172.16.0.1', '2025-05-23 17:25:25', '2025-05-23 17:25:25'),
(774, 90, 'verify', NULL, NULL, '10.0.0.1', '2025-05-23 12:24:43', '2025-05-23 12:24:43'),
(775, 27, 'validation', NULL, NULL, '8.8.8.8', '2025-05-23 12:08:23', '2025-05-23 12:08:23'),
(776, 74, 'verify', NULL, NULL, '10.0.0.1', '2025-05-23 19:10:27', '2025-05-23 19:10:27'),
(777, 27, 'check', NULL, NULL, '10.0.0.1', '2025-05-23 13:41:32', '2025-05-23 13:41:32'),
(778, 10, 'check', NULL, NULL, '172.16.0.1', '2025-05-23 20:24:41', '2025-05-23 20:24:41'),
(779, 60, 'validation', NULL, NULL, '192.168.1.1', '2025-05-23 08:30:09', '2025-05-23 08:30:09'),
(780, 62, 'activation', NULL, NULL, '8.8.8.8', '2025-05-23 20:21:34', '2025-05-23 20:21:34'),
(781, 5, 'validation', NULL, NULL, '8.8.8.8', '2025-05-23 13:23:34', '2025-05-23 13:23:34'),
(782, 60, 'activation', NULL, NULL, '172.16.0.1', '2025-05-23 15:35:42', '2025-05-23 15:35:42'),
(783, 44, 'check', NULL, NULL, '192.168.1.1', '2025-05-23 17:31:53', '2025-05-23 17:31:53'),
(784, 52, 'verify', NULL, NULL, '10.0.0.1', '2025-05-23 15:40:54', '2025-05-23 15:40:54'),
(785, 100, 'validation', NULL, NULL, '10.0.0.1', '2025-05-23 07:38:16', '2025-05-23 07:38:16'),
(786, 84, 'check', NULL, NULL, '8.8.8.8', '2025-05-23 15:00:35', '2025-05-23 15:00:35'),
(787, 43, 'verify', NULL, NULL, '172.16.0.1', '2025-05-23 19:27:26', '2025-05-23 19:27:26'),
(788, 37, 'verify', NULL, NULL, '172.16.0.1', '2025-05-23 10:30:29', '2025-05-23 10:30:29'),
(789, 95, 'validation', NULL, NULL, '8.8.8.8', '2025-05-23 19:35:09', '2025-05-23 19:35:09'),
(790, 9, 'validation', NULL, NULL, '172.16.0.1', '2025-05-23 14:43:57', '2025-05-23 14:43:57'),
(791, 34, 'check', NULL, NULL, '192.168.1.1', '2025-05-23 19:04:51', '2025-05-23 19:04:51'),
(792, 79, 'verify', NULL, NULL, '192.168.1.1', '2025-05-23 13:42:51', '2025-05-23 13:42:51'),
(793, 66, 'activation', NULL, NULL, '8.8.8.8', '2025-05-23 17:34:29', '2025-05-23 17:34:29'),
(794, 62, 'check', NULL, NULL, '192.168.1.1', '2025-05-23 20:32:02', '2025-05-23 20:32:02'),
(795, 85, 'verify', NULL, NULL, '8.8.8.8', '2025-05-23 20:49:43', '2025-05-23 20:49:43'),
(796, 99, 'activation', NULL, NULL, '172.16.0.1', '2025-05-23 20:26:38', '2025-05-23 20:26:38'),
(797, 110, 'verify', NULL, NULL, '8.8.8.8', '2025-05-23 18:17:42', '2025-05-23 18:17:42'),
(798, 102, 'validation', NULL, NULL, '10.0.0.1', '2025-05-23 06:47:42', '2025-05-23 06:47:42'),
(799, 49, 'activation', NULL, NULL, '8.8.8.8', '2025-05-23 11:44:09', '2025-05-23 11:44:09'),
(800, 54, 'activation', NULL, NULL, '10.0.0.1', '2025-05-23 09:53:50', '2025-05-23 09:53:50'),
(801, 87, 'verify', NULL, NULL, '192.168.1.1', '2025-05-23 16:19:23', '2025-05-23 16:19:23'),
(802, 51, 'validation', NULL, NULL, '192.168.1.1', '2025-05-24 18:56:31', '2025-05-24 18:56:31'),
(803, 104, 'verify', NULL, NULL, '172.16.0.1', '2025-05-24 08:48:12', '2025-05-24 08:48:12'),
(804, 64, 'validation', NULL, NULL, '192.168.1.1', '2025-05-24 17:52:38', '2025-05-24 17:52:38'),
(805, 10, 'validation', NULL, NULL, '192.168.1.1', '2025-05-24 13:22:20', '2025-05-24 13:22:20'),
(806, 83, 'verify', NULL, NULL, '192.168.1.1', '2025-05-24 06:58:53', '2025-05-24 06:58:53'),
(807, 38, 'check', NULL, NULL, '8.8.8.8', '2025-05-24 13:26:29', '2025-05-24 13:26:29'),
(808, 46, 'activation', NULL, NULL, '192.168.1.1', '2025-05-24 19:04:47', '2025-05-24 19:04:47'),
(809, 33, 'verify', NULL, NULL, '8.8.8.8', '2025-05-24 06:39:40', '2025-05-24 06:39:40'),
(810, 59, 'activation', NULL, NULL, '10.0.0.1', '2025-05-24 06:54:39', '2025-05-24 06:54:39'),
(811, 61, 'verify', NULL, NULL, '172.16.0.1', '2025-05-24 08:04:55', '2025-05-24 08:04:55'),
(812, 103, 'verify', NULL, NULL, '8.8.8.8', '2025-05-24 14:20:12', '2025-05-24 14:20:12'),
(813, 82, 'check', NULL, NULL, '8.8.8.8', '2025-05-24 20:05:27', '2025-05-24 20:05:27'),
(814, 85, 'validation', NULL, NULL, '192.168.1.1', '2025-05-24 09:15:52', '2025-05-24 09:15:52'),
(815, 78, 'check', NULL, NULL, '8.8.8.8', '2025-05-24 17:16:37', '2025-05-24 17:16:37'),
(816, 6, 'verify', NULL, NULL, '192.168.1.1', '2025-05-24 09:43:54', '2025-05-24 09:43:54'),
(817, 26, 'validation', NULL, NULL, '192.168.1.1', '2025-05-24 12:39:35', '2025-05-24 12:39:35'),
(818, 1, 'validation', NULL, NULL, '8.8.8.8', '2025-05-24 21:06:53', '2025-05-24 21:06:53'),
(819, 101, 'check', NULL, NULL, '10.0.0.1', '2025-05-24 13:38:43', '2025-05-24 13:38:43'),
(820, 32, 'activation', NULL, NULL, '10.0.0.1', '2025-05-24 13:40:29', '2025-05-24 13:40:29'),
(821, 37, 'activation', NULL, NULL, '10.0.0.1', '2025-05-25 10:00:05', '2025-05-25 10:00:05'),
(822, 106, 'validation', NULL, NULL, '192.168.1.1', '2025-05-25 10:44:59', '2025-05-25 10:44:59'),
(823, 34, 'check', NULL, NULL, '10.0.0.1', '2025-05-25 14:10:37', '2025-05-25 14:10:37'),
(824, 58, 'validation', NULL, NULL, '172.16.0.1', '2025-05-25 14:34:43', '2025-05-25 14:34:43'),
(825, 62, 'activation', NULL, NULL, '192.168.1.1', '2025-05-25 09:43:59', '2025-05-25 09:43:59'),
(826, 30, 'verify', NULL, NULL, '8.8.8.8', '2025-05-25 07:39:19', '2025-05-25 07:39:19'),
(827, 91, 'verify', NULL, NULL, '192.168.1.1', '2025-05-25 12:30:00', '2025-05-25 12:30:00'),
(828, 38, 'check', NULL, NULL, '172.16.0.1', '2025-05-25 12:22:38', '2025-05-25 12:22:38'),
(829, 86, 'check', NULL, NULL, '10.0.0.1', '2025-05-25 13:32:11', '2025-05-25 13:32:11'),
(830, 57, 'check', NULL, NULL, '172.16.0.1', '2025-05-25 10:57:11', '2025-05-25 10:57:11'),
(831, 60, 'check', NULL, NULL, '192.168.1.1', '2025-05-25 09:38:09', '2025-05-25 09:38:09'),
(832, 6, 'activation', NULL, NULL, '8.8.8.8', '2025-05-25 08:08:31', '2025-05-25 08:08:31'),
(833, 82, 'validation', NULL, NULL, '192.168.1.1', '2025-05-25 14:16:53', '2025-05-25 14:16:53'),
(834, 68, 'activation', NULL, NULL, '8.8.8.8', '2025-05-25 06:33:27', '2025-05-25 06:33:27'),
(835, 40, 'validation', NULL, NULL, '10.0.0.1', '2025-05-26 13:16:47', '2025-05-26 13:16:47'),
(836, 1, 'activation', NULL, NULL, '192.168.1.1', '2025-05-26 10:10:19', '2025-05-26 10:10:19'),
(837, 99, 'activation', NULL, NULL, '8.8.8.8', '2025-05-26 17:10:52', '2025-05-26 17:10:52'),
(838, 35, 'check', NULL, NULL, '192.168.1.1', '2025-05-26 15:15:49', '2025-05-26 15:15:49'),
(839, 4, 'check', NULL, NULL, '10.0.0.1', '2025-05-26 07:27:48', '2025-05-26 07:27:48'),
(840, 36, 'activation', NULL, NULL, '192.168.1.1', '2025-05-26 20:18:38', '2025-05-26 20:18:38'),
(841, 40, 'check', NULL, NULL, '172.16.0.1', '2025-05-26 13:45:54', '2025-05-26 13:45:54'),
(842, 6, 'check', NULL, NULL, '8.8.8.8', '2025-05-26 12:59:23', '2025-05-26 12:59:23'),
(843, 39, 'validation', NULL, NULL, '10.0.0.1', '2025-05-26 08:30:29', '2025-05-26 08:30:29'),
(844, 107, 'validation', NULL, NULL, '172.16.0.1', '2025-05-26 06:26:50', '2025-05-26 06:26:50'),
(845, 94, 'check', NULL, NULL, '10.0.0.1', '2025-05-26 09:25:14', '2025-05-26 09:25:14'),
(846, 84, 'verify', NULL, NULL, '8.8.8.8', '2025-05-26 16:31:37', '2025-05-26 16:31:37'),
(847, 47, 'check', NULL, NULL, '8.8.8.8', '2025-05-26 14:54:27', '2025-05-26 14:54:27'),
(848, 55, 'validation', NULL, NULL, '8.8.8.8', '2025-05-26 21:58:56', '2025-05-26 21:58:56'),
(849, 43, 'validation', NULL, NULL, '8.8.8.8', '2025-05-26 16:16:05', '2025-05-26 16:16:05'),
(850, 52, 'verify', NULL, NULL, '10.0.0.1', '2025-05-26 09:17:30', '2025-05-26 09:17:30'),
(851, 57, 'validation', NULL, NULL, '192.168.1.1', '2025-05-26 06:10:55', '2025-05-26 06:10:55'),
(852, 68, 'check', NULL, NULL, '10.0.0.1', '2025-05-26 18:55:11', '2025-05-26 18:55:11'),
(853, 104, 'check', NULL, NULL, '172.16.0.1', '2025-05-26 17:36:54', '2025-05-26 17:36:54'),
(854, 55, 'check', NULL, NULL, '192.168.1.1', '2025-05-26 20:00:42', '2025-05-26 20:00:42'),
(855, 77, 'verify', NULL, NULL, '192.168.1.1', '2025-05-26 12:30:30', '2025-05-26 12:30:30'),
(856, 104, 'validation', NULL, NULL, '192.168.1.1', '2025-05-26 16:23:03', '2025-05-26 16:23:03'),
(857, 59, 'check', NULL, NULL, '10.0.0.1', '2025-05-26 12:06:04', '2025-05-26 12:06:04'),
(858, 78, 'check', NULL, NULL, '172.16.0.1', '2025-05-26 15:13:40', '2025-05-26 15:13:40'),
(859, 58, 'activation', NULL, NULL, '8.8.8.8', '2025-05-26 07:43:30', '2025-05-26 07:43:30'),
(860, 26, 'activation', NULL, NULL, '10.0.0.1', '2025-05-26 14:59:20', '2025-05-26 14:59:20'),
(861, 28, 'validation', NULL, NULL, '192.168.1.1', '2025-05-26 07:47:00', '2025-05-26 07:47:00'),
(862, 73, 'validation', NULL, NULL, '192.168.1.1', '2025-05-26 06:13:01', '2025-05-26 06:13:01'),
(863, 2, 'activation', NULL, NULL, '172.16.0.1', '2025-05-26 06:27:40', '2025-05-26 06:27:40'),
(864, 90, 'activation', NULL, NULL, '172.16.0.1', '2025-05-26 14:25:17', '2025-05-26 14:25:17'),
(865, 106, 'activation', NULL, NULL, '192.168.1.1', '2025-05-26 07:59:25', '2025-05-26 07:59:25'),
(866, 37, 'check', NULL, NULL, '192.168.1.1', '2025-05-26 09:48:29', '2025-05-26 09:48:29'),
(867, 92, 'check', NULL, NULL, '192.168.1.1', '2025-05-26 18:51:37', '2025-05-26 18:51:37'),
(868, 2, 'activation', NULL, NULL, '172.16.0.1', '2025-05-27 09:12:11', '2025-05-27 09:12:11'),
(869, 76, 'validation', NULL, NULL, '8.8.8.8', '2025-05-27 14:21:21', '2025-05-27 14:21:21'),
(870, 99, 'activation', NULL, NULL, '192.168.1.1', '2025-05-27 10:26:49', '2025-05-27 10:26:49'),
(871, 103, 'verify', NULL, NULL, '8.8.8.8', '2025-05-27 12:21:45', '2025-05-27 12:21:45'),
(872, 85, 'activation', NULL, NULL, '172.16.0.1', '2025-05-27 21:41:53', '2025-05-27 21:41:53'),
(873, 103, 'verify', NULL, NULL, '192.168.1.1', '2025-05-27 13:41:29', '2025-05-27 13:41:29'),
(874, 1, 'verify', NULL, NULL, '10.0.0.1', '2025-05-27 08:16:24', '2025-05-27 08:16:24'),
(875, 36, 'check', NULL, NULL, '172.16.0.1', '2025-05-27 15:54:23', '2025-05-27 15:54:23'),
(876, 74, 'verify', NULL, NULL, '10.0.0.1', '2025-05-27 21:24:55', '2025-05-27 21:24:55'),
(877, 67, 'activation', NULL, NULL, '8.8.8.8', '2025-05-27 06:18:54', '2025-05-27 06:18:54'),
(878, 26, 'validation', NULL, NULL, '10.0.0.1', '2025-05-27 20:32:38', '2025-05-27 20:32:38'),
(879, 87, 'check', NULL, NULL, '192.168.1.1', '2025-05-27 06:10:56', '2025-05-27 06:10:56'),
(880, 89, 'verify', NULL, NULL, '8.8.8.8', '2025-05-27 10:30:48', '2025-05-27 10:30:48'),
(881, 6, 'verify', NULL, NULL, '192.168.1.1', '2025-05-27 17:59:14', '2025-05-27 17:59:14'),
(882, 50, 'verify', NULL, NULL, '192.168.1.1', '2025-05-27 16:19:21', '2025-05-27 16:19:21'),
(883, 30, 'activation', NULL, NULL, '172.16.0.1', '2025-05-27 11:02:36', '2025-05-27 11:02:36'),
(884, 94, 'check', NULL, NULL, '10.0.0.1', '2025-05-27 10:20:03', '2025-05-27 10:20:03'),
(885, 99, 'validation', NULL, NULL, '8.8.8.8', '2025-05-27 18:34:07', '2025-05-27 18:34:07'),
(886, 63, 'validation', NULL, NULL, '192.168.1.1', '2025-05-27 12:11:31', '2025-05-27 12:11:31'),
(887, 31, 'validation', NULL, NULL, '8.8.8.8', '2025-05-27 20:10:55', '2025-05-27 20:10:55'),
(888, 53, 'activation', NULL, NULL, '192.168.1.1', '2025-05-27 17:34:00', '2025-05-27 17:34:00'),
(889, 70, 'check', NULL, NULL, '8.8.8.8', '2025-05-27 11:18:46', '2025-05-27 11:18:46'),
(890, 32, 'activation', NULL, NULL, '10.0.0.1', '2025-05-27 16:33:46', '2025-05-27 16:33:46'),
(891, 66, 'check', NULL, NULL, '10.0.0.1', '2025-05-27 13:30:18', '2025-05-27 13:30:18'),
(892, 43, 'check', NULL, NULL, '192.168.1.1', '2025-05-27 17:54:10', '2025-05-27 17:54:10'),
(893, 106, 'check', NULL, NULL, '8.8.8.8', '2025-05-27 12:03:37', '2025-05-27 12:03:37'),
(894, 45, 'validation', NULL, NULL, '8.8.8.8', '2025-05-27 21:32:27', '2025-05-27 21:32:27'),
(895, 64, 'activation', NULL, NULL, '8.8.8.8', '2025-05-27 09:35:19', '2025-05-27 09:35:19'),
(896, 102, 'activation', NULL, NULL, '10.0.0.1', '2025-05-27 06:47:47', '2025-05-27 06:47:47'),
(897, 107, 'validation', NULL, NULL, '172.16.0.1', '2025-05-27 07:53:43', '2025-05-27 07:53:43'),
(898, 80, 'check', NULL, NULL, '10.0.0.1', '2025-05-27 15:00:53', '2025-05-27 15:00:53'),
(899, 95, 'verify', NULL, NULL, '192.168.1.1', '2025-05-27 06:33:28', '2025-05-27 06:33:28'),
(900, 24, 'validation', NULL, NULL, '192.168.1.1', '2025-05-27 21:05:46', '2025-05-27 21:05:46'),
(901, 74, 'check', NULL, NULL, '8.8.8.8', '2025-05-27 13:23:12', '2025-05-27 13:23:12'),
(902, 5, 'verify', NULL, NULL, '10.0.0.1', '2025-05-27 18:39:31', '2025-05-27 18:39:31'),
(903, 36, 'verify', NULL, NULL, '172.16.0.1', '2025-05-27 21:19:43', '2025-05-27 21:19:43'),
(904, 97, 'validation', NULL, NULL, '8.8.8.8', '2025-05-27 12:02:19', '2025-05-27 12:02:19'),
(905, 64, 'check', NULL, NULL, '172.16.0.1', '2025-05-27 08:30:05', '2025-05-27 08:30:05'),
(906, 109, 'check', NULL, NULL, '192.168.1.1', '2025-05-27 07:44:45', '2025-05-27 07:44:45'),
(907, 99, 'check', NULL, NULL, '172.16.0.1', '2025-05-27 06:34:31', '2025-05-27 06:34:31'),
(908, 97, 'validation', NULL, NULL, '10.0.0.1', '2025-05-27 07:37:57', '2025-05-27 07:37:57'),
(909, 69, 'verify', NULL, NULL, '192.168.1.1', '2025-05-27 12:44:43', '2025-05-27 12:44:43'),
(910, 49, 'check', NULL, NULL, '192.168.1.1', '2025-05-27 09:09:07', '2025-05-27 09:09:07'),
(911, 42, 'validation', NULL, NULL, '10.0.0.1', '2025-05-27 18:50:21', '2025-05-27 18:50:21'),
(912, 63, 'validation', NULL, NULL, '172.16.0.1', '2025-05-27 17:35:28', '2025-05-27 17:35:28'),
(913, 64, 'verify', NULL, NULL, '192.168.1.1', '2025-05-27 09:38:32', '2025-05-27 09:38:32'),
(914, 62, 'activation', NULL, NULL, '172.16.0.1', '2025-05-27 18:08:42', '2025-05-27 18:08:42'),
(915, 46, 'check', NULL, NULL, '192.168.1.1', '2025-05-27 11:07:00', '2025-05-27 11:07:00'),
(916, 45, 'verify', NULL, NULL, '192.168.1.1', '2025-05-27 10:31:40', '2025-05-27 10:31:40'),
(917, 51, 'validation', NULL, NULL, '8.8.8.8', '2025-05-27 10:15:25', '2025-05-27 10:15:25'),
(918, 78, 'check', NULL, NULL, '10.0.0.1', '2025-05-27 09:45:58', '2025-05-27 09:45:58'),
(919, 69, 'check', NULL, NULL, '172.16.0.1', '2025-05-27 08:52:35', '2025-05-27 08:52:35'),
(920, 48, 'validation', NULL, NULL, '10.0.0.1', '2025-05-27 11:52:20', '2025-05-27 11:52:20'),
(921, 52, 'validation', NULL, NULL, '172.16.0.1', '2025-05-27 18:32:07', '2025-05-27 18:32:07'),
(922, 88, 'check', NULL, NULL, '10.0.0.1', '2025-05-27 17:28:34', '2025-05-27 17:28:34'),
(923, 1, 'activation', NULL, NULL, '192.168.1.1', '2025-05-27 06:50:38', '2025-05-27 06:50:38'),
(924, 58, 'validation', NULL, NULL, '192.168.1.1', '2025-05-27 12:36:30', '2025-05-27 12:36:30'),
(925, 31, 'validation', NULL, NULL, '10.0.0.1', '2025-05-27 18:50:42', '2025-05-27 18:50:42'),
(926, 82, 'activation', NULL, NULL, '172.16.0.1', '2025-05-27 15:51:44', '2025-05-27 15:51:44'),
(927, 83, 'validation', NULL, NULL, '192.168.1.1', '2025-05-27 19:11:12', '2025-05-27 19:11:12'),
(928, 81, 'validation', NULL, NULL, '8.8.8.8', '2025-05-27 15:54:16', '2025-05-27 15:54:16'),
(929, 1, 'check', NULL, NULL, '172.16.0.1', '2025-05-27 07:54:45', '2025-05-27 07:54:45'),
(930, 107, 'validation', NULL, NULL, '10.0.0.1', '2025-05-27 21:02:42', '2025-05-27 21:02:42'),
(931, 62, 'check', NULL, NULL, '10.0.0.1', '2025-05-27 14:15:16', '2025-05-27 14:15:16'),
(932, 83, 'verify', NULL, NULL, '10.0.0.1', '2025-05-27 07:58:58', '2025-05-27 07:58:58'),
(933, 39, 'verify', NULL, NULL, '10.0.0.1', '2025-05-27 13:48:09', '2025-05-27 13:48:09'),
(934, 63, 'verify', NULL, NULL, '172.16.0.1', '2025-05-27 19:14:08', '2025-05-27 19:14:08'),
(935, 60, 'verify', NULL, NULL, '8.8.8.8', '2025-05-27 10:17:21', '2025-05-27 10:17:21'),
(936, 64, 'verify', NULL, NULL, '192.168.1.1', '2025-05-27 07:32:45', '2025-05-27 07:32:45'),
(937, 4, 'validation', NULL, NULL, '172.16.0.1', '2025-05-27 21:55:16', '2025-05-27 21:55:16'),
(938, 64, 'verify', '{\"domain\":\"127.0.0.1\",\"ip_address\":\"127.0.0.1\",\"timestamp\":\"2025-05-27 01:43:58\",\"success\":true}', NULL, NULL, '2025-05-26 23:43:58', '2025-05-26 23:43:58'),
(939, 64, 'verify', '{\"domain\":\"127.0.0.1\",\"ip_address\":\"127.0.0.1\",\"timestamp\":\"2025-05-27 01:43:59\",\"success\":true}', NULL, NULL, '2025-05-26 23:43:59', '2025-05-26 23:43:59'),
(940, 61, 'verify', '{\"domain\":\"exemple.com\",\"ip_address\":\"127.0.0.1\",\"timestamp\":\"2025-05-28 16:39:32\",\"success\":true}', NULL, NULL, '2025-05-28 14:39:32', '2025-05-28 14:39:32'),
(941, 61, 'verify', '{\"domain\":\"exemple.com\",\"ip_address\":\"127.0.0.1\",\"timestamp\":\"2025-05-28 16:39:33\",\"success\":true}', NULL, NULL, '2025-05-28 14:39:33', '2025-05-28 14:39:33'),
(942, 61, 'verify', '{\"domain\":\"exemple.com\",\"ip_address\":\"127.0.0.1\",\"timestamp\":\"2025-05-28 16:43:14\",\"success\":true}', NULL, NULL, '2025-05-28 14:43:14', '2025-05-28 14:43:14'),
(943, 61, 'verify', '{\"domain\":\"exemple.com\",\"ip_address\":\"127.0.0.1\",\"timestamp\":\"2025-05-28 16:43:14\",\"success\":true}', NULL, NULL, '2025-05-28 14:43:14', '2025-05-28 14:43:14');

-- --------------------------------------------------------

--
-- Structure de la table `mail_configs`
--

CREATE TABLE `mail_configs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `provider` varchar(255) NOT NULL DEFAULT 'phpmail',
  `mailer` varchar(255) NOT NULL DEFAULT 'smtp',
  `host` varchar(255) DEFAULT NULL,
  `port` int(11) DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `encryption` varchar(255) DEFAULT NULL,
  `from_address` varchar(255) DEFAULT NULL,
  `from_name` varchar(255) DEFAULT NULL,
  `template_name` varchar(255) DEFAULT NULL,
  `template_content` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2023_04_01_000001_create_projects_table', 1),
(5, '2023_04_02_000002_create_serial_keys_table', 1),
(6, '2024_01_09_000001_create_tenants_table', 1),
(7, '2024_01_10_000001_create_licence_histories_table', 1),
(8, '2024_01_10_000001_create_plans_table', 1),
(9, '2024_01_10_000001_create_roles_and_permissions_tables', 1),
(10, '2024_01_10_000002_create_api_keys_table', 1),
(11, '2024_01_10_000002_create_subscriptions_table', 1),
(12, '2024_01_15_000001_create_mail_configs_table', 1),
(13, '2024_01_17_000001_create_email_templates_table', 1),
(14, '2024_03_20_create_email_variables_table', 1),
(15, '2024_03_21_000000_create_admins_table', 1),
(16, '2024_03_21_000001_create_serial_key_histories_table', 1),
(17, '2024_03_22_000001_add_provider_to_mail_configs_table', 1),
(18, '2024_05_01_000002_create_clients_table', 1),
(19, '2024_05_01_000003_create_support_tickets_table', 1),
(20, '2024_05_01_000004_create_ticket_replies_table', 1),
(21, '2024_05_01_000005_create_payment_methods_table', 1),
(22, '2024_05_01_000006_create_invoices_table', 1),
(23, '2024_05_01_000007_create_invoice_items_table', 1),
(24, '2024_05_10_000000_add_two_factor_columns_to_admins_table', 1),
(25, '2024_05_15_000001_create_notifications_table', 1),
(26, '2024_05_20_000001_create_admin_role_table', 1),
(27, '2025_04_02_160003_create_personal_access_tokens_table', 1),
(28, '2025_04_03_054922_make_admin_id_nullable_in_serial_key_histories_table', 1),
(29, '2025_04_07_072637_create_admin_password_reset_tokens_table', 1),
(30, '2025_04_07_094844_add_content_column_to_email_templates', 1),
(31, '2025_04_07_110500_add_is_system_column_to_email_templates', 1),
(32, '2025_04_08_000001_add_is_super_admin_to_admins_table', 1),
(33, '2025_04_11_223351_create_cache_table', 2),
(34, '2025_04_28_210646_create_telescope_entries_table', 2),
(35, '2025_05_26_000000_create_settings_table', 3),
(36, '2025_06_08_221543_add_indexes_to_serial_keys_table', 4),
(37, '2025_06_08_222219_add_licence_type_to_serial_keys_table', 4),
(38, '2025_06_09_014445_create_tenant_user_table', 5),
(39, '2025_06_09_015902_add_licence_fields_to_tenants_table', 5),
(40, '2025_01_15_000001_add_unified_system_fields_to_serial_keys_table', 6),
(41, '2025_01_15_000002_add_unified_system_fields_to_tenants_table', 6);

-- --------------------------------------------------------

--
-- Structure de la table `notifications`
--

CREATE TABLE `notifications` (
  `id` char(36) NOT NULL,
  `type` varchar(255) NOT NULL,
  `notifiable_type` varchar(255) NOT NULL,
  `notifiable_id` bigint(20) UNSIGNED NOT NULL,
  `data` text NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `payment_methods`
--

CREATE TABLE `payment_methods` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tenant_id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(255) NOT NULL,
  `provider` varchar(255) NOT NULL,
  `provider_id` varchar(255) DEFAULT NULL,
  `card_brand` varchar(255) DEFAULT NULL,
  `card_last_four` varchar(255) DEFAULT NULL,
  `paypal_email` varchar(255) DEFAULT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT 0,
  `expires_at` timestamp NULL DEFAULT NULL,
  `billing_details` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`billing_details`)),
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`metadata`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `slug`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Accéder au dashboard', 'access-dashboard', NULL, '2025-04-15 18:14:54', '2025-04-15 18:14:54'),
(2, 'Gérer les paramètres', 'manage-settings', NULL, '2025-04-15 18:14:54', '2025-04-15 18:14:54'),
(3, 'Voir les licences', 'view-licenses', NULL, '2025-04-15 18:14:54', '2025-04-15 18:14:54'),
(4, 'Créer des licences', 'create-licenses', NULL, '2025-04-15 18:14:54', '2025-04-15 18:14:54'),
(5, 'Éditer les licences', 'edit-licenses', NULL, '2025-04-15 18:14:54', '2025-04-15 18:14:54'),
(6, 'Supprimer les licences', 'delete-licenses', NULL, '2025-04-15 18:14:54', '2025-04-15 18:14:54'),
(7, 'Voir les clients', 'view-clients', NULL, '2025-04-15 18:14:54', '2025-04-15 18:14:54'),
(8, 'Créer des clients', 'create-clients', NULL, '2025-04-15 18:14:54', '2025-04-15 18:14:54'),
(9, 'Éditer les clients', 'edit-clients', NULL, '2025-04-15 18:14:54', '2025-04-15 18:14:54'),
(10, 'Supprimer les clients', 'delete-clients', NULL, '2025-04-15 18:14:54', '2025-04-15 18:14:54'),
(11, 'Voir les projets', 'view-projects', NULL, '2025-04-15 18:14:54', '2025-04-15 18:14:54'),
(12, 'Créer des projets', 'create-projects', NULL, '2025-04-15 18:14:54', '2025-04-15 18:14:54'),
(13, 'Éditer les projets', 'edit-projects', NULL, '2025-04-15 18:14:54', '2025-04-15 18:14:54'),
(14, 'Supprimer les projets', 'delete-projects', NULL, '2025-04-15 18:14:54', '2025-04-15 18:14:54'),
(15, 'Voir les templates', 'view-templates', NULL, '2025-04-15 18:14:54', '2025-04-15 18:14:54'),
(16, 'Créer des templates', 'create-templates', NULL, '2025-04-15 18:14:54', '2025-04-15 18:14:54'),
(17, 'Éditer les templates', 'edit-templates', NULL, '2025-04-15 18:14:54', '2025-04-15 18:14:54'),
(18, 'Supprimer les templates', 'delete-templates', NULL, '2025-04-15 18:14:54', '2025-04-15 18:14:54'),
(19, 'Gérer les abonnements', 'manage-subscriptions', NULL, '2025-04-15 18:14:54', '2025-04-15 18:14:54'),
(20, 'Voir les factures', 'view-invoices', NULL, '2025-04-15 18:14:54', '2025-04-15 18:14:54'),
(21, 'Gérer les utilisateurs', 'manage-users', NULL, '2025-04-15 18:14:54', '2025-04-15 18:14:54'),
(22, 'Gérer les rôles', 'manage-roles', NULL, '2025-04-15 18:14:54', '2025-04-15 18:14:54');

-- --------------------------------------------------------

--
-- Structure de la table `permission_role`
--

CREATE TABLE `permission_role` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `permission_role`
--

INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES
(1, 2),
(1, 3),
(3, 2),
(3, 3),
(4, 2),
(5, 2),
(7, 2),
(7, 3),
(8, 2),
(9, 2),
(11, 2),
(11, 3),
(12, 2),
(13, 2),
(15, 2),
(15, 3),
(16, 2),
(17, 2),
(20, 2),
(20, 3);

-- --------------------------------------------------------

--
-- Structure de la table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `plans`
--

CREATE TABLE `plans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `billing_cycle` varchar(255) NOT NULL DEFAULT 'monthly',
  `features` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`features`)),
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `stripe_price_id` varchar(255) DEFAULT NULL,
  `paypal_plan_id` varchar(255) DEFAULT NULL,
  `trial_days` int(11) NOT NULL DEFAULT 0,
  `max_licenses` int(11) NOT NULL DEFAULT 1,
  `max_projects` int(11) NOT NULL DEFAULT 1,
  `max_clients` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `projects`
--

CREATE TABLE `projects` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `website_url` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `projects`
--

INSERT INTO `projects` (`id`, `name`, `description`, `website_url`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Projet Test 1', 'Description du projet test 1', 'https://projet1.example.com', 'active', '2025-04-15 18:14:55', '2025-04-15 18:14:55'),
(2, 'Projet Test 2', 'Description du projet test 2', 'https://projet2.example.com', 'active', '2025-04-15 18:14:55', '2025-04-15 18:14:55'),
(5, 'AdminLicence', NULL, NULL, 'active', '2025-04-22 12:38:47', '2025-04-22 12:38:47'),
(6, 'AdminLicence_Saas', NULL, NULL, 'active', '2025-04-22 12:54:28', '2025-04-22 12:54:28'),
(7, 'ChanLog', NULL, NULL, 'active', '2025-04-25 01:02:21', '2025-04-25 01:02:21'),
(12, 'Default Project', 'Projet par défaut pour le système unifié', NULL, 'active', '2025-06-11 14:00:56', '2025-06-11 14:00:56');

-- --------------------------------------------------------

--
-- Structure de la table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `roles`
--

INSERT INTO `roles` (`id`, `name`, `slug`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Super Admin', 'super-admin', 'Accès complet à toutes les fonctionnalités', '2025-04-15 18:14:54', '2025-04-15 18:14:54'),
(2, 'Admin', 'admin', 'Accès à la gestion des licences et des clients', '2025-04-15 18:14:54', '2025-04-15 18:14:54'),
(3, 'User', 'user', 'Accès limité aux fonctionnalités de base', '2025-04-15 18:14:54', '2025-04-15 18:14:54');

-- --------------------------------------------------------

--
-- Structure de la table `role_user`
--

CREATE TABLE `role_user` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `role_user`
--

INSERT INTO `role_user` (`role_id`, `user_id`) VALUES
(3, 1);

-- --------------------------------------------------------

--
-- Structure de la table `serial_keys`
--

CREATE TABLE `serial_keys` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `serial_key` varchar(255) NOT NULL,
  `status` enum('active','revoked','expired','suspended') NOT NULL DEFAULT 'active',
  `project_id` bigint(20) UNSIGNED NOT NULL,
  `domain` varchar(255) DEFAULT NULL,
  `ip_address` varchar(255) DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `licence_type` varchar(20) NOT NULL DEFAULT 'single' COMMENT 'Type de licence: single (mono-compte) ou multi (multi-comptes)',
  `max_accounts` int(11) DEFAULT NULL COMMENT 'Nombre maximum de comptes autorisés pour les licences multi-comptes',
  `features` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'Fonctionnalités activées pour cette licence' CHECK (json_valid(`features`)),
  `limits` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'Limites spécifiques à cette licence (projets, utilisateurs, etc.)' CHECK (json_valid(`limits`)),
  `is_saas_enabled` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Indique si cette licence active le mode SaaS',
  `max_tenants` int(11) DEFAULT NULL COMMENT 'Nombre maximum de tenants autorisés (mode SaaS)',
  `max_clients_per_tenant` int(11) DEFAULT NULL COMMENT 'Nombre maximum de clients par tenant',
  `max_projects` int(11) DEFAULT NULL COMMENT 'Nombre maximum de projets autorisés',
  `billing_cycle` varchar(191) DEFAULT NULL COMMENT 'Cycle de facturation (monthly, yearly, lifetime)',
  `price` decimal(10,2) DEFAULT NULL COMMENT 'Prix de la licence',
  `currency` varchar(3) NOT NULL DEFAULT 'EUR' COMMENT 'Devise de la licence',
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'Métadonnées additionnelles de la licence' CHECK (json_valid(`metadata`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `serial_keys`
--

INSERT INTO `serial_keys` (`id`, `serial_key`, `status`, `project_id`, `domain`, `ip_address`, `expires_at`, `created_at`, `updated_at`, `licence_type`, `max_accounts`, `features`, `limits`, `is_saas_enabled`, `max_tenants`, `max_clients_per_tenant`, `max_projects`, `billing_cycle`, `price`, `currency`, `metadata`) VALUES
(1, '67FEBE3F0858F-67FEBE3F08590-67FEBE3F08591', 'active', 1, 'example.com', '127.0.0.1', '2026-04-15 20:14:55', '2025-04-15 18:14:55', '2025-05-26 21:23:37', 'single', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'EUR', NULL),
(2, '67FEBE3F09320-67FEBE3F09321-67FEBE3F09322', 'active', 1, NULL, NULL, '2026-04-15 20:14:55', '2025-04-15 18:14:55', '2025-04-15 18:14:55', 'single', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'EUR', NULL),
(3, '67FEBE3F09B55-67FEBE3F09B56-67FEBE3F09B57', 'active', 1, NULL, NULL, '2026-04-15 20:14:55', '2025-04-15 18:14:55', '2025-04-15 18:14:55', 'single', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'EUR', NULL),
(4, '67FEBE3F0A356-67FEBE3F0A357-67FEBE3F0A358', 'active', 1, NULL, NULL, '2026-04-15 20:14:55', '2025-04-15 18:14:55', '2025-04-15 18:14:55', 'single', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'EUR', NULL),
(5, '67FEBE3F0ABB3-67FEBE3F0ABB4-67FEBE3F0ABB5', 'active', 1, NULL, NULL, '2026-04-15 20:14:55', '2025-04-15 18:14:55', '2025-04-15 18:14:55', 'single', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'EUR', NULL),
(6, '67FEBE3F0CC9C-67FEBE3F0CC9D-67FEBE3F0CC9E', 'active', 2, NULL, NULL, '2026-04-15 20:14:55', '2025-04-15 18:14:55', '2025-04-15 18:14:55', 'single', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'EUR', NULL),
(7, '67FEBE3F0D4B7-67FEBE3F0D4B8-67FEBE3F0D4B9', 'active', 2, NULL, NULL, '2026-04-15 20:14:55', '2025-04-15 18:14:55', '2025-04-15 18:14:55', 'single', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'EUR', NULL),
(8, '67FEBE3F0DD07-67FEBE3F0DD08-67FEBE3F0DD09', 'active', 2, NULL, NULL, '2026-04-15 20:14:55', '2025-04-15 18:14:55', '2025-04-15 18:14:55', 'single', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'EUR', NULL),
(9, '67FEBE3F0E4FB-67FEBE3F0E4FC-67FEBE3F0E4FD', 'active', 2, NULL, NULL, '2026-04-15 20:14:55', '2025-04-15 18:14:55', '2025-04-15 18:14:55', 'single', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'EUR', NULL),
(10, '67FEBE3F0EFE1-67FEBE3F0EFE4-67FEBE3F0EFE5', 'active', 2, NULL, NULL, '2026-04-15 20:14:55', '2025-04-15 18:14:55', '2025-04-15 18:14:55', 'single', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'EUR', NULL),
(21, 'T9UZ-EDBZ-E7S2-VXXN', 'active', 5, NULL, NULL, NULL, '2025-04-22 12:38:58', '2025-04-22 12:38:58', 'single', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'EUR', NULL),
(22, 'YM4I-AW6X-07LY-PLA9', 'active', 5, NULL, NULL, NULL, '2025-04-22 12:38:58', '2025-04-22 12:38:58', 'single', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'EUR', NULL),
(23, 'TABI-GKCD-VKBC-RGOR', 'active', 5, NULL, NULL, NULL, '2025-04-22 12:38:58', '2025-04-22 12:38:58', 'single', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'EUR', NULL),
(24, 'LAK2-ZITE-CT9E-RD79', 'active', 5, NULL, NULL, NULL, '2025-04-22 12:38:58', '2025-04-22 12:38:58', 'single', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'EUR', NULL),
(25, 'VRU0-X6SS-D5C4-TTLU', 'active', 5, NULL, NULL, NULL, '2025-04-22 12:38:58', '2025-04-22 12:38:58', 'single', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'EUR', NULL),
(26, 'MBUH-PMW2-L4UH-CTQJ', 'active', 5, NULL, NULL, NULL, '2025-04-22 12:38:58', '2025-04-22 12:38:58', 'single', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'EUR', NULL),
(27, 'FZAV-ERQS-LHAX-AEDI', 'active', 5, NULL, NULL, NULL, '2025-04-22 12:38:58', '2025-04-22 12:38:58', 'single', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'EUR', NULL),
(28, '1UNE-K3AM-3579-JNVW', 'active', 5, NULL, NULL, NULL, '2025-04-22 12:38:58', '2025-04-22 12:38:58', 'single', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'EUR', NULL),
(29, 'EWTW-XIH8-MMVE-FIPH', 'active', 5, NULL, NULL, NULL, '2025-04-22 12:38:58', '2025-04-22 12:38:58', 'single', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'EUR', NULL),
(30, 'NUUV-Q5KD-DSLY-DM27', 'active', 5, NULL, NULL, NULL, '2025-04-22 12:38:58', '2025-04-22 12:38:58', 'single', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'EUR', NULL),
(31, 'IVFG-D9GA-DEQJ-ZBOS', 'active', 5, NULL, NULL, NULL, '2025-04-22 12:38:58', '2025-04-22 12:38:58', 'single', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'EUR', NULL),
(32, 'RRRN-DW4Q-TVJE-W1CU', 'active', 5, NULL, NULL, NULL, '2025-04-22 12:38:58', '2025-04-22 12:38:58', 'single', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'EUR', NULL),
(33, '3UQF-UZXB-JT3D-9BM4', 'active', 5, NULL, NULL, NULL, '2025-04-22 12:38:58', '2025-04-22 12:38:58', 'single', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'EUR', NULL),
(34, '8PSG-QLOR-2ABL-GW4U', 'active', 5, NULL, NULL, NULL, '2025-04-22 12:38:59', '2025-04-22 12:38:59', 'single', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'EUR', NULL),
(35, 'HVRM-8TBU-QFLE-I8Y2', 'active', 5, NULL, NULL, NULL, '2025-04-22 12:38:59', '2025-04-22 12:38:59', 'single', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'EUR', NULL),
(36, '8XKP-NZGZ-ZWAS-OT5U', 'active', 5, NULL, NULL, NULL, '2025-04-22 12:38:59', '2025-04-22 12:38:59', 'single', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'EUR', NULL),
(37, 'HDZM-VX7Z-YZKL-YQFA', 'active', 5, NULL, NULL, NULL, '2025-04-22 12:38:59', '2025-04-22 12:38:59', 'single', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'EUR', NULL),
(38, '13GM-MTP7-NKPQ-O169', 'active', 5, NULL, NULL, NULL, '2025-04-22 12:38:59', '2025-04-22 12:38:59', 'single', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'EUR', NULL),
(39, 'PIVF-JJYB-TRPI-KHI3', 'active', 5, NULL, NULL, NULL, '2025-04-22 12:38:59', '2025-04-22 12:38:59', 'single', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'EUR', NULL),
(40, 'SVDW-JAIH-TPUL-CZ2J', 'active', 5, NULL, NULL, NULL, '2025-04-22 12:38:59', '2025-04-22 12:38:59', 'single', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'EUR', NULL),
(41, 'G6FY-U8H6-PPQS-UWZ0', 'active', 5, NULL, NULL, NULL, '2025-04-22 12:38:59', '2025-04-22 12:38:59', 'single', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'EUR', NULL),
(42, 'EYWQ-SC2X-N9OM-0QVG', 'active', 5, NULL, NULL, NULL, '2025-04-22 12:38:59', '2025-04-22 12:38:59', 'single', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'EUR', NULL),
(43, 'B9VT-CCKC-FPNV-9VOI', 'active', 5, NULL, NULL, NULL, '2025-04-22 12:38:59', '2025-04-22 12:38:59', 'single', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'EUR', NULL),
(44, 'KXZP-WAGZ-TMFC-QYGS', 'active', 5, NULL, NULL, NULL, '2025-04-22 12:38:59', '2025-04-22 12:38:59', 'single', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'EUR', NULL),
(45, 'BCGS-MON3-DVBY-TRE6', 'active', 5, NULL, NULL, NULL, '2025-04-22 12:38:59', '2025-04-22 12:38:59', 'single', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'EUR', NULL),
(46, 'DCV0-CVLG-9QGE-OELK', 'active', 5, NULL, NULL, NULL, '2025-04-22 12:38:59', '2025-04-22 12:38:59', 'single', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'EUR', NULL),
(47, 'ECLE-KGJD-RBR1-NYZT', 'active', 5, NULL, NULL, NULL, '2025-04-22 12:38:59', '2025-04-22 12:38:59', 'single', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'EUR', NULL),
(48, 'SVV0-IZUC-IHOC-N1J2', 'active', 5, NULL, NULL, NULL, '2025-04-22 12:38:59', '2025-04-22 12:38:59', 'single', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'EUR', NULL),
(49, 'G0GS-7SPB-UCLD-WKJG', 'active', 5, NULL, NULL, NULL, '2025-04-22 12:38:59', '2025-04-22 12:38:59', 'single', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'EUR', NULL),
(50, 'WEYT-XDUV-944N-W4EB', 'active', 5, NULL, NULL, NULL, '2025-04-22 12:38:59', '2025-04-22 12:38:59', 'single', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'EUR', NULL),
(51, 'R0O4-GYZY-Z9N9-NG0C', 'active', 5, NULL, NULL, NULL, '2025-04-22 12:38:59', '2025-04-22 12:38:59', 'single', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'EUR', NULL),
(52, 'ESUU-SGHL-E2QH-NH0E', 'active', 5, NULL, NULL, NULL, '2025-04-22 12:38:59', '2025-04-22 12:38:59', 'single', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'EUR', NULL),
(53, 'Q0JK-JX7I-ND9H-NKKN', 'active', 5, NULL, NULL, NULL, '2025-04-22 12:38:59', '2025-04-22 12:38:59', 'single', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'EUR', NULL),
(54, 'WRLG-A62Q-LO6P-2KZ5', 'active', 5, NULL, NULL, NULL, '2025-04-22 12:38:59', '2025-04-22 12:38:59', 'single', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'EUR', NULL),
(55, 'GETW-JBWZ-XFUJ-UYAL', 'active', 5, NULL, NULL, NULL, '2025-04-22 12:38:59', '2025-04-22 12:38:59', 'single', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'EUR', NULL),
(56, 'CGLY-EK0A-YBLZ-5VIT', 'active', 5, NULL, NULL, NULL, '2025-04-22 12:38:59', '2025-04-22 12:38:59', 'single', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'EUR', NULL),
(57, 'RHR0-DGX5-T2SW-ZLQM', 'active', 5, NULL, NULL, NULL, '2025-04-22 12:38:59', '2025-04-22 12:38:59', 'single', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'EUR', NULL),
(58, 'AOKH-NIFX-FXQE-VZPG', 'active', 5, NULL, NULL, NULL, '2025-04-22 12:38:59', '2025-04-22 12:38:59', 'single', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'EUR', NULL),
(59, 'HXJ8-RJFA-S15A-UDEE', 'active', 5, NULL, NULL, NULL, '2025-04-22 12:38:59', '2025-04-22 12:38:59', 'single', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'EUR', NULL),
(60, 'Q52T-OU5U-ZWZ5-GOPJ', 'active', 5, NULL, NULL, NULL, '2025-04-22 12:38:59', '2025-04-22 12:38:59', 'single', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'EUR', NULL),
(61, '9QXH-YDNF-WBFL-XFTU', 'active', 6, 'exemple.com', '127.0.0.1', NULL, '2025-04-22 12:54:44', '2025-05-28 14:39:32', 'single', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'EUR', NULL),
(62, 'VBY7-6QGH-XHX9-SIHL', 'active', 6, NULL, NULL, NULL, '2025-04-22 12:54:44', '2025-04-22 12:54:44', 'single', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'EUR', NULL),
(63, 'F7AG-8ZTO-Z6UD-4AR7', 'active', 6, NULL, NULL, NULL, '2025-04-22 12:54:44', '2025-04-22 12:54:44', 'single', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'EUR', NULL),
(64, 'ZNWQ-E0GP-QYVH-BPHZ', 'active', 6, '127.0.0.1', '127.0.0.1', NULL, '2025-04-22 12:54:44', '2025-05-26 21:28:43', 'single', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'EUR', NULL),
(65, 'LTR1-KOJF-66RA-67VU', 'active', 6, NULL, NULL, NULL, '2025-04-22 12:54:44', '2025-04-22 12:54:44', 'single', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'EUR', NULL),
(66, 'WHET-30AU-N7OR-L8R0', 'active', 6, NULL, NULL, NULL, '2025-04-22 12:54:44', '2025-04-22 12:54:44', 'single', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'EUR', NULL),
(67, 'CWVP-UCMP-LTSJ-HONN', 'active', 6, NULL, NULL, NULL, '2025-04-22 12:54:44', '2025-04-22 12:54:44', 'single', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'EUR', NULL),
(68, 'TFPV-ULWY-DPNW-CDLU', 'active', 6, NULL, NULL, NULL, '2025-04-22 12:54:44', '2025-04-22 12:54:44', 'single', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'EUR', NULL),
(69, 'NFKV-06BN-4RZ8-XYXF', 'revoked', 6, NULL, NULL, NULL, '2025-04-22 12:54:44', '2025-05-08 01:13:59', 'single', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'EUR', NULL),
(70, 'ZEPD-VOL0-I61U-H66K', 'suspended', 6, NULL, NULL, NULL, '2025-04-22 12:54:44', '2025-05-08 01:13:41', 'single', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'EUR', NULL),
(71, 'FVHJ-GJ0W-4ZQO-L3E3', 'active', 6, NULL, NULL, NULL, '2025-04-22 12:54:44', '2025-04-22 12:54:44', 'single', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'EUR', NULL),
(72, 'YJBO-IYGR-V6BS-FJZC', 'active', 6, NULL, NULL, NULL, '2025-04-22 12:54:44', '2025-04-22 12:54:44', 'single', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'EUR', NULL),
(73, 'X7GV-1UX5-FVS2-ICBK', 'active', 6, NULL, NULL, NULL, '2025-04-22 12:54:44', '2025-04-22 12:54:44', 'single', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'EUR', NULL),
(74, 'NSZZ-EOZI-NDPY-MBVV', 'active', 6, NULL, NULL, NULL, '2025-04-22 12:54:44', '2025-04-22 12:54:44', 'single', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'EUR', NULL),
(75, '7JNP-ECIS-TBJM-OAR3', 'active', 6, NULL, NULL, NULL, '2025-04-22 12:54:44', '2025-04-22 12:54:44', 'single', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'EUR', NULL),
(76, 'TV11-YUIS-VXWQ-6N2J', 'active', 6, NULL, NULL, NULL, '2025-04-22 12:54:44', '2025-04-22 12:54:44', 'single', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'EUR', NULL),
(77, '2O9C-IXCK-ZPCJ-CRWJ', 'active', 6, NULL, NULL, NULL, '2025-04-22 12:54:44', '2025-04-22 12:54:44', 'single', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'EUR', NULL),
(78, 'U91P-LCDD-HRXV-SQQC', 'active', 6, NULL, NULL, NULL, '2025-04-22 12:54:44', '2025-04-22 12:54:44', 'single', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'EUR', NULL),
(79, '9YRD-JGKM-KR0R-EXUC', 'active', 6, NULL, NULL, NULL, '2025-04-22 12:54:44', '2025-04-22 12:54:44', 'single', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'EUR', NULL),
(80, 'OXBC-HXQ3-XHVV-TIIP', 'active', 6, NULL, NULL, NULL, '2025-04-22 12:54:44', '2025-04-22 12:54:44', 'single', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'EUR', NULL),
(81, 'KUBX-0DVH-ZSDW-TU3M', 'active', 6, NULL, NULL, NULL, '2025-04-22 12:54:44', '2025-04-22 12:54:44', 'single', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'EUR', NULL),
(82, 'COLZ-06YX-7YXL-0BD8', 'active', 6, NULL, NULL, NULL, '2025-04-22 12:54:44', '2025-04-22 12:54:44', 'single', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'EUR', NULL),
(83, 'EVWT-UMCZ-UZHN-EGZB', 'active', 6, NULL, NULL, NULL, '2025-04-22 12:54:44', '2025-04-22 12:54:44', 'single', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'EUR', NULL),
(84, 'OVRT-YRE4-JOD8-EMAP', 'active', 6, NULL, NULL, NULL, '2025-04-22 12:54:44', '2025-04-22 12:54:44', 'single', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'EUR', NULL),
(85, 'L6FC-SZ1V-VAV7-D0PT', 'active', 6, NULL, NULL, NULL, '2025-04-22 12:54:44', '2025-04-22 12:54:44', 'single', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'EUR', NULL),
(86, 'H99S-SU7D-Z019-4WGO', 'active', 6, NULL, NULL, NULL, '2025-04-22 12:54:44', '2025-04-22 12:54:44', 'single', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'EUR', NULL),
(87, 'NY94-EFI5-I96N-556L', 'active', 6, NULL, NULL, NULL, '2025-04-22 12:54:44', '2025-04-22 12:54:44', 'single', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'EUR', NULL),
(88, '3TM5-Q0HW-WSWH-A6LP', 'active', 6, NULL, NULL, NULL, '2025-04-22 12:54:44', '2025-04-22 12:54:44', 'single', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'EUR', NULL),
(89, 'VQCJ-WLDX-CL2C-8UBS', 'active', 6, NULL, NULL, NULL, '2025-04-22 12:54:44', '2025-04-22 12:54:44', 'single', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'EUR', NULL),
(90, 'CWGK-IWYH-LF1U-GKO9', 'active', 6, NULL, NULL, NULL, '2025-04-22 12:54:44', '2025-04-22 12:54:44', 'single', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'EUR', NULL),
(91, 'MFWB-AIVQ-RB89-GFQU', 'active', 6, NULL, NULL, NULL, '2025-04-22 12:54:44', '2025-04-22 12:54:44', 'single', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'EUR', NULL),
(92, 'PZGO-YBMF-TIFM-M5IE', 'active', 6, NULL, NULL, NULL, '2025-04-22 12:54:44', '2025-04-22 12:54:44', 'single', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'EUR', NULL),
(93, 'OO5K-ACWM-25L4-PDW3', 'active', 6, NULL, NULL, NULL, '2025-04-22 12:54:44', '2025-04-22 12:54:44', 'single', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'EUR', NULL),
(94, 'D4CC-7U7P-WSAT-LXHA', 'active', 6, NULL, NULL, NULL, '2025-04-22 12:54:44', '2025-04-22 12:54:44', 'single', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'EUR', NULL),
(95, 'GTIN-KYWY-BING-FYJE', 'active', 6, NULL, NULL, NULL, '2025-04-22 12:54:44', '2025-04-22 12:54:44', 'single', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'EUR', NULL),
(96, 'E2C4-LSZS-3FN2-DI0J', 'active', 6, NULL, NULL, NULL, '2025-04-22 12:54:44', '2025-04-22 12:54:44', 'single', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'EUR', NULL),
(97, 'X1XI-DPFP-YEYL-2DQN', 'active', 6, NULL, NULL, NULL, '2025-04-22 12:54:44', '2025-04-22 12:54:44', 'single', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'EUR', NULL),
(98, 'X8BD-T9E3-KYFH-HAD3', 'active', 6, NULL, NULL, NULL, '2025-04-22 12:54:44', '2025-04-22 12:54:44', 'single', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'EUR', NULL),
(99, 'WYHB-8FUU-SKLG-PTXZ', 'active', 6, NULL, NULL, NULL, '2025-04-22 12:54:44', '2025-04-22 12:54:44', 'single', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'EUR', NULL),
(100, 'DIKC-JXIO-TEG9-JAQB', 'active', 6, NULL, NULL, NULL, '2025-04-22 12:54:44', '2025-04-22 12:54:44', 'single', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'EUR', NULL),
(101, 'ACIO-HYWO-PH3A-C7NR', 'active', 6, NULL, NULL, NULL, '2025-04-22 12:54:44', '2025-04-22 12:54:44', 'single', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'EUR', NULL),
(102, '4C2T-VHYT-J6ZD-ROAN', 'active', 6, NULL, NULL, NULL, '2025-04-22 12:54:44', '2025-04-22 12:54:44', 'single', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'EUR', NULL),
(103, 'QOKZ-ISLP-27VY-EPGR', 'active', 6, NULL, NULL, NULL, '2025-04-22 12:54:44', '2025-04-22 12:54:44', 'single', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'EUR', NULL),
(104, 'S96S-VL3M-0FB3-KAEP', 'active', 6, NULL, NULL, NULL, '2025-04-22 12:54:44', '2025-04-22 12:54:44', 'single', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'EUR', NULL),
(105, 'BRUC-FZXZ-J5SM-RPO2', 'active', 6, NULL, NULL, NULL, '2025-04-22 12:54:44', '2025-04-22 12:54:44', 'single', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'EUR', NULL),
(106, 'K4YD-UDPP-GL0T-FERT', 'active', 6, NULL, NULL, NULL, '2025-04-22 12:54:44', '2025-04-22 12:54:44', 'single', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'EUR', NULL),
(107, 'LYPR-JBOO-SNSQ-X6GB', 'active', 6, NULL, NULL, NULL, '2025-04-22 12:54:44', '2025-04-22 12:54:44', 'single', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'EUR', NULL),
(108, 'EWSP-2CU5-ROQC-GZPN', 'active', 6, NULL, NULL, NULL, '2025-04-22 12:54:44', '2025-04-22 12:54:44', 'single', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'EUR', NULL),
(109, 'AKC3-0JEK-DGRU-XHZ4', 'suspended', 6, NULL, NULL, NULL, '2025-04-22 12:54:44', '2025-06-10 19:17:13', 'single', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'EUR', NULL),
(110, 'UU3Y-HKBF-NM9A-4UD1', 'active', 6, NULL, NULL, NULL, '2025-04-22 12:54:44', '2025-04-22 12:54:44', 'single', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'EUR', NULL),
(117, 'SINGLE-DEFAULT-DB73E408', 'active', 12, NULL, NULL, NULL, '2025-06-11 14:00:56', '2025-06-11 14:00:56', 'single', 1, '{\"project_management\":true,\"api_access\":true,\"email_templates\":true,\"basic_support\":true,\"multi_tenant\":false,\"advanced_analytics\":false,\"priority_support\":false}', '{\"projects\":10,\"api_calls_per_month\":10000,\"email_templates\":5,\"storage_gb\":1}', 0, 1, 1, 10, 'lifetime', NULL, 'EUR', NULL),
(118, 'SAAS-DEFAULT-CF48FB1A', 'active', 12, NULL, NULL, '2026-06-11 16:00:56', '2025-06-11 14:00:56', '2025-06-11 14:00:56', 'multi', NULL, '{\"project_management\":true,\"api_access\":true,\"email_templates\":true,\"basic_support\":true,\"multi_tenant\":true,\"advanced_analytics\":true,\"priority_support\":true,\"white_label\":true,\"custom_domains\":true}', '{\"projects\":null,\"api_calls_per_month\":1000000,\"email_templates\":null,\"storage_gb\":100}', 1, 100, 1000, NULL, 'monthly', 99.99, 'EUR', NULL),
(119, 'SAAS-FB193AC56CB3', 'active', 12, NULL, NULL, '2026-06-12 02:12:46', '2025-06-12 00:12:46', '2025-06-12 00:12:46', 'multi', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'EUR', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `serial_key_histories`
--

CREATE TABLE `serial_key_histories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `serial_key_id` bigint(20) UNSIGNED NOT NULL,
  `action` varchar(255) NOT NULL,
  `details` text DEFAULT NULL,
  `admin_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(255) NOT NULL,
  `user_agent` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `serial_key_histories`
--

INSERT INTO `serial_key_histories` (`id`, `serial_key_id`, `action`, `details`, `admin_id`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES
(1, 21, 'create', 'Création d\'une nouvelle clé de licence', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/132.0.0.0 Safari/537.36 OPR/117.0.0.0 (Edition std-1)', '2025-04-22 12:38:58', '2025-04-22 12:38:58'),
(2, 22, 'create', 'Création d\'une nouvelle clé de licence', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/132.0.0.0 Safari/537.36 OPR/117.0.0.0 (Edition std-1)', '2025-04-22 12:38:58', '2025-04-22 12:38:58'),
(3, 23, 'create', 'Création d\'une nouvelle clé de licence', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/132.0.0.0 Safari/537.36 OPR/117.0.0.0 (Edition std-1)', '2025-04-22 12:38:58', '2025-04-22 12:38:58'),
(4, 24, 'create', 'Création d\'une nouvelle clé de licence', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/132.0.0.0 Safari/537.36 OPR/117.0.0.0 (Edition std-1)', '2025-04-22 12:38:58', '2025-04-22 12:38:58'),
(5, 25, 'create', 'Création d\'une nouvelle clé de licence', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/132.0.0.0 Safari/537.36 OPR/117.0.0.0 (Edition std-1)', '2025-04-22 12:38:58', '2025-04-22 12:38:58'),
(6, 26, 'create', 'Création d\'une nouvelle clé de licence', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/132.0.0.0 Safari/537.36 OPR/117.0.0.0 (Edition std-1)', '2025-04-22 12:38:58', '2025-04-22 12:38:58'),
(7, 27, 'create', 'Création d\'une nouvelle clé de licence', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/132.0.0.0 Safari/537.36 OPR/117.0.0.0 (Edition std-1)', '2025-04-22 12:38:58', '2025-04-22 12:38:58'),
(8, 28, 'create', 'Création d\'une nouvelle clé de licence', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/132.0.0.0 Safari/537.36 OPR/117.0.0.0 (Edition std-1)', '2025-04-22 12:38:58', '2025-04-22 12:38:58'),
(9, 29, 'create', 'Création d\'une nouvelle clé de licence', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/132.0.0.0 Safari/537.36 OPR/117.0.0.0 (Edition std-1)', '2025-04-22 12:38:58', '2025-04-22 12:38:58'),
(10, 30, 'create', 'Création d\'une nouvelle clé de licence', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/132.0.0.0 Safari/537.36 OPR/117.0.0.0 (Edition std-1)', '2025-04-22 12:38:58', '2025-04-22 12:38:58'),
(11, 31, 'create', 'Création d\'une nouvelle clé de licence', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/132.0.0.0 Safari/537.36 OPR/117.0.0.0 (Edition std-1)', '2025-04-22 12:38:58', '2025-04-22 12:38:58'),
(12, 32, 'create', 'Création d\'une nouvelle clé de licence', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/132.0.0.0 Safari/537.36 OPR/117.0.0.0 (Edition std-1)', '2025-04-22 12:38:58', '2025-04-22 12:38:58'),
(13, 33, 'create', 'Création d\'une nouvelle clé de licence', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/132.0.0.0 Safari/537.36 OPR/117.0.0.0 (Edition std-1)', '2025-04-22 12:38:59', '2025-04-22 12:38:59'),
(14, 34, 'create', 'Création d\'une nouvelle clé de licence', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/132.0.0.0 Safari/537.36 OPR/117.0.0.0 (Edition std-1)', '2025-04-22 12:38:59', '2025-04-22 12:38:59'),
(15, 35, 'create', 'Création d\'une nouvelle clé de licence', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/132.0.0.0 Safari/537.36 OPR/117.0.0.0 (Edition std-1)', '2025-04-22 12:38:59', '2025-04-22 12:38:59'),
(16, 36, 'create', 'Création d\'une nouvelle clé de licence', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/132.0.0.0 Safari/537.36 OPR/117.0.0.0 (Edition std-1)', '2025-04-22 12:38:59', '2025-04-22 12:38:59'),
(17, 37, 'create', 'Création d\'une nouvelle clé de licence', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/132.0.0.0 Safari/537.36 OPR/117.0.0.0 (Edition std-1)', '2025-04-22 12:38:59', '2025-04-22 12:38:59'),
(18, 38, 'create', 'Création d\'une nouvelle clé de licence', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/132.0.0.0 Safari/537.36 OPR/117.0.0.0 (Edition std-1)', '2025-04-22 12:38:59', '2025-04-22 12:38:59'),
(19, 39, 'create', 'Création d\'une nouvelle clé de licence', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/132.0.0.0 Safari/537.36 OPR/117.0.0.0 (Edition std-1)', '2025-04-22 12:38:59', '2025-04-22 12:38:59'),
(20, 40, 'create', 'Création d\'une nouvelle clé de licence', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/132.0.0.0 Safari/537.36 OPR/117.0.0.0 (Edition std-1)', '2025-04-22 12:38:59', '2025-04-22 12:38:59'),
(21, 41, 'create', 'Création d\'une nouvelle clé de licence', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/132.0.0.0 Safari/537.36 OPR/117.0.0.0 (Edition std-1)', '2025-04-22 12:38:59', '2025-04-22 12:38:59'),
(22, 42, 'create', 'Création d\'une nouvelle clé de licence', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/132.0.0.0 Safari/537.36 OPR/117.0.0.0 (Edition std-1)', '2025-04-22 12:38:59', '2025-04-22 12:38:59'),
(23, 43, 'create', 'Création d\'une nouvelle clé de licence', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/132.0.0.0 Safari/537.36 OPR/117.0.0.0 (Edition std-1)', '2025-04-22 12:38:59', '2025-04-22 12:38:59'),
(24, 44, 'create', 'Création d\'une nouvelle clé de licence', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/132.0.0.0 Safari/537.36 OPR/117.0.0.0 (Edition std-1)', '2025-04-22 12:38:59', '2025-04-22 12:38:59'),
(25, 45, 'create', 'Création d\'une nouvelle clé de licence', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/132.0.0.0 Safari/537.36 OPR/117.0.0.0 (Edition std-1)', '2025-04-22 12:38:59', '2025-04-22 12:38:59'),
(26, 46, 'create', 'Création d\'une nouvelle clé de licence', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/132.0.0.0 Safari/537.36 OPR/117.0.0.0 (Edition std-1)', '2025-04-22 12:38:59', '2025-04-22 12:38:59'),
(27, 47, 'create', 'Création d\'une nouvelle clé de licence', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/132.0.0.0 Safari/537.36 OPR/117.0.0.0 (Edition std-1)', '2025-04-22 12:38:59', '2025-04-22 12:38:59'),
(28, 48, 'create', 'Création d\'une nouvelle clé de licence', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/132.0.0.0 Safari/537.36 OPR/117.0.0.0 (Edition std-1)', '2025-04-22 12:38:59', '2025-04-22 12:38:59'),
(29, 49, 'create', 'Création d\'une nouvelle clé de licence', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/132.0.0.0 Safari/537.36 OPR/117.0.0.0 (Edition std-1)', '2025-04-22 12:38:59', '2025-04-22 12:38:59'),
(30, 50, 'create', 'Création d\'une nouvelle clé de licence', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/132.0.0.0 Safari/537.36 OPR/117.0.0.0 (Edition std-1)', '2025-04-22 12:38:59', '2025-04-22 12:38:59'),
(31, 51, 'create', 'Création d\'une nouvelle clé de licence', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/132.0.0.0 Safari/537.36 OPR/117.0.0.0 (Edition std-1)', '2025-04-22 12:38:59', '2025-04-22 12:38:59'),
(32, 52, 'create', 'Création d\'une nouvelle clé de licence', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/132.0.0.0 Safari/537.36 OPR/117.0.0.0 (Edition std-1)', '2025-04-22 12:38:59', '2025-04-22 12:38:59'),
(33, 53, 'create', 'Création d\'une nouvelle clé de licence', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/132.0.0.0 Safari/537.36 OPR/117.0.0.0 (Edition std-1)', '2025-04-22 12:38:59', '2025-04-22 12:38:59'),
(34, 54, 'create', 'Création d\'une nouvelle clé de licence', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/132.0.0.0 Safari/537.36 OPR/117.0.0.0 (Edition std-1)', '2025-04-22 12:38:59', '2025-04-22 12:38:59'),
(35, 55, 'create', 'Création d\'une nouvelle clé de licence', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/132.0.0.0 Safari/537.36 OPR/117.0.0.0 (Edition std-1)', '2025-04-22 12:38:59', '2025-04-22 12:38:59'),
(36, 56, 'create', 'Création d\'une nouvelle clé de licence', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/132.0.0.0 Safari/537.36 OPR/117.0.0.0 (Edition std-1)', '2025-04-22 12:38:59', '2025-04-22 12:38:59'),
(37, 57, 'create', 'Création d\'une nouvelle clé de licence', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/132.0.0.0 Safari/537.36 OPR/117.0.0.0 (Edition std-1)', '2025-04-22 12:38:59', '2025-04-22 12:38:59'),
(38, 58, 'create', 'Création d\'une nouvelle clé de licence', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/132.0.0.0 Safari/537.36 OPR/117.0.0.0 (Edition std-1)', '2025-04-22 12:38:59', '2025-04-22 12:38:59'),
(39, 59, 'create', 'Création d\'une nouvelle clé de licence', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/132.0.0.0 Safari/537.36 OPR/117.0.0.0 (Edition std-1)', '2025-04-22 12:38:59', '2025-04-22 12:38:59'),
(40, 60, 'create', 'Création d\'une nouvelle clé de licence', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/132.0.0.0 Safari/537.36 OPR/117.0.0.0 (Edition std-1)', '2025-04-22 12:38:59', '2025-04-22 12:38:59'),
(41, 61, 'create', 'Création d\'une nouvelle clé de licence', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/132.0.0.0 Safari/537.36 OPR/117.0.0.0 (Edition std-1)', '2025-04-22 12:54:44', '2025-04-22 12:54:44'),
(42, 62, 'create', 'Création d\'une nouvelle clé de licence', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/132.0.0.0 Safari/537.36 OPR/117.0.0.0 (Edition std-1)', '2025-04-22 12:54:44', '2025-04-22 12:54:44'),
(43, 63, 'create', 'Création d\'une nouvelle clé de licence', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/132.0.0.0 Safari/537.36 OPR/117.0.0.0 (Edition std-1)', '2025-04-22 12:54:44', '2025-04-22 12:54:44'),
(44, 64, 'create', 'Création d\'une nouvelle clé de licence', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/132.0.0.0 Safari/537.36 OPR/117.0.0.0 (Edition std-1)', '2025-04-22 12:54:44', '2025-04-22 12:54:44'),
(45, 65, 'create', 'Création d\'une nouvelle clé de licence', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/132.0.0.0 Safari/537.36 OPR/117.0.0.0 (Edition std-1)', '2025-04-22 12:54:44', '2025-04-22 12:54:44'),
(46, 66, 'create', 'Création d\'une nouvelle clé de licence', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/132.0.0.0 Safari/537.36 OPR/117.0.0.0 (Edition std-1)', '2025-04-22 12:54:44', '2025-04-22 12:54:44'),
(47, 67, 'create', 'Création d\'une nouvelle clé de licence', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/132.0.0.0 Safari/537.36 OPR/117.0.0.0 (Edition std-1)', '2025-04-22 12:54:44', '2025-04-22 12:54:44'),
(48, 68, 'create', 'Création d\'une nouvelle clé de licence', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/132.0.0.0 Safari/537.36 OPR/117.0.0.0 (Edition std-1)', '2025-04-22 12:54:44', '2025-04-22 12:54:44'),
(49, 69, 'create', 'Création d\'une nouvelle clé de licence', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/132.0.0.0 Safari/537.36 OPR/117.0.0.0 (Edition std-1)', '2025-04-22 12:54:44', '2025-04-22 12:54:44'),
(50, 70, 'create', 'Création d\'une nouvelle clé de licence', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/132.0.0.0 Safari/537.36 OPR/117.0.0.0 (Edition std-1)', '2025-04-22 12:54:44', '2025-04-22 12:54:44'),
(51, 71, 'create', 'Création d\'une nouvelle clé de licence', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/132.0.0.0 Safari/537.36 OPR/117.0.0.0 (Edition std-1)', '2025-04-22 12:54:44', '2025-04-22 12:54:44'),
(52, 72, 'create', 'Création d\'une nouvelle clé de licence', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/132.0.0.0 Safari/537.36 OPR/117.0.0.0 (Edition std-1)', '2025-04-22 12:54:44', '2025-04-22 12:54:44'),
(53, 73, 'create', 'Création d\'une nouvelle clé de licence', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/132.0.0.0 Safari/537.36 OPR/117.0.0.0 (Edition std-1)', '2025-04-22 12:54:44', '2025-04-22 12:54:44'),
(54, 74, 'create', 'Création d\'une nouvelle clé de licence', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/132.0.0.0 Safari/537.36 OPR/117.0.0.0 (Edition std-1)', '2025-04-22 12:54:44', '2025-04-22 12:54:44'),
(55, 75, 'create', 'Création d\'une nouvelle clé de licence', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/132.0.0.0 Safari/537.36 OPR/117.0.0.0 (Edition std-1)', '2025-04-22 12:54:44', '2025-04-22 12:54:44'),
(56, 76, 'create', 'Création d\'une nouvelle clé de licence', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/132.0.0.0 Safari/537.36 OPR/117.0.0.0 (Edition std-1)', '2025-04-22 12:54:44', '2025-04-22 12:54:44'),
(57, 77, 'create', 'Création d\'une nouvelle clé de licence', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/132.0.0.0 Safari/537.36 OPR/117.0.0.0 (Edition std-1)', '2025-04-22 12:54:44', '2025-04-22 12:54:44'),
(58, 78, 'create', 'Création d\'une nouvelle clé de licence', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/132.0.0.0 Safari/537.36 OPR/117.0.0.0 (Edition std-1)', '2025-04-22 12:54:44', '2025-04-22 12:54:44'),
(59, 79, 'create', 'Création d\'une nouvelle clé de licence', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/132.0.0.0 Safari/537.36 OPR/117.0.0.0 (Edition std-1)', '2025-04-22 12:54:44', '2025-04-22 12:54:44'),
(60, 80, 'create', 'Création d\'une nouvelle clé de licence', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/132.0.0.0 Safari/537.36 OPR/117.0.0.0 (Edition std-1)', '2025-04-22 12:54:44', '2025-04-22 12:54:44'),
(61, 81, 'create', 'Création d\'une nouvelle clé de licence', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/132.0.0.0 Safari/537.36 OPR/117.0.0.0 (Edition std-1)', '2025-04-22 12:54:44', '2025-04-22 12:54:44'),
(62, 82, 'create', 'Création d\'une nouvelle clé de licence', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/132.0.0.0 Safari/537.36 OPR/117.0.0.0 (Edition std-1)', '2025-04-22 12:54:44', '2025-04-22 12:54:44'),
(63, 83, 'create', 'Création d\'une nouvelle clé de licence', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/132.0.0.0 Safari/537.36 OPR/117.0.0.0 (Edition std-1)', '2025-04-22 12:54:44', '2025-04-22 12:54:44'),
(64, 84, 'create', 'Création d\'une nouvelle clé de licence', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/132.0.0.0 Safari/537.36 OPR/117.0.0.0 (Edition std-1)', '2025-04-22 12:54:44', '2025-04-22 12:54:44'),
(65, 85, 'create', 'Création d\'une nouvelle clé de licence', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/132.0.0.0 Safari/537.36 OPR/117.0.0.0 (Edition std-1)', '2025-04-22 12:54:44', '2025-04-22 12:54:44'),
(66, 86, 'create', 'Création d\'une nouvelle clé de licence', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/132.0.0.0 Safari/537.36 OPR/117.0.0.0 (Edition std-1)', '2025-04-22 12:54:44', '2025-04-22 12:54:44'),
(67, 87, 'create', 'Création d\'une nouvelle clé de licence', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/132.0.0.0 Safari/537.36 OPR/117.0.0.0 (Edition std-1)', '2025-04-22 12:54:44', '2025-04-22 12:54:44'),
(68, 88, 'create', 'Création d\'une nouvelle clé de licence', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/132.0.0.0 Safari/537.36 OPR/117.0.0.0 (Edition std-1)', '2025-04-22 12:54:44', '2025-04-22 12:54:44'),
(69, 89, 'create', 'Création d\'une nouvelle clé de licence', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/132.0.0.0 Safari/537.36 OPR/117.0.0.0 (Edition std-1)', '2025-04-22 12:54:44', '2025-04-22 12:54:44'),
(70, 90, 'create', 'Création d\'une nouvelle clé de licence', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/132.0.0.0 Safari/537.36 OPR/117.0.0.0 (Edition std-1)', '2025-04-22 12:54:44', '2025-04-22 12:54:44'),
(71, 91, 'create', 'Création d\'une nouvelle clé de licence', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/132.0.0.0 Safari/537.36 OPR/117.0.0.0 (Edition std-1)', '2025-04-22 12:54:44', '2025-04-22 12:54:44'),
(72, 92, 'create', 'Création d\'une nouvelle clé de licence', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/132.0.0.0 Safari/537.36 OPR/117.0.0.0 (Edition std-1)', '2025-04-22 12:54:44', '2025-04-22 12:54:44'),
(73, 93, 'create', 'Création d\'une nouvelle clé de licence', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/132.0.0.0 Safari/537.36 OPR/117.0.0.0 (Edition std-1)', '2025-04-22 12:54:44', '2025-04-22 12:54:44'),
(74, 94, 'create', 'Création d\'une nouvelle clé de licence', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/132.0.0.0 Safari/537.36 OPR/117.0.0.0 (Edition std-1)', '2025-04-22 12:54:44', '2025-04-22 12:54:44'),
(75, 95, 'create', 'Création d\'une nouvelle clé de licence', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/132.0.0.0 Safari/537.36 OPR/117.0.0.0 (Edition std-1)', '2025-04-22 12:54:44', '2025-04-22 12:54:44'),
(76, 96, 'create', 'Création d\'une nouvelle clé de licence', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/132.0.0.0 Safari/537.36 OPR/117.0.0.0 (Edition std-1)', '2025-04-22 12:54:44', '2025-04-22 12:54:44'),
(77, 97, 'create', 'Création d\'une nouvelle clé de licence', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/132.0.0.0 Safari/537.36 OPR/117.0.0.0 (Edition std-1)', '2025-04-22 12:54:44', '2025-04-22 12:54:44'),
(78, 98, 'create', 'Création d\'une nouvelle clé de licence', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/132.0.0.0 Safari/537.36 OPR/117.0.0.0 (Edition std-1)', '2025-04-22 12:54:44', '2025-04-22 12:54:44'),
(79, 99, 'create', 'Création d\'une nouvelle clé de licence', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/132.0.0.0 Safari/537.36 OPR/117.0.0.0 (Edition std-1)', '2025-04-22 12:54:44', '2025-04-22 12:54:44'),
(80, 100, 'create', 'Création d\'une nouvelle clé de licence', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/132.0.0.0 Safari/537.36 OPR/117.0.0.0 (Edition std-1)', '2025-04-22 12:54:44', '2025-04-22 12:54:44'),
(81, 101, 'create', 'Création d\'une nouvelle clé de licence', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/132.0.0.0 Safari/537.36 OPR/117.0.0.0 (Edition std-1)', '2025-04-22 12:54:44', '2025-04-22 12:54:44'),
(82, 102, 'create', 'Création d\'une nouvelle clé de licence', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/132.0.0.0 Safari/537.36 OPR/117.0.0.0 (Edition std-1)', '2025-04-22 12:54:44', '2025-04-22 12:54:44'),
(83, 103, 'create', 'Création d\'une nouvelle clé de licence', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/132.0.0.0 Safari/537.36 OPR/117.0.0.0 (Edition std-1)', '2025-04-22 12:54:44', '2025-04-22 12:54:44'),
(84, 104, 'create', 'Création d\'une nouvelle clé de licence', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/132.0.0.0 Safari/537.36 OPR/117.0.0.0 (Edition std-1)', '2025-04-22 12:54:44', '2025-04-22 12:54:44'),
(85, 105, 'create', 'Création d\'une nouvelle clé de licence', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/132.0.0.0 Safari/537.36 OPR/117.0.0.0 (Edition std-1)', '2025-04-22 12:54:44', '2025-04-22 12:54:44'),
(86, 106, 'create', 'Création d\'une nouvelle clé de licence', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/132.0.0.0 Safari/537.36 OPR/117.0.0.0 (Edition std-1)', '2025-04-22 12:54:44', '2025-04-22 12:54:44'),
(87, 107, 'create', 'Création d\'une nouvelle clé de licence', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/132.0.0.0 Safari/537.36 OPR/117.0.0.0 (Edition std-1)', '2025-04-22 12:54:44', '2025-04-22 12:54:44'),
(88, 108, 'create', 'Création d\'une nouvelle clé de licence', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/132.0.0.0 Safari/537.36 OPR/117.0.0.0 (Edition std-1)', '2025-04-22 12:54:44', '2025-04-22 12:54:44'),
(89, 109, 'create', 'Création d\'une nouvelle clé de licence', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/132.0.0.0 Safari/537.36 OPR/117.0.0.0 (Edition std-1)', '2025-04-22 12:54:44', '2025-04-22 12:54:44'),
(90, 110, 'create', 'Création d\'une nouvelle clé de licence', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/132.0.0.0 Safari/537.36 OPR/117.0.0.0 (Edition std-1)', '2025-04-22 12:54:44', '2025-04-22 12:54:44'),
(91, 70, 'suspend', 'Suspension de la clé de licence', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:138.0) Gecko/20100101 Firefox/138.0', '2025-05-08 01:13:41', '2025-05-08 01:13:41'),
(92, 69, 'revoke', 'Révocation de la clé de licence', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:138.0) Gecko/20100101 Firefox/138.0', '2025-05-08 01:13:59', '2025-05-08 01:13:59'),
(93, 109, 'status_change', 'Changement de statut de la clé de active à suspended', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:139.0) Gecko/20100101 Firefox/139.0', '2025-06-10 19:17:13', '2025-06-10 19:17:13');

-- --------------------------------------------------------

--
-- Structure de la table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('4Z5XQB0dVbEIz1PG2kq0sYsIThmjPvtAC8Uoafv3', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:139.0) Gecko/20100101 Firefox/139.0', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoiRmxTU1lhbmR2bHBZQ2k5Vmx0NU5TNlZWcVRPVjZqVXdsd0VwaXJyNSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9wcm9qZWN0cy9jcmVhdGUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjY6ImxvY2FsZSI7czoyOiJmciI7czo1MjoibG9naW5fYWRtaW5fNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToyO3M6MjA6ImxpY2Vuc2VfY2hlY2tfcmVzdWx0IjtiOjE7fQ==', 1750194757),
('AnqsThaw6aFzO1b5YB5MX41Igg0vYFIuzoR6qsWL', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:139.0) Gecko/20100101 Firefox/139.0', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiRUVtT25sNmc3Zms0Vkx0NTBHUFpXZG1LSnZBU3ZiZ2IydmU2QjE1QyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDM6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9hZG1pbi9sb2dpbj9sb2NhbGU9ZW4iO31zOjM6InVybCI7YToxOntzOjg6ImludGVuZGVkIjtzOjIxOiJodHRwOi8vbG9jYWxob3N0OjgwMDAiO31zOjY6ImxvY2FsZSI7czoyOiJlbiI7fQ==', 1750176928),
('EEMVwgNRRcrB0vVuES2iDcsqVjl1VVYRY3MCmDhZ', NULL, '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/128.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoicWtWVThZekVlTndITnBUTWRGdjBFWEN1elZaNHY1aXpNWG5PRHAxRSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9hZG1pbi9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1750178028),
('HpZKYuNm7O9QulrgOYATbO6JrkbimxKMNUeSTHib', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:139.0) Gecko/20100101 Firefox/139.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiRklJMDdlRHJZc1pWTmNxNk9EVHlwengwTmN5eWRTdW9DcXAyYjFZTSI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoyMToiaHR0cDovL2xvY2FsaG9zdDo4MDAwIjt9czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9hZG1pbi9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1750177451),
('juuaUreetJjCZ75pCSPFWlYadTbuRiiK7SBVfXKO', 2, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:139.0) Gecko/20100101 Firefox/139.0', 'YTo5OntzOjY6Il90b2tlbiI7czo0MDoiZkxLYlAzNmZ3R1k5bzRQcGZTQ2c2NGFlSGpGY0x2bEd5UEloaVBDVSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDg6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC93ZWItdHJhbnNsYXRpb25zP2xvY2FsZT1mciI7fXM6MzoidXJsIjthOjA6e31zOjUyOiJsb2dpbl9hZG1pbl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjI7czoyMToiZGFzaGJvYXJkX3Zpc2l0X2NvdW50IjtpOjE7czoyMDoibGljZW5zZV9jaGVja19yZXN1bHQiO2I6MTtzOjYyOiJsaWNlbnNlX2NoZWNrX3Nlc3Npb25fanV1YVVyZWV0SmpDWjc1cENTUEZXbFlhZFRidVJpaUs3U0JWZlhLTyI7YjoxO3M6NjoibG9jYWxlIjtzOjI6ImVuIjt9', 1750176263),
('PkhY5KRxsd6O7iwBYUN1TPumgLiY64dqbuSLHXiJ', NULL, '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/128.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiZTdnZEFLTmozTVZEOVlPRTNBVWY2YndQUTVaWUJsMjk4MnJwQXhwaCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9hZG1pbi9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1750177894),
('rupbuc9kZoYcL8VRDLq3cv8ip83fnHSRtCHQSseX', NULL, '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/128.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiSjhNNUxmNVJsM213TVhMU21aYzYwMUdoWUVSVThCcWc3bEFCRFRibiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9hZG1pbi9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1750177498),
('sIRMmeclEQ29LBlKDnJ5BuGsBoP2zHFgOK3LyZxq', NULL, '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/128.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiZ3Q3TGJNamhtMmdJNVpxRUFlcUJkNEN4SDJWRnBMR1NmMXgxVzllTyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9hZG1pbi9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1750178087),
('u6caCImb0sIlT8MKCfH06jtGIf8axe7TmJ9W2AZ0', 2, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:139.0) Gecko/20100101 Firefox/139.0', 'YToxMDp7czo2OiJfdG9rZW4iO3M6NDA6Ik1SUGFkUjFrV21hQVI1U1M2ZktGVHlmakViQTNrd2JqU0FVRUVIcGQiO3M6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjQ4OiJodHRwOi8vbG9jYWxob3N0OjgwMDAvd2ViLXRyYW5zbGF0aW9ucz9sb2NhbGU9ZW4iO31zOjM6InVybCI7YTowOnt9czo1MjoibG9naW5fYWRtaW5fNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToyO3M6MjE6ImRhc2hib2FyZF92aXNpdF9jb3VudCI7aToyO3M6MjA6ImxpY2Vuc2VfY2hlY2tfcmVzdWx0IjtiOjE7czo2MjoibGljZW5zZV9jaGVja19zZXNzaW9uX2p1dWFVcmVldEpqQ1o3NXBDU1BGV2xZYWRUYnVSaWlLN1NCVmZYS08iO2I6MTtzOjY6ImxvY2FsZSI7czoyOiJmciI7czo2MjoibGljZW5zZV9jaGVja19zZXNzaW9uX3U2Y2FDSW1iMHNJbFQ4TUtDZkgwNmp0R0lmOGF4ZTdUbUo5VzJBWjAiO2I6MTt9', 1750176278),
('w4bem111qhQyJakiAn4WLpMwQbUbt8L5JPyclXxM', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:139.0) Gecko/20100101 Firefox/139.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoieUN3empqYnZweVp2NW5OU0ZJZkMxdWY5ajRicERyN0s0YU9odzh0VCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9hZG1pbi9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6MzoidXJsIjthOjE6e3M6ODoiaW50ZW5kZWQiO3M6MjE6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMCI7fX0=', 1750178099),
('zQZy56yOpMGtr4Rf37BoUN6ZKIo9AUbL755XThqB', NULL, '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/128.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoibDFEV0trUENEV2pMMVhFRjIzNFpadkVieDJuVllYODFUZ2hWWEt1SCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9hZG1pbi9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1750178173),
('zXShvKIomWSoQge7hi7ltmZePR7CgppXqpB4rJpc', NULL, '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/128.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoidzNvRFNUWVhCUVBPZlZwQW82YlluR1NFeUx4V0JKdHd2NzBMYjFJbCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9hZG1pbi9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1750178135);

-- --------------------------------------------------------

--
-- Structure de la table `settings`
--

CREATE TABLE `settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `key` varchar(191) NOT NULL,
  `value` text DEFAULT NULL,
  `description` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `settings`
--

INSERT INTO `settings` (`id`, `key`, `value`, `description`, `created_at`, `updated_at`) VALUES
(1, 'license_check_settings', '{\"last_check\":null,\"login_count\":2,\"check_frequency\":\"5\",\"last_status\":null,\"warning_shown\":false}', 'Paramètres de vérification périodique de licence', '2025-05-26 15:36:40', '2025-05-26 15:46:12'),
(2, 'license_check_frequency', '5', 'Fréquence de vérification de licence', '2025-05-26 15:57:00', '2025-06-17 18:55:01'),
(3, 'last_license_check', '2025-06-17 20:55:01', 'Date de la dernière vérification de licence', '2025-05-26 15:57:00', '2025-06-17 18:55:01'),
(4, 'license_valid', '1', 'Statut de validité de la licence', '2025-05-26 15:57:00', '2025-06-17 18:55:01'),
(5, 'license_status', 'error', NULL, '2025-05-26 16:54:01', '2025-06-15 22:31:10'),
(6, 'license_expiry_date', NULL, NULL, '2025-05-26 16:54:01', '2025-05-26 16:54:01'),
(7, 'license_registered_domain', NULL, NULL, '2025-05-26 16:54:01', '2025-05-26 16:54:01'),
(8, 'license_registered_ip', NULL, NULL, '2025-05-26 16:54:01', '2025-05-26 16:54:01'),
(9, 'license_last_verified', NULL, NULL, '2025-05-26 16:54:01', '2025-05-26 16:54:01'),
(10, 'debug_api_response', '{\"status\":\"error\",\"message\":\"Domaine non autoris\\u00e9 pour cette cl\\u00e9\"}', NULL, '2025-05-26 17:10:26', '2025-06-15 22:31:10'),
(11, 'debug_api_http_code', '200', NULL, '2025-05-26 17:10:26', '2025-06-01 01:20:57'),
(12, 'debug_expiry_date', 'non trouvée', NULL, '2025-05-26 17:10:26', '2025-05-26 17:10:26');

-- --------------------------------------------------------

--
-- Structure de la table `subscriptions`
--

CREATE TABLE `subscriptions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tenant_id` bigint(20) UNSIGNED NOT NULL,
  `plan_id` bigint(20) UNSIGNED NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'active',
  `trial_ends_at` timestamp NULL DEFAULT NULL,
  `starts_at` timestamp NULL DEFAULT NULL,
  `ends_at` timestamp NULL DEFAULT NULL,
  `canceled_at` timestamp NULL DEFAULT NULL,
  `stripe_subscription_id` varchar(255) DEFAULT NULL,
  `paypal_subscription_id` varchar(255) DEFAULT NULL,
  `payment_method` varchar(255) DEFAULT NULL,
  `renewal_price` decimal(10,2) DEFAULT NULL,
  `billing_cycle` varchar(255) NOT NULL DEFAULT 'monthly',
  `auto_renew` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `support_tickets`
--

CREATE TABLE `support_tickets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `subject` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'open',
  `priority` varchar(255) NOT NULL DEFAULT 'medium',
  `category` varchar(255) DEFAULT NULL,
  `attachments` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`attachments`)),
  `last_reply_at` timestamp NULL DEFAULT NULL,
  `closed_at` timestamp NULL DEFAULT NULL,
  `closed_by_id` bigint(20) UNSIGNED DEFAULT NULL,
  `closed_by_type` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `telescope_entries`
--

CREATE TABLE `telescope_entries` (
  `sequence` bigint(20) UNSIGNED NOT NULL,
  `uuid` char(36) NOT NULL,
  `batch_id` char(36) NOT NULL,
  `family_hash` varchar(255) DEFAULT NULL,
  `should_display_on_index` tinyint(1) NOT NULL DEFAULT 1,
  `type` varchar(20) NOT NULL,
  `content` longtext NOT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `telescope_entries_tags`
--

CREATE TABLE `telescope_entries_tags` (
  `entry_uuid` char(36) NOT NULL,
  `tag` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `telescope_monitoring`
--

CREATE TABLE `telescope_monitoring` (
  `tag` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `tenants`
--

CREATE TABLE `tenants` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `licence_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `domain` varchar(255) NOT NULL,
  `database` varchar(255) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'active',
  `settings` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`settings`)),
  `subscription_id` varchar(191) DEFAULT NULL,
  `subscription_status` varchar(255) NOT NULL DEFAULT 'trial',
  `subscription_ends_at` timestamp NULL DEFAULT NULL,
  `trial_ends_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `serial_key_id` bigint(20) UNSIGNED DEFAULT NULL,
  `is_primary` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Indique si c''est le tenant principal (mode mono-compte)',
  `licence_features` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'Fonctionnalités héritées de la licence' CHECK (json_valid(`licence_features`)),
  `usage_stats` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'Statistiques d''utilisation du tenant' CHECK (json_valid(`usage_stats`)),
  `max_clients` int(11) DEFAULT NULL COMMENT 'Nombre maximum de clients autorisés',
  `max_projects` int(11) DEFAULT NULL COMMENT 'Nombre maximum de projets autorisés',
  `licence_mode` varchar(191) NOT NULL DEFAULT 'single' COMMENT 'Mode de licence: single ou saas',
  `licence_expires_at` timestamp NULL DEFAULT NULL COMMENT 'Date d''expiration de la licence'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `tenants`
--

INSERT INTO `tenants` (`id`, `licence_id`, `name`, `description`, `domain`, `database`, `status`, `settings`, `subscription_id`, `subscription_status`, `subscription_ends_at`, `trial_ends_at`, `created_at`, `updated_at`, `serial_key_id`, `is_primary`, `licence_features`, `usage_stats`, `max_clients`, `max_projects`, `licence_mode`, `licence_expires_at`) VALUES
(3, NULL, 'Primary Tenant', NULL, 'primary.local', NULL, 'active', '{\"theme\":\"default\",\"timezone\":\"Europe\\/Paris\",\"language\":\"fr\"}', NULL, 'active', NULL, NULL, '2025-06-11 14:00:56', '2025-06-11 14:00:56', 117, 1, '{\"project_management\":true,\"api_access\":true,\"email_templates\":true,\"basic_support\":true,\"multi_tenant\":false,\"advanced_analytics\":false,\"priority_support\":false}', '{\"projects_count\":0,\"clients_count\":0,\"api_calls_this_month\":0,\"storage_used_mb\":0}', 1, 10, 'single', NULL),
(4, NULL, 'Demo SaaS Tenant', NULL, 'demo.saas.local', NULL, 'active', '{\"theme\":\"modern\",\"timezone\":\"Europe\\/Paris\",\"language\":\"fr\",\"custom_branding\":true}', NULL, 'active', '2025-07-11 14:00:56', NULL, '2025-06-11 14:00:56', '2025-06-11 14:00:56', 118, 0, '{\"project_management\":true,\"api_access\":true,\"email_templates\":true,\"basic_support\":true,\"multi_tenant\":true,\"advanced_analytics\":true,\"priority_support\":true,\"white_label\":true,\"custom_domains\":true}', '{\"projects_count\":0,\"clients_count\":0,\"api_calls_this_month\":0,\"storage_used_mb\":0}', 1000, NULL, 'saas', '2026-06-11 14:00:56');

-- --------------------------------------------------------

--
-- Structure de la table `tenant_user`
--

CREATE TABLE `tenant_user` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tenant_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `role` varchar(191) NOT NULL DEFAULT 'user',
  `last_accessed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ticket_replies`
--

CREATE TABLE `ticket_replies` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `support_ticket_id` bigint(20) UNSIGNED NOT NULL,
  `user_type` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `message` text NOT NULL,
  `attachments` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`attachments`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `tenant_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `tenant_id`) VALUES
(1, 'Utilisateur Simple', 'user@example.com', NULL, '$2y$12$BSLn8v7ce8NCi3A3FefM1e6x1YntN6BQ.2/Td1tVYQ7QzfYTLoGk6', NULL, '2025-04-15 18:14:54', '2025-04-15 18:14:54', NULL);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `admins_email_unique` (`email`);

--
-- Index pour la table `admin_password_reset_tokens`
--
ALTER TABLE `admin_password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Index pour la table `admin_role`
--
ALTER TABLE `admin_role`
  ADD PRIMARY KEY (`admin_id`,`role_id`),
  ADD KEY `admin_role_role_id_foreign` (`role_id`);

--
-- Index pour la table `api_keys`
--
ALTER TABLE `api_keys`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `api_keys_key_unique` (`key`),
  ADD KEY `api_keys_project_id_foreign` (`project_id`);

--
-- Index pour la table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Index pour la table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Index pour la table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `clients_email_unique` (`email`),
  ADD KEY `clients_tenant_id_foreign` (`tenant_id`);

--
-- Index pour la table `email_templates`
--
ALTER TABLE `email_templates`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `email_variables`
--
ALTER TABLE `email_variables`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email_variables_name_unique` (`name`);

--
-- Index pour la table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Index pour la table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `invoices_subscription_id_foreign` (`subscription_id`),
  ADD KEY `invoices_payment_method_id_foreign` (`payment_method_id`),
  ADD KEY `invoices_tenant_id_status_index` (`tenant_id`,`status`);

--
-- Index pour la table `invoice_items`
--
ALTER TABLE `invoice_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `invoice_items_invoice_id_foreign` (`invoice_id`);

--
-- Index pour la table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Index pour la table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `licence_histories`
--
ALTER TABLE `licence_histories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `licence_histories_serial_key_id_foreign` (`serial_key_id`),
  ADD KEY `licence_histories_performed_by_foreign` (`performed_by`);

--
-- Index pour la table `mail_configs`
--
ALTER TABLE `mail_configs`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`);

--
-- Index pour la table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Index pour la table `payment_methods`
--
ALTER TABLE `payment_methods`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payment_methods_tenant_id_provider_index` (`tenant_id`,`provider`);

--
-- Index pour la table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_slug_unique` (`slug`);

--
-- Index pour la table `permission_role`
--
ALTER TABLE `permission_role`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `permission_role_role_id_foreign` (`role_id`);

--
-- Index pour la table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Index pour la table `plans`
--
ALTER TABLE `plans`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `plans_slug_unique` (`slug`);

--
-- Index pour la table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_slug_unique` (`slug`);

--
-- Index pour la table `role_user`
--
ALTER TABLE `role_user`
  ADD PRIMARY KEY (`role_id`,`user_id`),
  ADD KEY `role_user_user_id_foreign` (`user_id`);

--
-- Index pour la table `serial_keys`
--
ALTER TABLE `serial_keys`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `serial_keys_serial_key_unique` (`serial_key`),
  ADD KEY `serial_keys_serial_key_index` (`serial_key`),
  ADD KEY `serial_keys_status_index` (`status`),
  ADD KEY `serial_keys_project_id_index` (`project_id`),
  ADD KEY `serial_keys_domain_index` (`domain`),
  ADD KEY `serial_keys_ip_address_index` (`ip_address`),
  ADD KEY `serial_keys_expires_at_index` (`expires_at`),
  ADD KEY `serial_keys_created_at_index` (`created_at`),
  ADD KEY `serial_keys_updated_at_index` (`updated_at`),
  ADD KEY `serial_keys_licence_type_index` (`licence_type`),
  ADD KEY `serial_keys_is_saas_enabled_index` (`is_saas_enabled`),
  ADD KEY `serial_keys_licence_type_is_saas_enabled_index` (`licence_type`,`is_saas_enabled`);

--
-- Index pour la table `serial_key_histories`
--
ALTER TABLE `serial_key_histories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `serial_key_histories_serial_key_id_foreign` (`serial_key_id`),
  ADD KEY `serial_key_histories_admin_id_foreign` (`admin_id`);

--
-- Index pour la table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Index pour la table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `settings_key_unique` (`key`);

--
-- Index pour la table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subscriptions_plan_id_foreign` (`plan_id`),
  ADD KEY `subscriptions_tenant_id_status_index` (`tenant_id`,`status`),
  ADD KEY `subscriptions_stripe_subscription_id_index` (`stripe_subscription_id`),
  ADD KEY `subscriptions_paypal_subscription_id_index` (`paypal_subscription_id`);

--
-- Index pour la table `support_tickets`
--
ALTER TABLE `support_tickets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `support_tickets_client_id_foreign` (`client_id`);

--
-- Index pour la table `telescope_entries`
--
ALTER TABLE `telescope_entries`
  ADD PRIMARY KEY (`sequence`),
  ADD UNIQUE KEY `telescope_entries_uuid_unique` (`uuid`),
  ADD KEY `telescope_entries_batch_id_index` (`batch_id`),
  ADD KEY `telescope_entries_family_hash_index` (`family_hash`),
  ADD KEY `telescope_entries_created_at_index` (`created_at`),
  ADD KEY `telescope_entries_type_should_display_on_index_index` (`type`,`should_display_on_index`);

--
-- Index pour la table `telescope_entries_tags`
--
ALTER TABLE `telescope_entries_tags`
  ADD PRIMARY KEY (`entry_uuid`,`tag`),
  ADD KEY `telescope_entries_tags_tag_index` (`tag`);

--
-- Index pour la table `telescope_monitoring`
--
ALTER TABLE `telescope_monitoring`
  ADD PRIMARY KEY (`tag`);

--
-- Index pour la table `tenants`
--
ALTER TABLE `tenants`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tenants_domain_unique` (`domain`),
  ADD KEY `tenants_licence_id_index` (`licence_id`),
  ADD KEY `tenants_status_index` (`status`),
  ADD KEY `tenants_subscription_status_index` (`subscription_status`),
  ADD KEY `tenants_subscription_ends_at_subscription_status_index` (`subscription_ends_at`,`subscription_status`),
  ADD KEY `tenants_is_primary_index` (`is_primary`),
  ADD KEY `tenants_licence_mode_index` (`licence_mode`),
  ADD KEY `tenants_serial_key_id_status_index` (`serial_key_id`,`status`);

--
-- Index pour la table `tenant_user`
--
ALTER TABLE `tenant_user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tenant_user_tenant_id_user_id_unique` (`tenant_id`,`user_id`),
  ADD KEY `tenant_user_user_id_foreign` (`user_id`),
  ADD KEY `tenant_user_role_index` (`role`);

--
-- Index pour la table `ticket_replies`
--
ALTER TABLE `ticket_replies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ticket_replies_support_ticket_id_foreign` (`support_ticket_id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_tenant_id_foreign` (`tenant_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `api_keys`
--
ALTER TABLE `api_keys`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `clients`
--
ALTER TABLE `clients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `email_templates`
--
ALTER TABLE `email_templates`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `email_variables`
--
ALTER TABLE `email_variables`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `invoice_items`
--
ALTER TABLE `invoice_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `licence_histories`
--
ALTER TABLE `licence_histories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=944;

--
-- AUTO_INCREMENT pour la table `mail_configs`
--
ALTER TABLE `mail_configs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT pour la table `payment_methods`
--
ALTER TABLE `payment_methods`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT pour la table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `plans`
--
ALTER TABLE `plans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `projects`
--
ALTER TABLE `projects`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT pour la table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `serial_keys`
--
ALTER TABLE `serial_keys`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=120;

--
-- AUTO_INCREMENT pour la table `serial_key_histories`
--
ALTER TABLE `serial_key_histories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=94;

--
-- AUTO_INCREMENT pour la table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT pour la table `subscriptions`
--
ALTER TABLE `subscriptions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `support_tickets`
--
ALTER TABLE `support_tickets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `telescope_entries`
--
ALTER TABLE `telescope_entries`
  MODIFY `sequence` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75700;

--
-- AUTO_INCREMENT pour la table `tenants`
--
ALTER TABLE `tenants`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `tenant_user`
--
ALTER TABLE `tenant_user`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `ticket_replies`
--
ALTER TABLE `ticket_replies`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `admin_role`
--
ALTER TABLE `admin_role`
  ADD CONSTRAINT `admin_role_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `admin_role_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `api_keys`
--
ALTER TABLE `api_keys`
  ADD CONSTRAINT `api_keys_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `clients`
--
ALTER TABLE `clients`
  ADD CONSTRAINT `clients_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `invoices`
--
ALTER TABLE `invoices`
  ADD CONSTRAINT `invoices_payment_method_id_foreign` FOREIGN KEY (`payment_method_id`) REFERENCES `payment_methods` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `invoices_subscription_id_foreign` FOREIGN KEY (`subscription_id`) REFERENCES `subscriptions` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `invoices_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `invoice_items`
--
ALTER TABLE `invoice_items`
  ADD CONSTRAINT `invoice_items_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `licence_histories`
--
ALTER TABLE `licence_histories`
  ADD CONSTRAINT `licence_histories_performed_by_foreign` FOREIGN KEY (`performed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `licence_histories_serial_key_id_foreign` FOREIGN KEY (`serial_key_id`) REFERENCES `serial_keys` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `payment_methods`
--
ALTER TABLE `payment_methods`
  ADD CONSTRAINT `payment_methods_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `permission_role`
--
ALTER TABLE `permission_role`
  ADD CONSTRAINT `permission_role_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `permission_role_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `role_user`
--
ALTER TABLE `role_user`
  ADD CONSTRAINT `role_user_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `serial_keys`
--
ALTER TABLE `serial_keys`
  ADD CONSTRAINT `serial_keys_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `serial_key_histories`
--
ALTER TABLE `serial_key_histories`
  ADD CONSTRAINT `serial_key_histories_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `serial_key_histories_serial_key_id_foreign` FOREIGN KEY (`serial_key_id`) REFERENCES `serial_keys` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD CONSTRAINT `subscriptions_plan_id_foreign` FOREIGN KEY (`plan_id`) REFERENCES `plans` (`id`),
  ADD CONSTRAINT `subscriptions_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `support_tickets`
--
ALTER TABLE `support_tickets`
  ADD CONSTRAINT `support_tickets_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `telescope_entries_tags`
--
ALTER TABLE `telescope_entries_tags`
  ADD CONSTRAINT `telescope_entries_tags_entry_uuid_foreign` FOREIGN KEY (`entry_uuid`) REFERENCES `telescope_entries` (`uuid`) ON DELETE CASCADE;

--
-- Contraintes pour la table `tenants`
--
ALTER TABLE `tenants`
  ADD CONSTRAINT `tenants_licence_id_foreign` FOREIGN KEY (`licence_id`) REFERENCES `serial_keys` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `tenants_serial_key_id_foreign` FOREIGN KEY (`serial_key_id`) REFERENCES `serial_keys` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `tenant_user`
--
ALTER TABLE `tenant_user`
  ADD CONSTRAINT `tenant_user_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tenant_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `ticket_replies`
--
ALTER TABLE `ticket_replies`
  ADD CONSTRAINT `ticket_replies_support_ticket_id_foreign` FOREIGN KEY (`support_ticket_id`) REFERENCES `support_tickets` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
