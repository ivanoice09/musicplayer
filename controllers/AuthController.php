<?php
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../auth/JWT.php';

class AuthController
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    public function handleRegister()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            header('Location: /register');
            exit;
        }

        $data = [
            'username' => trim($_POST['username'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'password' => $_POST['password'] ?? ''
        ];

        $response = $this->register($data);

        if (isset($response['error'])) {
            $_SESSION['register_error'] = $response['error'];
            header('Location: /register');
            exit;
        }

        $_SESSION['user_id'] = $response['user_id'];
        $_SESSION['jwt_token'] = $response['token'];

        // Storing API keys in session is just for demo
        // in production its handled differently
        $_SESSION['api_key'] = $response['api_key'];
        $_SESSION['secret_key'] = $response['secret_key'];

        header('Location: /player');
    }

    public function handleLogin() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            header('Location: /login');
            exit;
        }
    
        $data = [
            'email' => trim($_POST['email'] ?? ''),
            'password' => $_POST['password'] ?? ''
        ];
    
        $response = $this->login($data);
    
        if (isset($response['error'])) {
            $_SESSION['login_error'] = $response['error'];
            header('Location: /login');
            exit;
        }
    
        $_SESSION['user_id'] = $this->userModel->findByEmail($data['email'])['id'];
        $_SESSION['jwt_token'] = $response['token'];
        
        if (isset($_POST['remember_me'])) {
            setcookie('jwt_token', $response['token'], time() + (7 * 24 * 60 * 60), '/', '', true, true);
        }
        
        header('Location: /player');
    }

    public function register($data)
    {
        // Validate input
        if (empty($data['username']) || empty($data['email']) || empty($data['password'])) {
            http_response_code(400);
            return ['error' => 'All fields are required'];
        }

        // Check if user exists
        if ($this->userModel->findByEmail($data['email'])) {
            http_response_code(409);
            return ['error' => 'Email already registered'];
        }

        // Create user
        $userId = $this->userModel->create([
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => password_hash($data['password'], PASSWORD_BCRYPT)
        ]);

        // Generate API keys
        $apiKeys = $this->userModel->generateApiKeys($userId);

        // Generate JWT
        $token = JWTHandler::generateToken($userId);

        return [
            'user_id' => $userId,
            'token' => $token,
            'api_key' => $apiKeys['api_key'],
            'secret_key' => $apiKeys['secret_key'] // Only returned once!
        ];
    }

    public function login($data)
    {
        $user = $this->userModel->findByEmail($data['email']);
        if (!$user || !password_verify($data['password'], $user['password_hash'])) {
            http_response_code(401);
            return ['error' => 'Invalid credentials'];
        }

        $token = JWTHandler::generateToken($user['id']);
        return ['token' => $token];
    }
}
