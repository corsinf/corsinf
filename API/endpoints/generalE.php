<?php
require_once(dirname(__DIR__, 2) . '/db/codigos_globales.php');


header('Content-Type: application/json');

// ───── Ejecutar API ─────
$api = new generalE();

if ($accion == 'encriptar') {
    echo json_encode($api->encriptar($parametros));
}

if ($accion == 'desencriptar') {
    echo json_encode($api->desencriptar($parametros));
}


class generalE
{
    private $codigo_globales;

    public function __construct()
    {
        $this->codigo_globales = new codigos_globales();
    }

    function encriptar($parametros)
    {
        $valor = $parametros['valor'];
        return $this->codigo_globales->encriptar_alfanumerico($valor);
    }

    function desencriptar($parametros)
    {
        $valor = $parametros['valor'];
        return $this->codigo_globales->desencriptar_alfanumerico($valor);
    }
}
