<?php

date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 2) . '/modelo/TALENTO_HUMANO/th_control_acceso_calculosM.php');
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
    echo ($controlador->descargar_excel());
}


class th_control_acceso_calculosC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new th_control_acceso_calculosM();
    }

    //Usa para el boton de descargar Excel
    function descargar_excel($nombreArchivo = 'example.xlsx', $datos = '')
    {
        //////////////////////////////////////////////////////////
        //aqui modificar de acuerdo a  las necesidades
        ////////////////////////////////////////////////////////////
        $datos = $this->modelo->listar();

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

    function control_acceso_reporte($parametros)
    {
        $txt_fecha_inicio = $parametros['txt_fecha_inicio'] ?? '';
        $txt_fecha_fin = $parametros['txt_fecha_fin'] ?? '';
        $ddl_departamentos = $parametros['ddl_departamentos'] ?? '';


        //////////////////////////////////////////////////////////
        //aqui modificar de acuerdo a  las necesidades
        ////////////////////////////////////////////////////////////

        //Antes
        // $datos = $this->modelo->control_acceso_departamento($txt_fecha_inicio, $txt_fecha_fin, $ddl_departamentos);

        //como deberia estar

        $datos = $this->modelo->listar($txt_fecha_inicio, $txt_fecha_fin, $ddl_departamentos); // y las fechas con where

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
}
