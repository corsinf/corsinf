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
    // El exit está dentro de la función, pero puedes poner otro aquí si quieres
    exit();
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

            ];

            $filas_datos[] = $fila_datos;
        }

        return $filas_datos;
    }
}
