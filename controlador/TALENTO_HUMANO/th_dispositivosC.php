<?php
date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 2) . '/modelo/TALENTO_HUMANO/th_dispositivosM.php');

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
    echo json_encode($controlador->importar_datos($_POST['parametros']));
}



class th_dispositivosC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new th_dispositivosM();
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
            array('campo' => 'th_dis_estado', 'dato' => 1)
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
        $dispositivo = $this->modelo->where('th_dis_id', $parametros['dispositivos'])->listar();
        // print_r($dispositivo);die()
         $carpeta = dirname(__DIR__,2).'/TEMP/data/data_importada_'.str_replace(" ",'_',$dispositivo[0]['nombre']);
         if(!file_exists($carpeta))
         {

            $carpeta1 = dirname(__DIR__,2).'/TEMP/data';
            mkdir($carpeta1,0777);
            mkdir($carpeta,0777);
         }
        $cliente = $this->conectar_buscar($parametros);
        if(count($cliente)>0)
        {
            $contenido = "";
            foreach ($cliente as $key => $value) {

                $contenido .= "Nombre: {$value['nombre']}, Numero de Tarjeta: {$value['CardNo']}\n";

                if($parametros['huellas']==1)
                {

                }
                if($parametros['facial']==1)
                {

                }
            }

            file_put_contents($carpeta."/usuarios.txt", $contenido);
        }else
        {
            return -1;
        }

    }

    function conectar_buscar($parametros)
    {
        $datos = $this->modelo->where('th_dis_id', $parametros['dispositivos'])->listar();

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
}
