<?php
date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 2) . '/modelo/TALENTO_HUMANO/th_reportes_hvM.php');

require_once(dirname(__DIR__, 2) . '/lib/spout_excel/vendor/box/spout/src/Spout/Autoloader/autoload.php');


use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Box\Spout\Common\Entity\Row;
use Box\Spout\Common\Type;
use Box\Spout\Writer\WriterInterface;
use Box\Spout\Writer\Common\Creator\Style\StyleBuilder;
use Box\Spout\Common\Entity\Style\CellAlignment;
use Box\Spout\Common\Entity\Style\Color;

$controlador = new th_reportes_hvC();

if (isset($_GET['descargarExcel'])) {
    echo ($controlador->descargarExcel());
}

if (isset($_GET['con'])) {
    echo ($controlador->con());
}


class th_reportes_hvC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new th_reportes_hvM();
    }

    function con()
    {
        //Para traer los datos de la tabla Prueba
        $datos = $this->modelo->conectar_saint();

        // foreach ($con as $dato) {
        //     echo

        //     'APELLIDOS' . $dato['APELLIDOS'] . "</br>" .
        //         'NOMBRES' . $dato['NOMBRES'] . "</br>" .
        //         'Empleado' . $dato['EMPLEADO']. "</br>" .
        //         'Cedula' . '000000000' . "</br>" .
        //         'Correo Institucional' . 'ejemplo@ejemplo.com' . "</br>" .
        //         'Departamento' . $dato['DEPARTAMENTO'] . "</br>" .
        //         'Dia' . $dato['FECHA'] . "</br>" .
        //         'Fecha' . $dato['FECHA'] . "</br>" .
        //         'Dias Trabajados' . 'calcular' . "</br>" .
        //         'Horario Contrato' . 'calcular' . "</br>" .
        //         'Hora Entrada' . 'horario' . "</br>" .
        //         'Hora Salida' . 'horario' . "</br>" .
        //         'RegEntrada' . $dato['FECHA'] . "</br>" .
        //         'RegSalida' . 'calcular' . "</br>" .
        //         'cumplimiento de jornada (8 horas)' . 'si tiene registro b' . "</br>" .
        //         'horas faltantes por cumplir jornada' . 'suma de horas' . "</br>" .
        //         'horas excedentes' . 'pasado la hora h' . "</br>" .
        //         'SalidasTemprano' . 'antes de la hora h' . "</br>" .
        //         'Atrasos' . 'calculo' . "</br>" .
        //         'Ausente' . 'si no tiene registro b' . "</br>" .
        //         'Suplem 25%' . 'no se de que es' . "</br>" .
        //         'Extra 100%' . 'sabados o domingos' . "</br></br>";
        // }

        $horarios = array(
            array('Departamento' => 'Docentes', 'hora_entrada' => '08:00', 'hora_salida' => '15:30'),
            array('Departamento' => 'Dece', 'hora_entrada' => '06:00', 'hora_salida' => '15:30'),
            array('Departamento' => 'Servicios Generales', 'hora_entrada' => '09:00', 'hora_salida' => '15:30'),
            array('Departamento' => 'Administrativos', 'hora_entrada' => '10:00', 'hora_salida' => '15:30'),
            array('Departamento' => 'TI', 'hora_entrada' => '11:00', 'hora_salida' => '15:30'),
            array('Departamento' => 'Salud', 'hora_entrada' => '12:00', 'hora_salida' => '15:30'),
        );




        // Procesar cada fila de datos y agregarla a la hoja de cálculo
        foreach ($datos as $dato) {

            $hora_entrada = '00:00';
            $hora_salida = '00:00';
            $hora_contrato = '';


            foreach ($horarios as $horario) {
                // Verificar si el departamento coincide
                if ($dato['DEPARTAMENTO'] == $horario['Departamento']) {
                    // Asignar las horas de inicio y fin
                    $hora_entrada = $horario['hora_entrada'];
                    $hora_salida = $horario['hora_salida'];
                    $hora_contrato = $hora_entrada . ' - ' . $hora_salida;

                    $salida = ($this->calcular_jornada($hora_entrada, $hora_salida, $dato));

                    //Mostrar resultados
                    echo "Departamento: " . $dato['DEPARTAMENTO'] . "</br>";
                    echo "Hora Entrada: " . $hora_entrada . "</br>";
                    echo "Hora Salida: " . $hora_salida . "</br>";
                    echo "Hora Entrada Registro: " . $dato['Hora_Entrada'] . "</br>";
                    echo "Hora Salida Registro: " . $dato['Hora_Salida'] . "</br>";
                    echo "Cumplimiento de Jornada (8 horas): " . $salida['cumplimiento_jornada'] . "</br>";
                    echo "Horas Faltantes: " . $salida['horas_faltantes'] . "</br>";

                    echo "duracion_programada: " . $salida['duracion_programada'] . "</br>";
                    echo "duracion_trabajada: " . $salida['duracion_trabajada'] . "</br>";

                    echo "horas_faltantes_format: " . $salida['horas_faltantes_format'] . "</br>";
                    echo "horas_excedentes: " . $salida['horas_excedentes'] . "</br>";
                    echo "atrasos: " . $salida['atrasos'] . "</br>";
                    echo "-------------------------</br>";

                    break; // Salir del bucle ya que hemos encontrado el departamento
                }
            }
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

    function descargarExcel($nombreArchivo = 'example.xlsx', $datos = '')
    {
        $datos = $this->modelo->conectar_saint();

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
            'cumplimiento de jornada (8 horas)',
            'horas faltantes por cumplir jornada',
            'horas excedentes',
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

        $hora_entrada = '00:00';
        $hora_salida = '00:00';
        $hora_contrato = '';

        // Procesar cada fila de datos y agregarla a la hoja de cálculo
        foreach ($datos as $dato) {

            //Para calcular las jornadas en base a los horarios
            foreach ($horarios as $horario) {
                // Verificar si el departamento coincide
                if ($dato['DEPARTAMENTO'] == $horario['Departamento']) {
                    // Asignar las horas de inicio y fin
                    $hora_entrada = $horario['hora_entrada'];
                    $hora_salida = $horario['hora_salida'];
                    $hora_contrato = $hora_entrada . ' - ' .  $hora_salida;

                    $salida = ($this->calcular_jornada($hora_entrada, $hora_salida, $dato));

                    break; // Salir del bucle ya que hemos encontrado el departamento
                }
            }

            $extra_100 = '';
            if ($this->calcular_dia($dato['FECHA']) == 'Sábado' || $this->calcular_dia($dato['FECHA']) == 'Domingo') {
                $extra_100 = $salida['duracion_programada'];
            }


            // Crear una fila de datos en el mismo orden que los encabezados
            $filas_datos = [
                $dato['APELLIDOS'], // APELLIDOS
                $dato['NOMBRES'], // NOMBRES
                $dato['EMPLEADO'], // EMPLEADO
                '000000000', // Cedula
                'ejemplo@ejemplo.com', // Correo Institucional
                $dato['DEPARTAMENTO'], // Departamento
                $this->calcular_dia($dato['FECHA']), // Dia
                $dato['FECHA'], // Fecha
                '1', // Dias Trabajados
                $hora_contrato, // Horario Contrato
                $hora_entrada, // Hora Entrada
                $hora_salida, // Hora Salida
                $dato['Hora_Entrada'], // RegEntrada
                $dato['Hora_Salida'], // RegSalida
                $salida['cumplimiento_jornada'], // Cumplimiento de jornada (8 horas)
                $salida['horas_faltantes_format'], // Horas faltantes por cumplir jornada
                $salida['horas_excedentes'], // Horas excedentes
                $salida['salida_temprano'], // Salidas Temprano
                $salida['atrasos'], // Atrasos
                '0', // Ausente 
                $salida['sumplementaria'], // Suplem 25%
                $extra_100, // Extra 100%
                $dato['_id'] // Extra 100%
            ];

            // Crear y agregar la fila de datos
            $fila_datos = WriterEntityFactory::createRowFromArray($filas_datos);
            $writer->addRow($fila_datos);
        }

        // Cerrar el writer
        $writer->close();
    }

    /**
     * 
     * Funciones para descargar el excel
     * 
     */

    function calcular_jornada($hora_entrada, $hora_salida, $dato)
    {
        $atrasos = '';
        $horas_excedentes = '';
        $horas_faltantes_format = '';

        // Convertir horas a minutos para cálculo
        $hora_entrada_min = intval(substr($hora_entrada, 0, 2)) * 60 + intval(substr($hora_entrada, 3, 2));
        $hora_salida_min = intval(substr($hora_salida, 0, 2)) * 60 + intval(substr($hora_salida, 3, 2));

        // Calcular duración de la jornada laboral programada
        $duracion_programada = $hora_salida_min - $hora_entrada_min;

        // Convertir horas de entrada y salida de datos a minutos
        $reg_entrada_min = intval(substr($dato['Hora_Entrada'], 0, 2)) * 60 + intval(substr($dato['Hora_Entrada'], 3, 2));
        $reg_salida_min = intval(substr($dato['Hora_Salida'], 0, 2)) * 60 + intval(substr($dato['Hora_Salida'], 3, 2));

        // Calcular duración real de trabajo
        $duracion_trabajada = $reg_salida_min - $reg_entrada_min;

        // Validación de cumplimiento de jornada
        $cumplimiento_jornada = ($duracion_trabajada >= $duracion_programada) ? 1 : 0;

        $horas_faltantes = ($duracion_programada - $duracion_trabajada);

        if ($horas_faltantes > 0) {
            $horas_faltantes_format = $this->minutos_a_horas($horas_faltantes);
        } else {
            $horas_excedentes = $this->minutos_a_horas($horas_faltantes * -1);
        }

        $atrasos_calc = $reg_entrada_min - $hora_entrada_min;

        if ($atrasos_calc > 0) {
            $atrasos = $this->minutos_a_horas($atrasos_calc);
        }

        $sumplementaria = ($horas_excedentes > 0) ? 1 : 0;

        $salida_temprano = ($horas_faltantes_format > 0) ? 1 : 0;

        $salida = array(
            "horas_faltantes_format" => $horas_faltantes_format,
            "horas_excedentes" => $horas_excedentes,
            "cumplimiento_jornada" => $cumplimiento_jornada,
            "atrasos" => $atrasos,
            "cumplimiento_jornada" => $cumplimiento_jornada,
            "horas_faltantes" => $horas_faltantes,

            "duracion_programada" => $this->minutos_a_horas($duracion_programada),
            "duracion_trabajada" => $duracion_trabajada,

            "sumplementaria" => $sumplementaria,
            "salida_temprano" => $salida_temprano,
        );

        return $salida;
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
}
