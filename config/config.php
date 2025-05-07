<?php
// Application Settings
define('APP_NAME', 'My Music Player');
define('APP_URL', 'http://localhost/music_player');
define('APP_ROOT', dirname(dirname(__FILE__)));

// Session Configuration
define('SESSION_TIMEOUT', 3600); // 1 hour

// File Upload Settings
define('UPLOAD_DIR', 'uploads/');
define('MAX_FILE_SIZE', 10 * 1024 * 1024); // 10MB

// API Keys (if any)
define('SPOTIFY_API_KEY', 'your_api_key_here');

// Environment (development/production)
define('ENVIRONMENT', 'development');

// Error Reporting (based on environment)
if (ENVIRONMENT === 'development') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Include database configuration
require_once 'database.php';
