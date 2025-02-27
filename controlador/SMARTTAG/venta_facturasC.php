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

$controlador = new venta_facturasC();
if (isset($_GET['facturas'])) {
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->facturas($parametros));
}
if (isset($_GET['facturas_finalizadas'])) {
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->facturas_finalizadas($parametros));
}

if (isset($_GET['facturas_pendientes'])) {
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->facturas_pendientes($parametros));
}

if(isset($_GET['punto_venta']))
{
	$query = '';
	if(isset($_GET['q']))
	{
		$query = $_GET['q'];
	}
	echo json_encode($controlador->punto_venta($query));
}

class venta_facturasC
{
	private $modelo;
	private $pagina;
	private $global;
	private $pdf;

	
	function __construct()
	{
		$this->modelo = new venta_facturasM();
		// $this->tipo = new venta_facturasM();
		$this->pagina = new codigos_globales();
		$this->pagina->registrar_pagina_creada('../vista/venta_facturas.php','Facturas de venta','5','estado');
	}


	function facturas($parametros)
	{
		$datos = $this->modelo->cargar_todas_facturas($parametros['query'],false,$parametros['punto']);
		// print_r($datos);die();
		$cabecera = array('cliente','Num factura','Fecha','subtotal','Iva','Total','Estado');
		$ocultar = array('id','tipo','punto_venta');		
		$botones[0] = array('boton'=>'Ver factura','icono'=>'<i class="fas fa-eye nav-icon"></i>','tipo'=>'default','id'=>'id,tipo,estado,punto_venta');
		$tabla = $this->pagina->tabla_generica($datos,$cabecera,$botones,false,$ocultar,$foto=false);
		return $tabla;
	}

	function facturas_pendientes($parametros)
	{
		$datos = $this->modelo->cargar_todas_facturas($parametros['query'],$parametros['tipo'],$parametros['punto']);
		// print_r($datos);die();
		$cabecera = array('cliente','Num factura','Fecha','subtotal','Iva','Total','Estado');
		$ocultar = array('id','tipo','punto_venta');
		$botones[0] = array('boton'=>'Ver factura','icono'=>'<i class="fas fa-eye nav-icon"></i>','tipo'=>'default','id'=>'id,tipo,estado,punto_venta');
		$tabla = $this->pagina->tabla_generica($datos,$cabecera,$botones,false,$ocultar,$foto=false);
		return $tabla;

	}
	function facturas_finalizadas($parametros)
	{
		$datos = $this->modelo->cargar_todas_facturas($parametros['query'],$parametros['tipo'],$parametros['punto']);
		// print_r($datos);die();
		$cabecera = array('cliente','Num factura','Fecha','subtotal','Iva','Total','Estado');
		$ocultar = array('id','tipo','punto_venta');
		$botones[0] = array('boton'=>'Ver factura','icono'=>'<i class="fas fa-eye nav-icon"></i>','tipo'=>'default','id'=>'id,tipo,estado,punto_venta');
		$tabla = $this->pagina->tabla_generica($datos,$cabecera,$botones,false,$ocultar,$foto=false);
		return $tabla;
		
	}
	 function punto_venta($query)
	{
		$datos = $this->modelo->punto_venta($query);
		$cta = array();
		foreach ($datos as $key => $value) {
			$cta[] = array('id'=>$value['id'],'text'=>utf8_encode($value['nombre']));			
		}
		return $cta;
	}
	

}
?>