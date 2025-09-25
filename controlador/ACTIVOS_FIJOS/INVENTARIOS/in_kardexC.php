<?php 
date_default_timezone_set('America/Guayaquil');
require_once(dirname(__DIR__, 3) . '/modelo/ACTIVOS_FIJOS/INVENTARIO/in_kardexM.php');


$controlador = new in_kardexC();

if (isset($_GET['Listatabla'])) {
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->Listatabla($parametros));
}


/**
 * 
 */
class in_kardexC
{
	private $modelo;
	function __construct()
	{
		$this->modelo = new in_kardexM();
	}

	function Listatabla($parametros)
	{
		$lista = $this->modelo->listarJoin($parametros['desde'],$parametros['hasta']);
		return $lista;
	}
}


?>