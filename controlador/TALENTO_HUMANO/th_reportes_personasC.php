<?PHP

require_once(dirname(__DIR__, 2) . '/modelo/FIRMADOR/th_personas_firmasM.php');
require_once(dirname(__DIR__, 2) . '/lib/TCPDF/tcpdf.php');

$controlador = new th_reportes_personasC();

if (isset($_GET['imprimirPDF'])) {
    // Llamar al método imprimirPDF
    echo json_encode($controlador->imprimirPDFFormularioSolicitud());
    exit; // Importante: terminar la ejecución después de enviar la respuesta JSON
}

class th_reportes_personasC
{
    private $modelo;

    function __construct()
    {
        //$this->modelo = new th_reportes_personasM();
    }


    function imprimirPDFFormularioSolicitud()
    {

        try {


            $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

            // Set document information
            $pdf->SetCreator('TCPDF');
            $pdf->SetAuthor('Autor del Formulario');
            $pdf->SetTitle('Formulario de Datos Personales');
            $pdf->SetSubject('Formulario TCPDF');

            // Remove default header/footer
            $pdf->setPrintHeader(false);
            $pdf->setPrintFooter(false);



            // Add a page
            $pdf->AddPage();
            // Definir fuentes y colores
            $pdf->SetFont('helvetica', 'B', 12);
            $pdf->SetTextColor(0, 0, 0); // Negro

            // Dibujar la tabla
            $pdf->SetFillColor(255, 255, 255); // Fondo blanco
            $pdf->SetDrawColor(0, 0, 0); // Bordes negros

            // Definir la ruta del logo
            $ruta_logo = dirname(__DIR__, 2) . '\img\empresa\179263446600111.jpeg';
            // Coordenadas y tamaño del logo en el PDF
            $x_logo = 10;
            $y_logo = 10;
            $ancho_logo = 30;
            $alto_logo = 20;
            // Coordenadas del título del formulario (alineado al lado del logo)
            $x_titulo = $x_logo + $ancho_logo; // Se mueve 5px a la derecha del logo
            $y_titulo = $y_logo; // Mantiene la misma altura del logo

            if (file_exists($ruta_logo)) {
                // Si el logo existe, agregarlo al PDF
                $pdf->Image($ruta_logo, $x_logo, $y_logo, $ancho_logo, $alto_logo, 'JPEG');
            } else {
                // Si no existe el logo, dibujamos un cuadro con "LOGO"
                $pdf->SetXY($x_logo, $y_logo);
                $pdf->Cell($ancho_logo, $alto_logo, 'LOGO', 1, 0, 'C', true);
            }

            $pdf->SetFont('helvetica', 'B', 12);


            // Guardar la posición inicial antes de MultiCell
            $y_inicial = $pdf->GetY();

            // Obtener el ancho y alto de la celda deseada
            $ancho_celda = 105;
            $alto_celda = 20;

            // Establecer fuente y fondo
            $pdf->SetFont('helvetica', 'B', 12);

            // Guardar la posición inicial antes de MultiCell
            $x_inicial = $pdf->GetX();
            $y_inicial = $pdf->GetY();

            // Crear una celda envolvente para controlar la posición
            $pdf->Cell($ancho_celda, $alto_celda, '', 1, 0, 'C', true);

            // Posicionar MultiCell en el centro de la celda envolvente
            $pdf->SetXY($x_inicial, $y_inicial + ($alto_celda - ($pdf->GetStringHeight($ancho_celda, "FORMULARIO DE SOLICITUD DE: PERMISO,\nJUSTIFICACIÓN O CARGO A VACACIONES\nDE PERSONAL"))) / 2);
            $pdf->MultiCell($ancho_celda, 6, "FORMULARIO DE SOLICITUD DE: PERMISO,\nJUSTIFICACIÓN O CARGO A VACACIONES\nDE PERSONAL", 0, 'C', false);


            // Restaurar la posición Y para la tabla derecha
            $pdf->SetY($y_inicial);

            // Agregar los datos a la derecha (alineados con el título)
            $pdf->SetFont('helvetica', '', 9); // Reducir tamaño y quitar negrilla en la tabla derecha
            $pdf->SetXY(145, $y_inicial); // Mueve a la posición inicial
            $pdf->Cell(20, 7, 'Código', 1, 0, 'L', true);
            $pdf->Cell(35, 7, 'GD-GTH-PR-001', 1, 1, 'L', true);

            $pdf->SetXY(145, $pdf->GetY()); // Mantiene alineación con el título
            $pdf->Cell(20, 7, 'Versión', 1, 0, 'L', true);
            $pdf->Cell(35, 7, '1.0', 1, 1, 'L', true);

            $pdf->SetXY(145, $pdf->GetY()); // Mantiene alineación con el título
            $pdf->Cell(20, 6, 'Página', 1, 0, 'L', true);
            $pdf->Cell(35, 6, '1 de 4', 1, 1, 'L', true);

            $pdf->Ln(5);


            $pagina_ancho = $pdf->GetPageWidth();
            $margen_derecho = 10; // Margen desde el borde derecho
            $ancho_total = 99;
            $x_inicial = $pagina_ancho - $margen_derecho - $ancho_total;

            // Posicionar el cursor en la parte derecha
            $pdf->SetXY($x_inicial, $pdf->GetY() - 2); // 🔹 Subir 2 unidades la línea para alinearla mejor

            // Asegurar que "Quito," y los campos lleguen hasta el final
            $pdf->Cell(22, 10, 'Quito,', 0, 0, 'R');
            $pdf->Cell(15, 7, '', 'B', 0, 'C'); // Línea para día
            $pdf->Cell(4, 10, 'de', 0, 0, 'C');
            $pdf->Cell(38, 7, '', 'B', 0, 'C'); // Línea para mes
            $pdf->Cell(5, 10, '202', 0, 0, 'C');
            $pdf->Cell(14, 7, '', 'B', 0, 'C'); // Línea para año

            $pdf->Ln(10);


            $pdf->Cell(20, 7, 'NOMBRES:', 0, 0);
            $pdf->Cell(60, 7, '', 'B', 0);
            $pdf->Cell(20, 7, 'CÉDULA:', 0, 0);
            $pdf->Cell(30, 7, '', 'B', 0);
            $pdf->Cell(30, 7, 'ESTADO CIVIL:', 0, 0);

            // Estado Civil
            $yPos = $pdf->GetY(); // Guarda la posición Y antes de imprimir "Soltero/a"

            $pdf->Cell(25, 7, 'Soltero/a', 0, 0);
            $pdf->CheckBox('soltero', 5, false);
            $pdf->Ln(14);

            $pdf->Cell(45, 7, 'CARGO (contrato):', 0, 0);
            $pdf->Cell(35, 7, '', 'B', 0);
            $pdf->Cell(20, 7, 'SECCIÓN:', 0, 0);
            $pdf->Cell(30, 7, '', 'B', 0);
            $pdf->Ln(14);

            // Genero
            $pdf->Cell(20, 7, 'GÉNERO:', 0, 0);
            $pdf->Cell(25, 7, 'Masculino', 0, 0);
            $pdf->CheckBox('masculino', 5, false);
            $pdf->Cell(25, 7, 'Femenino', 0, 0);
            $pdf->CheckBox('femenino', 5, false);
            $pdf->Cell(25, 7, 'No Sabe', 0, 0);
            $pdf->CheckBox('nosabe', 5, false);

            $pdf->SetXY(170, $yPos + 7); // Mueve a la siguiente línea ajustando Y
            $pdf->Cell(25, 7, 'Casado/a', 0, 0);
            $pdf->CheckBox('casado', 5, false);

            $pdf->SetXY(170, $pdf->GetY() + 7); // Mueve a la siguiente línea ajustando Y
            $pdf->Cell(25, 7, 'Unión H.', 0, 0);
            $pdf->CheckBox('union', 5, false);

            // Posicionamiento Ajustado
            $pdf->SetXY(170, $pdf->GetY() + 7); // Mueve a la siguiente línea ajustando Y
            $pdf->Cell(25, 7, 'Divorciado/a', 0, 0);
            $pdf->CheckBox('divorciado', 5, false);

            $pdf->SetXY(170, $pdf->GetY() + 7); // Mueve a la siguiente línea ajustando Y
            $pdf->Cell(25, 7, 'Viudo/a', 0, 0);
            $pdf->CheckBox('Viudo', 5, false);


            $pdf->Ln(10);



            $pdf->Cell(25, 7, 'ASUNTO:', 0, 0);
            $pdf->Cell(35, 7, 'Marque', 0, 0);
            $pdf->Cell(35, 7, '***SOLICITUD', 0, 0);
            $pdf->CheckBox('solicitud', 5, false);
            $pdf->Cell(35, 7, '***JUSTIFICACIÓN', 0, 0);
            $pdf->CheckBox('justificacion', 5, false);
            $pdf->Cell(45, 7, '***CARGO VACACIONES', 0, 0);
            $pdf->CheckBox('cargo_vacaciones', 5, false);
            $pdf->Ln(10);

            // Reason section
            $pdf->SetFillColor(200, 200, 200);

            $pdf->MultiCell(190, 7, 'Motivo', 0, 1, 'L', true);

            $pdf->Ln(5);
            // Column 1 of checkboxes
            $pdf->SetFillColor(255, 255, 255);
            $yPos = $pdf->GetY();
            $pdf->SetX(10);
            $pdf->Cell(85, 7, 'Calamidad Doméstica', 0, 0);
            $pdf->CheckBox('calamidad', 5, false);
            $pdf->Ln(7);

            $pdf->Cell(85, 7, '(Siniestros y Catástrofes)', 0, 1);

            $pdf->Cell(85, 7, 'Fallecimiento (familiares)', 0, 0);
            $pdf->CheckBox('fallecimiento', 5, false);
            $pdf->Ln(7);

            $pdf->Cell(85, 7, '(Adjuntar acta de defunción)', 0, 1);

            $pdf->Cell(40, 7, 'Familiar - hijos', 0, 0);
            $pdf->Cell(45, 7, '0 - 5 años', 0, 0);
            $pdf->CheckBox('hijos_0_5', 5, false);
            $pdf->Ln(7);

            $pdf->Cell(40, 7, '(Rango de Edad)', 0, 0);
            $pdf->Cell(45, 7, '6 - 11 años', 0, 0);
            $pdf->CheckBox('hijos_6_11', 5, false);
            $pdf->Ln(7);

            $pdf->SetX(50);
            $pdf->Cell(45, 7, '12 - 17 años', 0, 0);
            $pdf->CheckBox('hijos_12_17', 5, false);
            $pdf->Ln(7);

            $pdf->Cell(40, 7, 'Familiar - adultos', 0, 0);
            $pdf->Cell(45, 7, 'Discapacidad', 0, 0);
            $pdf->CheckBox('familiar_discapacidad', 5, false);
            $pdf->Ln(7);

            $pdf->Cell(40, 7, '(Cuidado Familiar)', 0, 0);
            $pdf->Cell(45, 7, 'Adulto Mayor', 0, 0);
            $pdf->CheckBox('familiar_adulto_mayor', 5, false);
            $pdf->Ln(7);

            $pdf->SetX(45);
            $pdf->Cell(50, 7, 'Enfermedad Catastrófica', 0, 0);
            $pdf->CheckBox('familiar_enfermedad', 5, false);
            $pdf->Ln(7);

            $pdf->Cell(45, 7, 'Personal (otros - detalle)', 0, 0);
            $pdf->Cell(45, 7, '', 'B', 0); // Línea inferior
            $pdf->Ln(7);

            $pdf->Cell(90, 7, '', 'B', 0);
            $pdf->Ln(7);

            // Column 2 of checkboxes (starting from Y position after "MOTIVO:" label)

            $pdf->SetXY(110,  $yPos);

            $pdf->Cell(85, 7, 'Maternidad / Paternidad', 0, 0);
            $pdf->CheckBox('maternidad', 5, false);
            $pdf->Ln(7);

            $pdf->SetX(110);
            $pdf->Cell(80, 7, '(Adjuntar certificado Nacido Vivo)', 0, 1);

            $pdf->SetX(110);
            $pdf->Cell(85, 7, 'Enfermedad', 0, 0);
            $pdf->CheckBox('enfermedad', 5, false);
            $pdf->Ln(7);

            $pdf->SetX(110);
            $pdf->Cell(80, 7, '(Adjuntar certificado)', 0, 1);

            $pdf->SetX(110);
            $pdf->Cell(85, 7, 'Cita Médica', 0, 0);
            $pdf->CheckBox('cita_medica', 5, false);
            $pdf->Ln(7);

            $pdf->SetX(110);
            $pdf->Cell(80, 7, '(Adjuntar certificado)', 0, 1);

            $pdf->SetX(110);
            $pdf->Cell(45, 7, 'Detalle de Atención Médica', 0, 0);
            $pdf->Cell(40, 7, 'Pública', 0, 0);
            $pdf->CheckBox('atencion_publica', 5, false);
            $pdf->Ln(7);

            $pdf->SetX(110);
            $pdf->Cell(45, 7, '(Enfermedad o Cita Médica)', 0, 0);
            $pdf->Cell(40, 7, 'Privada', 0, 0);
            $pdf->CheckBox('atencion_privada', 5, false);
            $pdf->Ln(7);

            $pdf->SetX(110);
            $pdf->Cell(20, 7, 'Lugar:', 0, 0);
            $pdf->Cell(70, 7, '', 'B', 0);
            $pdf->Ln(7);

            $pdf->SetX(110);
            $pdf->Cell(30, 7, 'Especialidad:', 0, 0);
            $pdf->Cell(60, 7, '', 'B', 0);
            $pdf->Ln(7);

            $pdf->SetX(110);
            $pdf->Cell(40, 7, 'Nombre del Médico:', 0, 0);
            $pdf->Cell(50, 7, '', 'B', 0);
            $pdf->Ln(7);

            $pdf->SetX(110);
            $pdf->Cell(15, 7, 'Fecha:', 0, 0);
            $pdf->Cell(5, 7, '', 'B', 0);
            $pdf->Cell(2, 7, '/', 0, 0);
            $pdf->Cell(5, 7, '', 'B', 0);
            $pdf->Cell(2, 7, '/', 0, 0);
            $pdf->Cell(5, 7, '', 'B', 0);
            $pdf->Cell(10, 7, 'Hora:', 0, 0);
            $pdf->Cell(12, 7, 'Desde', 0, 0);
            $pdf->Cell(5, 7, '', 'B', 0);
            $pdf->Cell(2, 7, 'h', 0, 0);
            $pdf->Cell(5, 7, '', 'B', 0);
            $pdf->Cell(10, 7, 'Hasta:', 0, 0);
            $pdf->Cell(5, 7, '', 'B', 0);
            $pdf->Cell(2, 7, 'h', 0, 0);
            $pdf->Cell(5, 7, '', 'B', 0);

            // Medical Department Section
            $pdf->SetFillColor(200, 200, 200);
            $pdf->Ln(15);

            $pdf->MultiCell(190, 7, 'ESPACIO EXCLUSIVO DEPARTAMENTO MÉDICO:', 0, 1, 'L', true);
            $pdf->SetFillColor(255, 255, 255);
            $pdf->Ln(5);

            $pdf->SetFont('helvetica', '', 9);
            $yPos = $pdf->GetY();

            // Left Column
            $leftColumnWidth = 95; // Half of the 190 width minus some margin

            // Left Column Content
            $pdf->Cell(70, 7, 'Certifico que el/la Trabajador/a requiere de:', 0, 1);

            $pdf->Cell(20, 7, 'REPOSO', 0, 0);
            $pdf->CheckBox('reposo', 5, false);
            $pdf->Cell(20, 0, '', 0, 0);
            $pdf->Cell(40, 7, 'por: Enfermedad General', 0, 0);
            $pdf->CheckBox('reposo_enfermedad', 5, false);
            $pdf->Ln(7);

            $pdf->Cell(20, 7, 'PERMISO', 0, 0);
            $pdf->CheckBox('permiso', 5, false);
            $pdf->Cell(20, 0, '', 0, 0);
            $pdf->Cell(40, 7, 'Asistencia a Consulta', 0, 0);
            $pdf->CheckBox('permiso_consulta', 5, false);
            $pdf->Ln(7);

            $pdf->Cell(20, 7, '(IDG)', 0, 0);
            $pdf->Cell(70, 7, '', 'B', 0);
            $pdf->Ln(7);

            $pdf->Cell(90, 7, '', 'B', 0);
            $pdf->Ln(15);

            // Signature line for left column
            $pdf->Cell(10, 0, '', 0, 0);
            $pdf->Cell(70, 0, '', 'B', 0); // Line for signature
            $pdf->Ln(5);
            $pdf->Cell(10, 7, '', 0, 0);
            $pdf->Cell(70, 7, 'Firma Médico', 0, 1, 'C');

            // Right Column - Set position to the right of the page
            $pdf->SetXY($leftColumnWidth + 10, $yPos); // 10 is margin between columns

            // Right Column Content
            $pdf->Cell(30, 7, 'Observaciones:', 0, 0);
            $pdf->Ln(7);

            $pdf->SetX($leftColumnWidth + 10);
            $pdf->Cell(67, 7, 'PRESENTA CERTIFICADO MÉDICO', 0, 0);
            $pdf->Cell(8, 7, 'SI', 0, 0);
            $pdf->CheckBox('certificado_si', 5, false);
            $pdf->Cell(10, 7, 'NO', 0, 0);
            $pdf->CheckBox('certificado_no', 5, false);
            $pdf->Ln(7);

            $pdf->SetX($leftColumnWidth + 10);
            $pdf->Cell(67, 7, 'PRESENTA CERTIFICADO DE ASISTENCIA', 0, 0);
            $pdf->Cell(8, 7, 'SI', 0, 0);
            $pdf->CheckBox('asistencia_si', 5, false);
            $pdf->Cell(10, 7, 'NO', 0, 0);
            $pdf->CheckBox('asistencia_no', 5, false);
            $pdf->Ln(7);

            $pdf->SetX($leftColumnWidth + 10);
            $pdf->Cell(20, 7, '(Motivo)', 0, 0);
            $pdf->Cell(75, 7, '', 'B', 0);
            $pdf->Ln(7);

            $pdf->SetX($leftColumnWidth + 10);
            $pdf->Cell(15, 7, 'Fecha:', 0, 0);
            $pdf->Cell(10, 7, '', 'B', 0);
            $pdf->Cell(2, 7, '/', 0, 0);
            $pdf->Cell(10, 7, '', 'B', 0);
            $pdf->Cell(2, 7, '/', 0, 0);
            $pdf->Cell(10, 7, '', 'B', 0);
            $pdf->Cell(6, 7, '', 0, 0);
            $pdf->Cell(10, 7, 'Desde', 0, 0);
            $pdf->Cell(10, 7, '', 'B', 0);
            $pdf->Cell(10, 7, 'Hasta:', 0, 0);
            $pdf->Cell(10, 7, '', 'B', 0);
            $pdf->Ln(15);

            // Signature line for right column
            $pdf->SetX($leftColumnWidth + 10);
            $pdf->Cell(10, 0, '', 0, 0);
            $pdf->Cell(70, 0, '', 'B', 0); // Line for signature
            $pdf->Ln(5);
            $pdf->SetX($leftColumnWidth + 10);
            $pdf->Cell(10, 7, '', 0, 0);
            $pdf->Cell(70, 7, 'Firma Médico', 0, 1, 'C');
            $pdf->Ln(20);

            // Permission date and time
            $yPos = $pdf->GetY(); // Obtener la posición actual en la página
            $pageHeight = $pdf->GetPageHeight(); // Altura total de la página
            $marginBottom = 20; // Margen inferior de seguridad

            // Si la posición actual está cerca del final, agregar una nueva página
            if (($yPos + $marginBottom) > $pageHeight) {
                $pdf->AddPage(); // Crear nueva hoja
            }
            $pdf->SetFont('helvetica', 'B', 10);

            $pdf->SetFillColor(200, 200, 200);
            $pdf->MultiCell(190, 7, 'FECHA Y HORA DEL PERMISO:', 0, 1, 'L', true);
            $pdf->SetFillColor(255, 255, 255);
            $pdf->SetFont('helvetica', '', 10);
            $pdf->Ln(5);

            $yPos = $pdf->GetY(); // Guardamos la posición actual

            // Ancho de columnas
            $leftColumnWidth = 95;

            // DESDE - FECHA
            $pdf->Cell(15, 7, 'DESDE:', 0, 0);
            $pdf->Cell(15, 7, '(fecha)', 0, 0);
            $pdf->Cell(10, 0, '', 'B', 0);
            $pdf->Cell(5, 7, '/', 0, 0);
            $pdf->Cell(10, 0, '', 'B', 0);
            $pdf->Cell(5, 7, '/', 0, 0);
            $pdf->Cell(10, 7, '202', 0, 0);
            $pdf->Cell(10, 0, '', 'B', 0);
            $pdf->Cell(20, 7, 'Total Días:', 0, 0);


            // DESDE - HORA
            $pdf->Cell(9, 7, '', 0, 0);
            $pdf->Cell(15, 7, 'DESDE:', 0, 0);
            $pdf->Cell(15, 7, '(hora)', 0, 0);
            $pdf->Cell(10, 0, '', 'B', 0);
            $pdf->Cell(5, 7, 'H', 0, 0);
            $pdf->Cell(10, 0, '', 'B', 0);
            $pdf->Cell(7, 7, '', 0, 0);
            $pdf->Cell(10, 7, 'Total Días:', 0, 0);

            $pdf->Ln(7); // Espacio entre filas


            $pdf->Cell(15, 7, 'HASTA:', 0, 0);
            $pdf->Cell(15, 7, '(fecha)', 0, 0);
            $pdf->Cell(10, 0, '', 'B', 0);
            $pdf->Cell(5, 7, '/', 0, 0);
            $pdf->Cell(10, 0, '', 'B', 0);
            $pdf->Cell(5, 7, '/', 0, 0);
            $pdf->Cell(10, 7, '202', 0, 0);
            $pdf->Cell(10, 0, '', 'B', 0);
            $pdf->Cell(2, 7, '', 0, 0);
            $pdf->Cell(15, 0, '', 'B', 0);


            // Posicionamos "HASTA" en la misma línea
            $pdf->Cell(12, 7, '', 0, 0);
            $pdf->Cell(15, 7, 'HASTA:', 0, 0);
            $pdf->Cell(15, 7, '(hora)', 0, 0);
            $pdf->Cell(10, 0, '', 'B', 0);
            $pdf->Cell(5, 7, 'H', 0, 0);
            $pdf->Cell(10, 0, '', 'B', 0);
            $pdf->Cell(10, 7, '', 0, 0);
            $pdf->Cell(15, 0, '', 'B', 0);

            $pdf->Ln(12); // Espacio final

            // Responsibility section
            $pdf->SetFillColor(200, 200, 200);
            $pdf->MultiCell(190, 7, 'ESPACIO DE RESPONSABILIDAD (ÚNICAMENTE PERSONAL DOCENTE):', 0, 1, 'L', true);
            $pdf->SetFillColor(255, 255, 255);
            $pdf->Cell(0, 7, '', 0, 1);
            $pdf->Cell(60, 7, '*** ANEXO PLANIFICACIÓN', 0, 0);
            $pdf->CheckBox('anexo_planificacion', 5, false);
            $pdf->Cell(60, 7, '*** NO REQUIERE PLANIFICACIÓN', 0, 0);
            $pdf->CheckBox('no_requiere_planificacion', 5, false);
            $pdf->Ln(7);

            $pdf->MultiCell(0, 7, 'Durante mi inasistencia la/las persona/s que realizará/n el reemplazo, se encuentran previamente informado/s sobre las actividades que desarrollaran por cada día de mi ausencia mediante documento anexo (Formato de Planificación Microcurricular por Ausencia).', 0, 'L');
            $pdf->Ln(3);

            $pdf->SetFont('helvetica', '', 8);
            $pdf->MultiCell(0, 5, '*** El área de Inspección, comunicará a la comunidad académica (padres de familia, representantes u otros) según corresponda sobre la inasistencia del Docente, coadyuvado además a la presentación del Docente de remplazo.', 0, 'L');
            $pdf->Ln(7);

            $pdf->SetFont('helvetica', '', 10);



            // Definir anchos de columnas
            $col1 = 90;
            $col2 = 70;

            // Obtener ancho total de la página
            $pagina_ancho = $pdf->GetPageWidth();
            $margen_izquierdo = 10; // Margen izquierdo predeterminado
            $margen_derecho = 10; // Margen derecho predeterminado
            $ancho_utilizable = $pagina_ancho - $margen_izquierdo - $margen_derecho;

            // Ancho de cada línea de firma
            $ancho_firma = 60;
            // Espacio en el centro
            $espacio_central = 30;
            // Calcular posición inicial para centrar
            $pos_x = ($ancho_utilizable - (2 * $ancho_firma) - $espacio_central) / 2 + $margen_izquierdo;

            // Segunda línea con descuentos
            $pdf->Cell($ancho_firma - 5, 7, '', 0, 0); // Espacio en la primera columna
            $pdf->Cell(35, 7, 'Observaciones', 0, 0);
            $pdf->Cell(46, 7, 'NO APLICA DESCUENTO', 0, 0);
            $pdf->CheckBox('no_aplica_descuento', 5, false);
            $pdf->Cell(44, 7, 'APLICA DESCUENTO', 0, 0);
            $pdf->CheckBox('aplica_descuento', 5, false);
            $pdf->Ln(20);
            // Dibujar las líneas de firma centradas
            $pdf->SetX($pos_x);
            $pdf->Cell($ancho_firma, 0, '', 'B', 0);
            $pdf->Cell($espacio_central, 0, '', 0, 0); // Espacio en el centro
            $pdf->Cell($ancho_firma, 0, '', 'B', 1); // Segunda línea de firma
            $pdf->Ln(5);

            // Etiquetas debajo de las firmas
            $pdf->SetX($pos_x);
            $pdf->Cell($ancho_firma, 7, 'FIRMA ELECTRÓNICA', 0, 0, 'C');
            $pdf->Cell($espacio_central, 7, '', 0, 0);
            $pdf->Cell($ancho_firma, 7, 'AUTORIZADO', 0, 1, 'C');
            $pdf->Ln(10);





            $pdf->SetFont('helvetica', '', 8);
            $pdf->MultiCell(0, 5, '*** Registrar el motivo de la solicitud y anexar la documentación (remplazo) de conformidad a la "política" establecida.', 0, 'L');
            $pdf->Ln(7);



            $tempDir = dirname(__DIR__, 2) . '/temp/';
            if (!file_exists($tempDir)) {
                mkdir($tempDir, 0777, true);
            }
            $fileName = 'formulario_' . time() . '.pdf';
            $filePath = $tempDir . $fileName;

            // Guardar el PDF en el servidor
            $pdf->Output($filePath, 'F');
            $relativePath = str_replace($_SERVER['DOCUMENT_ROOT'], '', $filePath);

            return [
                'success' => true,
                'ruta' => $relativePath,
                'message' => 'PDF generado correctamente'
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Error al generar el PDF: ' . $e->getMessage()
            ];
        }
    }

