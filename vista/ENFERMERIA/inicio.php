<?php

//Salud

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//Comunidad

//Estudiantes
if ($_GET['acc'] == 'estudiantes') {
    include('ENFERMERIA/Estudiantes/estudiantes.php');
}

if ($_GET['acc'] == 'registrar_estudiantes') {
    include('ENFERMERIA/Estudiantes/registrar_estudiantes.php');
}

//Representantes
if ($_GET['acc'] == 'representantes') {
    include('ENFERMERIA/Representantes/representantes.php');
}

if ($_GET['acc'] == 'registrar_representantes') {
    include('ENFERMERIA/Representantes/registrar_representantes.php');
}

//Administrativos
if ($_GET['acc'] == 'administrativos') {
    include('ENFERMERIA/Administrativos/administrativos.php');
}

if ($_GET['acc'] == 'registrar_administrativos') {
    include('ENFERMERIA/Administrativos/registrar_administrativos.php');
}

//Comunidad
if ($_GET['acc'] == 'comunidad') {
    include('ENFERMERIA/Comunidad/comunidad.php');
}

if ($_GET['acc'] == 'registrar_comunidad') {
    include('ENFERMERIA/Comunidad/registrar_comunidad.php');
}

//Docentes
if ($_GET['acc'] == 'docentes') {
    include('ENFERMERIA/Docentes/docentes.php');
}

if ($_GET['acc'] == 'registrar_docentes') {
    include('ENFERMERIA/Docentes/registrar_docentes.php');
}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


//Atenciones

if ($_GET['acc'] == 'atencion_pacientes') {
    include('ENFERMERIA/Atenciones/atencion_pacientes.php');
}

if ($_GET['acc'] == 'agendamiento') {
    include('ENFERMERIA/Atenciones/agendamiento.php');
}

if ($_GET['acc'] == 'agendamiento_asistente') {
    include('ENFERMERIA/Atenciones/agendamiento_asistente.php');
}

//Pacientes
if ($_GET['acc'] == 'pacientes') {
    include('ENFERMERIA/Pacientes/pacientes.php');
}

//Ficha medica
if ($_GET['acc'] == 'ficha_medica_pacientes') {
    include('ENFERMERIA/Fichas_Medicas/ficha_medica_pacientes.php');
}

//Consultas

if ($_GET['acc'] == 'consultas') {
    include('ENFERMERIA/Consultas/consultas.php');
}

if ($_GET['acc'] == 'consultas_estudiantes') {
    include('ENFERMERIA/Inspeccion/consultas_estudiantes.php');
}

if ($_GET['acc'] == 'detalle_consulta') {
    include('ENFERMERIA/Consultas/detalle_consulta.php');
}

if ($_GET['acc'] == 'consultas_pacientes') {
    include('ENFERMERIA/Consultas/consultas_pacientes.php');
}

if ($_GET['acc'] == 'registrar_consulta_paciente') {
    include('ENFERMERIA/Consultas/registrar_consulta_paciente.php');
}

if ($_GET['acc'] == 'seguimientos_personal') {
    include('ENFERMERIA/Consultas/seguimiento_personal.php');
}

//Pendiente para enviar Correo
if ($_GET['acc'] == 'mensaje_atencion') {
    include('ENFERMERIA/Consultas/Estudiantes/mensaje_atencion.php');
}


////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//Inicio representante

if ($_GET['acc'] == 'inicio_representante') {
    include('ENFERMERIA/Inicio/inicio_representante.php');
}

//Perfil del estudiante
if ($_GET['acc'] == 'perfil_estudiante_salud') {
    include('ENFERMERIA/Estudiantes/perfil_estudiante_salud.php');
}
//Para mostrar información de estudiante, relacionado con el modulo de salud, horario de clase.
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//Cursos


if ($_GET['acc'] == 'registrar_seccion') {
    include('ENFERMERIA/cursos/seccion/registrar_seccion.php');
}

if ($_GET['acc'] == 'seccion') {
    include('ENFERMERIA/cursos/seccion/seccion.php');
}

if ($_GET['acc'] == 'registrar_grado') {
    include('ENFERMERIA/cursos/grado/registrar_grado.php');
}

