<?php

require_once 'ac_reportes_activos_fijosC.php';

$reporte = new ac_reportes_activos_fijosC();

if (isset($_GET['ac_reporte_cedula_activo'])) {
    ($reporte->reporte_cedula_activo($_GET['id_activo']));
}