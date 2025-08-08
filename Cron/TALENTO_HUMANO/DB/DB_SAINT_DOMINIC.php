<?php
require_once('../calculo_control_acceso.php');

// Crear una instancia de la clase y llamar al método
$proceso = new calculo_persona($usuario, $password, $servidor, $database, $puerto);

date_default_timezone_set('America/Mexico_City'); // Ajusta a tu zona horaria
// $fecha_actual = date("Y-m-d");
$fecha_actual = '2025-08-07';

// $parametros = $proceso->calculo_persona_control_acceso(2000, '2025-06-27');

guardar_log('[INF] Inicio Inserción Masiva ', $database);
$parametros = $proceso->carga_masiva($fecha_actual);
guardar_log($parametros, $database);

print_r($parametros);
exit();

function guardar_log($mensaje, $db)
{
    $ruta_log = __DIR__ . '/log_carga_masiva_' . $db . '.log';
    $fecha_log = date("Y-m-d H:i:s");

    $entrada = "[$fecha_log] $mensaje\n";

    file_put_contents($ruta_log, $entrada, FILE_APPEND);
}
