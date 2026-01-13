<?php
date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 3)  . '/modelo/TALENTO_HUMANO/CATALOGOS/th_cat_comisionM.php');

$controlador = new th_cat_comisionC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar($_POST['id'] ?? ''));
}

if (isset($_GET['listar_comisiones'])) {
    echo json_encode($controlador->listar_comisiones($_POST['id'] ?? ''));
}

if (isset($_GET['insertar'])) {
    echo json_encode($controlador->insertar_editar($_POST['parametros']));
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

if (isset($_GET['eliminar'])) {
    echo json_encode($controlador->eliminar($_POST['id']));
}

class  th_cat_comisionC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new th_cat_comisionM();
    }


    function insertar_editar($parametros)
    {
        $datos = [
            ['campo' => 'codigo', 'dato' => $parametros['txt_codigo']],
            ['campo' => 'nombre', 'dato' => $parametros['txt_nombre']],
            ['campo' => 'descripcion', 'dato' => $parametros['txt_descripcion']],
        ];

        // INSERTAR
        if (empty($parametros['_id'])) {
            $datos[] = [
                'campo' => 'fecha_creacion',
                'dato'  => date('Y-m-d H:i:s')
            ];

            return $this->modelo->insertar($datos);
        }

        // EDITAR
        $where[] = [
            'campo' => 'id_comision',
            'dato'  => $parametros['_id']
        ];

        return $this->modelo->editar($datos, $where);
    }


    function listar($id = '')
    {
        $datos = $this->modelo->where('', $id)->listar();
        return $datos;
    }


    function listar_comisiones($id = '')
    {
        if ($id == '') {
            $datos = $this->modelo->listar_comisiones_contar_personas();
        } else {
            $datos = $this->modelo->where('estado', 1)->where('id_comision', $id)->listar();
        }
        return $datos;
    }


    function eliminar($id)
    {

        $datos = array(
            array('campo' => 'estado', 'dato' => 0),
        );

        $where[0]['campo'] = 'id_comision';
        $where[0]['dato'] = $id;

        $datos = $this->modelo->editar($datos, $where);
        return $datos;
    }


    function buscar($parametros)
    {
        $lista = array();
        $concat = "nombre, estado";
        $datos = $this->modelo->where('estado', 1)->like($concat, $parametros['query']);

        foreach ($datos as $key => $value) {
            $text = $value['nombre'];
            $lista[] = array('id' => ($value['id_comision']), 'text' => ($text), /* 'data' => $value */);
        }

        return $lista;
    }
}
