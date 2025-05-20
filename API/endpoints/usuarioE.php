<?php
require_once(dirname(__DIR__, 1) . '/middleware/jwt_auth.php');
require_once(dirname(__DIR__, 2) . '/modelo/ACTIVOS_FIJOS/actasM.php');

header('Content-Type: application/json');

// Middleware de autenticación
$auth = new TokenValidator();
$decoded = $auth->verify();

if (!$decoded) {
    http_response_code(401);
    echo json_encode(["error" => "Token inválido"]);
    exit;
}

$payload = json_decode(json_encode($decoded), true);
$actasM = new actasM($payload['data']['empresa'] ?? false);

if ($accion == 'usuario') {
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    echo json_encode($actasM->articulo($id));
}
