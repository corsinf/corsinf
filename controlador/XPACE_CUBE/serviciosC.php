<?php
date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 2) . '/modelo/XPACE_CUBE/serviciosM.php');

$controlador = new serviciosC();

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


if (isset($_GET['buscar_libres'])) {
    $query = isset($_GET['q']) ? trim($_GET['q']) : '';
    $id_espacio = isset($_GET['id_espacio']) && $_GET['id_espacio'] !== '' ? (int)$_GET['id_espacio'] : '';

    $parametros = array(
        'query' => $query,
        'id_espacio' => $id_espacio
    );

    echo json_encode($controlador->buscar_libres($parametros));
    exit;
}



class  serviciosC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new serviciosM();
    }

    function listar($id = '')
    {
        if ($id == "") {
            $datos = $this->modelo->where('estado', 1)->listar();
        } else {
            $datos = $this->modelo->where('id_servicio', $id)->where('estado', 1)->listar();
        }


        return $datos;
    }

    function insertar_editar($parametros) {}

    function eliminar($id) {}

    //Para usar en select2
    function buscar($parametros) {}


    public function buscar_libres($parametros)
    {
        $lista = [];

        // Obtener id_espacio si viene
        $id_espacio = isset($parametros['id_espacio']) && $parametros['id_espacio'] !== '' ? (int)$parametros['id_espacio'] : '';

        // Llamar al modelo pasando el id_espacio
        $datos = $this->modelo->listar_servicios_no_asignados($id_espacio);

        // Texto de búsqueda (opcional)
        $query = isset($parametros['query']) ? trim($parametros['query']) : '';

        foreach ($datos as $value) {
            if ($query === '' || stripos($value['nombre'], $query) !== false) {
                $lista[] = [
                    'id'   => $value['id_servicio'],
                    'text' => $value['nombre'],
                ];
            }
        }

        return $lista;
    }
}
