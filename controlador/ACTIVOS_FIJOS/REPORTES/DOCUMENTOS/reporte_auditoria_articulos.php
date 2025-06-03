 <?php
    require_once(dirname(__DIR__, 4) . '/lib/TCPDF/tcpdf.php');


    function pdf_reporte_auditoria_articulos($auditoria, $custodio, $localizacion, $custodioDato, $localizacionDato, $id_persona, $mostrar)
    {
        try {


            if (empty($auditoria)) {
                return ['estado' => 'error', 'mensaje' => 'No se encontraron artículos de auditoría.'];
            }


            $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

            // Configurar documento
            $pdf->SetCreator('TCPDF');
            $pdf->SetAuthor('Sistema de Activos Fijos');
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
            $pdf->SetXY($x_logo + $ancho_logo, $y_inicial + ($alto_celda - ($pdf->GetStringHeight($ancho_celda, "Auditoría de Artículos \npor " . $titulo))) / 2);
            $pdf->MultiCell($ancho_celda, 6, "Auditoría de Artículos \npor " . $titulo, 0, 'C', false);

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

            $pdf->Ln(10);

            $pdf->SetFont('helvetica', 'B', 12);
            if (!empty($custodio) && !empty($localizacion)) {
                $pdf->MultiCell(190, 7, 'Listado de Artículos Asignados a: ' . $custodio[0]['PERSON_NOM'] . " \nUbicación: " . $localizacion[0]['EMPLAZAMIENTO'], 0, 1, 'L');
            } elseif (!empty($custodio)) {
                $pdf->MultiCell(190, 7, 'Listado de Artículos Asignados a: ' . $custodio[0]['PERSON_NOM'], 0, 1, 'L');
            } elseif (!empty($localizacion)) {
                $pdf->MultiCell(190, 7, 'Inventario de Artículos Asignados a la Ubicación: ' . $localizacion[0]['EMPLAZAMIENTO'], 0, 1, 'L');
            }

            $pdf->SetFont('helvetica', 'B', 9);

            $estados = [
                1 => 'Artículos que Coinciden',
                2 => 'Artículos Faltantes',
                3 => 'Artículos que No Coinciden'
            ];

            foreach ($estados as $estado => $tituloTabla) {
                // Título de sección
                $pdf->Ln(5);
                $pdf->SetFont('helvetica', 'B', 9);
                $pdf->Cell(0, 6, $tituloTabla, 0, 1, 'L');

                // Establecer color de fondo para la cabecera
                switch ($estado) {
                    case 1:
                        $pdf->SetFillColor(173, 216, 230); // Azul claro
                        break;
                    case 2:
                        $pdf->SetFillColor(255, 182, 193); // Rojo claro
                        break;
                    case 3:
                        $pdf->SetFillColor(255, 255, 204); // Amarillo claro
                        break;
                    default:
                        $pdf->SetFillColor(220, 220, 220); // Gris claro
                        break;
                }

                // Cabecera
                $pdf->SetFont('helvetica', 'B', 8);
                $pdf->Cell(45, 6, 'RFID', 1, 0, 'C', true);
                $pdf->Cell(40, 6, 'Descripción', 1, 0, 'C', true);
                $pdf->Cell(50, 6, 'Características', 1, 0, 'C', true);
                $pdf->Cell(25, 6, 'Pertenece', 1, 0, 'C', true);
                $pdf->Cell(30, 6, 'Localización', 1, 1, 'C', true);

                // Contenido
                $pdf->SetFont('helvetica', '', 8);

                foreach ($auditoria as $item) {
                    if ($item['id_estado_articulo'] != $estado) continue;

                    $descripcion = !empty($item['descripcion']) ? $item['descripcion'] : 'S/N';
                    $caracteristica = !empty($item['caracteristica']) ? $item['caracteristica'] : 'S/N';

                    // Custodio y localización
                    if (!empty($item['id_persona'])) {
                        if ($item['id_persona'] == $id_persona) {
                            $pertenece = "Custodio Actual";
                            $ubicacion = "Localización Actual";
                        } else {

                            $custodioEncontrado = $custodioDato->buscar_custodio($item['id_persona']);
                            $localizacionEncontrada = $localizacionDato->buscar_localizacion($item['id_localizacion']);


                            $nombreCompleto = $custodioEncontrado[0]['PERSON_NOM'] ?? 'S/N';
                            $partesNombre = explode(' ', $nombreCompleto);
                            $pertenece = isset($partesNombre[1]) ? $partesNombre[0] . ' ' . $partesNombre[1] : $nombreCompleto;

                            $ubicacion = $localizacionEncontrada[0]['EMPLAZAMIENTO'] ?? 'S/N';
                        }
                    } else {
                        $pertenece = 'S/N';
                        $ubicacion = 'S/N';
                    }

                    // Obtener altura necesaria para cada campo (dependiendo del contenido)
                    $startX = $pdf->GetX();
                    $startY = $pdf->GetY();

                    $h1 = $pdf->getStringHeight(45, $item['tag_unique']);
                    $h2 = $pdf->getStringHeight(40, $descripcion);
                    $h3 = $pdf->getStringHeight(50, $caracteristica);
                    $h4 = $pdf->getStringHeight(25, $pertenece);
                    $h5 = $pdf->getStringHeight(30, $ubicacion);

                    $maxHeight = max($h1, $h2, $h3, $h4, $h5);

                    // Imprimir cada celda sin que se monten
                    $pdf->MultiCell(45, $maxHeight, $item['tag_unique'], 1, 'L', false, 0);
                    $pdf->MultiCell(40, $maxHeight, $descripcion, 1, 'L', false, 0);
                    $pdf->MultiCell(50, $maxHeight, $caracteristica, 1, 'L', false, 0);
                    $pdf->MultiCell(25, $maxHeight, $pertenece, 1, 'L', false, 0);
                    $pdf->MultiCell(30, $maxHeight, $ubicacion, 1, 'L', false, 1);
                }
            }

            $pdf->Ln(5);
            $pdf->SetFont('helvetica', '', 10);
            $pdf->SetFillColor(255, 255, 255);
            $pdf->MultiCell(190, 6, 'Fecha de generación: ' . date('d/m/Y'), 0, 1, 'L');
            $pdf->MultiCell(190, 6, 'Generado por: ' . ($_SESSION['usuario'] ?? 'Sistema'), 0, 1, 'L');

            $fileName = 'reporte_auditoria' . 'SKU_' . time() . '.pdf';
            ob_end_clean();

            if ($mostrar == true) {
                $pdf->Output($fileName, 'D');
            } else {
                $pdf->Output($fileName, 'I');
            }
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Error al generar el PDF: ' . $e->getMessage()
            ];
        }
    }
