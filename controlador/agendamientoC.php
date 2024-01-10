<?php
include('../modelo/agendamientoM.php');
include('../modelo/pacientesM.php');
include('../modelo/ficha_MedicaM.php');

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
    private $ficha_medica;

    function __construct()
    {
        $this->modelo = new agendamientoM();
        $this->pacientes = new pacientesM();
        $this->ficha_medica = new ficha_MedicaM();
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

        $sa_pac_tabla = $parametros['sa_pac_tabla'];
        $sa_pac_id_comunidad = $parametros['sa_pac_id_comunidad'];

        $buscar_paciente = $this->ficha_medica->gestion_comunidad_ficha_medica($sa_pac_id_comunidad, $sa_pac_tabla);
        $id_paciente = $buscar_paciente['sa_pac_id'];

        $buscar_paciente_fm = $this->pacientes->buscar_pacientes_ficha_medica($id_paciente);
        $id_paciente_fm = $buscar_paciente_fm[0]['sa_fice_id'];

        if ($parametros['tipo'] == 'consulta') {

            $datos = array(
                array('campo' => 'sa_fice_id', 'dato' => $id_paciente_fm),
                array('campo' => 'sa_conp_fecha_ingreso', 'dato' => $parametros['fecha']),
                array('campo' => 'sa_conp_tipo_consulta', 'dato' => $parametros['tipo']),
                array('campo' => 'sa_conp_estado_revision', 'dato' => 0),

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
                array('campo' => 'sa_fice_id', 'dato' => $id_paciente_fm),
                array('campo' => 'sa_conp_fecha_ingreso', 'dato' => $parametros['fecha']),
                array('campo' => 'sa_conp_tipo_consulta', 'dato' => $parametros['tipo']),
                array('campo' => 'sa_conp_estado_revision', 'dato' => 0),

            );
        }

        return  $datos = $this->modelo->insertar('consultas_medicas', $datos);

        //print_r($datos);
        //die();
    }
}
