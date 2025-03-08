<?php
require_once(dirname(__DIR__, 2) . '/modelo/SALUD_INTEGRAL/ficha_MedicaM.php');
require_once(dirname(__DIR__, 2) . '/modelo/ACTIVOS_FIJOS/contratosM.php');
include_once(dirname(__DIR__, 2) . '/db/codigos_globales.php');

include_once(dirname(__DIR__, 2) . '/lib/pdf/fpdf.php');
require_once(dirname(__DIR__, 2) . '/modelo/SALUD_INTEGRAL/pacientesM.php');

require_once(dirname(__DIR__, 2) . '/modelo/SALUD_INTEGRAL/detalle_fm_med_insM.php');

$controlador = new ficha_MedicaC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->lista_ficha_medica($_POST['id']));
}

if (isset($_GET['buscar'])) {
    echo json_encode($controlador->buscar_ficha_medica($_POST['buscar']));
}

if (isset($_GET['insertar'])) {
    echo json_encode($controlador->insertar_editar($_POST['parametros']));
}

if (isset($_GET['eliminar'])) {
    echo json_encode($controlador->eliminar($_POST['id']));
}


if (isset($_GET['listar_paciente_ficha'])) {
    echo json_encode($controlador->lista_solo_ficha_medica($_POST['sa_pac_id']));
}

//Para crear automaticamente paciente y ficha medica
if (isset($_GET['administrar_comunidad_ficha_medica'])) {

    $sa_pac_id_comunidad = '';
    $sa_pac_tabla = '';

    if (isset($_POST['sa_pac_id_comunidad'])) {
        $sa_pac_id_comunidad = $_POST['sa_pac_id_comunidad'];
    }

    if (isset($_POST['sa_pac_tabla'])) {
        $sa_pac_tabla = $_POST['sa_pac_tabla'];
    }

    echo json_encode($controlador->crear_paciente_ficha_medica($sa_pac_id_comunidad, $sa_pac_tabla));
}

if (isset($_GET['id_paciente_id_comunidad_tabla'])) {

    $sa_pac_id_comunidad = '';
    $sa_pac_tabla = '';

    if (isset($_POST['sa_pac_id_comunidad'])) {
        $sa_pac_id_comunidad = $_POST['sa_pac_id_comunidad'];
    }

    if (isset($_POST['sa_pac_tabla'])) {
        $sa_pac_tabla = $_POST['sa_pac_tabla'];
    }

    echo json_encode($controlador->id_paciente_id_comunidad_tabla($sa_pac_id_comunidad, $sa_pac_tabla));
}

if (isset($_GET['lista_seguros'])) {
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->lista_seguros($parametros));
}

if (isset($_GET['pdf_ficha_medica'])) {

    //print_r($_POST);die();
    $id_ficha = '';
    if (isset($_GET['id'])) {
        $id_ficha = $_GET['id'];
    }

    if (isset($_POST['id'])) {
        $id_ficha = $_POST['id'];
    }

    echo ($controlador->pdf_ficha_medica($id_ficha));
}

//print_r($controlador->id_paciente_id_comunidad_tabla('3','estudiantes'));

/*$parametros = array(
    'sa_sec_id' => 1,
    'sa_sec_nombre' => 'hola'
);

print_r($controlador->insertar_editar($parametros));*/

/*$modelo = new ficha_MedicaM();

print_r($modelo->buscar_ficha_medica_CODIGO(1));*/

class ficha_MedicaC
{
    private $modelo;
    private $seguros;
    private $cod_global;
    private $pacientesM;
    private $detalleFM;

    function __construct()
    {
        $this->modelo = new ficha_MedicaM();
        $this->seguros = new contratosM();
        $this->cod_global = new codigos_globales();
        $this->pacientesM = new pacientesM();
        $this->detalleFM = new detalle_fm_med_insM();
    }

    function lista_ficha_medica($id)
    {
        $datos = $this->modelo->lista_ficha_medica($id);
        return $datos;
    }

    function lista_solo_ficha_medica($id)
    {
        $datos = $this->modelo->lista_paciente_ficha_medica($id);
        return $datos;
    }

    function lista_seguros($parametros)
    {
        // print_r($parametros);die();
        $id = $this->cod_global->id_tabla($parametros['tabla']);
        $datos = $this->seguros->lista_articulos_seguro_detalle($parametros['tabla'], $parametros['id'], $_SESSION['INICIO']['MODULO_SISTEMA'], $id[0]['ID'], false);

        // print_r($datos);die();
        return $datos;
    }

