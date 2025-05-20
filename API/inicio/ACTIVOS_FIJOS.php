<?php

//Por post
if ($route === '/activos') {
    if ($method === 'POST') {
        $parametros = json_decode(file_get_contents("php://input"), true);
        $accion = 'lista';
        require_once(dirname(__DIR__, 1) . '/endpoints/activosE.php');
    } else {
        http_response_code(405);
        echo json_encode(["error" => "Método no permitido"]);
    }

    exit;
}

if ($route === '/activos_get') {
    if ($method === 'GET') {
        // Lo pasamos como variable global para que usuarioE.php lo use si existe
        if ($id !== null) {
            $_GET['id'] = $id;
            $accion = 'lista_get';
        }
        require_once(dirname(__DIR__, 1) . '/endpoints/activosE.php');
    } else {
        http_response_code(405);
        echo json_encode(["error" => "Método no permitido 1"]);
    }
    exit;
}
