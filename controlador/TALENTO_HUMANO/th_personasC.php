<?php
date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 2) . '/modelo/TALENTO_HUMANO/th_personasM.php');
require_once(dirname(__DIR__, 2) . '/modelo/TALENTO_HUMANO/th_dispositivosM.php');
require_once(dirname(__DIR__, 2) . '/modelo/TALENTO_HUMANO/th_cardM.php');
require_once(dirname(__DIR__, 2) . '/modelo/TALENTO_HUMANO/th_fingerM.php');
require_once(dirname(__DIR__, 2) . '/modelo/TALENTO_HUMANO/th_faceM.php');
require_once(dirname(__DIR__, 2) . '/modelo/empresaM.php');
require_once(dirname(__DIR__, 2) . '/db/codigos_globales.php');

$controlador = new th_personasC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar($_POST['id'] ?? ''));
}

if (isset($_GET['listar_personas_rol'])) {
    echo json_encode($controlador->listar_personas_rol());
}

if (isset($_GET['insertar'])) {
    echo json_encode($controlador->insertar_editar($_POST['parametros']));
}

if (isset($_GET['eliminar'])) {
    echo json_encode($controlador->eliminar($_POST['id']));
}

if (isset($_GET['buscar'])) {
    $query = '';

    if (isset($_GET['q'])) {
        $query = $_GET['q'];
    }

    $parametros = array(
        'query' => $query,
    );

    echo json_encode($controlador->buscar($parametros));
}
if (isset($_GET['conectar_buscar'])) {
    echo json_encode($controlador->conectar_buscar($_POST['parametros']));
}

if (isset($_GET['guardarImport'])) {
    echo json_encode($controlador->guardarImport($_POST['parametros']));
}
// if (isset($_GET['registros_biometria'])) {
//     echo json_encode($controlador->registros_biometria($_POST['parametros']));
// }
if (isset($_GET['eliminarFing'])) {
    echo json_encode($controlador->eliminarFing($_POST['id']));
}
if (isset($_GET['syncronizarPersona'])) {
    echo json_encode($controlador->syncronizarPersona($_POST['parametros']));
}

if (isset($_GET['listaTarjetas'])) {
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->listaTarjetas($parametros));
}

if (isset($_GET['generar_CardNo'])) {
    echo json_encode($controlador->generar_CardNo());
}

if (isset($_GET['addTarjetaBio'])) {
    echo json_encode($controlador->addTarjetaBio($_POST['parametros']));
}
if (isset($_GET['DeleteTarjetaBio'])) {
    echo json_encode($controlador->DeleteTarjetaBio($_POST['parametros']));
}

//FINGER
if (isset($_GET['listaHuellas'])) {
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->listaHuellas($parametros));
}
if (isset($_GET['CapturarFinger'])) {
    echo json_encode($controlador->CapturarFinger($_POST['parametros']));
}
if (isset($_GET['addHuellaBio'])) {
    $huella = $_FILES;
    $parametros = $_POST;
    echo json_encode($controlador->addHuellaBio($parametros,$huella));
}
if (isset($_GET['deteleHuella'])) {
    echo json_encode($controlador->deteleHuella($_POST['parametros']));
}
class th_personasC
{
    private $modelo;
    private $cod_globales;
    private $dispositivos;
    private $card;
    private $finger;
    private $face;
    private $sdk_patch;
    private $empresa;

    function __construct()
    {
        $this->modelo = new th_personasM();
        $this->dispositivos = new th_dispositivosM();

        $this->card = new th_cardM();
        $this->finger = new th_fingerM();
        $this->face = new th_faceM();

        $this->cod_globales = new codigos_globales();
        $this->sdk_patch = dirname(__DIR__, 2) . '/lib/SDKDevices/hikvision/bin/Debug/net8.0/CorsinfSDKHik.dll ';
        $this->empresa = new empresaM();
    }

    function listar($id = '')
    {
        if ($id == '') {
            $datos = $this->modelo->where('th_per_estado', 1)->listar();
        } else {
            $datos = $this->modelo->where('th_per_id', $id)->listar();
            $datosB = $this->biometria->where('th_per_id', $id)->listar();
            $datos[0]['biometria'] = array();
            if (count($datosB) > 0) {
                $datos[0]['biometria'] = $datosB[0];
            }
        }
        return $datos;
    }

