 <?php
    require_once(dirname(__DIR__, 4) . '/lib/TCPDF/tcpdf.php');


    function pdf_cedula_activo($articulos, $mostrar = false)
    {
        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

        $ruta = $_SESSION['INICIO']['RUTA_IMG_RELATIVA'];
        $empresa = $_SESSION['INICIO']['BASEDATO'];

        $ruta_img = $ruta . "emp=$empresa&dir=activos&nombre=" .  $articulos[0]['imagen'];



        // Configurar documento
        $pdf->SetCreator('TCPDF');
        $pdf->SetAuthor('CORSINF');
        $pdf->SetTitle('Cédula de Activo Fijo');
        $pdf->SetSubject('Cédula de Activo Fijo');
        $pdf->SetMargins(10, 15, 10);
        $pdf->SetAutoPageBreak(true, 15);
        $pdf->setFillColor(249, 254, 247);

        // Eliminar cabecera/pie de página por defecto
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        // Añadir página
        $pdf->AddPage();

        // Configurar fuentes y colores


        // Ruta del logo
        $ruta_logo = dirname(__DIR__, 4) . '/img/de_sistema/corsinf_letras_1.png';
        // print_r($ruta_logo); exit(); die();
        $x_logo = 30;
        $y_logo = 10;
        $ancho_logo = 20;
        $alto_logo = 0;
        if (file_exists($ruta_logo)) {
            $pdf->Image($ruta_logo, $x_logo, $y_logo, $ancho_logo, $alto_logo);
        }

        ////////////////////

        // Imágenes (cabecera)

        $pdf->SetFont('helvetica', 'B', 11);
        // Encabezados centrados
        $pdf->Cell(190, 5, ('CORSINF'), 0, 1, 'C');
        $pdf->Cell(190, 5, ('DIRECCIÓN GENERAL FINANCIERA'), 0, 1, 'C');
        $pdf->Cell(190, 5, ('DIRECCIÓN DE CONTROL DE ACTIVOS'), 0, 1, 'C');
        $pdf->Cell(190, 5, ('CÉDULA DE ACTIVO'), 0, 1, 'C');

        letra_estilo_normal($pdf);
        $pdf->Cell(100, 5, '', 0, 0, 'L');
        $pdf->Cell(90, 5, date('d/m/Y'), 0, 1, 'R');

        // Datos de activo
        letra_estilo_negrita($pdf);
        $pdf->Cell(15, 5, 'SKU', 1, 0, 'C');
        letra_estilo_normal($pdf);
        $pdf->Cell(30, 5, $articulos[0]['tag'], 1, 0, 'C');
        letra_estilo_negrita($pdf);
        $pdf->Cell(30, 5, 'DESCRIPCIÓN', 1, 0, 'C');
        letra_estilo_normal($pdf);
        $pdf->Cell(115, 5, $articulos[0]['nom'], 1, 1, 'C');

        //  / Etiqueta / 
        letra_estilo_negrita($pdf);
        $pdf->Cell(45, 5, 'RFID', 1, 0, 'C');
        letra_estilo_normal($pdf);
        $pdf->Cell(145, 5, $articulos[0]['RFID'], 1, 1, 'C');

        $pdf->Ln(5);

        letra_estilo_negrita($pdf);
        $pdf->Cell(30, 5, 'Localización', 1, 0, 'C');
        letra_estilo_normal($pdf);
        $pdf->Cell(160, 5, $articulos[0]['localizacion'], 1, 1, 'C');
        letra_estilo_negrita($pdf);
        $pdf->Cell(30, 5, 'Custodio', 1, 0, 'C');
        letra_estilo_normal($pdf);
        $pdf->Cell(160, 5, $articulos[0]['custodio'], 1, 1, 'C');

        $pdf->Ln(5);

        letra_estilo_negrita($pdf);
        $pdf->Cell(30, 5, 'TIPO', 1, 0, 'C');
        letra_estilo_normal($pdf);
        $pdf->Cell(65, 5, $articulos[0]['tipo_articulo'], 1, 0, 'C');
        letra_estilo_negrita($pdf);
        $pdf->Cell(30, 5, 'Estado', 1, 0, 'C');
        letra_estilo_normal($pdf);
        $pdf->Cell(65, 5, $articulos[0]['estado'], 1, 1, 'C');

        letra_estilo_negrita($pdf);
        $pdf->Cell(30, 5, 'Fecha Compra', 1, 0, 'C');
        letra_estilo_normal($pdf);
        $fecha_compra = !empty($articulos[0]['fecha_compra']) ? date('d/m/Y', strtotime($articulos[0]['fecha_compra'])) : '';
        $pdf->Cell(65, 5, $fecha_compra, 1, 0, 'C');
        letra_estilo_negrita($pdf);
        $pdf->Cell(30, 5, 'Fecha Referencia', 1, 0, 'C');
        letra_estilo_normal($pdf);
        $fecha_in = !empty($articulos[0]['fecha_in']) ? date('d/m/Y', strtotime($articulos[0]['fecha_in'])) : '';
        $pdf->Cell(65, 5, $fecha_in, 1, 1, 'C');

        letra_estilo_negrita($pdf);
        $pdf->Cell(30, 5, 'Categoría', 1, 0, 'C');
        letra_estilo_normal($pdf);
        $pdf->Cell(65, 5, '', 1, 0, 'C');
        letra_estilo_negrita($pdf);
        $pdf->Cell(30, 5, 'Subcategoría', 1, 0, 'C');
        letra_estilo_normal($pdf);
        $pdf->Cell(65, 5, '', 1, 1, 'C');

        letra_estilo_negrita($pdf);
        $pdf->Cell(30, 5, 'Cod. Grupo Activos', 1, 0, 'C');
        letra_estilo_normal($pdf);
        $pdf->Cell(65, 5, '', 1, 0, 'C');
        letra_estilo_negrita($pdf);
        $pdf->Cell(30, 5, 'Periódo Contable', 1, 0, 'C');
        letra_estilo_normal($pdf);
        $pdf->Cell(65, 5, '', 1, 1, 'C');

        // Propietario / Año
        $propi = 'PROPIO';
        letra_estilo_negrita($pdf);
        $pdf->Cell(30, 5, 'Propietario', 1, 0, 'C');
        letra_estilo_normal($pdf);
        $pdf->Cell(65, 5, $propi, 1, 0, 'C');
        letra_estilo_negrita($pdf);
        $pdf->Cell(30, 5, 'Año', 1, 0, 'C');
        letra_estilo_normal($pdf);
        $pdf->Cell(65, 5, date('Y'), 1, 1, 'C');

        // Línea separadora
        $pdf->Ln(5);

        // Marca / Modelo / Serie
        letra_estilo_negrita($pdf);
        $pdf->Cell(18, 5, 'Marca', 1, 0, 'C');
        letra_estilo_normal($pdf);
        $pdf->Cell(45.3, 5, $articulos[0]['marca'], 1, 0, 'C');
        letra_estilo_negrita($pdf);
        $pdf->Cell(18, 5, 'Modelo', 1, 0, 'C');
        letra_estilo_normal($pdf);
        $pdf->Cell(45.3, 5, $articulos[0]['modelo'], 1, 0, 'C');
        letra_estilo_negrita($pdf);
        $pdf->Cell(18, 5, 'Serie', 1, 0, 'C');
        letra_estilo_normal($pdf);
        $pdf->Cell(45.3, 5, $articulos[0]['serie'], 1, 1, 'C');

        // Color / Vida útil /
        letra_estilo_negrita($pdf);
        $pdf->Cell(18, 5, 'Color', 1, 0, 'C');
        letra_estilo_normal($pdf);
        $pdf->Cell(45.3, 5, $articulos[0]['color'], 1, 0, 'C');
        letra_estilo_negrita($pdf);
        $pdf->Cell(18, 5, 'Género', 1, 0, 'C');
        letra_estilo_normal($pdf);
        $pdf->Cell(45.3, 5, $articulos[0]['genero'], 1, 0, 'C');
        letra_estilo_negrita($pdf);
        $pdf->Cell(18, 5, 'Vida Activo', 1, 0, 'C');
        letra_estilo_normal($pdf);
        $pdf->Cell(45.3, 5, '', 1, 1, 'L');

        // Factura / Costo
        letra_estilo_negrita($pdf);
        $pdf->Cell(18, 5, 'Factura:', 1, 0, 'C');
        letra_estilo_normal($pdf);
        $pdf->Cell(45.3, 5, '', 1, 0, 'C');
        letra_estilo_negrita($pdf);
        $pdf->Cell(18, 5, 'Precio', 1, 0, 'C');
        $precio = number_format((float)$articulos[0]['precio'], 2, '.', '');
        letra_estilo_normal($pdf);
        $pdf->Cell(45.3, 5, '$' . $precio, 1, 0, 'C');
        letra_estilo_negrita($pdf);
        $pdf->Cell(18, 5, 'Oficina:', 1, 0, 'C');
        letra_estilo_normal($pdf);
        $pdf->Cell(45.3, 5, '', 1, 1, 'C');

        $pdf->Ln(5);

        // Características
        letra_estilo_negrita($pdf);
        $pdf->Cell(190, 5, 'Caracteristicas:', 0, 1, 'L');
        letra_estilo_normal($pdf);
        $pdf->MultiCell(190, 5, $articulos[0]['caracteristica'], 0, 'L');
        $pdf->Ln(2);

        // Trayectoria
        letra_estilo_negrita($pdf);
        $pdf->Cell(190, 5, 'Trayectoria:', 0, 1, 'L');
        $pdf->Ln(2);

        letra_estilo_negrita($pdf);
        $pdf->Cell(40, 5, 'Ultima depreciación', 1, 0, 'C');
        $pdf->Cell(40, 5, 'Costo Original', 1, 0, 'C');
        $pdf->Cell(35, 5, 'Depreciación', 1, 0, 'C');
        $pdf->Cell(40, 5, 'Depreciación Acumulada', 1, 0, 'C');
        $pdf->Cell(35, 5, 'Saldo en libro', 1, 1, 'C');
        letra_estilo_normal($pdf);
        $pdf->Cell(40, 5, '', 1, 0, 'C');
        $pdf->Cell(40, 5, '$' . $precio, 1, 0, 'C');
        $pdf->Cell(35, 5, '', 1, 0, 'C');
        $pdf->Cell(40, 5, '', 1, 0, 'C');
        $pdf->Cell(35, 5, '', 1, 1, 'C');

        $pdf->Ln(2);

        // Anexos
        letra_estilo_negrita($pdf);
        $pdf->Cell(190, 5, 'Anexos:', 0, 1, 'L');
        $pdf->Ln(2);

        $img_url = $ruta_img;

        // Establecer altura deseada en milímetros (por ejemplo, 30 mm)
        $fixed_height = 50;

        // Obtener tamaño real de la imagen en píxeles
        $img_info = getimagesize($img_url);
        $img_px_width = $img_info[0];
        $img_px_height = $img_info[1];

        // Calcular proporción ancho/alto
        $aspect_ratio = $img_px_width / $img_px_height;

        // Calcular ancho proporcional en milímetros
        $img_mm_width = $fixed_height * $aspect_ratio;

        // Obtener ancho total de la página
        $page_width = $pdf->getPageWidth();

        // Calcular X para centrar horizontalmente
        $x = ($page_width - $img_mm_width) / 2;
        $y = $pdf->GetY(); // Mantener la altura actual

        // Insertar imagen con altura fija y ancho proporcional, centrada horizontalmente
        $pdf->Image($img_url, $x, $y, $img_mm_width, $fixed_height, 'GIF');


        // Nombre del archivo
        $fileName = 'cedula_activo_' . 'SKU_' . time() . '.pdf';
        ob_end_clean();


        if ($mostrar == true) {
            $pdf->Output($fileName, 'D');
        } else {
            $pdf->Output($fileName, 'I');
        }
    }


    function letra_estilo_negrita($pdf)
    {
        $pdf->SetFont('helvetica', 'B', 8);
    }

    function letra_estilo_normal($pdf)
    {
        $pdf->SetFont('helvetica', '', 8);
    }
