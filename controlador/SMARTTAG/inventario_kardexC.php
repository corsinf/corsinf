<?php 
if(isset($_SESSION['INICIO']))
{   
  @session_start();
}else
{
     session_start();
}
require_once(dirname(__DIR__, 2) .'/db/codigos_globales.php');
require_once(dirname(__DIR__, 2) .'/modelo/inventario_kardexM.php');
require_once(dirname(__DIR__, 2) .'/lib/Reporte_excel.php');
require_once(dirname(__DIR__, 2) .'/lib/pdf/cabecera_pdf.php');

/**
 * 
 */$controlador = new inventario_kardexC();
if(isset($_GET['lista_kardex']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->lista_kardex($parametros));
}

if(isset($_GET['excel_kardex']))
{
	$query = $_GET['query'];
	$tipo =  $_GET['tipo'];
	echo json_encode($controlador->excel_lista($query,$tipo));
}

if(isset($_GET['pdf_kardex']))
{
	$query = $_GET['query'];
	$tipo =  $_GET['tipo'];
	echo json_encode($controlador->pdf_lista($query,$tipo));
}

if(isset($_GET['excel_existencias']))
{
	$query = $_GET['query'];
	echo json_encode($controlador->excel_existencias($query));
}



class inventario_kardexC
{
	private $modelo;
	private $pagina;
	private $excel;
	private $pdf;

	
	function __construct()
	{
		$this->modelo = new inventario_kardexM();
		$this->pagina = new codigos_globales();
		$this->pagina->registrar_pagina_creada('../vista/inventario_kardex.php','Kardex',2,'estado'); 
		$this->excel = new Reporte_excel();
		$this->pdf = new cabecera_pdf();
	}
	function lista_kardex($parametros)
	{
		// print_r($parametros);die();
		$datos = $this->modelo->lista_kardex($parametros['query'],$parametros['tipo']);
		$cabecera = array('Fecha','Producto','Entrada','Salida','No Factura','No Orden','No Trabajo','Documento','Stock','Existencias');
		$tabla = $this->pagina->tabla_generica($datos,$cabecera,$botones=false,false,$ocultar=false,$foto=false,$posicion=false,$enlace=false);
		return $tabla;
	}

	function excel_lista($query,$tipo)
	{		
		$datos = $this->modelo->lista_kardex($query,$tipo);
		$this->excel->kardex($datos);

	}

	function pdf_lista($query,$tipo)
	{		
		$datos = $this->modelo->lista_kardex($query,$tipo);
		// print_r($datos);die();
		   $tablaHTML[0]['medidas']=array(21,40,22,18,21,21,21,40,18,25);
		$tablaHTML[0]['alineado']=array('L','L','R','R','R','R','R','L','R','R');
		$tablaHTML[0]['datos']=array('FECHA','PRODUCTO','ENTRADAS','SALIDAS','No Factura','No Orden','No trabajo','TIPO DE DOCUMENTO',	'STOCK','EXISTENCIAS');
		$tablaHTML[0]['borde']=1;
		$tablaHTML[0]['estilo'] = 'B';
		$count = 1;
		foreach ($datos as $key => $value) {
			$tablaHTML[$count]['medidas']=$tablaHTML[0]['medidas'];
			$tablaHTML[$count]['alineado']=$tablaHTML[0]['alineado'];
			$tablaHTML[$count]['datos']=array($value['fecha']->format('Y-m-d'),$value['detalle_producto'],$value['entrada'],$value['salida'],$value['factura'],$value['joya'],$value['orden'],$value['Documento'],$value['existencias_ant'],$value['existencias']);
			$tablaHTML[$count]['borde']=1;
			$count+=1;
			
		}
	$this->pdf->cabecera_reporte_MC('Kardex de movimiento',$tablaHTML,$contenido=false,$image=false,false,false,$sizetable=7,true,30,'L');
		// $this->excel->kardex($datos);

	}


	function excel_existencias($query)
	{		
		$datos ='';// $this->modelo->lista_kardex($query);
		$this->excel->reporte_existencias($datos);

	}

}
?>