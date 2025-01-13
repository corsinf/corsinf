<?php
date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 2) . '/modelo/TALENTO_HUMANO/th_personasM.php');
require_once(dirname(__DIR__, 2) . '/modelo/TALENTO_HUMANO/th_dispositivosM.php');
require_once(dirname(__DIR__, 2) . '/modelo/TALENTO_HUMANO/th_biometriaM.php');
require_once(dirname(__DIR__, 2) . '/modelo/TALENTO_HUMANO/th_control_accesoM.php');

$controlador = new th_detectar_dispositivosC();

if (isset($_GET['BuscarDevice'])) {
    echo json_encode($controlador->BuscarDevice());
}

if (isset($_GET['ProbarConexion'])) {
	$parametros = $_POST['parametros'];
    echo json_encode($controlador->ProbarConexion($parametros));
}
if (isset($_GET['insertar'])) {
    echo json_encode($controlador->insertar($_POST['parametros']));
}

if (isset($_GET['CapturarFinger'])) {
    echo json_encode($controlador->CapturarFinger($_POST['parametros']));
}

if (isset($_GET['DetectarEventos'])) {
    echo json_encode($controlador->DetectarEventos($_POST['parametros']));
}
if (isset($_GET['DetenerEventos'])) {
    echo json_encode($controlador->DetenerEventos($_POST['parametros']));
}

/**
 * 
 */
class th_detectar_dispositivosC
{
	
	 private $modelo;

