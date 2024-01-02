<?php
include('../modelo/agendamientoM.php');
include('../modelo/pacientesM.php');

$controlador = new agendamientoC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->lista_consultas());
}
if (isset($_GET['cita_actual'])) {
    echo json_encode($controlador->cita_actual());
}

if (isset($_GET['buscar'])) {
    $query = '';
    if (isset($_GET['q'])) {
        $query = $_GET['q'];
    }
    echo json_encode($controlador->lista_pacientes($query));
}

if (isset($_GET['add_agenda'])) {

    $parametros = $_POST['parametros'];
    echo json_encode($controlador->add_agenda($parametros));
}

class agendamientoC
{
    private $modelo;
    private $pacientes;

    function __construct()
    {
        $this->modelo = new agendamientoM();
        $this->pacientes = new pacientesM();
    }

    function lista_consultas()
    {
        $datos = $this->modelo->lista_consultas();
        return $datos;
        // print_r($datos);die();
    }

    function cita_actual()
    {
        $fecha = date('Y-m-d');
        //$fecha = '';
        $datos = $this->modelo->lista_consultas($fecha);
        return $datos;
    }

    function lista_pacientes($buscar)
    {
        $datos = $this->pacientes->buscar_pacientes($buscar);
        $lista = array();
        foreach ($datos as $key => $value) {
            $lista[] = array('id' => $value['sa_fice_id'], 'text' => ($value['sa_pac_cedula'] . ' - ' . $value['sa_pac_apellidos'] . ' ' . $value['sa_pac_nombres']), 'data' => $value);
        }
        return $lista;
    }

    function add_agenda($parametros)
    {
        $datos = null;

        if ($parametros['tipo'] == 'consulta') {
            $datos = array(
                array('campo' => 'sa_fice_id', 'dato' => $parametros['paciente']),
                array('campo' => 'sa_conp_fecha_ingreso', 'dato' => $parametros['fecha']),
                array('campo' => 'sa_conp_tipo_consulta', 'dato' => $parametros['tipo']),
                array('campo' => 'sa_conp_estado_revision', 'dato' => 0),
                array('campo' => 'sa_conp_desde_hora', 'dato' => '00:00:00.0000000'),
                array('campo' => 'sa_conp_hasta_hora', 'dato' => '00:00:00.0000000'),

                array('campo' => 'sa_conp_peso', 'dato' => $parametros['sa_conp_peso']),
                array('campo' => 'sa_conp_altura', 'dato' => $parametros['sa_conp_altura']),
                array('campo' => 'sa_conp_temperatura', 'dato' => $parametros['sa_conp_temperatura']),
                array('campo' => 'sa_conp_presion_ar', 'dato' => $parametros['sa_conp_presion_ar']),
                array('campo' => 'sa_conp_frec_cardiaca', 'dato' => $parametros['sa_conp_frec_cardiaca']),
                array('campo' => 'sa_conp_frec_respiratoria', 'dato' => $parametros['sa_conp_frec_respiratoria']),
                array('campo' => 'sa_conp_motivo_consulta', 'dato' => $parametros['sa_conp_motivo_consulta']),

            );
        } else if ($parametros['tipo'] == 'certificado') {
            $datos = array(
                array('campo' => 'sa_fice_id', 'dato' => $parametros['paciente']),
                array('campo' => 'sa_conp_fecha_ingreso', 'dato' => $parametros['fecha']),
                array('campo' => 'sa_conp_tipo_consulta', 'dato' => $parametros['tipo']),
                array('campo' => 'sa_conp_estado_revision', 'dato' => 0),
                array('campo' => 'sa_conp_desde_hora', 'dato' => '00:00:00.0000000'),
                array('campo' => 'sa_conp_hasta_hora', 'dato' => '00:00:00.0000000'),
            );
        }

        return  $datos = $this->modelo->insertar('consultas_medicas', $datos);

        //print_r($datos);
        //die();
    }
}
