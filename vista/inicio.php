<?php

include('../cabeceras/header.php');

if ($_GET['acc'] == 'perfil') {
	include('perfil.php');
}
if ($_GET['acc'] == 'descargas') {
	include('ACTIVOS/descargas.php');
}

if ($_GET['acc'] == 'pagina_error') {
	include('pagina_error.php');
}

//ACTIVOS
if ($_GET['acc'] == 'articulos') {
	include('ACTIVOS/articulos.php');
}
if ($_GET['acc'] == 'cambios_custodio_localizacion') {
	include('ACTIVOS/cambios_custodio_localizacion.php');
}
if ($_GET['acc'] == 'lista_patrimoniales') {
	include('ACTIVOS/lista_patrimoniales.php');
}
if ($_GET['acc'] == 'detalle_articulo') {
	include('ACTIVOS/detalle_articulo.php');
}
if ($_GET['acc'] == 'cargar_bajas') {
	include('ACTIVOS/cargar_bajas.php');
}
if ($_GET['acc'] == 'cargar_datos') {
	include('ACTIVOS/cargar_datos.php');
}
if ($_GET['acc'] == 'parametros_art') {
	include('ACTIVOS/parametros_art.php');
}
if ($_GET['acc'] == 'localizacion') {
	include('ACTIVOS/localizacion.php');
}
if ($_GET['acc'] == 'localizacion_detalle') {
	include('ACTIVOS/localizacion_detalle.php');
}
if ($_GET['acc'] == 'custodio') {
	include('ACTIVOS/custodio.php');
}
if ($_GET['acc'] == 'custodio_detalle') {
	include('ACTIVOS/custodio_detalle.php');
}
if ($_GET['acc'] == 'proyectos') {
	include('ACTIVOS/proyectos.php');
}
if ($_GET['acc'] == 'detalle_proyectos') {
	include('ACTIVOS/detalle_proyectos.php');
}
if ($_GET['acc'] == 'clase_movimiento') {
	include('ACTIVOS/clase_movimiento.php');
}
if ($_GET['acc'] == 'detalle_clase_movimiento') {
	include('ACTIVOS/detalle_clase_movimiento.php');
}
if ($_GET['acc'] == 'impresiones_tag') {
	include('ACTIVOS/impresiones_tag.php');
}
if ($_GET['acc'] == 'actas') {
	include('ACTIVOS/actas.php');
}
if ($_GET['acc'] == 'reportes') {
	include('ACTIVOS/reportes.php');
}
if ($_GET['acc'] == 'nuevo_reporte') {
	include('ACTIVOS/nuevo_reporte.php');
}
if ($_GET['acc'] == 'reporte_detalle') {
	include('ACTIVOS/reporte_detalle.php');
}
if ($_GET['acc'] == 'siniestros') {
	include('ACTIVOS/siniestros.php');
}
if ($_GET['acc'] == 'lista_contratos') {
	include('ACTIVOS/lista_contratos.php');
}
if ($_GET['acc'] == 'contratos') {
	include('ACTIVOS/contratos.php');
}
if ($_GET['acc'] == 'bajas') {
	include('ACTIVOS/bajas.php');
}
if ($_GET['acc'] == 'terceros') {
	include('ACTIVOS/terceros.php');
}
if ($_GET['acc'] == 'patrimoniales') {
	include('ACTIVOS/patrimoniales.php');
}
if ($_GET['acc'] == 'reporte_detalle') {
	include('ACTIVOS/reporte_detalle.php');
}

//EMPRESA
if ($_GET['acc'] == 'usuarios') {
	include('EMPRESA/usuarios.php');
}
if ($_GET['acc'] == 'usuarios_perfil') {
	include('EMPRESA/usuarios_perfil.php');
}
if ($_GET['acc'] == 'tipo_usuario') {
	include('EMPRESA/tipo_usuario.php');
}
if ($_GET['acc'] == 'modulos_paginas') {
	include('EMPRESA/modulos_paginas.php');
}
if ($_GET['acc'] == 'licencias') {
	include('EMPRESA/licencias.php');
}
if ($_GET['acc'] == 'mis_licencias') {
	include('EMPRESA/mis_licencias.php');
}
if ($_GET['acc'] == 'detalle_usuario') {
	include('EMPRESA/detalle_usuario.php');
}
if ($_GET['acc'] == 'no_concurente') {
	include('EMPRESA/no_concurente.php');
}

