<?php
require_once(dirname(__DIR__, 2) . '/modelo/SALUD_INTEGRAL/insumosM.php');

$controlador = new insumosC();

if (isset($_GET['listar_todo'])) {
    echo json_encode($controlador->lista_todo_insumos());
}

if (isset($_GET['listar'])) {
    echo json_encode($controlador->lista_insumos($_POST['id']));
}

if (isset($_GET['buscar'])) {
    echo json_encode($controlador->buscar_insumos($_POST['buscar']));
}

if (isset($_GET['insertar'])) {
    echo json_encode($controlador->insertar_editar($_POST['parametros']));
}

if (isset($_GET['eliminar'])) {
    echo json_encode($controlador->eliminar($_POST['id']));
}

class insumosC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new insumosM();
    }

    function lista_todo_insumos()
    {
        $datos = $this->modelo->lista_insumos_todo();
        return $datos;
    }

    function lista_insumos($id)
    {
        $datos = $this->modelo->lista_insumos($id);
        return $datos;
    }

    function buscar_insumos($buscar)
    {
        $datos = $this->modelo->buscar_insumos($buscar);
        return $datos;
    }

    function insertar_editar($parametros)
    {
        $datos1[0]['campo'] = 'sa_cins_id';
        $datos1[0]['dato'] = strval($parametros['sa_cins_id']);


        $datos = array(
            array('campo' => 'sa_cins_codigo', 'dato' => $parametros['sa_cins_codigo']),
            array('campo' => 'sa_cins_presentacion', 'dato' => $parametros['sa_cins_presentacion']),
            array('campo' => 'sa_cins_lote', 'dato' => $parametros['sa_cins_lote']),
            array('campo' => 'sa_cins_caducidad', 'dato' => $parametros['sa_cins_caducidad']),
            array('campo' => 'sa_cins_minimos', 'dato' => $parametros['sa_cins_minimos']),
            array('campo' => 'sa_cins_stock', 'dato' => $parametros['sa_cins_stock']),
            array('campo' => 'sa_cins_movimiento', 'dato' => $parametros['sa_cins_movimiento']),
            array('campo' => 'sa_cins_localizacion', 'dato' => $parametros['sa_cins_localizacion']),
            array('campo' => 'sa_cins_uso', 'dato' => $parametros['sa_cins_uso']),
            array('campo' => 'sa_cins_observaciones', 'dato' => $parametros['sa_cins_observaciones']),
            array('campo' => 'sa_cins_nombre_comercial', 'dato' => $parametros['sa_cins_nombre_comercial']),
        );


        if ($parametros['sa_cins_id'] == '') {
            if (count($this->modelo->buscar_insumos_CODIGO($datos1[0]['dato'])) == 0) {
                $datos = $this->modelo->insertar($datos);
            } else {
                return -2;
            }
        } else {
            $where[0]['campo'] = 'sa_cins_id';
            $where[0]['dato'] = $parametros['sa_cins_id'];
            $datos = $this->modelo->editar($datos, $where);
        }
        return $datos;
    }

    function compara_datos($parametros)
    {
        $text = '';
        $marca = $this->modelo->lista_insumos($parametros['id']);

        if ($marca[0]['CODIGO'] != $parametros['cod']) {
            $text .= ' Se modifico CODIGO en SECCION de ' . $marca[0]['CODIGO'] . ' a ' . $parametros['cod'];
        }

        if ($marca[0]['DESCRIPCION'] != $parametros['des']) {
            $text .= ' Se modifico DESCRIPCION en SECCION DE ' . $marca[0]['DESCRIPCION'] . ' a ' . $parametros['des'];
        }

        return $text;
    }

    function eliminar($id)
    {
        $datos[0]['campo'] = 'sa_cins_id';
        $datos[0]['dato'] = $id;
        $datos = $this->modelo->eliminar($datos);
        return $datos;
    }
}
