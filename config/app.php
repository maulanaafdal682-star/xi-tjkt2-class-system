<?php
/**
 * Application Configuration
 */

define('APP_NAME', 'XI TJKT 2 Class System');
define('APP_VERSION', '1.0.0');
define('APP_URL', 'http://localhost/xi-tjkt2-class-system');

// Set error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Session configuration
ini_set('session.gc_maxlifetime', 86400); // 24 hours
ini_set('session.cookie_lifetime', 86400);

// Upload configuration
define('MAX_UPLOAD_SIZE', 5 * 1024 * 1024); // 5 MB
define('ALLOWED_EXTENSIONS', ['jpg', 'jpeg', 'png', 'webp', 'gif']);

// QR Code configuration
define('QR_EXPIRY_MINUTES', 5); // QR Code berlaku 5 menit

// Pagination
define('ITEMS_PER_PAGE', 20);
