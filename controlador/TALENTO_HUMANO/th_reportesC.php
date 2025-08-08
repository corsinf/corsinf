<?php
date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 2) . '/modelo/TALENTO_HUMANO/th_reportesM.php');

require_once(dirname(__DIR__, 2) . '/lib/spout_excel/vendor/box/spout/src/Spout/Autoloader/autoload.php');



use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Box\Spout\Common\Entity\Row;
use Box\Spout\Common\Type;
use Box\Spout\Writer\WriterInterface;
use Box\Spout\Writer\Common\Creator\Style\StyleBuilder;
use Box\Spout\Common\Entity\Style\CellAlignment;
use Box\Spout\Common\Entity\Style\Color;

$controlador = new th_reportesC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar($_POST['id'] ?? ''));
}

if (isset($_GET['insertar'])) {
    echo json_encode($controlador->insertar_editar($_POST['parametros']));
}

if (isset($_GET['descargarExcel'])) {
    echo ($controlador->descargarExcel());
}

if (isset($_GET['reporte'])) {
    // echo json_encode($controlador->pruebas());
    echo json_encode($controlador->control_acceso_reporte($_POST['parametros'] ?? ''));
}

if (isset($_GET['eliminar'])) {
    echo json_encode($controlador->eliminar($_POST['id'] ?? ''));
}

if (isset($_GET['sincronizar_calculo_asistencia'])) {
    echo json_encode($controlador->sincronizar_calculo_asistencia());
}




