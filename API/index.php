<?php
header('Content-Type: application/json');

// CORS headers globales
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

// Responder a preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

// Define tu base path
$basePath = '/corsinf/API';

// Elimina el base path
$endpoint = substr($requestUri, strlen($basePath));
$endpoint = rtrim($endpoint, '/');

// Divide en partes (por ejemplo: ['usuario', '3'])
$parts = explode('/', $endpoint);

// Identifica la ruta principal y opcionalmente el ID
$route = isset($parts[1]) ? '/' . $parts[1] : '/';
$id = isset($parts[2]) ? intval($parts[2]) : null;

if ($route === '/login') {
    if ($method === 'POST') {
        require __DIR__ . '/auth/login.php';
    } else {
        http_response_code(405);
        echo json_encode(["error" => "Método no permitido"]);
    }
    exit;
}

if ($route === '/usuario') {
    if ($method === 'GET') {
        // Lo pasamos como variable global para que usuarioE.php lo use si existe
        if ($id !== null) {
            $_GET['id'] = $id;
            $accion = 'usuario';
        }
        require __DIR__ . '/endpoints/usuarioE.php';
    } else {
        http_response_code(405);
        echo json_encode(["error" => "Método no permitido"]);
    }
    exit;
}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/*
 * Módulo ACTIVOS FIJOS
 */
require_once __DIR__ . '/inicio/ACTIVOS_FIJOS.php';
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

http_response_code(404);
echo json_encode(["error" => "Ruta no encontrada"]);
