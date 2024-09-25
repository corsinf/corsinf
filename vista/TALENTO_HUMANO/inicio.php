<?php

//Dispositivos
if ($_GET['acc'] == 'th_dispositivos') {
	include('TALENTO_HUMANO/DISPOSITIVOS/th_dispositivos.php');
}

if ($_GET['acc'] == 'th_registrar_dispositivos') {
	include('TALENTO_HUMANO/DISPOSITIVOS/th_registrar_dispositivos.php');
}

//Reportes
if ($_GET['acc'] == 'th_reportes_hv') {
	include('TALENTO_HUMANO/REPORTES/th_reportes_hv.php');
}

//Departamentos
if ($_GET['acc'] == 'th_departamentos') {
	include('TALENTO_HUMANO/DEPARTAMENTOS/th_departamentos.php');
}

if ($_GET['acc'] == 'th_registrar_departamentos') {
	include('TALENTO_HUMANO/DEPARTAMENTOS/th_registrar_departamentos.php');
}

//Personas
if ($_GET['acc'] == 'th_personas') {
	include('TALENTO_HUMANO/PERSONAS/th_personas.php');
}

if ($_GET['acc'] == 'th_registrar_personas') {
	include('TALENTO_HUMANO/PERSONAS/th_registrar_personas.php');
}

