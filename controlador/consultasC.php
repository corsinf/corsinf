<?php

use function Complex\ln;

include('../modelo/consultasM.php');
include('../lib/phpmailer/enviar_emails.php');
include('../lib/pdf/fpdf.php');


$controlador = new consultasC();

if (isset($_GET['listar_consulta_ficha'])) {

    $id_ficha = '';

    if (isset($_POST['id_ficha'])) {
        $id_ficha = $_POST['id_ficha'];
    }

    echo json_encode($controlador->lista_consultas_ficha($id_ficha));
}

if (isset($_GET['insertar'])) {
    echo json_encode($controlador->insertar_editar($_POST['parametros']));
}

if (isset($_GET['eliminar'])) {
    echo json_encode($controlador->eliminar($_POST['id']));
}

if (isset($_GET['listar_solo_consulta'])) {
    echo json_encode($controlador->lista_solo_consultas($_POST['id']));
}

if (isset($_GET['enviar_correo'])) {
    echo json_encode($controlador->enviar_correo($_POST['parametros']));
}

if (isset($_GET['notificaciones'])) {
    echo json_encode($controlador->pdf_notificaciones());
}

if (isset($_GET['datos_consulta'])) {

    $id_consulta = '';

    if (isset($_POST['id_consulta'])) {
        $id_consulta = $_POST['id_consulta'];
    }

    echo json_encode($controlador->carga_datos_consultas($id_consulta));
}

//print_r($controlador->lista_consultas(''));

/*$parametros = array(
    'sa_sec_id' => 1,
    'sa_sec_nombre' => 'hola'
);

print_r($controlador->insertar_editar($parametros));*/

/*$modelo = new consultasM();

print_r($modelo->buscar_consultas_CODIGO(1));*/

class consultasC
{
    private $modelo;
    private $email;
    function __construct()
    {
        $this->modelo = new consultasM();
        $this->email = new enviar_emails();
    }

    function lista_consultas_ficha($id_ficha)
    {
        $datos = $this->modelo->lista_consultas_ficha($id_ficha);
        return $datos;
    }

    function lista_solo_consultas($id)
    {
        $datos = $this->modelo->lista_solo_consultas($id);
        return $datos;
    }

    //Retorna los valores (id) para cargar la ficha medica y el paciente
    function carga_datos_consultas($id_consulta)
    {
        $datos = $this->modelo->carga_datos_consultas($id_consulta);


        return $datos;
    }