class th_reportesC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new th_reportesM();
    }

    function listar($id)
    {
        if ($id == '') {
            $datos = $this->modelo->listar_reporte();
        } else {
            $datos = $this->modelo->listar_reporte($id);
        }

        return $datos;
    }

    function insertar_editar($parametros)
    {
        $datos = array(
            array('campo' => 'th_rep_nombre', 'dato' => $parametros['txt_nombre']),
            array('campo' => 'th_rep_descripcion', 'dato' => $parametros['txt_descripcion']),
            array('campo' => 'th_tip_rep_id', 'dato' => $parametros['ddl_tipo_reporte']),
        );

        if ($parametros['_id'] == '') {
            $datos = $this->modelo->insertar($datos);
        } else {
            $where[0]['campo'] = 'th_rep_id';
            $where[0]['dato'] = $parametros['_id'];
            $datos = $this->modelo->editar($datos, $where);
        }

        return $datos;
    }

    function eliminar($id)
    {
        $datos = array(
            array('campo' => 'th_rep_estado', 'dato' => 0),
        );

        $where = array(
            array('campo' => 'th_rep_id', 'dato' => $id),
        );

        $datos = $this->modelo->editar($datos, $where);
        return $datos;
    }

    function pruebas()
    {
        $datos = $this->modelo->control_acceso_departamento('2024-11-28', '2024-11-29', 5);
        return $datos;
    }

    function control_acceso_reporte($parametros)
    {
        $txt_fecha_inicio = $parametros['txt_fecha_inicio'] ?? '';
        $txt_fecha_fin = $parametros['txt_fecha_fin'] ?? '';
        $ddl_departamentos = $parametros['ddl_departamentos'] ?? '';

        $datos = $this->modelo->control_acceso_departamento($txt_fecha_inicio, $txt_fecha_fin, $ddl_departamentos);

        $filas_datos = []; // Array para almacenar todas las filas de datos

        foreach ($datos as $dato) {
            $salida = $this->calcular_jornada(
                $dato['hora_entrada'],
                $dato['hora_salida'],
                $dato['hora_entrada_acc'],
                $dato['hora_salida_acc']
            );

            $extra_100 = ($dato['dia_nombre'] == 'Sábado' || $dato['dia_nombre'] == 'Domingo')
                ? $salida['duracion_programada']
                : '';

            // Crear un array asociativo con clave-valor
            $fila_datos = [
                'APELLIDOS' => $dato['primer_apellido'] . ' ' . $dato['segundo_apellido'],
                'NOMBRES' => $dato['primer_nombre'] . ' ' . $dato['segundo_nombre'],
                'Empleado' => $dato['primer_apellido'] . ' ' . $dato['segundo_apellido'] . ' ' . $dato['primer_nombre'] . ' ' . $dato['segundo_nombre'],
                'Cedula' => $dato['cedula'],
                'Correo Institucional' => $dato['correo'],
                'Departamento' => $dato['nombre_departamento'],
                'Dia' => $dato['dia_nombre'],
                'Fecha' => $dato['fecha'],
                'Dias Trabajados' => '1',
                'Horario Contrato' => $this->minutos_a_horas($dato['hora_entrada']) . ' - ' . $this->minutos_a_horas($dato['hora_salida']),
                'Hora Entrada' => $this->minutos_a_horas($dato['hora_entrada']),
                'Hora Salida' => $this->minutos_a_horas($dato['hora_salida']),
                'RegEntrada' => $this->minutos_a_horas($dato['hora_entrada_acc']),
                'RegSalida' => $this->minutos_a_horas($dato['hora_salida_acc']),
                'Cumplimiento de jornada (8 horas)' => $salida['cumplimiento_jornada'],
                'Horas faltantes por cumplir jornada' => $salida['horas_faltantes_format'],
                'Horas excedentes' => $salida['horas_excedentes'],
                'SalidasTemprano' => $salida['salida_temprano'],
                'Atrasos' => $salida['atrasos'],
                'Ausente' => 'Ausente',
                'Suplem 25%' => $salida['sumplementaria'],
                'Extra 100%' => $extra_100,
            ];

            $filas_datos[] = $fila_datos; // Agregar fila al array principal
        }

        return $filas_datos; // Retornar el array con todas las filas
    }


    function descargarExcel($nombreArchivo = 'example.xlsx', $datos = '')
    {
        $datos = $this->modelo->control_acceso_departamento('2024-11-28', '2024-11-29', 5);

        // Crear el writer para el archivo Excel
        $writer = WriterEntityFactory::createXLSXWriter();

        // Preparar para la descarga en el navegador
        header('Content-Disposition: attachment; filename="' . $nombreArchivo . '"');
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

        // Abre el archivo en modo descarga en el navegador
        $writer->openToBrowser($nombreArchivo);

        // Crear un estilo para la cabecera
        $estilo = (new StyleBuilder())
            ->setFontBold()
            ->setFontSize(12)
            ->setFontColor(Color::BLACK)
            ->setBackgroundColor(Color::YELLOW)
            ->setCellAlignment(CellAlignment::CENTER)
            ->build();

        // Crear y agregar la fila de encabezado
        $encabezado = [
            'APELLIDOS',
            'NOMBRES',
            'Empleado',
            'Cedula',
            'Correo Institucional',
            'Departamento',
            'Dia',
            'Fecha',
            'Dias Trabajados',
            'Horario Contrato',
            'Hora Entrada',
            'Hora Salida',
            'RegEntrada',
            'RegSalida',
            'Cumplimiento de jornada (8 horas)',
            'Horas faltantes por cumplir jornada',
            'Horas excedentes',
            'SalidasTemprano',
            'Atrasos',
            'Ausente',
            'Suplem 25%',
            'Extra 100%',

        ];

        $fila_encabezado = WriterEntityFactory::createRowFromArray($encabezado, $estilo);
        $writer->addRow($fila_encabezado);

        $horarios = array(
            array('Departamento' => 'Docentes', 'hora_entrada' => '07:00', 'hora_salida' => '15:30'),
            array('Departamento' => 'Dece', 'hora_entrada' => '07:00', 'hora_salida' => '15:30'),
            array('Departamento' => 'Servicios Generales', 'hora_entrada' => '07:00', 'hora_salida' => '15:30'),
            array('Departamento' => 'Administrativos', 'hora_entrada' => '07:00', 'hora_salida' => '15:30'),
            array('Departamento' => 'TI', 'hora_entrada' => '07:00', 'hora_salida' => '15:30'),
            array('Departamento' => 'Salud', 'hora_entrada' => '07:00', 'hora_salida' => '15:30'),
        );

        $hora_entrada = '0';
        $hora_salida = '0';
        $hora_entrada_acc = '0';
        $hora_salida_acc = '0';

        // Procesar cada fila de datos y agregarla a la hoja de cálculo
        foreach ($datos as $dato) {

            $hora_entrada = $dato['hora_entrada'];
            $hora_salida = $dato['hora_salida'];
            $hora_entrada_acc = $dato['hora_entrada_acc'];
            $hora_salida_acc = $dato['hora_salida_acc'];

            $salida = ($this->calcular_jornada($hora_entrada, $hora_salida, $hora_entrada_acc, $hora_salida_acc));

            $extra_100 = '';
            if (($dato['dia_nombre']) == 'Sábado' || ($dato['dia_nombre']) == 'Domingo') {
                $extra_100 = $salida['duracion_programada'];
            }

            // Crear una fila de datos en el mismo orden que los encabezados
            $filas_datos = [
                $dato['primer_apellido'] . ' ' . $dato['segundo_apellido'], // APELLIDOS
                $dato['primer_nombre'] . ' ' . $dato['segundo_nombre'], // NOMBRES
                $dato['primer_apellido'] . ' ' . $dato['segundo_apellido'] . ' ' . $dato['primer_nombre'] . ' ' . $dato['segundo_nombre'], // EMPLEADO
                $dato['cedula'], // Cedula
                $dato['correo'], // Correo Institucional
                $dato['nombre_departamento'], // Departamento
                $dato['dia_nombre'], // Dia
                $dato['fecha'], // Fecha
                '1', // Dias Trabajados
                $this->minutos_a_horas($dato['hora_entrada']) . ' - ' . $this->minutos_a_horas($dato['hora_salida']), // Horario Contrato
                $this->minutos_a_horas($dato['hora_entrada']), // Hora Entrada
                $this->minutos_a_horas($dato['hora_salida']), // Hora Salida
                $this->minutos_a_horas($dato['hora_entrada_acc']), // RegEntrada
                $this->minutos_a_horas($dato['hora_salida_acc']), // RegSalida
                $salida['cumplimiento_jornada'], // Cumplimiento de jornada (8 horas)
                $salida['horas_faltantes_format'], // Horas faltantes por cumplir jornada
                $salida['horas_excedentes'], // Horas excedentes
                $salida['salida_temprano'], // Salidas Temprano
                $salida['atrasos'], // Atrasos
                'Ausente',
                $salida['sumplementaria'], // Suplem 25%
                $extra_100, // Extra 100%
            ];

            // Crear y agregar la fila de datos
            $fila_datos = WriterEntityFactory::createRowFromArray($filas_datos);
            $writer->addRow($fila_datos);
        }

        // Cerrar el writer
        $writer->close();
    }

    function sincronizar_calculo_asistencia()
    {
        require_once(dirname(__DIR__, 2) . '/variables_entorno.php');

        $BASEDATO = $_SESSION['INICIO']['BASEDATO'];

        $ruta = dirname(__DIR__, 2) . '/Cron/TALENTO_HUMANO/DB/' . $BASEDATO . '.php';

        // 1) Usa el PHP CLI explícito (ajusta a tu instalación)
        $phpCli = ENV_PHP_81;

        $msg = [];

        // Validaciones
        if (!file_exists($phpCli)) {
            return ['msj' => "No se encontró PHP CLI en: $phpCli"];
        }
        if (!file_exists($ruta)) {
            return ['msj' => "No existe el script: $ruta"];
        }

        // 2) Escapar rutas con espacios
        $phpCliEsc = escapeshellarg($phpCli);
        $rutaEsc = escapeshellarg($ruta);

        // 3) Ejecutar y capturar salida + código retorno
        $salida = [];
        $code = 0;
        exec("$phpCliEsc $rutaEsc 2>&1", $salida, $code);

        if ($code === 0) {
            $msg[] = "Script ejecutado correctamente.";
        } else {
            $msg[] = "Error al ejecutar el script (código: $code).";
        }

        if (!empty($salida)) {
            $msg[] = "Salida:";
            $msg[] = implode("\n", $salida);
        }

        return ['msj' => implode("\n", $msg)];
    }


    /**
     * 
     * Funciones para descargar el excel
     * 
     */


    /**
     * Calcula datos relacionados con la jornada laboral y asistencia de una persona.
     *
     * @param int $hora_entrada Hora de inicio de la jornada laboral (en minutos desde las 00:00).
     * @param int $hora_salida Hora de finalización de la jornada laboral (en minutos desde las 00:00).
     * @param int $hora_entrada_acc Hora registrada de entrada de la persona (en minutos desde las 00:00).
     * @param int $hora_salida_acc Hora registrada de salida de la persona (en minutos desde las 00:00).
     *
     * @return array Cálculos relacionados con atrasos, ausencias, y horas extras.
     */
    function calcular_jornada($hora_entrada, $hora_salida, $hora_entrada_acc, $hora_salida_acc)
    {
        // Calcular duración de la jornada laboral programada y trabajada
        $duracion_programada = $hora_salida - $hora_entrada;
        $duracion_trabajada = $hora_salida_acc - $hora_entrada_acc;

        // Cálculo de cumplimiento de la jornada
        $cumplimiento_jornada = ($duracion_trabajada >= $duracion_programada) ? 1 : 0;

        // Calcular horas faltantes o excedentes
        $horas_faltantes = max(0, $duracion_programada - $duracion_trabajada);
        $horas_excedentes = max(0, $duracion_trabajada - $duracion_programada);

        // Convertir a formato de horas
        $horas_faltantes_format = $this->minutos_a_horas($horas_faltantes);
        $horas_excedentes_format = $this->minutos_a_horas($horas_excedentes);

        // Cálculo de atrasos
        $atrasos_calc = max(0, $hora_entrada_acc - $hora_entrada);
        $atrasos_format = $this->minutos_a_horas($atrasos_calc);

        // Validaciones complementarias
        $sumplementaria = ($horas_excedentes > 0) ? 1 : 0;
        $salida_temprano = ($hora_salida > $hora_salida_acc) ? 1 : 0;


        // Preparar la salida
        return [
            "horas_faltantes_format" => $horas_faltantes_format,
            "horas_excedentes" => $horas_excedentes_format,
            "cumplimiento_jornada" => $cumplimiento_jornada,
            "atrasos" => $atrasos_format,
            "horas_faltantes" => $horas_faltantes,
            "duracion_programada" => $this->minutos_a_horas($duracion_programada),
            "duracion_trabajada" => $this->minutos_a_horas($duracion_trabajada),
            "sumplementaria" => $sumplementaria,
            "salida_temprano" => $salida_temprano,
        ];
    }


    function minutos_a_horas($minutos)
    {
        $horas = floor($minutos / 60); // Calcular las horas
        $minutos_restantes = $minutos % 60; // Calcular los minutos restantes
        return sprintf('%02d:%02d', $horas, $minutos_restantes); // Formato HH:MM
    }

    function calcular_dia($fecha)
    {
        $ingresar_fecha = strtotime($fecha);

        $dias_ingles = date('l', $ingresar_fecha);
        switch ($dias_ingles) {
            case "Monday":
                return "Lunes";
            case 'Tuesday':
                return "Martes";
            case 'Wednesday':
                return "Miércoles";
            case 'Thursday':
                return "Jueves";
            case 'Friday':
                return "Viernes";
            case 'Saturday':
                return "Sábado";
            case 'Sunday':
                return "Domingo";
        }
    }

    function generarExcel($nombreArchivo = 'example.xlsx', $datos = '')
    {

        $datos = [
            ['Juan', 'Pérez', '28'],
            ['María', 'Gómez', '32'],
            ['Carlos', 'Rodríguez', '45']
        ];

        // Crear el writer para el archivo Excel
        $writer = WriterEntityFactory::createXLSXWriter();
        $writer->openToFile($nombreArchivo);

        // Agregar los datos
        foreach ($datos as $dato) {
            $row = WriterEntityFactory::createRowFromArray($dato);
            $writer->addRow($row);
        }

        // Cerrar el writer
        $writer->close();
        echo "Archivo Excel generado: $nombreArchivo";
    }
}
