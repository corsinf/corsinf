<?php
include('../modelo/empresaM.php');
include('../db/codigos_globales.php');
if(isset($_SESSION['INICIO']))
{	
  @session_start();
} 
/**
 * 
 */
$controlador = new empresaC();
if(isset($_GET['lista_licencias']))
{
	echo json_encode($controlador->lista_licencias());
}


class empresaC
{
	private $modelo;
	private $cod_global;
	function __construct()
	{
			$this->modelo = new licenciasM();
			$this->cod_global = new codigos_globales();
	}

}
?>