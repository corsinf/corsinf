<?php
date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 3) . '/modelo/HOST_TIME/UBICACIONES/hub_ubicacionesM.php');

$controlador = new hub_ubicacionesC();

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


class hub_ubicacionesC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new hub_ubicacionesM();
    }

    function listar($id = '')
    {
        if ($id == '') {
            $datos = $this->modelo->where('is_deleted', 0)->listar();
        } else {
            $datos = $this->modelo->listar_por_id($id);
        }
        return $datos;
    }

    function insertar_editar($parametros)
    {
        $datos = [
            ['campo' => 'nombre',      'dato' => $parametros['txt_nombre']],
            ['campo' => 'direccion',   'dato' => $parametros['txt_direccion']],
            ['campo' => 'telefono',    'dato' => $parametros['txt_telefono']],
            ['campo' => 'th_prov_id',  'dato' => $parametros['ddl_provincias']],
            ['campo' => 'th_ciu_id',   'dato' => $parametros['ddl_ciudad']],
            ['campo' => 'th_parr_id',  'dato' => $parametros['ddl_parroquia']],
            ['campo' => 'is_deleted',  'dato' => 0],
        ];

        if ($parametros['_id'] == '') {
            $datos = $this->modelo->insertar($datos);
        } else {
            $where[0]['campo'] = 'id_ubicacion';
            $where[0]['dato']  = $parametros['_id'];
            $datos = $this->modelo->editar($datos, $where);
        }

        return $datos;
    }

    function eliminar($id)
    {
        $datos = array(
            array('campo' => 'estado', 'dato' => 0),
        );

        $where[0]['campo'] = 'id_ubicacion';
        $where[0]['dato']  = $id;

        $datos = $this->modelo->editar($datos, $where);
        return $datos;
    }

    // Para usar en select2
    function buscar($parametros)
    {
        $lista  = array();
        $concat = "nombre, is_deleted";
        $datos  = $this->modelo->where('is_deleted', 0)->like($concat, $parametros['query']);

        foreach ($datos as $key => $value) {
            $text   = $value['nombre'];
            $lista[] = array(
                'id'   => $value['id_ubicacion'],
                'text' => $text,
            );
        }

        return $lista;
    }
}
