<?php
date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 2) . '/modelo/TALENTO_HUMANO/th_justificacionesM.php');
require_once(dirname(__DIR__, 2) . '/modelo/TALENTO_HUMANO/th_faltasM.php');
require_once(dirname(__DIR__, 2) . '/modelo/TALENTO_HUMANO/th_atrasosM.php');
require_once(dirname(__DIR__, 2) . '/modelo/TALENTO_HUMANO/th_control_accesoM.php');

$controlador = new th_justificacionesC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar($_POST['id'] ?? ''));
}

if (isset($_GET['listar_departamentos_justificaciones'])) {
    echo json_encode($controlador->listar_departamentos_justificaciones($_POST['id'] ?? ''));
}

if (isset($_GET['listar_personas_justificaciones'])) {
    echo json_encode($controlador->listar_personas_justificaciones($_POST['id'] ?? ''));
}

if (isset($_GET['insertar'])) {
    echo json_encode($controlador->insertar_editar($_POST['parametros']));
}

if (isset($_GET['eliminar'])) {
    echo json_encode($controlador->eliminar($_POST['id']));
}

if (isset($_GET['cargar_faltas'])) {
    echo json_encode($controlador->cargar_faltas($_POST['parametros']));
}


class th_justificacionesC
{
    private $modelo;
    private $faltas;
    private $atrasos;
    private $controlAcceso;

    function __construct()
    {
        $this->modelo = new th_justificacionesM();
        $this->faltas = new th_faltasM();
        $this->atrasos = new th_atrasosM();
        $this->controlAcceso = new th_control_accesoM();
    }

    function listar($id)
    {
        if ($id != '') {
            //$datos = $this->modelo->where('th_jus_id', $id)->listar();
            $datos = $this->modelo->listar_justificaciones($id, '', '');
            return $datos;
        }
        return null;
    }

    function listar_departamentos_justificaciones($id = '')
    {
        $datos = $this->modelo->listar_departamentos_justificaciones($id);
        return $datos;
    }

    function listar_personas_justificaciones($id = '')
    {
        $datos = $this->modelo->listar_personas_justificaciones($id);
        return $datos;
    }

    function insertar_editar($parametros)
    {

        // print_r($parametros);die();

        if ($parametros['cbx_justificar_rango'] == 1) {
           return $this->insertar_editar_rangos($parametros);
        } else {
            $txt_fecha_inicio = date('Y-m-d H:i:s', strtotime($parametros['txt_fecha_inicio']));
            $txt_fecha_fin = date('Y-m-d H:i:s', strtotime($parametros['txt_fecha_fin']));
            $txt_horas_totales =  $this->hora_a_minutos($parametros['txt_horas_totales']);
        }

        $datos = $this->modelo->existe_justificacion_en_rango($txt_fecha_inicio,$txt_fecha_fin,null,null,null);
        if($datos > 0){
            return -3;
        }



        $fechaMar = date("Ym", strtotime($txt_fecha_inicio));
        $hoy = date("Ym");
        $tabla = "_asistencias.th_justificaciones_".$fechaMar;

        // print_r($fechaMar.'-'.$hoy);die();
        if($fechaMar==$hoy)
        {
            $fechaMar ="";
            $tabla = "_asistencias.th_justificaciones";
        }


        $datos = array(
            array('campo' => 'th_jus_fecha_inicio', 'dato' =>  $txt_fecha_inicio),
            array('campo' => 'th_jus_fecha_fin', 'dato' => $txt_fecha_fin),
            array('campo' => 'th_per_id', 'dato' => $parametros['ddl_personas']),
            array('campo' => 'th_dep_id', 'dato' => $parametros['ddl_departamentos']),

            array('campo' => 'th_tip_jus_id', 'dato' => $parametros['ddl_tipo_justificacion']),
            array('campo' => 'th_jus_motivo', 'dato' => $parametros['txt_motivo']),

            array('campo' => 'th_jus_fecha_modificacion', 'dato' => date('Y-m-d H:i:s')),
            array('campo' => 'id_usuario', 'dato' => $_SESSION['INICIO']['ID_USUARIO']),
            array('campo' => 'th_jus_es_rango', 'dato' => $parametros['cbx_justificar_rango']),
            array('campo' => 'th_jus_minutos_justificados', 'dato' =>  $txt_horas_totales),
            array('campo' => 'th_jus_tipo', 'dato' =>  $parametros['txt_tipo_just']),
        );

        if($parametros['txt_tipo_just']=="A")
        {
            $this->atrasos->updateJustificacionAtraso($parametros['txt_id_DepPer'],$fechaMar);
        }else
        {            
            $this->faltas->updateJustificacionFaltas($parametros['txt_id_DepPer'],$fechaMar);
        }

        $this->controlAcceso->actualizar_min_justificacion($txt_horas_totales,$parametros['ddl_personas'],$txt_fecha_inicio,$fechaMar);

        // print_r($datos);die();

        if ($parametros['_id'] == '') {
            $datos = $this->modelo->insertarTabla($datos,$tabla);
        } else {
            $where[0]['campo'] = 'th_jus_id';
            $where[0]['dato'] = $parametros['_id'];
            $datos = $this->modelo->editar($datos, $where);
        }

        return $datos;
    }