    function insertar_editar($parametros)
    {
        $datos1[0]['campo'] = 'sa_conp_id';
        $datos1[0]['dato'] = strval($parametros['sa_conp_id']);

        $datos = array(
            array('campo' => 'sa_fice_id', 'dato' => $parametros['sa_fice_id']),
            array('campo' => 'sa_conp_nivel', 'dato' => $parametros['sa_conp_nivel']),
            array('campo' => 'sa_conp_paralelo', 'dato' => $parametros['sa_conp_paralelo']),
            array('campo' => 'sa_conp_edad', 'dato' => $parametros['sa_conp_edad']),
            array('campo' => 'sa_conp_peso', 'dato' => empty($parametros['sa_conp_peso']) ? 0 : $parametros['sa_conp_peso']),
            array('campo' => 'sa_conp_altura', 'dato' => empty($parametros['sa_conp_altura']) ? 0 : $parametros['sa_conp_altura']),
            array('campo' => 'sa_conp_temperatura', 'dato' => empty($parametros['sa_conp_temperatura']) ? 0 : $parametros['sa_conp_temperatura']),
            array('campo' => 'sa_conp_presion_ar', 'dato' => empty($parametros['sa_conp_presion_ar']) ? 0 : $parametros['sa_conp_presion_ar']),
            array('campo' => 'sa_conp_frec_cardiaca', 'dato' => empty($parametros['sa_conp_frec_cardiaca']) ? 0 : $parametros['sa_conp_frec_cardiaca']),
            array('campo' => 'sa_conp_frec_respiratoria', 'dato' => empty($parametros['sa_conp_frec_respiratoria']) ? 0 : $parametros['sa_conp_frec_respiratoria']),

            array('campo' => 'sa_conp_fecha_ingreso', 'dato' => $parametros['sa_conp_fecha_ingreso']),
            array('campo' => 'sa_conp_desde_hora', 'dato' => $parametros['sa_conp_desde_hora']),
            array('campo' => 'sa_conp_hasta_hora', 'dato' => $parametros['sa_conp_hasta_hora']),
            array('campo' => 'sa_conp_tiempo_aten', 'dato' => $parametros['sa_conp_tiempo_aten']),
            array('campo' => 'sa_conp_CIE_10_1', 'dato' => $parametros['sa_conp_CIE_10_1']),
            array('campo' => 'sa_conp_diagnostico_1', 'dato' => $parametros['sa_conp_diagnostico_1']),
            array('campo' => 'sa_conp_CIE_10_2', 'dato' => $parametros['sa_conp_CIE_10_2']),
            array('campo' => 'sa_conp_diagnostico_2', 'dato' => $parametros['sa_conp_diagnostico_2']),

            array('campo' => 'sa_conp_salud_certificado', 'dato' => $parametros['sa_conp_salud_certificado']),
            array('campo' => 'sa_conp_motivo_certificado', 'dato' => $parametros['sa_conp_motivo_certificado']),
            array('campo' => 'sa_conp_CIE_10_certificado', 'dato' => $parametros['sa_conp_CIE_10_certificado']),
            array('campo' => 'sa_conp_diagnostico_certificado', 'dato' => $parametros['sa_conp_diagnostico_certificado']),
            //array('campo' => 'sa_conp_fecha_entrega_certificado', 'dato' => $parametros['sa_conp_fecha_entrega_certificado']),
            //array('campo' => 'sa_conp_fecha_inicio_falta_certificado', 'dato' => $parametros['sa_conp_fecha_inicio_falta_certificado']),
            //array('campo' => 'sa_conp_fecha_fin_alta_certificado', 'dato' => $parametros['sa_conp_fecha_fin_alta_certificado']),
            array('campo' => 'sa_conp_dias_permiso_certificado', 'dato' => $parametros['sa_conp_dias_permiso_certificado']),

            array('campo' => 'sa_conp_permiso_salida', 'dato' => $parametros['sa_conp_permiso_salida']),
            //array('campo' => 'sa_conp_fecha_permiso_salud_salida', 'dato' => $parametros['sa_conp_fecha_permiso_salud_salida']),
            array('campo' => 'sa_conp_hora_permiso_salida', 'dato' => $parametros['sa_conp_hora_permiso_salida']),
            array('campo' => 'sa_conp_permiso_tipo', 'dato' => $parametros['sa_conp_permiso_tipo']),
            array('campo' => 'sa_conp_permiso_seguro_traslado', 'dato' => $parametros['sa_conp_permiso_seguro_traslado']),
            array('campo' => 'sa_conp_permiso_telefono_padre', 'dato' => $parametros['sa_conp_permiso_telefono_padre']),
            array('campo' => 'sa_conp_permiso_telefono_seguro', 'dato' => $parametros['sa_conp_permiso_telefono_seguro']),

            array('campo' => 'sa_conp_notificacion_envio_representante', 'dato' => $parametros['sa_conp_notificacion_envio_representante']),
            array('campo' => 'sa_id_representante', 'dato' => $parametros['sa_id_representante']),
            array('campo' => 'sa_conp_notificacion_envio_docente', 'dato' => $parametros['sa_conp_notificacion_envio_docente']),
            array('campo' => 'sa_id_docente', 'dato' => $parametros['sa_id_docente']),
            array('campo' => 'sa_conp_notificacion_envio_inspector', 'dato' => $parametros['sa_conp_notificacion_envio_inspector']),
            array('campo' => 'sa_id_inspector', 'dato' => $parametros['sa_id_inspector']),
            array('campo' => 'sa_conp_notificacion_envio_guardia', 'dato' => $parametros['sa_conp_notificacion_envio_guardia']),
            array('campo' => 'sa_id_guardia', 'dato' => $parametros['sa_id_guardia']),
            array('campo' => 'sa_conp_tipo_consulta', 'dato' => $parametros['sa_conp_tipo_consulta']),
            array('campo' => 'sa_conp_observaciones', 'dato' => $parametros['sa_conp_observaciones']),
            array('campo' => 'sa_conp_motivo_consulta', 'dato' => $parametros['sa_conp_motivo_consulta']),
            array('campo' => 'sa_conp_tratamiento', 'dato' => $parametros['sa_conp_tratamiento']),
            array('campo' => 'sa_conp_estado_revision', 'dato' => $parametros['sa_conp_estado_revision']),
        );


        $fechas_certificado = null;
        if ($parametros['sa_conp_tipo_consulta'] === 'certificado') {
            $fechas_certificado = array(
                array('campo' => 'sa_conp_fecha_entrega_certificado', 'dato' => $parametros['sa_conp_fecha_entrega_certificado']),
                array('campo' => 'sa_conp_fecha_inicio_falta_certificado', 'dato' => $parametros['sa_conp_fecha_inicio_falta_certificado']),
                array('campo' => 'sa_conp_fecha_fin_alta_certificado', 'dato' => $parametros['sa_conp_fecha_fin_alta_certificado']),
            );
            $datos = array_merge($datos, $fechas_certificado);
        }

        $fechas_salida = null;
        if ($parametros['sa_conp_permiso_salida'] === 'SI') {
            $fechas_salida = array(
                array('campo' => 'sa_conp_fecha_permiso_salud_salida', 'dato' => $parametros['sa_conp_fecha_permiso_salud_salida']),
            );
            $datos = array_merge($datos, $fechas_salida);
        }

        //print_r($parametros);die();

        if ($parametros['sa_conp_id'] == '') {
            if (count($this->modelo->buscar_consultas_CODIGO($datos1[0]['dato'])) == 0) {
                $datos = $this->modelo->insertar($datos);
            } else {
                return -2 . ' . ' . $datos1[0]['dato'];
            }
        } else {
            $where[0]['campo'] = 'sa_conp_id';
            $where[0]['dato'] = $parametros['sa_conp_id'];
            //$datos[] = array('campo' => 'sa_conp_estado', 'dato' => 1);
            $datos = $this->modelo->editar($datos, $where);
        }

        /*$where[0]['campo'] = 'sa_conp_id';
        $where[0]['dato'] = $parametros['sa_conp_id'];
        $datos = $this->modelo->editar($datos, $where);*/

        //$datos = $this->modelo->insertar($datos);

        return $datos;
    }

