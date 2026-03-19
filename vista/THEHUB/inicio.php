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

//Reservas de los espacios

if ($_GET['acc'] == 'hub_reservas') {
    include('THEHUB/RESERVAS/hub_reservas.php');
}

if ($_GET['acc'] == 'hub_registrar_reserva') {
    include('THEHUB/RESERVAS/hub_registrar_reserva.php');
}

if ($_GET['acc'] == 'hub_registrar_reserva_servicio') {
    include('THEHUB/RESERVAS/hub_registrar_reserva_servicio.php');
}


//rutas de las ubicaciones
if ($_GET['acc'] == 'hub_ubicaciones') {
    include('THEHUB/UBICACIONES/hub_ubicaciones.php');
}

if ($_GET['acc'] == 'hub_registrar_ubicacion') {
    include('THEHUB/UBICACIONES/hub_registrar_ubicacion.php');
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
