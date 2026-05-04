<?php
/**
 * Centralized configuration file
 * All application constants and settings should be defined here
 */

// Prevent direct access
defined('APP_NAME') or define('APP_NAME', 'Umbra Auto Dealership');

// Application Environment
defined('APP_ENV') or define('APP_ENV', 'development'); // development, production

// Error Reporting
if (APP_ENV === 'development') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Database Configuration
defined('DB_HOST') or define('DB_HOST', 'localhost');
defined('DB_NAME') or define('DB_NAME', 'umbra_db');
defined('DB_USER') or define('DB_USER', 'root');
defined('DB_PASS') or define('DB_PASS', '');
defined('DB_CHARSET') or define('DB_CHARSET', 'utf8mb4');

// Application Paths
defined('ROOT_PATH') or define('ROOT_PATH', dirname(__DIR__));
defined('APP_PATH') or define('APP_PATH', ROOT_PATH);
defined('CONTROLLERS_PATH') or define('CONTROLLERS_PATH', ROOT_PATH . '/controllers');
defined('MODELS_PATH') or define('MODELS_PATH', ROOT_PATH . '/models');
defined('VIEWS_PATH') or define('VIEWS_PATH', ROOT_PATH . '/views');
defined('CONFIG_PATH') or define('CONFIG_PATH', ROOT_PATH . '/config');

// Upload Configuration
defined('UPLOAD_DIR') or define('UPLOAD_DIR', ROOT_PATH . '/uploads');
defined('CARS_UPLOAD_DIR') or define('CARS_UPLOAD_DIR', UPLOAD_DIR . '/cars');
defined('NEWS_UPLOAD_DIR') or define('NEWS_UPLOAD_DIR', UPLOAD_DIR . '/news');
defined('MAX_FILE_SIZE') or define('MAX_FILE_SIZE', 5242880); // 5MB

// Session Configuration
defined('SESSION_LIFETIME') or define('SESSION_LIFETIME', 3600); // 1 hour

// User Roles
defined('ROLE_ADMIN') or define('ROLE_ADMIN', 'admin');
defined('ROLE_MANAGER') or define('ROLE_MANAGER', 'manager');
defined('ROLE_CONSULTANT') or define('ROLE_CONSULTANT', 'consultant');
defined('ROLE_USER') or define('ROLE_USER', 'user');

// Request Statuses
defined('STATUS_NEW') or define('STATUS_NEW', 'new');
defined('STATUS_IN_PROGRESS') or define('STATUS_IN_PROGRESS', 'in_progress');
defined('STATUS_COMPLETED') or define('STATUS_COMPLETED', 'completed');
defined('STATUS_CANCELLED') or define('STATUS_CANCELLED', 'cancelled');

// Pagination
defined('DEFAULT_PER_PAGE') or define('DEFAULT_PER_PAGE', 10);

// Security
defined('CSRF_ENABLED') or define('CSRF_ENABLED', true);
defined('PASSWORD_MIN_LENGTH') or define('PASSWORD_MIN_LENGTH', 6);