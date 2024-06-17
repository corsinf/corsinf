<?php
include('../modelo/v_med_insM.php');

$controlador = new v_med_insC();

//Para mostrar todos los registros con campos especificos para la vista principal
if (isset($_GET['listar_v_medicamentos'])) {
    echo json_encode($controlador->lista_vista_med_ins('medicamentos'));
}

if (isset($_GET['listar_v_insumos'])) {
    echo json_encode($controlador->lista_vista_med_ins('insumos'));
}

if (isset($_GET['listar_v_ingresoStock'])) {
    echo json_encode($controlador->lista_vista_med_ins('ingreso_stock'));
}

if (isset($_GET['vista_mod'])) {
    echo json_encode($controlador->editar($_POST['parametros']));
    //print_r($_POST['parametros']); exit;
}


class v_med_insC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new v_med_insM();
    }

    function lista_vista_med_ins($tipo)
    {
        $datos = $this->modelo->lista_vista_med_ins($tipo);
        return $datos;
    }

    function editar($parametros)
    {
        $datos = array(
            array('campo' => 'sa_vmi_estado', 'dato' => $parametros['sa_vmi_estado']),
        );

        $where[0]['campo'] = 'sa_vmi_id';
        $where[0]['dato'] = $parametros['sa_vmi_id'];
        $datos = $this->modelo->editar($datos, $where);

        return $datos;
    }
}
