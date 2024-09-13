<?php
require_once(dirname(__DIR__, 4) . '/modelo/PASANTES/02_ADRIAN/POSTULANTES/th_formacion_academicaM.php');

$controlador = new th_formacion_academicaC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar($_POST['id']));
}

if (isset($_GET['listar_modal'])) {
    echo json_encode($controlador->listar_modal($_POST['id']));
}

if (isset($_GET['insertar'])) {
    echo json_encode($controlador->insertar_editar($_POST['parametros']));
}

if (isset($_GET['eliminar'])) {
    echo json_encode($controlador->eliminar($_POST['id']));
}


class th_formacion_academicaC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new th_formacion_academicaM();
    }

    function listar($id)
    {
        $datos = $this->modelo->where('th_fora_id_formacion_academica', $id)->listar($id);
        $p = '';
        foreach ($datos as $key => $value) {
            $p .= '<h6 class="fw-bold my-1">'. $value['th_fora_titulo_obtenido'] .'</h6>
            <p class="my-1">'. $value['th_fora_institución'] .'</p>
            <p class="my-1">'. $value['th_fora_fecha_inicio_formacion']. ' - '. $value['th_fora_fecha_fin_formacion'] .'</p>';
        }
        return $p;
    }

    function listar_modal($id)
    {
        $datos = $this->modelo->where('th_fora_id_formacion_academica', $id)->listar($id);
        return $datos;
    }

    function insertar_editar($parametros)
    {
        $datos = array(
            array('campo' => 'th_fora_titulo_obtenido', 'dato' => $parametros['txt_titulo_obtenido']),
            array('campo' => 'th_fora_institución', 'dato' => $parametros['txt_institucion']),
            array('campo' => 'th_fora_fecha_inicio_formacion', 'dato' => $parametros['txt_fecha_inicio_academico']),
            array('campo' => 'th_fora_fecha_fin_formacion', 'dato' => $parametros['txt_fecha_final_academico']),

        );

        if ($parametros['_id'] == '') {
            $datos = $this->modelo->insertar($datos);
        } else {
            $where[0]['campo'] = 'th_fora_id_formacion_academica';
            $where[0]['dato'] = $parametros['_id'];
            $datos = $this->modelo->editar($datos, $where);
        }

        return $datos;
    }

    function eliminar($id)
    {
        $datos = array(
            array('campo' => 'th_fora_estado', 'dato' => 0),
        );

        $where[0]['campo'] = 'th_fora_id_formacion_academica';
        $where[0]['dato'] = $id;

        $datos = $this->modelo->eliminar($datos, $where);
        return $datos;
    }
}
