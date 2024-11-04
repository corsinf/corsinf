<?php
require_once(dirname(__DIR__, 2) . '/modelo/PASANTES/asistencias_pasantesM.php');
require_once(dirname(__DIR__, 2) . '/lib/pdf/fpdf.php');


$controlador = new asistencias_pasantesC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar($_POST['id'] ?? '', $_POST['modal'] ?? ''));
}

if (isset($_GET['insertar'])) {
    echo json_encode($controlador->insertar_editar($_POST['parametros']));
}

if (isset($_GET['eliminar'])) {
    echo json_encode($controlador->eliminar($_POST['id']));
}

if (isset($_GET['editar'])) {
    echo json_encode($controlador->editar($_POST['parametros']));
}

if (isset($_GET['editar_tutor'])) {
    echo json_encode($controlador->editar_tutor($_POST['parametros']));
}

if (isset($_GET['pdf_pasante_actividad'])) {
    echo ($controlador->pdf_pasante_actividad($_GET['id']));
}





class asistencias_pasantesC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new asistencias_pasantesM();
    }

    function listar($id = '', $modal = 0)
    {
        if ($modal == 1) {
            //print_r($id); exit();
            return $datos = $this->modelo->where('pas_id', $id)->listar();
        }

        if ($id == '') {
            if ($_SESSION['INICIO']['ID_USUARIO'] == 1) {
                $datos = $this->modelo->listar();
            } else {
                $datos = $this->modelo->where('pas_usu_id', $_SESSION['INICIO']['ID_USUARIO'])->listar();
            }
        } else {
            $datos = $this->modelo->where('pas_usu_id', $_SESSION['INICIO']['ID_USUARIO'])->where('pas_id', $id)->listar();
        }
        return $datos;
    }

    function insertar_editar($parametros)
    {
        //print_r($parametros);exit;
        $txt_obs_tutor = isset($parametros['txt_obs_tutor']) ? $parametros['txt_obs_tutor'] : '';

        $datos = array(
            array('campo' => 'pas_usu_id', 'dato' => ($_SESSION['INICIO']['ID_USUARIO'])),
            array('campo' => 'pas_nombre', 'dato' => ($_SESSION['INICIO']['USUARIO'])),
            //array('campo' => 'pas_hora_llegada', 'dato' => ($hora_del_sistema)),
            array('campo' => 'pas_observacion_pasante', 'dato' => $parametros['txt_obs_pasantes']),
            array('campo' => 'pas_observacion_tutor', 'dato' => $txt_obs_tutor),
            // array('campo' => 'pas_usu_id_tutor', 'dato' => $parametros['txt_cedula']),
            // array('campo' => 'pas_tutor_estado', 'dato' => $parametros['ddl_sexo']),
        );

        $datos = $this->modelo->insertar($datos);
        return $datos;
    }

    function editar($parametros)
    {
        date_default_timezone_set('America/Bogota');
        //tomar la hora del sistema

        $hora_del_sistema = new DateTime();
        $hora_del_sistema = $hora_del_sistema->format('Y-d-m H:i:s');

        //print_r($hora_del_sistema); exit();

        $txt_obs_tutor = isset($parametros['txt_obs_tutor']) ? $parametros['txt_obs_tutor'] : '.';

        $datos = array(
            array('campo' => 'pas_observacion_pasante', 'dato' => $parametros['txt_obs_pasantes']),
            array('campo' => 'pas_observacion_tutor', 'dato' => $txt_obs_tutor),
            array('campo' => 'pas_hora_salida', 'dato' => ($hora_del_sistema)),
            // array('campo' => 'pas_tutor_estado', 'dato' => $parametros['ddl_sexo']),
        );



        $where[0]['campo'] = 'pas_id';
        $where[0]['dato'] = $parametros['registro_id'];
        $datos = $this->modelo->editar($datos, $where);

        ////////////////////////////////////////////////
        //Para calcular total de horas
        //LLamar el registro 
        $datos = $this->modelo->where('pas_id', $parametros['registro_id'])->listar();

        $pas_hora_llegada = $datos[0]['pas_hora_llegada'];
        $pas_hora_salida = $datos[0]['pas_hora_salida'];

        $pas_hora_llegada = new DateTime($pas_hora_llegada);
        $pas_hora_salida = new DateTime($pas_hora_salida);

        // Calcular la diferencia
        $diferencia = $pas_hora_salida->diff($pas_hora_llegada);

        $horas_totales = $diferencia->h + ($diferencia->i / 60);

        $calcular_total = number_format($horas_totales, 2);

        $datos = array(
            array('campo' => 'pas_horas_total', 'dato' => $calcular_total),
        );

        $where[0]['campo'] = 'pas_id';
        $where[0]['dato'] = $parametros['registro_id'];
        $datos = $this->modelo->editar($datos, $where);

        return $datos;

        //return $parametros;
    }

    function editar_tutor($parametros)
    {
        $txt_obs_tutor = isset($parametros['txt_obs_tutor']) ? $parametros['txt_obs_tutor'] : '.';

        $datos = array(
            array('campo' => 'pas_observacion_tutor', 'dato' => $txt_obs_tutor),
            array('campo' => 'pas_tutor_estado', 'dato' => 1),
        );

        $where[0]['campo'] = 'pas_id';
        $where[0]['dato'] = $parametros['txt_id_registro'];
        $datos = $this->modelo->editar($datos, $where);

        return $datos;
    }

    function eliminar($id)
    {
        $datos = array(
            array('campo' => 'pac_estado', 'dato' => 0),
        );

        $where[0]['campo'] = 'pac_id';
        $where[0]['dato'] = $id;

        $datos = $this->modelo->editar($datos, $where);
        return $datos;
    }

    function pdf_pasante_actividad($id = '')
    {
        $HEADER_LOGO1 = 'logo.png';
        $HEADER_LOGO2 = 'logo2.png';
        $FOOTER_LOGO3 = 'jesuita.png';
        $FOOTER_LOGO4 = 'redes.png';
        $HEADER_FONT = 'Arial';
        $FOOTER_FONT = 'Arial';

        // Crear objeto PDF
        $pdf = new FPDF();
        $pdf->SetMargins(18, 30, 15);
        $pdf->SetAutoPageBreak(true, 15);
        $pdf->AddPage();

        function Header_1($pdf, $HEADER_FONT)
        {
            //$pdf->SetFont($HEADER_FONT, 'B', 12);
            //$pdf->Image($HEADER_FONT, 20, 8, 70);
            //$pdf->Image($HEADER_FONT, 110, 8, 60);
            $pdf->Ln(10);
        }

        function crear_tabla($pdf, $data)
        {
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->SetX(($pdf->GetPageWidth() - 175) / 2);
            $pdf->Cell(160, 5, 'Formato registro de horas de las Practicas Pre-profesionales', 0, 1, 'C');
            $pdf->Ln(4);

            foreach ($data as $row) {
                $pdf->Cell(82.5, 8, $row[0], 1, 0, 'L');
                $pdf->Cell(82.5, 8, $row[1], 1, 0, 'C');
                $pdf->Ln();
            }
        }

        function CreateActivityTable($pdf, $rowCount)
        {
            $pdf->SetX(($pdf->GetPageWidth() - 175) / 2);
            $pdf->SetFont('Times', 'B', 11);
            $pdf->Cell(36.2, 9, 'Fecha:dd/mm/aaaa', 1);
            $pdf->Cell(56.7, 9, 'Actividades/Fases', 1);
            $pdf->Cell(36.2, 9, 'Horas', 1);
            $pdf->Cell(36.2, 9, 'Firma', 1);
            $pdf->Ln();

            for ($i = 1; $i <= $rowCount; $i++) {
                if ($i % 20 == 0 && $i > 0) {
                    $pdf->AddPage();
                    //$pdf->CreateActivityTableHeader($pdf);
                }
                $pdf->SetX(($pdf->GetPageWidth() - 175) / 2);
                $pdf->Cell(36.2, 8, 'Fila ' . $i . ' - 1', 1);
                $pdf->Cell(56.7, 8, 'Fila ' . $i . ' - 2', 1);
                $pdf->Cell(36.2, 8, 'Fila ' . $i . ' - 3', 1);
                $pdf->Cell(36.2, 8, 'Fila ' . $i . ' - 4', 1);
                $pdf->Ln();
            }
        }

        function CreateActivityTableHeader($pdf)
        {
            $pdf->SetX(($pdf->GetPageWidth() - 175) / 2);
            $pdf->SetFont('Times', 'B', 11);
            $pdf->Cell(36.2, 9, 'Fecha:dd/mm/aaaa', 1);
            $pdf->Cell(56.7, 9, 'Actividades/Fases', 1);
            $pdf->Cell(36.2, 9, 'Horas', 1);
            $pdf->Cell(36.2, 9, 'Firma', 1);
            $pdf->Ln();
        }

        function AddTotalHours($pdf)
        {
            $pdf->Ln(5);
            $pdf->Cell(0, 10, "Total de horas: 240 Horas minimo", 0, 1, 'C');
            $pdf->Ln();
            $pdf->Cell(0, 10, "Firma Tutor Institucion/Empresa __________________________ ");
        }

        // Datos para la tabla
        $data = [
            ['Nombre del Estudiante:', ''],
            ['Codigo Banner del Estudiante:', ''],
            ['Carrera:', ''],
            ['Nombre de la Institucion / Empresa:', ''],
            ['Nombre del Tutor Institucion / Empresa:', ''],
            ['Nombre del Tutor PPP / PUCE:', '']
        ];


        //Crear tablas
        crear_tabla($pdf, $data);
        $pdf->Ln(10);
        CreateActivityTable($pdf, 45);
        AddTotalHours($pdf);

        // Verificar si necesitamos página adicional para el pie de página
        if ($pdf->GetY() + 30 > 276) {
            $pdf->AddPage();
        }

        // Salida del PDF
        $pdf->Output();
    }
}
