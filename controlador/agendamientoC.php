<?php
date_default_timezone_set('America/Guayaquil'); 
include('../modelo/agendamientoM.php');
include('../modelo/estudiantesM.php');

$controlador = new agendamientoC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->lista_consultas());
}
if (isset($_GET['cita_actual'])) {
    echo json_encode($controlador->cita_actual());
}

if (isset($_GET['buscar'])) {
	$query = '' ;
	if(isset($_GET['q']))
	{
		$query = $_GET['q'];
	}
    echo json_encode($controlador->lista_estudiantes($query));
}

if (isset($_GET['add_agenda'])) {

	$parametros = $_POST['parametros'];
    echo json_encode($controlador->add_agenda($parametros));
}

//echo json_encode($controlador->buscar_estudiante_ficha_medica(5));

class agendamientoC
{
    private $modelo;
    private $estudiantes;

    function __construct()
    {
        $this->modelo = new agendamientoM();
        $this->estudiantes = new estudiantesM();
    }

    function lista_consultas()
    {
    	$datos = $this->modelo->lista_consultas();
    	return $datos;
    	// print_r($datos);die();
    }

    function cita_actual(){
        $fecha = date('Y-m-d');
        $datos = $this->modelo->lista_consultas($fecha);
        return $datos;
    }

    function lista_estudiantes($buscar)
    {
    	$datos = $this->estudiantes->buscar_estudiantes($buscar);
    	$lista = array();
    	foreach ($datos as $key => $value) {
    		$lista[] = array('id'=>$value['sa_est_id'],'text'=>$value['sa_est_primer_apellido'].' '.$value['sa_est_primer_nombre'],'data'=>$value);
    	}
        return $lista;
    }
    function add_agenda($parametros)
    {
    	$estudiante = $this->estudiantes->lista_estudiantes($parametros['estudiante']);

    	$fechaObj1 = new DateTime( $estudiante[0]['sa_est_fecha_nacimiento']->format('Y-m-d'));
		$fechaObj2 = new DateTime();

		$diferencia = $fechaObj1->diff($fechaObj2);
		$diferenciaEnAnios = $diferencia->y;

        $datos = array(
            array('campo' => 'sa_fice_id', 'dato' => $estudiante[0]['sa_est_id']),
            array('campo' => 'sa_conp_nombres', 'dato' => $estudiante[0]['sa_est_primer_apellido'].' '.$estudiante[0]['sa_est_primer_nombre']),
            array('campo' => 'sa_conp_nivel', 'dato' => $estudiante[0]['sa_id_grado']),
            array('campo' => 'sa_conp_paralelo', 'dato' => $estudiante[0]['sa_id_paralelo']),
            array('campo' => 'sa_conp_edad', 'dato' => $diferenciaEnAnios),
            array('campo' => 'sa_conp_fecha_ingreso', 'dato' => $parametros['fecha']),
            array('campo' => 'sa_conp_tipo_consulta', 'dato' => $parametros['tipo']),
            array('campo' => 'sa_conp_estado', 'dato' => 0)

        );
        return  $datos = $this->modelo->insertar('consultas',$datos);




    	print_r($datos);die();

    }


}

