<?php
date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 3) . '/modelo/TALENTO_HUMANO/DOTACIONES/th_per_dotacion_detalleM.php');

$controlador = new th_per_dotacion_detalleC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar($_POST['id']));
}

if (isset($_GET['listar_dotaciones_detalle'])) {
    echo json_encode($controlador->listar_dotacion_detalle($_POST['id']));
}

if (isset($_GET['insertar'])) {
    echo json_encode($controlador->insertar_editar($_POST['parametros']));
}

if (isset($_GET['eliminar'])) {
    echo json_encode($controlador->eliminar($_POST['id']));
}


class th_per_dotacion_detalleC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new th_per_dotacion_detalleM();
    }

    function listar_dotacion_detalle($th_dot_id)
    {
        return $this->modelo->listar_detalle_dotacion($th_dot_id);
    }

    function listar($th_dot_id)
    {
        return $this->modelo->listar_detalle_dotacion($th_dot_id);
    }

    function insertar_editar($parametros)
    {
        $datos = array(
            array('campo' => 'th_dot_id', 'dato' => $parametros['th_dot_id']),
            array('campo' => 'id_dotacion_item', 'dato' => $parametros['ddl_dotacion_item']),
            array('campo' => 'id_talla', 'dato' => $parametros['ddl_talla']),
            array('campo' => 'th_dotd_cantidad', 'dato' => $parametros['txt_cantidad_adicional']),
            array('campo' => 'th_dotd_estado_item', 'dato' => 1),
            array('campo' => 'th_dotd_fecha_modificacion', 'dato' => date('Y-m-d H:i:s')),
        );

        if ($parametros['_id'] == '') {
            $datos[] = array('campo' => 'th_dotd_fecha_creacion', 'dato' => date('Y-m-d H:i:s'));
            $datos[] = array('campo' => 'th_dotd_estado', 'dato' => 1);
            return $this->modelo->insertar($datos);
        } else {
            $where[0]['campo'] = 'th_dotd_id';
            $where[0]['dato'] = $parametros['_id'];
            return $this->modelo->editar($datos, $where);
        }
    }

     function eliminar($id)
    {
        $datos = array(
            array('campo' => 'th_dotd_estado', 'dato' => 0),
            array('campo' => 'th_dotd_fecha_modificacion', 'dato' => date('Y-m-d H:i:s')),
        );

        $where[0]['campo'] = 'th_dotd_id';
        $where[0]['dato'] = strval($id);
        $resultado = $this->modelo->editar($datos, $where);

        return $resultado;
    }

    
}
