<?php
//clientes
//if ($_GET['acc'] == 'clientes') {
//	include('SMARTTAG/CLIENTES/clientes.php');
//}

//proveedor
//if ($_GET['acc'] == 'proveedor') {
//	include('SMARTTAG/PROVEEDOR/proveedor.php');
//}


//Documentos
//facturación
if ($_GET['acc'] == 'facturacion') {
	include('SMARTTAG/DOCUMENTOS/facturacion.php');
}

//COTIZACION
if ($_GET['acc'] == 'cotizacion') {
	include('SMARTTAG/DOCUMENTOS/cotizacion.php');
}

//Articulos
//joya
if ($_GET['acc'] == 'ropa') {
	include('SMARTTAG/ARTICULOS/ropa.php');
}

//bodegas
if ($_GET['acc'] == 'bodegas') {
	include('SMARTTAG/ARTICULOS/bodegas.php');
}
//materiales
if ($_GET['acc'] == 'materiales') {
	include('SMARTTAG/ARTICULOS/materiales.php');
}
//portales
if ($_GET['acc'] == 'portales') {
	include('SMARTTAG/ARTICULOS/portales.php');
}

//kardex
if ($_GET['acc'] == 'kardex') {
	include('SMARTTAG/ARTICULOS/kardex.php');
}
//transacciones
if ($_GET['acc'] == 'transacciones') {
	include('SMARTTAG/ARTICULOS/transacciones.php');
}


//Punto de venta
//admin punto de venta
if ($_GET['acc'] == 'admin_punto_venta') {
	include('SMARTTAG/PUNTO_DE_VENTA/admin_punto_venta.php');
}

//Trabajos
//trabajos en joyas
if ($_GET['acc'] == 'trabajos_en_joyas') {
	include('SMARTTAG/TRABAJOS/trabajos_en_joyas.php');
}

//lista de trabajos
if ($_GET['acc'] == 'lista_de_trabajos') {
	include('SMARTTAG/TRABAJOS/lista_de_trabajos.php');
}

//orden de trabajo
if ($_GET['acc'] == 'orden_de_trabajo') {
	include('SMARTTAG/TRABAJOS/orden_de_trabajo.php');
}

//nuevo trabajo joya
if ($_GET['acc'] == 'nuevo_trabajo_joya') {
	include('SMARTTAG/TRABAJOS/nuevo_trabajo_joya.php');
}
//nueva orden trabajo
if ($_GET['acc'] == 'nueva_orden_trabajo') {
	include('SMARTTAG/TRABAJOS/nueva_orden_trabajo.php');
}


//Ventas
//facturas de venta
if ($_GET['acc'] == 'facturas_de_venta') {
	include('SMARTTAG/VENTAS/facturas_de_venta.php');
}

//facturas de venta
if ($_GET['acc'] == 'cuentas_por_cobrar') {
	include('SMARTTAG/VENTAS/cuentas_por_cobrar.php');
}

//facturas de venta
if ($_GET['acc'] == 'cotizacion_de_venta') {
	include('SMARTTAG/VENTAS/cotizacion_de_venta.php');
}






?>