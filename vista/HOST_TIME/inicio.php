<?php
//
//MODULO ESPACIOS
//

//Espacios

if ($_GET['acc'] == 'hub_espacios') {
    include('HOST_TIME/ESPACIOS/hub_espacios.php');
}

if ($_GET['acc'] == 'hub_registrar_espacio') {
    include('HOST_TIME/ESPACIOS/hub_registrar_espacio.php');
}



//tipo de Ubicaciones


if ($_GET['acc'] == 'hub_ubicaciones') {
    include('HOST_TIME/ESPACIOS/hub_ubicaciones.php');
}
if ($_GET['acc'] == 'hub_registrar_ubicacion') {
    include('HOST_TIME/ESPACIOS/hub_registrar_ubicacion.php');
}



//turnos

if ($_GET['acc'] == 'hub_turnos') {
    include('HOST_TIME/TURNOS/hub_turnos.php');
}

if ($_GET['acc'] == 'hub_turnos_registrar') {
    include('HOST_TIME/TURNOS/hub_turnos_registrar.php');
}


//
//MODULO RESERVAS
//

//Reservas

if ($_GET['acc'] == 'hub_reservas') {
    include('HOST_TIME/RESERVAS/hub_reservas.php');
}
if ($_GET['acc'] == 'hub_reservas_registrar') {
    include('HOST_TIME/RESERVAS/hub_reservas_registrar.php'); 
}


//
//MODULO CATALOGOS
//

//numero de pisos

if ($_GET['acc'] == 'hub_numero_piso') {
    include('HOST_TIME/CATALOGOS/hub_cat_numero_piso.php');
}

if ($_GET['acc'] == 'hub_numero_piso_registrar') {
    include('HOST_TIME/CATALOGOS/hub_cat_numero_piso_registrar.php');
}

//Tipos Espacios

if ($_GET['acc'] == 'hub_tipos_espacios') {
    include('HOST_TIME/CATALOGOS/hub_tipos_espacios.php');
}
if ($_GET['acc'] == 'hub_registrar_tipo_espacio') {
    include('HOST_TIME/CATALOGOS/hub_registrar_tipo_espacio.php');
}
