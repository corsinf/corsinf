<?php
date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 3) . '/modelo/TALENTO_HUMANO/CATALOGOS/th_cat_cargoM.php');

$controlador = new th_cat_cargoC();

if (isset($_GET['insertar_editar'])) {
    echo json_encode($controlador->insertar_editar($_POST['parametros']));
}

if (isset($_GET['eliminar'])) {
    echo json_encode($controlador->eliminar($_POST['id']));
}

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar($_POST['id'] ?? ''));
}

if (isset($_GET['buscar'])) {

    $query = '';
    if (isset($_GET['q'])) {
        $query = $_GET['q'];
    }

    $parametros = [
        'query' => $query
    ];

    echo json_encode($controlador->buscar($parametros));
}

class th_cat_cargoC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new th_cat_cargoM();
    }

    function listar($id = '')
    {
        if ($id != '') {
            return $this->modelo->where('id_cargo', $id)->where('estado',1)->listar();
        }

        return $this->modelo->where('estado',1)->listar();
    }

    function insertar_editar($parametros)
    {
        // Limpieza y preparación de datos
        $nombre = $parametros['txt_th_car_nombre'];
        $descripcion = $parametros['txt_th_car_descripcion'];
        $id = $parametros['_id'];

        $datos = array(
            array('campo' => 'nombre', 'dato' => $nombre),
            array('campo' => 'descripcion', 'dato' => $descripcion),
            array('campo' => 'estado', 'dato' => 1),
        );
        if ($id == '') {
            $datos = $this->modelo->insertar_id($datos);
        } else {
            $where[0]['campo'] = 'id_cargo';
            $where[0]['dato'] = $id;
            $datos = $this->modelo->editar($datos, $where);
        }

        return $datos;
    }

    function eliminar($id)
    {
        // Borrado lógico (cambio de estado)
        $datos = array(
            array('campo' => 'estado', 'dato' => 0),
        );

        $where[0]['campo'] = 'id_cargo';
        $where[0]['dato'] = $id;

        $datos = $this->modelo->editar($datos, $where);
        return $datos;
    }

    function buscar($parametros)
    {
        $lista = [];
        $concat = "nombre, descripcion";

        $datos = $this->modelo
            ->where('estado', 1)
            ->like($concat, $parametros['query']);

        foreach ($datos as $value) {
            $lista[] = [
                'id'   => $value['id_cargo'],
                'text' => $value['nombre']
            ];
        }

        return $lista;
    }
}
