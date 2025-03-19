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

if ($_GET['acc'] == 'th_programar_horarios') {
	include('TALENTO_HUMANO/ASISTENCIAS/PROGRAMAR_HORARIOS/th_programar_horarios.php');
}

if ($_GET['acc'] == 'th_registrar_programar_horarios') {
	include('TALENTO_HUMANO/ASISTENCIAS/PROGRAMAR_HORARIOS/th_registrar_programar_horarios.php');
}

//Deteccion de dispositivos

if ($_GET['acc'] == 'th_detectar_dispositivos') {
	include('TALENTO_HUMANO/DISPOSITIVOS/th_detectar_dispositivos.php');
}

if ($_GET['acc'] == 'th_tomar_datos_biometricos') {
	include('TALENTO_HUMANO/DISPOSITIVOS/th_tomar_datos_biometricos.php');
}

//Justificaciones
if ($_GET['acc'] == 'th_justificaciones_tipo') {
	include('TALENTO_HUMANO/JUSTIFICACIONES/th_justificaciones_tipo.php');
}

if ($_GET['acc'] == 'th_registrar_tipo_justificacion') {
	include('TALENTO_HUMANO/JUSTIFICACIONES/th_registrar_tipo_justificacion.php');
}

if ($_GET['acc'] == 'th_justificaciones') {
	include('TALENTO_HUMANO/JUSTIFICACIONES/th_justificaciones.php');
}

if ($_GET['acc'] == 'th_registrar_justificaciones') {
	include('TALENTO_HUMANO/JUSTIFICACIONES/th_registrar_justificaciones.php');
}

//Feriados
if ($_GET['acc'] == 'th_feriados') {
	include('TALENTO_HUMANO/FERIADOS/th_feriados.php');
}

if ($_GET['acc'] == 'th_registrar_feriados') {
	include('TALENTO_HUMANO/FERIADOS/th_registrar_feriados.php');
}

/**
 * 
 * POSTULANTES
 * 
 */


if ($_GET['acc'] == 'th_postulantes') {
	include('TALENTO_HUMANO/POSTULANTES/postulantes.php');
}

if ($_GET['acc'] == 'th_registrar_postulantes') {
	include('TALENTO_HUMANO/POSTULANTES/registrar_postulantes.php');
}

if ($_GET['acc'] == 'th_informacion_personal') {
	include('TALENTO_HUMANO/POSTULANTES/informacion_personal.php');
}
