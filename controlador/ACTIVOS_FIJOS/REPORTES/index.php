<?php

require_once 'ac_reportes_activos_fijosC.php';
require_once 'ac_descargasC.php';

$reporte = new ac_reportes_activos_fijosC();
$descargas = new ac_descargasC();


if (isset($_GET['ac_reporte_cedula_activo'])) {
    ($reporte->reporte_cedula_activo($_GET['id_activo']));
}

if (isset($_GET['reporte_auditoria_articulos'])) {
    ($reporte->reporte_auditoria_articulos($_GET['id_persona'] ?? '', $_GET['id_localizacion'] ?? '', $_GET['id_empresa'] ?? ''));
}

if (isset($_GET['reporte_articulos_custodio_localizacion'])) {
    ($reporte->reporte_articulos_custodio_localizacion($_GET['id_persona'] ?? '', $_GET['id_localizacion'] ?? '', $_GET['id_empresa'] ?? ''));
}

//Para la descarga 

if (isset($_GET['cargar_lotes'])) {
    echo json_encode($descargas->cargar_lotes());
}
