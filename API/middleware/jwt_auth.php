<?php
require_once __DIR__ . '/../utils/jwt.php';

class TokenValidator
{
    private $token;
    private $headers;

    public function __construct()
    {
        $this->headers = getallheaders();
    }

    public function verify()
    {
        if (!$this->hasAuthorizationHeader()) {
            // Verifica si viene por cookie como alternativa
            if (isset($_COOKIE['token'])) {
                $this->token = $_COOKIE['token'];
            } else {
                return $this->response(401, "Token no proporcionado");
            }
        } else {
            $authHeader = $this->getAuthorizationToken();
            $this->token = str_replace("Bearer ", "", $authHeader);
        }

        try {
            $decoded = JWTHandler::verifyToken($this->token);
            return $decoded;
        } catch (Exception $e) {
            return $this->response(401, "Token inválido");
        }
    }

    private function hasAuthorizationHeader()
    {
        return isset($this->headers['Authorization']) ||
            isset($this->headers['authorization']) ||
            isset($_SERVER['HTTP_AUTHORIZATION']) ||
            isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION']);
    }

    private function getAuthorizationToken()
    {
        if (isset($this->headers['Authorization'])) {
            return $this->headers['Authorization'];
        } elseif (isset($this->headers['authorization'])) {
            return $this->headers['authorization'];
        } elseif (isset($_SERVER['HTTP_AUTHORIZATION'])) {
            return $_SERVER['HTTP_AUTHORIZATION'];
        } elseif (isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
            return $_SERVER['REDIRECT_HTTP_AUTHORIZATION'];
        }
        return null;
    }

    private function response($statusCode, $message)
    {
        http_response_code($statusCode);
        echo json_encode(["error" => $message]);
        exit;
    }
}

// Uso de la clase
$tokenValidator = new TokenValidator();
$decoded = $tokenValidator->verify();