if ($_GET['acc'] == 'grado') {
    include('ENFERMERIA/cursos/grado/grado.php');
}

if ($_GET['acc'] == 'registrar_paralelo') {
    include('ENFERMERIA/cursos/paralelo/registrar_paralelo.php');
}

if ($_GET['acc'] == 'paralelo') {
    include('ENFERMERIA/cursos/paralelo/paralelo.php');
}


////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//Farmacia

//Medicinas
if ($_GET['acc'] == 'registrar_medicamentos') {
    include('ENFERMERIA/Farmacia/Medicamentos/registrar_medicamentos.php');
}

if ($_GET['acc'] == 'medicamentos') {
    include('ENFERMERIA/Farmacia/Medicamentos/medicamentos.php');
}

if ($_GET['acc'] == 'cargar_farmacia') {
    include('ENFERMERIA/Farmacia/cargar_farmacia.php');
}

//Insumos
if ($_GET['acc'] == 'registrar_insumos') {
    include('ENFERMERIA/Farmacia/Insumos/registrar_insumos.php');
}

if ($_GET['acc'] == 'insumos') {
    include('ENFERMERIA/Farmacia/Insumos/insumos.php');
}
if ($_GET['acc'] == 'ingreso_stock') {
    include('ENFERMERIA/Farmacia/Ingreso_Stock/ingreso_stock.php');
}
if ($_GET['acc'] == 'salida_stock') {
    include('ENFERMERIA/Farmacia/Ingreso_Stock/salida_stock.php');
}
if ($_GET['acc'] == 'movimiento_stock') {
    include('ENFERMERIA/Farmacia/Ingreso_Stock/movimiento_stock.php');
}

//Notificaciones

if ($_GET['acc'] == 'notificaciones') {
    include('ENFERMERIA/Notificaciones/notificaciones.php');
}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//Docentes Agenda
//Clases

if ($_GET['acc'] == 'docente_paralelo') {
    include('ENFERMERIA/Docentes/Clases/docente_paralelo.php');
}

if ($_GET['acc'] == 'horario_clases') {
    include('ENFERMERIA/Docentes/Clases/horario_clases.php');
}

if ($_GET['acc'] == 'horario_disponible') {
    include('ENFERMERIA/Docentes/Clases/horario_disponible.php');
}

//Reunion
if ($_GET['acc'] == 'reunion_rep_doc') {
    include('ENFERMERIA/Docentes/Reunion/reunion_rep_doc.php');
}

if ($_GET['acc'] == 'reuniones_representante') {
    include('ENFERMERIA/Representantes/reuniones.php');
}

if ($_GET['acc'] == 'reuniones') {
    include('ENFERMERIA/Docentes/Reunion/reuniones.php');
}

//Historial de salud Estudiantes por Paralelo
if ($_GET['acc'] == 'historial_salud_estudiantil') {
    include('ENFERMERIA/Docentes/historial_salud_estudiantil.php');
}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//Inspección
//Permisos

//Historial de salud Estudiantes por Paralelo
if ($_GET['acc'] == 'permisos_salida_est') {
    include('ENFERMERIA/Inspeccion/permisos_salida_est.php');
}

//Para DBA Salud integral 
if ($_GET['acc'] == 'medicamentos_insumos_inputs') {
    include('ENFERMERIA/DBA/medicamentos_insumos_inputs.php');
}

if ($_GET['acc'] == 'config_general') {
    include('ENFERMERIA/DBA/config_general.php');
}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//Tutores

//Historial de salud Estudiantes por Paralelo
if ($_GET['acc'] == 'historial_salud_estudiantil_tutores') {
    include('ENFERMERIA/Tutores/historial_salud_estudiantil_tutores.php');
}

//Para DBA Salud integral 
if ($_GET['acc'] == 'paralelos_tutores') {
    include('ENFERMERIA/Tutores/paralelos_tutores.php');
}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//Idukay

//Para Idukay
if ($_GET['acc'] == 'configuraciones_idukay') {
    include('ENFERMERIA/Idukay/configuraciones_idukay.php');
}

//Don Vini
//Para que un usuario pueda actualizar estudiantes y representantes
if ($_GET['acc'] == 'don_vini') {
    include('ENFERMERIA/DON_VINI/don_vini.php');
}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////