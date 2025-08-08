<?php
date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 1) . '/calculo_control_acceso.php');

require_once(dirname(__DIR__, 3) . '/variables_entorno.php');

// Crear una instancia de la clase y llamar al método
$proceso = new calculo_persona(ENV_DEV_TH_USUARIO, ENV_DEV_TH_PASSWORD, ENV_DEV_TH_SERVIDOR, ENV_DEV_TH_DATABASE, ENV_DEV_TH_PUERTO);

$fecha_actual = date("Y-m-d");
// $fecha_actual = '2025-08-07';

// $parametros = $proceso->calculo_persona_control_acceso(2000, '2025-06-27');

guardar_log('[INF] Inicio Inserción Masiva ', ENV_DEV_TH_DATABASE);
$parametros = $proceso->carga_masiva($fecha_actual);
guardar_log($parametros, ENV_DEV_TH_DATABASE);

print_r($parametros);
exit();

function guardar_log($mensaje, $db)
{
    $ruta_log = __DIR__ . '/log_carga_masiva_' . $db . '.log';
    $fecha_log = date("Y-m-d H:i:s");

    $entrada = "[$fecha_log] $mensaje\n";

    file_put_contents($ruta_log, $entrada, FILE_APPEND);
}
