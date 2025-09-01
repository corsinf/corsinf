<?php
date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 2) . '/modelo/TALENTO_HUMANO/th_dispositivosM.php');
require_once(dirname(__DIR__, 2) . '/db/codigos_globales.php');

$controlador = new th_dispositivosC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar($_POST['id'] ?? ''));
}

if (isset($_GET['listarAll'])) {
    echo json_encode($controlador->listarAll());
}

if (isset($_GET['insertar'])) {
    echo json_encode($controlador->insertar_editar($_POST['parametros']));
}

if (isset($_GET['eliminar'])) {
    echo json_encode($controlador->eliminar($_POST['id']));
}

if (isset($_GET['eliminar2'])) {
    echo json_encode($controlador->eliminar_fisico($_POST['id']));
}


if (isset($_GET['importar_datos'])) {
    $controlador->importar_datos($_POST['parametros']);
    exit;
}

if (isset($_GET['descargar_zip'])) {
    echo json_encode($controlador->descargar_zip($_POST['parametros']));
}

if (isset($_GET['eliminar_carpeta'])) {
    echo json_encode($controlador->eliminar_carpeta($_POST['parametros']));
}



if ((isset($argv[0]) && basename($argv[0]) === basename(__FILE__) && isset($argv[1]) && $argv[1] === 'ejecutar_segundoPlano') ) {
    $json = $argv[2] ?? '{}';
    $json = str_replace(array(':',',','}','{'), array('":"','","','"}','{"'),$json);
    $json = str_replace(" ","", $json);

    // print_r($json);die();

    $data = json_decode($json,true);
    // print_r($data);die();
    $data = str_replace(" ","", $data);

    if (json_last_error() !== JSON_ERROR_NONE) {
        file_put_contents('log_zip.txt', "Error JSON: " . $data." ; ".$json, FILE_APPEND);
        exit(1);
    }

    // print_r($data);die();
    $controlador->importar_datos_proceso($data);

    // echo json_encode($controlador->importar_datos($_POST['parametros']));
}



class th_dispositivosC
{
    private $modelo;
    private $cod_globales;

    function __construct()
    {
        $this->modelo = new th_dispositivosM();
        $this->cod_globales = new codigos_globales();
        $this->sdk_patch = dirname(__DIR__,2).'\\lib\\SDKDevices\\hikvision\\bin\\Debug\\net8.0\\CorsinfSDKHik.dll ';
        // $this->sdk_patch = "C:\\Users\\lenovo\\source\\repos\\CorsinfSDKHik\\CorsinfSDKHik\\bin\\Debug\\net8.0\\CorsinfSDKHik.dll ";
        
    }

    function listar($id = '')
    {
        if ($id == '') {
            $datos = $this->modelo->where('th_dis_estado', 1)->listar();
        } else {
            $datos = $this->modelo->where('th_dis_id', $id)->listar();
        }
        return $datos;
    }

    function listarAll()
    {
       $datos = $this->modelo->listar();
        return $datos;
    }

