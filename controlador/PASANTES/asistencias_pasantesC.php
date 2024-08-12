<?php
require_once(dirname(__DIR__, 2) . '/modelo/PASANTES/asistencias_pasantesM.php');


$controlador = new asistencias_pasantesC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar());
}

if (isset($_GET['insertar'])) {
    echo json_encode($controlador->insertar_editar());
}

if (isset($_GET['eliminar'])) {
    echo json_encode($controlador->eliminar($_POST['id']));
}





class asistencias_pasantesC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new asistencias_pasantesM();
    }

    function listar()
    {
        $datos = $this->modelo->listar();
        return $datos;
    }

    function insertar_editar()
    {
        //print_r();
        //die();

        $datos = array(
            array('campo' => 'pas_usu_id', 'dato' => ($_SESSION['INICIO']['ID_USUARIO'])),
            array('campo' => 'pas_nombre', 'dato' => ($_SESSION['INICIO']['USUARIO'])),
            // array('campo' => 'pas_observacion_pasante', 'dato' => $parametros['txt_obs_pasantes']),
            // array('campo' => 'pas_observacion_tutor', 'dato' => $parametros['txt_obs_tutor']),
            // array('campo' => 'pas_usu_id_tutor', 'dato' => $parametros['txt_cedula']),
            // array('campo' => 'pas_tutor_estado', 'dato' => $parametros['ddl_sexo']),
        );
        $datos = $this->modelo->insertar($datos);
        return $datos;
    }

    function eliminar($id)
    {
        $datos = array(
            array('campo' => 'pac_estado', 'dato' => 0),
        );

        $where[0]['campo'] = 'pac_id';
        $where[0]['dato'] = $id;

        $datos = $this->modelo->editar($datos, $where);
        return $datos;
    }
}
