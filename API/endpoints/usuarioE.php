<?php
require_once __DIR__ . '/../middleware/jwt_auth.php';
require_once __DIR__ . '/../controladores/usuarioC.php';

header('Content-Type: application/json');

// Middleware de autenticación
$auth = new TokenValidator();
$decoded = $auth->verify();

if (!$decoded) {
    http_response_code(401);
    echo json_encode(["error" => "Token inválido"]);
    exit;
}

$controller = new usuarioC();

if ($accion == 'usuarios') {
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    $nombre = isset($_GET['nombre']) ? ($_GET['nombre']) : '';
    $controller->obtener($id, $nombre);
}
