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

//Asistencias
//Turnos
if ($_GET['acc'] == 'th_registrar_turnos') {
	include('TALENTO_HUMANO/ASISTENCIAS/TURNOS/th_registrar_turnos.php');
}

if ($_GET['acc'] == 'th_turnos') {
	include('TALENTO_HUMANO/ASISTENCIAS/TURNOS/th_turnos.php');
}

//Horarios
if ($_GET['acc'] == 'th_registrar_horarios') {
	include('TALENTO_HUMANO/ASISTENCIAS/HORARIOS/th_registrar_horarios.php');
}

if ($_GET['acc'] == 'th_horarios') {
	include('TALENTO_HUMANO/ASISTENCIAS/HORARIOS/th_horarios.php');
}

if ($_GET['acc'] == 'th_detectar_dispositivos') {
	include('TALENTO_HUMANO/DISPOSITIVOS/th_detectar_dispositivos.php');
}

if ($_GET['acc'] == 'th_tomar_datos_biometricos') {
	include('TALENTO_HUMANO/DISPOSITIVOS/th_tomar_datos_biometricos.php');
}