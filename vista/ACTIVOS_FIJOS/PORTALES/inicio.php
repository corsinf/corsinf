<?php
if ($_GET['acc'] == 'lista_portales') {
	include('ACTIVOS_FIJOS/PORTALES/lista_portales.php');
}

//portales
if ($_GET['acc'] == 'portales') {
	include('ACTIVOS_FIJOS/PORTALES/portales.php');
}

//PORTALES
if ($_GET['acc'] == 'po_procesos') {
    include('ACTIVOS_FIJOS/PORTALES/PROCESOS/po_procesos.php');
}

if ($_GET['acc'] == 'po_procesos_registrar') {
    include('ACTIVOS_FIJOS/PORTALES/PROCESOS/po_procesos_registrar.php');
}