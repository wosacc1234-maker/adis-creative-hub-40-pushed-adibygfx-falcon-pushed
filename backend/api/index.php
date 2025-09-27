<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once '../config/config.php';
require_once '../classes/Auth.php';

// Simple routing
$request_uri = $_SERVER['REQUEST_URI'];
$path = parse_url($request_uri, PHP_URL_PATH);
$path = str_replace('/api', '', $path);
$method = $_SERVER['REQUEST_METHOD'];

// Remove leading slash
$path = ltrim($path, '/');
$segments = explode('/', $path);

$endpoint = $segments[0] ?? '';
$action = $segments[1] ?? '';
$id = $segments[2] ?? '';

// Route requests
try {
    switch ($endpoint) {
        case 'auth':
            require_once 'endpoints/auth.php';
            break;
            
        case 'pages':
            require_once 'endpoints/pages.php';
            break;
            
        case 'portfolio':
            require_once 'endpoints/portfolio.php';
            break;
            
        case 'services':
            require_once 'endpoints/services.php';
            break;
            
        case 'blog':
            require_once 'endpoints/blog.php';
            break;
            
        case 'testimonials':
            require_once 'endpoints/testimonials.php';
            break;
            
        case 'forms':
            require_once 'endpoints/forms.php';
            break;
            
        case 'media':
            require_once 'endpoints/media.php';
            break;
            
        case 'settings':
            require_once 'endpoints/settings.php';
            break;
            
        default:
            http_response_code(404);
            echo json_encode(['error' => 'Endpoint not found']);
            break;
    }
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Internal server error', 'message' => $e->getMessage()]);
}
?>