    function eliminar($id)
    {
        $datos[0]['campo'] = 'sa_conp_id';
        $datos[0]['dato'] = $id;
        $datos = $this->modelo->eliminar($datos);
        return $datos;
    }

    function enviar_correo($parametros)
    {
        // print_r($parametros);die();
        $to_correo = $parametros['to'];
        $titulo_correo = $parametros['sub'];
        $cuerpo_correo = $parametros['men'];

        //return $this->email->enviar_email($to_correo, $cuerpo_correo, $titulo_correo, $correo_respaldo = 'soporte@corsinf.com', $archivos = false, $titulo_correo, true);

        return true;
    }

    function pdf_notificaciones()
    {
        $pdf = new FPDF('P', 'mm', 'A4');
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 12);

        $pdf->Cell(40, 10, utf8_decode(''), 'L T', 0);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(90, 10, utf8_decode('UNIDAD EDUCATIVA SAINT DOMINIC'), 1, 0, 'C');
        $pdf->Cell(20, 10, utf8_decode('Código:'), 1, 0, 'R');
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(40, 10, utf8_decode('GA-MD-RG-001'), 1, 1, 'C');


        $pdf->Cell(40, 10, utf8_decode(''), 'L', 0);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(90, 10, utf8_decode('DEPARTAMENTO MÉDICO'), 1, 0, 'C');
        $pdf->Cell(20, 10, utf8_decode('Versión:'), 1, 0, 'R');
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(40, 10, utf8_decode('1.0'), 1, 1, 'C');


