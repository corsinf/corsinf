<?php
require_once(dirname(__DIR__, 2) .'/modelo/procesosM.php');
/**
 * 
 */
$controlador = new procesosC();
if(isset($_GET['procesos']))
{
	$datos = $controlador->procesos();
	echo json_encode($datos);
}
class procesosC
{
	private $modelo;
	function __construct()
	{
		$this->modelo = new procesosM();

	}

	function procesos()
	{
		$datos = $this->modelo->lista_procesos();
		return $datos;
	}
}

?>