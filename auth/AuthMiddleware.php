<?php
class AuthMiddleware {
    public static function authenticate() {
        $headers = getallheaders();
        if (!isset($headers['Authorization'])) {
            http_response_code(401);
            echo json_encode(['error' => 'Authorization header missing']);
            exit;
        }

        $authHeader = $headers['Authorization'];
        $token = str_replace('Bearer ', '', $authHeader);

        $decoded = JWTHandler::validateToken($token);
        if (!$decoded) {
            http_response_code(401);
            echo json_encode(['error' => 'Invalid or expired token']);
            exit;
        }

        return $decoded['sub']; // Returns user ID
    }

    public static function checkApiKey($apiKey, $secretKey) {
        $db = (new Database())->connect();
        $stmt = $db->prepare("
            SELECT user_id FROM api_keys 
            WHERE api_key = :api_key 
            AND secret_key = :secret_key
            AND is_active = TRUE
            AND (expires_at IS NULL OR expires_at > NOW())
        ");
        $stmt->execute([
            ':api_key' => $apiKey,
            ':secret_key' => $secretKey
        ]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>