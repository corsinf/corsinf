<?php

if ($_GET['acc'] == 'fo_pasantes') {
	include('FORMACION/PASANTES/fo_pasantes.php');
}

if ($_GET['acc'] == 'fo_registrar_pasantes') {
	include('FORMACION/PASANTES/fo_registrar_pasantes.php');
}

if ($_GET['acc'] == 'fo_asistencias_pasantes') {
	include('FORMACION/ASISTENCIAS/fo_asistencias_pasantes.php');
}

if ($_GET['acc'] == 'fo_registro_pasantes_fin') {
	include('FORMACION/ASISTENCIAS/fo_registro_pasantes_fin.php');
}

if ($_GET['acc'] == 'fo_registro_pasantes') {
	include('FORMACION/ASISTENCIAS/fo_registro_pasantes.php');
}

if ($_GET['acc'] == 'fo_informes_asistencias') {
	include('FORMACION/INFORMES/fo_informes_asistencias.php');
}



