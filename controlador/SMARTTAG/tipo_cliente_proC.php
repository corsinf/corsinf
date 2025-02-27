<?php 
if(isset($_SESSION['INICIO']))
{   
  @session_start();
}else
{
     session_start();
}
require_once(dirname(__DIR__, 2) .'/db/codigos_globales.php');
require_once(dirname(__DIR__, 2) .'/modelo/tipo_cliente_proM.php');
/**
 * 
 */

$controlador = new tipo_cliente_proC();

if(isset($_GET['tipo_cli_c']))
{
	$query = '';
	$todo = false;
	if(isset($_GET['q']))
	{
		$query = $_GET['q'];
	}
	if(isset($_GET['todo']))
	{
		$todo =true;
	}
	echo json_encode($controlador->tipo_cli_C($query,$todo));
}
if(isset($_GET['tipo_cli_p']))
{
	$query = '';
	$todo = false;
	if(isset($_GET['q']))
	{
		$query = $_GET['q'];
	}
	if(isset($_GET['todo']))
	{
		$todo =true;
	}

	echo json_encode($controlador->tipo_cli_P($query,$todo));
}

class tipo_cliente_proC
{
	private $modelo;
	private $pagina;
		
	function __construct()
	{
		$this->modelo = new tipo_cliente_proM();
		$this->pagina = new codigos_globales();
		// $this->pagina->registrar_pagina_creada('../vista/tipo_cliente_pro.php','tipo_cliente_pro en joyas','6','estado');
	}
	
	function tipo_cli_C($query,$todo=false)
	{
		$datos = $this->modelo->ddl_tipos($query);
		$opciones = array();
		if($todo){$opciones[0] = array('id'=>'T','text'=>'Todos');}
		foreach ($datos as $key => $value) {
			$opciones[] = array('id'=>$value['id'],'text'=>$value['nombre']);		
		}
		return $opciones;
	}

	function tipo_cli_P($query,$todo=false)
	{
		$datos = $this->modelo->ddl_tipos_P($query);
		$opciones = array();
		if($todo){$opciones[0] = array('id'=>'T','text'=>'Todos');}
		foreach ($datos as $key => $value) {
			$opciones[] = array('id'=>$value['id'],'text'=>$value['nombre']);		
		}
		return $opciones;
	}
}

?>