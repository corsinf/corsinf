<?php
require_once(dirname(__DIR__, 2) . '/modelo/PORTALES/portalesM.php');
date_default_timezone_set('America/Guayaquil');

$controlador = new portalesC();

if (isset($_GET['lista'])) {
    echo json_encode($controlador->listar());
}
if (isset($_GET['lista_log'])) {
    echo json_encode($controlador->listar_log());
}
if (isset($_GET['comenzar_lectura'])) {
	$parametros = $_POST['parametros'];
    echo json_encode($controlador->comenzar_lectura($parametros));
}
if (isset($_GET['comenzar_lectura_log'])) {
	$parametros = $_POST['parametros'];
    echo json_encode($controlador->comenzar_lectura_log($parametros));
}

if (isset($_GET['guardar_antena'])) {
	$parametros = $_POST['parametros'];
    echo json_encode($controlador->guardar_antena($parametros));
}
if (isset($_GET['eliminar_portal_antena'])) {
	$parametros = $_POST['parametros'];
    echo json_encode($controlador->eliminar_portal_antena($parametros));
}
if (isset($_GET['iniciarControladora'])) {
	$params = array();
	parse_str($_POST['parametros'], $params);
    echo json_encode($controlador->iniciarControladora($params));
}
if (isset($_GET['configuracion_antenas'])) {
	$parametros = $_POST['parametros'];
    echo json_encode($controlador->configuracion_antenas($parametros));
}
if (isset($_GET['guardar_config'])) {
	$parametros = $_POST['parametros'];
    echo json_encode($controlador->guardar_config($parametros));
}

/**
  * 
  */
 class portalesC 
 {
 	private $modelo;
 	private $patch_ddl;
 	function __construct()
 	{
 		$this->modelo = new portalesM();
 		// $this->patch_ddl = dirname(__DIR__,2). "\\lib\\Antenas\\net8.0\\SESProLElibEPCcmd.exe";
 		$this->patch_ddl =  dirname(__DIR__,5)."Users\\lenovo\\Downloads\\SESProLElibEPCcmd\\bin\\Debug\\net8.0\\SESProLElibEPCcmd.exe";
 	}

 	function listar()
 	{
 		return $this->modelo->listar();
 	}

 	function listar_log()
 	{
 		return $this->modelo->listar_log();
 	}

 	function comenzar_lectura_log($parametros)
 	{
 		$portal = $this->modelo->listar($parametros['id']);
 		if(count($portal)>0)
 		{
	 		switch ($portal[0]['comunicacion']) {
	 			case 'TCPIP':
	 			// $command = "C:\\Users\\lenovo\\Downloads\\SESProLElibEPCcmd\\bin\\Debug\\net8.0\\SESProLElibEPCcmd.dll 2 186.4.219.172 10001";
	 			$command = $this->patch_ddl." 2 ".$portal[0]['ip']." ".$portal[0]['puerto']."";
	 			// print_r($command);die();
	 			$respuesta = shell_exec($command);
				$resp = json_decode($respuesta,true);
 				// print_r($resp);die();
	 				break;
	 			
	 			default:
	 				// code...
	 				break;
	 		}

	 		// print_r($resp);die();

	 		if($resp['resp']!='-1')
	 		{
		 		foreach ($resp as $key => $value) {
		 			$linea =  json_decode($value,true);
		 			// print_r($linea);die();

			 			$datos = array(
			 				array('campo'=>'ac_plog_controladora','dato'=>$parametros['id']),
			 				array('campo'=>'ac_plog_rfid','dato'=>$linea['epc']),
			 				array('campo'=>'ac_plog_antena','dato'=>$linea['No']),
			 				array('campo'=>'ac_plog_fecha_creacion','dato'=>date('Y-m-d H:i:s')),
		 			);
					 $this->modelo->guardar_antena('ac_portales_logs',$datos);
		 			// code...
		 		}
	 		}



	 		return $resp;
	 	}else
	 	{
	 		return array('resp' => '-1',"msj"=>"portal no encontrado");
	 	}
 	}

 	function comenzar_lectura($parametros)
 	{
 		$portal = $this->modelo->listar($parametros['id']);
 		if(count($portal)>0)
 		{
	 		switch ($portal[0]['comunicacion']) {
	 			case 'TCPIP':
	 			// $command = "C:\\Users\\lenovo\\Downloads\\SESProLElibEPCcmd\\bin\\Debug\\net8.0\\SESProLElibEPCcmd.dll 2 186.4.219.172 10001";
	 			$command =  $this->patch_ddl." 2 ".$portal[0]['ip']." ".$portal[0]['puerto']."";
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

 	function configuracion_antenas($parametros)
 	{
 		$portal = $this->modelo->listar($parametros['id']);
 		if(count($portal)>0)
 		{
	 		switch ($portal[0]['comunicacion']) {
	 			case 'TCPIP':
	 			// $command = "C:\\Users\\lenovo\\Downloads\\SESProLElibEPCcmd\\bin\\Debug\\net8.0\\SESProLElibEPCcmd.dll 2 186.4.219.172 10001";
	 			$command =  $this->patch_ddl." 2 ".$portal[0]['ip']." ".$portal[0]['puerto']."";
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

 	function iniciarControladora($parametros)
 	{
 		// print_r($parametros);die(); 		
 		switch ($parametros['ddl_tipo_antena']) {
 			case 'TCPIP':
 			// $command = "C:\\Users\\lenovo\\Downloads\\SESProLElibEPCcmd\\bin\\Debug\\net8.0\\SESProLElibEPCcmd.dll 2 186.4.219.172 10001";
 			$command =  $this->patch_ddl." 1 ".$parametros['txt_ip']." ".$parametros['txt_puerto']."";
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
	 	
 	}

 	function guardar_config($parametros)
 	{		
 		$portal = $this->modelo->listar($parametros['controladora']);
 		// print_r($parametros);die();
 		if(count($portal)>0)
 		{
	 		switch ($portal[0]['comunicacion']) {
	 			case 'TCPIP':
	 			// $command = "C:\\Users\\lenovo\\Downloads\\SESProLElibEPCcmd\\bin\\Debug\\net8.0\\SESProLElibEPCcmd.dll 2 186.4.219.172 10001";
	 			$command =  $this->patch_ddl." 3 ".$portal[0]['ip']." ".$portal[0]['puerto']." ".$parametros['lista']." ".$parametros['cbx']." ".$parametros['value']." ".$parametros['adr'];
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