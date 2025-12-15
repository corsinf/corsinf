<?php
date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 2) . '/modelo/TALENTO_HUMANO/th_programar_horariosM.php');

$controlador = new th_programar_horariosC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar($_POST['id'] ?? ''));
}

if (isset($_GET['listar_persona_horario'])) {
    echo json_encode($controlador->listar_persona_horario($_POST['id'] ?? ''));
}


if (isset($_GET['listar_departamentos_horarios'])) {
    echo json_encode($controlador->listar_departamentos_horarios($_POST['id'] ?? ''));
}

if (isset($_GET['listar_personas_horarios'])) {
    echo json_encode($controlador->listar_personas_horarios($_POST['id'] ?? ''));
}

if (isset($_GET['insertar'])) {
    echo json_encode($controlador->insertar_editar($_POST['parametros']));
}

if (isset($_GET['eliminar'])) {
    echo json_encode($controlador->eliminar($_POST['id']));
}


class th_programar_horariosC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new th_programar_horariosM();
    }

    function listar($id)
    {
        if ($id != '') {
            // $datos = $this->modelo->where('th_pro_id', $id)->listar();
            $datos = $this->modelo->listar_programacion_horarios($id);

            return $datos;
        }
        return null;
    }
    function listar_persona_horario($id)
    {
        // $datos = $this->modelo->where('th_pro_id', $id)->listar();
        $datos = $this->modelo->listar_horarios_persona_completo($id);

        return $datos;
    }

    function listar_departamentos_horarios($id = '')
    {
        $datos = $this->modelo->listar_departamentos_horarios($id);
        return $datos;
    }

    function listar_personas_horarios($id = '')
    {
        $datos = $this->modelo->listar_personas_horarios($id);
        return $datos;
    }

    function insertar_editar($parametros)
    {

        $datos = array(
            array('campo' => 'th_pro_fecha_inicio', 'dato' => $parametros['txt_fecha_inicio']),
            array('campo' => 'th_pro_fecha_fin', 'dato' => $parametros['txt_fecha_fin']),
            array('campo' => 'th_per_id', 'dato' => $parametros['ddl_personas']),
            array('campo' => 'th_dep_id', 'dato' => $parametros['ddl_departamentos']),
            array('campo' => 'th_hor_id', 'dato' => $parametros['ddl_horarios']),
            array('campo' => 'th_pro_tipo_ciclo', 'dato' => $parametros['cbx_horario']),
            array('campo' => 'th_pro_si_ciclo', 'dato' => $parametros['cbx_horario_detalle']),
            //array('campo' => 'th_pro_no_ciclo', 'dato' => $parametros['cbx_horario_detalle_sin']),
            array('campo' => 'th_pro_fecha_modificacion', 'dato' => date('Y-m-d H:i:s')),
        );

        if ($parametros['_id'] == '') {
            $datos = $this->modelo->insertar($datos);
        } else {
            $where[0]['campo'] = 'th_pro_id';
            $where[0]['dato'] = $parametros['_id'];
            $datos = $this->modelo->editar($datos, $where);
        }

        return $datos;
    }

    function eliminar($id)
    {
        $datos = array(
            array('campo' => 'th_pro_estado', 'dato' => 0),
        );

        $where[0]['campo'] = 'th_pro_id';
        $where[0]['dato'] = $id;

        $datos = $this->modelo->editar($datos, $where);
        return $datos;
    }
}