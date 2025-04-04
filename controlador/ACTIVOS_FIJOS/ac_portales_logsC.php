<?php
date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 2) . '/modelo/ACTIVOS_FIJOS/ac_portales_logsM.php');

$controlador = new ac_portales_logsC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar($_POST['id'] ?? ''));
}

class ac_portales_logsC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new ac_portales_logsM();
    }

    function listar($id = '')
    {
        $datos = $this->modelo->log_portal_articulo();
        return $datos;
    }
}
