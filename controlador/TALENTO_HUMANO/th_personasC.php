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

if (isset($_GET['addTarjetaBioAll'])) {
    echo json_encode($controlador->addTarjetaBioAll($_POST['parametros']));
}

if (isset($_GET['addTarjetaBase'])) {
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->addTarjetaBase($parametros['idPerson'],$parametros['CardNo']));
}
if (isset($_GET['DeleteTarjetaBio'])) {
    echo json_encode($controlador->DeleteTarjetaBio($_POST['parametros']));
}
if (isset($_GET['DeleteTarjetaBase'])) {
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->DeleteTarjetaBase($parametros['idPerson'],$parametros['CardNo']));
}

//FINGER
if (isset($_GET['listaHuellas'])) {
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->listaHuellas($parametros));
}
if (isset($_GET['CapturarFinger'])) {
    echo json_encode($controlador->CapturarFinger($_POST['parametros']));
}
if (isset($_GET['capturarFace'])) {
    echo json_encode($controlador->capturarFace($_POST['parametros']));
}
if (isset($_GET['addHuellaBio'])) {
    $huella = $_FILES;
    $parametros = $_POST;
    echo json_encode($controlador->addHuellaBio($parametros,$huella));
}
if (isset($_GET['addHuellaBio2'])) {
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->addHuellaBio2($parametros));
}
if (isset($_GET['addHuellaBase'])) {
    $huella = $_FILES;
    $parametros = $_POST;
    echo json_encode($controlador->addHuellaBase($parametros['idPerson'],$parametros['CardNo'],$parametros['NumFinger'],$parametros['detectado'],$huella));
}
if (isset($_GET['deteleHuella'])) {
    echo json_encode($controlador->deteleHuella($_POST['parametros']));
}

if (isset($_GET['deteleHuellaBase'])) {
    echo json_encode($controlador->deteleHuellaBase($_POST['parametros']));
}

// FACE
if (isset($_GET['listaFace'])) {
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->listaFace($parametros));
}
if (isset($_GET['addFaceBio'])) {
    $huella = $_FILES;
    $parametros = $_POST;
    echo json_encode($controlador->addFaceBio($parametros,$huella));
}

if (isset($_GET['addFaceBio2'])) {
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->addFaceBio2($parametros));
}

if (isset($_GET['addFaceBase'])) {
    $huella = $_FILES;
    $parametros = $_POST;
    echo json_encode($controlador->addFaceBase($parametros['idPerson'],$parametros['CardNo'],$parametros['detectado'],$huella));
}

if (isset($_GET['DeleteFaceBio'])) {
    echo json_encode($controlador->DeleteFaceBio($_POST['parametros']));
}