    function insertar_editar($parametros)
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
            array('campo' => 'th_dis_fecha_modificacion', 'dato' => date('Y-m-d H:i:s')),
            array('campo' => 'th_dis_estado', 'dato' => 1),
            array('campo' => 'th_dis_estado_dis', 'dato' => $parametros['estado'])
        );

        if ($parametros['_id'] == '') {
            if (count($this->modelo->where('th_dis_nombre', $parametros['txt_nombre'])->listar()) == 0) {
                $datos = $this->modelo->insertar($datos);
            } else {
                return -2;
            }
        } else {
            if (count($this->modelo->where('th_dis_nombre', $parametros['txt_nombre'])->where('th_dis_id !', $parametros['_id'])->listar()) == 0) {
                $where[0]['campo'] = 'th_dis_id';
                $where[0]['dato'] = $parametros['_id'];
                $datos = $this->modelo->editar($datos, $where);
            } else {
                return -2;
            }
        }

        return $datos;
    }

    function eliminar($id)
    {
        $datos = array(
            array('campo' => 'th_dis_estado', 'dato' => 0),
        );

        $where[0]['campo'] = 'th_dis_id';
        $where[0]['dato'] = $id;

        $datos = $this->modelo->editar($datos, $where);
        return $datos;
    }

    function eliminar_fisico($id)
    {
        $where[0]['campo'] = 'th_dis_id';
        $where[0]['dato'] = $id;

        $datos = $this->modelo->eliminar($where);
        return $datos;
    }

    function importar_datos($parametros)
    {
        $parametros['ID_EMPRESA'] = $_SESSION['INICIO']['ID_EMPRESA'];
        $json_arg = json_encode($parametros);
        $escaped_json = escapeshellarg($json_arg);

        $php_path = $this->encontrar_php_exe();
        $script =__FILE__;

        $cmd = "start /B \"\" \"$php_path\" \"$script\" ejecutar_segundoPlano  $escaped_json";

        // print_r($cmd);die();

        // OPCIONAL: registrar comando para depuración
        file_put_contents(__DIR__ . '/log_cmd.txt', $cmd . PHP_EOL, FILE_APPEND);

        // Responder al navegador inmediatamente
        header('Content-Type: application/json');
        echo json_encode(['estado' => 'iniciado']);

        // Finaliza respuesta
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_write_close();
        }
        ignore_user_abort(true);
        ob_end_flush();
        flush();

        // Ejecutar proceso en segundo plano
        pclose(popen("cmd /C $cmd", "r"));
    }

    function encontrar_php_exe() {
        if($_SERVER['SERVER_NAME']=="corsinf.com")
        {
            return "C:\\php-8.1.32\\php.exe";

        }else{
            $paths = explode(PATH_SEPARATOR, getenv('PATH'));
            foreach ($paths as $path) {
                $php = $path . DIRECTORY_SEPARATOR . 'php.exe';
                if (is_file($php) && is_executable($php)) {
                    return $php;
                }
            }
        }
        return null;
    }


    function importar_datos_proceso($parametros)
    {
        set_time_limit(0);  //---> para el lado der server
        $finger = ""; $resp_finger = array();
        $face = ""; $resp_face = array();
        $userbio = "1";

        $dispositivo =  $this->modelo->lista_dispositivos($parametros['ID_EMPRESA'],$parametros['dispositivos']);
        // $dispositivo = $this->modelo->where('th_dis_id', $parametros['dispositivos'])->listar();
        // print_r($_SESSION['INICIO']);die();
        // print_r($dispositivo);die();

         $nombre_descarga = 'data_importada_'.str_replace(" ",'_',$dispositivo[0]['nombre']);
         $carpeta = dirname(__DIR__,2).'/TEMP/data/'.$nombre_descarga;
         $link_descarga = '../TEMP/data/'.$nombre_descarga.'.zip';

        $carpeta1 = dirname(__DIR__,2).'/TEMP/data';
        if(!file_exists($carpeta1))
        {
            mkdir($carpeta1,0777);
        }

        if(!file_exists($carpeta))
        {            
            mkdir($carpeta,0777);
        }
        $cliente = $this->conectar_buscar($parametros);
        if(count($cliente)>0)
        {
            $userbio  = 1;
            $contenido = "";
            foreach ($cliente as $key => $value) {

                $carpeta1 = $carpeta.'/data_cardNo_'.$value['CardNo'];
                if(!file_exists($carpeta1))
                {
                    mkdir($carpeta1,0777);
                }

                $contenido .= "Nombre: {$value['nombre']}, Numero de Tarjeta: {$value['CardNo']}\n";

                if($parametros['huellas']==1)
                {
                    $finger  = 0;
                    $dllPath = $this->sdk_patch . '15 ' . $dispositivo[0]['host'] . ' ' . $dispositivo[0]['usuario'] . ' ' . $dispositivo[0]['port'] . ' ' . $dispositivo[0]['pass'] . ' '.$value['CardNo'].' '.$carpeta1;
                    // Comando para ejecutar la DLL
                    $command = "dotnet $dllPath";

                    $output = shell_exec($command);
                    // print_r($output);
                    $resp = json_decode($output, true);
                    if(isset($resp['resp']))
                    {
                        $cadena = $resp['resp'];
                        if($cadena==1)
                        {
                            array_push($resp_finger, 1);
                        }else
                        {
                            array_push($resp_finger, 0);
                        }
                    }else
                    {
                        array_push($resp_finger, 0);
                    }
                }


                if($parametros['facial']==1)
                {

                    $face  = 0;
                    $dllPath = $this->sdk_patch . '16 ' . $dispositivo[0]['host'] . ' ' . $dispositivo[0]['usuario'] . ' ' . $dispositivo[0]['port'] . ' ' . $dispositivo[0]['pass'] . ' '.$value['CardNo'].' '.$carpeta1;
                    // Comando para ejecutar la DLL
                    $command = "dotnet $dllPath";

                    $output = shell_exec($command);
                     // print_r($output);
                    $resp = json_decode($output, true);
                    $cadena = $resp['resp'];
                    if(isset($resp['resp']))
                    {
                        $cadena = $resp['resp'];
                        if($cadena==1)
                        {
                            array_push($resp_face, 1);
                        }else
                        {
                            array_push($resp_face, 0);
                        }
                    }else
                    {
                        array_push($resp_face, 0);
                    }

                }
            }
            file_put_contents($carpeta."/usuarios.txt", $contenido);

            if (array_sum($resp_finger)==0) {
                $finger = 0;
            }else if(array_sum($resp_finger)>1 && array_sum($resp_finger) == count($resp_finger))
            {
                $finger = 1;
            }else {
                $finger = 2;
            }

             if (array_sum($resp_face)==0) {
                $face = 0;
            }else if(array_sum($resp_face)>1 && array_sum($resp_face) == count($resp_face))
            {
                $face = 1;
            }else {
                $face = 2;
            }

            $this->cod_globales->CrearzipCarpeta($carpeta,$carpeta.'.zip');
            // print_r($resp_face);
            // print_r($resp_finger);
            // die();
            return array("userbio"=>$userbio,"face"=>$face,"finger"=>$finger,'link'=>$link_descarga,'nombre'=>$nombre_descarga);
        }else
        {
            return -1;
        }

    }

    function conectar_buscar($parametros)
    {
        // $datos = $this->modelo->where('th_dis_id', $parametros['dispositivos'])->listar();
// print_r($parametros);die();
        $datos  =  $this->modelo->lista_dispositivos($parametros['ID_EMPRESA'],$parametros['dispositivos']);

        if (count($datos) > 0) {
            $dllPath = $this->sdk_patch . '5 ' . $datos[0]['host'] . ' ' . $datos[0]['usuario'] . ' ' . $datos[0]['port'] . ' ' . $datos[0]['pass'] . ' ';
            // Comando para ejecutar la DLL
            $command = "dotnet $dllPath";

            // print_r($command);die();
            $output = shell_exec($command);
            $resp = json_decode($output, true);
            $cadena = $resp['msj'];
            // print_r($cadena);die();
            $cadena = preg_replace('/[^\w:{}\s,]/u', '', $cadena);
            $cadena = str_replace(["EmployedId","CardNo", "nombre", "{"], ['"EmployedId"','"CardNo"', '"nombre"', '{'], $cadena);
            $cadena = '[' . str_replace(['":', '}', ',"'], ['":"', '"}', '","'], $cadena) . ']';
            // print_r($cadena);die();
            $datos = json_decode($cadena, true);
            $lista = array();
            
            // print_r($datos);

            
            if(count($datos)>0)
            {
                foreach ($datos as $key => $value) {
                    // $nombres = explode(" ",$value['nombre']);
                    // if(count($nombres)<4)
                    // {
                    //     for ($i=count($nombres); $i <4; $i++) { 
                    //         $nombres[$i] = '';
                    //     }
                    // }
                    $lista[] = array("CardNo"=>$value['CardNo'],"nombre"=>$value['nombre']);
                }
            }

            // print_r($lista);die();

            // print_r($datos[0]['nombre']);die();

            return $lista;
        } else {
            return -1;
        }
    }


    function descargar_zip($parametros)
    {
        $nombre = str_replace(" ","_", $parametros['nombre']);
        $link = "../TEMP/data/data_importada_".$nombre.'.zip';
        if(file_exists("../".$link))
        {
            return array("resp"=>1,'nombre'=>$nombre,'link'=>  $link);
        }else
        {
            return array("resp"=>0,'nombre'=>$nombre,'link'=>  $link);
        }
    }

    function eliminar_carpeta($parametros)
    {
        $this->eliminarCarpeta(str_replace('.zip',"","../".$parametros['carpeta']));
        if(file_exists("../".$parametros['carpeta']))
        {
             unlink("../".$parametros['carpeta']);
             // return 1;
        }
    }

    function eliminarCarpeta($ruta) 
    {
        if (!is_dir($ruta)) {
            return false;
        }

        $archivos = array_diff(scandir($ruta), ['.', '..']);
        
        foreach ($archivos as $archivo) {
            $rutaCompleta = $ruta . DIRECTORY_SEPARATOR . $archivo;
            if (is_dir($rutaCompleta)) {
                $this->eliminarCarpeta($rutaCompleta); // recursivo para subdirectorios
            } else {
                unlink($rutaCompleta); // elimina archivo
            }
        }

        return rmdir($ruta); // elimina carpeta vacía
    }
}
