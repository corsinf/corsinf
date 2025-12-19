<?php
date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 2) . '/modelo/TALENTO_HUMANO/th_justificacionesM.php');

$controlador = new th_justificacionesC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar($_POST['id'] ?? ''));
}

if (isset($_GET['listar_departamentos_justificaciones'])) {
    echo json_encode($controlador->listar_departamentos_justificaciones($_POST['id'] ?? ''));
}

if (isset($_GET['listar_personas_justificaciones'])) {
    echo json_encode($controlador->listar_personas_justificaciones($_POST['id'] ?? ''));
}

if (isset($_GET['insertar'])) {
    echo json_encode($controlador->insertar_editar($_POST['parametros']));
}

if (isset($_GET['eliminar'])) {
    echo json_encode($controlador->eliminar($_POST['id']));
}


class th_justificacionesC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new th_justificacionesM();
    }

    function listar($id)
    {
        if ($id != '') {
            //$datos = $this->modelo->where('th_jus_id', $id)->listar();
            $datos = $this->modelo->listar_justificaciones($id, '', '');
            return $datos;
        }
        return null;
    }

    function listar_departamentos_justificaciones($id = '')
    {
        $datos = $this->modelo->listar_departamentos_justificaciones($id);
        return $datos;
    }

    function listar_personas_justificaciones($id = '')
    {
        $datos = $this->modelo->listar_personas_justificaciones($id);
        return $datos;
    }

    function insertar_editar($parametros)
    {


        if ($parametros['cbx_justificar_rango'] == 1) {
            $txt_fecha_inicio =  $parametros['txt_fecha_inicio'] . ' 00:00:00';
            $txt_fecha_fin =  $parametros['txt_fecha_fin'] . ' 00:00:00';
            $txt_horas_totales =  0;
        } else {
            $txt_fecha_inicio = date('Y-m-d H:i:s', strtotime($parametros['txt_fecha_inicio']));
            $txt_fecha_fin = date('Y-m-d H:i:s', strtotime($parametros['txt_fecha_fin']));
            $txt_horas_totales =  $this->hora_a_minutos($parametros['txt_horas_totales']);
        }

        $datos = $this->modelo->existe_justificacion_en_rango($txt_fecha_inicio,$txt_fecha_fin,$parametros['ddl_departamentos'] = null,$parametros['ddl_personas'] = null,$parametros['_id'] = null);
        if($datos > 0){
            return -3;
        }


        $datos = array(
            array('campo' => 'th_jus_fecha_inicio', 'dato' =>  $txt_fecha_inicio),
            array('campo' => 'th_jus_fecha_fin', 'dato' => $txt_fecha_fin),
            array('campo' => 'th_per_id', 'dato' => $parametros['ddl_personas']),
            array('campo' => 'th_dep_id', 'dato' => $parametros['ddl_departamentos']),

            array('campo' => 'th_tip_jus_id', 'dato' => $parametros['ddl_tipo_justificacion']),
            array('campo' => 'th_jus_motivo', 'dato' => $parametros['txt_motivo']),

            array('campo' => 'th_jus_fecha_modificacion', 'dato' => date('Y-m-d H:i:s')),
            array('campo' => 'id_usuario', 'dato' => $_SESSION['INICIO']['ID_USUARIO']),
            array('campo' => 'th_jus_es_rango', 'dato' => $parametros['cbx_justificar_rango']),
            array('campo' => 'th_jus_minutos_justificados', 'dato' =>  $txt_horas_totales),
        );

        if ($parametros['_id'] == '') {
            $datos = $this->modelo->insertar($datos);
        } else {
            $where[0]['campo'] = 'th_jus_id';
            $where[0]['dato'] = $parametros['_id'];
            $datos = $this->modelo->editar($datos, $where);
        }

        return $datos;
    }

    function eliminar($id)
    {
        $datos = array(
            array('campo' => 'th_jus_estado', 'dato' => 0),
        );

        $where[0]['campo'] = 'th_jus_id';
        $where[0]['dato'] = $id;

        $datos = $this->modelo->editar($datos, $where);
        return $datos;
    }

    function hora_a_minutos($time)
    {
        list($horas, $minutos) = explode(':', $time);
        return ($horas * 60) + $minutos;
    }
}