    function listar_personas_rol()
    {
        $id = $_SESSION['INICIO']['NO_CONCURENTE'];
        if($id != null){
            $datos = $this->modelo->where('th_per_id', $id)->listar();
        }else{
            return null;
        }

        return $datos;
    }

    //Para usar en select2
    function buscar($parametros)
    {
        $lista = array();
        $concat = "th_per_cedula, th_per_primer_apellido, th_per_segundo_apellido, th_per_primer_nombre, th_per_segundo_nombre";
        $datos = $this->modelo->where('th_per_estado', 1)->like($concat, $parametros['query']);

        //print_r($datos); exit();die();

        foreach ($datos as $key => $value) {
            $text = $value['th_per_cedula'] . ' - ' . $value['th_per_primer_apellido'] . ' ' . $value['th_per_segundo_apellido'] . ' ' . $value['th_per_primer_nombre'] . ' ' . $value['th_per_segundo_nombre'];
            $lista[] = array('id' => ($value['th_per_id']), 'text' => ($text), /* 'data' => $value */);
        }

        return $lista;
    }

    function conectar_buscar($parametros)
    {
        $datos = $this->dispositivos->where('th_dis_id', $parametros['id'])->listar();

        if (count($datos) > 0) {
            $dllPath = $this->sdk_patch . '5 ' . $datos[0]['host'] . ' ' . $datos[0]['usuario'] . ' ' . $datos[0]['port'] . ' ' . $datos[0]['pass'] . ' ';
            // Comando para ejecutar la DLL
            $command = "dotnet $dllPath";

            // print_r($command);die();
            $output = shell_exec($command);
            $resp = json_decode($output, true);
            $cadena = $resp['msj'];
            $cadena = preg_replace('/[^\w:{}\s,]/u', '', $cadena);
            $cadena = str_replace(["CardNo", "nombre", "{"], ['"CardNo"', '"nombre"', '{'], $cadena);
            $cadena = '[' . str_replace(['":', '}', ',"'], ['":"', '"}', '","'], $cadena) . ']';
            // print_r($cadena);die();
            $datos = json_decode($cadena, true);
            $lista = array();
            // print_r($datos);
            if(count($datos)>0)
            {
                foreach ($datos as $key => $value) {
                    $nombres = explode(" ",$value['nombre']);
                    if(count($nombres)<4)
                    {
                        for ($i=count($nombres); $i <4; $i++) { 
                            $nombres[$i] = '';
                        }
                    }
                    $lista[] = array("CardNo"=>$value['CardNo'],"nombre"=>$nombres);
                }
            }

            // print_r($lista);die();

            // print_r($datos[0]['nombre']);die();

            return $lista;
        } else {
            return -1;
        }
    }

    function guardarImport($parametros)
    {
        $msj = '';
        $datos = $parametros['datos'];
        $datos = json_decode($datos, true);

        foreach ($datos as $key => $value) {
            $per = explode(' ', $value['nombre']);
            $where = '';
            if (isset($per[0])) {
                $where .= "th_per_primer_nombre";
            }
            if (isset($per[2])) {
                $where .= "+' '+th_per_segundo_nombre";
            }
            if (isset($per[1])) {
                $where .= "+' '+th_per_primer_apellido";
            }
            if (isset($per[3])) {
                $where .= "+' '+th_per_segundo_apellido";
            }
            $this->modelo->reset();
            $datos = $this->modelo->where($where, $value['nombre'])->listar();

            // print_r($datos);die();
            if (count($datos) == 0) {
                $valor = array(
                    array('campo' =>  'th_per_fecha_modificacion', 'dato' => date('Y-m-d H:i:s')),
                );

                if (isset($per[0])) {
                    $campo = array('campo' =>  'th_per_primer_nombre', 'dato' => $per[0]);
                    array_push($valor, $campo);
                }
                if (isset($per[2])) {
                    $campo = array('campo' => 'th_per_segundo_nombre', 'dato' => $per[2]);
                    array_push($valor, $campo);
                }
                if (isset($per[1])) {
                    $campo = array('campo' => 'th_per_primer_apellido', 'dato' => $per[1]);
                    array_push($valor, $campo);
                }
                if (isset($per[3])) {
                    $campo = array('campo' => 'th_per_segundo_apellido', 'dato' => $per[3]);
                    array_push($valor, $campo);
                }
                // print_r($valor);die();               
                $datos = $this->modelo->insertar($valor);

                $reg = $this->modelo->where($where, $value['nombre'])->listar();

                // print_r($reg);die();
                $biom = array(
                    array('campo' => 'th_per_id', 'dato' => $reg[0]['_id']),
                    array('campo' => 'th_bio_card', 'dato' => $value['CardNo']),
                    array('campo' => 'th_bio_nombre', 'dato' => "tarjeta"),
                );

                $datos = $this->biometria->insertar($biom);
            } else {
                $msj .= 'El registro ' . $value['nombre'] . ' ya esta registrado<br>';
            }
        }

        if ($msj != '') {
            $msj = '<div style="text-align: left;">' . $msj . '</div>';
        }

        return array('resp' => 1, 'msj' => $msj);
    }

