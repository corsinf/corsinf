<?php
require_once(dirname(__DIR__, 1) . '/middleware/jwt_auth.php');
require_once(dirname(__DIR__, 2) . '/modelo/ACTIVOS_FIJOS/actasM.php');

header('Content-Type: application/json');

// ───── Ejecutar API ─────
$api = new activosE();

if ($accion == 'lista') {
    echo json_encode($api->lista($parametros));
}

if ($accion == 'lista_get') {
    echo json_encode($api->lista($id));
}


class activosE
{
    private $auth;
    private $payload;
    private $actasM;

    public function __construct()
    {
        $this->auth = new TokenValidator();
        
        $decoded = $this->auth->verify();
        
        if (!$decoded) {
            http_response_code(401);
            echo json_encode(["error" => "Token inválido"]);
            exit;
        }
        
        $this->payload = json_decode(json_encode($decoded), true);
        $this->actasM = new actasM($this->payload['data']['empresa'] ?? false);
    }

    function lista($parametros)
    {
        return $this->actasM->articulo($parametros);
    }
}
