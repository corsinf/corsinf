<?php
date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 2) . '/modelo/INNOVERS/in_student_consentM.php');
require_once(dirname(__DIR__, 2) . '/modelo/INNOVERS/in_personasM.php');

require_once(dirname(__DIR__, 2) . '/lib/pdf/fpdf.php');
require_once(dirname(__DIR__, 2) . '/lib/TCPDF/tcpdf.php');


$controlador = new student_consentC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar($_POST['id']));
}

if (isset($_GET['insertar'])) {
    echo json_encode($controlador->insertar_editar($_POST['parametros']));
}

if (isset($_GET['eliminar'])) {
    echo json_encode($controlador->eliminar($_POST['id']));
}

if (isset($_GET['pdf_studentconsent'])) {
    echo ($controlador->pdf_studentconsent($_GET['id']));
}



class student_consentC
{
    private $modelo;
    private $persona;

    function __construct()
    {
        $this->modelo = new student_consentM();
    }

    function listar($id)
    {
        $datos = $this->modelo->where('in_per_id', $id)->where('in_stc_estado', '1')->listar();
        return $datos;
    }

    function insertar_editar($parametros)
    {
        $hora_del_sistema = new DateTime();
        $hora_del_sistema = $hora_del_sistema->format('Y-d-m H:i:s');

        $in_stc_cbx_academic_all = (isset($parametros['cbx_academic_info']) && $parametros['cbx_academic_info'] == 'true') ? 1 : 0;
        $in_stc_cbx_academic_1 = (isset($parametros['cbx_admission']) && $parametros['cbx_admission'] == 'true') ? 1 : 0;
        $in_stc_cbx_academic_2 = (isset($parametros['cbx_registration']) && $parametros['cbx_registration'] == 'true') ? 1 : 0;
        $in_stc_cbx_academic_3 = (isset($parametros['cbx_grades']) && $parametros['cbx_grades'] == 'true') ? 1 : 0;
        $in_stc_cbx_academic_4 = (isset($parametros['cbx_gpa']) && $parametros['cbx_gpa'] == 'true') ? 1 : 0;
        $in_stc_cbx_academic_5 = (isset($parametros['cbx_standing']) && $parametros['cbx_standing'] == 'true') ? 1 : 0;
        $in_stc_cbx_academic_6 = (isset($parametros['cbx_graduation']) && $parametros['cbx_graduation'] == 'true') ? 1 : 0;
        $in_stc_cbx_financial_all = (isset($parametros['cbx_financial_info']) && $parametros['cbx_financial_info'] == 'true') ? 1 : 0;
        $in_stc_cbx_financial_1 = (isset($parametros['cbx_fees']) && $parametros['cbx_fees'] == 'true') ? 1 : 0;
        $in_stc_cbx_financial_2 = (isset($parametros['cbx_charges']) && $parametros['cbx_charges'] == 'true') ? 1 : 0;
        $in_stc_cbx_financial_3 = (isset($parametros['cbx_payments']) && $parametros['cbx_payments'] == 'true') ? 1 : 0;
        $in_stc_cbx_aid_financial = (isset($parametros['cbx_aid_info']) && $parametros['cbx_aid_info'] == 'true') ? 1 : 0;
        $in_stc_cbx_housing_all = (isset($parametros['cbx_housing_info']) && $parametros['cbx_housing_info'] == 'true') ? 1 : 0;
        $in_stc_cbx_housing_1 = (isset($parametros['cbx_location']) && $parametros['cbx_location'] == 'true') ? 1 : 0;
        $in_stc_cbx_housing_2 = (isset($parametros['cbx_room']) && $parametros['cbx_room'] == 'true') ? 1 : 0;
        $in_stc_cbx_housing_3 = (isset($parametros['cbx_judicial']) && $parametros['cbx_judicial'] == 'true') ? 1 : 0;
        $in_stc_cbx_remove_consent = (isset($parametros['cbx_remove_consent']) && $parametros['cbx_remove_consent'] == 'true') ? 1 : 0;

        //$ip_publica_get = file_get_contents('https://api.ipify.org');

        $ip_cliente_host = $_SERVER['REMOTE_ADDR'] ?? '';

        if($ip_cliente_host != ''){
            $ip_cliente_host = gethostbyaddr($ip_cliente_host);
        }

        $datos = array(
            array('campo' => 'in_stc_nombre_estudiante', 'dato' => $parametros['txt_student']),
            array('campo' => 'in_per_id', 'dato' => $parametros['id_persona']),
            array('campo' => 'in_stc_fecha_nacimiento', 'dato' => $parametros['txt_birth_date']),
            array('campo' => 'in_stc_proposito_autorizacion', 'dato' => $parametros['txt_purpose_authorization']),
            array('campo' => 'in_stc_primer_nombre_autorizado', 'dato' => $parametros['txt_first_authorized_name']),
            array('campo' => 'in_stc_primer_relacion_autorizada', 'dato' => $parametros['txt_first_relationship']),
            array('campo' => 'in_stc_primera_direccion_autorizada', 'dato' => $parametros['txt_first_address']),
            array('campo' => 'in_stc_primer_email_autorizado', 'dato' => $parametros['txt_first_email']),
            array('campo' => 'in_stc_segundo_nombre_autorizado', 'dato' => $parametros['txt_second_authorized_name']),
            array('campo' => 'in_stc_segunda_relacion_autorizada', 'dato' => $parametros['txt_second_relationship']),
            array('campo' => 'in_stc_segunda_direccion_autorizada', 'dato' => $parametros['txt_second_address']),
            array('campo' => 'in_stc_segundo_email_autorizado', 'dato' => $parametros['txt_second_email']),
            array('campo' => 'in_stc_firma_estudiante', 'dato' => $parametros['txt_firma_estudiante']),
            array('campo' => 'in_stc_fecha_firma', 'dato' => $hora_del_sistema),
            array('campo' => 'in_stc_nombre_registro', 'dato' => $parametros['txt_cedula']),
            array('campo' => 'in_stc_fecha_registro', 'dato' => $hora_del_sistema),
            array('campo' => 'in_stc_cbx_academic_all', 'dato' => $in_stc_cbx_academic_all),
            array('campo' => 'in_stc_cbx_academic_1', 'dato' => $in_stc_cbx_academic_1),
            array('campo' => 'in_stc_cbx_academic_2', 'dato' => $in_stc_cbx_academic_2),
            array('campo' => 'in_stc_cbx_academic_3', 'dato' => $in_stc_cbx_academic_3),
            array('campo' => 'in_stc_cbx_academic_4', 'dato' => $in_stc_cbx_academic_4),
            array('campo' => 'in_stc_cbx_academic_5', 'dato' => $in_stc_cbx_academic_5),
            array('campo' => 'in_stc_cbx_academic_6', 'dato' => $in_stc_cbx_academic_6),
            array('campo' => 'in_stc_cbx_financial_all', 'dato' => $in_stc_cbx_financial_all),
            array('campo' => 'in_stc_cbx_financial_1', 'dato' => $in_stc_cbx_financial_1),
            array('campo' => 'in_stc_cbx_financial_2', 'dato' => $in_stc_cbx_financial_2),
            array('campo' => 'in_stc_cbx_financial_3', 'dato' => $in_stc_cbx_financial_3),
            array('campo' => 'in_stc_cbx_aid_financial', 'dato' => $in_stc_cbx_aid_financial),
            array('campo' => 'in_stc_cbx_housing_all', 'dato' => $in_stc_cbx_housing_all),
            array('campo' => 'in_stc_cbx_housing_1', 'dato' => $in_stc_cbx_housing_1),
            array('campo' => 'in_stc_cbx_housing_2', 'dato' => $in_stc_cbx_housing_2),
            array('campo' => 'in_stc_cbx_housing_3', 'dato' => $in_stc_cbx_housing_3),
            array('campo' => 'in_stc_cbx_remove_consent', 'dato' => $in_stc_cbx_remove_consent),
            array('campo' => 'in_stc_fecha_modificacion', 'dato' => date('Y-m-d H:i:s')),

            array('campo' => 'in_stc_ip_publica', 'dato' =>  '$ip_publica_get'),
            array('campo' => 'SERVER_NAME', 'dato' => $_SERVER['SERVER_NAME'] ?? ''),
            array('campo' => 'SERVER_SOFTWARE', 'dato' => $_SERVER['SERVER_SOFTWARE'] ?? ''),
            array('campo' => 'SERVER_PROTOCOL', 'dato' => $_SERVER['SERVER_PROTOCOL'] ?? ''),
            array('campo' => 'SERVER_PORT', 'dato' => $_SERVER['SERVER_PORT'] ?? ''),
            array('campo' => 'HTTP_HOST', 'dato' => $_SERVER['HTTP_HOST'] ?? ''),
            array('campo' => 'REMOTE_ADDR', 'dato' => $_SERVER['REMOTE_ADDR'] ?? ''),
            array('campo' => 'HTTP_USER_AGENT', 'dato' => $_SERVER['HTTP_USER_AGENT'] ?? ''),
            array('campo' => 'REQUEST_METHOD', 'dato' => $_SERVER['REQUEST_METHOD'] ?? ''),
            array('campo' => 'REQUEST_URI', 'dato' => $_SERVER['REQUEST_URI'] ?? ''),
            array('campo' => 'HOST_CLIENTE', 'dato' => $ip_cliente_host ?? ''),
            array('campo' => 'HTTP_X_FORWARDED_FOR', 'dato' => $_SERVER['HTTP_X_FORWARDED_FOR'] ?? ''),
        );

        if (count($this->modelo->where('in_per_id', $parametros['id_persona'])->listar()) == 0) {
            $datos = $this->modelo->insertar($datos);
        } else {
            $where[0]['campo'] = 'in_per_id';
            $where[0]['dato'] = $parametros['id_persona'];
            $datos = $this->modelo->editar($datos, $where);
        }

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


    function pdf_studentconsent_fpdf($id)
    {

        $datos = $this->modelo->where('in_per_id', $id)->listar();
        //print_r($datos); die(); exit;
        $in_stc_nombre_estudiante = $datos[0]['nombre_estudiante'];
        $in_per_id = $datos[0]['id_persona'];
        $in_stc_fecha_nacimiento = $datos[0]['fecha_nacimiento'];
        $in_stc_proposito_autorizacion = $datos[0]['proposito_autorizacion'];
        $in_stc_primer_nombre_autorizado = $datos[0]['primer_nombre_autorizado'];
        $in_stc_primer_relacion_autorizada = $datos[0]['primer_relacion_autorizada'];
        $in_stc_primera_direccion_autorizada = $datos[0]['primera_direccion_autorizada'];
        $in_stc_primer_email_autorizado = $datos[0]['primer_email_autorizado'];
        $in_stc_segundo_nombre_autorizado = $datos[0]['segundo_nombre_autorizado'];
        $in_stc_segunda_relacion_autorizada = $datos[0]['segunda_relacion_autorizada'];
        $in_stc_segunda_direccion_autorizada = $datos[0]['segunda_direccion_autorizada'];
        $in_stc_segundo_email_autorizado = $datos[0]['segundo_email_autorizado'];
        $in_stc_firma_estudiante = $datos[0]['firma_estudiante'];
        $in_stc_fecha_firma = $datos[0]['fecha_firma'];
        $in_stc_nombre_registro = $datos[0]['nombre_registro'];
        $in_stc_fecha_registro = $datos[0]['fecha_registro'];
        $in_stc_cbx_academic_all = $datos[0]['cbx_academic_all'] == '1' ? true : false;
        $in_stc_cbx_academic_1 = ($datos[0]['cbx_academic_1']) == '1' ? true : false;
        $in_stc_cbx_academic_2 = ($datos[0]['cbx_academic_2']) == '1' ? true : false;
        $in_stc_cbx_academic_3 = ($datos[0]['cbx_academic_3']) == '1' ? true : false;
        $in_stc_cbx_academic_4 = ($datos[0]['cbx_academic_4']) == '1' ? true : false;
        $in_stc_cbx_academic_5 = ($datos[0]['cbx_academic_5']) == '1' ? true : false;
        $in_stc_cbx_academic_6 = ($datos[0]['cbx_academic_6']) == '1' ? true : false;
        $in_stc_cbx_financial_all = ($datos[0]['cbx_financial_all']) == '1' ? true : false;
        $in_stc_cbx_financial_1 = ($datos[0]['cbx_financial_1']) == '1' ? true : false;
        $in_stc_cbx_financial_2 = ($datos[0]['cbx_financial_2']) == '1' ? true : false;
        $in_stc_cbx_financial_3 = ($datos[0]['cbx_financial_3']) == '1' ? true : false;
        $in_stc_cbx_aid_financial = ($datos[0]['cbx_aid_financial']) == '1' ? true : false;
        $in_stc_cbx_housing_all = ($datos[0]['cbx_housing_all']) == '1' ? true : false;
        $in_stc_cbx_housing_1 = ($datos[0]['cbx_housing_1']) == '1' ? true : false;
        $in_stc_cbx_housing_2 = ($datos[0]['cbx_housing_2']) == '1' ? true : false;
        $in_stc_cbx_housing_3 = ($datos[0]['cbx_housing_3']) == '1' ? true : false;
        $in_stc_cbx_remove_consent = ($datos[0]['cbx_remove_consent']) == '1' ? true : false;

        $pdf = new FPDF('P', 'mm', 'A4');
        $pdf->SetMargins(10, 15, 10);
        $pdf->SetAutoPageBreak(true, 15);
        $pdf->setFillColor(249, 254, 247);
        $pdf->AliasNbPages();
        $pdf->AddPage();

        function CheckBox_1($pdf, $x, $y, $checked = false)
        {
            $pdf->SetDrawColor(0);
            $pdf->Rect($x, $y, 3, 3);
            if ($checked) {
                $pdf->Line($x, $y, $x + 3, $y + 3);
                $pdf->Line($x, $y + 3, $x + 3, $y);
            }
        }


        $pdf->SetFont('Arial', '', 11);

        $pdf->SetFont('Arial', 'B', 22);
        $pdf->Cell(18);
        $pdf->Cell(0, 15, 'CONSENT FOR RELEASE', 0, 1, 'J');
        $pdf->Cell(25);
        $pdf->SetFont('Arial', 'B', 20);
        $pdf->Cell(0, 3, 'of Student Information', 0, 1, 'J');

        $pdf->Image(('../../img/formularios/logo.png'), 146, 12, 60);
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
        $pdf->Cell(22, 5, utf8_decode($in_per_id), 'B', 0, 'L', true);

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
        $pdf->Cell(22, 5, utf8_decode($in_stc_fecha_nacimiento), 'B', 0, 'L', true);

        $pdf->setFillColor(244, 246, 255);

        $pdf->Ln(10);

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->MultiCell(0, 5, 'I hereby authorize the University of Idaho to discuss and verbally release the following information:', 0, 'L');

        $pdf->Ln(5);

        $pdf->SetFont('Arial', '', 10);
        // Checkboxes
        $y = $pdf->GetY();
        CheckBox($pdf, 12, $y, $in_stc_cbx_academic_all);
        $pdf->Cell(6);

        // Establece la posición inicial
        $start_x = $pdf->GetX();
        $start_y = $pdf->GetY();

        // Primera parte en negrita
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell($pdf->GetStringWidth('ALL'), 5, 'ALL', 0, 0);

        // Segunda parte normal
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell($pdf->GetStringWidth(' academic information '), 5, ' academic information ', 0, 0);

        // Tercera parte en negrita
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell($pdf->GetStringWidth('OR'), 5, 'OR', 0, 0);

        // Cuarta parte normal
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(0, 5, ' these individual items:', 0, 1);

        $pdf->Ln(2);
        $y = $pdf->GetY();
        CheckBox($pdf, 15, $y, $in_stc_cbx_academic_1);
        $pdf->Cell(10);
        $pdf->Cell(30, 5, 'Admission', 0, 0);
        CheckBox($pdf, 50, $y, $in_stc_cbx_academic_2);
        $pdf->Cell(5);
        $pdf->Cell(40, 5, 'Registration/Enrollment', 0, 0);
        CheckBox($pdf, 100, $y, $in_stc_cbx_academic_3);
        $pdf->Cell(10);
        $pdf->Cell(30, 5, 'Grades', 0, 1);

        $y = $pdf->GetY();
        CheckBox($pdf, 15, $y, $in_stc_cbx_academic_4);
        $pdf->Cell(10);
        $pdf->Cell(30, 5, 'GPA', 0, 0);
        CheckBox($pdf, 50, $y, $in_stc_cbx_academic_5);
        $pdf->Cell(5);
        $pdf->Cell(40, 5, 'Academic Standing', 0, 0);
        CheckBox($pdf, 100, $y, $in_stc_cbx_academic_6);
        $pdf->Cell(10);
        $pdf->Cell(30, 5, 'Graduation', 0, 1);

        $pdf->Ln(4);

        $y = $pdf->GetY();
        CheckBox($pdf, 12, $y, $in_stc_cbx_financial_all);
        $pdf->Cell(6);

        // Establece la posición inicial
        $start_x = $pdf->GetX();
        $start_y = $pdf->GetY();

        // Primera parte en negrita
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell($pdf->GetStringWidth('ALL'), 5, 'ALL', 0, 0);

        // Segunda parte normal
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell($pdf->GetStringWidth(' financial account information '), 5, ' financial account information ', 0, 0);

        // Tercera parte en negrita
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell($pdf->GetStringWidth('OR'), 5, 'OR', 0, 0);

        // Cuarta parte normal
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(0, 5, ' these individual items:', 0, 1);

        $pdf->Ln(2);

        $y = $pdf->GetY();
        CheckBox($pdf, 15, $y, $in_stc_cbx_financial_1);
        $pdf->Cell(10);
        $pdf->Cell(30, 5, 'Fees', 0, 0);
        CheckBox($pdf, 50, $y, $in_stc_cbx_financial_2);
        $pdf->Cell(5);
        $pdf->Cell(40, 5, 'Charges', 0, 0);
        CheckBox($pdf, 100, $y, $in_stc_cbx_financial_3);
        $pdf->Cell(10);
        $pdf->Cell(30, 5, 'Payments', 0, 1);

        $pdf->Ln(4);

        $y = $pdf->GetY();
        CheckBox($pdf, 12, $y, $in_stc_cbx_aid_financial);
        $pdf->Cell(6);

        // Posición inicial
        $start_x = $pdf->GetX();
        $start_y = $pdf->GetY();

        // Primera parte en negrita
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell($pdf->GetStringWidth('ALL'), 5, 'ALL', 0, 0);

        // Segunda parte normal
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(0, 5, ' financial aid information', 0, 1);

        $pdf->Ln(4);

        $y = $pdf->GetY();
        CheckBox($pdf, 12, $y, $in_stc_cbx_housing_all);
        $pdf->Cell(6);

        // Posición inicial
        $start_x = $pdf->GetX();
        $start_y = $pdf->GetY();

        // Primera parte en negrita
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell($pdf->GetStringWidth('ALL'), 5, 'ALL', 0, 0);

        // Segunda parte normal
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell($pdf->GetStringWidth('  university housing information '), 5, '  university housing information ', 0, 0);

        // Tercera parte en negrita
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell($pdf->GetStringWidth('OR'), 5, 'OR', 0, 0);

        // Cuarta parte normal
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(0, 5, ' these individual items:', 0, 1);

        $pdf->Ln(2);
        $y = $pdf->GetY();
        CheckBox($pdf, 15, $y, $in_stc_cbx_housing_1);
        $pdf->Cell(10);
        $pdf->Cell(30, 5, 'Location', 0, 0);
        CheckBox($pdf, 50, $y, $in_stc_cbx_housing_2);
        $pdf->Cell(5);
        $pdf->Cell(40, 5, 'Room Assignment', 0, 0);
        CheckBox($pdf, 100, $y, $in_stc_cbx_housing_3);
        $pdf->Cell(10);
        $pdf->Cell(30, 5, 'Judicial Matters', 0, 1);



        $pdf->Ln(3);

        $pdf->SetFont('Arial', 'B', 11);
        $pdf->Cell(73, 8, 'My authorization is for the following purpose:', 0, 1);
        $pdf->SetFont('Arial', '', 11);
        $pdf->MultiCell(190, 5, utf8_decode($in_stc_proposito_autorizacion), 0, 'J');

        $pdf->Ln(5);

        $pdf->SetFont('Arial', 'B', 10);

        $y = $pdf->GetY();
        $pdf->Cell(2);
        $pdf->Cell(0, 6, '***', 0, 'J');
        CheckBox($pdf, 19, $y + 1.2, $in_stc_cbx_remove_consent);
        $pdf->SetFont('Arial', '', 10);
        $pdf->SetXY(10, $y);
        $pdf->MultiCell(0, 6, utf8_decode('         I request to REMOVE my consent allowing UI to discuss and verbally release information to all currently designated individuals.***'), 1, 'J');

        $pdf->Ln(6);

        $pdf->SetFont('Arial', 'B', 11);
        $pdf->MultiCell(0, 5, 'I give consent for the following individual(s) to obtain the authorized information on request', 0, 'L');
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(0, 5, utf8_decode("(all information required):"), 0, 0);

        $pdf->Ln(6);

        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(5, 5, '1.', 0, 0);
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(3, 2, '', 0, 0);
        $pdf->Cell(100, 5, utf8_decode($in_stc_primer_nombre_autorizado), 'B', 0, 'L', true);
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(12, 2, '', 0, 0);
        $pdf->Cell(70, 5, utf8_decode($in_stc_primer_relacion_autorizada), 'B', 1, 'L', true);
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(53, 2, '', 0, 0);
        $pdf->Cell(10, 4, '(Printed Name)', 0, 0, 'C');
        $pdf->Cell(73, 2, '', 0, 0);
        $pdf->Cell(35, 4, '(Relationship to student)', 0, 1, 'C');
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(8, 2, '', 0, 0);
        $pdf->Cell(100, 5, utf8_decode($in_stc_primera_direccion_autorizada), 'B', 0, 'L', true);
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(12, 2, '', 0, 0);
        $pdf->Cell(70, 5, utf8_decode($in_stc_primer_email_autorizado), 'B', 1, 'L', true);
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(53, 2, '', 0, 0);
        $pdf->Cell(10, 4, '(Complete Adress)', 0, 0, 'C');
        $pdf->Cell(73, 2, '', 0, 0);
        $pdf->Cell(35, 4, '(Email)', 0, 1, 'C');

        $pdf->Ln(3);

        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(5, 5, '2.', 0, 0);
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(3, 2, '', 0, 0);
        $pdf->Cell(100, 5, utf8_decode($in_stc_segundo_nombre_autorizado), 'B', 0, 'L', true);
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(12, 2, '', 0, 0);
        $pdf->Cell(70, 5, utf8_decode($in_stc_segunda_relacion_autorizada), 'B', 1, 'L', true);
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(53, 2, '', 0, 0);
        $pdf->Cell(10, 4, '(Printed Name)', 0, 0, 'C');
        $pdf->Cell(73, 2, '', 0, 0);
        $pdf->Cell(35, 4, '(Relationship to student)', 0, 1, 'C');
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(8, 2, '', 0, 0);
        $pdf->Cell(100, 5, utf8_decode($in_stc_segunda_direccion_autorizada), 'B', 0, 'L', true);
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(12, 2, '', 0, 0);
        $pdf->Cell(70, 5, utf8_decode($in_stc_segundo_email_autorizado), 'B', 1, 'L', true);
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(53, 2, '', 0, 0);
        $pdf->Cell(10, 4, '(Complete Adress)', 0, 0, 'C');
        $pdf->Cell(73, 2, '', 0, 0);
        $pdf->Cell(35, 4, '(Email)', 0, 1, 'C');


        $pdf->Ln(5);

        $pdf->SetFont('Arial', '', 9);
        $pdf->MultiCell(0, 4, "I understand that this information is considered a student education, financial, and/or housing record. Further, I understand that by signing this release, I am waiving my right to keep this information confidential under the Family Educational Rights and Privacy Act (FERPA). I certify that my consent for disclosure of this information is entirely voluntary. I understand this consent for disclosure of information can be revoked by me in writing at any time, but will not affect information released prior to my revocation. I understand that if I want to make any changes to my consent for release, I will need to complete and file a new form. The authorization on this form will supersede all prior authorizations for release of my information.", 0, 'J');

        $pdf->Ln(5);

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(35, 5, "Student's Signature:", 0, 0);
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(3, 2, '', 0, 0);
        $pdf->Cell(80, 5, utf8_decode($in_stc_firma_estudiante), 'B', 0, 'L', true);
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(12, 2, '', 0, 0);
        $pdf->Cell(10, 5, 'Date:', 0, 0);
        $pdf->Cell(3, 2, '', 0, 0);
        $pdf->Cell(45, 5, utf8_decode($in_stc_fecha_firma), 'B', 1, 'L', true);

        $pdf->Ln(7);
        $xStart = 10;
        $yStart = $pdf->GetY() - 1;
        $yEnd = $yStart + 16;

        $xEnd = $pdf->GetPageWidth() - 10;
        $pdf->setFillColor(249, 254, 247);

        $pdf->Rect($xStart, $yStart, $xEnd - $xStart, $yEnd - $yStart, 'DF');

        $pdf->SetFont('Arial', 'B', 11);
        $pdf->Cell(3, 2, '', 0, 0);
        $pdf->Cell(0, 8, 'OFFICE USE ONLY', 0, 1, 'L');
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(3, 2, '', 0, 0);
        $pdf->Cell(20, 5, "Recorded by", 0, 0, '');
        $pdf->Cell(3, 2, '', 0, 0);
        $pdf->Cell(55, 5, utf8_decode($in_stc_nombre_registro), 'B', 0, 'L', true);
        $pdf->Cell(18, 2, '', 0, 0);
        $pdf->Cell(8, 5, 'Date:', 0, 0, '', 1);
        $pdf->Cell(3, 2, '', 0, 0);
        $pdf->Cell(45, 5, utf8_decode($in_stc_fecha_registro), 'B', 0, 'L', true);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(8, 2, '', 0, 0);
        $pdf->Cell(20, 5, 'Rev 12/18', 0, 0, 'R');

        $pdf->Output();
    }

    function pdf_studentconsent($id)
    {
        //Configuracion de PDF

        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

        // set document information
        $pdf->setCreator(PDF_CREATOR);
        $pdf->setAuthor('apudata');
        $pdf->setTitle('Student Consent');
        $pdf->setSubject('apudata');
        $pdf->setKeywords('apudata');

        // Añadir metadata personalizada extra
        $pdf->setHeaderData('', 0, 'Documento protegido', 'Cualquier modificación invalidará este documento.');
        //$pdf->setProtection(array('modify', 'annot-forms','copy'), '123', '123');


        // remove default header/footer
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        // set default monospaced font
        $pdf->setDefaultMonospacedFont('helvetica');

        // set margins
        $pdf->setMargins(10, 15, 10);

        // set auto page breaks
        $pdf->setAutoPageBreak(TRUE, 15);

        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        //$pdf->setFillColor(244, 246, 255);
        $pdf->setFillColor(222, 229, 255);

        // ---------------------------------------------------------

        // add a page
        $pdf->AddPage();

        $persona = new in_personasM();


        $datos = $this->modelo->where('in_per_id', $id)->listar();
        $persona = $persona->where('in_per_id', $id)->listar();
        $datos_privados = $this->modelo->listar_datos_privados($id);

        $primer_apellido = $persona[0]['primer_apellido'];
        $segundo_apellido = $persona[0]['segundo_apellido'];
        $primer_nombre = $persona[0]['primer_nombre'];
        $segundo_nombre = $persona[0]['segundo_nombre'];
        $cedula = $persona[0]['cedula'];
        $correo = $persona[0]['correo'];

        $ip_publica = $datos_privados[0]['REMOTE_ADDR'];


        //print_r($datos); die(); exit;
        $in_stc_nombre_estudiante = $datos[0]['nombre_estudiante'];
        $in_per_id = $datos[0]['id_persona'];
        $in_stc_fecha_nacimiento = $datos[0]['fecha_nacimiento'];
        if (!empty($in_stc_fecha_nacimiento)) {
            $in_stc_fecha_nacimiento = (new DateTime($in_stc_fecha_nacimiento))->format('d/m/Y');
        }
        $in_stc_proposito_autorizacion = $datos[0]['proposito_autorizacion'];
        $in_stc_primer_nombre_autorizado = $datos[0]['primer_nombre_autorizado'];
        $in_stc_primer_relacion_autorizada = $datos[0]['primer_relacion_autorizada'];
        $in_stc_primera_direccion_autorizada = $datos[0]['primera_direccion_autorizada'];
        $in_stc_primer_email_autorizado = $datos[0]['primer_email_autorizado'];
        $in_stc_segundo_nombre_autorizado = $datos[0]['segundo_nombre_autorizado'];
        $in_stc_segunda_relacion_autorizada = $datos[0]['segunda_relacion_autorizada'];
        $in_stc_segunda_direccion_autorizada = $datos[0]['segunda_direccion_autorizada'];
        $in_stc_segundo_email_autorizado = $datos[0]['segundo_email_autorizado'];
        $in_stc_firma_estudiante = $datos[0]['firma_estudiante'];
        $in_stc_fecha_firma = $datos[0]['fecha_firma'];
        if (!empty($in_stc_fecha_firma)) {
            $in_stc_fecha_firma = (new DateTime($in_stc_fecha_firma))->format('d/m/Y');
        }
        $in_stc_nombre_registro = $datos[0]['nombre_registro'];
        $in_stc_fecha_registro = $datos[0]['fecha_registro'];
        if (!empty($in_stc_fecha_registro)) {
            $in_stc_fecha_registro = (new DateTime($in_stc_fecha_registro))->format('d/m/Y');
        }
        $in_stc_cbx_academic_all = $datos[0]['cbx_academic_all'] == '1' ? true : false;
        $in_stc_cbx_academic_1 = ($datos[0]['cbx_academic_1']) == '1' ? true : false;
        $in_stc_cbx_academic_2 = ($datos[0]['cbx_academic_2']) == '1' ? true : false;
        $in_stc_cbx_academic_3 = ($datos[0]['cbx_academic_3']) == '1' ? true : false;
        $in_stc_cbx_academic_4 = ($datos[0]['cbx_academic_4']) == '1' ? true : false;
        $in_stc_cbx_academic_5 = ($datos[0]['cbx_academic_5']) == '1' ? true : false;
        $in_stc_cbx_academic_6 = ($datos[0]['cbx_academic_6']) == '1' ? true : false;
        $in_stc_cbx_financial_all = ($datos[0]['cbx_financial_all']) == '1' ? true : false;
        $in_stc_cbx_financial_1 = ($datos[0]['cbx_financial_1']) == '1' ? true : false;
        $in_stc_cbx_financial_2 = ($datos[0]['cbx_financial_2']) == '1' ? true : false;
        $in_stc_cbx_financial_3 = ($datos[0]['cbx_financial_3']) == '1' ? true : false;
        $in_stc_cbx_aid_financial = ($datos[0]['cbx_aid_financial']) == '1' ? true : false;
        $in_stc_cbx_housing_all = ($datos[0]['cbx_housing_all']) == '1' ? true : false;
        $in_stc_cbx_housing_1 = ($datos[0]['cbx_housing_1']) == '1' ? true : false;
        $in_stc_cbx_housing_2 = ($datos[0]['cbx_housing_2']) == '1' ? true : false;
        $in_stc_cbx_housing_3 = ($datos[0]['cbx_housing_3']) == '1' ? true : false;
        $in_stc_cbx_remove_consent = ($datos[0]['cbx_remove_consent']) == '1' ? true : false;


        function CheckBox($pdf, $x, $y, $checked = false)
        {
            $pdf->SetDrawColor(0);
            $pdf->Rect($x, $y, 3, 3);
            if ($checked) {
                $pdf->Line($x, $y, $x + 3, $y + 3);
                $pdf->Line($x, $y + 3, $x + 3, $y);
            }
        }


        $pdf->SetFont('helvetica', '', 11);

        $pdf->SetFont('helvetica', 'B', 22);
        $pdf->Cell(18, 3, '', 0, 0, 'R');
        $pdf->Cell(70, 10, 'CONSENT FOR RELEASE', 0, 1, 'L');

        $pdf->SetFont('helvetica', 'B', 20);
        $pdf->Cell(25, 3, '', 0, 0, 'R');
        $pdf->Cell(70, 3, 'of Student Information', 0, 0, 'L');

        $pdf->Image(('../../img/formularios/logo.png'), 146, 12, 60);

        // $pdf->SetFont('helvetica', '', 8);
        // $pdf->Cell(120, 3, '', 0, 0, 'R');
        // $pdf->Cell(70, 3, 'University of Idaha', 0, 1, 'R');

        $pdf->SetFont('helvetica', 'B', 8);
        $pdf->Cell(25, 3, '', 0, 0, 'R');
        $pdf->Cell(70, 3, 'Office of the Registrar', 0, 1, 'R');

        $pdf->SetFont('helvetica', '', 8);
        $pdf->Cell(120, 3, '', 0, 0, 'R');
        $pdf->Cell(70, 3, 'Phone: (208) 885-6731', 0, 1, 'R');

        $pdf->Cell(120, 3, '', 0, 0, 'R');
        $pdf->Cell(70, 3, 'Fax: (208) 885-9061', 0, 1, 'R');
        $pdf->SetTextColor(0, 113, 255);

        $pdf->SetFont('helvetica', 'U', 8);
        $pdf->Cell(120, 3, '', 0, 0, 'R');
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
        $pdf->SetFont('helvetica', '', 11);
        $pdf->Cell(10, 5, 'Student:', 0, 0);
        $pdf->SetFont('helvetica', '', 11);

        $pdf->Cell(10, 2, '', 0, 0);
        $pdf->Cell(35, 5, utf8_decode($first_name), 'B', 0, 'L', true);
        $pdf->Cell(35, 5, utf8_decode($middle_name), 'B', 0, 'L', true);
        $pdf->Cell(35, 5, utf8_decode($last_name), 'B', 0, 'L', true);

        $pdf->SetFont('helvetica', '', 11);
        $pdf->Cell(17, 2, '', 0, 0);
        $pdf->Cell(22, 5, 'Student ID:', 0, 0);
        $pdf->SetFont('helvetica', '', 11);
        $pdf->Cell(3, 2, '', 0, 0);
        $pdf->Cell(22, 5, utf8_decode($in_per_id), 'B', 0, 'L', true);

        $pdf->Ln(4);

        $pdf->SetFont('helvetica', '', 8);
        $pdf->Cell(8, 20, '', 0, 0);
        $pdf->Cell(35, 10, 'First', 0, 0, 'C');
        $pdf->Cell(35, 10, 'Middle', 0, 0, 'C');
        $pdf->Cell(35, 10, 'Last', 0, 0, 'C');
        $pdf->SetFont('helvetica', '', 11);
        $pdf->Cell(30, 2, '', 0, 0);
        $pdf->Cell(20, 12, 'Birth Date:', 0, 0);
        $pdf->Ln(3);
        $pdf->Cell(167, 2, '', 0, 0);

        $pdf->Cell(22, 5, utf8_decode($in_stc_fecha_nacimiento), 'B', 0, 'L', true);

        //$pdf->setFillColor(244, 246, 255);

        $pdf->Ln(10);

        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->MultiCell(0, 5, 'I hereby authorize the University of Idaho to discuss and verbally release the following information:', 0, 'L');

        $pdf->Ln(5);

        $pdf->SetFont('helvetica', '', 10);
        // Checkboxes
        $y = $pdf->GetY();
        CheckBox($pdf, 12, $y, $in_stc_cbx_academic_all);
        $pdf->Cell(6);

        // Establece la posición inicial
        $start_x = $pdf->GetX();
        $start_y = $pdf->GetY();

        // Primera parte en negrita
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->Cell($pdf->GetStringWidth('ALL'), 5, 'ALL', 0, 0);

        // Segunda parte normal
        $pdf->SetFont('helvetica', '', 10);
        $pdf->Cell($pdf->GetStringWidth(' academic information '), 5, ' academic information ', 0, 0);

        // Tercera parte en negrita
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->Cell($pdf->GetStringWidth('OR'), 5, 'OR', 0, 0);

        // Cuarta parte normal
        $pdf->SetFont('helvetica', '', 10);
        $pdf->Cell(0, 5, ' these individual items:', 0, 1);

        $pdf->Ln(2);
        $y = $pdf->GetY();
        CheckBox($pdf, 15, $y, $in_stc_cbx_academic_1);
        $pdf->Cell(10);
        $pdf->Cell(30, 5, 'Admission', 0, 0);
        CheckBox($pdf, 50, $y, $in_stc_cbx_academic_2);
        $pdf->Cell(5);
        $pdf->Cell(40, 5, 'Registration/Enrollment', 0, 0);
        CheckBox($pdf, 100, $y, $in_stc_cbx_academic_3);
        $pdf->Cell(10);
        $pdf->Cell(30, 5, 'Grades', 0, 1);

        $y = $pdf->GetY();
        CheckBox($pdf, 15, $y, $in_stc_cbx_academic_4);
        $pdf->Cell(10);
        $pdf->Cell(30, 5, 'GPA', 0, 0);
        CheckBox($pdf, 50, $y, $in_stc_cbx_academic_5);
        $pdf->Cell(5);
        $pdf->Cell(40, 5, 'Academic Standing', 0, 0);
        CheckBox($pdf, 100, $y, $in_stc_cbx_academic_6);
        $pdf->Cell(10);
        $pdf->Cell(30, 5, 'Graduation', 0, 1);

        $pdf->Ln(4);

        $y = $pdf->GetY();
        CheckBox($pdf, 12, $y, $in_stc_cbx_financial_all);
        $pdf->Cell(6);

        // Establece la posición inicial
        $start_x = $pdf->GetX();
        $start_y = $pdf->GetY();

        // Primera parte en negrita
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->Cell($pdf->GetStringWidth('ALL'), 5, 'ALL', 0, 0);

        // Segunda parte normal
        $pdf->SetFont('helvetica', '', 10);
        $pdf->Cell($pdf->GetStringWidth(' financial account information '), 5, ' financial account information ', 0, 0);

        // Tercera parte en negrita
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->Cell($pdf->GetStringWidth('OR'), 5, 'OR', 0, 0);

        // Cuarta parte normal
        $pdf->SetFont('helvetica', '', 10);
        $pdf->Cell(0, 5, ' these individual items:', 0, 1);

        $pdf->Ln(2);

        $y = $pdf->GetY();
        CheckBox($pdf, 15, $y, $in_stc_cbx_financial_1);
        $pdf->Cell(10);
        $pdf->Cell(30, 5, 'Fees', 0, 0);
        CheckBox($pdf, 50, $y, $in_stc_cbx_financial_2);
        $pdf->Cell(5);
        $pdf->Cell(40, 5, 'Charges', 0, 0);
        CheckBox($pdf, 100, $y, $in_stc_cbx_financial_3);
        $pdf->Cell(10);
        $pdf->Cell(30, 5, 'Payments', 0, 1);

        $pdf->Ln(4);

        $y = $pdf->GetY();
        CheckBox($pdf, 12, $y, $in_stc_cbx_aid_financial);
        $pdf->Cell(6);

        // Posición inicial
        $start_x = $pdf->GetX();
        $start_y = $pdf->GetY();

        // Primera parte en negrita
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->Cell($pdf->GetStringWidth('ALL'), 5, 'ALL', 0, 0);

        // Segunda parte normal
        $pdf->SetFont('helvetica', '', 10);
        $pdf->Cell(0, 5, ' financial aid information', 0, 1);

        $pdf->Ln(4);

        $y = $pdf->GetY();
        CheckBox($pdf, 12, $y, $in_stc_cbx_housing_all);
        $pdf->Cell(6);

        // Posición inicial
        $start_x = $pdf->GetX();
        $start_y = $pdf->GetY();

        // Primera parte en negrita
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->Cell($pdf->GetStringWidth('ALL'), 5, 'ALL', 0, 0);

        // Segunda parte normal
        $pdf->SetFont('helvetica', '', 10);
        $pdf->Cell($pdf->GetStringWidth('  university housing information '), 5, '  university housing information ', 0, 0);

        // Tercera parte en negrita
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->Cell($pdf->GetStringWidth('OR'), 5, 'OR', 0, 0);

        // Cuarta parte normal
        $pdf->SetFont('helvetica', '', 10);
        $pdf->Cell(0, 5, ' these individual items:', 0, 1);

        $pdf->Ln(2);
        $y = $pdf->GetY();
        CheckBox($pdf, 15, $y, $in_stc_cbx_housing_1);
        $pdf->Cell(10);
        $pdf->Cell(30, 5, 'Location', 0, 0);
        CheckBox($pdf, 50, $y, $in_stc_cbx_housing_2);
        $pdf->Cell(5);
        $pdf->Cell(40, 5, 'Room Assignment', 0, 0);
        CheckBox($pdf, 100, $y, $in_stc_cbx_housing_3);
        $pdf->Cell(10);
        $pdf->Cell(30, 5, 'Judicial Matters', 0, 1);



        $pdf->Ln(3);

        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->Cell(73, 8, 'My authorization is for the following purpose:', 0, 1);
        $pdf->SetFont('helvetica', '', 11);
        $pdf->MultiCell(190, 5, utf8_decode($in_stc_proposito_autorizacion), 0, 'J');

        $pdf->Ln(5);

        $pdf->SetFont('helvetica', 'B', 10);

        $y = $pdf->GetY();
        $pdf->Cell(2);
        $pdf->Cell(0, 6, '***', 0, 'J');
        CheckBox($pdf, 19, $y + 1.2, $in_stc_cbx_remove_consent);
        $pdf->SetFont('helvetica', '', 10);
        $pdf->SetXY(10, $y);
        $pdf->MultiCell(0, 6, utf8_decode('         I request to REMOVE my consent allowing UI to discuss and verbally release information to all currently designated individuals.***'), 1, 'J');

        $pdf->Ln(6);

        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->MultiCell(0, 5, 'I give consent for the following individual(s) to obtain the authorized information on request', 0, 'L');
        $pdf->SetFont('helvetica', '', 10);
        $pdf->Cell(0, 5, utf8_decode("(all information required):"), 0, 0);

        $pdf->Ln(6);

        $pdf->SetFont('helvetica', '', 10);
        $pdf->Cell(5, 5, '1.', 0, 0);
        $pdf->SetFont('helvetica', '', 10);
        $pdf->Cell(3, 2, '', 0, 0);
        $pdf->Cell(100, 5, utf8_decode($in_stc_primer_nombre_autorizado), 'B', 0, 'L', true);
        $pdf->SetFont('helvetica', '', 10);
        $pdf->Cell(12, 2, '', 0, 0);
        $pdf->Cell(70, 5, utf8_decode($in_stc_primer_relacion_autorizada), 'B', 1, 'L', true);
        $pdf->SetFont('helvetica', '', 7);
        $pdf->Cell(53, 2, '', 0, 0);
        $pdf->Cell(10, 4, '(Printed Name)', 0, 0, 'C');
        $pdf->Cell(73, 2, '', 0, 0);
        $pdf->Cell(35, 4, '(Relationship to student)', 0, 1, 'C');
        $pdf->SetFont('helvetica', '', 10);
        $pdf->Cell(8, 2, '', 0, 0);
        $pdf->Cell(100, 5, utf8_decode($in_stc_primera_direccion_autorizada), 'B', 0, 'L', true);
        $pdf->SetFont('helvetica', '', 10);
        $pdf->Cell(12, 2, '', 0, 0);
        $pdf->Cell(70, 5, utf8_decode($in_stc_primer_email_autorizado), 'B', 1, 'L', true);
        $pdf->SetFont('helvetica', '', 7);
        $pdf->Cell(53, 2, '', 0, 0);
        $pdf->Cell(10, 4, '(Complete Adress)', 0, 0, 'C');
        $pdf->Cell(73, 2, '', 0, 0);
        $pdf->Cell(35, 4, '(Email)', 0, 1, 'C');

        $pdf->Ln(3);

        $pdf->SetFont('helvetica', '', 10);
        $pdf->Cell(5, 5, '2.', 0, 0);
        $pdf->SetFont('helvetica', '', 10);
        $pdf->Cell(3, 2, '', 0, 0);
        $pdf->Cell(100, 5, utf8_decode($in_stc_segundo_nombre_autorizado), 'B', 0, 'L', true);
        $pdf->SetFont('helvetica', '', 10);
        $pdf->Cell(12, 2, '', 0, 0);
        $pdf->Cell(70, 5, utf8_decode($in_stc_segunda_relacion_autorizada), 'B', 1, 'L', true);
        $pdf->SetFont('helvetica', '', 7);
        $pdf->Cell(53, 2, '', 0, 0);
        $pdf->Cell(10, 4, '(Printed Name)', 0, 0, 'C');
        $pdf->Cell(73, 2, '', 0, 0);
        $pdf->Cell(35, 4, '(Relationship to student)', 0, 1, 'C');
        $pdf->SetFont('helvetica', '', 10);
        $pdf->Cell(8, 2, '', 0, 0);
        $pdf->Cell(100, 5, utf8_decode($in_stc_segunda_direccion_autorizada), 'B', 0, 'L', true);
        $pdf->SetFont('helvetica', '', 10);
        $pdf->Cell(12, 2, '', 0, 0);
        $pdf->Cell(70, 5, utf8_decode($in_stc_segundo_email_autorizado), 'B', 1, 'L', true);
        $pdf->SetFont('helvetica', '', 7);
        $pdf->Cell(53, 2, '', 0, 0);
        $pdf->Cell(10, 4, '(Complete Adress)', 0, 0, 'C');
        $pdf->Cell(73, 2, '', 0, 0);
        $pdf->Cell(35, 4, '(Email)', 0, 1, 'C');


        $pdf->Ln(5);

        $pdf->SetFont('helvetica', '', 9);
        $pdf->MultiCell(0, 4, "I understand that this information is considered a student education, financial, and/or housing record. Further, I understand that by signing this release, I am waiving my right to keep this information confidential under the Family Educational Rights and Privacy Act (FERPA). I certify that my consent for disclosure of this information is entirely voluntary. I understand this consent for disclosure of information can be revoked by me in writing at any time, but will not affect information released prior to my revocation. I understand that if I want to make any changes to my consent for release, I will need to complete and file a new form. The authorization on this form will supersede all prior authorizations for release of my information.", 0, 'J');

        $pdf->Ln(5);

        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->Cell(35, 5, "Student's Signature:", 0, 0);
        $pdf->SetFont('helvetica', '', 10);
        $pdf->Cell(3, 2, '', 0, 0);
        $pdf->Cell(80, 5, utf8_decode(''), '', 0, 'L');
        $pdf->SetFont('helvetica', '', 10);
        $pdf->Cell(12, 2, '', 0, 0);
        $pdf->Cell(10, 5, 'Date:', 0, 0);
        $pdf->Cell(3, 2, '', 0, 0);


        $pdf->Cell(45, 5, utf8_decode($in_stc_fecha_firma), 'B', 1, 'L', true);

        $pdf->Ln(7);
        $xStart = 10;
        $yStart = $pdf->GetY() - 1;
        $yEnd = $yStart + 16;

        $xEnd = $pdf->GetPageWidth() - 10;
        $pdf->setFillColor(242, 242, 242);


        $pdf->Rect($xStart, $yStart, $xEnd - $xStart, $yEnd - $yStart, 'DF');

        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->Cell(3, 2, '', 0, 0);
        $pdf->Cell(0, 8, 'OFFICE USE ONLY', 0, 1, 'L');
        $pdf->SetFont('helvetica', '', 10);
        $pdf->Cell(3, 2, '', 0, 0);
        $pdf->Cell(20, 5, "Recorded by", 0, 0, '');
        $pdf->Cell(3, 2, '', 0, 0);
        $pdf->Cell(55, 5, utf8_decode($in_stc_nombre_registro), 'B', 0, 'L', true);
        $pdf->Cell(18, 2, '', 0, 0);
        $pdf->Cell(8, 5, 'Date:', 0, 0, '', 1);
        $pdf->Cell(3, 2, '', 0, 0);


        $pdf->Cell(45, 5, utf8_decode($in_stc_fecha_registro), 'B', 0, 'L', true);
        $pdf->SetFont('helvetica', '', 8);
        $pdf->Cell(8, 2, '', 0, 0);
        $pdf->Cell(20, 5, 'Rev 12/18', 0, 0, 'R');


        if ($in_stc_firma_estudiante == 1) {
            /*
             *
             * Para firmar el documento
             *
             */


            $pdf->setFont('helvetica', '', 10);

            $data = $cedula;
            $encryptedData = openssl_encrypt($data, 'AES-128-CBC', 'clave_de_encriptacion', 0, '1234567891011121');

            $nombre = $primer_nombre . ' ' . $primer_apellido;
            //$cedula = '1004951668';
            $provincia = 'Pichincha';
            $ciudad = 'Quito';
            $parroquia = 'Cayambe';
            $organizacion = 'corsinf.com';
            //$correo = 'ruben.com';

            // $ip = $_SERVER['REMOTE_ADDR'];
            // $ip = file_get_contents('https://api.ipify.org');

            $ip = $ip_publica;

            $img_x = 48;
            $img_y = 244; // Cambiar la posición según el número de firmas
            $img_width = 15;
            $img_height = 15;

            $qr_txt = 'FIRMADO POR: ' . strtoupper($nombre) . "\n" .
                'CEDULA: ' . $cedula . "\n" .
                'IP: ' . $ip . "\n" .
                'CORREO: ' . $correo . "\n" .
                'LOCALIZACION: ' . strtoupper($provincia) . '/' . strtoupper($ciudad) . '/' . strtoupper($parroquia) . "\n" .
                'FECHA: ' . date("d/m/yy h:m:s") . "\n" .
                'VALIDAR CON: ' . $organizacion . "\n" .
                'CLAVE: ' . $encryptedData;

            $pdf->write2DBarcode($qr_txt, 'QRCODE,L', $img_x, $img_y, $img_width, $img_height);

            $txt_x = $img_x + $img_width + 2;
            $txt_y = $img_y - 6 + $img_height / 5;

            $pdf->setXY($txt_x, $txt_y);
            $pdf->setFont('courier', '', 9);
            $pdf->Cell(0, 10, 'Firmado electrónicamente por:');


            $pdf->setXY($txt_x, $txt_y + 4);
            $pdf->setFont('courier', 'B', 12);
            $pdf->Cell(0, 10, strtoupper($nombre));

            $pdf->setFont('courier', '', 9);

            $pdf->setXY($txt_x, $txt_y + 8);
            $pdf->Cell(0, 10, strtoupper('IP: ' . $ip));


            $pdf->setXY($txt_x, $txt_y + 12);
            $pdf->Cell(0, 10, ('CORREO: ' . $correo));

            /*
             *
             * Fin - Para firmar el documento
             *
             */
        }


        //Close and output PDF document
        $pdf->Output('student.pdf', 'I');

        //============================================================+
        // END OF FILE
        //============================================================+
    }
}
