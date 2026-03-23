<?php
date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 3) . '/modelo/HOST_TIME/HORARIOS/hub_horariosM.php');

$controlador = new hub_horariosC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar($_POST['id'] ?? '', $_POST['id_espacio'] ?? ''));
}

if (isset($_GET['insertar'])) {
    echo json_encode($controlador->insertar_editar($_POST['parametros']));
}

if (isset($_GET['eliminar'])) {
    echo json_encode($controlador->eliminar($_POST['id']));
}


class hub_horariosC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new hub_horariosM();
    }

    function listar($id = '', $id_espacio = '')
    {
        return $this->modelo->listar_horarios(
            $id_espacio !== '' ? $id_espacio : null,
            $id !== '' ? $id : null
        );
    }

    function insertar_editar($parametros)
    {
        $datos = array(
            array('campo' => 'id_espacio',   'dato' => $parametros['ddl_espacio']),
            array('campo' => 'dia_semana',   'dato' => $parametros['ddl_dia_semana']),
            array('campo' => 'hora_inicio',  'dato' => $parametros['txt_hora_inicio']),
            array('campo' => 'hora_fin',     'dato' => $parametros['txt_hora_fin']),
            array('campo' => 'activo',       'dato' => $parametros['cbx_activo']),
            array('campo' => 'estado',       'dato' => 1),
        );

        if ($parametros['_id'] == '') {
            $datos = $this->modelo->insertar($datos);
        } else {
            $where[0]['campo'] = 'id_horario';
            $where[0]['dato'] = $parametros['_id'];
            $datos = $this->modelo->editar($datos, $where);
        }

        return $datos;
    }

    function eliminar($id)
    {
        $datos = array(
            array('campo' => 'estado', 'dato' => 0),
        );

        $where[0]['campo'] = 'id_horario';
        $where[0]['dato'] = $id;

        return $this->modelo->editar($datos, $where);
    }
}
