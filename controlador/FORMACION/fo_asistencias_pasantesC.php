<?php
date_default_timezone_set('America/Bogota');

require_once(dirname(__DIR__, 2) . '/modelo/FORMACION/fo_asistencias_pasantesM.php');
require_once(dirname(__DIR__, 2) . '/lib/pdf/fpdf.php');



$controlador = new fo_asistencias_pasantesC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar($_POST['id'] ?? '', $_POST['modal'] ?? '', $_POST['registro_id'] ?? ''));
}

if (isset($_GET['insertar'])) {
    echo json_encode($controlador->insertar_editar($_POST['parametros']));
}

if (isset($_GET['eliminar'])) {
    echo json_encode($controlador->eliminar($_POST['id']));
}

if (isset($_GET['editar'])) {
    echo json_encode($controlador->editar($_POST['parametros']));
}

if (isset($_GET['editar_tutor'])) {
    echo json_encode($controlador->editar_tutor($_POST['parametros']));
}

if (isset($_GET['pdf_asistencias'])) {
    echo ($controlador->pdf_asistencias($_GET['id']));
}





class fo_asistencias_pasantesC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new fo_asistencias_pasantesM();
    }

    function listar($id = '', $modal = 0, $registro_id)
    {
        if ($modal == 1) {
            //print_r($id); exit();
            return $datos = $this->modelo->where('fo_pas_id', $id)->listar();
        }

        if ($id != '') {
            $datos = $this->modelo->where('fo_per_id', $id)->listar();
        } else {
            $datos = $this->modelo->listar();
        }

        if ($registro_id != '') {
            $datos = $this->modelo->where('fo_pas_id', $registro_id)->listar();
        }



        // if ($id == '') {
        //     if ($_SESSION['INICIO']['ID_USUARIO'] == 1) {
        //         $datos = $this->modelo->listar();
        //     } else {
        //         $datos = $this->modelo->where('fo_per_id', $_SESSION['INICIO']['ID_USUARIO'])->listar();
        //     }
        // } else {
        //     $datos = $this->modelo->where('fo_per_id', $_SESSION['INICIO']['ID_USUARIO'])->where('fo_pas_id', $id)->listar();
        // }

        return $datos;
    }

    //Para llegada
    function insertar_editar($parametros)
    {
        //print_r($parametros);exit;
        $txt_obs_tutor = isset($parametros['txt_obs_tutor']) ? $parametros['txt_obs_tutor'] : '';

        $datos = array(
            array('campo' => 'fo_per_id', 'dato' => intval($parametros['id_persona'])),
            array('campo' => 'fo_pas_observacion_pasante', 'dato' => $parametros['txt_obs_pasantes']),
            //array('campo' => 'fo_pas_observacion_tutor', 'dato' => $txt_obs_tutor),
        );

        $datos = $this->modelo->insertar($datos);
        return $datos;
    }

    //Para salida
    function editar($parametros)
    {
        //tomar la hora del sistema

        $hora_del_sistema = new DateTime();
        $hora_del_sistema = $hora_del_sistema->format('Y-d-m H:i:s');

        //print_r($hora_del_sistema); exit();

        $txt_obs_tutor = isset($parametros['txt_obs_tutor']) ? $parametros['txt_obs_tutor'] : '.';

        $datos = array(
            array('campo' => 'fo_pas_observacion_pasante', 'dato' => $parametros['txt_obs_pasantes']),
            array('campo' => 'fo_pas_observacion_tutor', 'dato' => $txt_obs_tutor),
            array('campo' => 'fo_pas_hora_salida', 'dato' => date('Y-m-d H:i:s')),
            // array('campo' => 'fo_pas_tutor_estado', 'dato' => $parametros['ddl_sexo']),
        );



        $where[0]['campo'] = 'fo_pas_id';
        $where[0]['dato'] = $parametros['registro_id'];
        $datos = $this->modelo->editar($datos, $where);

        ////////////////////////////////////////////////
        //Para calcular total de horas
        //LLamar el registro 
        $datos = $this->modelo->where('fo_pas_id', $parametros['registro_id'])->listar();

        $fo_pas_hora_llegada = $datos[0]['hora_llegada'];
        $fo_pas_hora_salida = $datos[0]['hora_salida'];

        $fo_pas_hora_llegada = new DateTime($fo_pas_hora_llegada);
        $fo_pas_hora_salida = new DateTime($fo_pas_hora_salida);

        // Calcular la diferencia
        $diferencia = $fo_pas_hora_salida->diff($fo_pas_hora_llegada);

        $horas_totales = $diferencia->h + ($diferencia->i / 60);

        $calcular_total = number_format($horas_totales, 2);

        $datos = array(
            array('campo' => 'fo_pas_horas_total', 'dato' => $calcular_total),
        );

        $where[0]['campo'] = 'fo_pas_id';
        $where[0]['dato'] = $parametros['registro_id'];
        $datos = $this->modelo->editar($datos, $where);

        return $datos;

        //return $parametros;
    }

    function editar_tutor($parametros)
    {
        $txt_obs_tutor = isset($parametros['txt_obs_tutor']) ? $parametros['txt_obs_tutor'] : '.';

        $datos = array(
            array('campo' => 'fo_pas_observacion_tutor', 'dato' => $txt_obs_tutor),
            array('campo' => 'fo_pas_tutor_estado', 'dato' => 1),
        );

        $where[0]['campo'] = 'fo_pas_id';
        $where[0]['dato'] = $parametros['txt_id_registro'];
        $datos = $this->modelo->editar($datos, $where);

        return $datos;
    }

    function eliminar($id)
    {
        $datos = array(
            array('campo' => 'pac_estado', 'dato' => 0),
        );

        $where[0]['campo'] = 'pac_id';
        $where[0]['dato'] = $id;

        $datos = $this->modelo->editar($datos, $where);
        return $datos;
    }

    function pdf_asistencias($id)
    {

        //$datos = $this->modelo->where('in_per_id', $id)->listar();

        $pdf = new FPDF('P', 'mm', 'A4');
        $pdf->SetMargins(10, 15, 10);
        $pdf->SetAutoPageBreak(true, 15);
        $pdf->setFillColor(249, 254, 247);
        $pdf->AliasNbPages();
        $pdf->AddPage();

        function CheckBox($pdf, $x, $y, $checked = false)
        {
            $pdf->SetDrawColor(0);
            $pdf->Rect($x, $y, 3, 3);
            if ($checked) {
                $pdf->Line($x, $y, $x + 3, $y + 3);
                $pdf->Line($x, $y + 3, $x + 3, $y);
            }
        }

        $in_stc_nombre_estudiante = '';


        $pdf->SetFont('Arial', '', 11);

        $pdf->SetFont('Arial', 'B', 22);
        $pdf->Cell(18);
        $pdf->Cell(0, 15, 'ASISTENCIAS DEL ESTUDIANTE', 0, 1, 'J');
        $pdf->Cell(25);
        $pdf->SetFont('Arial', 'B', 20);
        $pdf->Cell(0, 3, 'EJEMPLO', 0, 1, 'J');

        
        $pdf->Cell(120);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(120);
        $pdf->Cell(70, 0, 'University of Idaho', 0, 1, 'R');
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(120);
        $pdf->Cell(70, 3, 'Office of the Registrar', 0, 1, 'R');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(120);
        $pdf->Cell(70, 3, 'Phone: (208) 885-6731', 0, 1, 'R');
        $pdf->Cell(120);
        $pdf->Cell(70, 3, 'Fax: (208) 885-9061', 0, 1, 'R');
        $pdf->SetTextColor(0, 113, 255);
        $pdf->SetFont('Arial', 'U', 8);
        $pdf->Cell(120);
        $pdf->Cell(70, 2, 'registrarforms@uidaho.edu', 0, 1, 'R');

        $pdf->SetTextColor(0, 0, 0);

        $nombre_parts = explode(' ', $in_stc_nombre_estudiante);

        $first_name = isset($nombre_parts[0]) ? $nombre_parts[0] : '';
        $middle_name = isset($nombre_parts[1]) ? $nombre_parts[1] : '';
        $last_name = isset($nombre_parts[2]) ? $nombre_parts[2] : '';

        if (count($nombre_parts) > 3) {
            $last_name = implode(' ', array_slice($nombre_parts, 2));
        }

        $pdf->Ln(5);
        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(10, 5, 'Student:', 0, 0);
        $pdf->SetFont('Arial', '', 11);

        $pdf->Cell(10, 2, '', 0, 0);
        $pdf->Cell(35, 5, utf8_decode($first_name), 'B', 0, 'L', true);
        $pdf->Cell(35, 5, utf8_decode($middle_name), 'B', 0, 'L', true);
        $pdf->Cell(35, 5, utf8_decode($last_name), 'B', 0, 'L', true);

        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(17, 2, '', 0, 0);
        $pdf->Cell(22, 5, 'Student ID:', 0, 0);
        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(3, 2, '', 0, 0);
        $pdf->Cell(22, 5, utf8_decode(1), 'B', 0, 'L', true);

        $pdf->Ln(4);

        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(8, 20, '', 0, 0);
        $pdf->Cell(35, 10, 'First', 0, 0, 'C');
        $pdf->Cell(35, 10, 'Middle', 0, 0, 'C');
        $pdf->Cell(35, 10, 'Last', 0, 0, 'C');
        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(30, 2, '', 0, 0);
        $pdf->Cell(20, 12, 'Birth Date:', 0, 0);
        $pdf->Ln(3);
        $pdf->Cell(167, 2, '', 0, 0);
        $pdf->Cell(22, 5, utf8_decode('prueba'), 'B', 0, 'L', true);

        $pdf->setFillColor(244, 246, 255);

        $pdf->Ln(10);

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->MultiCell(0, 5, 'I hereby authorize the University of Idaho to discuss and verbally release the following information:', 0, 'L');

       
        $pdf->MultiCell(0, 6, utf8_decode('         I request to REMOVE my consent allowing UI to discuss and verbally release information to all currently designated individuals.***'), 1, 'J');

        $pdf->Ln(6);

        $pdf->SetFont('Arial', 'B', 11);
        $pdf->MultiCell(0, 5, 'I give consent for the following individual(s) to obtain the authorized information on request', 0, 'L');
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(0, 5, utf8_decode("(all information required):"), 0, 0);

        $pdf->Ln(6);

 

     

        $pdf->Output();
    }
}