        $pdf->Cell(40, 10, utf8_decode(''), 'L B', 0, 'C');
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(90, 10, utf8_decode('PERMISO DE SALIDA'), 1, 0, 'C');
        $pdf->Cell(20, 10, utf8_decode('Página:'), 1, 0, 'R');
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(40, 10, utf8_decode('1 de 1'), 1, 1, 'C');

        $pdf->ln('8');

        //ENVIAR DATOS 
        $mensaje = $this->mensajes_notificacion();
        $pdf->MultiCell(0, 5, utf8_decode($mensaje), 0, 'J');

        $pdf->ln('25');


        $nombre_medico = ' Md. Camila López';

        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(60, 10, utf8_decode($nombre_medico), '0', 0, 'C');
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(60, 10, utf8_decode('Representante'), '0', 1, 'R');

        $pdf->Cell(60, 10, utf8_decode('Médico Institucional'), '0', 0, 'C');

        $pdf->Output();
    }

    function mensajes_notificacion()
    {
        $fecha_creado = '2023/02/12';
        $nombre_estudiante = 'Andrea Andrea Lopez Lopez';
        $grado = 'Bachillerato 3';
        $paralelo = 'A';
        //CONSULTA
        $hora_desde = '08:00';
        $hora_hasta = '08:30';
        //cERTIFICADO
        $diagnostico_certificado = 'Consulta Medica';

        //Mensaje Consulta/////////////////////////////////////////////////////////////////////////////////////
        $mensaje_consulta = '';
        $mensaje_consulta .=
            'FECHA: ' . $fecha_creado . '
        
';
        $mensaje_consulta .=
            'CERTIFICO QUE EL/LA ESTUDIANTE' . $nombre_estudiante . ' DEL GRADO ' . $grado . ' PARALELO ' . $paralelo . ' SE ENCONTRO EN EL DEPARTAMENTO MÉDICO DESDE ' . $hora_desde . ' HASTA ' . $hora_hasta;
        ///////////////////////////////////////////////////////////////////////////////////////////////////

        //Mensaje Certificado/////////////////////////////////////////////////////////////////////////////////////
        $mensaje_certificado = '';
        $mensaje_certificado .=
            'HOY, ' . $fecha_creado . '
        
';
        $mensaje_certificado .=
            'CERTIFICO QUE EL/LA REPRESENTANTE DE ' . $nombre_estudiante . ' DEL GRADO ' . $grado . ' PARALELO ' . $paralelo . ' ENTREGA CERTIFICADO MÉDICO DE REPRESENTADO CON DIAGNÓSTICO ' . $diagnostico_certificado;
        ///////////////////////////////////////////////////////////////////////////////////////////////////

        //Mensaje Salida/////////////////////////////////////////////////////////////////////////////////////
        $mensaje_salida = '';
        $mensaje_salida .=
            'HOY, ' . $fecha_creado . '

';
        $mensaje_salida .=
            'CERTIFICO QUE EL/LA ESTUDIANTE  ' . $nombre_estudiante . ' DEL GRADO ' . $grado . ' PARALELO ' . $paralelo . ' REQUIERE SALIR DEL PLANTEL PARA RECIBIR ATENCIÓN MÉDICA EXTERNA';
        ///////////////////////////////////////////////////////////////////////////////////////////////////

        return $mensaje_certificado;
    }
}