    function imprimirPDF()
    {

        function crearCampo($pdf, $etiqueta, $x, $y, $valor = '')
        {
            // Color azul cielo SOLO para la etiqueta
            $pdf->SetFillColor(173, 216, 230);

            // Etiqueta con fondo de color
            $pdf->SetFont('helvetica', 'B', 8);
            $pdf->SetXY($x, $y);
            $pdf->MultiCell(40, 5, $etiqueta . ':', 0, 0, 'L', true); // Se activa el fondo solo aquí

            // Restablecer fondo blanco para el valor
            $pdf->SetFillColor(255, 255, 255);

            // Valor con borde negro
            $pdf->SetFont('helvetica', '', 8);
            $pdf->SetXY($x + 42, $y);  // Ajustamos la posición para dar un padding-right más pequeño
            $pdf->MultiCell(50, 5, $valor, 1, 0, 'L', false); // Aquí se agrega un borde negro (1)

            // Ajuste de la posición Y para el siguiente campo, se añade un pequeño espacio (padding-bottom)
            $y += 12; // Espacio ajustado para el padding-bottom (puedes modificar este valor si necesitas más o menos espacio)
        }

        function crearCampoAncho($pdf, $etiqueta, $y, $valor = '')
        {
            $pdf->SetFillColor(173, 216, 230);

            // Etiqueta con fondo de color
            $pdf->SetFont('helvetica', 'B', 8);
            $pdf->SetXY(12, $y);
            $pdf->MultiCell(40, 5, $etiqueta . ':', 0, 0, 'L', true); // Se activa el fondo solo aquí

            // Restablecer fondo blanco para el valor
            $pdf->SetFillColor(255, 255, 255);

            // Valor con borde negro y centrado dentro de su celda
            $pdf->SetFont('helvetica', '', 8);
            $pdf->SetXY(54, $y); // Posición X justo después de la etiqueta
            $pdf->MultiCell(143, 5, $valor, 1, 'C', false); // Ahora el valor se extiende hasta el final de la segunda columna

            // Ajuste de la posición Y para el siguiente campo
            return $y + 6; // Espacio ajustado para la siguiente línea
        }

        function crearCampoMovilizacion($pdf, $etiqueta, $x, $y, $valor, $col_width, $row_height)
        {
            // Color azul cielo SOLO para la etiqueta
            $pdf->SetFillColor(173, 216, 230);

            // Etiqueta con fondo de color
            $pdf->SetFont('helvetica', 'B', 8);
            $pdf->SetXY($x, $y);
            $pdf->MultiCell(18, $row_height, $etiqueta . ':', 0, 0, 'L', true); // Fondo azul SOLO en la etiqueta

            // Restablecer fondo blanco para el valor
            $pdf->SetFillColor(255, 255, 255);

            // Valor sin fondo
            $pdf->SetFont('helvetica', '', 8);
            $pdf->SetXY($x + 20, $y);  // Reducimos un poco el padding-right (ajustando la posición X)
            $pdf->MultiCell($col_width, $row_height, $valor, 1, 0, 'L', false); // Sin fondo

            // Ajuste de la posición Y para el siguiente campo (espacio añadido para padding-bottom)
            $y += $row_height + 2;  // Añadimos un poco de espacio para padding-bottom (puedes ajustar el valor si es necesario)
        }


        $datos = [
            [
                'clase' => 'Clase A',
                'nombres' => 'Juan Carlos',
                'apellidos' => 'Pérez Gómez',
                'cedula' => '1712345678',
                'codigo_dactilar' => 'ABCD12345',
                'fecha_nacimiento' => '1990-05-15',
                'estado_civil' => 'Soltero',
                'nacionalidad' => 'Ecuatoriano',
                'tipo_visa' => 'Turista',
                'numero_visa' => 'VT123456',
                'fecha_vencimiento_visa' => '2024-12-31',
                'provincia' => 'Pichincha',
                'canton' => 'Quito',
                'parroquia' => 'Centro Histórico',
                'origen_indigena' => 'No',
                'sexo' => 'Masculino',
                'identidad_genero' => 'Hombre',
                'orientacion' => 'Heterosexual',
                'tipo_discapacidad' => 'Ninguna',
                'etnia' => 'Mestizo',
                'calle_principal' => 'Av. Amazonas',
                'calle_secundaria' => 'Calle Juan León Mera',
                'numero_vivienda' => 'N45-12',
                'tipo_vivienda' => 'Departamento',
                'ocupacion_hogar' => 'Propietario',
                'zona_sector_barrio' => 'La Mariscal',
                'referencia' => 'Cerca del Parque El Ejido',
                'telefono_domicilio' => '02-2501234',
                'numero_piso_vivienda' => '5',
                'telefono_celular' => '0991234567',
                'correo_electronico' => 'juan.perez@example.com',
                'nombres_apellidos_1' => 'María López',
                'parentesco_1' => 'Madre',
                'telefono_domicilio_1' => '02-2405678',
                'telefono_celular_1' => '0987654321',
                'nombres_apellidos_2' => 'Pedro Ramírez',
                'parentesco_2' => 'Amigo',
                'telefono_domicilio_2' => '02-2609876',
                'telefono_celular_2' => '0978901234',
                'vehiculo' => 'Sí',
                'propietario' => 'Juan Carlos Pérez Gómez',
                'telefono' => '0991234567',
                'clase' => 'Automóvil',
                'tipo' => 'Sedán',
                'placa' => 'ABC-1234',
                'marca' => 'Toyota',
                'modelo' => 'Corolla',
                'ano' => '2020',
                'color1' => 'Gris',
                'color2' => 'No aplica',
                'licencia' => 'Tipo B',
                'deportes_que_practica' => 'Fútbol, natación',
                'pasatiempos_favoritos' => 'Lectura, cine',
                'consumos_nocivos' => 'No',
                'seguro_vida_privado' => 'Sí',
                'asistencia_psicologica' => 'No',
                'grupo_sanguineo' => 'O+',
                'enfermedades' => 'Ninguna',
                'religion' => 'Católico',
                'conocimiento_oferta' => 'Redes sociales',
                'integra_agrupaciones' => 'No',
                'trabajo_conyugue' => 'Sí',
                'detalle_agrupacion' => 'No aplica',
                'valor_ingresos_mensuales' => '1200',
                'cargo_cp' => 'Analista',
                'integro_grupos_laborales' => 'Sí',
                'remuneracion_cp' => '1500',
                'parientes_en_institucion' => 'No',
                'total_ingresos' => '2700',
            ]
        ];

        $clase = $datos[0]['clase'] ?? '';
        $nombres = $datos[0]['nombres'] ?? '';
        $apellidos = $datos[0]['apellidos'] ?? '';
        $cedula = $datos[0]['cedula'] ?? '';
        $codigo_dactilar = $datos[0]['codigo_dactilar'] ?? '';
        $fecha_nacimiento = $datos[0]['fecha_nacimiento'] ?? '';
        $estado_civil = $datos[0]['estado_civil'] ?? '';
        $nacionalidad = $datos[0]['nacionalidad'] ?? '';
        $tipo_visa = $datos[0]['tipo_visa'] ?? '';
        $numero_visa = $datos[0]['numero_visa'] ?? '';
        $fecha_vencimiento_visa = $datos[0]['fecha_vencimiento_visa'] ?? '';
        $provincia = $datos[0]['provincia'] ?? '';
        $canton = $datos[0]['canton'] ?? '';
        $parroquia = $datos[0]['parroquia'] ?? '';
        $origen_indigena = $datos[0]['origen_indigena'] ?? '';
        $sexo = $datos[0]['sexo'] ?? '';
        $identidad_genero = $datos[0]['identidad_genero'] ?? '';
        $orientacion = $datos[0]['orientacion'] ?? '';
        $tipo_discapacidad = $datos[0]['tipo_discapacidad'] ?? '';
        $etnia = $datos[0]['etnia'] ?? '';


        // Datos segunda sección
        $calle_principal = $datos[0]['calle_principal'] ?? '';
        $calle_secundaria = $datos[0]['calle_secundaria'] ?? '';
        $numero_vivienda = $datos[0]['numero_vivienda'] ?? '';
        $tipo_vivienda = $datos[0]['tipo_vivienda'] ?? '';
        $ocupacion_hogar = $datos[0]['ocupacion_hogar'] ?? '';

        $canton = $datos[0]['canton'] ?? '';
        $parroquia = $datos[0]['parroquia'] ?? '';
        $zona_sector_barrio = $datos[0]['zona_sector_barrio'] ?? '';
        $referencia = $datos[0]['referencia'] ?? '';
        $telefono_domicilio = $datos[0]['telefono_domicilio'] ?? '';

        $numero_piso_vivienda = $datos[0]['numero_piso_vivienda'] ?? '';
        $telefono_celular = $datos[0]['telefono_celular'] ?? '';
        $correo_electronico = $datos[0]['correo_electronico'] ?? '';
        $clase = $datos[0]['clase'] ?? '';  // Variable adicional

        // Datos de emergencia
        $nombres_apellidos_1 = $datos[0]['nombres_apellidos_1'] ?? '';
        $parentesco_1 = $datos[0]['parentesco_1'] ?? '';
        $telefono_domicilio_1 = $datos[0]['telefono_domicilio_1'] ?? '';
        $telefono_celular_1 = $datos[0]['telefono_celular_1'] ?? '';

        $nombres_apellidos_2 = $datos[0]['nombres_apellidos_2'] ?? '';
        $parentesco_2 = $datos[0]['parentesco_2'] ?? '';
        $telefono_domicilio_2 = $datos[0]['telefono_domicilio_2'] ?? '';
        $telefono_celular_2 = $datos[0]['telefono_celular_2'] ?? '';


        // datos familiares 
        $datos_familiares = [
            ['Gerardo Luis Santilla Torres', 'Hijo/a', '20/6/2010', '0984975811', 'Estudiante', '026197815'],
            ['Soledad Eduarda Torres Tobar', 'Esposo/a', '15/8/1987', '1547896755', 'Arquitecto', '023684915'],
            ['Maria Luisa Santilla Torres', 'Hijo/a', '26/9/2016', '0786948755', 'Estudiante', '023541975']
        ];

        // informacion de cargas familiares

        $datos_cargas = [
            ['Maria Luisa Santilla Torres', 'Hijo/a', 'Sí', 'Enfermedad Rara o Huérfana', 'Ninguna', '0%', 'SI'],
            ['Gerardo Luis Santilla Torres', 'Hijo/a', 'Sí', 'Cédula Ciudadanía EC', 'Física', '50%', 'SI']
        ];

        // referencias no familiares 

        $datos_referencias = [
            ['Gonzalo Eduardo Fajardo Ruiz', 'Amigo/a', '55', 'Empleado Privado (Arca S.A.)', 'Calle Maldonado y Calle Rioverde', '0998457895'],
            ['Lourdes Kerly Vivas Vilema', 'Amigo/a', '45', 'Empleado Privado (FastFood)', 'Calle 12 de octubre y Madrid', '0987124897']
        ];

        // educacion academica

        $formaciones_academicas = [
            [
                'nivel_instruccion' => 'Tercer Nivel',
                'titulo_obtenido' => 'Ingeniería en Sistemas Computacionales',
                'unidad_educativa' => 'Universidad Politécnica',
                'pais' => 'Ecuador',
                'cuarto_nivel' => 'NO',
                'registro_senecyt' => '123456',
                'motivo' => 'Abandonado',
                'fecha_inicio' => '01/01/2012',
                'fecha_fin' => '31/12/2015'
            ],
            [
                'nivel_instruccion' => 'Cuarto Nivel',
                'titulo_obtenido' => 'Máster en Administración de Empresas',
                'unidad_educativa' => 'Universidad Internacional',
                'pais' => 'EE.UU.',
                'cuarto_nivel' => 'SÍ',
                'registro_senecyt' => '654321',
                'motivo' => 'Culminado',
                'fecha_inicio' => '01/01/2016',
                'fecha_fin' => '31/12/2018'
            ],
            [
                'nivel_instruccion' => 'Tercer Nivel',
                'titulo_obtenido' => 'Licenciatura en Ciencias de la Educación',
                'unidad_educativa' => 'Universidad Nacional',
                'pais' => 'Ecuador',
                'cuarto_nivel' => 'NO',
                'registro_senecyt' => '789123',
                'motivo' => 'Culminado',
                'fecha_inicio' => '01/01/2008',
                'fecha_fin' => '31/12/2012'
            ]
        ];

        // conocimientos lengua extranjera

        $idiomas = [
            ['Inglés', 'Cambridge', 'Cambridge Institute', 'B2'],
            ['Francés', 'DELF', 'Alliance Française', 'B1'],
            ['Alemán', 'Goethe-Zertifikat', 'Goethe-Institut', 'A2']
        ];

        // datos de conocimiento 

        $datos_conocimientos = [
            [
                'paquetes_utilitarios' => 'Office: 64%',
                'base_de_datos' => 'SQL Server',
                'herramientas_graficas' => 'Adobe, Pixpa, Affinity',
                'otros_conocimientos' => 'Python',
                'registro_profesional_1' => 'Medicina',
                'numero_o_codigo_1' => '1784275994',
                'registro_profesional_2' => 'Contabilidad',
                'numero_o_codigo_2' => 'SAINT DOMINIC SCHOOL',
                'idiomas' => 'Inglés, Español', // Ejemplo adicional
                'habilidades_tecnicas' => 'Desarrollo web, Diseño gráfico' // Otro ejemplo adicional
            ],
            [
                'paquetes_utilitarios' => 'Office: 64%',
                'base_de_datos' => 'SQL Server',
                'herramientas_graficas' => 'Adobe, Pixpa, Affinity',
                'otros_conocimientos' => 'Python',
                'registro_profesional_1' => 'Medicina',
                'numero_o_codigo_1' => '1784275994',
                'registro_profesional_2' => 'Contabilidad',
                'numero_o_codigo_2' => 'SAINT DOMINIC SCHOOL',
                'idiomas' => 'Inglés, Español', // Ejemplo adicional
                'habilidades_tecnicas' => 'Desarrollo web, Diseño gráfico' // Otro ejemplo adicional
            ],
        ];


        // experiencia laborar

        $datos_experiencia = [
            [
                'institucion_empresa' => 'SAINT DOMINIC SCHOOL',
                'cargo_puesto' => 'DOCENTE',
                'motivo_salida' => 'ACTUAL',
                'fecha_ingreso' => '16 de junio de 2014',
                'tiempo_laborado' => 'Año/Mes/Día',
                'sector_empresarial' => 'Privado',
                'ultima_remuneracion' => '700,00 - 900,00',
                'fecha_salida' => '',
                'figura_legal' => 'Contrato Indefinido',
                'telefono_empresa' => '022648444 / 0998457895',
                'nombre_jefe_inmediato' => 'Alberto Zamora',
            ],
            [
                'institucion_empresa' => 'EMPRESA XYZ',
                'cargo_puesto' => 'GERENTE DE VENTAS',
                'motivo_salida' => 'Finalizado contrato',
                'fecha_ingreso' => '01 de marzo de 2015',
                'tiempo_laborado' => '2 años, 6 meses',
                'sector_empresarial' => 'Privado',
                'ultima_remuneracion' => '1,200,00',
                'fecha_salida' => '30 de agosto de 2017',
                'figura_legal' => 'Contrato Temporal',
                'telefono_empresa' => '022648888 / 0998451122',
                'nombre_jefe_inmediato' => 'Carlos Pérez',
            ]
        ];

        //informacion de eventos de capacitación 
        $datos_eventos = [
            [
                'nombre_evento' => 'Curso de Programación PHP',
                'tipo_evento' => 'Taller',
                'duracion_horas' => '40 horas',
                'institucion_auspiciante' => 'Universidad Técnica',
                'tipo_certificado' => 'Certificado de Participación',
                'fecha_inicio' => '01 de enero de 2023',
                'pais' => 'Ecuador',
                'fecha_fin' => '30 de enero de 2023'
            ],
            [
                'nombre_evento' => 'Diplomado en Desarrollo Web',
                'tipo_evento' => 'Diplomado',
                'duracion_horas' => '120 horas',
                'institucion_auspiciante' => 'Universidad de Tecnología',
                'tipo_certificado' => 'Diploma de Especialización',
                'fecha_inicio' => '15 de marzo de 2023',
                'pais' => 'Perú',
                'fecha_fin' => '15 de julio de 2023'
            ]
        ];

        // datos bancarios 
        $institucion_financiera = $datos[0]['institucion_financiera'] ?? 'Produbanco';
        $tipo_cuenta = $datos[0]['tipo_cuenta'] ?? 'Ahorros';
        $numero_cuenta = $datos[0]['numero_cuenta'] ?? '1208562700';

        //datos vehiculo 
        $vehiculo = $datos[0]['vehiculo'] ?? '';
        $propietario = $datos[0]['propietario'] ?? '';
        $telefono = $datos[0]['telefono'] ?? '';

        $clase = $datos[0]['clase'] ?? '';
        $tipo = $datos[0]['tipo'] ?? '';
        $placa = $datos[0]['placa'] ?? '';

        $marca = $datos[0]['marca'] ?? '';
        $modelo = $datos[0]['modelo'] ?? '';
        $ano = $datos[0]['ano'] ?? '';

        $color1 = $datos[0]['color1'] ?? '';
        $color2 = $datos[0]['color2'] ?? '';
        $licencia = $datos[0]['licencia'] ?? '';

        //habitos personales
        $deportes_que_practica = $datos[0]['deportes_que_practica'] ?? '';
        $pasatiempos_favoritos = $datos[0]['pasatiempos_favoritos'] ?? '';
        $consumos_nocivos = $datos[0]['consumos_nocivos'] ?? '';
        $seguro_vida_privado = $datos[0]['seguro_vida_privado'] ?? '';

        $asistencia_psicologica = $datos[0]['asistencia_psicologica'] ?? '';
        $grupo_sanguineo = $datos[0]['grupo_sanguineo'] ?? '';
        $enfermedades = $datos[0]['enfermedades'] ?? '';
        $religion = $datos[0]['religion'] ?? '';

        // informacion complementario
        $conocimiento_oferta = $datos[0]['conocimiento_oferta'] ?? '';
        $integra_agrupaciones = $datos[0]['integra_agrupaciones'] ?? '';
        $trabajo_conyugue = $datos[0]['trabajo_conyugue'] ?? '';
        $detalle_agrupacion = $datos[0]['detalle_agrupacion'] ?? '';

        $valor_ingresos_mensuales = $datos[0]['valor_ingresos_mensuales'] ?? '';
        $cargo_cp = $datos[0]['cargo_cp'] ?? '';
        $integro_grupos_laborales = $datos[0]['integro_grupos_laborales'] ?? '';
        $remuneracion_cp = $datos[0]['remuneracion_cp'] ?? '';
        $parientes_en_institucion = $datos[0]['parientes_en_institucion'] ?? '';

        $total_ingresos = $datos[0]['total_ingresos'] ?? '';



        try {
            // Asegúrate de que TCPDF esté incluido correctamente
            // require_once('tcpdf_include.php');

            $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

            // Set document information
            $pdf->SetCreator('TCPDF');
            $pdf->SetAuthor('Autor del Formulario');
            $pdf->SetTitle('Formulario de Datos Personales');
            $pdf->SetSubject('Formulario TCPDF');

            // Remove default header/footer
            $pdf->setPrintHeader(false);
            $pdf->setPrintFooter(false);



            // Add a page
            $pdf->AddPage();
            // Definir fuentes y colores
            $pdf->SetFont('helvetica', 'B', 12);
            $pdf->SetTextColor(0, 0, 0); // Negro

            // Dibujar la tabla
            $pdf->SetFillColor(255, 255, 255); // Fondo blanco
            $pdf->SetDrawColor(0, 0, 0); // Bordes negros

            // Definir la ruta del logo
            $ruta_logo = dirname(__DIR__, 2) . '\img\empresa\179263446600111.jpeg';
            // Coordenadas y tamaño del logo en el PDF
            $x_logo = 10;
            $y_logo = 10;
            $ancho_logo = 30;
            $alto_logo = 20;
            // Coordenadas del título del formulario (alineado al lado del logo)
            $x_titulo = $x_logo + $ancho_logo; // Se mueve 5px a la derecha del logo
            $y_titulo = $y_logo; // Mantiene la misma altura del logo

            if (file_exists($ruta_logo)) {
                // Si el logo existe, agregarlo al PDF
                $pdf->Image($ruta_logo, $x_logo, $y_logo, $ancho_logo, $alto_logo, 'JPEG');
            } else {
                // Si no existe el logo, dibujamos un cuadro con "LOGO"
                $pdf->SetXY($x_logo, $y_logo);
                $pdf->Cell($ancho_logo, $alto_logo, 'LOGO', 1, 0, 'C', true);
            }

            $pdf->SetXY($x_titulo, $y_titulo);

            $pdf->Cell(104, 10, 'Formulario de datos', 'LTR', 2, 'C', true); // Solo borde arriba y lados
            $pdf->Cell(104, 10, 'Personales y Profesionales', 'LRB', 0, 'C', true); // Solo lados y borde inferior

            // Agregar los datos a la derecha (alineados con el título)
            $pdf->SetFont('helvetica', '', 9); // Reducir tamaño y quitar negrilla en la tabla derecha
            $pdf->SetXY(143, $pdf->GetY() - 10); // Mueve la posición arriba para que inicie con el título
            $pdf->Cell(20, 7, 'Código', 1, 0, 'L', true);
            $pdf->Cell(35, 7, 'GD-GTH-PR-001', 1, 1, 'L', true);

            $pdf->SetXY(143, $pdf->GetY()); // Mantiene alineación con el título
            $pdf->Cell(20, 7, 'Versión', 1, 0, 'L', true);
            $pdf->Cell(35, 7, '1.0', 1, 1, 'L', true);

            $pdf->SetXY(143, $pdf->GetY()); // Mantiene alineación con el título
            $pdf->Cell(20, 6, 'Página', 1, 0, 'L', true); // Borde en la parte inferior para alinear
            $pdf->Cell(35, 6, '1 de 4', 1, 1, 'L', true); // Se asegura de que termine alineado


            // Reset colors and font for rest of document
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetFillColor(255, 255, 255);
            $pdf->SetFont('helvetica', '', 10);

            $pdf->Ln(5);
            $pdf->SetLineWidth(0.3); // Ancho del borde (opcional, ajusta el grosor)
            $pdf->SetDrawColor(0, 0, 0); // Establece el color del borde a negro

            // --- SECCIÓN 1: INFORMACIÓN PERSONAL ---
            $y = 30; // Posición inicial

            // === SECCIÓN 1: INFORMACIÓN PERSONAL ===
            $seccionAltura = 97;
            $pdf->SetFont('helvetica', 'B', 10);
            $pdf->SetCellMargins(0, 0, 0);
            $pdf->MultiCell(190, 7, '1. INFORMACIÓN PERSONAL', 0, 1, 'L', true);

            $y_start = $y + 7; // Posición inicial después del título

            // Establecer el color de fondo azul para la etiqueta 'Fotografía'
            $pdf->SetFillColor(173, 216, 230);

            // Establecer la fuente para la etiqueta (negrita)
            $pdf->SetFont('helvetica', 'B', 8);

            // Establecer la posición para la etiqueta 'Fotografía'
            $pdf->SetXY(105, $y_start + 8);

            // Mostrar la etiqueta 'Fotografía' con fondo de color azul
            $pdf->Cell(40, 5, 'Fotografía:', 0, 0, 'L', true);

            // Ajustar la posición Y para el siguiente campo
            $y_start += 6; // Ajuste para pasar al siguiente campo

            // Definir la ruta de la imagen
            $ruta_imagen = dirname(__DIR__, 2) . '\img\custodios\1401.jpeg';

            // Coordenadas y tamaño de la imagen
            $x_imagen = 147;
            $y_imagen = $y_start + 2;
            $ancho_imagen = 50;
            $alto_imagen = 54;

            if (file_exists($ruta_imagen)) {
                // Si la imagen existe, se muestra en el PDF
                $pdf->Image($ruta_imagen, $x_imagen, $y_imagen, $ancho_imagen, $alto_imagen, 'JPG');
            } else {
                // Si la imagen no existe, mostrar el recuadro con el texto de referencia
                $pdf->SetXY(147, $y_start + 2);
                $pdf->MultiCell(50, 54, '', 1, 'C');  // Campo de fotografía con borde

                $pdf->SetFillColor(255, 255, 255); // Fondo blanco
                $pdf->SetFont('helvetica', '', 8);

                // Agregar texto de referencia dentro del recuadro
                $pdf->SetXY(150, $y_start + 9);
                $pdf->MultiCell(40, 5, 'Fotografía:', 0, 'C');

                $pdf->SetXY(150, $y_start + 15);
                $pdf->MultiCell(40, 5, 'Tamaño Carné', 0, 'C');

                $pdf->SetXY(150, $y_start + 20);
                $pdf->MultiCell(40, 5, '(Física o Digital)', 0, 'C');
            }


            // Primera columna (izquierda)
            crearCampo($pdf, 'Nombres', 12, $y_start + 2, $nombres);
            crearCampo($pdf, 'Apellidos', 12, $y_start + 9, $apellidos);
            crearCampo($pdf, 'No. de Cédula EC', 12, $y_start + 16, $cedula);
            crearCampo($pdf, 'Código Dactilar', 12, $y_start + 23, $codigo_dactilar);
            crearCampo($pdf, 'Fecha de Nacimiento', 12, $y_start + 30, $fecha_nacimiento);
            crearCampo($pdf, 'Estado Civil', 12, $y_start + 37, $estado_civil);
            crearCampo($pdf, 'Nacionalidad (natal)', 12, $y_start + 44, $nacionalidad);
            crearCampo($pdf, 'Tipo de Visa (extranjero)', 12, $y_start + 51, $tipo_visa);
            crearCampo($pdf, 'No. de Visa (extranjero)', 12, $y_start + 58, $numero_visa);
            crearCampo($pdf, 'Fecha Vencimiento Visa', 12, $y_start + 65, $fecha_vencimiento_visa);
            crearCampo($pdf, 'Provincia', 12, $y_start + 72, $provincia);
            crearCampo($pdf, 'Cantón', 12, $y_start + 79, $canton);
            crearCampo($pdf, 'Parroquia', 12, $y_start + 86, $parroquia);
            crearCampoAncho($pdf, 'Origen Indígena', $y_start + 93, $origen_indigena);

            // Segunda columna (derecha)
            crearCampo($pdf, 'Tipo de Discapacidad', 105, $y_start + 58, $tipo_discapacidad);
            crearCampo($pdf, 'Sexo', 105, $y_start + 65, $sexo);  // Ajuste del espacio
            crearCampo($pdf, 'Etnia', 105, $y_start + 72, $etnia);  // Ajuste del espacio
            crearCampo($pdf, 'Identidad de Género', 105, $y_start + 79, $identidad_genero);  // Ajuste del espacio
            crearCampo($pdf, 'Cantón', 105, $y_start + 86, $canton);  // Ajuste del espacio


            // Ajuste de posición para la siguiente sección
            $y = $y_start + $seccionAltura + 5; // Se suma 5 para dejar un pequeño margen

            // === SECCIÓN 2: DATOS DOMICILIARIOS ===
            $seccionAltura = 50;
            $pdf->SetFont('helvetica', 'B', 10);
            $pdf->SetCellMargins(0, 0, 0);
            $pdf->SetXY(10, $y); // Asegura que el título inicie en la nueva línea
            $pdf->MultiCell(190, 7, '2. DATOS DOMICILIARIOS', 0, 1, 'L', true);

            $y_start = $y + 7; // Ahora inicia correctamente después del título

            // Primera columna (izquierda)
            crearCampo($pdf, 'Calle Principal', 12, $y_start + 2, $calle_principal);
            crearCampo($pdf, 'Calle Secundaria', 12, $y_start + 9, $calle_secundaria);
            crearCampo($pdf, 'Número de Vivienda', 12, $y_start + 16, $numero_vivienda);
            crearCampo($pdf, 'Tipo de Vivienda', 12, $y_start + 23, $tipo_vivienda);
            crearCampo($pdf, 'Ocupación del Hogar', 12, $y_start + 30, $ocupacion_hogar);
            crearCampo($pdf, 'Número Piso de Vivienda', 12, $y_start + 37, $numero_piso_vivienda);
            crearCampo($pdf, 'Número del Hogar', 12, $y_start + 44, $clase);

            // Segunda columna (derecha)
            crearCampo($pdf, 'Cantón', 105, $y_start + 2, $canton);
            crearCampo($pdf, 'Parroquia', 105, $y_start + 9, $parroquia);
            crearCampo($pdf, 'Zona/Sector/Barrio', 105, $y_start + 16, $zona_sector_barrio);
            crearCampo($pdf, 'Referencia', 105, $y_start + 23, $referencia);

            $pdf->SetFont('helvetica', 'B', 8);
            $pdf->SetXY(105, $y_start + 30);
            $pdf->SetFillColor(173, 216, 230);
            $pdf->MultiCell(40, 5, 'Teléfono Domicilio:', 0, 0, 'L', true);

            // División del valor en dos columnas para telefono_celular_1
            $telefono_domicilio_1_1 = substr($telefono_domicilio_1, 0, strlen($telefono_domicilio_1) / 2); // Primera mitad
            $telefono_domicilio_1_2 = substr($telefono_domicilio_1, strlen($telefono_domicilio_1) / 2); // Segunda mitad

            // Primera columna (primera mitad del teléfono celular) con 24 de ancho
            $pdf->SetFont('helvetica', '', 8);
            $pdf->SetFillColor(255, 255, 255); // Fondo blanco
            $pdf->SetXY(147, $y_start + 30); // Ajusta la posición según sea necesario
            $pdf->MultiCell(24, 5, $telefono_domicilio_1_1, 1, 0, 'L', false); // 24 para la primera columna

            // Segunda columna (segunda mitad del teléfono celular) con un pequeño espacio entre ellas
            $pdf->SetXY(173, $y_start + 30); // Ajusta la posición para la segunda columna (agregando un pequeño espacio)
            $pdf->MultiCell(24, 5, $telefono_domicilio_1_2, 1, 0, 'L', false); // 24 para la segunda columna


            $pdf->SetFont('helvetica', 'B', 8);
            $pdf->SetXY(105, $y_start + 37);
            $pdf->SetFillColor(173, 216, 230);
            $pdf->MultiCell(40, 5, 'Teléfono Celular:', 0, 0, 'L', true);

            // División del valor en dos columnas para telefono_celular_1
            $telefono_celular_1_1 = substr($telefono_celular_1, 0, strlen($telefono_celular_1) / 2); // Primera mitad
            $telefono_celular_1_2 = substr($telefono_celular_1, strlen($telefono_celular_1) / 2); // Segunda mitad

            // Primera columna (primera mitad del teléfono celular) con 24 de ancho
            $pdf->SetFont('helvetica', '', 8);
            $pdf->SetFillColor(255, 255, 255); // Fondo blanco
            $pdf->SetXY(147, $y_start + 37); // Ajusta la posición según sea necesario
            $pdf->MultiCell(24, 5, $telefono_celular_1_1, 1, 0, 'L', false); // 24 para la primera columna

            // Segunda columna (segunda mitad del teléfono celular) con un pequeño espacio entre ellas
            $pdf->SetXY(173, $y_start + 37); // Ajusta la posición para la segunda columna (agregando un pequeño espacio)
            $pdf->MultiCell(24, 5, $telefono_celular_1_2, 1, 0, 'L', false); // 24 para la segunda columna


            crearCampo($pdf, 'Correo Electrónico', 105, $y_start + 44, $correo_electronico);

            // Ajuste de posición para la siguiente sección
            $y = $y_start + $seccionAltura + 5;


            // === SECCIÓN 3: CONTACTOS DE EMERGENCIA ===

            $seccionAltura = 50;

            $pdf->SetFont('helvetica', 'B', 10);

            $pdf->SetCellMargins(0, 0, 0);

            $pdf->SetXY(10, $y); // Asegura que el título inicie en la nueva línea

            $pdf->MultiCell(190, 7, '3. CONTACTOS DE EMERGENCIA', 0, 1, 'L', true);

            $y_start = $y + 7; // Inicia justo debajo del título

            // Primera fila de contactos

            $pdf->SetFont('helvetica', 'B', 8);
            $pdf->SetFillColor(173, 216, 230); // Color azul cielo para los títulos secundarios
            $pdf->SetXY(12, $y_start + 2);
            $pdf->MultiCell(40, 5, 'Nombres y Apellidos:', 0, 0, 'L', true);

            $pdf->SetFont('helvetica', '', 8);
            $pdf->SetXY(54, $y_start + 2);
            $pdf->SetFillColor(255, 255, 255);
            $pdf->MultiCell(50, 5, $nombres_apellidos_1, 1, 0, 'L', false);

            $pdf->SetFont('helvetica', 'B', 8);
            $pdf->SetXY(105, $y_start + 2);
            $pdf->SetFillColor(173, 216, 230);
            $pdf->MultiCell(40, 5, 'Parentesco:', 0, 0, 'L', true);

            $pdf->SetFont('helvetica', '', 8);
            $pdf->SetXY(147, $y_start + 2);
            $pdf->SetFillColor(255, 255, 255);
            $pdf->MultiCell(50, 5, $parentesco_1, 1, 0, 'L', false);

            $pdf->SetFont('helvetica', 'B', 8);
            $pdf->SetXY(12, $y_start + 9);
            $pdf->SetFillColor(173, 216, 230);
            $pdf->MultiCell(40, 5, 'Teléfono Domicilio:', 0, 0, 'L', true);

            // División del valor en dos columnas
            $telefono_domicilio_1_1 = substr($telefono_domicilio_1, 0, strlen($telefono_domicilio_1) / 2); // Primer mitad
            $telefono_domicilio_1_2 = substr($telefono_domicilio_1, strlen($telefono_domicilio_1) / 2); // Segunda mitad

            // Primera columna (primera mitad del teléfono) con 24 de ancho
            $pdf->SetFont('helvetica', '', 8);
            $pdf->SetFillColor(255, 255, 255); // Fondo blanco
            $pdf->SetXY(54, $y_start + 9); // Ajusta la posición según sea necesario
            $pdf->MultiCell(24, 5, $telefono_domicilio_1_1, 1, 0, 'L', false); // 24 para la primera columna

            // Segunda columna (segunda mitad del teléfono) con un pequeño espacio entre ellas
            $pdf->SetXY(80, $y_start + 9); // Ajusta la posición para la segunda columna (agregando un pequeño espacio)
            $pdf->MultiCell(24, 5, $telefono_domicilio_1_2, 1, 0, 'L', false); // 24 para la segunda columna


            $pdf->SetFont('helvetica', 'B', 8);
            $pdf->SetXY(105, $y_start + 9);
            $pdf->SetFillColor(173, 216, 230);
            $pdf->MultiCell(40, 5, 'Teléfono Celular:', 0, 0, 'L', true);

            // División del valor en dos columnas para telefono_celular_1
            $telefono_celular_1_1 = substr($telefono_celular_1, 0, strlen($telefono_celular_1) / 2); // Primera mitad
            $telefono_celular_1_2 = substr($telefono_celular_1, strlen($telefono_celular_1) / 2); // Segunda mitad

            // Primera columna (primera mitad del teléfono celular) con 24 de ancho
            $pdf->SetFont('helvetica', '', 8);
            $pdf->SetFillColor(255, 255, 255); // Fondo blanco
            $pdf->SetXY(147, $y_start + 9); // Ajusta la posición según sea necesario
            $pdf->MultiCell(24, 5, $telefono_celular_1_1, 1, 0, 'L', false); // 24 para la primera columna

            // Segunda columna (segunda mitad del teléfono celular) con un pequeño espacio entre ellas
            $pdf->SetXY(173, $y_start + 9); // Ajusta la posición para la segunda columna (agregando un pequeño espacio)
            $pdf->MultiCell(24, 5, $telefono_celular_1_2, 1, 0, 'L', false); // 24 para la segunda columna


            // Segunda fila de contactos

            $pdf->SetFont('helvetica', 'B', 8);
            $pdf->SetXY(12, $y_start + 16);
            $pdf->SetFillColor(173, 216, 230);
            $pdf->MultiCell(40, 5, 'Nombres y Apellidos:', 0, 0, 'L', true);

            $pdf->SetFont('helvetica', '', 8);
            $pdf->SetXY(54, $y_start + 16);
            $pdf->SetFillColor(255, 255, 255);
            $pdf->MultiCell(50, 5, $nombres_apellidos_2, 1, 0, 'L', false);

            $pdf->SetFont('helvetica', 'B', 8);
            $pdf->SetXY(105, $y_start + 16);
            $pdf->SetFillColor(173, 216, 230);
            $pdf->MultiCell(40, 5, 'Parentesco:', 0, 0, 'L', true);

            $pdf->SetFont('helvetica', '', 8);
            $pdf->SetXY(147, $y_start + 16);
            $pdf->SetFillColor(255, 255, 255);
            $pdf->MultiCell(50, 5, $parentesco_2, 1, 0, 'L', false);

            $pdf->SetFont('helvetica', 'B', 8);
            $pdf->SetXY(12, $y_start + 23);
            $pdf->SetFillColor(173, 216, 230);
            $pdf->MultiCell(40, 5, 'Teléfono Domicilio:', 0, 0, 'L', true);
            // División del valor en dos columnas para telefono_domicilio_2
            $telefono_domicilio_2_1 = substr($telefono_domicilio_2, 0, strlen($telefono_domicilio_2) / 2); // Primera mitad
            $telefono_domicilio_2_2 = substr($telefono_domicilio_2, strlen($telefono_domicilio_2) / 2); // Segunda mitad

            // Primera columna (primera mitad del teléfono domicilio 2) con 24 de ancho
            $pdf->SetFont('helvetica', '', 8);
            $pdf->SetFillColor(255, 255, 255); // Fondo blanco
            $pdf->SetXY(54, $y_start + 23); // Ajusta la posición según sea necesario
            $pdf->MultiCell(24, 5, $telefono_domicilio_2_1, 1, 0, 'L', false); // 24 para la primera columna

            // Segunda columna (segunda mitad del teléfono domicilio 2) con un pequeño espacio entre ellas
            $pdf->SetXY(80, $y_start + 23); // Ajusta la posición para la segunda columna (agregando un pequeño espacio)
            $pdf->MultiCell(24, 5, $telefono_domicilio_2_2, 1, 0, 'L', false); // 24 para la segunda columna


            $pdf->SetFont('helvetica', 'B', 8);
            $pdf->SetXY(105, $y_start + 23);
            $pdf->SetFillColor(173, 216, 230);
            $pdf->MultiCell(40, 5, 'Teléfono Celular:', 0, 0, 'L', true);

            // División del valor en dos columnas para telefono_celular_2
            $telefono_celular_2_1 = substr($telefono_celular_2, 0, strlen($telefono_celular_2) / 2); // Primera mitad
            $telefono_celular_2_2 = substr($telefono_celular_2, strlen($telefono_celular_2) / 2); // Segunda mitad

            // Primera columna (primera mitad del teléfono celular 2) con 24 de ancho
            $pdf->SetFont('helvetica', '', 8);
            $pdf->SetFillColor(255, 255, 255); // Fondo blanco
            $pdf->SetXY(147, $y_start + 23); // Ajusta la posición según sea necesario
            $pdf->MultiCell(24, 5, $telefono_celular_2_1, 1, 0, 'L', false); // 24 para la primera columna

            // Segunda columna (segunda mitad del teléfono celular 2) con un pequeño espacio entre ellas
            $pdf->SetXY(173, $y_start + 23); // Ajusta la posición para la segunda columna (agregando un pequeño espacio)
            $pdf->MultiCell(24, 5, $telefono_celular_2_2, 1, 0, 'L', false); // 24 para la segunda columna

            $espaciado_titulo = 7; // Espacio uniforme entre el título y su contenido

            // Ajuste de posición para la siguiente sección
            $y = $y_start + $seccionAltura + 5;

            if ($y + $seccionAltura > 270) { // Si está cerca del final de la página
                $pdf->AddPage(); // Agregar nueva página
                $y = 20; // Resetear posición Y
            }


            // === SECCIÓN 4: INFORMACIÓN FAMILIAR (Convivientes Actuales) ===
            $seccionAltura = 22;
            $pdf->SetFont('helvetica', 'B', 10);
            $pdf->SetCellMargins(0, 0, 0);
            $pdf->SetXY(10, $y); // Asegura que el título inicie en la nueva línea
            $pdf->MultiCell(190, 7, '4. INFORMACIÓN FAMILIAR (Convivientes Actuales)', 0, 1, 'L', true);

            $y_start = $y + $espaciado_titulo; // Aplicar espaciado uniforme

            // Cabecera de la tabla de convivientes
            $pdf->SetFont('helvetica', 'B', 8);
            $pdf->SetFillColor(240, 240, 240);
            $pdf->SetXY(12, $y_start + 2);
            $pdf->Cell(50, 5, 'Nombres y Apellidos', 1, 0, 'C', true);
            $pdf->Cell(20, 5, 'Parentesco', 1, 0, 'C', true);
            $pdf->Cell(30, 5, 'Fecha Nacimiento', 1, 0, 'C', true);
            $pdf->Cell(35, 5, 'No. Cédula/Pasaporte', 1, 0, 'C', true);
            $pdf->Cell(25, 5, 'Ocupación', 1, 0, 'C', true);
            $pdf->Cell(25, 5, 'Teléfono', 1, 1, 'C', true);

            // Datos de convivientes
            $pdf->SetFont('helvetica', '', 7);

            foreach ($datos_familiares as $fila) {
                $pdf->SetX(12);
                $pdf->Cell(50, 5, $fila[0], 1, 0, 'C');
                $pdf->Cell(20, 5, $fila[1], 1, 0, 'C');
                $pdf->Cell(30, 5, $fila[2], 1, 0, 'C');
                $pdf->Cell(35, 5, $fila[3], 1, 0, 'C');
                $pdf->Cell(25, 5, $fila[4], 1, 0, 'C');
                $pdf->Cell(25, 5, $fila[5], 1, 1, 'C');
            }

            // Ajuste de posición para la siguiente sección
            $y = $y_start + $seccionAltura + 5;


            // Verificar si hay suficiente espacio para sección 5
            if ($y > 220) { // Si está cerca del final de la página
                $pdf->AddPage(); // Agregar nueva página
                $y = 20; // Resetear posición Y
            }

            // === SECCIÓN 5: INFORMACIÓN DE CARGAS FAMILIARES ===
            $seccionAltura = 18;

            $pdf->SetFont('helvetica', 'B', 10);
            $pdf->SetCellMargins(0, 0, 0);
            $pdf->SetXY(10, $y); // Asegura que el título inicie en la nueva línea
            $pdf->SetFillColor(255, 255, 255);
            $pdf->MultiCell(190, 7, '5. INFORMACIÓN DE CARGAS FAMILIARES (Impuesto a la renta - SRI)', 0, 1, 'L', true);

            $y_start = $y + $espaciado_titulo;

            // Cabecera de la tabla
            $pdf->SetFont('helvetica', 'B', 7);
            $pdf->SetFillColor(240, 240, 240);
            $pdf->SetXY(12, $y_start + 2);
            $pdf->Cell(40, 5, 'Nombres y Apellidos', 1, 0, 'C', true);
            $pdf->Cell(15, 5, 'Parentesco', 1, 0, 'C', true);
            $pdf->Cell(25, 5, 'Carga Familiar', 1, 0, 'C', true);
            $pdf->Cell(40, 5, 'Certificado/Aval', 1, 0, 'C', true);
            $pdf->Cell(25, 5, 'Discapacidad', 1, 0, 'C', true);
            $pdf->Cell(15, 5, 'Porcentaje', 1, 0, 'C', true);
            $pdf->Cell(25, 5, 'Autorización IR', 1, 1, 'C', true);

            // Datos de cargas familiares
            $pdf->SetFont('helvetica', '', 7);
            foreach ($datos_cargas as $fila) {
                $pdf->SetX(12);
                $pdf->Cell(40, 5, $fila[0], 1, 0, 'C');
                $pdf->Cell(15, 5, $fila[1], 1, 0, 'C');
                $pdf->Cell(25, 5, $fila[2], 1, 0, 'C');
                $pdf->Cell(40, 5, $fila[3], 1, 0, 'C');
                $pdf->Cell(25, 5, $fila[4], 1, 0, 'C');
                $pdf->Cell(15, 5, $fila[5], 1, 0, 'C');
                $pdf->Cell(25, 5, $fila[6], 1, 1, 'C');
            }

            // Ajuste de posición para la siguiente sección
            $y = $y_start + $seccionAltura + 5;

            // === SECCIÓN 6: REFERENCIAS NO FAMILIARES ===
            $seccionAltura = 18;
            $pdf->SetFont('helvetica', 'B', 10);
            $pdf->SetCellMargins(0, 0, 0);
            $pdf->SetXY(10, $y);
            $pdf->SetFillColor(255, 255, 255);
            $pdf->MultiCell(190, 7, '6. REFERENCIAS NO FAMILIARES (Certificado de Honorabilidad)', 0, 1, 'L', true);

            $y_start = $y + $espaciado_titulo;

            // Cabecera de la tabla
            $pdf->SetFont('helvetica', 'B', 8);
            $pdf->SetFillColor(240, 240, 240);
            $pdf->SetXY(12, $y_start + 2);
            $pdf->Cell(50, 5, 'Nombres y Apellidos', 1, 0, 'C', true);
            $pdf->Cell(20, 5, 'Parentesco', 1, 0, 'C', true);
            $pdf->Cell(10, 5, 'Edad', 1, 0, 'C', true);
            $pdf->Cell(35, 5, 'Ocupación', 1, 0, 'C', true);
            $pdf->Cell(40, 5, 'Dirección', 1, 0, 'C', true);
            $pdf->Cell(30, 5, 'Teléfono', 1, 1, 'C', true);

            // Datos de referencias no familiares
            $pdf->SetFont('helvetica', '', 7);
            foreach ($datos_referencias as $fila) {
                $pdf->SetX(12);
                $pdf->Cell(50, 5, $fila[0], 1, 0, 'C');
                $pdf->Cell(20, 5, $fila[1], 1, 0, 'C');
                $pdf->Cell(10, 5, $fila[2], 1, 0, 'C');
                $pdf->Cell(35, 5, $fila[3], 1, 0, 'C');
                $pdf->Cell(40, 5, $fila[4], 1, 0, 'C');
                $pdf->Cell(30, 5, $fila[5], 1, 1, 'C');
            }

            // Ajuste de posición para la siguiente sección
            $y = $y_start + $seccionAltura + 5;

            // === SECCIÓN 7: EDUCACIÓN ACADÉMICA ===
            $seccionAltura = 87;
            $pdf->SetFont('helvetica', 'B', 10);
            $pdf->SetCellMargins(0, 0, 0);
            $pdf->SetXY(10, $y);
            $pdf->SetFillColor(255, 255, 255);
            $pdf->MultiCell(190, 7, '7. EDUCACIÓN ACADÉMICA (Tres más reciente - culminado y/o en estudios)', 0, 1, 'L', true);

            $y_start = $y + 7;

            // Datos de educación académica
            foreach ($formaciones_academicas as $formacion) {
                if (!empty($formacion['nivel_instruccion'])) {
                    crearCampo($pdf, 'Nivel de Instrucción', 12, $y_start + 2, $formacion['nivel_instruccion']);
                    crearCampo($pdf, 'Título Obtenido', 105, $y_start + 2, $formacion['titulo_obtenido']);
                    crearCampoAncho($pdf, 'Unidad Educativa', $y_start + 11, $formacion['unidad_educativa']);
                    crearCampo($pdf, 'País', 105, $y_start + 18, $formacion['pais']); // Espacio aumentado a +16
                    crearCampo($pdf, 'Cuarto Nivel', 12, $y_start + 18, $formacion['cuarto_nivel']); // Espacio aumentado a +16

                    // Incremento del espacio entre estos campos
                    crearCampo($pdf, 'Nro Registro SENESCYT', 105, $y_start + 25, $formacion['registro_senecyt']); // Espacio incrementado
                    crearCampo($pdf, 'Motivo/Horario/Otros:', 12, $y_start + 25, $formacion['motivo']); // Espacio incrementado
                    crearCampo($pdf, 'Fecha Inicio', 105, $y_start + 32, $formacion['fecha_inicio']); // Espacio incrementado
                    crearCampo($pdf, 'Fecha Fin', 12, $y_start + 32, $formacion['fecha_fin']); // Espacio incrementado

                    // Ajustar la posición Y para la siguiente formación académica
                    $y_start += 37; // Incremento más grande para evitar sobreposición
                }
            }

            // Ajuste de espacio para la siguiente sección
            $y = $y_start + 7;


            if ($y > 240) { // Si está muy cerca del final de la página
                $pdf->AddPage(); // Agregar nueva página
                $y = 20; // Resetear posición Y
            }

            // === SECCIÓN 8: CONOCIMIENTOS LENGUA EXTRANJERA ===
            $seccionAltura = 27;
            $pdf->SetFont('helvetica', 'B', 10);
            $pdf->SetCellMargins(0, 0, 0);
            $pdf->SetXY(10, $y);
            $pdf->MultiCell(190, 7, '8. CONOCIMIENTOS LENGUA EXTRANJERA (Actual)', 0, 1, 'L', true);

            $y_start = $y + 7; // Inicia después del título

            // Cabecera de la fila
            $pdf->SetFont('helvetica', 'B', 8);
            $pdf->SetFillColor(240, 240, 240);
            $pdf->SetXY(12, $y_start + 2);
            $pdf->Cell(30, 6, 'Idioma', 1, 0, 'C', true);
            $pdf->Cell(60, 6, 'Certificación Internacional', 1, 0, 'C', true);
            $pdf->Cell(60, 6, 'Institución', 1, 0, 'C', true);
            $pdf->Cell(35, 6, 'Nivel', 1, 1, 'C', true);

            // Datos de los idiomas
            $pdf->SetFont('helvetica', '', 8);
            foreach ($idiomas as $fila) {
                $pdf->SetX(12);
                $pdf->Cell(30, 6, $fila[0], 1, 0, 'C'); // Idioma
                $pdf->Cell(60, 6, $fila[1], 1, 0, 'C'); // Certificación
                $pdf->Cell(60, 6, $fila[2], 1, 0, 'C'); // Institución
                $pdf->Cell(35, 6, $fila[3], 1, 1, 'C'); // Nivel
            }


            // Ajuste de posición
            $y = $y_start + $seccionAltura + 5;

            $y = $y_start + 7; // Espacio adicional después de la sección
            if ($y > 240) { // Si está muy cerca del final de la página
                $pdf->AddPage(); // Agregar nueva página
                $y = 20; // Resetear posición Y
            }

            // --- SECCIÓN 13: INFORMACIÓN MOVILIZACIÓN (Matrícula del Vehículo) ---
            $seccionAltura = 30;
            if ($y + $seccionAltura > 270) { // Si está cerca del final de la página
                $pdf->AddPage(); // Agregar nueva página
                $y = 20; // Resetear posición Y
            }

            // === SECCIÓN 9: INFORMACIÓN ADICIONAL ===
            $pdf->SetFont('helvetica', 'B', 10);
            $pdf->SetCellMargins(0, 0, 0);
            $pdf->SetXY(10, $y);
            $pdf->SetFillColor(255, 255, 255);
            $pdf->MultiCell(190, 7, '9. INFORMACIÓN ADICIONAL (Actual)', 0, 1, 'L', true);

            $y_start = $y + 7; // Inicia después del título

            foreach ($datos_conocimientos as $index => $item) {
                // Verificar si el espacio es suficiente en la página antes de agregar un nuevo bloque
                if ($y_start + 27 > 270) { // Si el siguiente bloque sobrepasa el límite de la hoja
                    $pdf->AddPage(); // Agregar nueva página
                    $y_start = 20; // Reiniciar la posición Y en la nueva página
                }

                crearCampo($pdf, 'Paquetes Utilitarios', 12, $y_start + 2, $item['paquetes_utilitarios']);
                crearCampo($pdf, 'Base de Datos', 105, $y_start + 2, $item['base_de_datos']);
                crearCampo($pdf, 'Herramientas Gráficas', 12, $y_start + 9, $item['herramientas_graficas']);
                crearCampo($pdf, 'Otros Conocimientos', 105, $y_start + 9, $item['otros_conocimientos']);
                crearCampo($pdf, 'Registro Profesional 1', 12, $y_start + 16, $item['registro_profesional_1']);
                crearCampo($pdf, 'Número o Código', 105, $y_start + 16, $item['numero_o_codigo_1']);
                crearCampo($pdf, 'Registro Profesional 2', 12, $y_start + 23, $item['registro_profesional_2']);
                crearCampo($pdf, 'Número o Código', 105, $y_start + 23, $item['numero_o_codigo_2']);

                // Ajuste de la posición para el siguiente bloque de datos
                $y_start += 32;
            }

            // Ajuste final de la posición
            $y = $y_start + 1;



            // --- SECCIÓN 10: EXPERIENCIA LABORAL ---
            $pdf->SetFont('helvetica', 'B', 10);
            $pdf->SetCellMargins(0, 0, 0);
            $pdf->SetXY(10, $y);
            $pdf->MultiCell(190, 7, '10. EXPERIENCIA LABORAL', 0, 1, 'L', true);

            $y_start = $y + 7; // Inicia después del título

            foreach ($datos_experiencia as $index => $experiencia) {
                // Verificar si el espacio es suficiente antes de agregar el siguiente bloque de experiencia
                if ($y_start + 32 > 270) { // Si el siguiente bloque sobrepasa el límite de la hoja
                    $pdf->AddPage(); // Agregar nueva página
                    $y_start = 20; // Reiniciar la posición Y en la nueva página
                }
                crearCampoAncho($pdf, 'Institución/Empresa', $y_start + 2, $experiencia['institucion_empresa']);
                crearCampoAncho($pdf, 'Cargo/Puesto', $y_start + 9, $experiencia['cargo_puesto']);
                crearCampoAncho($pdf, 'Motivo Salida', $y_start + 16, $experiencia['motivo_salida']);
                crearCampo($pdf, 'Fecha Ingreso', 12, $y_start + 23, $experiencia['fecha_ingreso']);
                crearCampo($pdf, 'Tiempo Laborado', 105, $y_start + 23, $experiencia['tiempo_laborado']);
                crearCampo($pdf, 'Sector Empresarial', 12, $y_start + 30, $experiencia['sector_empresarial']);
                crearCampo($pdf, 'Última Remuneración', 105, $y_start + 30, $experiencia['ultima_remuneracion']);
                crearCampo($pdf, 'Fecha Salida', 12, $y_start + 37, $experiencia['fecha_salida']);
                crearCampo($pdf, 'Figura Legal', 105, $y_start + 37, $experiencia['figura_legal']);
                $pdf->SetFont('helvetica', 'B', 8);
                $pdf->SetXY(12, $y_start + 44);
                $pdf->SetFillColor(173, 216, 230);
                $pdf->MultiCell(40, 5, 'Teléfono Empresa:', 0, 0, 'L', true);
                // División del valor en dos columnas para telefono_domicilio_2
                $telefono_domicilio_2_1 = substr($telefono_domicilio_2, 0, strlen($telefono_domicilio_2) / 2); // Primera mitad
                $telefono_domicilio_2_2 = substr($telefono_domicilio_2, strlen($telefono_domicilio_2) / 2); // Segunda mitad

                // Primera columna (primera mitad del teléfono domicilio 2) con 24 de ancho
                $pdf->SetFont('helvetica', '', 8);
                $pdf->SetFillColor(255, 255, 255); // Fondo blanco
                $pdf->SetXY(54, $y_start + 44); // Ajusta la posición según sea necesario
                $pdf->MultiCell(24, 5, $telefono_domicilio_2_1, 1, 0, 'L', false); // 24 para la primera columna

                // Segunda columna (segunda mitad del teléfono domicilio 2) con un pequeño espacio entre ellas
                $pdf->SetXY(80, $y_start + 44); // Ajusta la posición para la segunda columna (agregando un pequeño espacio)
                $pdf->MultiCell(24, 5, $telefono_domicilio_2_2, 1, 0, 'L', false); // 24 para la segunda columna

                crearCampo($pdf, 'Nombre Jefe Inmediato', 105, $y_start + 44, $experiencia['nombre_jefe_inmediato']);

                // Ajuste de la posición para el siguiente bloque de experiencia laboral
                $y_start += 51; // Incrementa la posición Y para el siguiente bloque
            }

            $y = $y_start + 5; // Espacio adicional después de la sección
            if ($y > 240) { // Si está muy cerca del final de la página
                $pdf->AddPage(); // Agregar nueva página
                $y = 20; // Resetear posición Y
            }

            // --- SECCIÓN 13: INFORMACIÓN MOVILIZACIÓN (Matrícula del Vehículo) ---
            $seccionAltura = 30;
            if ($y + $seccionAltura > 270) { // Si está cerca del final de la página
                $pdf->AddPage(); // Agregar nueva página
                $y = 20; // Resetear posición Y
            }
            // --- SECCIÓN 11: INFORMACIÓN EVENTOS DE CAPACITACIÓN ---
            $pdf->SetFont('helvetica', 'B', 10);
            $pdf->SetCellMargins(0, 0, 0);
            $pdf->SetXY(10, $y);
            $pdf->MultiCell(190, 7, '11. INFORMACIÓN EVENTOS DE CAPACITACIÓN', 0, 1, 'L', true);

            $y_start = $y + 7; // Inicia después del título

            foreach ($datos_eventos as $item) {
                // Verificar si el espacio es suficiente antes de agregar el siguiente bloque de eventos
                if ($y_start + 27 > 270) { // Si el siguiente bloque sobrepasa el límite de la hoja
                    $pdf->AddPage(); // Agregar nueva página
                    $y_start = 20; // Reiniciar la posición Y en la nueva página
                }

                crearCampoAncho($pdf, 'Nombre del Evento', $y_start + 2, $item['nombre_evento']);
                crearCampo($pdf, 'Tipo de Evento', 12, $y_start + 9, $item['tipo_evento']);
                crearCampo($pdf, 'Duración de Horas', 105, $y_start + 9, $item['duracion_horas']);
                crearCampoAncho($pdf, 'Institución Auspiciante', $y_start + 16, $item['institucion_auspiciante']);
                crearCampo($pdf, 'Tipo de Certificado', 12, $y_start + 23, $item['tipo_certificado']);
                crearCampo($pdf, 'Fecha Inicio', 105, $y_start + 23, $item['fecha_inicio']);
                crearCampo($pdf, 'País', 12, $y_start + 30, $item['pais']);
                crearCampo($pdf, 'Fecha Fin', 105, $y_start + 30, $item['fecha_fin']);

                // Ajuste de la posición para el siguiente bloque de eventos
                $y_start += 36; // Incrementa la posición Y para el siguiente bloque
            }

            $y = $y_start + 5; // Espacio adicional después de la sección

            // --- SECCIÓN 12: INFORMACIÓN BANCARIA ---
            $seccionAltura = 3;
            if ($y + $seccionAltura > 270) { // Verificar si se está cerca del final de la página
                $pdf->AddPage(); // Agregar nueva página
                $y = 20; // Resetear la posición Y
            }


            $pdf->SetFont('helvetica', 'B', 10);
            $pdf->SetCellMargins(0, 0, 0);
            $pdf->SetXY(10, $y);
            $pdf->MultiCell(190, 7, '12. INFORMACIÓN BANCARIA', 0, 1, 'L', true);
            $y_start = $y + 9;

            // Definimos el ancho de las columnas para que se acomoden
            $col_width = 30;  // Ancho de cada columna
            $row_height = 5;  // Altura de fila

            // Color azul para los títulos
            $pdf->SetFillColor(173, 216, 230);  // Azul cielo

            // Títulos de los campos en color azul
            $pdf->SetFont('helvetica', 'B', 8);

            // Columna 1: Institución Financiera
            $pdf->SetXY(12, $y_start);
            $pdf->MultiCell($col_width + 5, $row_height, 'Institución Financiera:', 0, 'L', true);

            // Columna 2: Institución Financiera (Descripción)
            $pdf->SetXY(49, $y_start);
            $pdf->MultiCell($col_width, $row_height, $institucion_financiera, 1, 'L', false);

            // Columna 3: Tipo Cuenta
            $pdf->SetXY(85, $y_start);
            $pdf->MultiCell($col_width - 9, $row_height, 'Tipo Cuenta:', 0, 'L', true);

            // Columna 4: Tipo Cuenta (Descripción)
            $pdf->SetXY(108, $y_start);
            $pdf->MultiCell($col_width - 2, $row_height, $tipo_cuenta, 1, 'L', false);

            // Columna 5: Número Cuenta
            $pdf->SetXY(141, $y_start);
            $pdf->MultiCell($col_width - 4, $row_height, 'Número Cuenta:', 0, 'L', true);

            // Columna 6: Número Cuenta (Descripción)
            $pdf->SetXY(169, $y_start);
            $pdf->MultiCell($col_width - 2, $row_height, $numero_cuenta, 1, 'L', false);

            // Ajuste de la posición Y para la siguiente sección
            $y_start += $row_height + 2;  // Aumentamos la altura para que haya espacio entre las filas

            $y = $y_start + 5; // Ajuste de altura para la siguiente sección

            if ($y > 240) { // Si está muy cerca del final de la página
                $pdf->AddPage(); // Agregar nueva página
                $y = 20; // Resetear posición Y
            }

            // --- SECCIÓN 13: INFORMACIÓN MOVILIZACIÓN (Matrícula del Vehículo) ---
            $seccionAltura = 30;
            if ($y + $seccionAltura > 270) { // Si está cerca del final de la página
                $pdf->AddPage(); // Agregar nueva página
                $y = 20; // Resetear posición Y
            }
            $pdf->SetFont('helvetica', 'B', 10);
            $pdf->SetCellMargins(0, 0, 0);
            $pdf->SetXY(10, $y);
            $pdf->SetFillColor(255, 255, 255);
            $pdf->MultiCell(190, 7, '13. INFORMACIÓN MOVILIZACIÓN (Matrícula del Vehículo)', 0, 1, 'L', true);
            $y_start = $y + 11;

            // Definir coordenadas y ancho de cada columna (reducidos para mayor compactación)

            $col1_x = 12;  // Primera columna
            $col2_x = 68;  // Segunda columna (antes era 75)
            $col3_x = 148; // Tercera columna (antes era 140)
            $row_height = 5; // Altura de fila más compacta
            // Primera fila de información
            crearCampoMovilizacion($pdf, 'Vehículo', $col1_x, $y_start, $vehiculo, 20, $row_height);
            crearCampoMovilizacion($pdf, 'Propietario', $col2_x, $y_start, $propietario, 50, $row_height);
            crearCampoMovilizacion($pdf, 'Teléfono', $col3_x, $y_start, $telefono, 30, $row_height);

            // Segunda fila de información
            crearCampoMovilizacion($pdf, 'Clase', $col1_x, $y_start + $row_height + 2, $clase, 20, $row_height);
            crearCampoMovilizacion($pdf, 'Tipo', $col2_x, $y_start + $row_height  + 2, $tipo, 50, $row_height);
            crearCampoMovilizacion($pdf, 'Placa', $col3_x, $y_start + $row_height + 2, $placa, 30, $row_height);

            // Tercera fila de información
            crearCampoMovilizacion($pdf, 'Marca', $col1_x, $y_start + 2 * $row_height + 4, $marca, 20, $row_height);
            crearCampoMovilizacion($pdf, 'Modelo', $col2_x, $y_start + 2 * $row_height +  4, $modelo, 50, $row_height);
            crearCampoMovilizacion($pdf, 'Año', $col3_x, $y_start + 2 * $row_height +  4, $ano, 30, $row_height);

            // Cuarta fila de información
            crearCampoMovilizacion($pdf, 'Color 1', $col1_x, $y_start + 3 * $row_height + 6, $color1, 20, $row_height);
            crearCampoMovilizacion($pdf, 'Color 2', $col2_x, $y_start + 3 * $row_height + 6, $color2, 50, $row_height);
            crearCampoMovilizacion($pdf, 'Licencia', $col3_x, $y_start + 3 * $row_height + 6, $licencia, 30, $row_height);

            $y = $y_start + $seccionAltura + 5; // Ajuste de altura para la siguiente sección

            if ($y > 240) { // Si está muy cerca del final de la página
                $pdf->AddPage();
                $y = 20; // Resetear posición Y
            }

            // === SECCIÓN 14: CROQUIS DOMICILIARIO (Foto Google Maps) ===
            $seccionAltura = 30;
            if ($y + $seccionAltura > 270) { // Verificar si está cerca del final de la página
                $pdf->AddPage(); // Agregar nueva página
                $y = 20; // Resetear la posición Y
            }
            $pdf->SetFont('helvetica', 'B', 10);
            $pdf->SetCellMargins(0, 0, 0);
            $pdf->SetXY(10, $y);
            $pdf->MultiCell(190, 7, '14. CROQUIS DOMICILIARIO (Foto Google Maps)', 0, 1, 'L', true);
            $y_start = $y + 7; // Inicia después del título

            // URL de la imagen del mapa (reemplaza "YOUR_API_KEY" con tu clave de API)
            $mapUrl = "https://maps.googleapis.com/maps/api/staticmap?center=-0.2802442,-78.4629427&zoom=17&size=600x400&markers=color:red|label:S|-0.2802442,-78.4629427&key=YOUR_API_KEY";

            // Insertar la imagen en el PDF (ajustar las coordenadas y el tamaño de la imagen)
            $pdf->Image($mapUrl, 12, $y_start + 5, 180, 120);

            // Actualizar la posición Y después de la imagen
            $y = $y_start + 40; // posición de inicio + offset (5) + altura de imagen (120) + margen (5)

            if ($y > 240) {
                $pdf->AddPage();
                $y = 20;
            }


            // === SECCIÓN 15: HÁBITOS PERSONALES ===
            $seccionAltura = 30;
            if ($y + $seccionAltura > 270) { // Verificar si está cerca del final de la página
                $pdf->AddPage(); // Agregar nueva página
                $y = 20; // Resetear la posición Y
            }
            $pdf->SetFont('helvetica', 'B', 10);
            $pdf->SetCellMargins(0, 0, 0);
            $pdf->SetXY(10, $y);
            $pdf->MultiCell(190, 7, '15. HÁBITOS PERSONALES', 0, 1, 'L', true);
            $y_start = $y + 7; // Inicia después del título

            // Columna 1: Datos

            $pdf->SetFont('helvetica', 'B', 8);
            $pdf->SetXY(12, $y_start + 2);
            $pdf->SetFillColor(173, 216, 230);
            $pdf->MultiCell(40, 5, 'Deportes que Práctica:', 0, 0, 'L', true);

            // Dividir el valor en dos partes para deportes_que_practica
            $deportes_que_practica_1 = substr($deportes_que_practica, 0, strlen($deportes_que_practica) / 2); // Primera mitad
            $deportes_que_practica_2 = substr($deportes_que_practica, strlen($deportes_que_practica) / 2); // Segunda mitad

            // Primera columna (primera mitad del deporte que practica)
            $pdf->SetFont('helvetica', '', 8);
            $pdf->SetFillColor(255, 255, 255); // Fondo blanco
            $pdf->SetXY(54, $y_start + 2);
            $pdf->MultiCell(24, 5, $deportes_que_practica_1, 1, 0, 'L', false); // 24 para la primera columna

            // Segunda columna (segunda mitad del deporte que practica) con un pequeño espacio
            $pdf->SetXY(80, $y_start + 2); // Ajusta la posición para la segunda columna
            $pdf->MultiCell(24, 5, $deportes_que_practica_2, 1, 0, 'L', false); // 24 para la segunda columna

            // Pasatiempos Favoritos
            $pdf->SetFont('helvetica', 'B', 8);
            $pdf->SetXY(12, $y_start + 9);
            $pdf->SetFillColor(173, 216, 230); // Fondo azul para el título
            $pdf->MultiCell(40, 5, 'Pasatiempos Favoritos:', 0, 0, 'L', true);

            // Dividir el valor en dos partes para pasatiempos_favoritos
            $pasatiempos_favoritos_1 = substr($pasatiempos_favoritos, 0, strlen($pasatiempos_favoritos) / 2); // Primera mitad
            $pasatiempos_favoritos_2 = substr($pasatiempos_favoritos, strlen($pasatiempos_favoritos) / 2); // Segunda mitad

            // Primera columna (primera mitad de los pasatiempos favoritos)
            $pdf->SetFont('helvetica', '', 8);
            $pdf->SetFillColor(255, 255, 255); // Fondo blanco
            $pdf->SetXY(54, $y_start + 9);
            $pdf->MultiCell(24, 5, $pasatiempos_favoritos_1, 1, 0, 'L', false); // 24 para la primera columna

            // Segunda columna (segunda mitad de los pasatiempos favoritos)
            $pdf->SetXY(80, $y_start + 9); // Ajusta la posición para la segunda columna
            $pdf->MultiCell(24, 5, $pasatiempos_favoritos_2, 1, 0, 'L', false); // 24 para la segunda columna

            // Consumos Nocivos
            $pdf->SetFont('helvetica', 'B', 8);
            $pdf->SetXY(12, $y_start + 16);
            $pdf->SetFillColor(173, 216, 230); // Fondo azul para el título
            $pdf->MultiCell(40, 5, 'Consumos Nocivos:', 0, 0, 'L', true);

            // Dividir el valor en dos partes para consumos_nocivos
            $consumos_nocivos_1 = substr($consumos_nocivos, 0, strlen($consumos_nocivos) / 2); // Primera mitad
            $consumos_nocivos_2 = substr($consumos_nocivos, strlen($consumos_nocivos) / 2); // Segunda mitad

            // Primera columna (primera mitad de los consumos nocivos)
            $pdf->SetFont('helvetica', '', 8);
            $pdf->SetFillColor(255, 255, 255); // Fondo blanco
            $pdf->SetXY(54, $y_start + 16);
            $pdf->MultiCell(24, 5, $consumos_nocivos_1, 1, 0, 'L', false); // 24 para la primera columna

            // Segunda columna (segunda mitad de los consumos nocivos)
            $pdf->SetXY(80, $y_start + 16); // Ajusta la posición para la segunda columna
            $pdf->MultiCell(24, 5, $consumos_nocivos_2, 1, 0, 'L', false); // 24 para la segunda columna

            crearCampo($pdf, 'Seguro de Vida Privado', 12, $y_start + 24, $seguro_vida_privado);

            // Columna 2: Datos
            crearCampo($pdf, 'Asistencia Psicológica', 105, $y_start + 2, $asistencia_psicologica);
            crearCampo($pdf, 'Grupo Sanguíneo', 105, $y_start + 9, $grupo_sanguineo);
            // Enfermedades
            $pdf->SetFont('helvetica', 'B', 8);
            $pdf->SetXY(105, $y_start + 16);
            $pdf->SetFillColor(173, 216, 230); // Fondo azul para el título
            $pdf->MultiCell(40, 5, 'Enfermedades:', 0, 0, 'L', true);

            // Dividir el valor en dos partes para enfermedades
            $enfermedades_1 = substr($enfermedades, 0, strlen($enfermedades) / 2); // Primera mitad
            $enfermedades_2 = substr($enfermedades, strlen($enfermedades) / 2); // Segunda mitad

            // Primera columna (primera mitad de las enfermedades)
            $pdf->SetFont('helvetica', '', 8);
            $pdf->SetFillColor(255, 255, 255); // Fondo blanco
            $pdf->SetXY(147, $y_start + 16);
            $pdf->MultiCell(24, 5, $enfermedades_1, 1, 0, 'L', false); // 24 para la primera columna

            // Segunda columna (segunda mitad de las enfermedades)
            $pdf->SetXY(173, $y_start + 16); // Ajusta la posición para la segunda columna
            $pdf->MultiCell(24, 5, $enfermedades_2, 1, 0, 'L', false); // 24 para la segunda columna

            crearCampo($pdf, 'Religión', 105, $y_start + 24, $religion);

            // Actualizar la posición para la siguiente sección
            $y = $y_start + 30 + 5;

            if ($y > 240) {
                $pdf->AddPage();
                $y = 20;
            }


            // === SECCIÓN 16: INFORMACIÓN COMPLEMENTARIA ===
            $seccionAltura = 35;
            if ($y + $seccionAltura > 270) { // Verificar si está cerca del final de la página
                $pdf->AddPage(); // Agregar nueva página
                $y = 20; // Resetear la posición Y
            }
            $pdf->SetFont('helvetica', 'B', 10);
            $pdf->SetCellMargins(0, 0, 0);
            $pdf->SetXY(10, $y);
            $pdf->MultiCell(190, 7, '16. INFORMACIÓN COMPLEMENTARIA', 0, 1, 'L', true);
            $y_start = $y + 7; // Inicia después del título

            // Columna 1: Datos
            crearCampo($pdf, 'Conocimiento de Oferta', 12, $y_start + 2, $conocimiento_oferta);
            crearCampo($pdf, 'Integra Agrupaciones', 12, $y_start + 9, $integra_agrupaciones);
            crearCampo($pdf, 'Trabajo Cónyugue/Pareja', 12, $y_start + 16, $trabajo_conyugue);
            crearCampo($pdf, 'Detalle Agrupación', 12, $y_start + 23, $detalle_agrupacion);

            // Columna 2: Datos
            crearCampo($pdf, 'Valor Ingresos Mensuales', 105, $y_start + 2, $valor_ingresos_mensuales);
            crearCampo($pdf, 'Cargo C./P.', 105, $y_start + 9, $cargo_cp);
            crearCampo($pdf, 'Integro Grupos Laborales', 105, $y_start + 16, $integro_grupos_laborales);
            crearCampo($pdf, 'Remuneración C./P.', 105, $y_start + 23, $remuneracion_cp);

            // Columna 1 (continuación)
            crearCampo($pdf, 'Parientes en Institución', 12, $y_start + 30, $parientes_en_institucion);
            // Columna 2 (continuación)
            crearCampo($pdf, 'Total Ingresos', 105, $y_start + 30, $total_ingresos);

            // Actualizar la posición para la siguiente sección
            $y = $y_start + $seccionAltura + 5;

            if ($y > 240) {
                $pdf->AddPage();
                $y = 20;
            }

            // === SECCIÓN 17: DECLARATORIA DE RESPONSABILIDAD ===
            $seccionAltura = 90;
            if ($y + $seccionAltura > 270) { // Verificar si está cerca del final de la página
                $pdf->AddPage(); // Agregar nueva página
                $y = 20; // Resetear la posición Y
            }
            $pdf->SetFont('helvetica', 'B', 10);
            $pdf->SetCellMargins(0, 0, 0);
            $pdf->SetXY(10, $y);
            $pdf->MultiCell(190, 7, '17. DECLARATORIA DE RESPONSABILIDAD', 0, 1, 'L', true);
            $y_start = $y + 7; // Inicia después del título

            // Texto de la declaratoria dentro de la sección
            $pdf->SetFont('Helvetica', '', 10);
            $pdf->SetXY(12, $y_start + 3);
            $pdf->MultiCell(0, 10, 'Declaro que la información proporcionada en el presente formulario es veraz y autorizo a la Institución que realice las verificaciones pertinentes que requiera', 0, 'L');


            $pdf->SetXY(12, $y_start + 40);
            $pdf->Line(12, $y_start + 38, 100, $y_start + 38); // Línea para firma (arriba del texto)
            $pdf->SetFont('Helvetica', 'B', 10);
            $pdf->Cell(95, 10, 'ALEJANDRA VALERIA SANTILLAN BERMEO', 0, 0, 'L'); // Nombre

            $pdf->SetXY(120, $y_start + 40);
            $pdf->Line(120, $y_start + 38, 200, $y_start + 38); // Línea para firma de coordinación (arriba del texto)
            $pdf->SetFont('Helvetica', 'B', 10);
            $pdf->Cell(95, 10, 'COORDINACIÓN DE TALENTO HUMANO', 0, 1, 'L'); // Coordinación


            // Obtener la fecha en inglés
            $fecha = date('l, d \d\e F \d\e Y');

            // Traducir los nombres de los días y meses
            $buscar = [
                'Monday',
                'Tuesday',
                'Wednesday',
                'Thursday',
                'Friday',
                'Saturday',
                'Sunday',
                'January',
                'February',
                'March',
                'April',
                'May',
                'June',
                'July',
                'August',
                'September',
                'October',
                'November',
                'December'
            ];

            $reemplazar = [
                'Lunes',
                'Martes',
                'Miércoles',
                'Jueves',
                'Viernes',
                'Sábado',
                'Domingo',
                'Enero',
                'Febrero',
                'Marzo',
                'Abril',
                'Mayo',
                'Junio',
                'Julio',
                'Agosto',
                'Septiembre',
                'Octubre',
                'Noviembre',
                'Diciembre'
            ];

            $fecha = str_replace($buscar, $reemplazar, $fecha);

            $pdf->SetXY(12, $y_start + 60);
            $pdf->SetFont('Helvetica', '', 10);
            $pdf->MultiCell(0, 10, 'Fecha última actualización: ' . $fecha, 0, 1, 'L');


            // Actualizar la posición para la siguiente sección (si existiese)
            $y = $y_start + $seccionAltura + 5;
            $tempDir = dirname(__DIR__, 2) . '/temp/';
            if (!file_exists($tempDir)) {
                mkdir($tempDir, 0777, true);
            }
            $fileName = 'formulario_' . time() . '.pdf';
            $filePath = $tempDir . $fileName;

            // Guardar el PDF en el servidor
            $pdf->Output($filePath, 'F');
            $relativePath = str_replace($_SERVER['DOCUMENT_ROOT'], '', $filePath);

            return [
                'success' => true,
                'ruta' => $relativePath,
                'message' => 'PDF generado correctamente'
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Error al generar el PDF: ' . $e->getMessage()
            ];
        }
    }
}
