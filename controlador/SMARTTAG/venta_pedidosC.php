<?php 
if(isset($_SESSION['INICIO']))
{   
  @session_start();
}else
{
     session_start();
}
require_once(dirname(__DIR__, 2) .'/db/codigos_globales.php');
require_once(dirname(__DIR__, 2) .'/modelo/venta_pedidosM.php');
// include('../modelo/usuariosM.php');

$controlador = new venta_pedidosC();
if (isset($_GET['pedidos'])) {
	echo json_encode($controlador->pedidos());
}
if (isset($_GET['pedidos_finalizadas'])) {
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->pedidos_finalizadas($parametros));
}

if (isset($_GET['pedidos_pendientes'])) {
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->pedidos_pendientes($parametros));
}



class venta_pedidosC
{
	private $modelo;
	private $pagina;
	private $global;
	private $pdf;

	
	function __construct()
	{
		$this->modelo = new venta_pedidosM();
		// $this->tipo = new venta_pedidosM();
		$this->pagina = new codigos_globales();
		$this->pagina->registrar_pagina_creada('../vista/venta_pedidos.php','cotizacion de venta','5','estado');
	}


	function pedidos()
	{
		$datos = $this->modelo->cargar_todas_pedidos();
		// print_r($datos);die();
		$cabecera = array('cliente','Num factura','Fecha','subtotal','Iva','Total','Estado');
		$ocultar = array('id','tipo','punto_venta');
		$botones[0] = array('boton'=>'Ver factura','icono'=>'<i class="fas fa-eye nav-icon"></i>','tipo'=>'default','id'=>'id,tipo,estado,punto_venta');
		$tabla = $this->pagina->tabla_generica($datos,$cabecera,$botones,false,$ocultar,$foto=false);
		return $tabla;
	}

	function pedidos_pendientes($parametros)
	{
		$datos = $this->modelo->cargar_todas_pedidos($parametros['tipo']);
		// print_r($datos);die();
		$cabecera = array('cliente','Num factura','Fecha','subtotal','Iva','Total','Estado');
		$ocultar = array('id','tipo','punto_venta');
		$botones[0] = array('boton'=>'Ver factura','icono'=>'<i class="fas fa-eye nav-icon"></i>','tipo'=>'default','id'=>'id,tipo,estado,punto_venta');
		$tabla = $this->pagina->tabla_generica($datos,$cabecera,$botones,false,$ocultar,$foto=false);
		return $tabla;

	}
	function pedidos_finalizadas($parametros)
	{
		$datos = $this->modelo->cargar_todas_pedidos($parametros['tipo']);
		// print_r($datos);die();
		$cabecera = array('cliente','Num factura','Fecha','subtotal','Iva','Total','Estado');
		$ocultar = array('id','tipo','punto_venta');
		$botones[0] = array('boton'=>'Ver factura','icono'=>'<i class="fas fa-eye nav-icon"></i>','tipo'=>'default','id'=>'id,tipo,estado,punto_venta');
		$tabla = $this->pagina->tabla_generica($datos,$cabecera,$botones,false,$ocultar,$foto=false);
		return $tabla;
		
	}
	

}
?>