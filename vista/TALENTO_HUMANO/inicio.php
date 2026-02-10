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

if ($_GET['acc'] == 'th_reportes') {
	include('TALENTO_HUMANO/REPORTES/th_reportes.php');
}

if ($_GET['acc'] == 'th_registrar_reportes') {
	include('TALENTO_HUMANO/REPORTES/th_registrar_reportes.php');
}

if ($_GET['acc'] == 'th_reporte_atributos') {
	include('TALENTO_HUMANO/REPORTES/th_reporte_atributos.php');
}

if ($_GET['acc'] == 'th_reporte_tabla') {
	include('TALENTO_HUMANO/REPORTES/th_reporte_tabla.php');
}

if ($_GET['acc'] == 'th_reporte_atrasos') {
	include('TALENTO_HUMANO/REPORTES/th_reporte_atrasos.php');
}

if ($_GET['acc'] == 'th_reporte_general') {
	include('TALENTO_HUMANO/REPORTES/th_reporte_general.php');
}

if ($_GET['acc'] == 'th_reporte_general_') {
	include('TALENTO_HUMANO/REPORTES/th_reporte_general_.php');
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

if ($_GET['acc'] == 'th_personas_nomina') {
	include('TALENTO_HUMANO/PERSONAS/th_personas_nomina.php');
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

if ($_GET['acc'] == 'th_addRegistroBio') {
	include('TALENTO_HUMANO/DISPOSITIVOS/th_addRegistroBio.php');
}

if ($_GET['acc'] == 'log_dispositivos') {
	include('TALENTO_HUMANO/DISPOSITIVOS/th_log_dispositivos.php');
}


/**
 * 
 * MARCACIONES
 * 
 */

if ($_GET['acc'] == 'th_marcaciones') {
	include('TALENTO_HUMANO/MARCACIONES/th_marcaciones.php');
}

//CONTROL_ACCESO_WEB
if ($_GET['acc'] == 'th_marcaciones_web') {
	include('TALENTO_HUMANO/MARCACIONES/CONTROL_ACCESO_WEB/th_marcaciones_web.php');
}

if ($_GET['acc'] == 'th_marcaciones_web_registrar') {
	include('TALENTO_HUMANO/MARCACIONES/CONTROL_ACCESO_WEB/th_marcaciones_web_registrar.php');
}

if ($_GET['acc'] == 'th_marcaciones_web_registrar_manual') {
	include('TALENTO_HUMANO/MARCACIONES/CONTROL_ACCESO_WEB/th_marcaciones_web_registrar_manual.php');
}

// APROBACION
if ($_GET['acc'] == 'th_control_aprobacion') {
	include('TALENTO_HUMANO/MARCACIONES/APROBACION/th_control_aprobacion.php');
}

// TRIANGULAR
if ($_GET['acc'] == 'th_triangular') {
	include('TALENTO_HUMANO/MARCACIONES/TRIANGULAR/th_triangular.php');
}

if ($_GET['acc'] == 'th_triangular_registrar') {
	include('TALENTO_HUMANO/MARCACIONES/TRIANGULAR/th_triangular_registrar.php');
}

if ($_GET['acc'] == 'th_triangular_departamento') {
	include('TALENTO_HUMANO/MARCACIONES/TRIANGULAR/th_triangular_departamento.php');
}

/**
 * 
 * Recalculo
 * 
 */

if ($_GET['acc'] == 'th_recalcular') {
	include('TALENTO_HUMANO/MARCACIONES/th_recalcular.php');
}


/**
 * 
 *Contratación
 * 
 */


if ($_GET['acc'] == 'th_contr_plazas') {
	include('TALENTO_HUMANO/CONTRATACION/PLAZAS/th_contr_plazas.php');
}


if ($_GET['acc'] == 'th_registro_plaza') {
	include('TALENTO_HUMANO/CONTRATACION/PLAZAS/th_registro_plaza.php');
}

if ($_GET['acc'] == 'th_informacion_plaza') {
	include('TALENTO_HUMANO/CONTRATACION/PLAZAS/th_informacion_plaza.php');
}

if ($_GET['acc'] == 'th_contr_plaza_etapas') {
	include('TALENTO_HUMANO/CONTRATACION/PLAZAS/th_contr_plaza_etapas.php');
}


if ($_GET['acc'] == 'th_contr_plaza_cargo') {
	include('TALENTO_HUMANO/CONTRATACION/CARGO_PLAZA/th_contr_plaza_cargo.php');
}


if ($_GET['acc'] == 'th_registrar_plaza_cargo') {
	include('TALENTO_HUMANO/CONTRATACION/CARGO_PLAZA/th_registrar_plaza_cargo.php');
}



if ($_GET['acc'] == 'th_contr_cargos') {
	include('TALENTO_HUMANO/CONTRATACION/CARGOS/th_contr_cargos.php');
}

if ($_GET['acc'] == 'th_registro_cargo') {
	include('TALENTO_HUMANO/CONTRATACION/CARGOS/th_registro_cargo.php');
}





if ($_GET['acc'] == 'th_contr_postulaciones') {
	include('TALENTO_HUMANO/CONTRATACION/POSTULACIONES/th_contr_postulaciones.php');
}
if ($_GET['acc'] == 'th_contr_postulados') {
	include('TALENTO_HUMANO/CONTRATACION/POSTULACIONES/th_contr_postulados.php');
}
if ($_GET['acc'] == 'th_registro_postulaciones') {
	include('TALENTO_HUMANO/CONTRATACION/POSTULACIONES/th_registro_postulaciones.php');
}




if ($_GET['acc'] == 'th_cat_requisitos_plaza') {
	include('TALENTO_HUMANO/CONTRATACION/REQUISITOS/th_cat_requisitos_plaza.php');
}

if ($_GET['acc'] == 'th_cat_requisitos') {
	include('TALENTO_HUMANO/CONTRATACION/REQUISITOS/th_cat_requisitos.php');
}

if ($_GET['acc'] == 'th_registro_requisito') {
	include('TALENTO_HUMANO/CONTRATACION/REQUISITOS/th_registro_requisito.php');
}



if ($_GET['acc'] == 'th_contr_etapas_proceso') {
	include('TALENTO_HUMANO/CONTRATACION/ETAPAS_PROCESO/th_contr_etapas_proceso.php');
}

if ($_GET['acc'] == 'th_registro_etapa_proceso') {
	include('TALENTO_HUMANO/CONTRATACION/ETAPAS_PROCESO/th_registro_etapa_proceso.php');
}

if ($_GET['acc'] == 'th_organizar_etapas_proceso') {
	include('TALENTO_HUMANO/CONTRATACION/ETAPAS_PROCESO/th_organizar_etapas_proceso.php');
}



if ($_GET['acc'] == 'th_contr_seguimiento_postulante') {
	include('TALENTO_HUMANO/CONTRATACION/SEGUIMIENTO_POSTULANTE/th_contr_seguimiento_postulante.php');
}
if ($_GET['acc'] == 'th_registrar_seguimiento_postulante') {
	include('TALENTO_HUMANO/CONTRATACION/SEGUIMIENTO_POSTULANTE/th_registrar_seguimiento_postulante.php');
}

if ($_GET['acc'] == 'th_contr_proceso_contratacion') {
	include('TALENTO_HUMANO/CONTRATACION/PROCESO_CONTRATACION/th_contr_proceso_contratacion.php');
}



if ($_GET['acc'] == 'th_contr_cargo_requisitos') {
	include('TALENTO_HUMANO/CONTRATACION/CARGO_REQUISITOS/th_contr_cargo_requisitos.php');
}
if ($_GET['acc'] == 'th_registrar_cargo_requisitos') {
	include('TALENTO_HUMANO/CONTRATACION/CARGO_REQUISITOS/th_registrar_cargo_requisitos.php');
}


if ($_GET['acc'] == 'th_cat_requisitos_detalles') {
	include('TALENTO_HUMANO/CONTRATACION/REQUISITOS_DETALLE/th_cat_requisitos_detalles.php');
}
if ($_GET['acc'] == 'th_registrar_requisitos_detalles') {
	include('TALENTO_HUMANO/CONTRATACION/REQUISITOS_DETALLE/th_registrar_requisitos_detalles.php');
}


if ($_GET['acc'] == 'th_cat_rango_profesional') {
	include('TALENTO_HUMANO/CONTRATACION/CARGO_NIVELES/th_cat_rango_profesional.php');
}

if ($_GET['acc'] == 'th_registrar_niveles_cargo') {
	include('TALENTO_HUMANO/CONTRATACION/CARGO_NIVELES/th_registrar_niveles_cargo.php');
}


if ($_GET['acc'] == 'th_servicio_catering') {
	include('TALENTO_HUMANO/CONTRATACION/CATERING/th_servicio_catering.php');
}


//competencias 


if ($_GET['acc'] == 'th_contr_competencias') {
	include('TALENTO_HUMANO/CONTRATACION/COMPETENCIAS/th_contr_competencias.php');
}

if ($_GET['acc'] == 'th_registrar_competencia') {
	include('TALENTO_HUMANO/CONTRATACION/COMPETENCIAS/th_registrar_competencia.php');
}

/**
 * 
 * Solicitudes y justificaciones
 * 
 */

if ($_GET['acc'] == 'th_solicitud_permiso') {
	include('TALENTO_HUMANO/SOLICITUDES/SOLICITUD_PERMISO/th_solicitud_permiso.php');
}

if ($_GET['acc'] == 'th_registrar_solicitud_permiso') {
	include('TALENTO_HUMANO/SOLICITUDES/SOLICITUD_PERMISO/th_registrar_solicitud_permiso.php');
}
if ($_GET['acc'] == 'th_solicitud_persona') {
	include('TALENTO_HUMANO/SOLICITUDES/SOLICITUD_PERMISO/th_solicitud_persona.php');
}



if ($_GET['acc'] == 'th_aprobacion_solicitudes') {
	include('TALENTO_HUMANO/SOLICITUDES/SOLICITUD_MEDICO/th_aprobacion_solicitudes.php');
}

if ($_GET['acc'] == 'th_registrar_aprobacion_solicitudes') {
	include('TALENTO_HUMANO/SOLICITUDES/SOLICITUD_MEDICO/th_registrar_aprobacion_solicitudes.php');
}

if ($_GET['acc'] == 'th_solicitudes_personas') {
	include('TALENTO_HUMANO/SOLICITUDES/SOLICITUD_MEDICO/th_solicitudes_personas.php');
}

/**
 * 
 * Comision
 * 
 */

if ($_GET['acc'] == 'th_comision') {
	include('TALENTO_HUMANO/COMISION/th_comision.php');
}

if ($_GET['acc'] == 'th_registrar_comision') {
	include('TALENTO_HUMANO/COMISION/th_registrar_comision.php');
}

/**
 * 
 * Reglamento
 * 
 */

if ($_GET['acc'] == 'th_proteccion_datos') {
	include('TALENTO_HUMANO/PROTECCION_DATOS/th_proteccion_datos.php');
}

if ($_GET['acc'] == 'th_ley_violeta') {
	include('TALENTO_HUMANO/PROTECCION_DATOS/th_ley_violeta.php');
}