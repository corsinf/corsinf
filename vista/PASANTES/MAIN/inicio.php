<?php

if ($_GET['acc'] == 'asistencias_pasantes') {
    include('PASANTES/MAIN/ASISTENCIAS/asistencias_pasantes.php');
}

if ($_GET['acc'] == 'registro_pasantes') {
    include('PASANTES/MAIN/ASISTENCIAS/registro_pasantes.php');
}

if ($_GET['acc'] == 'registro_pasantes_fin') {
    include('PASANTES/MAIN/ASISTENCIAS/registro_pasantes_fin.php');
}