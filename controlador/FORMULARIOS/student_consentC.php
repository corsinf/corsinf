<?php
require_once(dirname(__DIR__, 2) . '/modelo/FORMULARIOS/student_consentM.php');
require_once(dirname(__DIR__, 2) . '/lib/pdf/fpdf.php');


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

    function __construct()
    {
        $this->modelo = new student_consentM();
    }

    function listar($id)
    {
        $datos = $this->modelo->where('edu_id', $id)->listar();
        return $datos;
    }

    function insertar_editar($parametros)
    {
        $edu_cbx_academic_all = isset($_POST['edu_cbx_academic_all']) && $_POST['edu_cbx_academic_all'] == 'true' ? 1 : 0;
        $edu_cbx_academic_1 = isset($_POST['edu_cbx_academic_1']) && $_POST['edu_cbx_academic_1'] == 'true' ? 1 : 0;
        $edu_cbx_academic_2 = isset($_POST['edu_cbx_academic_2']) && $_POST['edu_cbx_academic_2'] == 'true' ? 1 : 0;
        $edu_cbx_academic_3 = isset($_POST['edu_cbx_academic_3']) && $_POST['edu_cbx_academic_3'] == 'true' ? 1 : 0;
        $edu_cbx_academic_4 = isset($_POST['edu_cbx_academic_4']) && $_POST['edu_cbx_academic_4'] == 'true' ? 1 : 0;
        $edu_cbx_academic_5 = isset($_POST['edu_cbx_academic_5']) && $_POST['edu_cbx_academic_5'] == 'true' ? 1 : 0;
        $edu_cbx_academic_6 = isset($_POST['edu_cbx_academic_6']) && $_POST['edu_cbx_academic_6'] == 'true' ? 1 : 0;
        $edu_cbx_financial_all = isset($_POST['edu_cbx_financial_all']) && $_POST['edu_cbx_financial_all'] == 'true' ? 1 : 0;
        $edu_cbx_financial_1 = isset($_POST['edu_cbx_financial_1']) && $_POST['edu_cbx_financial_1'] == 'true' ? 1 : 0;
        $edu_cbx_financial_2 = isset($_POST['edu_cbx_financial_2']) && $_POST['edu_cbx_financial_2'] == 'true' ? 1 : 0;
        $edu_cbx_financial_3 = isset($_POST['edu_cbx_financial_3']) && $_POST['edu_cbx_financial_3'] == 'true' ? 1 : 0;
        $edu_cbx_aid_financial = isset($_POST['edu_cbx_aid_financial']) && $_POST['edu_cbx_aid_financial'] == 'true' ? 1 : 0;
        $edu_cbx_housing_all = isset($_POST['edu_cbx_housing_all']) && $_POST['edu_cbx_housing_all'] == 'true' ? 1 : 0;
        $edu_cbx_housing_1 = isset($_POST['edu_cbx_housing_1']) && $_POST['edu_cbx_housing_1'] == 'true' ? 1 : 0;
        $edu_cbx_housing_2 = isset($_POST['edu_cbx_housing_2']) && $_POST['edu_cbx_housing_2'] == 'true' ? 1 : 0;
        $edu_cbx_housing_3 = isset($_POST['edu_cbx_housing_3']) && $_POST['edu_cbx_housing_3'] == 'true' ? 1 : 0;
        $edu_cbx_remove_consent = isset($_POST['edu_cbx_remove_consent']) && $_POST['edu_cbx_remove_consent'] == 'true' ? 1 : 0;

        $datos1[0]['campo'] = 'edu_id';
        $datos1[0]['dato'] = strval($parametros['txt_student']);

        $datos = array(
            array('campo' => 'edu_nombre_estudiante', 'dato' => $parametros['txt_student']),
            array('campo' => 'edu_id_estudiante', 'dato' => $parametros['txt_id_student']),
            array('campo' => 'edu_fecha_nacimiento', 'dato' => $parametros['txt_birth_date']),
            array('campo' => 'edu_proposito_autorizacion', 'dato' => $parametros['txt_purpose_authorization']),
            array('campo' => 'edu_primer_nombre_autorizado', 'dato' => $parametros['txt_first_authorized_name']),
            array('campo' => 'edu_primer_relacion_autorizada', 'dato' => $parametros['txt_first_relationship']),
            array('campo' => 'edu_primera_direccion_autorizada', 'dato' => $parametros['txt_first_address']),
            array('campo' => 'edu_primer_email_autorizado', 'dato' => $parametros['txt_first_email']),
            array('campo' => 'edu_segundo_nombre_autorizado', 'dato' => $parametros['txt_second_authorized_name']),
            array('campo' => 'edu_segunda_relacion_autorizada', 'dato' => $parametros['txt_second_relationship']),
            array('campo' => 'edu_segunda_direccion_autorizada', 'dato' => $parametros['txt_second_address']),
            array('campo' => 'edu_segundo_email_autorizado', 'dato' => $parametros['txt_second_email']),
            // array('campo' => 'edu_firma_estudiante', 'dato' => $parametros['']),
            // array('campo' => 'edu_fecha_firma', 'dato' => $parametros['']),
            // array('campo' => 'edu_nombre_registro', 'dato' => $parametros['']),
            // array('campo' => 'edu_fecha_registro', 'dato' => $parametros['']),
            array('campo' => 'edu_cbx_academic_all', 'dato' => $edu_cbx_academic_all),
            array('campo' => 'edu_cbx_academic_1', 'dato' => $edu_cbx_academic_1),
            array('campo' => 'edu_cbx_academic_2', 'dato' => $edu_cbx_academic_2),
            array('campo' => 'edu_cbx_academic_3', 'dato' => $edu_cbx_academic_3),
            array('campo' => 'edu_cbx_academic_4', 'dato' => $edu_cbx_academic_4),
            array('campo' => 'edu_cbx_academic_5', 'dato' => $edu_cbx_academic_5),
            array('campo' => 'edu_cbx_academic_6', 'dato' => $edu_cbx_academic_6),
            array('campo' => 'edu_cbx_financial_all', 'dato' => $edu_cbx_financial_all),
            array('campo' => 'edu_cbx_financial_1', 'dato' => $edu_cbx_financial_1),
            array('campo' => 'edu_cbx_financial_2', 'dato' => $edu_cbx_financial_2),
            array('campo' => 'edu_cbx_financial_3', 'dato' => $edu_cbx_financial_3),
            array('campo' => 'edu_cbx_aid_financial', 'dato' => $edu_cbx_aid_financial),
            array('campo' => 'edu_cbx_housing_all', 'dato' => $edu_cbx_housing_all),
            array('campo' => 'edu_cbx_housing_1', 'dato' => $edu_cbx_housing_1),
            array('campo' => 'edu_cbx_housing_2', 'dato' => $edu_cbx_housing_2),
            array('campo' => 'edu_cbx_housing_3', 'dato' => $edu_cbx_housing_3),
            array('campo' => 'edu_cbx_remove_consent', 'dato' => $edu_cbx_remove_consent),
            // array('campo' => 'edu_fecha_creacion', 'dato' => $parametros['']),
            // array('campo' => 'edu_fecha_modificacion', 'dato' => $parametros['']),
            // array('campo' => 'edu_estado', 'dato' => $parametros['']),
        );

        // if ($parametros['txt_id'] == '') {
        //     if (count($this->modelo->where('pac_cedula', $parametros['txt_cedula'])->listar()) == 0) {
        //         $datos = $this->modelo->insertar($datos);
        //     } else {
        //         return -2;
        //     }
        // } else {
        //     $where[0]['campo'] = 'edu_id';
        //     $where[0]['dato'] = $parametros['edu_id'];
        //     $datos = $this->modelo->editar($datos, $where);
        // }
        $datos = $this->modelo->insertar($datos);
        return $datos;
        return ($parametros);
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

    function pdf_studentconsent($id = 1)
    {
        $datos = $this->modelo->where('edu_id', $id)->listar();

        $edu_nombre_estudiante = $datos[0]['edu_nombre_estudiante'];
        $edu_id_estudiante = $datos[0]['edu_id_estudiante'];
        $edu_fecha_nacimiento = $datos[0]['edu_fecha_nacimiento'];
        $edu_proposito_autorizacion = $datos[0]['edu_proposito_autorizacion'];
        $edu_primer_nombre_autorizado = $datos[0]['edu_primer_nombre_autorizado'];
        $edu_primer_relacion_autorizada = $datos[0]['edu_primer_relacion_autorizada'];
        $edu_primera_direccion_autorizada = $datos[0]['edu_primera_direccion_autorizada'];
        $edu_primer_email_autorizado = $datos[0]['edu_primer_email_autorizado'];
        $edu_segundo_nombre_autorizado = $datos[0]['edu_segundo_nombre_autorizado'];
        $edu_segunda_relacion_autorizada = $datos[0]['edu_segunda_relacion_autorizada'];
        $edu_segunda_direccion_autorizada = $datos[0]['edu_segunda_direccion_autorizada'];
        $edu_segundo_email_autorizado = $datos[0]['edu_segundo_email_autorizado'];
        $edu_firma_estudiante = $datos[0]['edu_firma_estudiante'];
        $edu_fecha_firma = $datos[0]['edu_fecha_firma'];
        $edu_nombre_registro = $datos[0]['edu_nombre_registro'];
        $edu_fecha_registro = $datos[0]['edu_fecha_registro'];
        $edu_cbx_academic_all = $datos[0]['edu_cbx_academic_all'];
        $edu_cbx_academic_1 = $datos[0]['edu_cbx_academic_1'];
        $edu_cbx_academic_2 = $datos[0]['edu_cbx_academic_2'];
        $edu_cbx_academic_3 = $datos[0]['edu_cbx_academic_3'];
        $edu_cbx_academic_4 = $datos[0]['edu_cbx_academic_4'];
        $edu_cbx_academic_5 = $datos[0]['edu_cbx_academic_5'];
        $edu_cbx_academic_6 = $datos[0]['edu_cbx_academic_6'];
        $edu_cbx_financial_all = $datos[0]['edu_cbx_financial_all'];
        $edu_cbx_financial_1 = $datos[0]['edu_cbx_financial_1'];
        $edu_cbx_financial_2 = $datos[0]['edu_cbx_financial_2'];
        $edu_cbx_financial_3 = $datos[0]['edu_cbx_financial_3'];
        $edu_cbx_aid_financial = $datos[0]['edu_cbx_aid_financial'];
        $edu_cbx_housing_all = $datos[0]['edu_cbx_housing_all'];
        $edu_cbx_housing_1 = $datos[0]['edu_cbx_housing_1'];
        $edu_cbx_housing_2 = $datos[0]['edu_cbx_housing_2'];
        $edu_cbx_housing_3 = $datos[0]['edu_cbx_housing_3'];
        $edu_cbx_remove_consent = $datos[0]['edu_cbx_remove_consent'];

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


        $pdf->SetFont('Arial', '', 11);

        // Student Information
        $pdf->SetFont('Arial', 'B', 22);
        $pdf->Cell(18);
        $pdf->Cell(0, 15, 'CONSENT FOR RELEASE', 0, 1, 'J');
        $pdf->Cell(25);
        $pdf->SetFont('Arial', 'B', 20);
        $pdf->Cell(0, 3, 'of Student Information', 0, 1, 'J');

        // University of Idaho contact info
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

        $nombre_parts = explode(' ', $edu_nombre_estudiante);

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
        $pdf->Cell(22, 5, utf8_decode($edu_id_estudiante), 'B', 0, 'L', true);

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
        $pdf->Cell(22, 5, utf8_decode($edu_fecha_nacimiento), 'B', 0, 'L', true);

        $pdf->setFillColor(244, 246, 255);

        $pdf->Ln(10);

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->MultiCell(0, 5, 'I hereby authorize the University of Idaho to discuss and verbally release the following information:', 0, 'L');

        $pdf->Ln(5);

        $pdf->SetFont('Arial', '', 10);
        // Checkboxes
        $y = $pdf->GetY();
        CheckBox($pdf, 12, $y, $edu_cbx_academic_all);
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
        CheckBox($pdf, 15, $y, $edu_cbx_academic_1);
        $pdf->Cell(10);
        $pdf->Cell(30, 5, 'Admission', 0, 0);
        CheckBox($pdf, 50, $y, $edu_cbx_academic_2);
        $pdf->Cell(5);
        $pdf->Cell(40, 5, 'Registration/Enrollment', 0, 0);
        CheckBox($pdf, 100, $y, $edu_cbx_academic_3);
        $pdf->Cell(10);
        $pdf->Cell(30, 5, 'Grades', 0, 1);

        $y = $pdf->GetY();
        CheckBox($pdf, 15, $y, $edu_cbx_academic_4);
        $pdf->Cell(10);
        $pdf->Cell(30, 5, 'GPA', 0, 0);
        CheckBox($pdf, 50, $y, $edu_cbx_academic_5);
        $pdf->Cell(5);
        $pdf->Cell(40, 5, 'Academic Standing', 0, 0);
        CheckBox($pdf, 100, $y, $edu_cbx_academic_6);
        $pdf->Cell(10);
        $pdf->Cell(30, 5, 'Graduation', 0, 1);

        $pdf->Ln(4);

        $y = $pdf->GetY();
        CheckBox($pdf, 12, $y, $edu_cbx_financial_all);
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
        CheckBox($pdf, 15, $y, $edu_cbx_financial_1);
        $pdf->Cell(10);
        $pdf->Cell(30, 5, 'Fees', 0, 0);
        CheckBox($pdf, 50, $y, $edu_cbx_financial_2);
        $pdf->Cell(5);
        $pdf->Cell(40, 5, 'Charges', 0, 0);
        CheckBox($pdf, 100, $y, $edu_cbx_financial_3);
        $pdf->Cell(10);
        $pdf->Cell(30, 5, 'Payments', 0, 1);

        $pdf->Ln(4);

        $y = $pdf->GetY();
        CheckBox($pdf, 12, $y, $edu_cbx_aid_financial);
        $pdf->Cell(6);

        // Establece la posición inicial
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
        CheckBox($pdf, 12, $y, $edu_cbx_housing_all);
        $pdf->Cell(6);

        // Establece la posición inicial
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
        CheckBox($pdf, 15, $y, $edu_cbx_housing_1);
        $pdf->Cell(10);
        $pdf->Cell(30, 5, 'Location', 0, 0);
        CheckBox($pdf, 50, $y, $edu_cbx_housing_2);
        $pdf->Cell(5);
        $pdf->Cell(40, 5, 'Room Assignment', 0, 0);
        CheckBox($pdf, 100, $y, $edu_cbx_housing_3);
        $pdf->Cell(10);
        $pdf->Cell(30, 5, 'Judicial Matters', 0, 1);



        $pdf->Ln(3);

        $pdf->SetFont('Arial', 'B', 11);
        $pdf->Cell(73, 8, 'My authorization is for the following purpose:', 0, 1);
        $pdf->SetFont('Arial', '', 11);
        $pdf->MultiCell(190, 5, utf8_decode($edu_proposito_autorizacion), 0, 'J');

        $pdf->Ln(5);

        $pdf->SetFont('Arial', 'B', 10);

        $y = $pdf->GetY();
        $pdf->Cell(2);
        $pdf->Cell(0, 6, '***', 0, 'J');
        CheckBox($pdf, 19, $y + 1.2, $edu_cbx_remove_consent);
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
        $pdf->Cell(100, 5, utf8_decode($edu_primer_nombre_autorizado), 'B', 0, 'L', true);
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(12, 2, '', 0, 0);
        $pdf->Cell(70, 5, utf8_decode($edu_primer_relacion_autorizada), 'B', 1, 'L', true);
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(53, 2, '', 0, 0);
        $pdf->Cell(10, 4, '(Printed Name)', 0, 0, 'C');
        $pdf->Cell(73, 2, '', 0, 0);
        $pdf->Cell(35, 4, '(Relationship to student)', 0, 1, 'C');
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(8, 2, '', 0, 0);
        $pdf->Cell(100, 5, utf8_decode($edu_primera_direccion_autorizada), 'B', 0, 'L', true);
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(12, 2, '', 0, 0);
        $pdf->Cell(70, 5, utf8_decode($edu_primer_email_autorizado), 'B', 1, 'L', true);
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
        $pdf->Cell(100, 5, utf8_decode($edu_segundo_nombre_autorizado), 'B', 0, 'L', true);
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(12, 2, '', 0, 0);
        $pdf->Cell(70, 5, utf8_decode($edu_segunda_relacion_autorizada), 'B', 1, 'L', true);
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(53, 2, '', 0, 0);
        $pdf->Cell(10, 4, '(Printed Name)', 0, 0, 'C');
        $pdf->Cell(73, 2, '', 0, 0);
        $pdf->Cell(35, 4, '(Relationship to student)', 0, 1, 'C');
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(8, 2, '', 0, 0);
        $pdf->Cell(100, 5, utf8_decode($edu_segunda_direccion_autorizada), 'B', 0, 'L', true);
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(12, 2, '', 0, 0);
        $pdf->Cell(70, 5, utf8_decode($edu_segundo_email_autorizado), 'B', 1, 'L', true);
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
        $pdf->Cell(80, 5, utf8_decode($edu_firma_estudiante), 'B', 0, 'L', true);
        //$pdf->Cell(103, 5, utf8_decode($edu_firma_estudiante), 0, 0);
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(12, 2, '', 0, 0);
        $pdf->Cell(10, 5, 'Date:', 0, 0);
        $pdf->Cell(3, 2, '', 0, 0);
        $pdf->Cell(45, 5, utf8_decode($edu_fecha_firma), 'B', 1, 'L', true);
        //$pdf->Cell(30, 5, utf8_decode($edu_fecha_firma), 0, 1);

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
        $pdf->Cell(55, 5, utf8_decode($edu_nombre_registro), 'B', 0, 'L', true);
        //$pdf->Cell(75, 5, utf8_decode($edu_nombre_registro), 0, 0, '');
        $pdf->Cell(18, 2, '', 0, 0);
        $pdf->Cell(8, 5, 'Date:', 0, 0, '', 1);
        $pdf->Cell(3, 2, '', 0, 0);
        $pdf->Cell(45, 5, utf8_decode($edu_fecha_registro), 'B', 0, 'L', true);
        //$pdf->Cell(45, 5, utf8_decode($edu_fecha_registro), 0, 0, '');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(8, 2, '', 0, 0);
        $pdf->Cell(20, 5, 'Rev 12/18', 0, 0, 'R');

        $pdf->Output();
    }
}