if (isset($_GET['DeleteFaceBase'])) {
    echo json_encode($controlador->DeleteFaceBase($_POST['parametros']));
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
        $this->sdk_patch = dirname(__DIR__,2).'\\lib\\SDKDevices\\hikvision\\bin\\Debug\\net8.0\\CorsinfSDKHik.dll ';
        // $this->sdk_patch = "C:\\Users\\lenovo\\source\\repos\\CorsinfSDKHik\\CorsinfSDKHik\\bin\\Debug\\net8.0\\CorsinfSDKHik.dll ";
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

    function guardarImport($parametros)
    {
        $msj = '';
        $datos = $parametros['datos'];
        $datos = json_decode($datos, true);

        // print_r($datos);die();

        foreach ($datos as $key => $value) {
            $per = explode(' ', $value['nombre']);
            $data = count($per);
            $where = '';
            if (isset($per[0])) {
                $where .= "th_per_primer_apellido";
            }
            if (isset($per[1])) {
                $where .= "+' '+th_per_segundo_apellido";
            }
            if (isset($per[2])) {
                $where .= "+' '+th_per_primer_nombre";
            }
            if (isset($per[3])) {
                $where .= "+' '+th_per_segundo_nombre";
            }
           
            $this->modelo->reset();
            $data = $this->modelo->where($where, $value['nombre'])->listar();

            // print_r($where);

            // print_r($value['nombre']);
            // print_r($data);die();

            if (count($data) == 0) {
                $valor = array(
                    array('campo' =>  'th_per_fecha_modificacion', 'dato' => date('Y-m-d H:i:s')),
                );

                $nombre = $this->cod_globales->separarNombreCompleto($value['nombre']);

                // print_r($nombre);die();

                if (isset($nombre[2])) {
                    $campo = array('campo' =>  'th_per_primer_nombre', 'dato' => $nombre[2]);
                    array_push($valor, $campo);
                }
                if (isset($nombre[3])) {
                    $campo = array('campo' => 'th_per_segundo_nombre', 'dato' => $nombre[3]);
                    array_push($valor, $campo);
                }
                if (isset($nombre[0])) {
                    $campo = array('campo' => 'th_per_primer_apellido', 'dato' => $nombre[0]);
                    array_push($valor, $campo);
                }
                if (isset($nombre[1])) {
                    $campo = array('campo' => 'th_per_segundo_apellido', 'dato' => $nombre[1]);
                    array_push($valor, $campo);
                }
                // print_r($valor);die();               
                $datos = $this->modelo->insertar($valor);

                // print_r($where);
                // print_r($value['nombre']);die();
                $reg = $this->modelo->where($where, $value['nombre'])->listar();

                // print_r($data);die();
                $biom = array(
                    array('campo' => 'th_per_id', 'dato' => $reg[0]['_id']),
                    array('campo' => 'th_cardNo', 'dato' => $value['CardNo']),
                    array('campo' => 'th_card_nombre', 'dato' => $value['nombre']),
                    array('campo' => 'th_card_creacion', 'dato' => date('Y-m-d')),
                );

                // print_r($biom);die();
                $datos = $this->card->insertar($biom);
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


    function addTarjetaBioAll($parametros)
    {
         $tarjetas = $this->card->where('th_per_id', $parametros['idPerson'])->listar();
         $respuesta = array();
         foreach ($tarjetas as $key => $value) {
            $nombre =  $value['th_card_nombre'];
            $CardNom = $value['th_cardNo'];
            $EmployedId = $parametros['idPerson']+$value['th_card_id'];
            $resp = $this->enviarTarjetaBio($parametros['device'],$nombre,$EmployedId,$CardNom);
            array_push($respuesta, $resp);
         }
         return $respuesta;
    }

    function enviarTarjetaBio($device,$nombre,$EmployedId,$CardNom)
    {
        $dispositivo = $this->dispositivos->where('th_dis_id', $device)->listar();
        $dllPath = $this->sdk_patch . '7 ' . $dispositivo[0]['host'] . ' ' . $dispositivo[0]['usuario'] . ' ' . $dispositivo[0]['port'] . ' ' . $dispositivo[0]['pass'] . ' "' . $nombre . '" ' . $EmployedId . ' ' . $CardNom;
        // Comando para ejecutar la DLL
        $command = "dotnet $dllPath";

        // print_r($command);die();

        $output = shell_exec($command);
        $resp = json_decode($output, true);
        return $resp;
    }

    function addTarjetaBio($parametros)
    {

        $persona = $this->modelo->where('th_per_id', $parametros['idPerson'])->listar();
        $tarjetas = $this->card->where('th_per_id', $parametros['idPerson'])->where('th_cardNo', $parametros['CardNo'])->listar();
        $nombre =  $persona[0]['primer_apellido']." ".$persona[0]['segundo_apellido']." ".$persona[0]['primer_nombre'] ." ".$persona[0]['segundo_nombre'];
        // $nombre = "Kim Kim Wai Lam Shawn1";
        $CardNom = $parametros['CardNo'];
        $EmployedId = $tarjetas[0]['th_card_id'];

        $dispositivo = $this->dispositivos->where('th_dis_id', $parametros['device'])->listar();
        $dllPath = $this->sdk_patch . '7 ' . $dispositivo[0]['host'] . ' ' . $dispositivo[0]['usuario'] . ' ' . $dispositivo[0]['port'] . ' ' . $dispositivo[0]['pass'] . ' "' . $nombre . '" ' . $EmployedId . ' ' . $CardNom;
        // Comando para ejecutar la DLL
        $command = "dotnet $dllPath";

        // print_r($command);die();

        $output = shell_exec($command);
        $resp = json_decode($output, true);
        return $resp;
    }

    function addTarjetaBase($idPerson,$CardNom)
    {
        $persona = $this->modelo->where('th_per_id', $idPerson)->listar();
        $nombre =  $persona[0]['primer_apellido']." ".$persona[0]['segundo_apellido']." ".$persona[0]['primer_nombre'] ." ".$persona[0]['segundo_nombre'];
        $datosBio = array(
            array('campo' => 'th_cardNo', 'dato' => $CardNom),
            array('campo' => 'th_per_id', 'dato' => $idPerson),
            array('campo' => 'th_card_creacion', 'dato' => date('Y-m-d')),
            array('campo' => 'th_card_nombre', 'dato' =>$nombre),
        );
        $datos = $this->card->insertar($datosBio);
        return $datos;
    }

    function DeleteTarjetaBio($parametros)
    {
        // print_r($parametros);die();
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
            // $tarjeta = $this->card->where('th_per_id', $parametros['idPerson'])->where('th_cardNo',$CardNom)->listar();
            // $datosBio = array(
            //     array('campo' => 'th_card_id', 'dato' => $tarjeta[0]['_id']),
            // );
            // $this->card->eliminar($datosBio);
            $this->DeleteTarjetaBase($parametros['idPerson'],$CardNom);
        }
        return $resp;
    }

    function DeleteTarjetaBase($idPerson,$CardNom)
    {
        $datos = $this->modelo->where('th_per_id', $idPerson)->listar();
        $tarjeta = $this->card->where('th_per_id', $idPerson)->where('th_cardNo',$CardNom)->listar();
        $datosBio = array(
            array('campo' => 'th_card_id', 'dato' => $tarjeta[0]['_id']),
        );
       return  $this->card->eliminar($datosBio);
    }

    function listaHuellas($parametros)
    {

        $finger = $this->finger->where('th_per_id', $parametros['id'])->listar();
        return $finger;
    }

    function listaFace($parametros)
    {

        $finger = $this->face->where('th_per_id', $parametros['id'])->listar();
        foreach ($finger as $key => $value) {
            $imagen = '';
            if (file_exists($value['th_face_patch'])) {
                $imagenBinaria = file_get_contents($value['th_face_patch']);
                $imagenBase64 = base64_encode($imagenBinaria);
                $imagen = 'data:image/jpeg;base64,' . $imagenBase64;
            } 
            $finger[$key]['imagen'] = $imagen;
        }
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


            // print_r($command);die();
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

    function addHuellaBio2($parametros)
    {

        $huellaBase = $this->finger->where('th_id_finger',$parametros['_id'])->listar();
        $dispositivo = $this->dispositivos->where('th_dis_id', $parametros['dispositivo'])->listar();
        $dispositivo = $dispositivo[0];
        $patch = $huellaBase[0]['th_finger_patch'];
        $NumFinger = $huellaBase[0]['th_finger_numero'];
        $cards = $huellaBase[0]['th_cardNo'];
        

        $resp = array("msj"=>"SetFingegDataSuccessful","resp"=>1);
        if (file_exists($patch)) {

            $dllPath = $this->sdk_patch.'4 '.$dispositivo['host'].' '.$dispositivo['usuario'].' '.$dispositivo['port'].' '.$dispositivo['pass'].' '.$cards.' '.$patch.' '.$NumFinger;
            $command = "dotnet $dllPath";


            // print_r($command);die();
            $output = shell_exec($command);
            $msj = json_decode($output,true);
            if($msj['resp']!=1)
            {
                $resp = $msj;
            }
        }else
        {
            return  array("msj"=>"Archivo no encontrado","resp"=>-2);
        }

        return $resp;        
    }

    function addHuellaBase($idPerson,$CardNom,$NumFinger,$detectado,$file)
    {
        $huella = $this->finger->where('th_per_id',$idPerson)->where('th_cardNo',$CardNom)->where('th_finger_numero',$NumFinger)->listar();
        if(count($huella)==0)
        {
            $patch = $detectado;
            $nombreTemp = explode("\\", $patch);
            $nombre = $nombreTemp[(count($nombreTemp)-1)]; 
            $empresa = $this->empresa->datos_empresa($_SESSION['INICIO']['ID_EMPRESA']);
            if($detectado=="")
            {
                $patch = $empresa[0]['ruta_huellas'].'\\'.$file['huella']['name'];
                $nombre = $file['huella']['name'];
            }

            //guarda en base de datos
            $datosBio = array(
                array('campo' => 'th_cardNo', 'dato' => $CardNom),
                array('campo' => 'th_per_id', 'dato' => $idPerson),
                array('campo' => 'th_finger_creacion', 'dato' => date('Y-m-d')),
                array('campo' => 'th_finger_numero', 'dato' => $NumFinger),
                array('campo' => 'th_finger_patch', 'dato' => $patch),
                array('campo' => 'th_finger_nombre', 'dato' =>$nombre),
            );
            $datos = $this->finger->insertar($datosBio);
            return $datos;        
        }else
        {
            return -2;
        }
    }

    function addFaceBio($parametros,$file)
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
        // if (count($cards)>0) {

            $dllPath = $this->sdk_patch.'12 '.$dispositivo['host'].' '.$dispositivo['usuario'].' '.$dispositivo['port'].' '.$dispositivo['pass'].' '.$cards[0]['th_cardNo'].' '.$patch;
            $command = "dotnet $dllPath";


            // print_r($command);die();
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
                    array('campo' => 'th_face_creacion', 'dato' => date('Y-m-d')),
                    array('campo' => 'th_face_patch', 'dato' => $patch),
                    array('campo' => 'th_face_nombre', 'dato' =>$nombre),
                );
                $datos = $this->face->insertar($datosBio);

            }
        // }

        return $resp;        
    }

    function addFaceBio2($parametros)
    {

        $face_registro = $this->face->where('th_id_face',$parametros['_id'])->listar();
        $dispositivo = $this->dispositivos->where('th_dis_id', $parametros['dispositivo'])->listar();
        $dispositivo = $dispositivo[0];
        $persona = $this->modelo->where('th_per_id', $parametros['PersonaId'])->listar();
        $cards = $parametros['CardNo'];

        $patch = $face_registro[0]['th_face_patch'];
        $nombre = $face_registro[0]['th_face_nombre'];
        $resp = array("msj"=>"SetFingegDataSuccessful","resp"=>1);

        if(file_exists($patch))
        {

            $resp = array("msj"=>"SetFingegDataSuccessful","resp"=>1);

            $dllPath = $this->sdk_patch.'12 '.$dispositivo['host'].' '.$dispositivo['usuario'].' '.$dispositivo['port'].' '.$dispositivo['pass'].' '.$cards.' '.$patch;
            $command = "dotnet $dllPath";


            // print_r($command);die();
             $output = shell_exec($command);
            $msj = json_decode($output,true);
            if($msj['resp']!=1)
            {
                $resp = $msj;
            }
        }else
        {
            $resp = array("msj"=>"No se a encontrado la imagen","resp"=>-2);
        }       

        return $resp; 
    }

    function addFaceBase($idPerson,$CardNom,$detectado,$file)
    {
        $registroFacial = $this->face->where('th_per_id',$idPerson)->where('th_cardNo',$CardNom)->listar();
        if(count( $registroFacial)==0)
        {            
            $patch = $detectado;
            $nombreTemp = explode("\\", $patch);
            $nombre = $nombreTemp[(count($nombreTemp)-1)]; 
            $empresa = $this->empresa->datos_empresa($_SESSION['INICIO']['ID_EMPRESA']);
            if($detectado=="")
            {
                $patch = $empresa[0]['ruta_huellas'].'\\'.$file['huella']['name'];
                $nombre = $file['huella']['name'];
            }

  
            //guarda en base de datos
            $datosBio = array(
                array('campo' => 'th_cardNo', 'dato' => $CardNom),
                array('campo' => 'th_per_id', 'dato' => $idPerson),
                array('campo' => 'th_face_creacion', 'dato' => date('Y-m-d')),
                array('campo' => 'th_face_patch', 'dato' => $patch),
                array('campo' => 'th_face_nombre', 'dato' =>$nombre),
            );
            $datos = $this->face->insertar($datosBio);
            return $datos;     
        }else
        {
            return -2;
        }   
    }

    function deteleHuella($parametros)
    {
        // print_r($parametros);die();
        // die();
        $resp = -1;
        $datos = $this->modelo->where('th_per_id', $parametros['idPerson'])->listar();
        $CardNom = $parametros['CardNo'];
        $dispositivo = $this->dispositivos->where('th_dis_id', $parametros['device'])->listar();
        $fingerData = $this->finger->where('th_id_finger',$parametros['idHuella'])->listar();
        
        // print_r($fingerData);die();

        $dllPath = $this->sdk_patch . '14 ' . $dispositivo[0]['host'] . ' ' . $dispositivo[0]['usuario'] . ' ' . $dispositivo[0]['port'] . ' ' . $dispositivo[0]['pass'] ." ". $CardNom." ".$fingerData[0]['th_finger_numero'];
        // // Comando para ejecutar la DLL
        $command = "dotnet $dllPath";

        // print_r($command);die();

        $output = shell_exec($command);
        $resp = json_decode($output, true);
       // print_r($resp);
        $respHuellas = json_decode($resp['msj'],true);

        // print_r($respHuellas);die();
       
        if($respHuellas[0]['resp']=='1')
        {
            $resp = 1;
        }
          $datosBio = array(
                array('campo' => 'th_id_finger', 'dato' => $parametros['idHuella']),
            );
        $this->finger->eliminar($datosBio);
        return $resp;
    }

    function deteleHuellaBase($parametros)
    {
        $datosBio = array(
            array('campo' => 'th_id_finger', 'dato' => $parametros['_id']),
        );
        return $this->finger->eliminar($datosBio);
    }

    function capturarFace($parametros)
    {
        // print_r($parametros);die();
        $resp = -1;
        $empresa = $this->empresa->datos_empresa($_SESSION['INICIO']['ID_EMPRESA']);
        $patch = $empresa[0]['ruta_huellas'];
        if(!file_exists($patch))
        {
            mkdir($patch, 0777, true);
        }
        $dispositivo = $this->dispositivos->where('th_dis_id', $parametros['iddispostivos'])->listar();
        $dispositivo = $dispositivo[0];
        $persona = $this->modelo->where('th_per_id', $parametros['idPerson'])->listar();
        $nombre = str_replace(" ","_",$persona[0]['primer_apellido']." ".$persona[0]['segundo_apellido']." ".$persona[0]['primer_nombre'] ." ".$persona[0]['segundo_nombre']." ".date('YmdHis'));


        $cards = $this->card->where("th_per_id",$parametros['idPerson'])->listar();
        if(count($cards)==0){return -2;} //si no tiene tarjetas registradas no puede seguir avanzando


        $dllPath = $this->sdk_patch.'11 '.$dispositivo['host'].' '.$dispositivo['usuario'].' '.$dispositivo['port'].' '.$dispositivo['pass'].' '.$nombre.'_Face '.$patch;
        $command = "dotnet $dllPath";

        // print_r($command);die();
        $output = shell_exec($command);
        $msj = json_decode($output,true);
        $imagen = '';
        if(file_exists($patch.'\\'.$nombre.'_Face.jpg'))
        {
            $resp = 1;

            if (file_exists($patch.'\\'.$nombre.'_Face.jpg')) {
                $imagenBinaria = file_get_contents($patch.'\\'.$nombre.'_Face.jpg');
                $imagenBase64 = base64_encode($imagenBinaria);
                $imagen = 'data:image/jpeg;base64,' . $imagenBase64;
            } 

        }

        return array('resp'=>$resp,'msj'=>$msj['msj'],'patch'=>$patch.'\\'.$nombre.'_Face.jpg','imagen'=>$imagen);

    }

    function DeleteFaceBio($parametros)
    {
        // print_r($parametros);die();
        // die();
        $resp = 1;
        $datos = $this->modelo->where('th_per_id', $parametros['idPerson'])->listar();
        $CardNom = $parametros['CardNo'];
        $dispositivo = $this->dispositivos->where('th_dis_id', $parametros['device'])->listar();
        $dllPath = $this->sdk_patch . '13 ' . $dispositivo[0]['host'] . ' ' . $dispositivo[0]['usuario'] . ' ' . $dispositivo[0]['port'] . ' ' . $dispositivo[0]['pass'] ." ". $CardNom;
        // // Comando para ejecutar la DLL
        $command = "dotnet $dllPath";

        // print_r($command);die();

        $output = shell_exec($command);
        $resp = json_decode($output, true);
        $datosBio = array(
                array('campo' => 'th_id_face', 'dato' => $parametros['_idFace']),
            );
        $this->face->eliminar($datosBio);
        return $resp;
    }

    function DeleteFaceBase($parametros)
    {        
        $datosBio = array(
                array('campo' => 'th_id_face', 'dato' => $parametros['_idFace']),
            );
        return $this->face->eliminar($datosBio);
    }

}