    function __construct()
    {
        // $this->modelo = new th_detectar_dispositivosM();
        $this->sdk_patch = dirname(__DIR__,2).'/lib/SDKDevices/hikvision/bin/Debug/net8.0/CorsinfSDKHik.dll ';
        $this->modelo_dispositivos = new th_dispositivosM();
        $this->modelo_personas = new th_personasM();
        $this->modelo_biometria = new th_biometriaM();
        $this->control_acceso = new th_control_accesoM();
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


    function DetectarEventos($parametros)
    {
    	// print_r($_SESSION['INICIO']);die();
    	set_time_limit(0);
		$dispositivo = $this->modelo_dispositivos->where('th_dis_id',$parametros['dispostivos'])->listar();	
		$dllPath = $this->sdk_patch . '6 ' . $dispositivo[0]['host'] . ' ' . $dispositivo[0]['usuario'] . ' ' . $dispositivo[0]['port'] . ' ' . $dispositivo[0]['pass'] . ' '.$_SESSION['INICIO']['IP_HOST']. ' '.$_SESSION['INICIO']['PUERTO_DB']. ' '.$_SESSION['INICIO']['BASEDATO']. ' '.$_SESSION['INICIO']['USUARIO_DB']. ' '.$_SESSION['INICIO']['PASSWORD_DB'];
		$command = "dotnet $dllPath"; // Comando básico para dotnet

		// print_r($command);die();
		// Crear archivo de salida
		$outputFile = dirname(__DIR__,2).'/Cron/output_file.log';
		$outputFile2 = dirname(__DIR__,2).'/Cron/output_file_error.log';
		pclose(popen("start /B $command ", "r"));
		

    }

   

    function DetectarEventos3($parametros)
    {
    	set_time_limit(0);
    	$dispositivo = $this->modelo_dispositivos->where('th_dis_id',$parametros['dispostivos'])->listar();
    	$dllPath = $this->sdk_patch.'6 '.$dispositivo[0]['host'].' '.$dispositivo[0]['usuario'].' '.$dispositivo[0]['port'].' '.$dispositivo[0]['pass'].' ';
		// Comando para ejecutar la DLL
		$command = "dotnet $dllPath";

		// print_r($command);die();
    	$descriptors = [
		    0 => ["pipe", "r"],
		    1 => ["pipe", "w"],
		    2 => ["pipe", "w"]
		];

		$process = proc_open($command, $descriptors, $pipes);
 		$status = proc_get_status($process);
    	$pid = $status['pid'];

		if (is_resource($process)) {
		    // Mantener un bucle continuo para leer la salida del proceso en tiempo real
		    while (!feof($pipes[1])) {
		        // Leer una línea de salida del proceso C#
		        $output = fgets($pipes[1]);
		        if($output=='')
		        {
		        	// print_r($output);
		        	return -1;
		        }
		        // print_r($output);die();
		        if ($output !== false) {
		            // Enviar la salida al cliente (por ejemplo, a un frontend en JavaScript)
		            // return  $output ;

		            $resp = json_decode($output,true);
		            if($resp!='')
		            {
		            	foreach ($resp as $key => $value) 
		            	{		            	
				            $idDis = '';
				            $idPer = '';
				            $dispo = $this->modelo_dispositivos->where('th_dis_host',$value['ip'])->listar();
				            if(count($dispo)>0) { $idDis = $dispo[0]['_id']; }
				            	if(isset($value['Card Number']))
					            {
					            	$entrada = 1;
					            	// print_r($value);die();
					            	$per = $this->modelo_biometria->where('th_bio_card',$value['Card Number'])->listar();
					            	if(count($per)>0)
					            	{
					            		$idPer = $per[0]['th_per_id'];
					            	}

					            	$control_acceso = $this->control_acceso->where('th_per_id',$idPer,'CONVERT(date, th_acc_fecha_hora)',date('Y-m-d'))->listar();
					            	if(count($control_acceso)>0 && (count($control_acceso)+1)%2==0)
					            	{
					            		$entrada = 0;
					            	}
					            	$Hora = explode(" ", $value['fecha']);
						            $datos = array(
						            	array('campo'=>'th_dis_id','dato'=>$idDis),
						            	array('campo'=>'th_per_id','dato'=>$idPer),
						            	array('campo'=>'th_acc_tipo_registro','dato'=>$entrada),
						            	array('campo'=>'th_acc_fecha_hora','dato'=>$value['fecha']),
						            	array('campo'=>'th_acc_hora','dato'=>$Hora[1]),
						            );
						            $this->control_acceso->insertar($datos);

					            }
				           
			        	}
		             }

		            ob_flush();  // Envía el contenido al navegador inmediatamente
		            flush();     // Descarga el buffer de salida
		        }else
		        {
		        	break;
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


    function CapturarFinger($parametros)
    {
    	// print_r($parametros);die();
    	$patch = "C:\\huella";
    	if(!file_exists($patch))
    	{
    		mkdir($patch, 0777, true);
    	}
    	$nombre = str_replace(' ','', $parametros['usuario']);
    	$dispositivo = $this->modelo_dispositivos->where('th_dis_id', $parametros['iddispostivos'])->listar();
    	$dispositivo = $dispositivo[0];
    	$usuarios = $this->modelo_personas->where('th_per_id', $parametros['Idusuario'])->listar();


    	$dllPath = $this->sdk_patch.'3 '.$dispositivo['host'].' '.$dispositivo['usuario'].' '.$dispositivo['port'].' '.$dispositivo['pass'].' '.$nombre.'CapFinger'.$parametros['dedo'].' '.$patch;
		$command = "dotnet $dllPath";

		// print_r($command);die();
		$output = shell_exec($command);
		$msj = json_decode($output,true);
		if(file_exists($patch.'\\'.$nombre.'CapFinger'.$parametros['dedo'].'.dat'))
		{
			$resp = 1;
		 	$reg = $this->modelo_biometria->where('th_per_id',$parametros['Idusuario'])->listar();
		 	$biom = array(
	            	array('campo' => 'th_per_id', 'dato' =>$parametros['Idusuario']),
	            	array('campo' => 'th_bio_patch', 'dato' => $patch.'\\'.$nombre.'CapFinger'.$parametros['dedo'].'.dat'),
	            	array('campo' => 'th_bio_nombre', 'dato' => "Huella dactilar ".$parametros['dedo']),
	            	array('campo' => 'th_bio_card', 'dato' => $parametros['CardNo'])
	        	);
		 	if(count($reg)==0)
		 	{				
	        	$this->modelo_biometria->insertar($biom);
	    	}else
	    	{
	    		$where = array(
	            	array('campo' => 'th_bio_id', 'dato' =>$reg[0]['_id'])
	        	);
	        	$this->modelo_biometria->editar($biom, $where);
	    	}

		}else
		{
			$resp = -1;
		}

		return array('resp'=>$resp,'msj'=>$msj['msj'],'patch'=>$patch.'\\'.$nombre.'CapFinger'.$parametros['dedo'].'.dat');
    	// print_r($resp);die();
    }


    function DetenerEventos()
    {
		// Comando para eliminar el proceso por PID

		print_r(PID);die();
		$command = "taskkill /IM dotnet.exe /F";

		// Ejecutar el comando
		exec($command, $output, $return_var);

		// Verificar si el comando fue exitoso
		if ($return_var === 0) {
		    echo "Proceso con PID $pid eliminado correctamente.";
		} else {
		    echo "Hubo un error al intentar eliminar el proceso con PID $pid.";
		}
    }    
}

?> 