<?php
require_once(dirname(__DIR__, 3) . '/lib/pdf/fpdf.php');

$controlador = new Formulario_ReporteC();

if (isset($_GET['pdf_formularioreporte'])) {
    echo ($controlador->pdf_formularioreporte());
}

class Formulario_ReporteC
{
    function pdf_formularioreporte(){

        $nombre_completo = 'Roberto Jose Díaz Bedoya';
        $numero_identificacion = '654564654';
        $direccion = 'Quitumbe, calle Moran Valverde y Guanya Ñan';
        $telefono = '0996548771';
        $correo = 'pruebadesarrollo@gmail.com';
        $tipo_bien = 'Vehículo';
        $descripcion_bien = 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Quis laudantium quos ex voluptas molestias cumque distinctio pariatur illum placeat! Iusto similique qui reprehenderit officiis possimus dignissimos beatae dolorum mollitia ipsum?';
        $numero_serie = '45456478';
        $valor_bien = '28512';
        $fecha_incidente = '2024/08/11';
        $lugar_incidente = 'Quito, Av. Simón Bolivar';
        $descripcion_indicente = 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Quis laudantium quos ex voluptas molestias cumque distinctio pariatur illum placeat! Iusto similique qui reprehenderit officiis possimus dignissimos beatae dolorum mollitia ipsum?';
        $perdida = 'Parcial';
        $descripcion_daños = 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Quis laudantium quos ex voluptas molestias cumque distinctio pariatur illum placeat! Iusto similique qui reprehenderit officiis possimus dignissimos beatae dolorum mollitia ipsum?';
        $documentos_adjuntos1 = 'Factura';
        $documentos_adjuntos2 = 'Póliza de seguro';
        $documentos_adjuntos3 = 'Informe Policial';
        $documentos_adjuntos4 = null;
        $documentos_adjuntos5 = null;
        $total_documentos_adjuntos = $documentos_adjuntos1."\n".$documentos_adjuntos2."\n".$documentos_adjuntos3."\n".$documentos_adjuntos4."\n".$documentos_adjuntos5;
        $confirmacion = 'Si';
        $firma_solicitante = 'Roberto Díaz';
        $fecha = '2024/08/22';

        
        $pdf = new FPDF('P', 'mm', 'A4');
        $pdf->SetMargins(15, 15, 15);
        $pdf->SetAutoPageBreak(true, 15);
        $pdf->setFillColor(255, 255, 255);
        $pdf->AliasNbPages();
        $pdf->AddPage();

        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, utf8_decode('Formulario de Reporte'), 0, 1, 'C');
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 5, utf8_decode('de Pérdida Total o Parcial'), 0, 1, 'C');

        $pdf->Ln(6);

        
        $pdf->SetFont('Arial', 'B', 13);
        $pdf->Cell(0, 7, utf8_decode('Información del Solicitante'), 1, 1, 'C');
        
        $pdf->SetFont('Arial', 'B', 11);
        $pdf->Cell(115, 6, utf8_decode('Nombre Completo'), 1, 0, 'C');
        $pdf->Cell(65, 6, utf8_decode('Número de identificación'), 1, 1, 'C');
        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(115, 6, utf8_decode($nombre_completo), 1, 0, 'C');
        $pdf->Cell(65, 6, utf8_decode($numero_identificacion), 1, 1, 'C');
        $pdf->SetFont('Arial', 'B', 11);
        $pdf->Cell(115, 6, utf8_decode('Correo Electrónico'), 1, 0, 'C');
        $pdf->Cell(65, 6, utf8_decode('Teléfono'), 1, 1, 'C');
        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(115, 6, utf8_decode($correo), 1, 0, 'C');
        $pdf->Cell(65, 6, utf8_decode($telefono), 1, 1, 'C');
        $pdf->SetFont('Arial', 'B', 11);
        $pdf->Cell(0, 6, utf8_decode('Dirección'), 1, 1, 'C');
        $pdf->SetFont('Arial', '', 11);
        $pdf->Multicell(0, 6, utf8_decode($direccion), 1, 'C');

        $pdf->Ln(8);

        $pdf->SetFont('Arial', 'B', 13);
        $pdf->Cell(0, 7, utf8_decode('Información del Bien Perdido:'), 1, 1, 'C');
        $pdf->SetFont('Arial', 'B', 11);
        $pdf->Cell(0, 6, utf8_decode('Tipo de bien'), 1, 1, 'J');
        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(0, 6, utf8_decode($tipo_bien), 1, 1, 'J');
        $pdf->SetFont('Arial', 'B', 11);
        $pdf->Cell(0, 6, utf8_decode('Descripción del bien'), 1, 1, 'J');
        $pdf->SetFont('Arial', '', 11);
        $pdf->MultiCell(0, 6, utf8_decode($descripcion_bien), 1, 1, 'J');
        $pdf->SetFont('Arial', 'B', 11);
        $pdf->Cell(0, 6, utf8_decode('Número de serie o identificación '), 1, 1, 'J');
        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(0, 6, utf8_decode($numero_serie), 1, 1, 'J');
        $pdf->SetFont('Arial', 'B', 11);
        $pdf->Cell(0, 6, utf8_decode('Valor estimado del bien'), 1, 1, 'J');
        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(0, 6, utf8_decode('$'.$valor_bien), 1, 1, 'J');

        $pdf->Ln(8);

        $pdf->SetFont('Arial', 'B', 13);
        $pdf->Cell(0, 7, utf8_decode('Detalles del Incidente:'), 1, 1, 'C');
        $pdf->SetFont('Arial', 'B', 11);
        $pdf->Cell(0, 6, utf8_decode('Fecha del incidente'), 1, 1, 'J');
        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(0, 6, utf8_decode($fecha_incidente), 1, 1, 'J');
        $pdf->SetFont('Arial', 'B', 11);
        $pdf->Cell(0, 6, utf8_decode('Lugar del incidente'), 1, 1, 'J');
        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(0, 6, utf8_decode($lugar_incidente), 1, 1, 'J');
        $pdf->SetFont('Arial', 'B', 11);
        $pdf->Cell(0, 6, utf8_decode('Descripción del incidente'), 1, 1, 'J');
        $pdf->SetFont('Arial', '', 11);
        $pdf->Multicell(0, 6, utf8_decode($descripcion_indicente), 1, 1, 'J');
        $pdf->SetFont('Arial', 'B', 11);
        $pdf->Cell(0, 6, utf8_decode('¿Fue una pérdida total o parcial?'), 1, 1, 'J');
        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(0, 6, utf8_decode($perdida), 1, 1, 'J');
        $pdf->SetFont('Arial', 'B', 11);
        $pdf->Cell(0, 6, utf8_decode('Descripción de los daños sufridos (en caso de pérdida parcial)'), 1, 1, 'J');
        $pdf->SetFont('Arial', '', 11);
        $pdf->MultiCell(0, 6, utf8_decode($descripcion_daños), 1, 1, 'J');

        $pdf->Ln(23);

        $pdf->SetFont('Arial', 'B', 13);
        $pdf->Cell(0, 7, utf8_decode('Documentación Adicional'), 1, 1, 'C');
        $pdf->SetFont('Arial', 'B', 11);
        $pdf->Cell(0, 6, utf8_decode('Documentos Adjuntos'), 1, 1, 'J');
        $pdf->SetFont('Arial', '', 11);
        $pdf->MultiCell(0, 6, utf8_decode($total_documentos_adjuntos), 1, 1, 'J');

        $pdf->Ln(8);

        $pdf->SetFont('Arial', 'B', 13);
        $pdf->Cell(0, 7, utf8_decode('Declaración del Solicitante'), 1, 1, 'C');
        $pdf->SetFont('Arial', 'B', 11);
        $pdf->Cell(140, 6, utf8_decode('Confirmo que la información proporcionada es verídica y completa.'), 1, 0, 'J');
        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(40, 6, utf8_decode($confirmacion), 1, 1, 'J');

        $pdf->Ln(10);
        
        $pdf->SetFont('Arial', 'B', 11);
        $pdf->Cell(100, 6, utf8_decode('Firma del Solicitante'), 1, 0, 'C');
        $pdf->Cell(80, 6, utf8_decode('Fecha'), 1, 1, 'C');
        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(100, 8, utf8_decode($firma_solicitante), 1, 0, 'C');
        $pdf->Cell(80, 8, utf8_decode($fecha), 1, 1, 'C');

        $pdf->Output();
    }
}