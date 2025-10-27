<?php
date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 1) . '/calculo_control_acceso.php');

require_once(dirname(__DIR__, 3) . '/variables_entorno.php');

// Crear una instancia de la clase y llamar al método
$proceso = new calculo_persona(ENV_SAINT_USUARIO, ENV_SAINT_PASSWORD, ENV_SAINT_SERVIDOR, ENV_SAINT_DATABASE, ENV_SAINT_PUERTO);

// $fecha_actual = '2025-08-07';

// $parametros = $proceso->calculo_persona_control_acceso(2000, '2025-06-27');


$fecha_inicial = '2025-10-25';
$fecha_final   = '2025-10-25'; // por ejemplo

$fecha_actual = new DateTime($fecha_inicial);
$fecha_limite = new DateTime($fecha_final);

guardar_log('[INF] Inicio Inserción Masiva ', ENV_SAINT_DATABASE);
while ($fecha_actual <= $fecha_limite) {
    $fecha_str = $fecha_actual->format('Y-m-d');
    
    $parametros = $proceso->carga_masiva($fecha_str);
    
    echo "Procesado: $fecha_str\n";
    $fecha_actual->modify('+1 day');
}
guardar_log($parametros, ENV_SAINT_DATABASE);

print_r($parametros);
exit();

function guardar_log($mensaje, $db)
{
    $ruta_log = __DIR__ . '/log_carga_masiva_fechas_' . $db . '.log';
    $fecha_log = date("Y-m-d H:i:s");

    $entrada = "[$fecha_log] $mensaje\n";

    file_put_contents($ruta_log, $entrada, FILE_APPEND);
}