    // function registros_biometria($parametros)
    // {
    //     $datos = $this->biometria->where('th_per_id', $parametros['id'])->listar();
    //     $detalle = array();
    //     foreach ($datos as $key => $value) {
    //         $detalle[] = array('id' => $value['_id'], 'detalle' => $value['th_bio_nombre']);
    //     }

    //     return $detalle;
    //     // print_r($datos);die();        
    // }

    function eliminarFing($id)
    {
        $where[0]['campo'] = 'th_bio_id';
        $where[0]['dato'] = $id;
        return $this->biometria->eliminar($where);
    }

    function syncronizarPersona($parametros)
    {
        // print_r($parametros);die();

        $datos = $this->listar($parametros['id']);

        $datosBio = array(
            array('campo' => 'th_bio_card', 'dato' => $parametros['card']),
            array('campo' => 'th_per_id', 'dato' => $parametros['id']),
        );



        if (count($datos[0]['biometria']) == 0 && $parametros['card'] != '') {
            $datos = $this->biometria->insertar($datosBio);
        } else if (count($datos[0]['biometria']) > 0 && $parametros['card'] != '') {

            $where[0]['campo'] = 'th_per_id';
            $where[0]['dato'] = $parametros['id'];
            $datos = $this->biometria->editar($datosBio, $where);
        }
        $dispositivo = $this->dispositivos->where('th_dis_id', $parametros['device'])->listar();
        $datos = $this->listar($parametros['id']);


        $dllPath = $this->sdk_patch . '4 ' . $dispositivo[0]['host'] . ' ' . $dispositivo[0]['usuario'] . ' ' . $dispositivo[0]['port'] . ' ' . $dispositivo[0]['pass'] . ' "' . $datos[0]['primer_apellido'] . ' ' . $datos[0]['segundo_apellido'] . ' ' . $datos[0]['primer_nombre'] . ' ' . $datos[0]['segundo_nombre'] . '" ' . $parametros['id'] . ' ' . $parametros['card'] . ' ' . $datos[0]['biometria']['th_bio_patch'];
        // Comando para ejecutar la DLL
        $command = "dotnet $dllPath";

        // print_r($command);die();
        $output = shell_exec($command);
        $resp = json_decode($output, true);
        return $resp;
    }


    function generar_CardNo()
    {
        $data = $this->cod_globales->secuenciale_globales("CardNo",$_SESSION['INICIO']['ID_EMPRESA'],true);
        return $data;
    }

    function listaTarjetas($parametros)
    {

        $tarjetas = $this->card->where('th_per_id', $parametros['id'])->listar();
        return $tarjetas;
    }

