<?php
date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 2) . '/modelo/TALENTO_HUMANO/th_detectar_dispositivosM.php');
require_once(dirname(__DIR__, 2) . '/modelo/TALENTO_HUMANO/th_dispositivosM.php');

$controlador = new th_detectar_dispositivosC();

if (isset($_GET['BuscarDevice'])) {
    echo json_encode($controlador->BuscarDevice());
}
if (isset($_GET['DetectarEventos'])) {
    echo json_encode($controlador->DetectarEventos());
}
if (isset($_GET['ProbarConexion'])) {
	$parametros = $_POST['parametros'];
    echo json_encode($controlador->ProbarConexion($parametros));
}
if (isset($_GET['insertar'])) {
    echo json_encode($controlador->insertar($_POST['parametros']));
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
        $this->sdk_patch = dirname(__DIR__,2).'/lib/SDKDevices/hikvision/bin/Debug/net8.0/CorsinfSDKHik.dll ';
        $this->modelo_dispositivos = new th_dispositivosM();
    }

    function BuscarDevice()
    {
    	// $datos = array(array('nombre'=>'hola','host'=>'192.168.100.1','_id'=>1));

    	$dllPath = $this->sdk_patch.'1';
		// Comando para ejecutar la DLL
		$command = "dotnet $dllPath";

		// print_r($command);die();
		$output = shell_exec($command);
		$resp = json_decode($output,true);

		// print_r($resp);die();

		$tr = array();
		foreach ($resp as $key => $value) {
			$device =  json_decode($value,true);

			if(!isset($device['Error']))
			{
				if(isset($device['ProbeMatch']))
				{
					$detalle_device =$device['ProbeMatch'];

					$tr[]=array('item'=>($key+1),'tipo'=>$detalle_device['DeviceDescription'],'Estado'=>$detalle_device['Activated'],'ipv4'=>$detalle_device['IPv4Address'],'puerto'=>$detalle_device['CommandPort'],'serie'=>$detalle_device['DeviceSN'],'MAC'=>$detalle_device['MAC'],'_id'=>$key);
				}
			}else
			{
				break;
			}

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

    function ProbarConexion($parametros)
    {
    	$dllPath = $this->sdk_patch.'2 '.$parametros['txt_host'].' '.$parametros['txt_usuario'].' '.$parametros['txt_puerto'].' '.$parametros['txt_pass'].' ';
		// Comando para ejecutar la DLL
		$command = "dotnet $dllPath";

		// print_r($command);die();
		$output = shell_exec($command);
		$resp = json_decode($output,true);

		$cadena = $resp['msj'];
		$palabra1 = "error";
		$palabra2 = "locked";
		$respuesta = 1;
		$patron = "/\b($palabra1|$palabra2)\b/";

		if (preg_match($patron, $cadena)) {
			$respuesta = -1;
		}


		$tr = array('resp'=>$respuesta,'msj'=>$cadena);
    	return $tr;
    }

    function insertar($parametros)
    {
        $datos = array(
            array('campo' => 'th_dis_nombre', 'dato' => $parametros['txt_nombre']),
            array('campo' => 'th_dis_host', 'dato' => $parametros['txt_host']),
            array('campo' => 'th_dis_port', 'dato' => $parametros['txt_puerto']),
            array('campo' => 'th_dis_ssl', 'dato' => $parametros['cbx_ssl']),
            array('campo' => 'th_dis_usuario', 'dato' => $parametros['txt_usuario']),
            array('campo' => 'th_dis_pass', 'dato' => $parametros['txt_pass']),
            array('campo' => 'th_dis_modelo', 'dato' => $parametros['ddl_modelo']),

            array('campo' => 'th_dis_serial', 'dato' => $parametros['txt_serial']),
            array('campo' => 'th_dis_fecha_modificacion', 'dato' => date('Y-m-d H:i:s'))
        );


        if ($parametros['_id'] == '') {
            if (count($this->modelo_dispositivos->where('th_dis_host', $parametros['txt_host'])->listar()) == 0) {
                $datos = $this->modelo_dispositivos->insertar($datos);
            } else {
                return -2;
            }
        } 

        return $datos;
    }
}

?> 