<?php

use function Complex\ln;

include('../modelo/consultasM.php');
include('../modelo/ficha_MedicaM.php');
include('../modelo/pacientesM.php');
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

if (isset($_GET['pdf_consulta'])) {

    //print_r($_POST);die();
    $id_consulta = '';
    if (isset($_GET['id_consulta'])) {
        $id_consulta = $_GET['id_consulta'];
    }

    if (isset($_POST['id_consulta'])) {
        $id_consulta = $_POST['id_consulta'];
    }

    echo ($controlador->pdf_consulta_paciente($id_consulta));
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
    private $ficha_medicaM;
    private $pacientesM;
    private $email;
    function __construct()
    {
        $this->modelo = new consultasM();
        $this->ficha_medicaM = new ficha_MedicaM();
        $this->pacientesM = new pacientesM();
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

    function pdf_consulta_paciente($id_consulta)
    {
        $datos = $this->modelo->lista_solo_consultas($id_consulta);
        $ficha_medica = $this->ficha_medicaM->lista_ficha_medica_id($datos[0]['sa_fice_id']);
        $paciente = $this->pacientesM->obtener_informacion_pacienteM($ficha_medica[0]['sa_fice_pac_id']);

        //Consulta 
        $sa_fice_id = $datos[0]['sa_fice_id'];
        $sa_conp_nivel = $datos[0]['sa_conp_nivel'];
        $sa_conp_paralelo = $datos[0]['sa_conp_paralelo'];
        $sa_conp_edad = $datos[0]['sa_conp_edad'];
        $sa_conp_peso = $datos[0]['sa_conp_peso'];
        $sa_conp_altura = $datos[0]['sa_conp_altura'];
        $sa_conp_temperatura = $datos[0]['sa_conp_temperatura'];
        $sa_conp_presion_ar = $datos[0]['sa_conp_presion_ar'];
        $sa_conp_frec_cardiaca = $datos[0]['sa_conp_frec_cardiaca'];
        $sa_conp_frec_respiratoria = $datos[0]['sa_conp_frec_respiratoria'];

        $sa_conp_fecha_ingreso = $datos[0]['sa_conp_fecha_ingreso'];
        $sa_conp_fecha_ingreso = $sa_conp_fecha_ingreso->format('Y-m-d');

        $sa_conp_desde_hora = $datos[0]['sa_conp_desde_hora'];
        $sa_conp_desde_hora = $sa_conp_desde_hora->format('H:i:s');

        $sa_conp_hasta_hora = $datos[0]['sa_conp_hasta_hora'];
        $sa_conp_hasta_hora = $sa_conp_hasta_hora->format('H:i:s');

        $sa_conp_tiempo_aten = $datos[0]['sa_conp_tiempo_aten'];

        $sa_conp_CIE_10_1 = $datos[0]['sa_conp_CIE_10_1'];
        $sa_conp_diagnostico_1 = $datos[0]['sa_conp_diagnostico_1'];
        $sa_conp_CIE_10_2 = $datos[0]['sa_conp_CIE_10_2'];
        $sa_conp_diagnostico_2 = $datos[0]['sa_conp_diagnostico_2'];

        $sa_conp_salud_certificado = $datos[0]['sa_conp_salud_certificado'];
        $sa_conp_motivo_certificado = $datos[0]['sa_conp_motivo_certificado'];
        $sa_conp_CIE_10_certificado = $datos[0]['sa_conp_CIE_10_certificado'];
        $sa_conp_diagnostico_certificado = $datos[0]['sa_conp_diagnostico_certificado'];
        $sa_conp_fecha_entrega_certificado = $datos[0]['sa_conp_fecha_entrega_certificado'];
        $sa_conp_fecha_inicio_falta_certificado = $datos[0]['sa_conp_fecha_inicio_falta_certificado'];
        $sa_conp_fecha_fin_alta_certificado = $datos[0]['sa_conp_fecha_fin_alta_certificado'];
        $sa_conp_dias_permiso_certificado = $datos[0]['sa_conp_dias_permiso_certificado'];

        $sa_conp_permiso_salida = $datos[0]['sa_conp_permiso_salida'];

        $sa_conp_fecha_permiso_salud_salida = $datos[0]['sa_conp_fecha_permiso_salud_salida'];
        if ($sa_conp_fecha_permiso_salud_salida !== null) {
            $sa_conp_fecha_permiso_salud_salida = $sa_conp_fecha_permiso_salud_salida->format('Y-m-d');
        }

        $sa_conp_hora_permiso_salida = $datos[0]['sa_conp_hora_permiso_salida'];
        if ($sa_conp_hora_permiso_salida !== null) {
            $sa_conp_hora_permiso_salida = $sa_conp_hora_permiso_salida->format('H:i:s');
        }

        $sa_conp_permiso_tipo = $datos[0]['sa_conp_permiso_tipo'];
        $sa_conp_permiso_seguro_traslado = $datos[0]['sa_conp_permiso_seguro_traslado'];
        $sa_conp_permiso_telefono_padre = $datos[0]['sa_conp_permiso_telefono_padre'];
        $sa_conp_permiso_telefono_seguro = $datos[0]['sa_conp_permiso_telefono_seguro'];

        $sa_conp_motivo_consulta = $datos[0]['sa_conp_motivo_consulta'];
        $sa_conp_observaciones = $datos[0]['sa_conp_observaciones'];
        $sa_conp_tratamiento = $datos[0]['sa_conp_tratamiento'];
        $sa_conp_tipo_consulta = $datos[0]['sa_conp_tipo_consulta'];

        //Ficha medica
        $sa_fice_pac_grupo_sangre = $ficha_medica[0]['sa_fice_pac_grupo_sangre'];
        $sa_fice_pac_direccion_domicilio = $ficha_medica[0]['sa_fice_pac_direccion_domicilio'];

        $sa_fice_rep_1_primer_apellido = $ficha_medica[0]['sa_fice_rep_1_primer_apellido'];
        $sa_fice_rep_1_segundo_apellido = $ficha_medica[0]['sa_fice_rep_1_segundo_apellido'];
        $sa_fice_rep_1_primer_nombre = $ficha_medica[0]['sa_fice_rep_1_primer_nombre'];
        $sa_fice_rep_1_segundo_nombre = $ficha_medica[0]['sa_fice_rep_1_segundo_nombre'];
        $sa_fice_rep_1_completo = $sa_fice_rep_1_primer_apellido . ' ' . $sa_fice_rep_1_segundo_apellido . ' ' . $sa_fice_rep_1_primer_nombre . ' ' . $sa_fice_rep_1_segundo_nombre;
        $sa_fice_rep_1_parentesco = $ficha_medica[0]['sa_fice_rep_1_parentesco'];
        $sa_fice_rep_1_telefono_1 = $ficha_medica[0]['sa_fice_rep_1_telefono_1'];
        $sa_fice_rep_1_telefono_2 = $ficha_medica[0]['sa_fice_rep_1_telefono_2'];

        $sa_fice_rep_2_primer_apellido = $ficha_medica[0]['sa_fice_rep_2_primer_apellido'];
        $sa_fice_rep_2_segundo_apellido = $ficha_medica[0]['sa_fice_rep_2_segundo_apellido'];
        $sa_fice_rep_2_primer_nombre = $ficha_medica[0]['sa_fice_rep_2_primer_nombre'];
        $sa_fice_rep_2_segundo_nombre = $ficha_medica[0]['sa_fice_rep_2_segundo_nombre'];
        $sa_fice_rep_2_completo = $sa_fice_rep_2_primer_apellido . ' ' . $sa_fice_rep_2_segundo_apellido . ' ' . $sa_fice_rep_2_primer_nombre . ' ' . $sa_fice_rep_2_segundo_nombre;

        $sa_fice_rep_2_parentesco = $ficha_medica[0]['sa_fice_rep_2_parentesco'];
        $sa_fice_rep_2_telefono_1 = $ficha_medica[0]['sa_fice_rep_2_telefono_1'];
        $sa_fice_rep_2_telefono_2 = $ficha_medica[0]['sa_fice_rep_2_telefono_2'];

        $sa_fice_pregunta_1_obs = $ficha_medica[0]['sa_fice_pregunta_1_obs'];
        $sa_fice_pregunta_2_obs = $ficha_medica[0]['sa_fice_pregunta_2_obs'];
        $sa_fice_pregunta_3_obs = $ficha_medica[0]['sa_fice_pregunta_3_obs'];
        $sa_fice_pregunta_4_obs = $ficha_medica[0]['sa_fice_pregunta_4_obs'];
        $sa_fice_pregunta_5_obs = $ficha_medica[0]['sa_fice_pregunta_5_obs'];

        //Pacientes
        $sa_pac_temp_cedula = $paciente[0]['sa_pac_temp_cedula'];
        $sa_pac_temp_primer_nombre = $paciente[0]['sa_pac_temp_primer_nombre'];
        $sa_pac_temp_segundo_nombre = $paciente[0]['sa_pac_temp_segundo_nombre'];
        $sa_pac_temp_primer_apellido = $paciente[0]['sa_pac_temp_primer_apellido'];
        $sa_pac_temp_segundo_apellido = $paciente[0]['sa_pac_temp_segundo_apellido'];
        //$sa_pac_temp_fecha_nacimiento = $paciente[0]['sa_pac_temp_fecha_nacimiento'];

        $fechaNacimiento = $paciente[0]['sa_pac_temp_fecha_nacimiento'];
        $sa_pac_temp_fecha_nacimiento = $fechaNacimiento->format('Y-m-d');

        /*$fechaActual = new DateTime(); 
        $diferencia = $fechaActual->diff($fechaNacimiento);
        $edad = $diferencia->y;*/



        //print_r($datos);

        //exit();

        $sa_pac_temp_correo = $paciente[0]['sa_pac_temp_correo'];



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
        $pdf->Cell(90, 10, utf8_decode('FORMULARIO - ' . strtoupper($sa_conp_tipo_consulta)), 1, 0, 'C');
        $pdf->Cell(20, 10, utf8_decode('Página:'), 1, 0, 'R');
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(40, 10, utf8_decode('1 de 1'), 1, 1, 'C');

        $pdf->ln('8');

        /////////////////////////////////////////////////////////////////////////////////////////////
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(190, 10, utf8_decode('  DATOS PERSONALES DEL USUARIO / PACIENTE'), 1, 1, 'L');

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(22, 7, utf8_decode('CÉDULA'), 1, 0, 'C');
        $pdf->Cell(42, 7, utf8_decode('PRIMER APELLIDO'), 1, 0, 'C');
        $pdf->Cell(42, 7, utf8_decode('SEGUNDO APELLIDO'), 1, 0, 'C');
        $pdf->Cell(42, 7, utf8_decode('PRIMER NOMBRE'), 1, 0, 'C');
        $pdf->Cell(42, 7, utf8_decode('SEGUNDO NOMBRE'), 1, 1, 'C');

        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(22, 8, utf8_decode($sa_pac_temp_cedula), 1, 0, 'C');
        $pdf->Cell(42, 8, utf8_decode($sa_pac_temp_primer_apellido), 1, 0, 'C');
        $pdf->Cell(42, 8, utf8_decode($sa_pac_temp_segundo_apellido), 1, 0, 'C');
        $pdf->Cell(42, 8, utf8_decode($sa_pac_temp_primer_nombre), 1, 0, 'C');
        $pdf->Cell(42, 8, utf8_decode($sa_pac_temp_segundo_nombre), 1, 1, 'C');

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(70, 7, utf8_decode('FECHA DE NACIMIENTO'), 1, 0, 'C');
        $pdf->Cell(20, 7, utf8_decode('EDAD'), 1, 0, 'C');
        $pdf->Cell(100, 7, utf8_decode('CORREO'), 1, 1, 'C');

        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(70, 8, ($sa_pac_temp_fecha_nacimiento), 1, 0, 'C');
        $pdf->Cell(20, 8, utf8_decode($sa_conp_edad . ' años'), 1, 0, 'C');
        $pdf->Cell(100, 8, utf8_decode($sa_pac_temp_correo), 1, 1, 'C');

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(50, 7, utf8_decode('GRUPO SANGUÍNEO'), 1, 0, 'C');
        $pdf->Cell(140, 7, utf8_decode('DIRECCIÓN'), 1, 1, 'C');

        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(50, 8, utf8_decode($sa_fice_pac_grupo_sangre), 1, 0, 'C');
        $pdf->Cell(140, 8, utf8_decode($sa_fice_pac_direccion_domicilio), 1, 1, 'C');

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(47.5, 7, utf8_decode('FECHA DE INGRESO'), 1, 0, 'C');
        $pdf->Cell(47.5, 7, utf8_decode('HORA DE ATENCIÓN'), 1, 0, 'C');
        $pdf->Cell(47.5, 7, utf8_decode('HORA FIN DE ATENCIÓN'), 1, 0, 'C');
        $pdf->Cell(47.5, 7, utf8_decode('TIEMPO DE ATENCIÓN'), 1, 1, 'C');

        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(47.5, 8, ($sa_conp_fecha_ingreso), 1, 0, 'C');
        $pdf->Cell(47.5, 8, ($sa_conp_desde_hora), 1, 0, 'C');
        $pdf->Cell(47.5, 8, ($sa_conp_hasta_hora), 1, 0, 'C');
        $pdf->Cell(47.5, 8, ($sa_conp_tiempo_aten), 1, 1, 'C');


        /////////////////////////////////////////////////////////////////////////////////////////////
        $pdf->ln('4');

        /////////////////////////////////////////////////////////////////////////////////////////////
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(190, 10, utf8_decode('  DATOS DE CONTACTO'), 1, 1, 'L');

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(70, 7, utf8_decode('EN CASO NECESARIO LLAMAR A:'), 1, 0, 'C');
        $pdf->Cell(40, 7, utf8_decode('PARENTESCO'), 1, 0, 'C');
        $pdf->Cell(40, 7, utf8_decode('TELÉFONO 1'), 1, 0, 'C');
        $pdf->Cell(40, 7, utf8_decode('TELÉFONO 2'), 1, 1, 'C');

        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(70, 8, utf8_decode($sa_fice_rep_1_completo), 1, 0, 'C');
        $pdf->Cell(40, 8, utf8_decode($sa_fice_rep_1_parentesco), 1, 0, 'C');
        $pdf->Cell(40, 8, utf8_decode($sa_fice_rep_1_telefono_1), 1, 0, 'C');
        $pdf->Cell(40, 8, utf8_decode($sa_fice_rep_1_telefono_2), 1, 1, 'C');

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(70, 7, utf8_decode('EN CASO NECESARIO LLAMAR A:'), 1, 0, 'C');
        $pdf->Cell(40, 7, utf8_decode('PARENTESCO'), 1, 0, 'C');
        $pdf->Cell(40, 7, utf8_decode('TELÉFONO 1'), 1, 0, 'C');
        $pdf->Cell(40, 7, utf8_decode('TELÉFONO 2'), 1, 1, 'C');



        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(70, 8, utf8_decode($sa_fice_rep_2_completo), 1, 0, 'C');
        $pdf->Cell(40, 8, utf8_decode($sa_fice_rep_2_parentesco), 1, 0, 'C');
        $pdf->Cell(40, 8, utf8_decode($sa_fice_rep_2_telefono_1), 1, 0, 'C');
        $pdf->Cell(40, 8, utf8_decode($sa_fice_rep_2_telefono_2), 1, 1, 'C');



        /////////////////////////////////////////////////////////////////////////////////////////////
        $pdf->ln('4');

        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(190, 10, utf8_decode('  DIAGNÓSTICOS'), 1, 1, 'L');

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(32, 7, utf8_decode('DIAGNÓSTICO 1: '), 1, 0, 'C');
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(158, 7, utf8_decode($sa_conp_diagnostico_1), 1, 1, 'C');

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(32, 7, utf8_decode('DIAGNÓSTICO 2: '), 1, 0, 'C');
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(158, 7, utf8_decode($sa_conp_diagnostico_2), 1, 1, 'C');


        /////////////////////////////////////////////////////////////////////////////////////////////
        $pdf->ln('4');

        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(190, 10, utf8_decode('  CONSTANTES VITALES'), 1, 1, 'L');

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(29.6, 7, utf8_decode('TEMPERATURA'), 1, 0, 'C');
        $pdf->Cell(31.6, 7, utf8_decode('PRESIÓN ARTERIAL'), 1, 0, 'C');
        $pdf->Cell(29.6, 7, utf8_decode('PULOS / min'), 1, 0, 'C');
        $pdf->Cell(41.2, 7, utf8_decode('FRECIENCIA RESPITARORIA'), 1, 0, 'C');
        $pdf->Cell(29, 7, utf8_decode('PESO (kg)'), 1, 0, 'C');
        $pdf->Cell(29, 7, utf8_decode('TALLA (m)'), 1, 1, 'C');

        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(29.6, 8, utf8_decode($sa_conp_temperatura), 1, 0, 'C');
        $pdf->Cell(31.6, 8, utf8_decode($sa_conp_presion_ar), 1, 0, 'C');
        $pdf->Cell(29.6, 8, utf8_decode($sa_conp_frec_cardiaca), 1, 0, 'C');
        $pdf->Cell(41.2, 8, utf8_decode($sa_conp_frec_respiratoria), 1, 0, 'C');
        $pdf->Cell(29, 8, utf8_decode($sa_conp_peso), 1, 0, 'C');
        $pdf->Cell(29, 8, utf8_decode($sa_conp_altura), 1, 1, 'C');

        /////////////////////////////////////////////////////////////////////////////////////////////
        $pdf->ln('4');

        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(190, 10, utf8_decode('  REFERENCIA'), 1, 1, 'L');

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(160, 7, utf8_decode('PACIENTE REFERIDO A:'), 1, 0, 'C');
        $pdf->Cell(30, 7, utf8_decode('TELÉFONO'), 1, 1, 'C');

        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(160, 8, utf8_decode($sa_conp_permiso_seguro_traslado), 1, 0, 'C');
        $pdf->Cell(30, 8, utf8_decode($sa_conp_permiso_telefono_seguro), 1, 1, 'C');

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(47.5, 7, utf8_decode('TELÉFONO RESPONSABLE:'), 1, 0, 'C');
        $pdf->Cell(47.5, 7, utf8_decode('FECHA DE SALIDA:'), 1, 0, 'C');
        $pdf->Cell(47.5, 7, utf8_decode('HORA DE SALIDA'), 1, 0, 'C');
        $pdf->Cell(47.5, 7, utf8_decode('TIPO DE SALIDA'), 1, 1, 'C');

        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(47.5, 8, utf8_decode($sa_conp_permiso_telefono_padre), 1, 0, 'C');
        $pdf->Cell(47.5, 8, ($sa_conp_hora_permiso_salida), 1, 0, 'C');
        $pdf->Cell(47.5, 8, ($sa_conp_fecha_permiso_salud_salida), 1, 0, 'C');
        $pdf->Cell(47.5, 8, utf8_decode(strtoupper($sa_conp_permiso_tipo)), 1, 1, 'C');


        $pdf->AddPage();

        /////////////////////////////////////////////////////////////////////////////////////////////
        //Ficha medica
        /////////////////////////////////////////////////////////////////////////////////////////////
        $pdf->ln('10');

        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(190, 10, utf8_decode('  INFORMACIÓN ADICIONAL'), 1, 1, 'L');

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(190, 7, utf8_decode('   1.- ¿Ha sido diagnosticado con alguna enfermedad?:'), 1, 1, 'L');
        $pdf->SetFont('Arial', '', 9);
        $pdf->MultiCell(190, 6, utf8_decode($sa_fice_pregunta_1_obs), 1);

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(190, 7, utf8_decode('   2.- ¿Tiene algún antecedente familiar de importancia?:'), 1, 1, 'L');
        $pdf->SetFont('Arial', '', 9);
        $pdf->MultiCell(190, 6, utf8_decode($sa_fice_pregunta_2_obs), 1);

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(190, 7, utf8_decode('   3.- ¿Ha sido sometido a cirugías previas?:'), 1, 1, 'L');
        $pdf->SetFont('Arial', '', 9);
        $pdf->MultiCell(190, 6, utf8_decode($sa_fice_pregunta_3_obs), 1);

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(190, 7, utf8_decode('   4.- ¿Tiene alergias?:'), 1, 1, 'L');
        $pdf->SetFont('Arial', '', 9);
        $pdf->MultiCell(190, 6, utf8_decode($sa_fice_pregunta_4_obs), 1);

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(190, 7, utf8_decode('   5.- ¿Qué medicamentos usa?:'), 1, 1, 'L');
        $pdf->SetFont('Arial', '', 9);
        $pdf->MultiCell(190, 6, utf8_decode($sa_fice_pregunta_5_obs), 1);

        /////////////////////////////////////////////////////////////////////////////////////////////



        $pdf->ln('8');

        $pdf->SetFont('Arial', '', 9);




        $nombre_medico = ' Md. Camila López';

        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(60, 10, utf8_decode($nombre_medico), '0', 1, 'C');
        $pdf->SetFont('Arial', '', 12);

        $pdf->Cell(60, 10, utf8_decode('Médico Institucional'), '0', 0, 'C');

        $pdf->Output();
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
