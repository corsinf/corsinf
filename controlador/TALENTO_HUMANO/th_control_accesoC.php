<?php
date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 2) . '/modelo/TALENTO_HUMANO/th_control_accesoM.php');

$controlador = new th_control_accesoC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar($_POST['fecha_inicio'] ?? '', $_POST['fecha_fin'] ?? ''));
}

if (isset($_GET['reporte'])) {
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->listar_datos($parametros));
}


class th_control_accesoC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new th_control_accesoM();
    }

    function listar($fecha_ini = '', $fecha_final = '')
    {
        if ($fecha_ini == '') {
            $datos = $this->modelo->listar_personalizado();
        } else {
            $datos = $this->modelo->listar_personalizado($fecha_ini, $fecha_final);
        }
        
        return $datos;
    }

    function listar_datos($parametros)
    {
        // print_r($parametros);die();
        $fecha_ini =$parametros['txt_fecha_inicio']; 
        $fecha_final =$parametros['txt_fecha_fin'];
        $departamento = $parametros['ddl_departamentos'];
        $usuario = $parametros['ddl_personas'];   
        $orden = $parametros['tipo_ordenamiento'];  


        $fecha_obj = new DateTime($fecha_ini);
        $mesdesde =  $fecha_obj->format('Y').''. $fecha_obj->format('m');

        $fecha_obj = new DateTime($fecha_final);
        $mesHasta =  $fecha_obj->format('Y').''. $fecha_obj->format('m');

        // print_r($mesdesde.'-'.$mesHasta);die();
        $array_table = array();
        if($mesdesde==$mesHasta)
        {
            $array_table[] =  array('tbl'=>'th_control_acceso','inicio'=>$fecha_ini,'fin'=>$fecha_final);
        }else
        {
            $inicio = new DateTime($fecha_ini);
            $fin = new DateTime($fecha_final);

            $inicio->modify('first day of this month');
            $fin->modify('first day of next month');

            $intervalo = DateInterval::createFromDateString('1 month');
            $periodo = new DatePeriod($inicio, $intervalo, $fin);

            $meses = [];
            $num_meses = 1;
            foreach ($periodo as $dt) {
                $periodo = $dt->format('Ym');
                $hoy = date('Ym');
                if($periodo!=$hoy)
                {
                    $periodo_ = $dt->format('Y-m');
                    $ultimoDia = clone $dt;
                    $ultimoDia->modify('last day of this month');
                    $fecha_ultima = $ultimoDia->format('Y-m-d');
                    $fecha_primero =  $dt->format('Y-m-d');
                    if($num_meses>1) {$fecha_ini = $fecha_primero; }

                    array_push($array_table, array('tbl'=>'th_control_acceso_'.$periodo,'inicio'=>$fecha_ini,'fin'=>$fecha_ultima));
                    $num_meses++;
                }else
                {
                    $hoy = date('Y-m');
                    array_push($array_table, array('tbl'=>'th_control_acceso','inicio'=>$hoy.'-01','fin'=>$fecha_final));
                }
            }
        }

        // print_r($array_table);die();
        $list_dato = array();
        foreach ($array_table as $key => $value) {
            $data = $this->traer_datos($value['tbl'],$value['inicio'],$value['fin'],$usuario,$departamento,$orden);
            // print_r($data);die();
            foreach ($data as $key2 => $value2) {
                array_push($list_dato, $value2);
            }
        }


              
        // print_r($list_dato);die();

        // $resultado = array();   
        // $datos = $this->modelo->listar_marcaciones($tabla,$fecha_ini, $fecha_final,$usuario,$departamento,$orden);
        foreach ($list_dato as $key => $value) {
            $dateTime = new DateTime($value['Fecha']);
            $dia = (int)$dateTime->format('w');
            // print_r($value);die();
            $horario = $this->modelo->lista_detalle_turnos_x_persona($value['card'],$dia);
            // print_r($horario);die();
            if(count($horario)>0)
            {
                $horario[0]['Ausente'] = 'NO';
                $resultado[] = array_merge($value, $horario[0]);
            }
        }
        // print_r($resultado);die();
        return $resultado;
        // print_r($parametros);die();
    }

    function traer_datos($tabla,$fecha_ini, $fecha_final,$usuario,$departamento,$orden)
    {        
       return $this->modelo->listar_marcaciones($tabla,$fecha_ini, $fecha_final,$usuario,$departamento,$orden);
    }
}
