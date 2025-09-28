<?php
/**
 * Production Configuration Template for Hostinger Deployment
 * Copy this file to backend/config/config.php and update with your actual values
 */

// ============================================================================
// DATABASE CONFIGURATION - UPDATE WITH YOUR HOSTINGER DATABASE DETAILS
// ============================================================================
define('DB_HOST', 'localhost'); // Usually 'localhost' for Hostinger
define('DB_NAME', 'u123456789_portfolio'); // Your Hostinger database name
define('DB_USER', 'u123456789_user'); // Your Hostinger database username  
define('DB_PASS', 'your_secure_password'); // Your Hostinger database password

// ============================================================================
// SITE CONFIGURATION - UPDATE WITH YOUR ACTUAL DOMAIN
// ============================================================================
define('SITE_URL', 'https://yourdomain.com'); // Your actual domain name
define('ADMIN_URL', SITE_URL . '/backend/admin');
define('API_URL', SITE_URL . '/backend/api');

// ============================================================================
// FILE UPLOAD CONFIGURATION
// ============================================================================
define('UPLOAD_PATH', 'uploads/');
define('MAX_FILE_SIZE', 10 * 1024 * 1024); // 10MB - Hostinger limit
define('ALLOWED_FILE_TYPES', ['jpg', 'jpeg', 'png', 'gif', 'webp', 'pdf', 'mp4']);

// ============================================================================
// EMAIL CONFIGURATION - UPDATE WITH YOUR EMAIL SETTINGS
// ============================================================================

// Option 1: Gmail SMTP (Recommended)
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USERNAME', 'your-email@gmail.com'); // Your Gmail address
define('SMTP_PASSWORD', 'your-app-password'); // Gmail App Password (not regular password)

// Option 2: Hostinger SMTP (Alternative)
// define('SMTP_HOST', 'smtp.hostinger.com');
// define('SMTP_PORT', 587);
// define('SMTP_USERNAME', 'noreply@yourdomain.com');
// define('SMTP_PASSWORD', 'your-email-password');

// Email sender details
define('FROM_EMAIL', 'noreply@yourdomain.com'); // Your domain email
define('FROM_NAME', 'Adil GFX Portfolio');

// ============================================================================
// SECURITY CONFIGURATION - GENERATE NEW VALUES
// ============================================================================
define('JWT_SECRET', 'your-unique-jwt-secret-key-minimum-32-characters-long'); // Generate new secret
define('CSRF_TOKEN_NAME', 'csrf_token');

// ============================================================================
// INTEGRATION SETTINGS - UPDATE WITH YOUR ACCOUNTS
// ============================================================================

// Google Analytics
define('GOOGLE_ANALYTICS_ID', 'GA-XXXXXXXXX'); // Your GA tracking ID

// Meta Pixel (Facebook)
define('META_PIXEL_ID', 'XXXXXXXXXXXXXXXXX'); // Your Meta Pixel ID

// WhatsApp Business
define('WHATSAPP_NUMBER', '+1234567890'); // Your WhatsApp number

// Calendly Integration
define('CALENDLY_URL', 'https://calendly.com/yourusername'); // Your Calendly URL

// ============================================================================
// ENVIRONMENT SETTINGS
// ============================================================================
define('ENVIRONMENT', 'production'); // production, staging, development
define('DEBUG_MODE', false); // Set to false for production
define('LOG_ERRORS', true); // Enable error logging

// ============================================================================
// SESSION CONFIGURATION
// ============================================================================
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 1); // Requires HTTPS
ini_set('session.use_strict_mode', 1);
ini_set('session.cookie_lifetime', 86400); // 24 hours

// ============================================================================
// ERROR HANDLING
// ============================================================================
if (ENVIRONMENT === 'production') {
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);
    ini_set('error_log', __DIR__ . '/../admin/logs/error.log');
} else {
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
}

// ============================================================================
// TIMEZONE CONFIGURATION
// ============================================================================
date_default_timezone_set('America/New_York'); // Update to your timezone

// ============================================================================
// START SESSION
// ============================================================================
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ============================================================================
// HOSTINGER SPECIFIC OPTIMIZATIONS
// ============================================================================

// Memory limit optimization
ini_set('memory_limit', '256M');

// Execution time for large operations
ini_set('max_execution_time', 300);

// File upload limits
ini_set('upload_max_filesize', '10M');
ini_set('post_max_size', '10M');

// ============================================================================
// SECURITY HEADERS
// ============================================================================
if (ENVIRONMENT === 'production') {
    header('X-Content-Type-Options: nosniff');
    header('X-Frame-Options: DENY');
    header('X-XSS-Protection: 1; mode=block');
    header('Referrer-Policy: strict-origin-when-cross-origin');
}

// ============================================================================
// CONFIGURATION VALIDATION
// ============================================================================
function validateConfiguration() {
    $errors = [];
    
    // Check database configuration
    if (DB_HOST === 'localhost' && DB_NAME === 'portfolio_db') {
        $errors[] = 'Database configuration not updated from template';
    }
    
    // Check site URL
    if (SITE_URL === 'https://yourdomain.com') {
        $errors[] = 'Site URL not updated from template';
    }
    
    // Check email configuration
    if (SMTP_USERNAME === 'your-email@gmail.com') {
        $errors[] = 'Email configuration not updated from template';
    }
    
    // Check security keys
    if (JWT_SECRET === 'your-unique-jwt-secret-key-minimum-32-characters-long') {
        $errors[] = 'Security keys not updated from template';
    }
    
    return $errors;
}

// Validate configuration in development
if (ENVIRONMENT !== 'production') {
    $config_errors = validateConfiguration();
    if (!empty($config_errors)) {
        echo '<div style="background: #ff6b6b; color: white; padding: 20px; margin: 20px;">';
        echo '<h3>Configuration Errors:</h3>';
        foreach ($config_errors as $error) {
            echo '<p>• ' . htmlspecialchars($error) . '</p>';
        }
        echo '</div>';
    }
}

// ============================================================================
// HELPER FUNCTIONS
// ============================================================================

/**
 * Get site setting value
 */
function getSiteSetting($key, $default = '') {
    // This would connect to database and fetch setting
    // Placeholder for now
    return $default;
}

/**
 * Log system events
 */
function logEvent($message, $type = 'info') {
    if (LOG_ERRORS) {
        $log_message = date('Y-m-d H:i:s') . " [{$type}] " . $message . PHP_EOL;
        file_put_contents(__DIR__ . '/../admin/logs/system.log', $log_message, FILE_APPEND | LOCK_EX);
    }
}

/**
 * Generate secure random string
 */
function generateSecureToken($length = 32) {
    return bin2hex(random_bytes($length));
}

?>