    function addTarjetaBio($parametros)
    {

        $persona = $this->modelo->where('th_per_id', $parametros['idPerson'])->listar();
        $nombre =  $persona[0]['primer_apellido']." ".$persona[0]['segundo_apellido']." ".$persona[0]['primer_nombre'] ." ".$persona[0]['segundo_nombre'];
        // $nombre = "Kim Kim Wai Lam Shawn1";
        $CardNom = $parametros['CardNo'];
        $EmployedId = $parametros['idPerson'];

        $dispositivo = $this->dispositivos->where('th_dis_id', $parametros['device'])->listar();
        $dllPath = $this->sdk_patch . '7 ' . $dispositivo[0]['host'] . ' ' . $dispositivo[0]['usuario'] . ' ' . $dispositivo[0]['port'] . ' ' . $dispositivo[0]['pass'] . ' "' . $nombre . '" ' . $EmployedId . ' ' . $CardNom;
        // Comando para ejecutar la DLL
        $command = "dotnet $dllPath";

        // print_r($command);die();

        $output = shell_exec($command);
        $resp = json_decode($output, true);
        if($resp['resp']==1)
        {
            $datosBio = array(
                array('campo' => 'th_cardNo', 'dato' => $CardNom),
                array('campo' => 'th_per_id', 'dato' => $parametros['idPerson']),
                array('campo' => 'th_card_creacion', 'dato' => date('Y-m-d')),
                array('campo' => 'th_card_nombre', 'dato' =>$nombre),
            );
            $datos = $this->card->insertar($datosBio);
        }
        return $resp;
    }

    function DeleteTarjetaBio($parametros)
    {
        $datos = $this->modelo->where('th_per_id', $parametros['idPerson'])->listar();
        $CardNom = $parametros['CardNo'];

        $dispositivo = $this->dispositivos->where('th_dis_id', $parametros['device'])->listar();
        $dllPath = $this->sdk_patch . '8 ' . $dispositivo[0]['host'] . ' ' . $dispositivo[0]['usuario'] . ' ' . $dispositivo[0]['port'] . ' ' . $dispositivo[0]['pass'] ." ". $CardNom;
        // Comando para ejecutar la DLL
        $command = "dotnet $dllPath";

        $output = shell_exec($command);
        $resp = json_decode($output, true);
        if($resp['resp']==1)
        {
            $tarjeta = $this->card->where('th_per_id', $parametros['idPerson'])->where('th_cardNo',$CardNom)->listar();
            $datosBio = array(
                array('campo' => 'th_card_id', 'dato' => $tarjeta[0]['_id']),
            );
            $this->card->eliminar($datosBio);
        }
        return $resp;
    }

    function listaHuellas($parametros)
    {

        $finger = $this->finger->where('th_per_id', $parametros['id'])->listar();
        return $finger;
    }

    function CapturarFinger($parametros)
    {
        // print_r($parametros);die();

        $empresa = $this->empresa->datos_empresa($_SESSION['INICIO']['ID_EMPRESA']);
        $patch = $empresa[0]['ruta_huellas'];
        if(!file_exists($patch))
        {
            mkdir($patch, 0777, true);
        }
        $dispositivo = $this->dispositivos->where('th_dis_id', $parametros['iddispostivos'])->listar();
        $dispositivo = $dispositivo[0];
        $persona = $this->modelo->where('th_per_id', $parametros['idPerson'])->listar();
        $nombre = str_replace(" ","_",$persona[0]['primer_apellido']." ".$persona[0]['segundo_apellido']." ".$persona[0]['primer_nombre'] ." ".$persona[0]['segundo_nombre']);


        $cards = $this->card->where("th_per_id",$parametros['idPerson'])->listar();
        if(count($cards)==0){return -2;} //si no tiene tarjetas registradas no puede seguir avanzando


        $dllPath = $this->sdk_patch.'3 '.$dispositivo['host'].' '.$dispositivo['usuario'].' '.$dispositivo['port'].' '.$dispositivo['pass'].' '.$nombre.'CapFinger'.$parametros['dedo'].' '.$patch;
        $command = "dotnet $dllPath";

        // print_r($command);die();
        $output = shell_exec($command);
        $msj = json_decode($output,true);
        if(file_exists($patch.'\\'.$nombre.'CapFinger'.$parametros['dedo'].'.dat'))
        {
            $resp = 1;
        //     $reg = $this->modelo_biometria->where('th_per_id',$parametros['Idusuario'])->listar();
        //     $biom = array(
        //             array('campo' => 'th_per_id', 'dato' =>$parametros['Idusuario']),
        //             array('campo' => 'th_bio_patch', 'dato' => $patch.'\\'.$nombre.'CapFinger'.$parametros['dedo'].'.dat'),
        //             array('campo' => 'th_bio_nombre', 'dato' => "Huella dactilar ".$parametros['dedo']),
        //             array('campo' => 'th_bio_card', 'dato' => $parametros['CardNo'])
        //         );
        //     if(count($reg)==0)
        //     {               
        //         $this->modelo_biometria->insertar($biom);
        //     }else
        //     {
        //         $where = array(
        //             array('campo' => 'th_bio_id', 'dato' =>$reg[0]['_id'])
        //         );
        //         $this->modelo_biometria->editar($biom, $where);
        //     }

        }else
        {
            $resp = -1;
        }

        return array('resp'=>$resp,'msj'=>$msj['msj'],'patch'=>$patch.'\\'.$nombre.'CapFinger'.$parametros['dedo'].'.dat');
        // print_r($resp);die();
    }

