<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTHandler {
    private static $secret_key = "CORSINF_API_R";
    private static $encrypt = ['HS256'];

    public static function generateToken($data) {
        $issuedAt = time();
        $payload = [
            'iat' => $issuedAt,
            'exp' => $issuedAt + 3600, // 1 hora
            'data' => $data
        ];
        return JWT::encode($payload, self::$secret_key, self::$encrypt[0]);
    }

    public static function verifyToken($jwt) {
        return JWT::decode($jwt, new Key(self::$secret_key, self::$encrypt[0]));
    }
}
