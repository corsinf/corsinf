<?php

//ACTIVOS

if ($_GET['acc'] == 'articulos_cr') {
    include('ACTIVOS_FIJOS/ARTICULOS/articulos_cr.php');
}

if ($_GET['acc'] == 'detalle_articulo') {
    include('ACTIVOS_FIJOS/ARTICULOS/detalle_articulo.php');
}

if ($_GET['acc'] == 'ac_articulos_registrar') {
    include('ACTIVOS_FIJOS/ARTICULOS/ac_articulos_registrar.php');
}

if ($_GET['acc'] == 'cambios_custodio_localizacion') {
    include('ACTIVOS_FIJOS/cambios_custodio_localizacion.php');
}

if ($_GET['acc'] == 'cargar_datos') {
    include('ACTIVOS_FIJOS/cargar_datos.php');
}

if ($_GET['acc'] == 'parametros_art') {
    include('ACTIVOS_FIJOS/parametros_art.php');
}

if ($_GET['acc'] == 'localizacion') {
    include('ACTIVOS_FIJOS/LOCALIZACION/ac_localizacion.php');
}

if ($_GET['acc'] == 'proyectos') {
    include('ACTIVOS_FIJOS/PROYECTOS/ac_proyectos.php');
}

if ($_GET['acc'] == 'detalle_proyectos') {
    include('ACTIVOS_FIJOS/PROYECTOS/ac_proyectos_detalle.php');
}

if ($_GET['acc'] == 'clase_movimiento') {
    include('ACTIVOS_FIJOS/CLASE_MOVIMIENTO/ac_clase_movimiento.php');
}

if ($_GET['acc'] == 'detalle_clase_movimiento') {
    include('ACTIVOS_FIJOS/CLASE_MOVIMIENTO/ac_clase_movimiento_detalle.php');
}

if ($_GET['acc'] == 'actas') {
    include('ACTIVOS_FIJOS/actas.php');
}

if ($_GET['acc'] == 'reportes') {
    include('ACTIVOS_FIJOS/reportes.php');
}

if ($_GET['acc'] == 'nuevo_reporte') {
    include('ACTIVOS_FIJOS/nuevo_reporte.php');
}

if ($_GET['acc'] == 'reporte_detalle') {
    include('ACTIVOS_FIJOS/reporte_detalle.php');
}

if ($_GET['acc'] == 'lista_formatos') {
    include('ACTIVOS_FIJOS/lista_formatos.php');
}

if ($_GET['acc'] == 'ac_descarga_lotes') {
    include('ACTIVOS_FIJOS/REPORTES/ac_descarga_lotes.php');
}



/**
 * @todo Revisar este archivo
 * @note Actualmente se mantiene como respaldo
 * @warning No modificar este archivo sin autorización.
 */

if ($_GET['acc'] == 'patrimoniales') {
    include('ACTIVOS_FIJOS/patrimoniales.php');
}

if ($_GET['acc'] == 'impresiones_tag') {
    include('ACTIVOS_FIJOS/impresiones_tag.php');
}

if ($_GET['acc'] == 'cargar_bajas') {
    include('ACTIVOS_FIJOS/cargar_bajas.php');
}

if ($_GET['acc'] == 'localizacion_detalle') {
    include('ACTIVOS_FIJOS/LOCALIZACION/ac_localizacion_detalle.php');
}

if ($_GET['acc'] == 'custodio') {
    include('ACTIVOS_FIJOS/PERSONAS/ac_custodio.php');
}

if ($_GET['acc'] == 'custodio_detalle') {
    include('ACTIVOS_FIJOS/PERSONAS/ac_custodio_detalle.php');
}

if ($_GET['acc'] == 'in_kardex') {
    include('ACTIVOS_FIJOS/INVENTARIOS/KARDEX/in_kardex.php');
}

/**
 * @deprecated Archivo dado de baja el 02/04/2025.
 * @note Este archivo se mantiene como respaldo, pero ya no se utilizará en producción.
 * @warning No modificar este archivo. Para cambios, referirse a la nueva implementación.
 */

// if ($_GET['acc'] == 'articulos') {
//     include('ACTIVOS_FIJOS/articulos.php');
// }

// if ($_GET['acc'] == 'lista_patrimoniales') {
//     include('ACTIVOS_FIJOS/lista_patrimoniales.php');
// }

// if ($_GET['acc'] == 'bajas') {
//     include('ACTIVOS_FIJOS/bajas.php');
// }

// if ($_GET['acc'] == 'terceros') {
//     include('ACTIVOS_FIJOS/terceros.php');
// }