    function buscar_ficha_medica($buscar)
    {
        $datos = $this->modelo->buscar_ficha_medica($buscar);
        return $datos;
    }

    function insertar_editar($parametros)
    {

        $datos = array(
            array('campo' => 'sa_fice_pac_id', 'dato' => $parametros['sa_fice_pac_id']),
            array('campo' => 'sa_fice_pac_grupo_sangre', 'dato' => $parametros['sa_fice_pac_grupo_sangre']),
            array('campo' => 'sa_fice_pac_direccion_domicilio', 'dato' => $parametros['sa_fice_pac_direccion_domicilio']),
            array('campo' => 'sa_fice_pac_seguro_medico', 'dato' => $parametros['sa_fice_pac_seguro_medico']),
            array('campo' => 'sa_fice_pac_seguro_predeterminado', 'dato' => $parametros['sa_fice_pac_seguro_predeterminado']),

            array('campo' => 'sa_fice_rep_1_primer_apellido', 'dato' => $parametros['sa_fice_rep_1_primer_apellido']),
            array('campo' => 'sa_fice_rep_1_segundo_apellido', 'dato' => $parametros['sa_fice_rep_1_segundo_apellido']),
            array('campo' => 'sa_fice_rep_1_primer_nombre', 'dato' => $parametros['sa_fice_rep_1_primer_nombre']),
            array('campo' => 'sa_fice_rep_1_segundo_nombre', 'dato' => $parametros['sa_fice_rep_1_segundo_nombre']),
            array('campo' => 'sa_fice_rep_1_parentesco', 'dato' => $parametros['sa_fice_rep_1_parentesco']),
            array('campo' => 'sa_fice_rep_1_telefono_1', 'dato' => $parametros['sa_fice_rep_1_telefono_1']),
            array('campo' => 'sa_fice_rep_1_telefono_2', 'dato' => $parametros['sa_fice_rep_1_telefono_2']),
            array('campo' => 'sa_fice_rep_1_cedula', 'dato' => $parametros['sa_fice_rep_1_cedula']),

            array('campo' => 'sa_fice_rep_2_primer_apellido', 'dato' => $parametros['sa_fice_rep_2_primer_apellido']),
            array('campo' => 'sa_fice_rep_2_segundo_apellido', 'dato' => $parametros['sa_fice_rep_2_segundo_apellido']),
            array('campo' => 'sa_fice_rep_2_primer_nombre', 'dato' => $parametros['sa_fice_rep_2_primer_nombre']),
            array('campo' => 'sa_fice_rep_2_segundo_nombre', 'dato' => $parametros['sa_fice_rep_2_segundo_nombre']),
            array('campo' => 'sa_fice_rep_2_parentesco', 'dato' => $parametros['sa_fice_rep_2_parentesco']),
            array('campo' => 'sa_fice_rep_2_telefono_1', 'dato' => $parametros['sa_fice_rep_2_telefono_1']),
            array('campo' => 'sa_fice_rep_2_telefono_2', 'dato' => $parametros['sa_fice_rep_2_telefono_2']),
            array('campo' => 'sa_fice_rep_2_cedula', 'dato' => $parametros['sa_fice_rep_2_cedula']),

            array('campo' => 'sa_fice_pregunta_1', 'dato' => $parametros['sa_fice_pregunta_1']),
            array('campo' => 'sa_fice_pregunta_1_obs', 'dato' => $parametros['sa_fice_pregunta_1_obs']),
            array('campo' => 'sa_fice_pregunta_2', 'dato' => $parametros['sa_fice_pregunta_2']),
            array('campo' => 'sa_fice_pregunta_2_obs', 'dato' => $parametros['sa_fice_pregunta_2_obs']),
            array('campo' => 'sa_fice_pregunta_3', 'dato' => $parametros['sa_fice_pregunta_3']),
            array('campo' => 'sa_fice_pregunta_3_obs', 'dato' => $parametros['sa_fice_pregunta_3_obs']),
            array('campo' => 'sa_fice_pregunta_4', 'dato' => $parametros['sa_fice_pregunta_4']),
            array('campo' => 'sa_fice_pregunta_4_obs', 'dato' => $parametros['sa_fice_pregunta_4_obs']),
            array('campo' => 'sa_fice_pregunta_5_obs', 'dato' => $parametros['sa_fice_pregunta_5_obs']),
            //Este campo es para que valide que ya esta hecha la ficha medica
            array('campo' => 'sa_fice_estado_realizado', 'dato' => 1),
            array('campo' => 'sa_fice_medicamentos_alergia', 'dato' => $parametros['sa_fice_medicamentos_alergia']),
            array('campo' => 'sa_fice_autoriza_medicamentos', 'dato' => $parametros['sa_fice_autoriza_medicamentos']),
        );

        if ($parametros['sa_fice_id'] == '') {
            return -2;
        } else {
            $where[0]['campo'] = 'sa_fice_id';
            $where[0]['dato'] = $parametros['sa_fice_id'];
            $datos = $this->modelo->editar($datos, $where);
        }
        return $datos;
    }

