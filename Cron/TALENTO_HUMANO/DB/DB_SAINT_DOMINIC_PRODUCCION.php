<?php
date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 1) . '/calculo_control_acceso.php');

require_once(dirname(__DIR__, 3) . '/variables_entorno.php');

// Crear una instancia de la clase y llamar al método
$proceso = new calculo_persona(ENV_SAINT_USUARIO, ENV_SAINT_PASSWORD, ENV_SAINT_SERVIDOR, ENV_SAINT_DATABASE, ENV_SAINT_PUERTO);

$fecha_actual = date("Y-m-d");
// $fecha_actual = '2025-08-07';

// $parametros = $proceso->calculo_persona_control_acceso(2000, '2025-06-27');

guardar_log('[INF] Inicio Inserción Masiva ', ENV_SAINT_DATABASE);
$parametros = $proceso->carga_masiva($fecha_actual);
guardar_log($parametros, ENV_SAINT_DATABASE);

print_r($parametros);
exit();

function guardar_log($mensaje, $db)
{
    $directorio = dirname(__DIR__, 3) . '/logs/talento_humano/asistencias';
    $ruta_log = $directorio . '/log_carga_cron_' . $db . '.log';

    // Verificar si el directorio existe, si no, crearlo con permisos 0777
    if (!is_dir($directorio)) {
        mkdir($directorio, 0777, true);
    }

    $fecha_log = date("Y-m-d H:i:s");
    $entrada = "[$fecha_log] $mensaje\n";

    file_put_contents($ruta_log, $entrada, FILE_APPEND);
}
