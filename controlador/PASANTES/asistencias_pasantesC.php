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

if (isset($_GET['editar'])) {
    echo json_encode($controlador->editar($_POST['parametros']));
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
        //tomar la hora del sistema
        $hora_del_sistema = date('Y-m-d H:i:s');
        $datos = array(
            array('campo' => 'pas_usu_id', 'dato' => ($_SESSION['INICIO']['ID_USUARIO'])),
            array('campo' => 'pas_nombre', 'dato' => ($_SESSION['INICIO']['USUARIO'])),
            array('campo' => 'pas_hora_llegada', 'dato' => ($hora_del_sistema)),
            // array('campo' => 'pas_observacion_pasante', 'dato' => $parametros['txt_obs_pasantes']),
            // array('campo' => 'pas_observacion_tutor', 'dato' => $parametros['txt_obs_tutor']),
            // array('campo' => 'pas_usu_id_tutor', 'dato' => $parametros['txt_cedula']),
            // array('campo' => 'pas_tutor_estado', 'dato' => $parametros['ddl_sexo']),
        );

        $datos = $this->modelo->insertar($datos);
        return $datos;
    }

    function editar($parametros)
    {
        //print_r();
        //die();
        //tomar la hora del sistema
        $hora_del_sistema = date('Y-m-d H:i:s');
        $datos = array(
            array('campo' => 'pas_observacion_pasante', 'dato' => $parametros['txt_obs_pasantes']),
            array('campo' => 'pas_observacion_tutor', 'dato' => $parametros['txt_obs_tutor']),
            array('campo' => 'pas_hora_salida', 'dato' => ($hora_del_sistema)),
            // array('campo' => 'pas_tutor_estado', 'dato' => $parametros['ddl_sexo']),
        );

        $where[0]['campo'] = 'pas_id';
        $where[0]['dato'] = $parametros['registro_id'];
        $datos = $this->modelo->editar($datos, $where);

        ////////////////////////////////////////////////
        //Para calcular total de horas
        //LLamar el registro 
        $datos = $this->modelo->where('pas_id', $parametros['registro_id'])->listar();

        $pas_hora_llegada = $datos[0]['pas_hora_llegada'];
        $pas_hora_salida = $datos[0]['pas_hora_salida'];

        $pas_hora_llegada = new DateTime($pas_hora_llegada);
        $pas_hora_salida = new DateTime($pas_hora_salida);

        $diferencia = $pas_hora_salida->diff($pas_hora_llegada);

        $horas_totales = $diferencia->h + ($diferencia->i / 60);

        $calcular_total = number_format($horas_totales, 1);

        $datos = array(
            array('campo' => 'pas_horas_total', 'dato' => $calcular_total),
        );

        $where[0]['campo'] = 'pas_id';
        $where[0]['dato'] = $parametros['registro_id'];
        $datos = $this->modelo->editar($datos, $where);

        return $datos;

        //return $parametros;
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
