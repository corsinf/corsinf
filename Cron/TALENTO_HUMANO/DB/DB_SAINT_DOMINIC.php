<?php
require_once('../calculo_control_acceso.php');

// Crear una instancia de la clase y llamar al método
$proceso = new calculo_persona($usuario, $password, $servidor, $database, $puerto);


date_default_timezone_set('America/Mexico_City'); // Ajusta a tu zona horaria
$fecha_actual = date("Y-m-d");

// $parametros = $proceso->calculo_persona_control_acceso(2000, '2025-06-27');

// $parametros = $proceso->carga_masiva('2025-07-14');
$parametros = $proceso->carga_masiva($fecha_actual);

print_r($parametros);
exit();
die();
