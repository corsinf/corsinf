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

if (isset($_GET['guardar_antena'])) {
	$parametros = $_POST['parametros'];
    echo json_encode($controlador->guardar_antena($parametros));
}
if (isset($_GET['eliminar_portal_antena'])) {
	$parametros = $_POST['parametros'];
    echo json_encode($controlador->eliminar_portal_antena($parametros));
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
 		if(count($portal)>0)
 		{
	 		switch ($portal[0]['comunicacion']) {
	 			case 'TCPIP':
	 			// $command = "C:\\Users\\lenovo\\Downloads\\SESProLElibEPCcmd\\bin\\Debug\\net8.0\\SESProLElibEPCcmd.dll 2 186.4.219.172 10001";
	 			$command =  dirname(__DIR__,2). "\\lib\\Antenas\\net8.0\\SESProLElibEPCcmd.exe 2 ".$portal[0]['ip']." ".$portal[0]['puerto']."";
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

 	function guardar_antena($parametros)
 	{
 		if($parametros['tipo']=='TCPIP')
 		{
 			$parametros['comm'] = '.';
			$parametros['comm2'] = '.';
			$parametros['adr'] = '.';
 		}
 		$datos = array(
 				array('campo'=>'nombre_portal','dato'=>$parametros['nombre']),
 				array('campo'=>'com_portal','dato'=>$parametros['comm']),
 				array('campo'=>'com2_portal','dato'=>$parametros['comm2']),
 				array('campo'=>'adr','dato'=>$parametros['adr']),
 				array('campo'=>'ip_portal','dato'=>$parametros['ip']),
 				array('campo'=>'puerto_portal','dato'=>$parametros['puerto']),
 				array('campo'=>'comunicacion_portal','dato'=>$parametros['tipo'])
 			);
 		return $this->modelo->guardar_antena('ac_portales',$datos);
 		print_r($parametros);die();
 	}
 	
 	function eliminar_portal_antena($parametros)
 	{
 		return $this->modelo->eliminar_portal_antena($parametros['id']);
 	}


 } 
?>