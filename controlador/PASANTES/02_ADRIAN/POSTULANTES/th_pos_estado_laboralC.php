<?php

require_once(dirname(__DIR__, 4) . '/modelo/PASANTES/02_ADRIAN/POSTULANTES/th_pos_estado_laboralM.php');

$controlador = new th_pos_estado_laboralC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar($_POST['id']));
}

if (isset($_GET['insertar'])) {
    echo json_encode($controlador->insertar_editar_estado_laboral($_POST['parametros']));
}

if (isset($_GET['eliminar'])) {
    echo json_encode($controlador->eliminar($_POST['id']));
}



class th_pos_estado_laboralC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new th_pos_estado_laboralM();
    }

    function listar($id)
    {
        if ($id == '') {
            $datos = $this->modelo->where('th_pos_estado_laboral', 1)->listar();
        } else {
            $datos = $this->modelo->where('th_pos_id', $id)->listar();
        }
        return $datos;
    }

    function insertar_editar_estado_laboral($parametros)
    {
       //print_r($parametros); exit(); die();

        $datos = array(

            array('campo' => 'th_pos_id', 'dato' => $parametros['id_postulante']),
            array('campo' => 'th_est_estado_laboral', 'dato' => $parametros['ddl_estado_laboral']),
            array('campo' => 'th_est_fecha_contratacion', 'dato' => $parametros['txt_fecha_contratacion_estado']),
            array('campo' => 'th_est_fecha_salida', 'dato' => $parametros['txt_fecha_salida_estado']),

        );
        $datos = $this->modelo->insertar($datos);
        
        /* if ($parametros['_id'] == '') {
           $datos = $this->modelo->insertar($datos);
        } else {
            $where[0]['campo'] = 'th_est_id';
            $where[0]['dato'] = $parametros['_id'];
            $datos = $this->modelo->editar($datos, $where);
        }*/

        return $datos;

    }

    function eliminar($id)
    {
        $datos = array(
            array('campo' => 'th_pos_estado', 'dato' => 0),
        );

        $where[0]['campo'] = 'th_pos_id';
        $where[0]['dato'] = $id;

        $datos = $this->modelo->editar($datos, $where);
        return $datos;
    }

}
