<?php

if ($_GET['acc'] == 'form_insert_adrian') {
    include('PASANTES/02_ADRIAN/form_insert_adrian.php');
}

if ($_GET['acc'] == 'inicio_prueba_3') {
    include('FIRMADOR/INICIO_PRUEBA/inicio_prueba_3.php');
}

if ($_GET['acc'] == 'inicio_prueba_4') {
    include('FIRMADOR/INICIO_PRUEBA/inicio_prueba_4.php');
}

if ($_GET['acc'] == 'validar_pdf') {
    include('FIRMADOR/INICIO_PRUEBA/validar_pdf.php');
}

/**
 * 
 * POSTULANTES
 * 
 */


if ($_GET['acc'] == 'postulantes') {
    include('PASANTES/02_ADRIAN/POSTULANTES/postulantes.php');
}

if ($_GET['acc'] == 'registrar_postulantes') {
    include('PASANTES/02_ADRIAN/POSTULANTES/registrar_postulantes.php');
}

if ($_GET['acc'] == 'informacion_personal') {
    include('PASANTES/02_ADRIAN/POSTULANTES/informacion_personal.php');
}
