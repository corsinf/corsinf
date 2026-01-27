<?php
require_once(dirname(__DIR__, 4) . '/lib/TCPDF/tcpdf.php');

function pdf_reporte_permiso($parametros, $modo_guardar = false)
{
    if (is_string($parametros)) {
        $parametros = json_decode($parametros, true);
    }

    $meses = [
        "",
        "Enero",
        "Febrero",
        "Marzo",
        "Abril",
        "Mayo",
        "Junio",
        "Julio",
        "Agosto",
        "Septiembre",
        "Octubre",
        "Noviembre",
        "Diciembre"
    ];

    $codigo  = 'GTH-PTH-DSP-ANX-02';
    $version = '1.0';
    $pagina  = '1 de 1';

    /* =====================================================
       FECHA DE CREACIÓN
    ===================================================== */
    $f_creacion = isset($parametros['th_sol_per_fecha_creacion'])
        ? strtotime($parametros['th_sol_per_fecha_creacion'])
        : time();

    $fecha_dia   = date('d', $f_creacion);
    $fecha_mes   = $meses[(int)date('m', $f_creacion)];
    $fecha_anio  =  substr(date('y', $f_creacion), -1);

    /* =====================================================
       DATOS PERSONALES
    ===================================================== */
    $nombres       = $parametros['nombre_completo'] ?? 'N/A';
    $cedula        = $parametros['cedula'] ?? 'N/A';
    $cargo         = $parametros['cargo_nombre'] ?? 'N/A';
    $genero        = $parametros['genero'] ?? 'N/A';
    $estado_civil  = $parametros['estado_civil'] ?? 'N/A';
    $asunto        = 'Solicitud';

    /* =====================================================
       MOTIVOS
    ===================================================== */
    $motivo_personal = false;
    $motivo_calamidad = false;
    $motivo_fallecimiento = false;
    $motivo_maternidad_paternidad  = false;
    $motivo_enfermedad  = false;
    $motivo_cita_medica  = false;

    $motivo_db = $parametros['th_sol_per_motivo'] ?? '';

    switch ($motivo_db) {
        case 'PERSONAL':
            $motivo_personal = true;
            break;
        case 'CALAMIDAD':
            $motivo_calamidad = true;
            break;
        case 'FALLECIMIENTO':
            $motivo_fallecimiento = true;
            break;
        case 'MATERNIDAD_PATERNIDAD':
            $motivo_maternidad_paternidad = true;
            break;
        case 'ENFERMEDAD':
            $motivo_enfermedad = true;
            break;
        case 'CITA_MEDICA':
            $motivo_cita_medica = true;
            break;
    }

    $motivo_solicitud = $parametros['th_sol_per_tipo_motivo'];
    $string_motivo = "";

    if ($motivo_solicitud == "MOTIVO_MEDICO") {
        $string_motivo = "Médico";
    } else if ($motivo_solicitud == "MOTIVO_PERSONAL") {
        $string_motivo = "Personal";
    }

    /* =====================================================
       RANGO DE EDAD
    ===================================================== */
    $rango = $parametros['th_sol_per_rango_edad'] ?? '';

    $rango_edad_0_5   = ($rango === '0-5');
    $rango_edad_6_11  = ($rango === '6-11');
    $rango_edad_12_17 = ($rango === '12-17');

    /* =====================================================
       TIPO DE CUIDADO
    ===================================================== */
    $cuidado = $parametros['th_sol_per_tipo_cuidado'] ?? '';

    $discapacidad            = ($cuidado === 'DISCAPACIDAD');
    $adulto_mayor            = ($cuidado === 'ADULTO_MAYOR');
    $enfermedad_catastrofica = ($cuidado === 'ENFERMEDAD_CATASTROFICA');


    /* =====================================================
       ATENCIÓN MÉDICA
    ===================================================== */
    $tipo_atencion = $parametros['th_sol_per_tipo_atencion'] ?? '';

    $atencion_privada = ($tipo_atencion === 'PRIVADA');
    $atencion_publica = ($tipo_atencion === 'PUBLICA');

    $lugar         = $parametros['th_sol_per_lugar'] ?? '';
    $especialidad  = $parametros['th_sol_per_especialidad'] ?? '';
    $nombre_medico = $parametros['th_sol_per_medico'] ?? '';

    /* =====================================================
       FECHA Y HORA DE ATENCIÓN
    ===================================================== */
    $f_atencion = isset($parametros['th_sol_per_fecha_atencion'])
        ? strtotime($parametros['th_sol_per_fecha_atencion'])
        : null;

    $fecha_cita_dia  = $f_atencion ? date('d', $f_atencion) : '';
    $fecha_cita_mes  = $f_atencion ? date('m', $f_atencion) : '';
    $fecha_cita_anio = $f_atencion ? date('y', $f_atencion) : '';

    $h_desde = isset($parametros['th_sol_per_hora_desde'])
        ? strtotime($parametros['th_sol_per_hora_desde'])
        : null;

    $hora_desde_HH = $h_desde ? date('H', $h_desde) : '';
    $hora_desde_MM = $h_desde ? date('i', $h_desde) : '';

    $h_hasta = isset($parametros['th_sol_per_hora_hasta'])
        ? strtotime($parametros['th_sol_per_hora_hasta'])
        : null;

    $hora_hasta_HH = $h_hasta ? date('H', $h_hasta) : '';
    $hora_hasta_MM = $h_hasta ? date('i', $h_hasta) : '';

    $detalle_motivo = $parametros['th_sol_per_detalle'] ?? '';

    /* =====================================================
       PERIODO DEL PERMISO
    ===================================================== */
    $f_principal_permiso = isset($parametros['fecha_principal_permiso']) ? strtotime($parametros['fecha_principal_permiso']) : time();
    $f_desde = isset($parametros['desde']) ? strtotime($parametros['desde']) : time();
    $f_hasta = isset($parametros['hasta']) ? strtotime($parametros['hasta']) : time();


    $desde_dia_permiso  = date('d', $f_principal_permiso);
    $desde_mes_permiso  = $meses[(int)date('m', $f_principal_permiso)];
    $desde_anio_permiso = substr(date('Y', $f_principal_permiso), -1);

    $desde_dia  = date('d', $f_desde);
    $desde_mes  = $meses[(int)date('m', $f_desde)];
    $desde_anio = date('y', $f_desde);
    $desde_hora = date('H', $f_desde);
    $desde_min  = date('i', $f_desde);

    $hasta_dia  = date('d', $f_hasta);
    $hasta_mes  = $meses[(int)date('m', $f_hasta)];
    $hasta_anio = date('y', $f_hasta);
    $hasta_hora = date('H', $f_hasta);
    $hasta_min  = date('i', $f_hasta);



    $total_dias  = $parametros['total_dias_permiso'] ?? '0';
    $total_horas = $parametros['total_horas_permiso'] ?? '0';

    /* =====================================================
   MAPEO TABLA th_solicitud_permiso_medico → PDF
===================================================== */

    // Reposo y permiso
    $presenta_reposo  = ($parametros['reposo'] ?? 0) == 1;
    $presenta_permiso = ($parametros['permiso_consulta'] ?? 0) == 1;

    // Tipo de enfermedad
    $tipo_enfermedad = $parametros['tipo_enfermedad'] ?? '';

    $enfermedad_general  = ($tipo_enfermedad === 'enfermedad_general');
    $asistencia_consulta = ($tipo_enfermedad === 'asistencia_consulta');

    // Código IDG
    $idg_texto_fila1 = $parametros['codigo_idg'] ?? '';
    $idg_texto_fila2 = '';

    // Si no existe la llave en el array, será null.
    $observacion_certificado_medico = isset($parametros['presenta_cert_medico'])
        ? ($parametros['presenta_cert_medico'] == 1 ? 'SI' : 'NO')
        : null;

    $observacion_certificado_asistencia = isset($parametros['presenta_cert_asistencia'])
        ? ($parametros['presenta_cert_asistencia'] == 1 ? 'SI' : 'NO')
        : null;

    // Observaciones médicas
    $motivo_observacion = $parametros['motivo'] ?? '';

    // Fechas médicas
    $f_obs_desde = !empty($parametros['desde'])
        ? strtotime($parametros['desde'])
        : null;

    $f_obs_hasta = !empty($parametros['hasta'])
        ? strtotime($parametros['hasta'])
        : null;

    // Fecha observación (parte superior)
    $fecha_obs_dia  = $f_obs_desde ? date('d', $f_obs_desde) : '';
    $fecha_obs_mes  = $f_obs_desde ? date('m', $f_obs_desde) : '';
    $fecha_obs_anio = $f_obs_desde ? date('y', $f_obs_desde) : '';

    $fecha_desde_completa = $f_obs_desde ? date('d/m/Y', $f_obs_desde) : '';
    $fecha_hasta_completa = $f_obs_hasta ? date('d/m/Y', $f_obs_hasta) : '';

    // Obtener el tipo de cálculo: 'horas' o 'dias' (o 'fechas')
    $tipo_calculo = $parametros['tipo_calculo'] ?? '';

    // --- PROCESAR DESDE ---
    $f_per_desde = !empty($parametros['fecha_desde_permiso']) ? strtotime($parametros['fecha_desde_permiso']) : null;
    $desde_dia  = ($f_per_desde && $tipo_calculo !== 'horas') ? date('d', $f_per_desde) : '';
    $desde_mes  = ($f_per_desde && $tipo_calculo !== 'horas') ? $meses[(int)date('m', $f_per_desde)] : '';
    $desde_anio = ($f_per_desde && $tipo_calculo !== 'horas') ? substr(date('Y', $f_per_desde), -1) : '';
    $desde_hora = ($f_per_desde && $tipo_calculo === 'horas') ? date('H', $f_per_desde) : '';
    $desde_min  = ($f_per_desde && $tipo_calculo === 'horas') ? date('i', $f_per_desde) : '';

    // --- PROCESAR HASTA ---
    $f_per_hasta = !empty($parametros['fecha_hasta_permiso']) ? strtotime($parametros['fecha_hasta_permiso']) : null;
    $hasta_dia  = ($f_per_hasta && $tipo_calculo !== 'horas') ? date('d', $f_per_hasta) : '';
    $hasta_mes  = ($f_per_hasta && $tipo_calculo !== 'horas') ? $meses[(int)date('m', $f_per_hasta)] : '';
    $hasta_anio = ($f_per_hasta && $tipo_calculo !== 'horas') ? substr(date('Y', $f_per_hasta), -1) : '';
    $hasta_hora = ($f_per_hasta && $tipo_calculo === 'horas') ? date('H', $f_per_hasta) : '';
    $hasta_min  = ($f_per_hasta && $tipo_calculo === 'horas') ? date('i', $f_per_hasta) : '';

    // --- TOTALES ---
    $total_dias  = ($tipo_calculo !== 'horas') ? ($parametros['total_dias_permiso'] ?? '0.00') : '---';
    $total_horas = ($tipo_calculo === 'horas') ? ($parametros['total_horas_permiso'] ?? '0.00') : '---';

    $tipo_calculo = $parametros['tipo_calculo'] ?? ''; // 'fechas' o 'horas'


    $nombre_medico = $parametros['nombre_medico'] ?? '';

    // Ruta del documento médico
    $ruta_solicitud_medica = $parametros['ruta_solicitud'] ?? '';


    /* =====================================================
       RESPONSABILIDAD
    ===================================================== */
    // Obtenemos el valor: 0 (No requiere), 1 (Anexo), o null/vacío (Ninguno)
    $planificacion = $parametros['th_sol_per_planificacion'] ?? null;

    // Inicializamos ambos como falsos para que no se marque nada por defecto
    $no_requiere_planificacion = false;
    $anexo_planificacion = false;

    // Solo evaluamos si el valor no es estrictamente nulo o vacío
    if ($planificacion !== null && $planificacion !== '') {
        if ($planificacion == 'NO_REQUIERE') {
            $no_requiere_planificacion = true;
        } elseif ($planificacion == 'ANEXO_PLANIFICACION') {
            $anexo_planificacion = true;
        }
    }
    $autorizo_descuento = true;


    // ==========================================
    // CONFIGURACIÓN DEL PDF
    // ==========================================
    $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);



    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);

    $pdf->setMargins(10, 10, 10);
    $pdf->setAutoPageBreak(TRUE, 10);

    $pdf->AddPage();

    // ==========================================
    // FUNCIÓN PARA DIBUJAR CHECKBOXES
    // ==========================================
    function dibujar_checkbox($pdf, $x, $y, $marcado = false, $size = 3)
    {
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
    $pdf->Rect(10, 10, 20, 18);
    if (file_exists('img/logo.png')) {
        $pdf->Image('img/logo.png', 11, 11, 18, 16);
    }

    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->SetXY(30, 10);
    $pdf->Cell(117, 18, 'SOLICITUD DE PERMISO O JUSTIFICACIÓN', 1, 0, 'C');

    $pdf->SetXY(147, 10);
    $pdf->SetFont('helvetica', 'B', 7);
    $pdf->Cell(18, 6, 'Código:', 1, 0, 'L');
    $pdf->SetFont('helvetica', '', 6);
    $pdf->Cell(35, 6, $codigo, 1, 1, 'C');

    $pdf->SetXY(147, 16);
    $pdf->SetFont('helvetica', 'B', 7);
    $pdf->Cell(18, 6, 'Versión:', 1, 0, 'L');
    $pdf->SetFont('helvetica', '', 7);
    $pdf->Cell(35, 6, $version, 1, 1, 'C');

    $pdf->SetXY(147, 22);
    $pdf->SetFont('helvetica', 'B', 7);
    $pdf->Cell(18, 6, 'Página:', 1, 0, 'L');
    $pdf->SetFont('helvetica', '', 7);
    $pdf->Cell(35, 6, $pagina, 1, 1, 'C');

    // ==========================================
    // FECHA AL FILO DERECHO (Termina exacto en el 6)
    // ==========================================

    // --- LUGAR Y FECHA (FORMATO PARA LLENAR) ---
    $pdf->SetXY(10, 30);
    $pdf->SetFont('helvetica', '', 9);

    // Texto inicial
    $pdf->Cell(118, 5, '', 0, 0); // Espacio para empujar la fecha a la derecha
    $pdf->Cell(12, 5, 'Quito, ', 0, 0, 'R');

    // Día: ______
    $pdf->Cell(10, 5, $fecha_dia, 'B', 0, 'C');
    $pdf->Cell(6, 5, ' de ', 0, 0, 'C');

    // Mes: _________________
    $pdf->Cell(25, 5, $fecha_mes, 'B', 0, 'C');
    $pdf->Cell(12, 5, ' 202', 0, 0, 'R');

    // Año (último dígito): ____
    // El ancho se ajusta para llegar exactamente al borde derecho (196 - 200)
    $pdf->Cell(6, 5, $fecha_anio, 'B', 1, 'C');

    // ==========================================
    // DATOS PERSONALES
    // ==========================================
    $pdf->Ln(2);
    $startY = $pdf->GetY();

    // --- BLOQUE IZQUIERDO (Nombres, Cédula, Cargo) ---
    $pdf->SetFont('helvetica', 'B', 8);
    $pdf->SetXY(10, $startY);
    $pdf->Cell(20, 6, 'NOMBRES:', 0, 0);
    $pdf->SetFont('helvetica', '', 8);
    $pdf->Cell(75, 6, $nombres, 'B', 1);

    $pdf->SetX(10);
    $pdf->SetFont('helvetica', 'B', 8);
    $pdf->Cell(20, 6, 'CÉDULA:', 0, 0);
    $pdf->SetFont('helvetica', '', 8);
    $pdf->Cell(75, 6, $cedula, 'B', 1);

    $pdf->SetX(10);
    $pdf->SetFont('helvetica', 'B', 8);
    $pdf->Cell(30, 6, 'CARGO (contrato):', 0, 0);
    $pdf->SetFont('helvetica', '', 8);
    $pdf->Cell(65, 6, $cargo, 'B', 1);

    // --- BLOQUE CENTRAL (GÉNERO / ASUNTO) ---
    // Aumentamos posX_Gen_Chk para dar espacio a "Prefiero no decirlo"
    $yRow = $startY;
    $posX_Gen_Txt = 107;
    $posX_Gen_Chk = 152;

    $pdf->SetFont('helvetica', 'B', 8);
    $pdf->SetXY($posX_Gen_Txt, $yRow);
    $pdf->Cell(18, 5, 'GÉNERO:', 0, 0);
    $pdf->SetFont('helvetica', '', 7);
    $pdf->Cell(25, 5, 'Masculino', 0, 0);
    dibujar_checkbox($pdf, $posX_Gen_Chk, $yRow + 1, $genero == 'Masculino');

    $yRow += 5;
    $pdf->SetXY($posX_Gen_Txt + 18, $yRow);
    $pdf->Cell(25, 5, 'Femenino', 0, 0);
    dibujar_checkbox($pdf, $posX_Gen_Chk, $yRow + 1, $genero == 'Femenino');

    $yRow += 5;
    $pdf->SetXY($posX_Gen_Txt + 18, $yRow);
    $pdf->SetFont('helvetica', '', 6.5);
    $pdf->Cell(25, 5, 'Prefiero no decirlo', 0, 0);
    dibujar_checkbox($pdf, $posX_Gen_Chk, $yRow + 1, $genero == 'Prefiero no decirlo');

    $yRow += 5;
    $pdf->SetXY($posX_Gen_Txt, $yRow);
    $pdf->SetFont('helvetica', 'B', 8);
    $pdf->Cell(18, 5, 'ASUNTO:', 0, 0);
    $pdf->SetFont('helvetica', '', 7);
    $pdf->Cell(25, 5, 'Solicitud', 0, 0);
    dibujar_checkbox($pdf, $posX_Gen_Chk, $yRow + 1, $asunto == 'Solicitud');

    $yRow += 5;
    $pdf->SetXY($posX_Gen_Txt + 18, $yRow);
    $pdf->Cell(25, 5, 'Justificación', 0, 0);
    dibujar_checkbox($pdf, $posX_Gen_Chk, $yRow + 1, $asunto == 'Justificación');

    // --- BLOQUE DERECHO (ESTADO CIVIL - AL FILO TOTAL) ---
    // Usamos 195 - 200 para que los cuadros queden al borde derecho de la hoja
    $yRow = $startY;
    $posX_Est_Txt = 165;
    $posX_Est_Chk = 196; // Ajustado para terminar en el mismo recto que el año "2026"

    $pdf->SetFont('helvetica', 'B', 8);
    $pdf->SetXY($posX_Est_Txt, $yRow);
    $pdf->Cell(15, 5, 'ESTADO', 0, 0);
    $pdf->SetFont('helvetica', '', 7);
    $pdf->Cell(15, 5, 'Soltero/a', 0, 0);
    dibujar_checkbox($pdf, $posX_Est_Chk, $yRow + 1, $estado_civil == 'Soltero/a');

    $yRow += 5;
    $pdf->SetXY($posX_Est_Txt, $yRow);
    $pdf->SetFont('helvetica', 'B', 8);
    $pdf->Cell(15, 5, 'CIVIL:', 0, 0);
    $pdf->SetFont('helvetica', '', 7);
    $pdf->Cell(15, 5, 'Casado/a', 0, 0);
    dibujar_checkbox($pdf, $posX_Est_Chk, $yRow + 1, $estado_civil == 'Casado/a');

    $yRow += 5;
    $pdf->SetXY($posX_Est_Txt + 15, $yRow);
    $pdf->Cell(15, 5, 'Unión H.', 0, 0);
    dibujar_checkbox($pdf, $posX_Est_Chk, $yRow + 1, $estado_civil == 'Unión H.');

    $yRow += 5;
    $pdf->SetXY($posX_Est_Txt + 15, $yRow);
    $pdf->Cell(15, 5, 'Divorcio/a', 0, 0);
    dibujar_checkbox($pdf, $posX_Est_Chk, $yRow + 1, $estado_civil == 'Divorcio/a');

    $yRow += 5;
    $pdf->SetXY($posX_Est_Txt + 15, $yRow);
    $pdf->Cell(15, 5, 'Viudo/a', 0, 0);
    dibujar_checkbox($pdf, $posX_Est_Chk, $yRow + 1, $estado_civil == 'Viudo/a');

    // ==========================================
    // SECCIÓN DE MOTIVO REESTRUCTURADA
    // ==========================================

    $pdf->Ln(7);
    $y_inicio = $pdf->GetY();
    $pdf->SetFillColor(220, 220, 220);
    $pdf->Rect(10, $y_inicio, 190, 5, 'F');
    $pdf->SetXY(10, $y_inicio);
    $pdf->SetFont('helvetica', 'B', 8);
    $pdf->Cell(30, 5, 'MOTIVO: ' . $string_motivo, 0, 1);

    $y = $pdf->GetY() + 2;
    $colX1_Chk = 95;   // Punto checkbox columna 1
    $colX_Final = 196; // Punto checkbox columna 2

    if ($motivo_db == "PERSONAL" || $motivo_db == "FALLECIMIENTO" || $motivo_db == "CALAMIDAD") {
        $col_chk = 100; // Checkboxes alineados a la derecha (ancho total)
        $pdf->SetFont('helvetica', '', 9);

        // 1. Personal
        $pdf->SetXY(10, $y);
        $pdf->Cell(50, 5, '* Personal', 0, 0);
        dibujar_checkbox($pdf, $col_chk, $y + 1, $motivo_personal, 4);

        // 2. Calamidad Doméstica
        $y += 8;
        $pdf->SetXY(10, $y);
        $pdf->Cell(50, 5, 'Calamidad Doméstica', 0, 0);
        dibujar_checkbox($pdf, $col_chk, $y + 1, $motivo_calamidad, 4);
        $y += 4;
        $pdf->SetXY(10, $y);
        $pdf->SetFont('helvetica', '', 7);
        $pdf->Cell(50, 4, '(Siniestros y Catástrofes)', 0, 0);

        // 3. Fallecimiento
        $y += 7;
        $pdf->SetXY(10, $y);
        $pdf->SetFont('helvetica', '', 9);
        $pdf->Cell(50, 5, 'Fallecimiento (familiares)', 0, 0);
        dibujar_checkbox($pdf, $col_chk, $y + 1, $motivo_fallecimiento, 4);
        $y += 4;
        $pdf->SetXY(10, $y);
        $pdf->SetFont('helvetica', '', 7);
        $pdf->Cell(50, 4, '(Adjuntar acta de defunción)', 0, 0);

        // 4. Familiar - Hijos (Rango de Edad)
        $y += 8;
        $pdf->SetXY(10, $y);
        $pdf->SetFont('helvetica', '', 9);
        $pdf->Cell(35, 5, 'Familiar - hijos', 0, 0);
        $pdf->Cell(30, 5, '0 - 5 años', 0, 0);
        dibujar_checkbox($pdf, $col_chk, $y + 1, $rango_edad_0_5, 4);

        $y += 6;
        $pdf->SetXY(10, $y);
        $pdf->Cell(35, 5, '(Rango de Edad)', 0, 0);
        $pdf->Cell(30, 5, '6 - 11 años', 0, 0);
        dibujar_checkbox($pdf, $col_chk, $y + 1, $rango_edad_6_11, 4);

        $y += 6;
        $pdf->SetXY(45, $y); // Alineado con los rangos anteriores
        $pdf->Cell(30, 5, '12 - 17 años', 0, 0);
        dibujar_checkbox($pdf, $col_chk, $y + 1, $rango_edad_12_17, 4);

        // 5. Familiar - Adultos
        $y += 8;
        $pdf->SetXY(10, $y);
        $pdf->Cell(35, 5, 'Familiar - adultos', 0, 0);
        $pdf->Cell(30, 5, 'Discapacidad', 0, 0);
        dibujar_checkbox($pdf, $col_chk, $y + 1, $discapacidad, 4);

        $y += 6;
        $pdf->SetXY(10, $y);
        $pdf->Cell(35, 5, '(Cuidado Familiar)', 0, 0);
        $pdf->Cell(30, 5, 'Adulto Mayor', 0, 0);
        dibujar_checkbox($pdf, $col_chk, $y + 1, $adulto_mayor, 4);

        // 6. Enfermedad Catastrófica
        $y += 6;
        $pdf->SetXY(45, $y);
        $pdf->Cell(45, 5, 'Enfermedad Catastrófica', 0, 0);
        dibujar_checkbox($pdf, $col_chk, $y + 1, $enfermedad_catastrofica, 4);

        // 7. Detalle Motivo (Ancho completo)
        $y += 10;
        $pdf->SetXY(10, $y);
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->Cell(30, 5, 'Detalle motivo:', 0, 0);
        $pdf->SetFont('helvetica', '', 9);
        // 160mm es el espacio restante para llegar al final del margen (190 total)
        $pdf->Cell(160, 5, $detalle_motivo, 'B', 1);
    } else if ($motivo_db == "MATERNIDAD_PATERNIDAD" || $motivo_db == "ENFERMEDAD" || $motivo_db == "CITA_MEDICA") {

        $col_chk = 120;
        $pdf->SetFont('helvetica', '', 9);

        // 1. Maternidad / Paternidad
        $pdf->SetXY(10, $y);
        $pdf->Cell(50, 5, 'Maternidad / Paternidad', 0, 0);
        dibujar_checkbox($pdf, $col_chk, $y + 1, $motivo_maternidad_paternidad, 4);

        $y += 4.5; // Salto corto para la nota
        $pdf->SetXY(10, $y);
        $pdf->SetFont('helvetica', '', 7);
        $pdf->Cell(90, 4, '(Adjuntar certificado Nacido Vivo)', 0, 0);

        // 2. Enfermedad
        $y += 5.5;
        $pdf->SetXY(10, $y);
        $pdf->SetFont('helvetica', '', 9);
        $pdf->Cell(50, 5, 'Enfermedad', 0, 0);
        dibujar_checkbox($pdf, $col_chk, $y + 1, $motivo_enfermedad, 4);

        $y += 4.5;
        $pdf->SetXY(10, $y);
        $pdf->SetFont('helvetica', '', 7);
        $pdf->Cell(90, 4, '(Adjuntar certificado)', 0, 0);

        // 3. Cita Médica
        $y += 5.5;
        $pdf->SetXY(10, $y);
        $pdf->SetFont('helvetica', '', 9);
        $pdf->Cell(50, 5, 'Cita Médica', 0, 0);
        dibujar_checkbox($pdf, $col_chk, $y + 1, $motivo_cita_medica, 4);

        $y += 4.5;
        $pdf->SetXY(10, $y);
        $pdf->SetFont('helvetica', '', 7);
        $pdf->Cell(90, 4, '(Adjuntar certificado)', 0, 0);

        // --- Detalles de Atención ---
        $y += 6;
        $pdf->SetXY(10, $y);
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->Cell(50, 5, 'Detalle de Atención:', 0, 0);
        $pdf->SetFont('helvetica', '', 9);
        $pdf->Cell(10, 5, '* Privada', 0, 0);
        dibujar_checkbox($pdf, 80, $y + 1, $atencion_privada, 4);

        $pdf->SetXY(100, $y);
        $pdf->Cell(20, 5, '** Pública', 0, 0);
        dibujar_checkbox($pdf, $col_chk, $y + 1, $atencion_publica, 4);

        $y += 4.5;
        $pdf->SetXY(10, $y);
        $pdf->SetFont('helvetica', '', 7);
        $pdf->Cell(90, 4, '(Enfermedad o Cita Médica)', 0, 0);

        // Campos de texto (Espaciado reducido a 6mm)
        $y += 6;
        $pdf->SetXY(10, $y);
        $pdf->SetFont('helvetica', '', 9);
        $pdf->Cell(15, 5, 'Lugar:', 0, 0);
        $pdf->Cell(170, 5, $lugar, 'B', 1);

        $y += 6;
        $pdf->SetXY(10, $y);
        $pdf->Cell(25, 5, 'Especialidad:', 0, 0);
        $pdf->Cell(160, 5, $especialidad, 'B', 1);

        $y += 6;
        $pdf->SetXY(10, $y);
        $pdf->Cell(35, 5, 'Nombre Médico:', 0, 0);
        $pdf->Cell(150, 5, $nombre_medico, 'B', 1);

        // Fila de Fecha y Horas (Todo en una misma línea Y)
        $y += 6.5;
        $pdf->SetXY(10, $y);
        $pdf->Cell(12, 5, 'Fecha:', 0, 0);
        $pdf->Cell(28, 5, "$fecha_cita_dia/$fecha_cita_mes/$fecha_cita_anio", 'B', 0, 'C');
        $pdf->Cell(25, 5, '  Hora Desde:', 0, 0);
        $pdf->Cell(20, 5, "$hora_desde_HH:$hora_desde_MM", 'B', 0, 'C');
        $pdf->Cell(20, 5, '  Hasta:', 0, 0);
        $pdf->Cell(20, 5, "$hora_hasta_HH:$hora_hasta_MM", 'B', 1, 'C');

        // Detalle Motivo
        $y += 7;
        $pdf->SetXY(10, $y);
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->Cell(28, 5, 'Detalle motivo:', 0, 0);
        $pdf->SetFont('helvetica', '', 9);
        $pdf->Cell(157, 5, $detalle_motivo, 'B', 1);
    }

    // ==========================================
    // SECCIÓN EXCLUSIVA DEPARTAMENTO MÉDICO
    // ==========================================

    if ($motivo_db == "PERSONAL" || $motivo_db == "FALLECIMIENTO" || $motivo_db == "CALAMIDAD") {
    } else {
        $pdf->Ln(3);
        $y = $pdf->GetY();
        $pdf->SetFillColor(220, 220, 220);
        $pdf->Rect(10, $y, 190, 5, 'F');
        $pdf->SetXY(10, $y);
        $pdf->SetFont('helvetica', 'B', 8);
        $pdf->Cell(190, 5, 'ESPACIO EXCLUSIVO DEPARTAMENTO MÉDICO:', 0, 1);

        $colX_Final = 196; // El recto donde todo debe terminar
        $colX_Checks_Derecha = 95; // Donde terminan los checks de la columna izquierda

        $y = $pdf->GetY() + 2;
        $pdf->SetXY(10, $y);
        $pdf->SetFont('helvetica', '', 8);
        $pdf->Cell(60, 5, 'Certifico que el/la Trabajador/a requiere de:', 0, 1);

        // --- FILA REPOSO ---
        $y = $pdf->GetY() + 1;
        $pdf->SetXY(15, $y);
        $pdf->SetFont('helvetica', 'B', 8);
        $pdf->Cell(18, 5, 'REPOSO', 0, 0);
        dibujar_checkbox($pdf, 29 + 5, $y + 1, $presenta_reposo, 3);

        $pdf->SetXY(55, $y);
        $pdf->SetFont('helvetica', '', 8);
        $pdf->Cell(45, 5, 'por: Enfermedad General', 0, 0);
        dibujar_checkbox($pdf, $colX_Checks_Derecha, $y + 1, $enfermedad_general, 3);

        // Observaciones SI NO (Alineado al recto 196)
        $pdf->SetXY(105, $y);
        $pdf->Cell(65, 5, 'Observaciones: PRESENTA CERTIFICADO MÉDICO', 0, 0);
        $pdf->SetXY(175, $y);
        $pdf->Cell(5, 5, 'SI', 0, 0);
        dibujar_checkbox($pdf, 180, $y + 1, $observacion_certificado_medico == 'SI', 3);
        $pdf->SetXY(186, $y);
        $pdf->Cell(5, 5, 'NO', 0, 0);
        dibujar_checkbox($pdf, 195, $y + 1, $observacion_certificado_medico == 'NO', 3);

        // --- FILA PERMISO ---
        $y += 6;
        $pdf->SetXY(15, $y);
        $pdf->SetFont('helvetica', 'B', 8);
        $pdf->Cell(18, 5, 'PERMISO', 0, 0);
        dibujar_checkbox($pdf, 29 + 5, $y + 1, $presenta_permiso, 3);

        $pdf->SetXY(55, $y);
        $pdf->SetFont('helvetica', '', 8);
        $pdf->Cell(45, 5, 'Asistencia a Consulta', 0, 0);
        dibujar_checkbox($pdf, $colX_Checks_Derecha, $y + 1, $asistencia_consulta, 3);

        // Certificado Asistencia SI NO
        $pdf->SetXY(105, $y);
        $pdf->Cell(65, 5, 'PRESENTA CERTIFICADO DE ASISTENCIA', 0, 0);
        $pdf->SetXY(175, $y);
        $pdf->Cell(5, 5, 'SI', 0, 0);
        dibujar_checkbox($pdf, 180, $y + 1, $observacion_certificado_asistencia == 'SI', 3);
        $pdf->SetXY(186, $y);
        $pdf->Cell(5, 5, 'NO', 0, 0);
        dibujar_checkbox($pdf, 195, $y + 1, $observacion_certificado_asistencia == 'NO', 3);

        // --- FILA IDG (DOS FILAS) / MOTIVO ---
        $y += 6;
        $pdf->SetXY(10, $y);
        $pdf->SetFont('helvetica', '', 8);
        $pdf->Cell(10, 5, '(IDG)', 0, 0);
        $pdf->Cell(78, 5, $idg_texto_fila1, 'B', 0); // Fila 1 de IDG

        // Motivo al lado derecho
        $pdf->SetXY(105, $y);
        $pdf->Cell(12, 5, '(Motivo)', 0, 0);
        $pdf->Cell($colX_Final + 3 - 117, 5, $motivo_observacion, 'B', 1);

        // Segunda fila de IDG
        $y += 5;
        $pdf->SetXY(12, $y);
        $pdf->Cell(86, 7, $idg_texto_fila2, 'B', 1); // Fila 2 de IDG

        // --- FILA FECHA Y HORA (Formato solicitado) ---
        $y += 1;
        // --- FILA FECHA Y HORA MÉDICA ---
        $pdf->SetXY(105, $y);
        $pdf->SetFont('helvetica', '', 7);
        $pdf->Cell(10, 5, 'Fecha:', 0, 0);

        // Fecha: ____/____/____
        $pdf->Cell(7, 5, $fecha_obs_dia, 'B', 0, 'C');
        $pdf->Cell(2, 5, '/', 0, 0, 'C');
        $pdf->Cell(7, 5, $fecha_obs_mes, 'B', 0, 'C');
        $pdf->Cell(2, 5, '/', 0, 0, 'C');
        $pdf->Cell(10, 5, $fecha_obs_anio, 'B', 0, 'C');

        $pdf->Cell(10, 5, ' Desde', 0, 0);

        $pdf->Cell(17, 5, $fecha_desde_completa, 'B', 0, 'C');
        $pdf->Cell(10, 5, ' Hasta:', 0, 0);

        // Calculamos el ancho restante para que la línea llegue exactamente a 196
        $anchoFinal = 199 - $pdf->GetX();
        $pdf->Cell($anchoFinal, 5, $fecha_hasta_completa, 'B', 1, 'C');

        // ==========================================
        // FIRMAS DEPARTAMENTO MÉDICO
        // ==========================================
        $pdf->Ln(7);
        $yFirmas = $pdf->GetY();

        // Firma Izquierda
        $pdf->Line(20, $yFirmas, 80, $yFirmas);
        $pdf->SetXY(20, $yFirmas + 1);
        $pdf->SetFont('helvetica', 'B', 7);
        $pdf->Cell(60, 4, 'FIRMA MÉDICO / ENFERMERA(O)', 0, 0, 'C');

        // Firma Derecha
        $pdf->Line(120, $yFirmas, 180, $yFirmas);
        $pdf->SetXY(120, $yFirmas + 1);
        $pdf->Cell(60, 4, 'FIRMA MÉDICO / ENFERMERA(O)', 0, 1, 'C');
    }

    $tipo_calculo = $parametros['tipo_calculo'] ?? '';

    // ==========================================
    // SECCIÓN: FECHA Y HORA DEL PERMISO
    // ==========================================
    $pdf->Ln(3);
    $y = $pdf->GetY();
    $pdf->SetFillColor(220, 220, 220);
    $pdf->Rect(10, $y, 190, 5, 'F');
    $pdf->SetFont('helvetica', 'B', 9);
    $pdf->SetXY(10, $y);
    $pdf->Cell(190, 5, 'DETALLE DEL TIEMPO SOLICITADO:', 0, 1);

    $pdf->Ln(2);
    $yRow1 = $pdf->GetY();

    if ($tipo_calculo == "fecha") {
        // --- MODO SOLO FECHAS (DÍAS COMPLETOS) EN UNA SOLA FILA ---

        $pdf->SetXY(10, $yRow1);

        // 1. Bloque DESDE (Aprox. 75mm)
        $pdf->SetFont('helvetica', 'B', 8);
        $pdf->Cell(12, 5, 'DESDE:', 0, 0);
        $pdf->SetFont('helvetica', '', 7);
        $pdf->Cell(9, 5, '(fecha)', 0, 0);
        $pdf->Cell(8, 5, $desde_dia, 'B', 0, 'C');
        $pdf->Cell(3, 5, '/', 0, 0, 'C');
        $pdf->Cell(25, 5, $desde_mes, 'B', 0, 'C');
        $pdf->Cell(3, 5, '/', 0, 0, 'C');
        $pdf->Cell(7, 5, '202', 0, 0, 'R');
        $pdf->Cell(4, 5, $desde_anio, 'B', 0, 'C');

        // 2. Bloque HASTA (Aprox. 75mm)
        $pdf->SetX(85); // Espacio suficiente después del primer bloque
        $pdf->SetFont('helvetica', 'B', 8);
        $pdf->Cell(12, 5, 'HASTA:', 0, 0);
        $pdf->SetFont('helvetica', '', 7);
        $pdf->Cell(9, 5, '(fecha)', 0, 0);
        $pdf->Cell(8, 5, $hasta_dia, 'B', 0, 'C');
        $pdf->Cell(3, 5, '/', 0, 0, 'C');
        $pdf->Cell(25, 5, $hasta_mes, 'B', 0, 'C');
        $pdf->Cell(3, 5, '/', 0, 0, 'C');
        $pdf->Cell(7, 5, '202', 0, 0, 'R');
        $pdf->Cell(4, 5, $hasta_anio, 'B', 0, 'C');

        // 3. Bloque TOTAL DÍAS (Aprox. 40mm)
        $pdf->SetX(160);
        $pdf->SetFont('helvetica', 'B', 8);
        $pdf->Cell(20, 5, 'TOTAL DÍAS:', 0, 0);
        $pdf->SetFont('helvetica', '', 8);
        $pdf->Cell(15, 5, $total_dias, 'B', 1, 'C'); // Salto de línea final
    } else if ($tipo_calculo == "horas") {
        // --- MODO HORAS EN UNA SOLA FILA ---

        $pdf->SetXY(10, $yRow1);

        // 1. Bloque de FECHA (Aprox. 60mm)
        $pdf->SetFont('helvetica', 'B', 8);
        $pdf->Cell(12, 5, 'FECHA:', 0, 0);
        $pdf->SetFont('helvetica', '', 7);
        $pdf->Cell(9, 5, '(fecha)', 0, 0);
        $pdf->Cell(8, 5, $desde_dia_permiso, 'B', 0, 'C');
        $pdf->Cell(3, 5, '/', 0, 0, 'C');
        $pdf->Cell(25, 5, $desde_mes_permiso, 'B', 0, 'C');
        $pdf->Cell(3, 5, '/', 0, 0, 'C');
        $pdf->Cell(7, 5, '202', 0, 0, 'R');
        $pdf->Cell(4, 5, $desde_anio_permiso, 'B', 0, 'C');

        // 2. Bloque DESDE HORA (Aprox. 35mm)
        $pdf->SetX(85);
        $pdf->SetFont('helvetica', 'B', 8);
        $pdf->Cell(12, 5, 'DESDE:', 0, 0);
        $pdf->SetFont('helvetica', '', 8);
        $pdf->Cell(15, 5, $desde_hora . ':' . $desde_min, 'B', 0, 'C');

        // 3. Bloque HASTA HORA (Aprox. 35mm)
        $pdf->SetX(117);
        $pdf->SetFont('helvetica', 'B', 8);
        $pdf->Cell(12, 5, 'HASTA:', 0, 0);
        $pdf->SetFont('helvetica', '', 8);
        $pdf->Cell(15, 5, $hasta_hora . ':' . $hasta_min, 'B', 0, 'C');

        // 4. Bloque TOTAL HORAS (Aprox. 45mm)
        $pdf->SetX(150);
        $pdf->SetFont('helvetica', 'B', 8);
        $pdf->Cell(25, 5, 'TOTAL HORAS:', 0, 0);
        $pdf->SetFont('helvetica', '', 8);
        $pdf->Cell(20, 5, $total_horas, 'B', 1, 'C'); // Aquí el 1 para bajar de fila al terminar
    }

    // ==========================================
    // ESPACIO DE RESPONSABILIDAD (PERSONAL DOCENTE)
    // ==========================================
    if ($planificacion !== null && $planificacion !== '') {
    } else {

        $pdf->Ln(3);
        $y = $pdf->GetY();

        // Dibujar cuadro con línea punteada
        $pdf->SetLineStyle(array('dash' => '2,2', 'color' => array(0, 0, 0)));
        $pdf->Rect(10, $y, 190, 18);

        // Título principal (ajustado ancho a 95 para dar espacio)
        $pdf->SetXY(11, $y + 1);
        $pdf->SetFont('helvetica', 'B', 7.5);
        $pdf->Cell(95, 5, 'ESPACIO DE RESPONSABILIDAD (ÚNICAMENTE PERSONAL DOCENTE):', 0, 0);

        // --- BLOQUE 1: NO REQUIERE PLANIFICACIÓN ---
        $pdf->SetXY(108, $y + 1); // Movido a la izquierda para dar aire
        $pdf->SetFont('helvetica', 'B', 7);
        $pdf->Cell(45, 5, '*** NO REQUIERE PLANIFICACIÓN', 0, 0, 'R');
        // Checkbox después del texto (en X=154 para no chocar)
        dibujar_checkbox($pdf, 154, $y + 1.5, $no_requiere_planificacion, 3);

        // --- BLOQUE 2: ANEXO PLANIFICACIÓN ---
        $pdf->SetXY(158, $y + 1); // Empieza después del primer check
        $pdf->Cell(33, 5, '***ANEXO PLANIFICACIÓN', 0, 0, 'R');
        // Checkbox al final (en X=192 para alinear al borde derecho)
        dibujar_checkbox($pdf, 192, $y + 1.5, $anexo_planificacion, 3);

        // --- PÁRRAFO INFERIOR ---
        $pdf->SetLineStyle(array('dash' => 0)); // Reset a línea sólida
        $pdf->SetXY(11, $y + 7);
        $pdf->SetFont('helvetica', '', 8.5);

        // Usamos MultiCell para que el texto fluya correctamente dentro del cuadro
        $pdf->MultiCell(188, 4, 'Durante mi inasistencia la/las persona/s que realizará/n el reemplazo, se encuentran previamente informado/s sobre las actividades que desarrollarán por cada hora/día de mi ausencia mediante documento anexo (Planificación).', 0, 'L');
    }


    // ==========================================
    // NOTAS AL PIE
    // ==========================================
    $pdf->Ln(4);
    $pdf->SetX(10);
    // Tamaño de fuente reducido para notas legales
    $pdf->SetFont('helvetica', '', 5);

    // El alto de celda (1.8) y el ancho (190) aseguran que queden como 4 bloques definidos
    $pdf->MultiCell(190, 1.8, '* Toda solicitud de permiso Personal o atención Médica privada es considerará "personal" a excepción de los establecidos por la ley.', 0, 'J');

    $pdf->SetX(10);
    $pdf->MultiCell(190, 1.8, '** Se concederá a los trabajadores el tiempo necesario para ser atendidos por los facultativos del IESS, tales permisos se otorgarán sin reducción de las remuneraciones (CT: Art. 42, numeral 9).', 0, 'J');

    $pdf->SetX(10);
    $pdf->MultiCell(190, 1.8, '*** La Planificación anexa cuenta con todos los espacios debidamente solicitadas a demás con la revisión y autorización del Área Académica, según corresponda.', 0, 'J');

    $pdf->SetX(10);
    $pdf->MultiCell(190, 1.8, '**** El área de Inspección, comunicará a padres de familia, representantes u otros según corresponda sobre la inasistencia del Docente, coadyuvado además a la presentación del Docente de remplazo.', 0, 'J');
    // ==========================================
    // SECCIÓN DE FIRMAS (SIMETRÍA TOTAL)
    // ==========================================
    $pdf->Ln(2);
    $y = $pdf->GetY();
    $altoCuadro = 22;
    $anchoCuadro = 95;

    // Definimos el estilo de línea una sola vez para ambos
    $pdf->SetDrawColor(0);
    $pdf->SetLineWidth(0.4); // Grosor uniforme para que se vea como la imagen

    // 1. CUADRO IZQUIERDO
    $pdf->Rect(10, $y, $anchoCuadro, $altoCuadro);

    // Contenido Izquierdo
    $pdf->SetXY(11, $y + 1);
    $pdf->SetFont('helvetica', 'B', 8);
    $pdf->Cell(30, 4, 'Observaciones:', 0, 0);

    // Checkbox y texto Autorizo Descuento
    $yCheck = $y + 5;
    dibujar_checkbox($pdf, 16, $yCheck, $autorizo_descuento, 5);
    $pdf->SetXY(11, $yCheck + 5.5);
    $pdf->SetFont('helvetica', 'B', 6);
    $pdf->MultiCell(16, 2.5, "AUTORIZO\nDESCUENTO", 0, 'C');

    // Línea de Firma Electrónica
    $pdf->Line(35, $y + 16, 95, $y + 16);
    $pdf->SetXY(35, $y + 16);
    $pdf->SetFont('helvetica', 'B', 9);
    $pdf->Cell(60, 6, 'FIRMA ELECTRÓNICA', 0, 0, 'C');

    // 2. CUADRO DERECHO
    // Importante: Empezamos en 105.1 para evitar que la línea central se vea doble
    $pdf->Rect(105, $y, $anchoCuadro, $altoCuadro);

    // Línea de Autorizado
    $pdf->Line(110, $y + 16, 195, $y + 16);
    $pdf->SetXY(105, $y + 16);
    $pdf->SetFont('helvetica', 'B', 9);
    $pdf->Cell(95, 6, 'AUTORIZADO', 0, 0, 'C');

    // 3. NOTA AL PIE
    $pdf->SetXY(10, $y + $altoCuadro + 1);
    $pdf->SetFont('helvetica', '', 6);
    $pdf->Cell(190, 4, 'Nota: El presente documento tiene como base la "Política de Solicitud Permisos, Faltas, Licencias, Justificaciones y Cargo a Vacaciones" debidamente socializadas.', 0, 1, 'L');
    // ==========================================
    // LEYENDAS DE CONFIDENCIALIDAD
    // ==========================================

    // Primera línea: DOCUMENTO CONFIDENCIAL
    $pdf->SetFont('helvetica', 'B', 7); // Fuente normal, negrita para resaltar
    $pdf->Cell(0, 4, 'DOCUMENTO CONFIDENCIAL.', 0, 1, 'C');

    // Segunda línea: Cláusula de prohibición
    $pdf->SetFont('helvetica', '', 7); // Fuente normal sin negrita
    $pdf->MultiCell(0, 4, 'Se prohíbe su impresión, copia o reproducción total o parcial, sin la debida autorización de la "Unidad Educativa Particular Saint Dominic School"', 0, 'C');

    if ($modo_guardar) {
        // Devolver el contenido del PDF para guardarlo
        return $pdf->Output('', 'S'); // 'S' retorna el PDF como string
    } else {
        // Limpiar cualquier salida previa
        if (ob_get_length()) {
            ob_end_clean();
        }
        // Mostrar en navegador
        $pdf->Output('Solicitud_Permiso.pdf', 'I');
        exit;
    }
}
