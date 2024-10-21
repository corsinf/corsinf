<?php
date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 2) . '/modelo/TALENTO_HUMANO/th_detectar_dispositivosM.php');

$controlador = new th_detectar_dispositivosC();

if (isset($_GET['BuscarDevice'])) {
    echo json_encode($controlador->BuscarDevice());
}
if (isset($_GET['DetectarEventos'])) {
    echo json_encode($controlador->DetectarEventos());
}
if (isset($_GET['CambiarPass'])) {
	$parametros = $_POST['parametros'];
    echo json_encode($controlador->CambiarPass($parametros));
}

/**
 * 
 */
class th_detectar_dispositivosC
{
	
	 private $modelo;

    function __construct()
    {
        $this->modelo = new th_detectar_dispositivosM();
    }

    function BuscarDevice()
    {
    	// $datos = array(array('nombre'=>'hola','host'=>'192.168.100.1','_id'=>1));


    	$dllPath = dirname(__DIR__,2).'/lib/SDKDevices/hikvision/bin/Debug/net8.0/libreriasHik.dll 1';
		// Comando para ejecutar la DLL
		$command = "dotnet $dllPath";

		// Ejecuta el comando y captura la salida
		$output = shell_exec($command);
		$resp = json_decode($output,true);

		$tr = array();
		foreach ($resp as $key => $value) {
			$device =  json_decode($value,true);
			$detalle_device =$device['ProbeMatch'];

			$tr[]=array('item'=>($key+1),'tipo'=>$detalle_device['DeviceDescription'],'Estado'=>$detalle_device['Activated'],'ipv4'=>$detalle_device['IPv4Address'],'puerto'=>$detalle_device['CommandPort'],'serie'=>$detalle_device['DeviceSN'],'MAC'=>$detalle_device['MAC'],'_id'=>$key);

			// print_r($detalle_device);die();
		}

		// Muestra la salida
		// print_r($resp);die();



    	return $tr;
    }

    function DetectarEventos()
    {
    	$dllPath = dirname(__DIR__,2).'/lib/SDKDevices/hikvision/bin/Debug/net8.0/libreriasHik.dll 2';
		// Comando para ejecutar la DLL
		$command = "dotnet $dllPath";

    	$descriptors = [
		    0 => ["pipe", "r"],
		    1 => ["pipe", "w"],
		    2 => ["pipe", "w"]
		];

		$process = proc_open($command, $descriptors, $pipes);

		if (is_resource($process)) {
		    // Mantener un bucle continuo para leer la salida del proceso en tiempo real
		    while (!feof($pipes[1])) {
		        // Leer una línea de salida del proceso C#
		        $output = fgets($pipes[1]);
		        
		        if ($output !== false) {
		            // Enviar la salida al cliente (por ejemplo, a un frontend en JavaScript)
		            echo " " . $output . "<br>";
		            ob_flush();  // Envía el contenido al navegador inmediatamente
		            flush();     // Descarga el buffer de salida
		        }
		    }

		    // Cerrar los pipes cuando se haya terminado
		    fclose($pipes[0]);
		    fclose($pipes[1]);
		    fclose($pipes[2]);
		    // Cerrar el proceso
		    proc_close($process);
		} else {
		    echo "No se pudo iniciar el proceso C#.";
		}
    }

    function CambiarPass($parametros)
    {
    	print_r($parametros);die();
    }
}

?> 