    function insertar_editar_rangos($parametros)
    {
        $rangos = $parametros['rango'];
        $idPersona = $parametros['ddl_personas'];

        foreach ($rangos as $key => $value) {
            $fechaMar = date("Ym", strtotime($value[2]));
            $hoy = date("Ym");
            $tabla = "_asistencias.th_justificaciones_".$fechaMar;

            // print_r($fechaMar.'-'.$hoy);die();
            if($fechaMar==$hoy)
            {
                $fechaMar ="";
                $tabla = "_asistencias.th_justificaciones";
            }

            $datos = $this->modelo->existe_justificacion_en_rango($value[2],$value[2],null,null,null);
            if($datos == 0)
            {
                

                $datos = array(
                    array('campo' => 'th_jus_fecha_inicio', 'dato' =>  $value[2]),
                    array('campo' => 'th_jus_fecha_fin', 'dato' => $value[2]),
                    array('campo' => 'th_per_id', 'dato' => $idPersona),
                    array('campo' => 'th_dep_id', 'dato' => $parametros['ddl_departamentos']),

                    array('campo' => 'th_tip_jus_id', 'dato' => $parametros['ddl_tipo_justificacion']),
                    array('campo' => 'th_jus_motivo', 'dato' => $parametros['txt_motivo']),

                    array('campo' => 'th_jus_fecha_modificacion', 'dato' => date('Y-m-d H:i:s')),
                    array('campo' => 'id_usuario', 'dato' => $_SESSION['INICIO']['ID_USUARIO']),
                    array('campo' => 'th_jus_es_rango', 'dato' => $parametros['cbx_justificar_rango']),
                    array('campo' => 'th_jus_minutos_justificados', 'dato' =>  $value[3]),
                    array('campo' => 'th_jus_tipo', 'dato' =>  $parametros['txt_tipo_just']),
                );

                if($parametros['txt_tipo_just']=="A")
                {
                    $this->atrasos->updateJustificacionAtraso($value[0],$fechaMar);
                }else
                {            
                    $this->faltas->updateJustificacionFaltas($value[0],$fechaMar);
                }

                $this->controlAcceso->actualizar_min_justificacion($value[3],$parametros['ddl_personas'],$value[2],$fechaMar);

                // print_r($datos);die();

                if ($parametros['_id'] == '') {
                    $datos = $this->modelo->insertarTabla($datos,$tabla);
                } else {
                    $where[0]['campo'] = 'th_jus_id';
                    $where[0]['dato'] = $parametros['_id'];
                    $datos = $this->modelo->editar($datos, $where);
                }
            }

        }

        return $datos;

    }

    function eliminar($id)
    {
        $datos = array(
            array('campo' => 'th_jus_estado', 'dato' => 0),
        );

        $where[0]['campo'] = 'th_jus_id';
        $where[0]['dato'] = $id;

        $datos = $this->modelo->editar($datos, $where);
        return $datos;
    }

    function hora_a_minutos($time)
    {
        list($horas, $minutos) = explode(':', $time);
        return ($horas * 60) + $minutos;
    }

    function cargar_faltas($parametros)
    {
        // print_r($parametros);die();
        $fecha_obj = new DateTime($parametros['desdeReg']);
        $mesdesde =  $fecha_obj->format('Y').''. $fecha_obj->format('m');

        $fecha_obj = new DateTime($parametros['hastaReg']);
        $mesHasta =  $fecha_obj->format('Y').''. $fecha_obj->format('m');

        $hoy = date('Ym');

        // print_r($mesdesde.'-'.$mesHasta);die();
        $array_table = array();
        $array_table_faltas = array();
        if($mesdesde==$mesHasta)
        {
            if($mesdesde==$hoy && $mesHasta==$hoy)
            {
                $array_table_faltas[] =  array('tbl'=>'asis_faltas','inicio'=>$parametros['desdeReg'],'fin'=>$parametros['hastaReg']);
                $array_table[] =  array('tbl'=>'asis_atrasos','inicio'=>$parametros['desdeReg'],'fin'=>$parametros['hastaReg']);
            }else
            {
                $array_table_faltas[] =  array('tbl'=>'asis_faltas_'.$mesdesde,'inicio'=>$parametros['desdeReg'],'fin'=>$parametros['hastaReg']);
                $array_table[] =  array('tbl'=>'asis_atrasos_'.$mesdesde,'inicio'=>$parametros['desdeReg'],'fin'=>$parametros['hastaReg']);
            }
        }else
        {
            $inicio = new DateTime($parametros['desdeReg']);
            $fin = new DateTime($parametros['hastaReg']);

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

                    array_push($array_table, array('tbl'=>'asis_atrasos_'.$periodo,'inicio'=>$parametros['desdeReg'],'fin'=>$fecha_ultima));
                    array_push($array_table_faltas, array('tbl'=>'asis_faltas_'.$periodo,'inicio'=>$parametros['desdeReg'],'fin'=>$fecha_ultima));
                    $num_meses++;
                }else
                {
                    $hoy = date('Y-m');
                    array_push($array_table, array('tbl'=>'asis_atrasos','inicio'=>$hoy.'-01','fin'=>$parametros['hastaReg']));
                    array_push($array_table_faltas, array('tbl'=>'asis_faltas','inicio'=>$hoy.'-01','fin'=>$parametros['hastaReg']));
                }
            }
        }

        // print_r($array_table);die();
        $lista_atrasos  = array();
        $lista_faltas  = array();
        foreach ($array_table as $key => $value) {
            $_atrasos = $this->atrasos->lista_atrasos($parametros['persona'],$value['tbl'],$value['inicio'],$value['fin']);
            foreach ($_atrasos as $key => $value) {
                array_push($lista_atrasos,$value);
            }
        }

        foreach ($array_table_faltas as $key => $value) {
            $_faltas = $this->faltas->lista_faltas($parametros['persona'],$value['tbl'],$value['inicio'],$value['fin']);
            foreach ($_faltas as $key => $value) {
                array_push($lista_faltas,$value);
            }
        }

       
        $data = array('faltas'=>$lista_faltas,'atrasos'=>$lista_atrasos);
        return $data;
        // print_r($lista);die();
    }
}
