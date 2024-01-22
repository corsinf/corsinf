<?php
include('../modelo/ficha_MedicaM.php');
include('../modelo/contratosM.php');
include('../db/codigos_globales.php');

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

//posiblemnte ya no sirve
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

if (isset($_GET['lista_seguros'])) {
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->lista_seguros($parametros));
}

//print_r($controlador->lista_ficha_medica(''));

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

    function __construct()
    {
        $this->modelo = new ficha_MedicaM();
        $this->seguros = new contratosM();
        $this->cod_global = new codigos_globales();
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
            array('campo' => 'sa_fice_rep_2_primer_apellido', 'dato' => $parametros['sa_fice_rep_2_primer_apellido']),
            array('campo' => 'sa_fice_rep_2_segundo_apellido', 'dato' => $parametros['sa_fice_rep_2_segundo_apellido']),
            array('campo' => 'sa_fice_rep_2_primer_nombre', 'dato' => $parametros['sa_fice_rep_2_primer_nombre']),
            array('campo' => 'sa_fice_rep_2_segundo_nombre', 'dato' => $parametros['sa_fice_rep_2_segundo_nombre']),
            array('campo' => 'sa_fice_rep_2_parentesco', 'dato' => $parametros['sa_fice_rep_2_parentesco']),
            array('campo' => 'sa_fice_rep_2_telefono_1', 'dato' => $parametros['sa_fice_rep_2_telefono_1']),
            array('campo' => 'sa_fice_rep_2_telefono_2', 'dato' => $parametros['sa_fice_rep_2_telefono_2']),
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
}
