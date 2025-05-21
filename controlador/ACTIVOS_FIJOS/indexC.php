<?php

require_once(dirname(__DIR__, 2) . '/modelo/ACTIVOS_FIJOS/indexM.php');
require_once(dirname(__DIR__, 2) . '/db/codigos_globales.php');

/**
 * 
 **/

$controlador = new indexC();

if (isset($_GET['lista_articulos_tipo'])) {
	echo json_encode($controlador->lista_articulos_tipo());
}

if (isset($_GET['contar_custodios'])) {
	echo json_encode($controlador->contar_custodios());
}

if (isset($_GET['contar_localizacion'])) {
	echo json_encode($controlador->contar_localizacion());
}



class indexC
{
	private $modelo;
	private $cod_global;

	function __construct()
	{
		$this->modelo = new indexM();
		$this->cod_global = new codigos_globales();
	}

	function lista_articulos_tipo()
	{
		$datos = $this->modelo->lista_articulos_tipo();
		return $datos;
	}

	function contar_custodios()
	{
		$datos = $this->modelo->contar_custodios();
		return $datos;
	}

	function contar_localizacion()
	{
		$datos = $this->modelo->contar_localizacion();
		return $datos;
	}

	
}
