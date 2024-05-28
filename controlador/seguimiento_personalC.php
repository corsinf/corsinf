<?php
include('../modelo/seguimiento_personalM.php');

$controlador = new seguimiento_personalC();

if (isset($_GET['listar_seguimiento'])) {
    echo json_encode($controlador->lista_segumiento($_POST['id']));
}

if (isset($_GET['insertar'])) {
    echo json_encode($controlador->insertar($_POST['parametros']));
}


class seguimiento_personalC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new seguimiento_personalM();
    }

    function lista_segumiento($id_paciente)
    {
        $datos = $this->modelo->lista_seguimiento($id_paciente);
        return $datos;
    }

    function insertar($parametros)
    {
        $datos = array(
            array('campo' => 'pac_id', 'dato' => $parametros['pac_id']),
            array('campo' => 'sa_sep_observacion', 'dato' => $parametros['sa_sep_observacion']),
            array('campo' => 'usu_id', 'dato' => $_SESSION['INICIO']['ID_USUARIO']),
        );

       
        $datos = $this->modelo->insertar($datos);

        return $datos;
    }
}