    function compara_datos($parametros)
    {
        $text = '';
        $marca = $this->modelo->lista_ficha_medica($parametros['id']);

        if ($marca[0]['CODIGO'] != $parametros['cod']) {
            $text .= ' Se modifico CODIGO en SECCION de ' . $marca[0]['CODIGO'] . ' a ' . $parametros['cod'];
        }

        if ($marca[0]['DESCRIPCION'] != $parametros['des']) {
            $text .= ' Se modifico DESCRIPCION en SECCION DE ' . $marca[0]['DESCRIPCION'] . ' a ' . $parametros['des'];
        }

        return $text;
    }

    function eliminar($id)
    {
        $datos[0]['campo'] = 'sa_fice_id';
        $datos[0]['dato'] = $id;
        $datos = $this->modelo->eliminar($datos);
        return $datos;
    }

    //Para crear la ficha medica
    function crear_paciente_ficha_medica($sa_pac_id_comunidad, $sa_pac_tabla)
    {
        /*$existe_paciente_comunidad = $this->modelo->existe_paciente_comunidad($sa_pac_id_comunidad, $sa_pac_tabla);
        $existe_paciente_comunidad = $existe_paciente_comunidad[0]['existe_paciente_comunidad'];

        //////////////////////////////////////////////////////////////////////////////////////////////////////////////
        //Variable para retornar el id del paciente
        $variable_paciente = 1;

        $salida = '';
        if ($existe_paciente_comunidad == 1) {
            $salida = 'valor del paciente';
        } else if ($existe_paciente_comunidad == 0) {
            $salida = $this->modelo->crear_paciente_ficha_medica($sa_pac_id_comunidad, $sa_pac_tabla);
        }

        return $salida;*/

        //echo $sa_pac_id_comunidad . ' ' . $sa_pac_tabla;


        return $this->modelo->gestion_comunidad_ficha_medica($sa_pac_id_comunidad, $sa_pac_tabla);
    }

    function id_paciente_id_comunidad_tabla($sa_pac_id_comunidad, $sa_pac_tabla)
    {
        return $this->modelo->obtener_id_tabla_paciente($sa_pac_id_comunidad, $sa_pac_tabla);
    }



    /**
     * PDF para la ficha medica
     */

