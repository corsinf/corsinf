<?php

date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 2) . '/modelo/TALENTO_HUMANO/th_control_acceso_calculosM.php');
require_once(dirname(__DIR__, 2) . '/modelo/TALENTO_HUMANO/th_reporte_camposM.php');
require_once(dirname(__DIR__, 2) . '/lib/spout_excel/vendor/box/spout/src/Spout/Autoloader/autoload.php');

$controlador = new th_control_acceso_calculosC();

use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Box\Spout\Common\Entity\Row;
use Box\Spout\Common\Type;
use Box\Spout\Writer\WriterInterface;
use Box\Spout\Writer\Common\Creator\Style\StyleBuilder;
use Box\Spout\Common\Entity\Style\CellAlignment;
use Box\Spout\Common\Entity\Style\Color;

if (isset($_GET['reporte'])) {
    // echo json_encode($controlador->pruebas());
    echo json_encode($controlador->control_acceso_reporte($_POST['parametros'] ?? ''));
}

if (isset($_GET['descargar_excel'])) {
    $parametros = [
        'txt_fecha_inicio' => $_GET['txt_fecha_inicio'] ?? '',
        'txt_fecha_fin' => $_GET['txt_fecha_fin'] ?? '',
        'ddl_departamentos' => $_GET['ddl_departamentos'] ?? '',
        '_id' => $_GET['_id'] ?? '',

    ];

    $controlador->descargar_excel('reporte.xlsx', $parametros, $encabezado);
}

if (isset($_GET['informacion_marcacion'])) {
    echo json_encode($controlador->mostrar_informacion_marcacion($_POST['id_marcacion'] ?? ''));
}

class th_control_acceso_calculosC
{
    private $modelo;
    private $encabezados;

    function __construct()
    {
        $this->modelo = new th_control_acceso_calculosM();
        $this->encabezados = new th_reporte_camposM();
    }

    //Usa para el boton de descargar Excel
    function descargar_excel($nombreArchivo = 'Reporte.xlsx', $parametros = [])
    {
        $txt_fecha_inicio   = $parametros['txt_fecha_inicio'] ?? '';
        $txt_fecha_fin      = $parametros['txt_fecha_fin'] ?? '';
        $ddl_departamentos  = $parametros['ddl_departamentos'] ?? '';
        $id                 = $parametros['_id'] ?? '';

        // Obtener lista de encabezados desde la BD (array de objetos o arrays)
        $listaEncabezados = $this->encabezados->listar_reporte_campos($id);

        // Crear array plano solo con los títulos (encabezados)
        $encabezado = [];
        foreach ($listaEncabezados as $item) {
            $encabezado[] = is_object($item) ? $item->nombre_encabezado : $item['nombre_encabezado'];
        }

        // Mapa de encabezados a claves reales en datos
        $mapaClaveDato = [
            'APELLIDOS' => 'apellidos',
            'NOMBRES' => 'nombres',
            'Empleado' => 'empleado',
            'Cedula' => 'cedula',
            'Correo Institucional' => 'correo_institucional',
            'Departamento' => 'departamento',
            'Dia' => 'dia',
            'Fecha' => 'fecha',
            'Horario Contrato' => 'horario_contrato',
            'Turno' => 'turno_nombre',
            'Entrada Inicio Turno' => 'entrada_hora_inicio_turno',
            'Entrada Fin Turno' => 'entrada_hora_fin_turno',
            'RegEntrada' => 'regentrada',
            'Hora Entrada' => 'hora_entrada',
            'Hora Ajustada' => 'hora_ajustada',
            'Atrasos' => 'atrasos',
            'Ausente' => 'ausente',
            'Salida Inicio Turno' => 'salida_hora_inicio_turno',
            'Salida Fin Turno' => 'salida_hora_fin_turno',
            'RegSalida' => 'regsalida',
            'Hora Salida' => 'hora_salida',
            'Salidas Temprano' => 'salidas_temprano',
            'Dias Trabajados' => 'dias_trabajados',
            'Cumplimiento de jornada (8 horas)' => 'cumple_jornada',
            'Horas faltantes por cumplir jornada' => 'horas_faltantes',
            'Horas excedentes' => 'horas_excedentes',
            'SalidasTemprano' => 'salidas_temprano',
            'Suplem 25%' => 'horas_suplementarias',
            'Extra 100%' => 'horas_extraordinarias',
        ];

        // Obtener datos filtrados de acuerdo a parámetros
        $datos = $this->modelo->listar_asistencia_por_fecha_departamento(
            $txt_fecha_inicio,
            $txt_fecha_fin,
            $ddl_departamentos
        );

        // Limpiar buffer para evitar errores al descargar
        if (ob_get_length()) {
            ob_end_clean();
        }

        // Preparar headers para descarga Excel
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $nombreArchivo . '"');
        header('Cache-Control: max-age=0');

