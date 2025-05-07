<!-- ENTRY POINT -->
<?php
require_once 'config/config.php';
session_start();

// Simple router
$request = $_SERVER['REQUEST_URI'];
$base_path = '/musicplayer';
$request = str_replace($base_path, '', $request);

switch ($request) {
    case '/':
    case '':
    case '/home':
        // require_once 'controllers/HomeController.php';
        // $controller = new HomeController();
        // $controller->index();
        // break;

    case '/player':
        // require_once 'controllers/PlayerController.php';
        // $controller = new PlayerController();
        // $controller->index();
        // break;

    case '/register':
        require_once 'controllers/AuthController.php';
        $authController = new AuthController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $authController->handleRegister();
        } else {
            require_once 'views/auth/register.php';
        }
        break;

    case '/login':
        require_once 'controllers/AuthController.php';
        $authController = new AuthController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $authController->handleLogin();
        } else {
            require_once 'views/auth/login.php';
        }
        break;

    case preg_match('/^\/api\/.*/', $request) ? true : false:
        // API routes are handled separately
        break;

    default:
        http_response_code(404);
        require_once 'views/404.php';
        break;
}
?>