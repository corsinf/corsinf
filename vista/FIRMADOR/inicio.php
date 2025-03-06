<?php


if ($_GET['acc'] == 'firmar_pdf') {
    include('FIRMADOR/firmar_pdf.php');
}
if ($_GET['acc'] == 'registrar_firma') {
    include('FIRMADOR/registrar_firma.php');
}
if ($_GET['acc'] == 'sala_firmado') {
    include('FIRMADOR/sala_firmado.php');
}
if ($_GET['acc'] == 'validar_firma') {
    include('FIRMADOR/validar_firma.php');
}
if ($_GET['acc'] == 'validar_pdf') {
    include('FIRMADOR/validar_pdf.php');
}
