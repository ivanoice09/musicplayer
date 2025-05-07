<?php
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTHandler {
    private static $secretKey;
    private static $expireTime;

    public static function init() {
        self::$secretKey = getenv('JWT_SECRET');
        self::$expireTime = getenv('JWT_EXPIRE') ?: 3600;
    }

    public static function generateToken($userId) {
        $issuedAt = time();
        $expire = $issuedAt + self::$expireTime;

        $payload = [
            'iat' => $issuedAt,
            'exp' => $expire,
            'sub' => $userId
        ];

        return JWT::encode($payload, self::$secretKey, 'HS256');
    }

    public static function validateToken($token) {
        try {
            $decoded = JWT::decode($token, new Key(self::$secretKey, 'HS256'));
            return (array) $decoded;
        } catch (Exception $e) {
            return false;
        }
    }
}

JWTHandler::init();
?>