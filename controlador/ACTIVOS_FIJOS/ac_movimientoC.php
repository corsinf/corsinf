<?php
date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 2) . '/modelo/ACTIVOS_FIJOS/ac_movimientoM.php');

$controlador = new ac_movimientoC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar($_POST['id'] ?? ''));
}

if (isset($_GET['insertar'])) {
    echo json_encode($controlador->insertar_editar($_POST['parametros']));
}

if (isset($_GET['eliminar'])) {
    echo json_encode($controlador->eliminar($_POST['id']));
}

class ac_movimientoC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new ac_movimientoM();
    }

    function listar($id = '')
    {
        if ($id == '') {
            $datos = $this->modelo->listar();
        } else {
            $datos = $this->modelo->where('id_movimiento', $id)->listar();
        }
        return $datos;
    }


    //
        //De aqui para abajo editar 
    //


    function insertar_editar($parametros)
    {
        // print_r($parametros);
        // exit();
        // die();

        $datos = array(
            array('campo' => 'po_nivel', 'dato' => $parametros['txt_nivel']),
            array('campo' => 'po_TP', 'dato' => $parametros['txt_TP']),
            array('campo' => 'po_proceso', 'dato' => $parametros['txt_proceso']),
            array('campo' => 'po_DC', 'dato' => $parametros['txt_DC']),
            array('campo' => 'po_cmds', 'dato' => $parametros['txt_cmds']),
            array('campo' => 'po_picture', 'dato' => $parametros['txt_picture']),
            array('campo' => 'po_color', 'dato' => $parametros['txt_color']),
            array('campo' => 'po_cta_costo', 'dato' => $parametros['txt_cta_costo']),
            array('campo' => 'po_mi_cta', 'dato' => $parametros['txt_mi_cta']),
            // array('campo' => 'th_dep_fecha_modificacion', 'dato' => date('Y-m-d H:i:s')),
        );

        if ($parametros['_id'] == '') {
            if (count($this->modelo->where('po_proceso', $parametros['txt_proceso'])->listar()) == 0) {
                $datos = $this->modelo->insertar($datos);
            } else {
                return -2;
            }
        } else {
            if (count($this->modelo->where('po_proceso', $parametros['txt_proceso'])->where('id_movimiento !', $parametros['_id'])->listar()) == 0) {
                $where[0]['campo'] = 'id_movimiento';
                $where[0]['dato'] = $parametros['_id'];
                $datos = $this->modelo->editar($datos, $where);
            } else {
                return -2;
            }
        }

        return $datos;
    }

    function eliminar($id)
    {
        $datos = array(
            array('campo' => 'po_estado', 'dato' => 0),
        );

        $where[0]['campo'] = 'id_movimiento';
        $where[0]['dato'] = $id;

        $datos = $this->modelo->editar($datos, $where);
        return $datos;
    }

}