//SEGUROS
if ($_GET['acc'] == 'lista_solicitudes') {
	include('SEGUROS/lista_solicitudes.php');
}
if ($_GET['acc'] == 'ingresar_proceso') {
	include('SEGUROS/ingresar_proceso.php');
}
if ($_GET['acc'] == 'formulario_prestamos') {
	include('SEGUROS/formulario_prestamos.php');
}

//LIBRERIA
if ($_GET['acc'] == 'nuevo_libro') {
	include('LIBRERIA/nuevo_libro.php');
}
if ($_GET['acc'] == 'lista_libros') {
	include('LIBRERIA/lista_libros.php');
}
if ($_GET['acc'] == 'detalle_libro') {
	include('LIBRERIA/detalle_libro.php');
}

//Salud

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//No vale
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//Admision

if ($_GET['acc'] == 'comunidad') {
	include('ENFERMERIA/comunidad.php');
}

//No vale
if ($_GET['acc'] == 'ficha_medica_representado') {
	include('ENFERMERIA/representantes/ficha_medica_representado.php');
}

//Consultas

if ($_GET['acc'] == 'consulta_estudiante') {
	include('ENFERMERIA/Consultas/Estudiantes/consulta_estudiante.php');
}

if ($_GET['acc'] == 'registrar_consulta_estudiante') {
	include('ENFERMERIA/Consultas/Estudiantes/registrar_consulta_estudiante.php');
}

if ($_GET['acc'] == 'mensaje_atencion') {
	include('ENFERMERIA/Consultas/Estudiantes/mensaje_atencion.php');
}

//Ficha Medica
if ($_GET['acc'] == 'ficha_estudiante') {
	include('ENFERMERIA/Fichas_Medicas/Estudiantes/ficha_estudiante.php');
}

if ($_GET['acc'] == 'registrar_ficha_estudiante') {
	include('ENFERMERIA/Fichas_Medicas/Estudiantes/registrar_ficha_estudiante.php');
}

if ($_GET['acc'] == 'parametrizacion') {
	include('ENFERMERIA/cursos/parametrizacion.php');
}

if ($_GET['acc'] == 'atencion') {
	include('ENFERMERIA/Atenciones/historial.php');
}

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
	include('ENFERMERIA/representantes/representantes.php');
}

if ($_GET['acc'] == 'registrar_representantes') {
	include('ENFERMERIA/representantes/registrar_representantes.php');
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

if ($_GET['acc'] == 'consultas_pacientes') {
	include('ENFERMERIA/Consultas/consultas_pacientes.php');
}

if ($_GET['acc'] == 'registrar_consulta_paciente') {
	include('ENFERMERIA/Consultas/registrar_consulta_paciente.php');
}


////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//Inicio representante

if ($_GET['acc'] == 'inicio_representante') {
	include('ENFERMERIA/Inicio/inicio_representante.php');
}

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

//Medicinas
if ($_GET['acc'] == 'registrar_insumos') {
	include('ENFERMERIA/Farmacia/Insumos/registrar_insumos.php');
}

if ($_GET['acc'] == 'insumos') {
	include('ENFERMERIA/Farmacia/Insumos/insumos.php');
}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



//Notificaciones

if ($_GET['acc'] == 'notificaciones') {
	include('ENFERMERIA/Notificaciones/notificaciones.php');
}



if ($_GET['acc'] == 'index') {
	// print_r($_SESSION['INICIO']);die();
	switch ($_SESSION['INICIO']['MODULO_SISTEMA']) {
		case '1':
			include('EMPRESA/index.php');
			break;
		case '2':
			include('ACTIVOS/index.php');
			break;
		case '3':
			include('SRI/index.php');
			break;
		case '4':
			include('LIBRERIA/index.php');
			break;
		case '5':
			include('SEGUROS/index.php');
			break;
		case '6':
			include('SEGUROS/index.php');
			break;
		case 'variable':

			break;
	}
}


include('../cabeceras/footer.php');
