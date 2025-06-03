<?php

if ($route === '/encriptar') {
    if ($method === 'POST') {
        // $parametros = json_decode(file_get_contents("php://input"));
        $parametros = json_decode(file_get_contents("php://input"), true);
        $accion = 'encriptar';
        require_once(dirname(__DIR__, 1) . '/endpoints/generalE.php');
    } else {
        http_response_code(405);
        echo json_encode(["error" => "Método no permitido"]);
    }

    exit;
}

if ($route === '/desencriptar') {
    if ($method === 'POST') {
        // $parametros = json_decode(file_get_contents("php://input"));
        $parametros = json_decode(file_get_contents("php://input"), true);
        $accion = 'desencriptar';
        require_once(dirname(__DIR__, 1) . '/endpoints/generalE.php');
    } else {
        http_response_code(405);
        echo json_encode(["error" => "Método no permitido"]);
    }

    exit;
}