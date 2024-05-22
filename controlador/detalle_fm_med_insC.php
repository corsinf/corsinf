<?php
include('../modelo/detalle_fm_med_insM.php');

$controlador = new detalle_fm_med_insC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->lista_det_fm($_POST['id']));
}

if (isset($_GET['eliminar'])) {
    echo json_encode($controlador->eliminar($_POST['id']));
}

if (isset($_GET['insertar'])) {
    echo json_encode($controlador->insertar_editar($_POST['parametros']));
}

if (isset($_GET['fm_farmaco_a'])) {
    echo json_encode($controlador->farmacos_alergia($_POST['parametros']));
}

class detalle_fm_med_insC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new detalle_fm_med_insM();
    }

    function lista_det_fm($id)
    {
        $datos = $this->modelo->lista_det_fm($id);
        return $datos;
    }

    function insertar_editar($parametros)
    {

        $datos = array(
            //array('campo' => 'sa_det_fm_id', 'dato' => $parametros['sa_det_fm_id']),
            array('campo' => 'sa_fice_id', 'dato' => strval($parametros['sa_fice_id'])),
            array('campo' => 'sa_det_fice_id_cmed_cins', 'dato' => strval($parametros['sa_det_fice_id_cmed_cins'])),
            array('campo' => 'sa_det_fice_nombre', 'dato' => $parametros['sa_det_fice_nombre']),
            array('campo' => 'sa_det_fice_tipo', 'dato' => $parametros['sa_det_fice_tipo']),
        );

        $datos = $this->modelo->insertar($datos);

        return $datos;
    }

    function eliminar($id)
    {
        $datos = $this->modelo->eliminar($id);
        return $datos;
    }

    function farmacos_alergia($parametros){
        $fm = $parametros['sa_fice_id'];
        $id_farmaco = $parametros['sa_det_fice_id_cmed_cins']; 
        $tipo = $parametros['sa_det_fice_tipo'];

        $datos = $this->modelo->farmaco_fm_alergico($fm, $id_farmaco, $tipo);
        return $datos;

    }
}
