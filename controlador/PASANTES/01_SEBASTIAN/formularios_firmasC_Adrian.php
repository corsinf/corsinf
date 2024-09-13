<?php

use Box\Spout\Common\Entity\Cell;

require_once(dirname(__DIR__, 3) . '/lib/pdf/fpdf.php');
require_once(dirname(__DIR__, 3) . '/modelo/PASANTES/01_SEBASTIAN/formularios_firmasM.php');

$controlador = new formularios_firmasC();

if (isset($_GET['persona_juridica'])) {
    echo ($controlador->persona_juridica($_GET['id']));
}

class formularios_firmasC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new formularios_firmasM();
    }
    function persona_juridica($id)
    {

        $datos = $this->modelo->where('fir_sol_id', $id)->listar();

        $fir_sol_primer_nombre = $datos[0]['fir_sol_primer_nombre'];
        $fir_sol_segundo_nombre = $datos[0]['fir_sol_segundo_nombre'];
        $fir_sol_primer_apellido = $datos[0]['fir_sol_primer_apellido'];
        $fir_sol_segundo_apellido = $datos[0]['fir_sol_segundo_apellido'];
        $fir_sol_numero_identificacion = $datos[0]['fir_sol_numero_identificacion'];
        $fir_sol_ciudad = $datos[0]['fir_sol_ciudad'];
        $fir_sol_provincia = $datos[0]['fir_sol_provincia'];
        $fir_sol_numero_celular = $datos[0]['fir_sol_numero_celular'];
        $fir_sol_numero_fijo = $datos[0]['fir_sol_numero_fijo'];
        $fir_sol_razon_social = $datos[0]['fir_sol_razon_social'];
        $fir_sol_ruc_juridico = $datos[0]['fir_sol_ruc_juridico'];
        $fir_sol_direccion_ruc_juridico = $datos[0]['fir_sol_direccion_ruc_juridico'];
        $fir_sol_correo_empresarial = $datos[0]['fir_sol_correo_empresarial'];

        $fir_sol_nombre_completo = $fir_sol_primer_nombre . ' ' . $fir_sol_segundo_nombre . ' ' . $fir_sol_primer_apellido . ' ' . $fir_sol_segundo_apellido;
        $fir_sol_autorizacion = 'Yo, ' . $fir_sol_nombre_completo . ' con número de cédula o pasaporte ' . $fir_sol_numero_identificacion . ' en mi calidad de representante legal de la empresa ' . 'Yo que sé' . ', con número de RUC ' . $fir_sol_ruc_juridico . ', autorizo a ANFAC AUTORIDAD DE CERTIFICACION ECUADOR C.A. la emisión de los siguientes certificados digitales para los siguientes empleados de esta empresa:';

        $pdf = new FPDF('P', 'mm', 'A4');
        $pdf->SetMargins(15, 15, 15);
        $pdf->SetAutoPageBreak(true, 15);
        $pdf->setFillColor(255, 255, 255);
        $pdf->AliasNbPages();
        $pdf->AddPage();
        
        $pdf->SetY(10);
        $pdf->SetFont('Helvetica', '', 11);
        $pdf->SetTextColor(169, 169, 169);
        $pdf->Cell(0, 10, 'Pag ' . $pdf->PageNo(), 0, 1, 'C');

        $pdf->SetTextColor(0, 0, 0);

        $pdf->SetFont('Times', 'BU', 13);
        $pdf->Cell(0, 10, utf8_decode('FORMULARIO PERSONA JURÍDICA'), 0, 1, 'C');

        $pdf->Ln(7);

        $pdf->SetTextColor(0, 102, 204);

        $pdf->SetFont('Arial', 'B', 11);
        $pdf->Cell(85, 7, utf8_decode('Ciudad, Fecha: '), 0, 0, 'R');
        $pdf->SetFont('Arial', '', 11);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(50, 7, utf8_decode('Quito, 2023-02-10'), 0, 1, 'L');

        $pdf->Ln(7);

        $pdf->SetTextColor(0, 0, 0);

        $pdf->SetFont('Arial', 'B', 11);
        $pdf->Cell(0, 7, utf8_decode('DATOS REPRESENTANTE LEGAL'), 1, 1, 'C');

        $pdf->Cell(85, 7, utf8_decode('RAZÓN SOCIAL'), 1, 0, 'R');
        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(0, 7, utf8_decode($fir_sol_razon_social), 1, 1, 'L');

        $pdf->SetFont('Arial', 'B', 11);
        $pdf->Cell(85, 7, utf8_decode('RUC'), 1, 0, 'R');
        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(0, 7, utf8_decode($fir_sol_ruc_juridico), 1, 1, 'L');

        $pdf->SetFont('Arial', 'B', 11);
        $pdf->MultiCellRow(85, 7, utf8_decode('NOMBRES COMPLETOS DEL' . "\n" . 'REPRESENTANTE LEGAL'), 1, 'R', 0);
        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(0, 14, utf8_decode($fir_sol_nombre_completo), 1, 1, 'L');

        $pdf->SetFont('Arial', 'B', 11);
        $pdf->Cell(85, 7, utf8_decode('NÚMERO DE CÉDULA O PASAPORTE'), 1, 0, 'R');
        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(0, 7, utf8_decode($fir_sol_numero_identificacion), 1, 1, 'L');

        if (strlen($fir_sol_direccion_ruc_juridico) > 50 & strlen($fir_sol_direccion_ruc_juridico) < 101) {
            $pdf->SetFont('Arial', 'B', 11);
            $pdf->Cell(85, 14, utf8_decode('DIRECCIÓN (COMO ESTÁ EN EL RUC)'), 1, 0, 'R');
            $pdf->SetFont('Arial', '', 11);
            $pdf->MultiCellRow(0, 7, utf8_decode($fir_sol_direccion_ruc_juridico), 1, 1, 'L');
            $pdf->Ln(14);
        } else {
            if (strlen($fir_sol_direccion_ruc_juridico) < 51) {
                $pdf->SetFont('Arial', 'B', 11);
                $pdf->Cell(85, 7, utf8_decode('DIRECCIÓN (COMO ESTÁ EN EL RUC)'), 1, 0, 'R');
                $pdf->SetFont('Arial', '', 11);
                $pdf->Cell(0, 7, utf8_decode($fir_sol_direccion_ruc_juridico), 1, 1, 'L');
            }
            if (strlen($fir_sol_direccion_ruc_juridico) > 100 & strlen($fir_sol_direccion_ruc_juridico) < 151) {
                $pdf->SetFont('Arial', 'B', 11);
                $pdf->Cell(85, 21, utf8_decode('DIRECCIÓN (COMO ESTÁ EN EL RUC)'), 1, 0, 'R');
                $pdf->SetFont('Arial', '', 11);
                $pdf->MultiCellRow(0, 7, utf8_decode($fir_sol_direccion_ruc_juridico), 1, 1, 'L');
                $pdf->Ln(21);
            }
        }

        $pdf->SetFont('Arial', 'B', 11);
        $pdf->Cell(85, 7, utf8_decode('PROVINCIA'), 1, 0, 'R');
        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(0, 7, utf8_decode($fir_sol_provincia), 1, 1, 'L');

        $pdf->SetFont('Arial', 'B', 11);
        $pdf->Cell(85, 7, utf8_decode('CIUDAD'), 1, 0, 'R');
        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(0, 7, utf8_decode($fir_sol_ciudad), 1, 1, 'L');

        $pdf->SetFont('Arial', 'B', 11);
        $pdf->MultiCellRow(85, 7, utf8_decode('DIRECCION CORREO ELECTRÓNICO' . "\n" . 'EMPRESARIAL VÁLIDO'), 1, 'R', 0);
        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(0, 14, utf8_decode($fir_sol_correo_empresarial), 1, 1, 'L');

        $pdf->SetFont('Arial', 'B', 11);
        $pdf->Cell(85, 7, utf8_decode('N° CELULAR (PONER CÓDIGO DE PAÍS)'), 1, 0, 'R');
        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(0, 7, utf8_decode($fir_sol_numero_celular), 1, 1, 'L');

        $pdf->SetFont('Arial', 'B', 11);
        $pdf->Cell(85, 7, utf8_decode('N° FIJO (PONER CÓDIGO DE PAÍS)'), 1, 0, 'R');
        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(0, 7, utf8_decode($fir_sol_numero_fijo), 1, 1, 'L');

        
        
        $pdf->AddPage();

        $pdf->SetY(10);
        $pdf->SetFont('Helvetica', '', 11);
        $pdf->SetTextColor(169, 169, 169);
        $pdf->Cell(0, 10, 'Pag ' . $pdf->PageNo(), 0, 1, 'C');

        $pdf->SetTextColor(0, 0, 0);

        $pdf->SetFont('Times', 'BU', 13);
        $pdf->Cell(0, 10, utf8_decode('AUTORIZACIÓN DE CERTIFICADOS DIGITALES'), 0, 1, 'C');

        $pdf->Ln(10);

        $pdf->SetTextColor(0, 102, 204);

        $pdf->SetFont('Arial', 'B', 11);
        $pdf->Cell(85, 7, utf8_decode('Ciudad, Fecha: '), 0, 0, 'R');
        $pdf->SetFont('Arial', '', 11);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(50, 7, utf8_decode('Quito, 2023-02-10'), 0, 1, 'L');

        $pdf->Ln(8);


        $pdf->MultiCell(0, 6, utf8_decode($fir_sol_autorizacion));

        $pdf->Ln(7);

        $pdf->SetFont('Arial', 'B', 11);
        $pdf->Cell(8, 12, utf8_decode('#'), 1, 0, 'C');
        $pdf->MultiCellRow(92, 6, utf8_decode('Nombres y apellidos de las personas que van a hacer uso de un certificado digital'), 1, 'C', 0);
        $pdf->Cell(40, 12, utf8_decode('Número de Cédula'), 1, 0, 'C');
        $pdf->Cell(40, 12, utf8_decode('Cargo'), 1, 1, 'C');

        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(8, 6, utf8_decode('1'), 1, 0, 'C');
        $pdf->Cell(92, 6, utf8_decode(''), 1, 0, 'C');
        $pdf->Cell(40, 6, utf8_decode(''), 1, 0, 'C');
        $pdf->Cell(40, 6, utf8_decode(''), 1, 1, 'C');

        $pdf->Cell(8, 6, utf8_decode('2'), 1, 0, 'C');
        $pdf->Cell(92, 6, utf8_decode(''), 1, 0, 'C');
        $pdf->Cell(40, 6, utf8_decode(''), 1, 0, 'C');
        $pdf->Cell(40, 6, utf8_decode(''), 1, 1, 'C');

        $pdf->Ln(7);

        $pdf->MultiCell(0, 5, utf8_decode('Nota: Todas las personas, incluido el representante legal, que requieran de un certificado digital de firma electrónica deben constar en la tabla anterior.'));

        $pdf->Ln(7);

        $pdf->MultiCell(0, 5, utf8_decode('Particular que pongo en su conocimiento para los fines pertinentes.'));

        $pdf->Ln(10);

        $pdf->Cell(0, 6, utf8_decode('Atentamente,'), 0, 1, 'J');

        $pdf->MultiCell(0, 6, utf8_decode(''), 0, 'L');

        $pdf->Ln(30);

        $pdf->SetFont('Arial', '', 26);

        $pdf->Cell(100, 8, utf8_decode('X'), 'B', 1, 'L');

        $pdf->Ln(15);

        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(0, 6, utf8_decode('Nombre: '.$fir_sol_nombre_completo), 0, 1, 'J');
        $pdf->Cell(0, 6, utf8_decode('Representante legal: '.'Alguien ha de ser'), 0, 1, 'J');


        $pdf->Output();
    }
}
