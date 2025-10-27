<?php
date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 2) . '/modelo/XPACE_CUBE/reserva_servicioM.php');

$controlador = new reserva_servicioC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar($_POST['id'] ?? ''));
}

if (isset($_GET['insertar'])) {
    echo json_encode($controlador->insertar_editar($_POST['parametros']));
}

if (isset($_GET['eliminar'])) {
    echo json_encode($controlador->eliminar($_POST['id']));
}

if (isset($_GET['buscar'])) {
    $query = '';

    if (isset($_GET['q'])) {
        $query = $_GET['q'];
    }

    $parametros = array(
        'query' => $query,
    );

    echo json_encode($controlador->buscar($parametros));
}


class  reserva_servicioC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new reserva_servicioM();
    }

    function listar($id = '')
    {
        $datos = $this->modelo->listar_servicios_por_reserva($id);
        return $datos ?: []; // devuelve array vacío si no hay datos
    }

    function insertar_editar($parametros) {}

    function eliminar($id) {}

    //Para usar en select2
    function buscar($parametros) {}
}
