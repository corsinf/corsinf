<?php

if ($_GET['acc'] == 'hub_miembros') {
    include('THEHUB/MIEMBROS/hub_miembros.php');
}



//Servicios de la los espacios

if ($_GET['acc'] == 'hub_servicios') {
    include('THEHUB/SERVICIOS/hub_servicios.php');
}

if ($_GET['acc'] == 'hub_registrar_servicio') {
    include('THEHUB/SERVICIOS/hub_registrar_servicio.php');
}

if ($_GET['acc'] == 'hub_registrar_reserva_servicio') {
    include('THEHUB/RESERVAS/hub_registrar_reserva_servicio.php');
}

//rutas de las bodegas

if ($_GET['acc'] == 'hub_bodega') {
    include('THEHUB/BODEGAS/hub_bodega.php');
}

if ($_GET['acc'] == 'hub_registrar_bodega') {
    include('THEHUB/BODEGAS/hub_registrar_bodega.php');
}

if ($_GET['acc'] == 'hub_movimiento_articulo') {
    include('THEHUB/BODEGAS/hub_movimiento_articulo.php');
}

if ($_GET['acc'] == 'hub_registrar_movimiento_articulo') {
    include('THEHUB/BODEGAS/hub_registrar_movimiento_articulo.php');
}
