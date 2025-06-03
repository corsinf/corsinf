<?php

require_once 'ac_reportes_activos_fijosC.php';

$reporte = new ac_reportes_activos_fijosC();

if (isset($_GET['ac_reporte_cedula_activo'])) {
    ($reporte->reporte_cedula_activo($_GET['id_activo']));
}


if (isset($_GET['reporte_auditoria_articulos'])) {
    ($reporte->reporte_auditoria_articulos($_GET['id_persona'], $_GET['id_localizacion']));
}


if (isset($_GET['reporte_articulos_custodio_localizacion'])) {
    ($reporte->reporte_articulos_custodio_localizacion($_GET['id_persona'], $_GET['id_localizacion']));
}
