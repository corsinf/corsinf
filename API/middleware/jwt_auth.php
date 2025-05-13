<?php
require_once __DIR__ . '/../utils/jwt.php';

class TokenValidator {
    private $token;
    private $headers;

    public function __construct() {
        $this->headers = getallheaders();
    }

    // Método para obtener y verificar el token
    public function verify() {
        if (!$this->hasAuthorizationHeader()) {
            return $this->response(401, "Token no proporcionado");
        }

        $this->token = str_replace("Bearer ", "", $this->headers['Authorization']);

        try {
            $decoded = JWTHandler::verifyToken($this->token);
            return $decoded; // Retorna el payload decodificado si el token es válido
        } catch (Exception $e) {
            return $this->response(401, "Token inválido");
        }
    }

    // Verificar si el encabezado de autorización está presente
    private function hasAuthorizationHeader() {
        return isset($this->headers['Authorization']);
    }

    // Método para manejar las respuestas de error
    private function response($statusCode, $message) {
        http_response_code($statusCode);
        echo json_encode(["error" => $message]);
        exit;
    }
}

// Uso de la clase
$tokenValidator = new TokenValidator();
$decoded = $tokenValidator->verify();

// Si el token es válido, puedes usar $decoded para identificar al usuario
// Ejemplo: echo json_encode($decoded);
?>
