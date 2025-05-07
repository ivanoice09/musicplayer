<?php
require_once __DIR__ . '/../config/database.php';

class User {
    private $db;

    public function __construct() {
        $this->db = (new Database())->connect();
    }

    public function create($data) {
        $stmt = $this->db->prepare("
            INSERT INTO users (username, email, password_hash)
            VALUES (:username, :email, :password_hash)
        ");
        $stmt->execute([
            ':username' => $data['username'],
            ':email' => $data['email'],
            ':password_hash' => $data['password']
        ]);
        return $this->db->lastInsertId();
    }

    public function generateApiKeys($userId) {
        $apiKey = bin2hex(random_bytes(32));
        $secretKey = bin2hex(random_bytes(32));
        $expireDays = getenv('API_KEY_EXPIRE_DAYS') ?: 30;

        $stmt = $this->db->prepare("
            INSERT INTO api_keys 
            (user_id, api_key, secret_key, expires_at)
            VALUES (:user_id, :api_key, :secret_key, DATE_ADD(NOW(), INTERVAL :expire_days DAY))
        ");
        $stmt->execute([
            ':user_id' => $userId,
            ':api_key' => $apiKey,
            ':secret_key' => $secretKey,
            ':expire_days' => $expireDays
        ]);

        return [
            'api_key' => $apiKey,
            'secret_key' => $secretKey
        ];
    }

    public function findByEmail($email) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute([':email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Other user methods...
}
?>