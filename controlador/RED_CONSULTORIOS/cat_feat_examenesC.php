<?php
require_once(dirname(__DIR__, 2) . '/modelo/RED_CONSULTORIOS/cat_feat_examenesM.php');

$controlador = new cat_feat_examenesC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar());
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

class cat_feat_examenesC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new cat_feat_examenesM();
    }

    function listar()
    {
        $datos = $this->modelo->where('fex_estado', '1')->listar();
        return $datos;
    }

    function insertar_editar($parametros)
    {

        $datos1[0]['campo'] = 'fex_id';
        $datos1[0]['dato'] = strval($parametros['txt_feat_id']);


        $datos = array(
            array('campo' => 'fex_descripcion', 'dato' => $parametros['txt_feat_examen']),
            array('campo' => 'fex_name_input', 'dato' => $parametros['txt_feat_input']),
        );

        if ($parametros['txt_feat_id'] == '') {

            if (empty($this->modelo->where('fex_name_input', $parametros['txt_feat_input'])->listar())) {
                $datos = $this->modelo->insertar($datos);
            } else {
                return -2;
            }
        } else {
            $where[0]['campo'] = 'fex_id';
            $where[0]['dato'] = $parametros['txt_feat_id'];
            $datos = $this->modelo->editar($datos, $where);
        }

        //$datos = $this->modelo->insertar($datos);
        return $datos;
    }

    function eliminar($id)
    {
        $datos[0]['campo'] = 'fex_id';
        $datos[0]['dato'] = $id;

        $datos = $this->modelo->eliminar($datos);
        return $datos;
    }

    function buscar($parametros)
    {
        $lista = array();

        $concat = "fex_descripcion, fex_id";

        $datos = $this->modelo->where('fex_estado', 1)->like($concat, $parametros['query']);

        //print_r($datos); exit();die();

        foreach ($datos as $key => $value) {
            $lista[] = array('id' => ($value['fex_id']), 'text' => ($value['fex_descripcion']), 'data' => $value);
        }

        return $lista;
    }
}