        // Crear escritor XLSX (Spout)
        $writer = WriterEntityFactory::createXLSXWriter();
        $writer->openToBrowser($nombreArchivo);

        // Estilo para encabezado
        $estilo = (new StyleBuilder())
            ->setFontBold()
            ->setFontSize(12)
            ->setFontColor(Color::BLACK)
            ->setBackgroundColor(Color::YELLOW)
            ->setCellAlignment(CellAlignment::CENTER)
            ->build();

        // Crear fila encabezado con estilo
        $cells = [];
        foreach ($encabezado as $titulo) {
            $cells[] = WriterEntityFactory::createCell($titulo);
        }
        $writer->addRow(WriterEntityFactory::createRow($cells, $estilo));

        // Agregar datos filas filtradas según encabezados
        foreach ($datos as $dato) {
            $fila_array = (array) $dato;
            $fila_filtrada = [];

            foreach ($encabezado as $tituloEncabezado) {
                $claveDato = $mapaClaveDato[$tituloEncabezado] ?? null;
                $fila_filtrada[] = $claveDato ? ($fila_array[$claveDato] ?? '') : '';
            }

            $writer->addRow(WriterEntityFactory::createRowFromArray($fila_filtrada));
        }

        $writer->close();
        exit();
    }

    public function control_acceso_reporte($parametros)
    {
        $txt_fecha_inicio = $parametros['txt_fecha_inicio'] ?? '';
        $txt_fecha_fin = $parametros['txt_fecha_fin'] ?? '';
        $ddl_departamentos = $parametros['ddl_departamentos'] ?? '';

        // Obtener datos del modelo usando BaseModel
        $datos = $this->modelo->listar_asistencia_por_fecha_departamento($txt_fecha_inicio, $txt_fecha_fin, $ddl_departamentos);

        $filas_datos = [];

        foreach ($datos as $dato) {
            $fila_datos = [
                'APELLIDOS' => $dato['apellidos'],
                'NOMBRES' => $dato['nombres'],
                'Empleado' => $dato['empleado'],
                'Cedula' => $dato['cedula'],
                'Correo Institucional' => $dato['correo_institucional'],
                'Departamento' => $dato['departamento'],
                'Dia' => $dato['dia'],
                'Fecha' => $dato['fecha'],
                'Horario Contrato' => $dato['horario_contrato'],
                'Turno' => $dato['turno_nombre'],
                'Entrada Inicio Turno' => $dato['entrada_hora_inicio_turno'],
                'Entrada Fin Turno' => $dato['entrada_hora_fin_turno'],
                'RegEntrada' => $dato['regentrada'],
                'Hora Entrada' => $dato['hora_entrada'],
                'Hora Ajustada' => $dato['hora_ajustada'],
                'Atrasos' => $dato['atrasos'],
                'Ausente' => $dato['ausente'],
                'Salida Inicio Turno' => $dato['salida_hora_inicio_turno'],
                'Salida Fin Turno' => $dato['salida_hora_fin_turno'],
                'RegSalida' => $dato['regsalida'],
                'Hora Salida' => $dato['hora_salida'],
                'Salidas Temprano' => $dato['salidas_temprano'],
                'Dias Trabajados' => $dato['dias_trabajados'],
                'Cumplimiento de jornada (8 horas)' => $dato['cumple_jornada'],
                'Horas faltantes por cumplir jornada' => $dato['horas_faltantes'],
                'Horas excedentes' => $dato['horas_excedentes'],
                'SalidasTemprano' => $dato['salidas_temprano'],
                'Suplem 25%' => $dato['horas_suplementarias'],
                'Extra 100%' => $dato['horas_extraordinarias'],
                'id_marcacion' => $dato['_id'],
            ];

            $filas_datos[] = $fila_datos;
        }

        return $filas_datos;
    }

    function mostrar_informacion_marcacion($id_marcacion)
    {
        $datos = $this->modelo->where('th_asi_id', $id_marcacion)->listar(1);

        if (empty($datos)) {
            return "No se encontró información para la marcación ID $id_marcacion.";
        }

        $d = $datos[0]; // Primer registro

        $texto  = "<br><strong>Información adicional: </strong><br>";
        $texto .= "Apellidos: {$d['apellidos']}<br>";
        $texto .= "Nombres: {$d['nombres']}<br>";
        $texto .= "Empleado: {$d['empleado']}<br>";
        $texto .= "Cédula: {$d['cedula']}<br>";
        $texto .= "Correo: {$d['correo_institucional']}<br><br>";

        $texto .= "Fecha inicio programación: {$d['fecha_inicio_programacion']}<br>";
        $texto .= "Fecha fin programación: {$d['fecha_fin_programacion']}<br>";
        $texto .= "Departamento: {$d['departamento']}<br>";
        $texto .= "Prioridad: {$d['prioridad_programacion']}<br>";
        $texto .= "Horario: {$d['horario_contrato']}<br>";
        $texto .= "Turno: {$d['turno_nombre']}<br>";

        $texto .= "<br><strong>Datos del turno entrada: </strong><br>";
        $texto .= "Hora inicio: {$d['entrada_hora_inicio_turno']}<br>";
        $texto .= "Hora fin: {$d['entrada_hora_fin_turno']}<br>";
        $texto .= "Hora marcación: {$d['regentrada']}<br>";
        $texto .= "Hora entrada: {$d['hora_entrada']}<br>";
        $texto .= "Hora ajustada: {$d['hora_ajustada']}<br>";
        $texto .= "Atrasado: {$d['atrasos']}<br>";
        $texto .= "Ausente: {$d['ausente']}<br>";

        $texto .= "<br><strong>Datos del turno salida: </strong><br>";
        $texto .= "Hora inicio: {$d['salida_hora_inicio_turno']}<br>";
        $texto .= "Hora fin: {$d['salida_hora_fin_turno']}<br>";
        $texto .= "Hora marcación salida: {$d['regsalida']}<br>";
        $texto .= "Hora marcación salida str: {$d['salida_marcacion_str']}<br>";
        $texto .= "Hora salida: {$d['hora_salida']}<br>";
        $texto .= "Salida antes de hora: {$d['salidas_temprano']}<br>";
        $texto .= "Ausente salida: {$d['salida_ausente']}<br>";

        $texto .= "<br><strong>Datos de descanso: </strong><br>";
        $texto .= "Hora inicio descanso: {$d['descanso_inicio']}<br>";
        $texto .= "Hora fin descanso: {$d['descanso_fin']}<br>";
        $texto .= "Minutos descanso simple: {$d['minutos_descanso_simple']}<br>";
        $texto .= "Descanso formal: {$d['usa_descanso_formal']}<br>";
        $texto .= "Descanso simple: {$d['descanso_simple']}<br>";
        $texto .= "Minutos descanso calculado: {$d['minutos_descanso_calculado']}<br>";

        $texto .= "<br><strong>Resultado de Justificación:</strong><br>";
        $texto .= "Día justificado: {$d['dia_justificado']}<br>";
        $texto .= "Motivo: {$d['motivo_justificacion']}<br>";
        $texto .= "Fecha Inicio: {$d['inicio_justificacion']}<br>";
        $texto .= "Fecha Fin: {$d['fin_justificacion']}<br>";
        $texto .= "Horas Justificadas: {$d['horas_justificadas']}<br>";
        $texto .= "Es Rango: {$d['justificacion_es_rango']}<br>";
        $texto .= "Asignado a: {$d['justificacion_asignado_a']}<br>";

        $texto .= "<br><strong>Horas de trabajo: </strong><br>";
        $texto .= "Tiempo entrada-salida: {$d['tiempo_entrada_salida']}<br>";
        $texto .= "Post descanso: {$d['tiempo_post_descanso']}<br>";
        $texto .= "Post justificación: {$d['tiempo_post_justificacion']}<br>";
        $texto .= "Horas trabajadas finales: {$d['horas_trabajadas_finales']}<br>";
        $texto .= "Horas excedentes: {$d['horas_excedentes']}<br>";
        $texto .= "Horas que debe trabajar: {$d['horas_trabajo_hora']}<br>";
        $texto .= "Horas faltantes: {$d['horas_faltantes']}<br>";
        $texto .= "Cumple jornada mínima: {$d['cumple_jornada']}<br>";
        $texto .= "Tipo justificación aplicada: {$d['tipo_justificacion_aplicada']}<br>";
        $texto .= "Minutos justificados calculado: {$d['minutos_justificados_calculado']}<br>";
        $texto .= "Es feriado: {$d['es_feriado']}<br>";
        $texto .= "Trabajó en feriado: {$d['trabajo_en_feriado']}<br>";
        $texto .= "Trabajó con justificación: {$d['trabajo_con_justificacion']}<br>";

        $texto .= "<br><strong>Horas Extra: </strong><br>";
        $texto .= "Calcula horas extra: {$d['calcula_horas_extra']}<br>";
        $texto .= "Horas suplementarias: {$d['horas_suplementarias']}<br>";
        $texto .= "Horas extraordinarias: {$d['horas_extraordinarias']}<br>";
        $texto .= "Rango suplementarias: {$d['rango_suplementarias']}<br>";
        $texto .= "Rango extras: {$d['rango_extras']}<br>";

        return nl2br($texto); // para que los \n se vean como saltos en HTML
    }
}
