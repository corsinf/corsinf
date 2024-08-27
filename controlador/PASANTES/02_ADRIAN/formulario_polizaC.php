<?php
require_once(dirname(__DIR__, 3) . '/lib/pdf/fpdf.php');

$controlador = new Formulario_PolizaC();

if (isset($_GET['pdf_formulariopoliza'])) {
    echo ($controlador->pdf_formulariopoliza());
}

class Formulario_PolizaC
{
    function pdf_formulariopoliza(){

        $tipo_seguro = 'Seguro de vida';
        $cobertura_solicitada = 'Básica';
        $monto_asegurado = '120.99';
        $fecha_cobertura = 'Inmediata';
        $descripcion_objeto = 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Facere aliquam error dolores corrupti ipsum porro natus voluptas recusandae. Enim voluptatibus dolorum eius nihil libero, sapiente dolore aspernatur dolores veritatis ipsum.';
        $valor_declarado = '1566.45';
        $numero_serie = '3654654';
        $declaracion = 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Facere aliquam error dolores corrupti ipsum porro natus voluptas recusandae. Enim voluptatibus dolorum eius nihil libero, sapiente dolore aspernatur dolores veritatis ipsum.';



        $pdf = new FPDF('P', 'mm', 'A4');
        $pdf->SetMargins(15, 15, 15);
        $pdf->SetAutoPageBreak(true, 15);
        $pdf->setFillColor(255, 255, 255);
        $pdf->AliasNbPages();
        $pdf->AddPage();


        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, utf8_decode('Formulario de Solicitud'), 0, 1, 'C');
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 5, utf8_decode('de Póliza de Seguros'), 0, 1, 'C');

        $pdf->Ln(6);

        
        $pdf->SetFont('Arial', 'B', 13);
        $pdf->Cell(0, 9, utf8_decode('Información de la Póliza'), 1, 1, 'C');
        
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(75, 8, utf8_decode('Tipo de Seguro'), 1, 0, 'R');
        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(0, 8, utf8_decode($tipo_seguro), 1, 1, 'L');

        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(75, 8, utf8_decode('Cobertura Solicitada'), 1, 0, 'R');
        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(0, 8, utf8_decode($cobertura_solicitada), 1, 1, 'L');

        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(75, 8, utf8_decode('Monto Asegurado'), 1, 0, 'R');
        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(0, 8, utf8_decode('$'.$monto_asegurado), 1, 1, 'L');
        

        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(75, 8, utf8_decode('Fecha de Inicio de la Cobertura'), 1, 0, 'R');
        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(0, 8, utf8_decode($fecha_cobertura), 1, 1, 'L');

        $pdf->Ln(8);

        
        $pdf->SetFont('Arial', 'B', 13);
        $pdf->Cell(0, 9, utf8_decode('Información del Objeto Asegurado (si aplica)'), 1, 1, 'C');

        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 8, utf8_decode('Descripción del Objeto Asegurado'), 1, 1, 'J');
        $pdf->SetFont('Arial', '', 11);
        $pdf->MultiCell(0, 6, utf8_decode($descripcion_objeto), 1, 0, 'J');

        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 8, utf8_decode('Valor Declarado del Objeto'), 1, 1, 'J');
        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(0, 8, utf8_decode('$'.$valor_declarado), 1, 1, 'J');

        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 8, utf8_decode('Número de Serie o Identificación '), 1, 1, 'J');
        $pdf->SetFont('Arial', '', 11);
        $pdf->MultiCell(0, 8, utf8_decode($numero_serie), 1, 0, 'J');

        $pdf->Ln(8);

        
        $pdf->SetFont('Arial', 'B', 13);
        $pdf->Cell(0, 9, utf8_decode('Declaración y Firma'), 1, 1, 'C');

        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 8, utf8_decode('Declaración del Asegurado'), 1, 1, 'J');
        $pdf->SetFont('Arial', '', 11);
        $pdf->MultiCell(0, 6, utf8_decode($declaracion), 1, 0, 'J');

        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 8, utf8_decode('Firma'), 1, 1, 'J');
        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(0, 20, utf8_decode(''), 1, 1, 'J');



        $pdf->Output();
    }
}

?>