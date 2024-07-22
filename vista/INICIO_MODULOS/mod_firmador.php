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
