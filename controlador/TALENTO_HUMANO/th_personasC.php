<?php
date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 2) . '/modelo/TALENTO_HUMANO/th_personasM.php');
require_once(dirname(__DIR__, 2) . '/modelo/TALENTO_HUMANO/th_dispositivosM.php');
require_once(dirname(__DIR__, 2) . '/modelo/TALENTO_HUMANO/th_biometriaM.php');

$controlador = new th_personasC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar($_POST['id'] ?? ''));
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
if (isset($_GET['registros_biometria'])) {
    echo json_encode($controlador->registros_biometria($_POST['parametros']));
}
if (isset($_GET['eliminarFing'])) {
    echo json_encode($controlador->eliminarFing($_POST['id']));
}



class th_personasC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new th_personasM();
        $this->dispositivos = new th_dispositivosM();
        $this->biometria = new th_biometriaM();
        $this->sdk_patch = dirname(__DIR__,2).'/lib/SDKDevices/hikvision/bin/Debug/net8.0/CorsinfSDKHik.dll ';
    
    }

    function listar($id = '')
    {
        if ($id == '') {
            $datos = $this->modelo->where('th_per_estado', 1)->listar();
        } else {
            $datos = $this->modelo->where('th_per_id', $id)->listar();
            $datosB = $this->biometria->where('th_per_id', $id)->listar();
            $datos[0]['biometria']= array();
            if(count($datosB)>0)
            {
                $datos[0]['biometria'] = $datosB[0];
            }
        }
        return $datos;
    }

    function insertar_editar($parametros)
    {
        $txt_fecha_nacimiento = !empty($parametros['txt_fecha_nacimiento']) ? $parametros['txt_fecha_nacimiento'] : null;
        $txt_fecha_aut_inicio = !empty($parametros['txt_fecha_aut_inicio']) ? $parametros['txt_fecha_aut_inicio'] : null;
        $txt_fecha_aut_limite = !empty($parametros['txt_fecha_aut_limite']) ? $parametros['txt_fecha_aut_limite'] : null;
        $txt_fecha_admision = !empty($parametros['txt_fecha_admision']) ? $parametros['txt_fecha_admision'] : null;

        $datos = array(

            array('campo' => 'th_per_primer_apellido', 'dato' => $parametros['txt_primer_apellido']),
            array('campo' => 'th_per_segundo_apellido', 'dato' => $parametros['txt_segundo_apellido']),
            array('campo' => 'th_per_primer_nombre', 'dato' => $parametros['txt_primer_nombre']),
            array('campo' => 'th_per_segundo_nombre', 'dato' => $parametros['txt_segundo_nombre']),
            array('campo' => 'th_per_cedula', 'dato' => $parametros['txt_cedula']),
            array('campo' => 'th_per_sexo', 'dato' => $parametros['ddl_sexo']),
            array('campo' => 'th_per_fecha_nacimiento', 'dato' => $txt_fecha_nacimiento),
            array('campo' => 'th_per_correo', 'dato' => $parametros['txt_correo']),
            array('campo' => 'th_per_telefono_1', 'dato' => $parametros['txt_telefono_1']),
            array('campo' => 'th_per_telefono_2', 'dato' => $parametros['txt_telefono_2']),
            array('campo' => 'th_per_es_admin', 'dato' => $parametros['cbx_admin']),
            array('campo' => 'th_per_habiltado', 'dato' => $parametros['cbx_habilitado']),
            array('campo' => 'th_per_estado_civil', 'dato' => $parametros['ddl_estado_civil']),
            array('campo' => 'th_per_postal', 'dato' => $parametros['txt_postal']),
            array('campo' => 'th_per_direccion', 'dato' => $parametros['txt_direccion']),
            array('campo' => 'th_per_fecha_aut_inicio', 'dato' => $txt_fecha_aut_inicio),
            array('campo' => 'th_per_fecha_aut_limite', 'dato' => $txt_fecha_aut_limite),
            array('campo' => 'th_per_fecha_admision', 'dato' => $txt_fecha_admision),
            array('campo' => 'th_per_cargo', 'dato' => $parametros['txt_cargo']),
            array('campo' => 'th_per_observaciones', 'dato' => $parametros['txt_observaciones']),
            array('campo' =>  'th_per_fecha_modificacion', 'dato' => date('Y-m-d H:i:s')),

            // array('campo' =>  'th_per_foto_url', 'dato' => $parametros['txt_foto_url']),
            //array('campo' =>  'th_prov_id', 'dato' => $parametros['txt__id']),
            //array('campo' =>  'th_ciu_id', 'dato' => $parametros['txt_id']),
            //array('campo' =>  'th_barr_id', 'dato' => $parametros['txt__id']),
        );

        if ($parametros['_id'] == '') {
            if (count($this->modelo->where('th_per_cedula', $parametros['txt_cedula'])->listar()) == 0) {
                $datos = $this->modelo->insertar($datos);
            } else {
                return -2;
            }
        } else {
            if (count($this->modelo->where('th_per_cedula', $parametros['txt_cedula'])->where('th_per_id !', $parametros['_id'])->listar()) == 0) {
                $where[0]['campo'] = 'th_per_id';
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
            array('campo' => 'th_per_estado', 'dato' => 0),
        );

        $where[0]['campo'] = 'th_per_id';
        $where[0]['dato'] = $id;

        $datos = $this->modelo->editar($datos, $where);
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
        $datos = $this->dispositivos->where('th_dis_id',$parametros['id'])->listar();

        if(count($datos)>0)
        {
            $dllPath = $this->sdk_patch.'5 '.$datos[0]['host'].' '.$datos[0]['usuario'].' '.$datos[0]['port'].' '.$datos[0]['pass'].' ';
            // Comando para ejecutar la DLL
            $command = "dotnet $dllPath";

            // print_r($command);die();
            $output = shell_exec($command);
            $resp = json_decode($output,true);
            $cadena = $resp['msj'];
            $cadena = preg_replace('/[^\w:{}\s,]/u', '', $cadena);
            $cadena = str_replace(["CardNo", "nombre", "{"], ['"CardNo"', '"nombre"', '{'], $cadena);
            $cadena = '[' . str_replace(['":', '}',',"'],['":"','"}','","'], $cadena) . ']';
            // print_r($cadena);die();
            $datos = json_decode($cadena, true);

            return $datos;
        }else
        {
            return -1;
        }

    }

    function guardarImport($parametros)
    {
        $msj = '';
        $datos = $parametros['datos'];
        $datos = json_decode($datos, true);

        foreach ($datos as $key => $value) {
            $per = explode(' ',$value['nombre']);
            $where = '';
            if(isset($per[0]))
            {                   
                $where.="th_per_primer_nombre";
            }
            if(isset($per[2]))
            {                
                $where.="+' '+th_per_segundo_nombre";
            }
            if(isset($per[1]))
            {
               $where.="+' '+th_per_primer_apellido";
            }
            if(isset($per[3]))
            {
                $where.="+' '+th_per_segundo_apellido";
            }
            $this->modelo->reset();
            $datos = $this->modelo->where($where,$value['nombre'])->listar();

            // print_r($datos);die();
            if(count($datos)==0)
            {
                 $valor = array(
                        array('campo' =>  'th_per_fecha_modificacion', 'dato' => date('Y-m-d H:i:s')),
                );

                if(isset($per[0]))
                {                   
                    $campo = array('campo' =>  'th_per_primer_nombre', 'dato' => $per[0]);               
                    array_push($valor, $campo);
                }
                if(isset($per[2]))
                {
                    $campo = array('campo' => 'th_per_segundo_nombre', 'dato' => $per[2]);               
                    array_push($valor, $campo);
                }
                if(isset($per[1]))
                {
                     $campo = array('campo' => 'th_per_primer_apellido', 'dato' => $per[1]);               
                    array_push($valor, $campo);
                }
                if(isset($per[3]))
                {
                     $campo = array('campo' => 'th_per_segundo_apellido', 'dato' => $per[3]);       
                    array_push($valor, $campo);
                }
                // print_r($valor);die();               
                $datos = $this->modelo->insertar($valor);

                $reg = $this->modelo->where($where,$value['nombre'])->listar();

                // print_r($reg);die();
                $biom = array(
                    array('campo' => 'th_per_id', 'dato' => $reg[0]['_id']),
                    array('campo' => 'th_bio_card', 'dato' => $value['CardNo']),
                    array('campo' => 'th_bio_nombre', 'dato' => "tarjeta"),
                );

                $datos = $this->biometria->insertar($biom);

            }else
            {
                $msj.='El registro '.$value['nombre'].' ya esta registrado<br>';
            }
        }

        if($msj!='')
        {
            $msj = '<div style="text-align: left;">'.$msj.'</div>';
        }

        return array('resp'=>1,'msj'=>$msj);
    }

    function registros_biometria($parametros)
    {
        $datos = $this->biometria->where('th_per_id',$parametros['id'])->listar();
        $detalle = array();
        foreach ($datos as $key => $value) {
            $detalle[] = array('id'=>$value['_id'],'detalle'=>$value['th_bio_nombre']);
        }

        return $detalle;
        // print_r($datos);die();        
    }

    function eliminarFing($id)
    {
        $where[0]['campo'] = 'th_bio_id';
        $where[0]['dato'] = $id;
        return $this->biometria->eliminar($where);

    }


}
