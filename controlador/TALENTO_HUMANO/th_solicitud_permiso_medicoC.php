<?php
date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 2) . '/modelo/TALENTO_HUMANO/th_solicitud_permiso_medicoM.php');
require_once(dirname(__DIR__, 2) . '/modelo/TALENTO_HUMANO/th_solicitud_permisoM.php');

$controlador = new th_solicitud_permiso_medicoC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar());
}
if (isset($_GET['listar_solicitud_medico'])) {
    echo json_encode($controlador->listar_solicitud_medico($_POST['id'] ?? ''));
}

if (isset($_GET['listar_solicitudes_persona'])) {
    echo json_encode($controlador->listar_solicitudes_persona($_POST['id'] ?? '', $_POST['estado'] ?? ''));
}

if (isset($_GET['insertar_editar'])) {
    echo json_encode($controlador->insertar_editar($_POST['parametros']));
}

if (isset($_GET['eliminar'])) {
    echo json_encode($controlador->eliminar($_POST['id']));
}

class th_solicitud_permiso_medicoC
{
    private $modelo;
    private $th_solicitud_permiso;

    function __construct()
    {
        $this->modelo = new th_solicitud_permiso_medicoM();
        $this->th_solicitud_permiso = new th_solicitud_permisoM();
    }

    /* ===========================
       LISTAR
    ============================ */
    function listar()
    {
        $datos = $this->th_solicitud_permiso->listar_personas_con_total_solicitudes();
        return $datos;
    }

    function listar_solicitudes_persona($per_id = '', $estado = 2)
    {
        $datos =  $this->th_solicitud_permiso->listar_solicitudes_persona_con_medico($per_id);

        return  $datos;
    }

    function listar_solicitud_medico($id = ''){

        if($id !=  ''){
            $datos = $this->modelo->where('th_sol_per_med_id',$id)->listar(); 
        }
        return $datos;

    }

    
    function insertar_editar($parametros)
{
    $toInt = fn($v) => ($v === '' || $v === null) ? 0 : (int)$v;
    $toBool = fn($v) => ($v === '1' || $v === 1 || $v === true) ? 1 : 0;

    $datos = [
        // ID de solicitud - debe venir de los parámetros, no hardcodeado
        ['campo' => 'th_sol_per_id', 'dato' => $parametros['id_solicitud'] ?? null],

        ['campo' => 'th_sol_per_med_reposo', 'dato' => $toBool($parametros['reposo'] ?? 0)],
        ['campo' => 'th_sol_per_med_permiso_consulta', 'dato' => $toBool($parametros['permiso_consulta'] ?? 0)],

        ['campo' => 'th_sol_per_med_codigo_idg', 'dato' => $parametros['codigo_idg'] ?? null],

        // Los nombres de parámetros deben coincidir con lo que envía JavaScript
        ['campo' => 'th_sol_per_med_presenta_cert_medico', 'dato' => $toBool($parametros['presenta_cert_medico'] ?? 0)],
        ['campo' => 'th_sol_per_med_presenta_cert_asistencia', 'dato' => $toBool($parametros['presenta_cert_asistencia'] ?? 0)],

        ['campo' => 'th_sol_per_med_motivo', 'dato' => $parametros['motivo'] ?? null],
        ['campo' => 'th_sol_per_med_observaciones', 'dato' => $parametros['observaciones'] ?? null],

        ['campo' => 'th_sol_per_med_fecha', 'dato' => $parametros['fecha'] ?? null],
        ['campo' => 'th_sol_per_med_desde', 'dato' => $parametros['desde'] ?? null],
        ['campo' => 'th_sol_per_med_hasta', 'dato' => $parametros['hasta'] ?? null],

        ['campo' => 'th_sol_per_med_nombre_medico', 'dato' => $parametros['nombre_medico'] ?? null],

        // Estado solicitud debe venir de los parámetros cuando es edición
        ['campo' => 'th_sol_per_med_estado_solicitud', 'dato' => $parametros['estado_solicitud'] ?? 0],
        ['campo' => 'th_sol_per_med_fecha_modificacion', 'dato' => date('Y-m-d H:i:s')],
    ];

    // INSERCIÓN
    if (empty($parametros['_id'])) {
        $datos[] = ['campo' => 'th_sol_per_med_estado', 'dato' => 1];
        $datos[] = ['campo' => 'th_sol_per_med_fecha_creacion', 'dato' => date('Y-m-d H:i:s')];

        return $this->modelo->insertar_id($datos) ? 1 : 0;
    }

    // EDICIÓN
    $where = [
        ['campo' => 'th_sol_per_med_id', 'dato' => $parametros['_id']]
    ];

    return $this->modelo->editar($datos, $where) ? 1 : 0;
}

    
    function eliminar($id)
    {
        $datos = [
            ['campo' => 'th_sol_per_med_estado', 'dato' => 0],
            ['campo' => 'th_sol_per_med_fecha_modificacion', 'dato' => date('Y-m-d H:i:s')],
        ];

        $where = [
            ['campo' => 'th_sol_per_med_id', 'dato' => $id]
        ];

        return $this->modelo->editar($datos, $where) ? 1 : 0;
    }
}