    function pdf_ficha_medica($id_ficha_medica)
    {
        //echo $id_ficha_medica;die();
        $ficha_medica = $this->modelo->lista_ficha_medica_id($id_ficha_medica);
        //print_r($ficha_medica);die();

        $paciente = $this->pacientesM->obtener_informacion_pacienteM($ficha_medica[0]['sa_fice_pac_id']);

        //Ficha medica
        $sa_fice_pac_grupo_sangre = $ficha_medica[0]['sa_fice_pac_grupo_sangre'];
        $sa_fice_pac_direccion_domicilio = $ficha_medica[0]['sa_fice_pac_direccion_domicilio'];

        $sa_fice_pac_seguro_medico = $ficha_medica[0]['sa_fice_pac_seguro_medico'];
        $sa_fice_pac_seguro_predeterminado = $ficha_medica[0]['sa_fice_pac_seguro_predeterminado'];


        $sa_fice_rep_1_primer_apellido = $ficha_medica[0]['sa_fice_rep_1_primer_apellido'];
        $sa_fice_rep_1_segundo_apellido = $ficha_medica[0]['sa_fice_rep_1_segundo_apellido'];
        $sa_fice_rep_1_primer_nombre = $ficha_medica[0]['sa_fice_rep_1_primer_nombre'];
        $sa_fice_rep_1_segundo_nombre = $ficha_medica[0]['sa_fice_rep_1_segundo_nombre'];
        $sa_fice_rep_1_completo = $sa_fice_rep_1_primer_apellido . ' ' . $sa_fice_rep_1_segundo_apellido . ' ' . $sa_fice_rep_1_primer_nombre . ' ' . $sa_fice_rep_1_segundo_nombre;
        $sa_fice_rep_1_parentesco = $ficha_medica[0]['sa_fice_rep_1_parentesco'];
        $sa_fice_rep_1_telefono_1 = $ficha_medica[0]['sa_fice_rep_1_telefono_1'];
        $sa_fice_rep_1_telefono_2 = $ficha_medica[0]['sa_fice_rep_1_telefono_2'];
        $sa_fice_rep_1_cedula = $ficha_medica[0]['sa_fice_rep_1_cedula'];


        $sa_fice_rep_2_primer_apellido = $ficha_medica[0]['sa_fice_rep_2_primer_apellido'];
        $sa_fice_rep_2_segundo_apellido = $ficha_medica[0]['sa_fice_rep_2_segundo_apellido'];
        $sa_fice_rep_2_primer_nombre = $ficha_medica[0]['sa_fice_rep_2_primer_nombre'];
        $sa_fice_rep_2_segundo_nombre = $ficha_medica[0]['sa_fice_rep_2_segundo_nombre'];
        $sa_fice_rep_2_completo = $sa_fice_rep_2_primer_apellido . ' ' . $sa_fice_rep_2_segundo_apellido . ' ' . $sa_fice_rep_2_primer_nombre . ' ' . $sa_fice_rep_2_segundo_nombre;

        $sa_fice_rep_2_parentesco = $ficha_medica[0]['sa_fice_rep_2_parentesco'];
        $sa_fice_rep_2_telefono_1 = $ficha_medica[0]['sa_fice_rep_2_telefono_1'];
        $sa_fice_rep_2_telefono_2 = $ficha_medica[0]['sa_fice_rep_2_telefono_2'];

        $sa_fice_pregunta_1 = $ficha_medica[0]['sa_fice_pregunta_1'];
        $sa_fice_pregunta_2 = $ficha_medica[0]['sa_fice_pregunta_2'];
        $sa_fice_pregunta_3 = $ficha_medica[0]['sa_fice_pregunta_3'];
        $sa_fice_pregunta_4 = $ficha_medica[0]['sa_fice_pregunta_4'];

        $sa_fice_pregunta_1_obs = $ficha_medica[0]['sa_fice_pregunta_1_obs'];
        $sa_fice_pregunta_2_obs = $ficha_medica[0]['sa_fice_pregunta_2_obs'];
        $sa_fice_pregunta_3_obs = $ficha_medica[0]['sa_fice_pregunta_3_obs'];
        $sa_fice_pregunta_4_obs = $ficha_medica[0]['sa_fice_pregunta_4_obs'];
        $sa_fice_pregunta_5_obs = $ficha_medica[0]['sa_fice_pregunta_5_obs'];

        $sa_fice_fecha_creacion = $ficha_medica[0]['sa_fice_fecha_creacion'];
        $sa_fice_medicamentos_alergia = $ficha_medica[0]['sa_fice_medicamentos_alergia'];
        $sa_fice_autoriza_medicamentos = $ficha_medica[0]['sa_fice_autoriza_medicamentos'];

        //Pacientes
        $sa_pac_temp_cedula = $paciente[0]['sa_pac_temp_cedula'];
        $sa_pac_temp_primer_nombre = $paciente[0]['sa_pac_temp_primer_nombre'];
        $sa_pac_temp_segundo_nombre = $paciente[0]['sa_pac_temp_segundo_nombre'];
        $sa_pac_temp_primer_apellido = $paciente[0]['sa_pac_temp_primer_apellido'];
        $sa_pac_temp_segundo_apellido = $paciente[0]['sa_pac_temp_segundo_apellido'];
        $sa_pac_tabla = $paciente[0]['sa_pac_tabla'];
        $sa_pac_id_comunidad = $paciente[0]['sa_pac_id_comunidad'];

        $sa_pac_nombre_completos = $sa_pac_temp_primer_apellido . ' ' . $sa_pac_temp_segundo_apellido . ' ' . $sa_pac_temp_primer_nombre . ' ' . $sa_pac_temp_segundo_nombre;


        $sa_pac_temp_fecha_nacimiento = $paciente[0]['sa_pac_temp_fecha_nacimiento'];
        if ($sa_pac_temp_fecha_nacimiento !== null) {
            $sa_pac_temp_fecha_nacimiento = $sa_pac_temp_fecha_nacimiento;
        }

        $sa_pac_temp_correo = $paciente[0]['sa_pac_temp_correo'];


        $id = $this->cod_global->id_tabla($sa_pac_tabla);
        $datos_1 = $this->seguros->lista_articulos_seguro_detalle($sa_pac_tabla, $sa_pac_id_comunidad, $_SESSION['INICIO']['MODULO_SISTEMA'], $id[0]['ID'], false, $sa_fice_pac_seguro_predeterminado);

        $nombre_seguro = '';
        if (!empty($datos_1)) {
            $nombre_seguro = ($datos_1[0]['plan_seguro']);
        }

        // print_r($parametros);die();
        // $id = $this->cod_global->id_tabla($sa_pac_tabla);
        // $datos_1 = $this->seguros->lista_articulos_seguro_detalle($sa_pac_tabla, $sa_pac_id_comunidad, $_SESSION['INICIO']['MODULO_SISTEMA'], $id[0]['ID'], false, $sa_conp_permiso_seguro_traslado);

        // if (!empty($datos_1)) {
        //     $nombre_seguro = ($datos_1[0]['plan_seguro']);
        // }

        //Detalle Ficha medica 
        $detalles_FM = $this->detalleFM->lista_det_fm($ficha_medica[0]['sa_fice_id']);

        $logo = '../../assets/images/favicon-32x32.png';

        if (($_SESSION['INICIO']['LOGO']) == '.' || $_SESSION['INICIO']['LOGO'] == '' || $_SESSION['INICIO']['LOGO'] == null) {
            $logo;
        } else {
            $logo = '../' . $_SESSION['INICIO']['LOGO'];
        }



        $pdf = new FPDF('P', 'mm', 'A4');

        $pdf->AddPage();

        $pdf->Image($logo, 15, 10, 30, 30);

        $pdf->Cell(36, 10, utf8_decode(''), 'L T', 0);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(94, 10, utf8_decode('GESTIÓN ADMINISTRATIVA'), 1, 0, 'C');
        $pdf->Cell(20, 10, utf8_decode('Código:'), 1, 0, 'R');
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(40, 10, utf8_decode('GA-CE-FT-' . $id_ficha_medica), 1, 1, 'C');


        $pdf->Cell(36, 10, utf8_decode(''), 'L', 0);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(94, 10, utf8_decode('DEPARTAMENTO MÉDICO'), 1, 0, 'C');
        $pdf->Cell(20, 10, utf8_decode('Versión:'), 1, 0, 'R');
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(40, 10, utf8_decode('1.0'), 1, 1, 'C');

        $pdf->Cell(36, 10, utf8_decode(''), 'L B', 0, 'C');
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(94, 10, utf8_decode('CONSENTIMIENTO INFORMADO - FICHA DE AUTORIZACIÓN'), 1, 0, 'C');
        $pdf->Cell(20, 10, utf8_decode(''), 1, 0, 'R');
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(40, 10, utf8_decode(''), 1, 1, 'C');

        $pdf->ln('8');

        /////////////////////////////////////////////////////////////////////////////////////////////
        $pdf->SetFont('Arial', '', 8.5);
        $pdf->MultiCell(190, 4, utf8_decode('La siguiente ficha de registro es absolutamente confidencial. Solo será conocida por el equipo de salud escolar de la institución educativa, con el fin de lograr una mejor atención del estudiante durante su jornada escolar y en caso de emergencia. Para ello, solicitamos contestar correctamente todas las preguntas. La entrega de la presente ficha se debe realizar durante los primeros días del año lectivo.'), 0);


        $pdf->ln('5');


        $pdf->SetFont('Arial', 'B', 8.5);
        $pdf->SetFillColor(74, 113, 192);
        $pdf->Cell(190, 7, utf8_decode('UNIDAD EDUCATIVA PARTICULAR SAINT DOMINIC SCHOOL'), 1, 1, 'C', true);

        //        $pdf->SetFillColor(255, 255, 0);


        $pdf->SetFillColor(191, 212, 236);
        $pdf->Cell(95, 7, utf8_decode('Código AMIE: 17H01716'), 1, 0, 'C', true);
        $pdf->Cell(95, 7, utf8_decode('Año Lectivo: 2023 - 2024'), 1, 1, 'C', true);
        $pdf->SetFillColor(74, 113, 192);

        $pdf->Cell(190, 7, utf8_decode('I. Datos Generales del Estudiantes'), 1, 1, 'L', true);

        $pdf->SetFillColor(191, 212, 236);

        $pdf->SetFont('Arial', 'B', 8.5);
        $pdf->Cell(75, 7, utf8_decode('Nombre del estudiante:'), 1, 0, 'R', true);
        $pdf->SetFont('Arial', '', 8.5);
        $pdf->Cell(115, 7, utf8_decode($sa_pac_nombre_completos), 1, 1, 'L');

        $pdf->SetFont('Arial', 'B', 8.5);
        $pdf->Cell(75, 7, utf8_decode('Fecha de Nacimiento:'), 1, 0, 'R', true);
        $pdf->SetFont('Arial', '', 8.5);
        $pdf->Cell(115, 7, utf8_decode($sa_pac_temp_fecha_nacimiento), 1, 1, 'L');

        $pdf->SetFont('Arial', 'B', 8.5);
        $pdf->Cell(75, 7, utf8_decode('Grupo Sanguíneo y Factor Rh:'), 1, 0, 'R', true);
        $pdf->SetFont('Arial', '', 8.5);
        $pdf->Cell(115, 7, utf8_decode($sa_fice_pac_grupo_sangre), 1, 1, 'L');

        $pdf->SetFont('Arial', 'B', 8.5);
        $pdf->Cell(75, 7, utf8_decode('Dirección del Domicilio:'), 1, 0, 'R', true);
        $pdf->SetFont('Arial', '', 8.5);
        $pdf->Cell(115, 7, utf8_decode($sa_fice_pac_direccion_domicilio), 1, 1, 'L');

        $pdf->SetFont('Arial', 'B', 8.5);
        $pdf->Cell(75, 7, utf8_decode('Nombre del representante o familiar responsable:'), 1, 0, 'R', true);
        $pdf->SetFont('Arial', '', 8.5);
        $pdf->Cell(115, 7, utf8_decode($sa_fice_rep_1_completo), 1, 1, 'L');

        $pdf->SetFont('Arial', 'B', 8.5);
        $pdf->Cell(75, 7, utf8_decode('Parentesco:'), 1, 0, 'R', true);
        $pdf->SetFont('Arial', '', 8.5);
        $pdf->Cell(115, 7, utf8_decode($sa_fice_rep_1_parentesco), 1, 1, 'L');

        $pdf->SetFont('Arial', 'B', 8.5);
        $pdf->Cell(75, 7, utf8_decode('Teléfono Celular:'), 1, 0, 'R', true);
        $pdf->SetFont('Arial', '', 8.5);
        $pdf->Cell(115, 7, utf8_decode($sa_fice_rep_1_telefono_1), 1, 1, 'L');

        $pdf->SetFont('Arial', 'B', 8.5);
        $pdf->Cell(75, 7, utf8_decode('Teléfono Fijo:'), 1, 0, 'R', true);
        $pdf->SetFont('Arial', '', 8.5);
        $pdf->Cell(115, 7, utf8_decode($sa_fice_rep_1_telefono_2), 1, 1, 'L');

        $pdf->SetFont('Arial', 'B', 8.5);
        $pdf->Cell(75, 7, utf8_decode('¿El estudiante posee seguro médico?:'), 1, 0, 'R', true);
        $pdf->SetFont('Arial', '', 8.5);
        $pdf->Cell(115, 7, utf8_decode($sa_fice_pac_seguro_medico), 1, 1, 'L');

        $pdf->SetFont('Arial', 'B', 8.2);
        $pdf->Cell(75, 7, utf8_decode('Establecimiento de Salud al que normalmente acude:'), 1, 0, 'R', true);
        $pdf->SetFont('Arial', '', 8.5);
        $pdf->Cell(115, 7, utf8_decode($nombre_seguro), 1, 1, 'L');

        $pdf->MultiCell(190, 7, utf8_decode('En caso de urgencia llamar a (orden de importancia), Indique obligatoriamente al menos un número fijo de contacto:'), 1);


        $pdf->SetFont('Arial', 'B', 8.2);
        $pdf->Cell(75, 7, utf8_decode('Nombre del representante o familiar responsable:'), 1, 0, 'R', true);
        $pdf->SetFont('Arial', '', 8.5);
        $pdf->Cell(115, 7, utf8_decode($sa_fice_rep_1_completo), 1, 1, 'L');

        $pdf->SetFont('Arial', 'B', 8.2);
        $pdf->Cell(47.5, 7, utf8_decode('Parentesco:'), 1, 0, 'R', true);
        $pdf->SetFont('Arial', '', 8.5);
        $pdf->Cell(47.5, 7, utf8_decode($sa_fice_rep_1_parentesco), 1, 0, 'L');
        $pdf->SetFont('Arial', 'B', 8.2);
        $pdf->Cell(47.5, 7, utf8_decode('Teléfono:'), 1, 0, 'R', true);
        $pdf->SetFont('Arial', '', 8.5);
        $pdf->Cell(47.5, 7, utf8_decode($sa_fice_rep_1_telefono_1), 1, 1, 'L');

        $pdf->SetFont('Arial', 'B', 8.2);
        $pdf->Cell(75, 7, utf8_decode('Nombre del representante o familiar responsable:'), 1, 0, 'R', true);
        $pdf->SetFont('Arial', '', 8.5);
        $pdf->Cell(115, 7, utf8_decode($sa_fice_rep_2_completo), 1, 1, 'L');

        $pdf->SetFont('Arial', 'B', 8.2);
        $pdf->Cell(47.5, 7, utf8_decode('Parentesco:'), 1, 0, 'R', true);
        $pdf->SetFont('Arial', '', 8.5);
        $pdf->Cell(47.5, 7, utf8_decode($sa_fice_rep_2_parentesco), 1, 0, 'L');
        $pdf->SetFont('Arial', 'B', 8.2);
        $pdf->Cell(47.5, 7, utf8_decode('Teléfono:'), 1, 0, 'R', true);
        $pdf->SetFont('Arial', '', 8.5);
        $pdf->Cell(47.5, 7, utf8_decode($sa_fice_rep_2_telefono_1), 1, 1, 'L');

        $pdf->SetFillColor(74, 113, 192);

        $pdf->SetFont('Arial', 'B', 8.2);
        $pdf->Cell(190, 7, utf8_decode('II. Información Importante'), 1, 1, 'L', true);
        $pdf->SetFont('Arial', '', 8.2);

        $pdf->MultiCell(190, 7, utf8_decode('Si usted considera que existe alguna condición médica importante en el estudiante. Mencionar, por favor explíquelo a continuación.'), 1);


        $pdf->SetFillColor(191, 212, 236);

        $pdf->SetFont('Arial', 'B', 8.5);
        $pdf->Cell(190, 7, utf8_decode('   1.- ¿Ha sido diagnosticado con alguna enfermedad?:'), 1, 1, 'L', true);
        $pdf->SetFont('Arial', '', 9);

        if ($sa_fice_pregunta_1 === 'Si') {
            $pdf->MultiCell(190, 6, utf8_decode($sa_fice_pregunta_1_obs), 1);
        } else {
            $pdf->MultiCell(190, 6, utf8_decode($sa_fice_pregunta_1), 1);
        }

        $pdf->SetFont('Arial', 'B', 8.5);
        $pdf->Cell(190, 7, utf8_decode('   2.- ¿Tiene algún antecedente familiar de importancia?:'), 1, 1, 'L', true);
        $pdf->SetFont('Arial', '', 9);

        if ($sa_fice_pregunta_2 === 'Si') {
            $pdf->MultiCell(190, 6, utf8_decode($sa_fice_pregunta_2_obs), 1);
        } else {
            $pdf->MultiCell(190, 6, utf8_decode($sa_fice_pregunta_2), 1);
        }

        $pdf->SetFont('Arial', 'B', 8.5);
        $pdf->Cell(190, 7, utf8_decode('   3.- ¿Ha sido sometido a cirugías previas?:'), 1, 1, 'L', true);
        $pdf->SetFont('Arial', '', 9);

        if ($sa_fice_pregunta_3 === 'Si') {
            $pdf->MultiCell(190, 6, utf8_decode($sa_fice_pregunta_3_obs), 1);
        } else {
            $pdf->MultiCell(190, 6, utf8_decode($sa_fice_pregunta_3), 1);
        }

        $pdf->SetFont('Arial', 'B', 8.5);
        $pdf->Cell(190, 7, utf8_decode('   4.- ¿Tiene alergias?:'), 1, 1, 'L', true);
        $pdf->SetFont('Arial', '', 9);

        if ($sa_fice_pregunta_4 === 'Si') {
            $pdf->MultiCell(190, 6, utf8_decode($sa_fice_pregunta_4_obs), 1);
        } else {
            $pdf->MultiCell(190, 6, utf8_decode($sa_fice_pregunta_4), 1);
        }

        $pdf->SetFont('Arial', 'B', 8.5);
        $pdf->Cell(190, 7, utf8_decode('   5.- ¿Qué medicamentos usa?:'), 1, 1, 'L', true);
        $pdf->SetFont('Arial', '', 9);
        $pdf->MultiCell(190, 6, utf8_decode($sa_fice_pregunta_5_obs), 1);


        $pdf->SetFont('Arial', 'B', 8.5);
        $pdf->Cell(190, 7, utf8_decode('   6.- ¿Tiene prohibido tomar algún medicamento?:'), 1, 1, 'L', true);
        $pdf->SetFont('Arial', '', 9);

        if ($sa_fice_medicamentos_alergia === 'Si') {

            $salida = '';
            foreach ($detalles_FM as $detalle) {
                $salida .= $detalle['sa_det_fice_nombre'] . ', ';
            }
            $pdf->MultiCell(190, 6, utf8_decode($salida), 1);
        } else {
            $pdf->MultiCell(190, 6, utf8_decode('No'), 1);
        }


        /////////////////////////////////////////////////////////////////////////////////////////////

        $pdf->SetFillColor(74, 113, 192);
        $pdf->SetFont('Arial', 'B', 8.5);
        $pdf->Cell(190, 7, utf8_decode('III. Autorización'), 1, 1, 'L', true);
        $pdf->SetFont('Arial', '', 8.5);

        $fecha_entrada = $sa_fice_fecha_creacion;
        $timestamp = strtotime(substr($fecha_entrada, 0, 19));
        $fecha_formateada = date('d \d\e M \d\e Y', $timestamp);

        $pdf->MultiCell(190, 7, utf8_decode('Yo, ' . $sa_fice_rep_1_completo . ', con número de cédula ' . $sa_fice_rep_1_cedula . ' , SI autorizo que mi representado ' . $sa_pac_nombre_completos . ', con número de cédula ' . $sa_pac_temp_cedula . ' reciba atención médica escolar, y en caso de una urgencia, sea trasladado al establecimiento de salud respectivo en el Distrito o fuera de él si es necesario. Declaro que la información consignada en esta ficha corresponde a la realidad y se comprometen a comunicar por escrito a la Unidad Educativa Particular "Saint Dominic School" cualquier modificación de ésta.

Fecha: ' . $fecha_formateada), 1);

        $pdf->SetFont('Arial', 'B', 8.5);
        $pdf->Cell(190, 7, utf8_decode('Autorización para recibir medicamentos por parte del departamento médico:'), 1, 1, 'L', true);
        $pdf->SetFont('Arial', '', 9);

        $autoriza = '';
        if ($sa_fice_autoriza_medicamentos === '0') {
            $autoriza = 'No está autorizado';
            $pdf->MultiCell(190, 6, utf8_decode($autoriza), 1);
        } else {
            $autoriza = 'Autorizado';
            $pdf->MultiCell(190, 6, utf8_decode($autoriza), 1);
        }




        $pdf->SetFont('Arial', 'B', 8.5);
        $pdf->MultiCellRow(68, 5, utf8_decode('
Firma del padre de familia o representante legal (o huella digital):
        
'), 1, 'L', 1);
        $pdf->SetFont('Arial', '', 8.5);
        $pdf->MultiCellRow(122, 10, utf8_decode('
        
'), 1, 'L', 0);

        //$pdf->Cell(190, 7, utf8_decode(''), 1, 1, 'L');


        $pdf->ln('20');

        $pdf->SetFont('Arial', 'B', 8.5);
        $pdf->MultiCellRow(68, 10, utf8_decode('Nombres y Apellidos:'), 1, 'L', 1);
        $pdf->SetFont('Arial', '', 8.5);

        $pdf->MultiCellRow(122, 10, utf8_decode($sa_fice_rep_1_completo), 1, 'L', 0);

        //Footer
        $pdf->setY(271.9);
        $pdf->setX(30);
        $pdf->SetFont('Arial', 'I', 8);
        $pdf->Cell(148.5, 5, 'Desarrollado por Corsinf', 0, 0, 'C');
        $pdf->SetFont('Arial', 'B', 12);


        $pdf->Output('D', $sa_pac_temp_cedula . '-' . $sa_pac_nombre_completos . '.pdf');
        //$pdf->Output();
    }
}
