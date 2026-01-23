<?php
require_once(dirname(__DIR__, 1) . '\SOLICITUDES\th_reportes_personalC.php');
$reporte = new th_reportes_personalC();

if (isset($_GET['ac_reporte_permiso'])) {
    $reporte->reporte_permiso_usuario($_GET, true);
}
if (isset($_GET['ver_documento'])) {
    echo json_encode($reporte->reporte_permiso_usuario($_POST['parametros'],true));
}
if (isset($_GET['ver_documento_pdf'])) {
    echo json_encode($reporte->reporte_permiso_usuario($_POST['parametros'],false));
}
