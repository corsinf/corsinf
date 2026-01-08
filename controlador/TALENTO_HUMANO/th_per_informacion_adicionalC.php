<?php
date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 2) . '/modelo/TALENTO_HUMANO/th_per_informacion_adicionalM.php');

$controlador = new th_per_informacion_adicionalC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar($_POST['id'] ?? ''));
}


if (isset($_GET['insertar'])) {
    echo json_encode($controlador->insertar_editar($_POST['parametros']));
}

if (isset($_GET['eliminar'])) {
    echo json_encode($controlador->eliminar($_POST['id']));
}

class th_per_informacion_adicionalC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new th_per_informacion_adicionalM();
    }


    function listar($id = '')
    {
        if ($id == '') {
            $datos = $this->modelo
                ->where('th_inf_adi_estado', 1)
                ->listar();
        } else {
            $datos = $this->modelo
                ->where('th_inf_adi_id', $id)
                ->listar();
        }
        return $datos;
    }

    function insertar_editar($parametros)
    {
        $id = isset($parametros['_id']) ? intval($parametros['_id']) : 0;

        $datos = array(
            array('campo' => 'th_per_id', 'dato' => $parametros['th_per_id']),
            array('campo' => 'th_inf_adi_tiempo_trabajo', 'dato' => $parametros['tiempo_trabajo']),
            array('campo' => 'th_inf_adi_remuneracion_promedio', 'dato' => $parametros['remuneracion_promedio']),
            array('campo' => 'th_inf_adi_fecha_modificacion', 'dato' => date('Y-m-d H:i:s')),
        );


        if ($id == 0) {
            $existe = $this->modelo
                ->where('th_per_id', $parametros['th_per_id'])
                ->where('th_inf_adi_estado', 1)
                ->listar();

            if (count($existe) > 0) {
                return -2; // Ya existe información adicional
            }

            $datos_insert = $this->modelo->insertar_id($datos);
            return $datos_insert ? 1 : 0;
        } else {

            $existe = $this->modelo
                ->where('th_per_id', $parametros['th_per_id'])
                ->where('th_inf_adi_id !', $id)
                ->where('th_inf_adi_estado', 1)
                ->listar();

            if (count($existe) > 0) {
                return -2;
            }

            $where[0]['campo'] = 'th_inf_adi_id';
            $where[0]['dato'] = $id;

            $datos = $this->modelo->editar($datos, $where);
            return $datos;
        }
    }
    function eliminar($id)
    {
        $datos = array(
            array('campo' => 'th_inf_adi_estado', 'dato' => 0),
            array('campo' => 'th_inf_adi_fecha_modificacion', 'dato' => date('Y-m-d H:i:s')),
        );

        $where[0]['campo'] = 'th_inf_adi_id';
        $where[0]['dato'] = $id;

        $datos = $this->modelo->editar($datos, $where);
        return $datos;
    }
}
