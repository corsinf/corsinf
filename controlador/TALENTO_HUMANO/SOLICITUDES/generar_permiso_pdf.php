<?php
 require_once(dirname(__DIR__, 3) . '/lib/TCPDF/tcpdf.php');

function generar_permiso_pdf() {

    // ==========================================
    // INFORMACIÓN DEL DOCUMENTO
    // ==========================================
    $codigo  = 'GTH-PTH-SPJ-02';
    $version = '1.0';
    $pagina  = '1 de 1';

    // ==========================================
    // DATOS PERSONALES
    // ==========================================
    $nombres       = 'María Elena García Rodríguez';
    $genero        = 'Femenino';
    $estado_civil  = 'Casado/a';
    $cedula        = '1715842963';
    $cargo         = 'Gestión de Recursos Humanos';
    $asunto        = 'Solicitud';

    // ==========================================
    // MOTIVOS
    // ==========================================
    $motivo_personal          = false;
    $motivo_calamidad         = false;
    $motivo_fallecimiento     = false;
    $motivo_familiar_hijos    = true;
    $motivo_familiar_adultos  = false;
    $motivo_maternidad        = false;
    $motivo_paternidad        = false;
    $motivo_enfermedad        = false;
    $motivo_cita_medica       = false;
    $motivo_atencion_medica   = false;

    // ==========================================
    // DATOS ADICIONALES DE MOTIVOS
    // ==========================================
    $rango_edad_hijos  = '6-11';
    $discapacidad = false;
    $adulto_mayor = false;
    $enfermedad_catastrofica = false;
    $atencion_privada = true;
    $atencion_publica = false;
    $lugar            = 'Clínica Metropolitana';
    $especialidad     = 'Pediatría General';
    $nombre_medico    = 'Dr. Carlos Mendoza López';
    $fecha_cita_dia   = '15';
    $fecha_cita_mes   = '01';
    $fecha_cita_anio  = '2026';
    $hora_desde_cita  = '10';
    $hora_hasta_cita  = '11';
    $detalle_motivo   = 'Atención médica preventiva para hijo en edad escolar';

    // ==========================================
    // ESPACIO DEPARTAMENTO MÉDICO
    // ==========================================
    $certificado_trabajador = true;
    $reposo = false;
    $permiso_medico = true;
    $enfermedad_general = false;
    $asistencia_consulta = true;
    $idc = false;

    $observacion_requiere_certificado_medico = 'SI';
    $observacion_requiere_certificado_asistencia = 'SI';
    $motivo_observacion = 'Atención ambulatoria - Consulta pediátrica';
    $fecha_obs_dia = '15';
    $fecha_obs_mes = '01';
    $fecha_obs_anio ='2026';
    $desde_obs = '10:00';
    $hasta_obs = '12:00';

    // ==========================================
    // FECHA Y HORA DEL PERMISO
    // ==========================================
    $desde_dia = '15';
    $desde_mes = '01';
    $desde_anio ='26';
    $desde_hora =  '10';
    $desde_min = '00';
    $hasta_dia = '15';
    $hasta_mes = '01';
    $hasta_anio ='26';
    $hasta_hora = '12';
    $hasta_min = '00';
    $total_dias = '0';
    $total_horas = '2';

    // ==========================================
    // ESPACIO DE RESPONSABILIDAD
    // ==========================================
    $responsable_identificacion = true;
    $aviso_inmediato = false;
    $informacion_responsable = 'Personal notificado sobre actividades a ejecutar durante ausencia';

    // ==========================================
    // CONFIGURACIÓN DEL PDF
    // ==========================================
    $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

    // Información del documento
    $pdf->setCreator(PDF_CREATOR);
    $pdf->setAuthor('Sistema de Talento Humano');
    $pdf->setTitle('Solicitud de Permiso o Justificación');
    $pdf->setSubject('Permiso');

    // Quitar header y footer por defecto
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);

    // Configurar márgenes
    $pdf->setMargins(10, 10, 10);
    $pdf->setAutoPageBreak(TRUE, 10);

    // Agregar página
    $pdf->AddPage();

    // ==========================================
    // FUNCIÓN PARA DIBUJAR CHECKBOXES
    // ==========================================
    function dibujar_checkbox($pdf, $x, $y, $marcado = false, $size = 3) {
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->SetLineWidth(0.3);
        $pdf->Rect($x, $y, $size, $size);

        if ($marcado) {
            $pdf->SetDrawColor(0, 0, 0);
            $pdf->SetLineWidth(0.5);
            $pdf->Line($x + 0.5, $y + 0.5, $x + $size - 0.5, $y + $size - 0.5);
            $pdf->Line($x + 0.5, $y + $size - 0.5, $x + $size - 0.5, $y + 0.5);
        }
    }

    // ==========================================
    // ENCABEZADO DEL FORMULARIO
    // ==========================================

    // Rectángulo para Logo
    $pdf->Rect(10, 10, 20, 18);
    if (file_exists('img/logo.png')) {
        $pdf->Image('img/logo.png', 11, 11, 18, 16);
    }

    // Título del formulario
    $pdf->SetFont('helvetica', 'B', 11);
    $pdf->SetXY(30, 10);
    $pdf->Cell(117, 18, 'SOLICITUD DE PERMISO O JUSTIFICACIÓN', 1, 0, 'C');

    // Tabla de información del documento
    $pdf->SetXY(147, 10);
    $pdf->SetFont('helvetica', 'B', 8);
    $pdf->Cell(20, 6, 'Versión:', 1, 0, 'L');
    $pdf->SetFont('helvetica', '', 8);
    $pdf->Cell(33, 6, $version, 1, 1, 'C');

    $pdf->SetXY(147, 16);
    $pdf->SetFont('helvetica', 'B', 8);
    $pdf->Cell(20, 6, 'Página:', 1, 0, 'L');
    $pdf->SetFont('helvetica', '', 8);
    $pdf->Cell(33, 6, $pagina, 1, 1, 'C');

    $pdf->SetXY(147, 22);
    $pdf->SetFont('helvetica', 'B', 8);
    $pdf->Cell(20, 6, 'Código:', 1, 0, 'L');
    $pdf->SetFont('helvetica', '', 7);
    $pdf->Cell(33, 6, $codigo, 1, 1, 'C');

    // Fecha y lugar
    $pdf->SetXY(10, 32);
    $pdf->SetFont('helvetica', '', 9);
    $pdf->Cell(0, 5, 'Quito, 15 de Enero 2026', 0, 1, 'R');

    // ==========================================
    // DATOS PERSONALES
    // ==========================================
    $pdf->Ln(2);

    // NOMBRES
    $y = $pdf->GetY();
    $pdf->SetFont('helvetica', 'B', 9);
    $pdf->SetXY(10, $y);
    $pdf->Cell(18, 5, 'NOMBRES:', 0, 0);
    $pdf->SetFont('helvetica', '', 9);
    $pdf->Cell(52, 5, $nombres, 'B', 0);

    // GÉNERO
    $pdf->SetXY(85, $y);
    $pdf->SetFont('helvetica', 'B', 9);
    $pdf->Cell(16, 5, 'GÉNERO:', 0, 0);
    $pdf->SetFont('helvetica', '', 8);
    $pdf->Cell(18, 5, 'Masculino', 0, 0);
    dibujar_checkbox($pdf, 120, $y + 1, $genero == 'Masculino', 3);

    // ESTADO CIVIL
    $pdf->SetXY(130, $y);
    $pdf->SetFont('helvetica', 'B', 9);
    $pdf->Cell(20, 5, 'ESTADO', 0, 0);
    $pdf->SetFont('helvetica', '', 8);
    $pdf->Cell(15, 5, 'Soltero/a', 0, 0);
    dibujar_checkbox($pdf, 167, $y + 1, $estado_civil == 'Soltero/a', 3);
    $pdf->Ln(5);

    // CÉDULA
    $y = $pdf->GetY();
    $pdf->SetXY(10, $y);
    $pdf->SetFont('helvetica', 'B', 9);
    $pdf->Cell(18, 5, 'CÉDULA:', 0, 0);
    $pdf->SetFont('helvetica', '', 9);
    $pdf->Cell(52, 5, $cedula, 'B', 0);

    // Femenino
    $pdf->SetXY(101, $y);
    $pdf->SetFont('helvetica', '', 8);
    $pdf->Cell(18, 5, 'Femenino', 0, 0);
    dibujar_checkbox($pdf, 120, $y + 1, $genero == 'Femenino', 3);

    // CIVIL - Casado/a
    $pdf->SetXY(130, $y);
    $pdf->SetFont('helvetica', 'B', 9);
    $pdf->Cell(20, 5, 'CIVIL:', 0, 0);
    $pdf->SetFont('helvetica', '', 8);
    $pdf->Cell(15, 5, 'Casado/a', 0, 0);
    dibujar_checkbox($pdf, 167, $y + 1, $estado_civil == 'Casado/a', 3);
    $pdf->Ln(5);

    // CARGO
    $y = $pdf->GetY();
    $pdf->SetXY(10, $y);
    $pdf->SetFont('helvetica', 'B', 9);
    $pdf->Cell(28, 5, 'CARGO (contrato):', 0, 0);
    $pdf->SetFont('helvetica', '', 9);
    $pdf->Cell(42, 5, $cargo, 'B', 0);

    // Prefiero no decirlo
    $pdf->SetXY(85, $y);
    $pdf->SetFont('helvetica', '', 8);
    $pdf->Cell(34, 5, 'Prefiero no decirlo', 0, 0);
    dibujar_checkbox($pdf, 120, $y + 1, $genero == 'Prefiero no decirlo', 3);

    // Unión H.
    $pdf->SetXY(150, $y);
    $pdf->SetFont('helvetica', '', 8);
    $pdf->Cell(15, 5, 'Unión H.', 0, 0);
    dibujar_checkbox($pdf, 167, $y + 1, $estado_civil == 'Unión H.', 3);
    $pdf->Ln(5);

    // ASUNTO
    $y = $pdf->GetY();
    $pdf->SetXY(85, $y);
    $pdf->SetFont('helvetica', 'B', 9);
    $pdf->Cell(16, 5, 'ASUNTO:', 0, 0);
    $pdf->SetFont('helvetica', '', 8);
    $pdf->Cell(18, 5, 'Solicitud', 0, 0);
    dibujar_checkbox($pdf, 120, $y + 1, $asunto == 'Solicitud', 3);

    // Divorciado/a
    $pdf->SetXY(150, $y);
    $pdf->SetFont('helvetica', '', 8);
    $pdf->Cell(15, 5, 'Divorciado/a', 0, 0);
    dibujar_checkbox($pdf, 167, $y + 1, $estado_civil == 'Divorciado/a', 3);
    $pdf->Ln(5);

    // Justificación
    $y = $pdf->GetY();
    $pdf->SetXY(101, $y);
    $pdf->SetFont('helvetica', '', 8);
    $pdf->Cell(18, 5, 'Justificación', 0, 0);
    dibujar_checkbox($pdf, 120, $y + 1, $asunto == 'Justificación', 3);

    // Viudo/a
    $pdf->SetXY(150, $y);
    $pdf->SetFont('helvetica', '', 8);
    $pdf->Cell(15, 5, 'Viudo/a', 0, 0);
    dibujar_checkbox($pdf, 167, $y + 1, $estado_civil == 'Viudo/a', 3);

    // ==========================================
    // SECCIÓN DE MOTIVO
    // ==========================================
    $pdf->Ln(4);

    // Fondo gris para la sección MOTIVO
    $y = $pdf->GetY();
    $pdf->SetFillColor(220, 220, 220);
    $pdf->Rect(10, $y, 190, 5, 'F');

    $pdf->SetXY(10, $y);
    $pdf->SetFont('helvetica', 'B', 9);
    $pdf->Cell(30, 5, 'MOTIVO:', 0, 1);

    // * Personal | Maternidad / Paternidad
    $y = $pdf->GetY();
    dibujar_checkbox($pdf, 10, $y + 1, $motivo_personal, 3.5);
    $pdf->SetXY(14, $y);
    $pdf->SetFont('helvetica', '', 8);
    $pdf->Cell(3, 5, '*', 0, 0);
    $pdf->Cell(20, 5, 'Personal', 0, 0);

    $pdf->SetXY(105, $y);
    dibujar_checkbox($pdf, 105, $y + 1, $motivo_maternidad, 3.5);
    $pdf->SetXY(110, $y);
    $pdf->Cell(55, 5, 'Maternidad / Paternidad', 0, 1);

    // Calamidad Doméstica | (Adjuntar certificado Nacido Vivo)
    $y = $pdf->GetY();
    dibujar_checkbox($pdf, 10, $y + 1, $motivo_calamidad, 3.5);
    $pdf->SetXY(14, $y);
    $pdf->Cell(70, 5, 'Calamidad Doméstica', 0, 0);

    $pdf->SetXY(110, $y);
    $pdf->SetFont('helvetica', '', 7);
    $pdf->Cell(60, 5, '(Adjuntar certificado Nacido Vivo)', 0, 1);

    // (Siniestros Catastróficos)
    $y = $pdf->GetY();
    $pdf->SetXY(14, $y);
    $pdf->SetFont('helvetica', '', 7);
    $pdf->Cell(70, 4, '(Siniestros Catastróficos)', 0, 1);

    // Fallecimiento (familiares) | Enfermedad
    $y = $pdf->GetY();
    dibujar_checkbox($pdf, 10, $y + 1, $motivo_fallecimiento, 3.5);
    $pdf->SetXY(14, $y);
    $pdf->SetFont('helvetica', '', 8);
    $pdf->Cell(50, 5, 'Fallecimiento (familiares)', 0, 0);

    $pdf->SetXY(105, $y);
    dibujar_checkbox($pdf, 105, $y + 1, $motivo_enfermedad, 3.5);
    $pdf->SetXY(110, $y);
    $pdf->Cell(50, 5, 'Enfermedad', 0, 1);

    // (Adjuntar acta de defunción) | (Adjuntar certificado)
    $y = $pdf->GetY();
    $pdf->SetXY(14, $y);
    $pdf->SetFont('helvetica', '', 7);
    $pdf->Cell(50, 4, '(Adjuntar acta de defunción)', 0, 0);

    $pdf->SetXY(110, $y);
    $pdf->Cell(50, 4, '(Adjuntar certificado)', 0, 1);

    // Familiar - hijos | Cita Médica
    $y = $pdf->GetY();
    dibujar_checkbox($pdf, 10, $y + 1, $motivo_familiar_hijos, 3.5);
    $pdf->SetXY(14, $y);
    $pdf->SetFont('helvetica', '', 8);
    $pdf->Cell(30, 5, 'Familiar - hijos', 0, 0);

    $pdf->SetXY(105, $y);
    dibujar_checkbox($pdf, 105, $y + 1, $motivo_cita_medica, 3.5);
    $pdf->SetXY(110, $y);
    $pdf->Cell(50, 5, 'Cita Médica', 0, 1);

    // (Rango de Edad) | (Adjuntar certificado)
    $y = $pdf->GetY();
    $pdf->SetXY(14, $y);
    $pdf->SetFont('helvetica', '', 7);
    $pdf->Cell(30, 4, '(Rango de Edad)', 0, 0);

    $pdf->SetXY(110, $y);
    $pdf->Cell(50, 4, '(Adjuntar certificado)', 0, 1);

    // 0 - 5 años | Detalle de Atención Médica
    $y = $pdf->GetY();
    dibujar_checkbox($pdf, 10, $y + 1, $rango_edad_hijos == '0-5', 3.5);
    $pdf->SetXY(14, $y);
    $pdf->SetFont('helvetica', '', 8);
    $pdf->Cell(20, 5, '0 - 5 años', 0, 0);

    $pdf->SetXY(105, $y);
    dibujar_checkbox($pdf, 105, $y + 1, $motivo_atencion_medica, 3.5);
    $pdf->SetXY(110, $y);
    $pdf->Cell(55, 5, 'Detalle de Atención Médica', 0, 1);

    // 6 - 11 años | (Enfermedad o Cita Médica)
    $y = $pdf->GetY();
    dibujar_checkbox($pdf, 10, $y + 1, $rango_edad_hijos == '6-11', 3.5);
    $pdf->SetXY(14, $y);
    $pdf->Cell(25, 5, '6 - 11 años', 0, 0);

    $pdf->SetXY(110, $y);
    $pdf->SetFont('helvetica', '', 7);
    $pdf->Cell(55, 5, '(Enfermedad o Cita Médica)', 0, 0);

    $pdf->SetXY(175, $y);
    $pdf->SetFont('helvetica', '', 8);
    $pdf->Cell(3, 5, '*', 0, 0);
    $pdf->Cell(13, 5, 'Privada', 0, 0);
    dibujar_checkbox($pdf, 194, $y + 1, $atencion_privada, 3.5);
    $pdf->Ln(5);

    // 12 - 17 años | Lugar:
    $y = $pdf->GetY();
    dibujar_checkbox($pdf, 10, $y + 1, $rango_edad_hijos == '12-17', 3.5);
    $pdf->SetXY(14, $y);
    $pdf->Cell(25, 5, '12 - 17 años', 0, 0);

    $pdf->SetXY(110, $y);
    $pdf->SetFont('helvetica', '', 8);
    $pdf->Cell(10, 5, 'Lugar:', 0, 0);
    $pdf->Cell(55, 5, $lugar, 'B', 0);

    $pdf->SetXY(175, $y);
    $pdf->Cell(3, 5, '**', 0, 0);
    $pdf->Cell(13, 5, 'Pública', 0, 0);
    dibujar_checkbox($pdf, 194, $y + 1, $atencion_publica, 3.5);
    $pdf->Ln(5);

    // Familiar - adultos | Especialidad:
    $y = $pdf->GetY();
    dibujar_checkbox($pdf, 10, $y + 1, $motivo_familiar_adultos, 3.5);
    $pdf->SetXY(14, $y);
    $pdf->Cell(35, 5, 'Familiar - adultos', 0, 0);

    $pdf->SetXY(110, $y);
    $pdf->Cell(20, 5, 'Especialidad:', 0, 0);
    $pdf->Cell(70, 5, $especialidad, 'B', 1);

    // (Cuidado Familiar) | Nombre del Médico:
    $y = $pdf->GetY();
    $pdf->SetXY(14, $y);
    $pdf->SetFont('helvetica', '', 7);
    $pdf->Cell(35, 4, '(Cuidado Familiar)', 0, 0);

    $pdf->SetXY(110, $y);
    $pdf->SetFont('helvetica', '', 8);
    $pdf->Cell(32, 4, 'Nombre del Médico:', 0, 0);
    $pdf->Cell(58, 4, $nombre_medico, 'B', 1);

    // Discapacidad | Fecha y Hora
    $y = $pdf->GetY();
    dibujar_checkbox($pdf, 10, $y + 1, $discapacidad, 3.5);
    $pdf->SetXY(14, $y);
    $pdf->SetFont('helvetica', '', 8);
    $pdf->Cell(25, 5, 'Discapacidad', 0, 0);

    $pdf->SetXY(110, $y);
    $pdf->Cell(12, 5, 'Fecha:', 0, 0);
    $pdf->Cell(7, 5, $fecha_cita_dia, 'B', 0);
    $pdf->Cell(2, 5, '/', 0, 0);
    $pdf->Cell(7, 5, $fecha_cita_mes, 'B', 0);
    $pdf->Cell(2, 5, '/', 0, 0);
    $pdf->Cell(7, 5, $fecha_cita_anio, 'B', 0);

    $pdf->Cell(15, 5, 'Hora: Desde', 0, 0);
    $pdf->Cell(8, 5, $hora_desde_cita, 'B', 0);
    $pdf->Cell(3, 5, 'h', 0, 0);
    $pdf->Cell(10, 5, 'Hasta', 0, 0);
    $pdf->Cell(8, 5, $hora_hasta_cita, 'B', 0);
    $pdf->Cell(3, 5, 'h', 0, 1);

    // Adulto Mayor
    $y = $pdf->GetY();
    dibujar_checkbox($pdf, 10, $y + 1, $adulto_mayor, 3.5);
    $pdf->SetXY(14, $y);
    $pdf->Cell(25, 5, 'Adulto Mayor', 0, 1);

    // Enfermedad Catastrófica
    $y = $pdf->GetY();
    dibujar_checkbox($pdf, 10, $y + 1, $enfermedad_catastrofica, 3.5);
    $pdf->SetXY(14, $y);
    $pdf->Cell(40, 5, 'Enfermedad Catastrófica', 0, 1);

    // Detalle motivo:
    $pdf->Ln(1);
    $pdf->SetFont('helvetica', 'B', 8);
    $pdf->Cell(22, 5, 'Detalle motivo:', 0, 0);
    $pdf->SetFont('helvetica', '', 8);
    $pdf->Cell(168, 5, $detalle_motivo, 'B', 1);

    // ==========================================
    // ESPACIO EXCLUSIVO DEPARTAMENTO MÉDICO
    // ==========================================
    $pdf->Ln(2);
    $pdf->SetFont('helvetica', 'B', 9);
    $pdf->Cell(0, 5, 'ESPACIO EXCLUSIVO DEPARTAMENTO MÉDICO:', 0, 1);

    // Certifico que el/la Trabajador/a requiere de:
    $y = $pdf->GetY();
    $pdf->SetFont('helvetica', '', 8);
    $pdf->Cell(68, 5, 'Certifico que el/la Trabajador/a requiere de:', 0, 0);

    // Observaciones
    $pdf->SetFont('helvetica', 'B', 8);
    $pdf->Cell(27, 5, 'Observaciones:', 0, 0);
    $pdf->SetFont('helvetica', '', 7);
    $pdf->Cell(47, 5, 'REQUIERE CERTIFICADO MÉDICO', 0, 0);
    dibujar_checkbox($pdf, 154, $y + 1, $observacion_requiere_certificado_medico == 'SI', 3);
    $pdf->SetXY(158, $y);
    $pdf->Cell(5, 5, 'SI', 0, 0);
    dibujar_checkbox($pdf, 168, $y + 1, $observacion_requiere_certificado_medico == 'NO', 3);
    $pdf->SetXY(172, $y);
    $pdf->Cell(5, 5, 'NO', 0, 1);

    // REPOSO
    $y = $pdf->GetY();
    dibujar_checkbox($pdf, 10, $y + 1, $reposo, 3.5);
    $pdf->SetXY(14, $y);
    $pdf->SetFont('helvetica', 'B', 8);
    $pdf->Cell(15, 5, 'REPOSO', 0, 0);

    $pdf->SetFont('helvetica', '', 8);
    $pdf->Cell(8, 5, 'por:', 0, 0);
    dibujar_checkbox($pdf, 40, $y + 1, $enfermedad_general, 3.5);
    $pdf->SetXY(44, $y);
    $pdf->Cell(35, 5, 'Enfermedad General', 0, 0);

    $pdf->SetXY(95, $y);
    $pdf->SetFont('helvetica', '', 7);
    $pdf->Cell(50, 5, 'REQUIERE CERTIFICADO DE ASISTENCIA', 0, 0);
    dibujar_checkbox($pdf, 154, $y + 1, $observacion_requiere_certificado_asistencia == 'SI', 3);
    $pdf->SetXY(158, $y);
    $pdf->Cell(5, 5, 'SI', 0, 0);
    dibujar_checkbox($pdf, 168, $y + 1, $observacion_requiere_certificado_asistencia == 'NO', 3);
    $pdf->SetXY(172, $y);
    $pdf->Cell(5, 5, 'NO', 0, 1);

    // PERMISO
    $y = $pdf->GetY();
    dibujar_checkbox($pdf, 10, $y + 1, $permiso_medico, 3.5);
    $pdf->SetXY(14, $y);
    $pdf->SetFont('helvetica', 'B', 8);
    $pdf->Cell(15, 5, 'PERMISO', 0, 0);

    $pdf->SetFont('helvetica', '', 8);
    $pdf->Cell(8, 5, 'de:', 0, 0);
    dibujar_checkbox($pdf, 40, $y + 1, $asistencia_consulta, 3.5);
    $pdf->SetXY(44, $y);
    $pdf->Cell(35, 5, 'Asistencia a Consulta', 0, 0);

    $pdf->SetXY(95, $y);
    $pdf->Cell(15, 5, '(Motivo)', 0, 0);
    $pdf->Cell(90, 5, $motivo_observacion, 'B', 1);

    // (IDC)
    $y = $pdf->GetY();
    dibujar_checkbox($pdf, 10, $y + 1, $idc, 3.5);
    $pdf->SetXY(14, $y);
    $pdf->SetFont('helvetica', '', 8);
    $pdf->Cell(15, 5, '(IDC)', 0, 0);

    $pdf->SetXY(95, $y);
    $pdf->Cell(12, 5, 'Fecha:', 0, 0);
    $pdf->Cell(7, 5, $fecha_obs_dia, 'B', 0);
    $pdf->Cell(2, 5, '/', 0, 0);
    $pdf->Cell(7, 5, $fecha_obs_mes, 'B', 0);
    $pdf->Cell(2, 5, '/', 0, 0);
    $pdf->Cell(10, 5, $fecha_obs_anio, 'B', 0);

    $pdf->Cell(12, 5, 'Desde', 0, 0);
    $pdf->Cell(18, 5, $desde_obs, 'B', 0);
    $pdf->Cell(12, 5, 'Hasta:', 0, 0);
    $pdf->Cell(18, 5, $hasta_obs, 'B', 1);

    // Líneas de firma
    $pdf->Ln(2);
    $y = $pdf->GetY();

    $pdf->Line(20, $y + 8, 70, $y + 8);
    $pdf->Line(130, $y + 8, 180, $y + 8);

    $pdf->SetY($y + 9);
    $pdf->SetFont('helvetica', '', 8);
    $pdf->Cell(60, 4, 'Firma Médico', 0, 0, 'C');
    $pdf->Cell(70, 4, '', 0, 0);
    $pdf->Cell(60, 4, 'Firma Médico', 0, 1, 'C');

    // ==========================================
    // FECHA Y HORA DEL PERMISO
    // ==========================================
    $pdf->Ln(2);

    // Fondo gris
    $y = $pdf->GetY();
    $pdf->SetFillColor(220, 220, 220);
    $pdf->Rect(10, $y, 190, 5, 'F');

    $pdf->SetXY(10, $y);
    $pdf->SetFont('helvetica', 'B', 9);
    $pdf->Cell(0, 5, 'FECHA Y HORA DEL PERMISO:', 0, 1);

    $pdf->Ln(1);

    // DESDE
    $y = $pdf->GetY();
    $pdf->SetFont('helvetica', 'B', 8);
    $pdf->Cell(15, 5, 'DESDE:', 0, 1);

    $y = $pdf->GetY();
    $pdf->SetFont('helvetica', '', 7);
    $pdf->Cell(10, 5, 'Fecha:', 0, 0);
    $pdf->SetFont('helvetica', '', 8);
    $pdf->Cell(10, 5, $desde_dia, 'B', 0);
    $pdf->Cell(2, 5, '/', 0, 0);
    $pdf->Cell(22, 5, $desde_mes, 'B', 0);
    $pdf->Cell(2, 5, '/', 0, 0);
    $pdf->Cell(5, 5, '20', 0, 0);
    $pdf->Cell(8, 5, $desde_anio, 'B', 0);

    // TOTAL DÍAS
    $pdf->SetXY(65, $y);
    $pdf->SetFont('helvetica', 'B', 8);
    $pdf->Cell(22, 5, 'TOTAL DÍAS:', 0, 0);
    $pdf->SetFont('helvetica', '', 8);
    $pdf->Cell(18, 5, $total_dias, 'B', 0);

    // HASTA
    $pdf->SetXY(110, $y - 5);
    $pdf->SetFont('helvetica', 'B', 8);
    $pdf->Cell(15, 5, 'HASTA:', 0, 1);

    $pdf->SetXY(110, $y);
    $pdf->SetFont('helvetica', '', 7);
    $pdf->Cell(10, 5, 'Fecha:', 0, 0);
    $pdf->SetFont('helvetica', '', 8);
    $pdf->Cell(10, 5, $hasta_dia, 'B', 0);
    $pdf->Cell(2, 5, '/', 0, 0);
    $pdf->Cell(22, 5, $hasta_mes, 'B', 0);
    $pdf->Cell(2, 5, '/', 0, 0);
    $pdf->Cell(5, 5, '20', 0, 0);
    $pdf->Cell(8, 5, $hasta_anio, 'B', 0);

    // TOTAL HORAS
    $pdf->SetXY(165, $y);
    $pdf->SetFont('helvetica', 'B', 8);
    $pdf->Cell(23, 5, 'TOTAL HORAS:', 0, 0);
    $pdf->SetFont('helvetica', '', 8);
    $pdf->Cell(12, 5, $total_horas, 'B', 1);

    // HORA DESDE
    $y = $pdf->GetY();
    $pdf->SetXY(10, $y);
    $pdf->SetFont('helvetica', '', 7);
    $pdf->Cell(10, 5, 'Hora:', 0, 0);
    $pdf->SetFont('helvetica', '', 8);
    $pdf->Cell(10, 5, $desde_hora, 'B', 0);
    $pdf->SetFont('helvetica', '', 7);
    $pdf->Cell(5, 5, 'h', 0, 0);
    $pdf->Cell(10, 5, $desde_min, 'B', 0);
    $pdf->Cell(10, 5, 'min', 0, 0);

    // HORA HASTA
    $pdf->SetXY(110, $y);
    $pdf->SetFont('helvetica', '', 7);
    $pdf->Cell(10, 5, 'Hora:', 0, 0);
    $pdf->SetFont('helvetica', '', 8);
    $pdf->Cell(10, 5, $hasta_hora, 'B', 0);
    $pdf->SetFont('helvetica', '', 7);
    $pdf->Cell(5, 5, 'h', 0, 0);
    $pdf->Cell(10, 5, $hasta_min, 'B', 0);
    $pdf->Cell(10, 5, 'min', 0, 1);

    // ==========================================
    // ESPACIO DE RESPONSABILIDAD (ÚNICAMENTE DOCENTE)
    // ==========================================
    $pdf->Ln(2);
    $y = $pdf->GetY();

    // Dibujar rectángulo
    $pdf->SetDrawColor(0);
    $pdf->SetLineWidth(0.3);
    $pdf->Rect(10, $y, 190, 18);

    $pdf->SetXY(12, $y + 2);
    $pdf->SetFont('helvetica', 'B', 8);
    $pdf->Cell(100, 4, 'ESPACIO DE RESPONSABILIDAD (ÚNICAMENTE DOCENTE):', 0, 0);

    $pdf->SetFont('helvetica', '', 7);
    $pdf->Cell(45, 4, 'Responsable Identificación', 0, 0);
    dibujar_checkbox($pdf, 165, $y + 2, $responsable_identificacion, 3);

    $pdf->Cell(18, 4, '***AVISO', 0, 0);
    dibujar_checkbox($pdf, 192, $y + 2, $aviso_inmediato, 3);
    $pdf->Ln(4);

    $pdf->SetXY(12, $y + 6);
    $pdf->SetFont('helvetica', '', 7);
    $pdf->Cell(100, 3, 'Durante mi inasistencia la/las persona/s que realizará/n el reemplazo, se encuentra previamente informado/a sobre las actividades', 0, 1);

    $pdf->SetXY(12, $y + 9);
    $pdf->Cell(100, 3, 'que debe efectuar el estudiante (Reporte Escrito) correspondiente a su documento escrito (Planificación).', 0, 0);

    $pdf->SetXY(165, $y + 6);
    $pdf->Cell(30, 3, 'INMEDIATO***', 0, 1);

    $pdf->SetXY(12, $y + 12);
    $pdf->Cell(186, 3, '* Todo ausencia del personal docente se ejecutó, indistintamente sea por "emergencia o" por emergencia se otorgará por 48 h.', 0, 1);

    $pdf->SetXY(12, $y + 15);
    $pdf->Cell(25, 3, 'Información:', 0, 0);
    $pdf->Cell(163, 3, $informacion_responsable, 'B', 1);

    // ==========================================
    // OBSERVACIONES
    // ==========================================
    $y = $pdf->GetY() + 1;
    $pdf->SetXY(10, $y);
    $pdf->SetFont('helvetica', '', 6);
    $pdf->MultiCell(190, 2.5, '*** La justificación o permiso con evidencia, respaldo del certificado será validada y notificada; en el período establecido en el Acta de reunión, según instrucción/evaluador.', 0, 'J');

    $pdf->SetXY(10, $y + 5);
    $pdf->MultiCell(190, 2.5, '*** El certificado médico debe incluir: Nombre del paciente/ según Circular Art. 41, Num. 4); Adicionalmente debe estar verificado por la institución del (IT, IESS, GC...N°.48)', 0, 'J');

    $pdf->SetXY(10, $y + 10);
    $pdf->MultiCell(190, 2.5, '* Todo ausencia del funcionario es emergencia, con autorización (indistintamente); es emergencia con verificación en las circunstancias (CT, N°. 42, Núm 1B).', 0, 'J');

    $pdf->SetXY(10, $y + 15);
    $pdf->MultiCell(190, 2.5, '* En caso de reposo, (enfermedad) debe presentar REPOSO; si el permiso es solamente para la Consulta, debe presentar un CERTIFICADO DE ASISTENCIA donde verifique que asistió en el día y fecha que especifique que reposó', 0, 'J');

    // ==========================================
    // FIRMA ELECTRÓNICA Y AUTORIZADO
    // ==========================================
    $pdf->Ln(3);
    $y = $pdf->GetY();

    // Cuadros de firma
    $pdf->SetDrawColor(0);
    $pdf->SetLineWidth(0.3);
    $pdf->Rect(10, $y, 90, 18);
    $pdf->Rect(110, $y, 90, 18);

    // FIRMA ELECTRÓNICA
    $pdf->SetXY(10, $y + 2);
    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->Cell(90, 5, 'FIRMA ELECTRÓNICA', 0, 1, 'C');

    // Línea para la firma
    $pdf->Line(20, $y + 13, 90, $y + 13);

    // AUTORIZADO
    $pdf->SetXY(110, $y + 2);
    $pdf->Cell(90, 5, 'AUTORIZADO', 0, 1, 'C');

    // Línea para la firma
    $pdf->Line(120, $y + 13, 190, $y + 13);

    // Texto adicional
    $pdf->SetXY(12, $y + 14);
    $pdf->SetFont('helvetica', '', 7);
    $pdf->Cell(88, 3, 'Apruebo Solicitante', 0, 0, 'C');

    // ==========================================
    // NOTA FINAL
    // ==========================================
    $pdf->SetY($y + 20);
    $pdf->SetFont('helvetica', '', 5);
    $pdf->MultiCell(0, 2.5, 'Nota: El presente documento debe tener adjunto la "Política de Permisos, Faltas, Licencias, Justificaciones Cargo y Sanciones" adicionales sobre están sujetas.', 0, 'J');

    // ==========================================
    // GENERAR EL PDF
    // ==========================================
    $pdf->Output('solicitud_permiso.pdf', 'I');
}

generar_permiso_pdf();
?>
