<?php 
if(isset($_SESSION['INICIO']))
{   
  @session_start();
}else
{
     session_start();
}
require_once(dirname(__DIR__, 2) .'/db/codigos_globales.php');
require_once(dirname(__DIR__, 2) .'/modelo/venta_facturasM.php');
// include('../modelo/usuariosM.php');

$controlador = new venta_prefacturasC();
if (isset($_GET['prefacturas'])) {
	echo json_encode($controlador->prefacturas());
}
if (isset($_GET['prefacturas_finalizadas'])) {
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->prefacturas_finalizadas($parametros));
}

if (isset($_GET['prefacturas_pendientes'])) {
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->prefacturas_pendientes($parametros));
}



class venta_prefacturasC
{
	private $modelo;
	private $pagina;
	private $global;
	private $pdf;

	
	function __construct()
	{
		$this->modelo = new venta_facturasM();
		// $this->tipo = new venta_prefacturasM();
		$this->pagina = new codigos_globales();
		$this->pagina->registrar_pagina_creada('../vista/venta_prefacturas.php','Prefacturas de venta','5','estado');
	}


	function prefacturas()
	{
		$datos = $this->modelo->cargar_todas_prefacturas();
		// print_r($datos);die();
		$cabecera = array('cliente','Num factura','Fecha','subtotal','Iva','Total','Estado');
		$ocultar = array('id','tipo');
		$botones[0] = array('boton'=>'Ver factura','icono'=>'<i class="fas fa-eye nav-icon"></i>','tipo'=>'default','id'=>'id,tipo');
		$tabla = $this->pagina->tabla_generica($datos,$cabecera,$botones,false,$ocultar,$foto=false);
		return $tabla;
	}

	function prefacturas_pendientes($parametros)
	{
		$datos = $this->modelo->cargar_todas_prefacturas($parametros['tipo']);
		// print_r($datos);die();
		$cabecera = array('cliente','Num factura','Fecha','subtotal','Iva','Total','Estado');
		$ocultar = array('id','tipo');
		$botones[0] = array('boton'=>'Ver factura','icono'=>'<i class="fas fa-eye nav-icon"></i>','tipo'=>'default','id'=>'id,tipo');
		$tabla = $this->pagina->tabla_generica($datos,$cabecera,$botones,false,$ocultar,$foto=false);
		return $tabla;

	}
	function prefacturas_finalizadas($parametros)
	{
		$datos = $this->modelo->cargar_todas_prefacturas($parametros['tipo']);
		// print_r($datos);die();
		$cabecera = array('cliente','Num factura','Fecha','subtotal','Iva','Total','Estado');
		$ocultar = array('id','tipo');
		$botones[0] = array('boton'=>'Ver factura','icono'=>'<i class="fas fa-eye nav-icon"></i>','tipo'=>'default','id'=>'id,tipo');
		$tabla = $this->pagina->tabla_generica($datos,$cabecera,$botones,false,$ocultar,$foto=false);
		return $tabla;
		
	}
	

}
?>