<?php
include('../modelo/consultasM.php');

$controlador = new consultasC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->lista_consultas($_POST['id']));
}

if (isset($_GET['buscar'])) {
    echo json_encode($controlador->buscar_consultas($_POST['buscar']));
}

if (isset($_GET['insertar'])) {
    echo json_encode($controlador->insertar_editar($_POST['parametros']));
}

if (isset($_GET['eliminar'])) {
    echo json_encode($controlador->eliminar($_POST['id']));
}

if (isset($_GET['listar_solo_consulta'])) {
    echo json_encode($controlador->lista_solo_consultas($_POST['id']));
}

//print_r($controlador->lista_consultas(''));

/*$parametros = array(
    'sa_sec_id' => 1,
    'sa_sec_nombre' => 'hola'
);

print_r($controlador->insertar_editar($parametros));*/

/*$modelo = new consultasM();

print_r($modelo->buscar_consultas_CODIGO(1));*/

class consultasC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new consultasM();
    }

    function lista_consultas($id)
    {
        $datos = $this->modelo->lista_consultas($id);
        return $datos;
    }

    function lista_solo_consultas($id)
    {
        $datos = $this->modelo->lista_solo_consultas($id);
        return $datos;
    }

    function buscar_consultas($buscar)
    {
        $datos = $this->modelo->buscar_consultas($buscar);
        return $datos;
    }

    function insertar_editar($parametros)
    {
        $datos1[0]['campo'] = 'sa_conp_id';
        $datos1[0]['dato'] = strval($parametros['sa_conp_id']);

        $datos = array(
            array('campo' => 'sa_fice_id', 'dato' => strval($parametros['sa_fice_id'])),
            array('campo' => 'sa_conp_nombres', 'dato' => $parametros['sa_conp_nombres']),
            array('campo' => 'sa_conp_nivel', 'dato' => $parametros['sa_conp_nivel']),
            array('campo' => 'sa_conp_paralelo', 'dato' => $parametros['sa_conp_paralelo']),
            array('campo' => 'sa_conp_edad', 'dato' => $parametros['sa_conp_edad']),
            array('campo' => 'sa_conp_correo', 'dato' => $parametros['sa_conp_correo']),
            array('campo' => 'sa_conp_telefono', 'dato' => $parametros['sa_conp_telefono']),
            array('campo' => 'sa_conp_fecha_ingreso', 'dato' => $parametros['sa_conp_fecha_ingreso']),
            array('campo' => 'sa_conp_desde_hora', 'dato' => $parametros['sa_conp_desde_hora']),
            array('campo' => 'sa_conp_hasta_hora', 'dato' => $parametros['sa_conp_hasta_hora']),
            array('campo' => 'sa_conp_tiempo_aten', 'dato' => $parametros['sa_conp_tiempo_aten']),
            array('campo' => 'sa_conp_CIE_10_1', 'dato' => $parametros['sa_conp_CIE_10_1']),
            array('campo' => 'sa_conp_diagnostico_1', 'dato' => $parametros['sa_conp_diagnostico_1']),
            array('campo' => 'sa_conp_CIE_10_2', 'dato' => $parametros['sa_conp_CIE_10_2']),
            array('campo' => 'sa_conp_diagnostico_2', 'dato' => $parametros['sa_conp_diagnostico_2']),
            array('campo' => 'sa_conp_medicina_1', 'dato' => $parametros['sa_conp_medicina_1']),
            array('campo' => 'sa_conp_dosis_1', 'dato' => $parametros['sa_conp_dosis_1']),
            array('campo' => 'sa_conp_medicina_2', 'dato' => $parametros['sa_conp_medicina_2']),
            array('campo' => 'sa_conp_dosis_2', 'dato' => $parametros['sa_conp_dosis_2']),
            array('campo' => 'sa_conp_medicina_3', 'dato' => $parametros['sa_conp_medicina_3']),
            array('campo' => 'sa_conp_dosis_3', 'dato' => $parametros['sa_conp_dosis_3']),
            array('campo' => 'sa_conp_certificado_salud', 'dato' => $parametros['sa_conp_certificado_salud']),
            array('campo' => 'sa_conp_motivo_certificado', 'dato' => $parametros['sa_conp_motivo_certificado']),
            array('campo' => 'sa_conp_CIE_10_certificado', 'dato' => $parametros['sa_conp_CIE_10_certificado']),
            array('campo' => 'sa_conp_diagnostico_certificado', 'dato' => $parametros['sa_conp_diagnostico_certificado']),
            array('campo' => 'sa_conp_fecha_entrega_certificado', 'dato' => $parametros['sa_conp_fecha_entrega_certificado']),
            array('campo' => 'sa_conp_fecha_inicio_falta_certificado', 'dato' => $parametros['sa_conp_fecha_inicio_falta_certificado']),
            array('campo' => 'sa_conp_fecha_fin_alta_certificado', 'dato' => $parametros['sa_conp_fecha_fin_alta_certificado']),
            array('campo' => 'sa_conp_dias_permiso_certificado', 'dato' => $parametros['sa_conp_dias_permiso_certificado']),
            array('campo' => 'sa_conp_permiso_salida', 'dato' => $parametros['sa_conp_permiso_salida']),
            array('campo' => 'sa_conp_fecha_permiso_salud_salida', 'dato' => $parametros['sa_conp_fecha_permiso_salud_salida']),
            array('campo' => 'sa_conp_hora_permiso_salida', 'dato' => $parametros['sa_conp_hora_permiso_salida']),
            array('campo' => 'sa_conp_notificacion_envio_representante', 'dato' => $parametros['sa_conp_notificacion_envio_representante']),
            array('campo' => 'sa_conp_notificacion_envio_inspector', 'dato' => $parametros['sa_conp_notificacion_envio_inspector']),
            array('campo' => 'sa_conp_notificacion_envio_guardia', 'dato' => $parametros['sa_conp_notificacion_envio_guardia']),
            array('campo' => 'sa_conp_observaciones', 'dato' => $parametros['sa_conp_observaciones']),
            array('campo' => 'sa_conp_tipo_consulta', 'dato' => $parametros['sa_conp_tipo_consulta']),
            array('campo' => 'sa_conp_estado', 'dato' => 1) //editar
        );



        if ($parametros['sa_conp_id'] == '') {
            if (count($this->modelo->buscar_consultas_CODIGO($datos1[0]['dato'])) == 0) {
                $datos = $this->modelo->insertar($datos);
            } else {
                return -2 .' . '. $datos1[0]['dato'];

            }
        } else {
            $where[0]['campo'] = 'sa_conp_id';
            $where[0]['dato'] = $parametros['sa_conp_id'];
            $datos = $this->modelo->editar($datos, $where);
        }

        /*$where[0]['campo'] = 'sa_conp_id';
        $where[0]['dato'] = $parametros['sa_conp_id'];
        $datos = $this->modelo->editar($datos, $where);*/
        return $datos;
    }

    function compara_datos($parametros)
    {
        $text = '';
        $marca = $this->modelo->lista_consultas($parametros['id']);

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
        $datos[0]['campo'] = 'sa_conp_id';
        $datos[0]['dato'] = $id;
        $datos = $this->modelo->eliminar($datos);
        return $datos;
    }
}
