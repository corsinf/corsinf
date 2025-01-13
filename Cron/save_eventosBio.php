<?php

date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 1) . '/modelo/TALENTO_HUMANO/th_personasM.php');
require_once(dirname(__DIR__, 1) . '/modelo/TALENTO_HUMANO/th_dispositivosM.php');
require_once(dirname(__DIR__, 1) . '/modelo/TALENTO_HUMANO/th_biometriaM.php');
require_once(dirname(__DIR__, 1) . '/modelo/TALENTO_HUMANO/th_control_accesoM.php');


$modelo_dispositivos = new th_dispositivosM();
$modelo_personas = new th_personasM();
$modelo_biometria = new th_biometriaM();
$control_acceso = new th_control_accesoM();



$outputFile = 'output_file.log';

if (file_exists($outputFile)) {
    $file = fopen($outputFile, 'r');
    
    if ($file) {
        // Recorre el archivo línea por línea
        while (($line = fgets($file)) !== false) {
            $line = trim($line);
            $data = json_decode($line, true);
            // print_r($data);die();
            $idDis = '';
            $idPer = '';
            $dispo = $modelo_dispositivos->where('th_dis_host',$data[0]['ip'])->listar();
            if(count($dispo)>0) { $idDis = $dispo[0]['_id']; }
                if(isset($data[0]['Card Number']))
                {
                    $entrada = 1;
                    // print_r($value);die();
                    $per = $modelo_biometria->where('th_bio_card',$data[0]['Card Number'])->listar();
                    if(count($per)>0)
                    {
                        $idPer = $per[0]['th_per_id'];
                    }

                    $control_acceso_data = $control_acceso->where('th_per_id',$idPer,'CONVERT(date, th_acc_fecha_hora)',date('Y-m-d'))->listar();
                    if(count($control_acceso_data)>0 && (count($control_acceso_data)+1)%2==0)
                    {
                        $entrada = 0;
                    }
                    $Hora = explode(" ", $data[0]['fecha']);
                    $datos = array(
                        array('campo'=>'th_dis_id','dato'=>$idDis),
                        array('campo'=>'th_per_id','dato'=>$idPer),
                        array('campo'=>'th_acc_tipo_registro','dato'=>$entrada),
                        array('campo'=>'th_acc_fecha_hora','dato'=>$data[0]['fecha']),
                        array('campo'=>'th_acc_hora','dato'=>$Hora[1]),
                    );
                    // print_r($datos);die();
                    $control_acceso->insertar($datos);

                }
            }   
             // unlink($outputFile);                        
        }
    }




/*
        $sdk_patch = dirname(__DIR__,2).'/lib/SDKDevices/hikvision/bin/Debug/net8.0/CorsinfSDKHik.dll ';
        $modelo_dispositivos = new th_dispositivosM();
        $modelo_personas = new th_personasM();
        $modelo_biometria = new th_biometriaM();
        $control_acceso = new th_control_accesoM();

        set_time_limit(0);
        $dispositivo = $modelo_dispositivos->where('th_dis_id',14)->listar();
        $dllPath = $sdk_patch.'6 '.$dispositivo[0]['host'].' '.$dispositivo[0]['usuario'].' '.$dispositivo[0]['port'].' '.$dispositivo[0]['pass'].' ';
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
                            $dispo = $modelo_dispositivos->where('th_dis_host',$value['ip'])->listar();
                            if(count($dispo)>0) { $idDis = $dispo[0]['_id']; }
                                if(isset($value['Card Number']))
                                {
                                    $entrada = 1;
                                    // print_r($value);die();
                                    $per = $modelo_biometria->where('th_bio_card',$value['Card Number'])->listar();
                                    if(count($per)>0)
                                    {
                                        $idPer = $per[0]['th_per_id'];
                                    }

                                    $control_acceso = $control_acceso->where('th_per_id',$idPer,'CONVERT(date, th_acc_fecha_hora)',date('Y-m-d'))->listar();
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
                                    $control_acceso->insertar($datos);

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
*/