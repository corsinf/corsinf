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
        $datos = array(
            array('campo' => 'sa_fice_id', 'dato' => $parametros['paciente']),
            array('campo' => 'sa_conp_fecha_ingreso', 'dato' => $parametros['fecha']),
            array('campo' => 'sa_conp_tipo_consulta', 'dato' => $parametros['tipo']),
            array('campo' => 'sa_conp_estado_revision', 'dato' => 0)

        );
        return  $datos = $this->modelo->insertar('consultas_medicas', $datos);

        //print_r($datos);
        //die();
    }
}
