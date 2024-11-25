<?php

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Firmador
// Cartas de Autorización

if ($_GET['acc'] == 'persona_natural_ca') {
    include('FIRMADOR/CARTA_AUTORIZACION/persona_natural_ca.php');
}

if ($_GET['acc'] == 'persona_natural_ruc_ca') {
    include('FIRMADOR/CARTA_AUTORIZACION/persona_natural_ruc_ca.php');
}

if ($_GET['acc'] == 'persona_juridica_ca') {
    include('FIRMADOR/CARTA_AUTORIZACION/persona_juridica_ca.php');
}

if ($_GET['acc'] == 'firmas') {
    include('FIRMADOR/Firmas/firmas.php');
}

if ($_GET['acc'] == 'agregar_firma') {
    include('FIRMADOR/Firmas/registrar_firma.php');
}

if ($_GET['acc'] == 'inicio_prueba') {
    include('FIRMADOR/INICIO_PRUEBA/inicio_prueba.php');
}

if ($_GET['acc'] == 'inicio_prueba_2') {
    include('FIRMADOR/INICIO_PRUEBA/inicio_prueba_2.php');
}

if ($_GET['acc'] == 'validar_firma') {
    include('FIRMADOR/INICIO_PRUEBA/validar_firma.php');
}

if ($_GET['acc'] == 'sala_firmado') {
    include('FIRMADOR/INICIO_PRUEBA/sala_firmado.php');
}
if ($_GET['acc'] == 'firmar_pdf') {
    include('FIRMADOR/INICIO_PRUEBA/firmar_pdf.php');
}

if ($_GET['acc'] == 'student_consent') {
    include('FIRMADOR/INICIO_PRUEBA/student_consent.php');
}

if ($_GET['acc'] == 'pagina_jav') {
    include('FIRMADOR/PRUEBAS/pagina_jav.php');
}


if ($_GET['acc'] == 'calendario_espacio') {
    include('COWORKING/calendario_espacio.php');
}

if ($_GET['acc'] == 'crear_mienbros') {
    include('COWORKING/crear_mienbros.php');
}

if ($_GET['acc'] == 'crear_mienbrosdos') {
    include('COWORKING/crear_mienbrosdos.php');
}

if ($_GET['acc'] == 'Espacios') {
    include('COWORKING/Espacios.php');
}

