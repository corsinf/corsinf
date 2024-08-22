<?php
require_once(dirname(__DIR__, 3) . '/lib/pdf/fpdf.php');

$controlador = new formularios_firmasC();

if (isset($_GET['persona_natural'])) {
    echo $controlador->persona_natural();
}

if (isset($_GET['hola'])) {
    echo $controlador->persona();
}

class formularios_firmasC
{

    function persona_natural()
    {
        //////////////////////////////////////////////////////////////////////////////////////////////////////
        $fir_segundo_apellido = 'Pilca';
        $fir_primer_apellido = 'Ortiz';
        $fir_primer_nombre = 'Ruben';
        $fir_segundo_nombre = 'Andres';

        $nombres_completos =  $fir_primer_apellido . ' ' . $fir_segundo_apellido  . ' ' . $fir_primer_nombre . ' ' . $fir_segundo_nombre;


        //////////////////////////////////////////////////////////////////////////////////////////////////////

        $pdf = new FPDF('P', 'mm', 'A4');
        $pdf->SetMargins(28, 15, 28);
        $pdf->SetAutoPageBreak(true, 15);
        $pdf->AddPage();

        $pdf->SetY(10);
        $pdf->SetFont('Helvetica', '', 11);
        $pdf->SetTextColor(169, 169, 169);
        $pdf->Cell(0, 10, 'Pag ' . $pdf->PageNo(), 0, 1, 'C');




        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont('Times', 'BU', 12);
        $pdf->Cell(0, 15, utf8_decode('FORMULARIO PERSONA NATURAL'), 0, 1, 'C');

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
        $pdf->Cell(60, 7, utf8_decode('NOMBRES COMPLETOS'), 1, 0, 'L');
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(94, 7, utf8_decode($nombres_completos), 1, 1, 'L');

        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(60, 7, utf8_decode('NÚMERO DE RUC:'), 1, 0, 'L');
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(94, 7, utf8_decode('Ruben Pilca'), 1, 1, 'L');

        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(60, 7, utf8_decode('NOMBRES COMPLETOS'), 1, 0, 'L');
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(94, 7, utf8_decode('Ruben Pilca'), 1, 1, 'L');

        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(60, 7, utf8_decode('NOMBRES COMPLETOS'), 1, 0, 'L');
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(94, 7, utf8_decode('Ruben Pilca'), 1, 1, 'L');

        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(60, 7, utf8_decode('DIRECCION CORREO ELECTRONICO:'), 1, 0, 'L');
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(94, 7, utf8_decode('Ruben Pilca'), 1, 1, 'L');

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
            'Yo '.$nombres_completos.' con número de cédula o pasaporte 100456789654; autorizo a ANFAC AUTORIDAD DE CERTIFICACION ECUADOR C.A. la emision de mi certificado digital de Firma Electronica. 
            
Particular que pongo en su conocimiento para los fines pertinentes. 
            
            
            
Atentamente,';

        $pdf->MultiCell(0, 6, utf8_decode($authorization_text_natural), 0, 'L');

        $pdf->Ln(30);

        $pdf->SetFont('Arial', '', 26);

        $pdf->Cell(100, 8, utf8_decode('X'), 'B', 1, 'L');



        $pdf->Output();
    }

    function persona()
    {
        echo 'hola';
    }
}
