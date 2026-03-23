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
        if ($this->modelo->verificar_duplicado(
            $parametros['ddl_espacio'],
            $parametros['ddl_dia_semana'],
            $parametros['txt_hora_inicio'],
            $parametros['txt_hora_fin'],
            $parametros['_id']
        )) {
            return ['duplicado' => 1, 'mensaje' => 'Ya existe un horario idéntico para este espacio y día.'];
        }

        $datos = [
            ['campo' => 'id_espacio',  'dato' => $parametros['ddl_espacio']],
            ['campo' => 'dia_semana',  'dato' => $parametros['ddl_dia_semana']],
            ['campo' => 'hora_inicio', 'dato' => $parametros['txt_hora_inicio']],
            ['campo' => 'hora_fin',    'dato' => $parametros['txt_hora_fin']],
            ['campo' => 'activo',      'dato' => $parametros['cbx_activo']],
            ['campo' => 'estado',      'dato' => 1],
        ];

        if ($parametros['_id'] == '') {
            return $this->modelo->insertar($datos);
        }

        $where = [['campo' => 'id_horario', 'dato' => $parametros['_id']]];
        return $this->modelo->editar($datos, $where);
    }

    function eliminar($id)
    {
        $where = [['campo' => 'id_horario', 'dato' => $id]];
        return $this->modelo->editar([['campo' => 'estado', 'dato' => 0]], $where);
    }
}
