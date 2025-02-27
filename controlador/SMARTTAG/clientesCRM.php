<?php
require_once(dirname(__DIR__, 2) .'/modelo/clientesM.php');
/**
 * 
 */
$controlador = new clientesC();
if(isset($_GET['clientes']))
{
	$parametros = $_POST['parametros'];
	$datos = $controlador->clientes($parametros);
	echo json_encode($datos);
}
class clientesCRMC
{
	private $modelo;
	function __construct()
	{
		$this->modelo = new clientesM();

	}

	function clientes($parametros)
	{

		$datos = $this->modelo->lista_clientes($parametros['query'],$parametros['cali'],$parametros['proce']);
		return $datos;

	}
}

?>