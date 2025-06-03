<?php

if (isset($_GET['detalle_activo'])) {
    require_once('ac_detalle_activo.php');
}

if (isset($_GET['reportes_activos_fijos'])) {
    require_once('ac_reportes_activos_fijos.php');
}