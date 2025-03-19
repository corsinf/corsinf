<?php
require_once(dirname(__DIR__, 2) . '/modelo/PORTALES/portalesM.php');

$controlador = new portalesC();

if (isset($_GET['lista'])) {
    echo json_encode($controlador->listar());
}
if (isset($_GET['comenzar_lectura'])) {
	$parametros = $_POST['parametros'];
    echo json_encode($controlador->comenzar_lectura($parametros));
}

/**
  * 
  */
 class portalesC 
 {
 	private $modelo;
 	function __construct()
 	{
 		$this->modelo = new portalesM();
 	}

 	function listar()
 	{
 		return $this->modelo->listar();
 	}

 	function comenzar_lectura($parametros)
 	{
 		$portal = $this->modelo->listar($parametros['id']);
 		// print_r($portal);die();
 		if(count($portal)>0)
 		{
	 		switch ($portal[0]['comunicacion']) {
	 			case 'TCPIP':
	 			// $command = "C:\\Users\\lenovo\\Downloads\\SESProLElibEPCcmd\\bin\\Debug\\net8.0\\SESProLElibEPCcmd.dll 2 186.4.219.172 10001";
	 			$command = "C:\\xampp\\htdocs\\corsinf\\lib\\Antenas\\net8.0\\SESProLElibEPCcmd.exe 2 186.4.219.172 10001";
	 			// print_r($command);die();
	 			$respuesta = shell_exec($command);
				$resp = json_decode($respuesta,true);
 				// print_r($resp);die();
	 				break;
	 			
	 			default:
	 				// code...
	 				break;
	 		}

	 		return $resp;
	 	}else
	 	{
	 		return array('resp' => '-1',"msj"=>"portal no encontrado");
	 	}
 	}
 } 
?>