    function addHuellaBio($parametros,$file)
    {
        $patch = $parametros['detectado'];
        $nombreTemp = explode("\\", $patch);
        $nombre = $nombreTemp[(count($nombreTemp)-1)]; 
        $empresa = $this->empresa->datos_empresa($_SESSION['INICIO']['ID_EMPRESA']);
        if($parametros['detectado']=="")
        {
            $patch = $empresa[0]['ruta_huellas'].'\\'.$file['huella']['name'];
            $nombre = $file['huella']['name'];
        }
       
        $dispositivo = $this->dispositivos->where('th_dis_id', $parametros['iddispostivos'])->listar();
        $dispositivo = $dispositivo[0];
        $persona = $this->modelo->where('th_per_id', $parametros['idPerson'])->listar();
        $cards = $this->card->where("th_per_id",$parametros['idPerson'])->listar();



        $resp = array("msj"=>"SetFingegDataSuccessful","resp"=>1);
        if (count($cards)>0) {

            $dllPath = $this->sdk_patch.'4 '.$dispositivo['host'].' '.$dispositivo['usuario'].' '.$dispositivo['port'].' '.$dispositivo['pass'].' '.$cards[0]['th_cardNo'].' '.$patch.' '.$parametros['NumFinger'];
            $command = "dotnet $dllPath";


            print_r($command);die();
             $output = shell_exec($command);
            $msj = json_decode($output,true);
            if($msj['resp']!=1)
            {
                $resp = $msj;
            }else
            {
                //guarda en base de datos
                $datosBio = array(
                    array('campo' => 'th_cardNo', 'dato' => $cards[0]['th_cardNo']),
                    array('campo' => 'th_per_id', 'dato' => $parametros['idPerson']),
                    array('campo' => 'th_finger_creacion', 'dato' => date('Y-m-d')),
                    array('campo' => 'th_finger_numero', 'dato' => $parametros['NumFinger']),
                    array('campo' => 'th_finger_patch', 'dato' => $patch),
                    array('campo' => 'th_finger_nombre', 'dato' =>$nombre),
                );
                $datos = $this->finger->insertar($datosBio);

            }
        }

        return $resp;        
    }

    function deteleHuella($parametros)
    {
        // print_r($parametros);die();
        // die();
        $resp = 1;
        $datos = $this->modelo->where('th_per_id', $parametros['idPerson'])->listar();
        $CardNom = $parametros['CardNo'];
        $dispositivo = $this->dispositivos->where('th_dis_id', $parametros['device'])->listar();
        $dllPath = $this->sdk_patch . '9 ' . $dispositivo[0]['host'] . ' ' . $dispositivo[0]['usuario'] . ' ' . $dispositivo[0]['port'] . ' ' . $dispositivo[0]['pass'] ." ". $CardNom." 1";
        // // Comando para ejecutar la DLL
        $command = "dotnet $dllPath";

        $output = shell_exec($command);
        $resp = json_decode($output, true);
        
            print_r($resp);die();
        if($resp['resp']==1)
        {
            $itemHuellas = json_decode($resp['msj'],true);
            foreach ($itemHuellas as $key => $value) {
                if($value==$parametros['NumFinger'])
                {
                    // envia al biometrico a eliminar
                }
            }
        }
        if($resp['msj']=='')
        {
            $resp = 1;
        }
          $datosBio = array(
                array('campo' => 'th_id_finger', 'dato' => $parametros['idHuella']),
            );
        $this->finger->eliminar($datosBio);
        return $resp;
    }
}
