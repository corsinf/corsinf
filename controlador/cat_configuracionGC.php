<?php
include('../modelo/cat_configuracionGM.php');

$controlador = new cat_configuracionGC();

//Para mostrar todos los registros con campos especificos para la vista principal
if (isset($_GET['listar_config_general'])) {
    echo json_encode($controlador->lista_vista_med_ins());
}

if (isset($_GET['vista_mod'])) {
    echo json_encode($controlador->editar($_POST['parametros']));
    //print_r($_POST['parametros']); exit;
}


class cat_configuracionGC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new cat_configuracionGM();
    }

    function lista_vista_med_ins()
    {
        $datos = $this->modelo->lista_vista_conf_general();
        return $datos;
    }

    function editar($parametros)
    {
        $datos = array(
            array('campo' => 'sa_config_estado', 'dato' => $parametros['sa_config_estado']),
        );

        $where[0]['campo'] = 'sa_config_id';
        $where[0]['dato'] = $parametros['sa_config_id'];
        $datos = $this->modelo->editar($datos, $where);

        return $datos;
    }
}
