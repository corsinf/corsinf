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

//numero de pisos


if ($_GET['acc'] == 'hub_numero_piso') {
    include('HOST_TIME/ESPACIOS/hub_cat_numero_piso.php');
}

if ($_GET['acc'] == 'hub_numero_piso_registrar') {
    include('HOST_TIME/ESPACIOS/hub_cat_numero_piso_registrar.php');
}

//tipo de espacios


if ($_GET['acc'] == 'hub_tipos_espacios') {
    include('HOST_TIME/ESPACIOS/hub_tipos_espacios.php');
}
if ($_GET['acc'] == 'hub_registrar_tipo_espacio') {
    include('HOST_TIME/ESPACIOS/hub_registrar_tipo_espacio.php');
}


//
//MODULO HORARIOS
//


//horarios

if ($_GET['acc'] == 'hub_horarios') {
    include('HOST_TIME/HORARIOS/hub_horarios.php');
}
if ($_GET['acc'] == 'hub_registrar_horario') {
    include('HOST_TIME/HORARIOS/hub_registrar_horario.php');
}
