<?php 
if(isset($_SESSION['INICIO']))
{   
  @session_start();
}else
{
     session_start();
}
require_once(dirname(__DIR__, 2) .'/db/codigos_globales.php');
require_once(dirname(__DIR__, 2) .'/modelo/inicioM.php');

/**
 * 
 */$controlador = new inicioC();



class inicioC
{
	private $modelo;
	private $pagina;

	
	function __construct()
	{
		$this->modelo = new inicioM();
		$this->pagina = new codigos_globales();
		$this->pagina->registrar_pagina_creada('../vista/inicio.php','Home',NULL,'estado'); 
	}

}
?>