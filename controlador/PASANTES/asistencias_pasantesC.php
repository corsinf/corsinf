<?php
require_once(dirname(__DIR__, 2) . '/modelo/PASANTES/asistencias_pasantesM.php');


$controlador = new asistencias_pasantesC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar($_POST['id'] ?? '', $_POST['modal'] ?? ''));
}

if (isset($_GET['insertar'])) {
    echo json_encode($controlador->insertar_editar($_POST['parametros']));
}

if (isset($_GET['eliminar'])) {
    echo json_encode($controlador->eliminar($_POST['id']));
}

if (isset($_GET['editar'])) {
    echo json_encode($controlador->editar($_POST['parametros']));
}

if (isset($_GET['editar_tutor'])) {
    echo json_encode($controlador->editar_tutor($_POST['parametros']));
}





class asistencias_pasantesC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new asistencias_pasantesM();
    }

    function listar($id = '', $modal = 0)
    {
        if ($modal == 1) {
            //print_r($id); exit();
            return $datos = $this->modelo->where('pas_id', $id)->listar();
        }

        if ($id == '') {
            if ($_SESSION['INICIO']['ID_USUARIO'] == 1) {
                $datos = $this->modelo->listar();
            } else {
                $datos = $this->modelo->where('pas_usu_id', $_SESSION['INICIO']['ID_USUARIO'])->listar();
            }
        } else {
            $datos = $this->modelo->where('pas_usu_id', $_SESSION['INICIO']['ID_USUARIO'])->where('pas_id', $id)->listar();
        }
        return $datos;
    }

    function insertar_editar($parametros)
    {
        //print_r($parametros);exit;
        $txt_obs_tutor = isset($parametros['txt_obs_tutor']) ? $parametros['txt_obs_tutor'] : '';

        $datos = array(
            array('campo' => 'pas_usu_id', 'dato' => ($_SESSION['INICIO']['ID_USUARIO'])),
            array('campo' => 'pas_nombre', 'dato' => ($_SESSION['INICIO']['USUARIO'])),
            //array('campo' => 'pas_hora_llegada', 'dato' => ($hora_del_sistema)),
            array('campo' => 'pas_observacion_pasante', 'dato' => $parametros['txt_obs_pasantes']),
            array('campo' => 'pas_observacion_tutor', 'dato' => $txt_obs_tutor),
            // array('campo' => 'pas_usu_id_tutor', 'dato' => $parametros['txt_cedula']),
            // array('campo' => 'pas_tutor_estado', 'dato' => $parametros['ddl_sexo']),
        );

        $datos = $this->modelo->insertar($datos);
        return $datos;
    }

    function editar($parametros)
    {
        date_default_timezone_set('America/Bogota');
        //tomar la hora del sistema

        $hora_del_sistema = new DateTime();
        $hora_del_sistema = $hora_del_sistema->format('Y-d-m H:i:s');

        //print_r($hora_del_sistema); exit();

        $txt_obs_tutor = isset($parametros['txt_obs_tutor']) ? $parametros['txt_obs_tutor'] : '.';

        $datos = array(
            array('campo' => 'pas_observacion_pasante', 'dato' => $parametros['txt_obs_pasantes']),
            array('campo' => 'pas_observacion_tutor', 'dato' => $txt_obs_tutor),
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

        // Calcular la diferencia
        $diferencia = $pas_hora_salida->diff($pas_hora_llegada);

        $horas_totales = $diferencia->h + ($diferencia->i / 60);

        $calcular_total = number_format($horas_totales, 2);

        $datos = array(
            array('campo' => 'pas_horas_total', 'dato' => $calcular_total),
        );

        $where[0]['campo'] = 'pas_id';
        $where[0]['dato'] = $parametros['registro_id'];
        $datos = $this->modelo->editar($datos, $where);

        return $datos;

        //return $parametros;
    }

    function editar_tutor($parametros)
    {
        $txt_obs_tutor = isset($parametros['txt_obs_tutor']) ? $parametros['txt_obs_tutor'] : '.';

        $datos = array(
            array('campo' => 'pas_observacion_tutor', 'dato' => $txt_obs_tutor),
            array('campo' => 'pas_tutor_estado', 'dato' => 1),
        );

        $where[0]['campo'] = 'pas_id';
        $where[0]['dato'] = $parametros['txt_id_registro'];
        $datos = $this->modelo->editar($datos, $where);
        
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
