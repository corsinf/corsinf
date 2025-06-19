<?php
date_default_timezone_set('America/Guayaquil');
require_once(dirname(__DIR__, 2) . '/modelo/TALENTO_HUMANO/th_log_dispositivosM.php');
require_once(dirname(__DIR__, 2) . '/modelo/TALENTO_HUMANO/th_dispositivosM.php');

$controlador = new th_log_dispositivosC();

if (isset($_GET['Buscar_log'])) {
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->Buscar_log($parametros));
}

if (isset($_GET['Buscar_save'])) {
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->Buscar_save($parametros));
}


class th_log_dispositivosC
{
    private $modelo;
    private $dispositivos;

    function __construct()
    {
        $this->modelo = new th_log_dispositivosM();
        $this->dispositivos = new th_dispositivosM();
        $this->sdk_patch = dirname(__DIR__,2).'\\lib\\SDKDevices\\hikvision\\bin\\Debug\\net8.0\\CorsinfSDKHik.dll ';
        
    }

    function Buscar_log($parametros)
    {
        $dispositivo = $this->dispositivos->where('th_dis_id',$parametros['dispostivos'])->listar();

        $dllPath = $this->sdk_patch.'10 '.$dispositivo[0]['host'].' '.$dispositivo[0]['usuario'].' '.$dispositivo[0]['port'].' '.$dispositivo[0]['pass'].' '.$parametros['desde'].' '.$parametros['hasta'];
        // Comando para ejecutar la DLL
        $command = "dotnet $dllPath";

        // print_r($command);die();
        $output = shell_exec($command);
        $resp = json_decode($output,true);
        $cadena = $resp['msj'];
        $lista = explode(';', $cadena);
        $filtrado = array_filter($lista);
        return $filtrado;
    }

    function Buscar_save($parametros)
    {
         $dispositivo = $this->dispositivos->where('th_dis_id',$parametros['dispostivos'])->listar();

        $dllPath = $this->sdk_patch.'10 '.$dispositivo[0]['host'].' '.$dispositivo[0]['usuario'].' '.$dispositivo[0]['port'].' '.$dispositivo[0]['pass'].' '.$parametros['desde'].' '.$parametros['hasta'];
        // Comando para ejecutar la DLL
        $command = "dotnet $dllPath";

        // print_r($command);die();
        $output = shell_exec($command);
        $resp = json_decode($output,true);
        $cadena = $resp['msj'];
        $lista = explode(';', $cadena);
        $filtrado = array_filter($lista);
        foreach ($filtrado as $key => $value) {
            // print_r($value);die();
             $datos = array(
                            array('campo'=>'LOG_DEVICE','dato'=>$value),
                            array('campo'=>'estado_procesado','dato'=>0),
                        );
            $this->modelo->insertar($datos);
        }
        return $filtrado;
    }
}
