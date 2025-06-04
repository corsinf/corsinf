 <?php
    require_once(dirname(__DIR__, 4) . '/lib/TCPDF/tcpdf.php');

    function pdf_reporte_articulos_custodio_localizacion($articulos, $custodio, $localizacion, $mostrar)
    {

        if (empty($articulos)) {
            return ['estado' => 'error', 'mensaje' => 'No hay artículos.'];
        }

       
        // Crear instancia de TCPDF
        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

        // Configurar documento
        $pdf->SetCreator('TCPDF');
        $pdf->SetAuthor('CORSINF');
        $pdf->SetTitle('Reporte de Artículos por Persona');
        $pdf->SetSubject('Listado de Artículos');

        // Eliminar cabecera/pie de página por defecto
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        // Añadir página
        $pdf->AddPage();

        // Configurar fuentes y colores
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->SetTextColor(0, 0, 0); // Negro
        $pdf->SetFillColor(255, 255, 255); // Fondo blanco
        $pdf->SetDrawColor(0, 0, 0); // Bordes negros

        // Definir la ruta del logo
        $ruta_logo = dirname(__DIR__, 2) . '/img/empresa/179263446600111.jpeg';

        // Coordenadas y tamaño del logo
        $x_logo = 10;
        $y_logo = 10;
        $ancho_logo = 30;
        $alto_logo = 20;

        // Coordenadas del título
        $x_titulo = $x_logo + $ancho_logo + 5;
        $y_titulo = $y_logo;

        // Añadir logo si existe
        if (file_exists($ruta_logo)) {
            $pdf->Image($ruta_logo, $x_logo, $y_logo, $ancho_logo, $alto_logo, 'JPEG');
        } else {
            $pdf->SetXY($x_logo, $y_logo);
            $pdf->Cell($ancho_logo, $alto_logo, 'LOGO', 1, 0, 'C', true);
        }

        // Guardar posición inicial
        $y_inicial = $pdf->GetY();

        // Configurar dimensiones de la celda del título
        $ancho_celda = 105;
        $alto_celda = 20;

        // Crear celda para el título
        $pdf->Cell($ancho_celda, $alto_celda, '', 1, 0, 'C', true);

        $titulo = "";

        if (!empty($custodio) && !empty($localizacion)) {
            $titulo = "Custodio y Localizacion";
        } elseif (!empty($custodio)) {
            $titulo = "Custodio";
        } elseif (!empty($localizacion)) {
            $titulo = "Localización";
        }

        // Posicionar el título en el centro de la celda
        $pdf->SetXY($x_logo + $ancho_logo, $y_inicial + ($alto_celda - ($pdf->GetStringHeight($ancho_celda, "Inventario de Artículos \npor " . $titulo))) / 2);
        $pdf->MultiCell($ancho_celda, 6, "Inventario de Artículos \npor " . $titulo, 0, 'C', false);

        // Restaurar posición Y para la tabla derecha
        $pdf->SetY($y_inicial);

        // Agregar los datos a la derecha
        $pdf->SetFont('helvetica', '', 9);
        $pdf->SetXY(145, $y_inicial);
        $pdf->Cell(20, 7, 'Código', 1, 0, 'L', true);
        $pdf->Cell(35, 7, 'GD-GTH-PR-001', 1, 1, 'L', true);

        $pdf->SetXY(145, $pdf->GetY());
        $pdf->Cell(20, 7, 'Versión', 1, 0, 'L', true);
        $pdf->Cell(35, 7, '1.0', 1, 1, 'L', true);

        $pdf->SetXY(145, $pdf->GetY());
        $pdf->Cell(20, 6, 'Página', 1, 0, 'L', true);
        $pdf->Cell(35, 6, '1 de ' . $pdf->getAliasNbPages(), 1, 1, 'L', true);

        $pdf->Ln(5);

        // Agregar información de la persona
        if (isset($articulos[0]['persona'])) {
            $pdf->SetFont('helvetica', 'B', 10);
            $pdf->Cell(190, 7, 'INFORMACIÓN DE LA PERSONA', 0, 1, 'L');
            $pdf->SetFont('helvetica', '', 10);
            $pdf->Cell(40, 6, 'Código:', 0, 0, 'L');
            $pdf->Cell(150, 6, 1, 0, 1, 'L');
            $pdf->Cell(40, 6, 'Nombre:', 0, 0, 'L');
            $pdf->Cell(150, 6, $articulos[0]['persona'] ?? 'No disponible', 0, 1, 'L');
        }


        // Cabecera de la tabla de artículos
        $pdf->SetFont('helvetica', 'B', 10);

        if (!empty($custodio) && !empty($localizacion)) {
            $pdf->Cell(190, 7, 'Listado de Artículos Asignados a: ' . $custodio[0]['PERSON_NOM'] . ' — Ubicación: ' . $localizacion[0]['EMPLAZAMIENTO'], 0, 1, 'L');
        } elseif (!empty($custodio)) {
            $pdf->Cell(190, 7, 'Listado de Artículos Asignados a: ' . $custodio[0]['PERSON_NOM'], 0, 1, 'L');
        } elseif (!empty($localizacion)) {
            $pdf->Cell(190, 7, 'Inventario de Artículos Asignados a la Ubicación: ' . $localizacion[0]['EMPLAZAMIENTO'], 0, 1, 'L');
        }
        $pdf->Ln(5);

        $pdf->SetFillColor(220, 220, 220); // Gris claro para la cabecera

        // Cabecera de la tabla
        $pdf->SetFont('helvetica', 'B', 8);

        $pdf->Cell(45, 7, 'RFID', 1, 0, 'C', true);
        $pdf->Cell(45, 7, 'Descripción', 1, 0, 'C', true);
        $pdf->Cell(40, 7, 'Modelo', 1, 0, 'C', true);
        $pdf->Cell(35, 7, 'Serie', 1, 0, 'C', true);
        $pdf->Cell(25, 7, 'Fecha', 1, 1, 'C', true);

        // Datos de la tabla
        $pdf->SetFont('helvetica', '', 8);
        $pdf->SetFillColor(255, 255, 255); // Blanco para los datos

        foreach ($articulos as $articulo) {

            $pdf->Cell(45, 6, $articulo['tag_unique'], 1, 0, 'L', true);

            // Usar MultiCell para descripción (preserva el ancho fijo)
            $x = $pdf->GetX();
            $y = $pdf->GetY();
            $pdf->MultiCell(45, 6, $articulo['descripcion'], 1, 'L', true);
            $pdf->SetXY($x + 45, $y);

            $pdf->Cell(40, 6, $articulo['modelo'], 1, 0, 'L', true);
            $pdf->Cell(35, 6, $articulo['serie'], 1, 0, 'L', true);
            $pdf->Cell(25, 6, soloFecha($articulo['fecha_creacion']), 1, 1, 'R', true);
        }

        // Agregar información adicional al final
        $pdf->Ln(10);
        $pdf->SetFont('helvetica', '', 10);
        $pdf->Cell(190, 6, 'Fecha de generación: ' . date('d/m/Y H:i:s'), 0, 1, 'L');
        $pdf->Cell(190, 6, 'Generado por: ' . ($_SESSION['usuario'] ?? 'Sistema'), 0, 1, 'L');
        $fileName = 'cedula_activo_' . 'SKU_' . time() . '.pdf';
        ob_end_clean();


        if ($mostrar == true) {
            $pdf->Output($fileName, 'D');
        } else {
            $pdf->Output($fileName, 'I');
        }
    }

    function soloFecha($fechaCompleta)
    {
        return substr($fechaCompleta, 0, 10);
    }
