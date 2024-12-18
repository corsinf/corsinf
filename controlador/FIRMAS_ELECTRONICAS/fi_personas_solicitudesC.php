<?php
require_once(dirname(__DIR__, 2) . '/modelo/FIRMAS_ELECTRONICAS/fi_personas_solicitudesM.php');
require_once(dirname(__DIR__, 2) . '/lib/pdf/fpdf.php');

$controlador = new fi_personas_solicitudesC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar());
}

if (isset($_GET['pdf_persona_consentimiento'])) {
    echo $controlador->pdf_acta_consentimiento($_GET['id']);
}

class fi_personas_solicitudesC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new fi_personas_solicitudesM();
    }

    function listar()
    {
        $_id = isset($_SESSION['INICIO']['NO_CONCURENTE']) ? $_SESSION['INICIO']['NO_CONCURENTE'] : null;
        $datos = $this->modelo->listar_join($_id);
        return $datos;
    }

    function pdf_acta_consentimiento($id_solicitud)
    {
        $_id = isset($_SESSION['INICIO']['NO_CONCURENTE']) ? $_SESSION['INICIO']['NO_CONCURENTE'] : null;
        $datos = $this->modelo->listar_join_pdf($_id, $id_solicitud);
        //////////////////////////////////////////////////////////////////////////////////////////////////////

        $fi_sol_primer_nombre = $datos[0]['primer_nombre'];
        $fi_sol_segundo_nombre = $datos[0]['segundo_nombre'];
        $fi_sol_primer_apellido = $datos[0]['primer_apellido'];
        $fi_sol_segundo_apellido = $datos[0]['segundo_apellido'];
        $fi_sol_numero_identificacion = $datos[0]['cedula'];
        $fi_sol_direccion_domicilio = $datos[0]['direccion'];
        $fi_sol_correo = $datos[0]['correo'];
        $fi_sol_ciudad = $datos[0]['ciudad'];
        $fi_sol_provincia = $datos[0]['provincia'];
        $fi_sol_numero_celular = $datos[0]['telefono_1'];
        $fi_sol_numero_fijo = $datos[0]['telefono_2'];
        $fi_sol_razon_social = $datos[0]['razon_social'];
        $fi_sol_ruc_juridico = $datos[0]['identificacion'];
        $fi_sol_direccion_ruc_juridico = $datos[0]['direccion_ruc_juridico'];
        $fi_sol_correo_empresarial = $datos[0]['correo_empresarial'];
        $fi_sol_tipo_formulario = $datos[0]['nombre_solicitud'];

        $fi_sol_nombres_completos =  $datos[0]['nombres_completos'];
        $CFomulario_id =  $datos[0]['CFomulario_id'];


        //////////////////////////////////////////////////////////////////////////////////////////////////////

        $pdf = new FPDF('P', 'mm', 'A4');
        $pdf->SetMargins(28, 15, 28);
        $pdf->SetAutoPageBreak(true, 15);
        $pdf->AddPage();

        $pdf->SetY(10);
        $pdf->SetFont('Helvetica', '', 11);
        $pdf->SetTextColor(169, 169, 169);
        $pdf->Cell(0, 10, 'Pag ' . $pdf->PageNo(), 0, 1, 'C');



        if ($CFomulario_id == 1) {
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetFont('Times', 'BU', 12);
            $pdf->Cell(0, 15, utf8_decode('FORMULARIO PERSONA NATURAL'), 0, 1, 'C');
        } else if ($CFomulario_id == 2) {
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetFont('Times', 'BU', 12);
            $pdf->Cell(0, 15, utf8_decode('FORMULARIO PERSONA NATURAL CON RUC'), 0, 1, 'C');
        } else if ($CFomulario_id == 3) {
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetFont('Times', 'BU', 12);
            $pdf->Cell(0, 15, utf8_decode('FORMULARIO PERSONA JURÍDICA'), 0, 1, 'C');
        }


        $pdf->Ln(10);

        $pdf->SetFont('Times', 'B', 12);
        $pdf->SetFillColor(255, 255, 255); // Color de relleno
        $pdf->SetTextColor(0, 102, 204);   // Cambia el color del texto (Azul oscuro)
        $pdf->Cell(0, 3, utf8_decode('CIUDAD, FECHA:'), 0, 1, 'C');


        $pdf->Ln(8);

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetTextColor(0, 0, 0);   // Cambia el color del texto (Azul oscuro)
        $pdf->Cell(0, 7, utf8_decode('DATOS REPRESENTANTE LEGAL'), 1, 1, 'C');

        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(64, 7, utf8_decode('NOMBRES COMPLETOS'), 1, 0, 'L');
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(90, 7, utf8_decode($fi_sol_nombres_completos), 1, 1, 'L');



        if ($CFomulario_id == 1) {
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(64, 7, utf8_decode('NÚMERO DE CEDULA O PASAPORTE:'), 1, 0, 'L');
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(90, 7, utf8_decode($fi_sol_numero_identificacion), 1, 1, 'L');
        } else if ($CFomulario_id == 2) {
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(64, 7, utf8_decode('NÚMERO DE RUC:'), 1, 0, 'L');
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(90, 7, utf8_decode($fi_sol_numero_identificacion . '001'), 1, 1, 'L');
        }

        if (strlen($fi_sol_direccion_domicilio) > 50 & strlen($fi_sol_direccion_domicilio) < 101) {
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(64, 14, utf8_decode('DIRECCIÓN DOMICILIO'), 1, 0, 'L');
            $pdf->SetFont('Arial', '', 9);
            $pdf->MultiCellRow(90, 7, utf8_decode($fi_sol_direccion_domicilio), 1, 1, 'L');
            $pdf->Ln(14);
        } else {
            if (strlen($fi_sol_direccion_domicilio) < 51) {
                $pdf->SetFont('Arial', 'B', 9);
                $pdf->Cell(64, 7, utf8_decode('DIRECCIÓN DOMICILIO'), 1, 0, 'L');
                $pdf->SetFont('Arial', '', 9);
                $pdf->Cell(90, 7, utf8_decode($fi_sol_direccion_domicilio), 1, 1, 'L');
            }
            if (strlen($fi_sol_direccion_domicilio) > 100 & strlen($fi_sol_direccion_domicilio) < 151) {
                $pdf->SetFont('Arial', 'B', 9);
                $pdf->Cell(64, 21, utf8_decode('DIRECCIÓN DOMICILIO'), 1, 0, 'L');
                $pdf->SetFont('Arial', '', 9);
                $pdf->MultiCellRow(90, 7, utf8_decode($fi_sol_direccion_domicilio), 1, 1, 'L');
                $pdf->Ln(21);
            }
        }

        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(64, 7, utf8_decode('PROVINCIA'), 1, 0, 'L');
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(90, 7, utf8_decode($fi_sol_provincia), 1, 1, 'L');

        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(64, 7, utf8_decode('CIUDAD:'), 1, 0, 'L');
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(90, 7, utf8_decode($fi_sol_ciudad), 1, 1, 'L');

        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(64, 7, utf8_decode('DIRECCION CORREO ELECTRONICO:'), 1, 0, 'L');
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(90, 7, utf8_decode($fi_sol_correo), 1, 1, 'L');

        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(64, 7, utf8_decode('NO. CELULAR PONER CÓDIGO DE PAÍS'), 1, 0, 'L');
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(90, 7, utf8_decode($fi_sol_numero_celular), 1, 1, 'L');

        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(64, 7, utf8_decode('NO. FIJO PONER CÓDIGO DE PAÍS'), 1, 0, 'L');
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(90, 7, utf8_decode($fi_sol_numero_fijo), 1, 1, 'L');

        $pdf->AddPage();

        $pdf->SetY(10);
        $pdf->SetFont('Helvetica', '', 11);
        $pdf->SetTextColor(169, 169, 169);
        $pdf->Cell(0, 10, 'Pag ' . $pdf->PageNo(), 0, 1, 'C');


        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 15, utf8_decode('Autorización de Certificados Digitales para funcionarios.'), 0, 1, 'C');

        $pdf->Ln(10);

        $pdf->SetFont('Times', 'B', 12);
        $pdf->SetFillColor(255, 255, 255); // Color de relleno
        $pdf->SetTextColor(0, 102, 204);   // Cambia el color del texto (Azul oscuro)
        $pdf->Cell(0, 3, utf8_decode('CIUDAD, FECHA:'), 0, 1, 'C');

        $pdf->Ln(30);

        $pdf->SetFont('Arial', '', 9.5);
        $pdf->SetTextColor(0, 0, 0);   // Cambia el color del texto (Azul oscuro)
        $authorization_text_natural =
            'Yo ' . $fi_sol_nombres_completos . ' con número de cédula o pasaporte ' . $fi_sol_numero_identificacion . '; autorizo a ANF AC AUTORIDAD DE CERTIFICACION ECUADOR C.A. la emision de mi certificado digital de Firma Electronica. 
            
Particular que pongo en su conocimiento para los fines pertinentes. 
            
            
            
Atentamente,';

        $pdf->MultiCell(0, 6, utf8_decode($authorization_text_natural), 0, 'L');

        $pdf->Ln(30);

        $pdf->SetFont('Arial', '', 26);

        $pdf->Cell(100, 8, utf8_decode('X'), 'B', 1, 'L');

        $pdf->Output();
    }
}
