<?php
date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 3) . '/modelo/HOST_TIME/ESPACIOS/hub_espacios_turnosM.php');

$controlador = new hub_espacios_turnosC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar($_POST['id_espacio'] ?? ''));
}

if (isset($_GET['insertar'])) {
    echo json_encode($controlador->insertar($_POST['parametros']));
}

if (isset($_GET['eliminar'])) {
    echo json_encode($controlador->eliminar($_POST['id']));
}


class hub_espacios_turnosC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new hub_espacios_turnosM();
    }

    function listar($id_espacio = '')
    {
        if ($id_espacio === '') return [];
        return $this->modelo->listar_por_espacio($id_espacio);
    }

    function insertar($parametros)
    {
        $ahora = date('Y-m-d H:i:s');

        // 1. Verificar turno exacto duplicado
        $duplicado = $this->modelo->verificar_duplicado(
            $parametros['id_espacio'],
            $parametros['hub_tur_id'],
            $parametros['dia']
        );

        if (!empty($duplicado) && $duplicado[0]['c'] > 0) {
            return -2; // Mismo turno ya asignado
        }

        // 2. Verificar solapamiento de horas con otros turnos
        $solapado = $this->modelo->verificar_solapamiento(
            $parametros['id_espacio'],
            $parametros['hub_tur_id'],
            $parametros['dia']
        );

        if (!empty($solapado) && $solapado[0]['c'] > 0) {
            return -3; // Choque de horarios
        }

        $datos = [
            ['campo' => 'id_espacios',        'dato' => $parametros['id_espacio']],
            ['campo' => 'hub_tur_id',          'dato' => $parametros['hub_tur_id']],
            ['campo' => 'hub_tuh_dia',         'dato' => $parametros['dia']],
            ['campo' => 'is_deleted',          'dato' => 0],
            ['campo' => 'id_usuario_crea',     'dato' => $_SESSION['INICIO']['ID_USUARIO'] ?? 1],
            ['campo' => 'fecha_creacion',      'dato' => $ahora],
            ['campo' => 'id_usuario_modifica', 'dato' => $_SESSION['INICIO']['ID_USUARIO'] ?? 1],
            ['campo' => 'fecha_modificacion',  'dato' => $ahora],
        ];

        return $this->modelo->insertar($datos);
    }

    function eliminar($id)
    {
        $datos = [['campo' => 'hub_tuh_id', 'dato' => $id]];
        return $this->modelo->eliminar($datos);
    }
}
