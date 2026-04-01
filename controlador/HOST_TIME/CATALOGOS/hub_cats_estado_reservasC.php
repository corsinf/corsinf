<?php
date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 3) . '/modelo/HOST_TIME/CATALOGOS/hub_cats_estado_reservasM.php');

$controlador = new hub_cats_estado_reservasC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar($_POST['id'] ?? ''));
}

if (isset($_GET['insertar'])) {
    echo json_encode($controlador->insertar_editar($_POST['parametros']));
}

if (isset($_GET['eliminar'])) {
    echo json_encode($controlador->eliminar($_POST['id']));
}

if (isset($_GET['buscar'])) {
    $query = $_GET['q'] ?? '';

    $parametros = array(
        'query' => $query,
    );

    echo json_encode($controlador->buscar($parametros));
}

class hub_cats_estado_reservasC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new hub_cats_estado_reservasM();
    }

    function listar($id = '')
    {
        if ($id == '') {
            return $this->modelo->where('activo', 1)->listar();
        } else {
            return $this->modelo->where('id_estado_reserva', $id)->listar();
        }
    }

    function insertar_editar($parametros)
    {
        $datos = array(
            array('campo' => 'codigo', 'dato' => $parametros['txt_codigo']),
            array('campo' => 'nombre', 'dato' => $parametros['txt_nombre']),
            array('campo' => 'descripcion', 'dato' => $parametros['txt_descripcion']),
            array('campo' => 'activo', 'dato' => 1),
        );

        if ($parametros['_id'] == '') {
            // VALIDAR DUPLICADO POR NOMBRE
            if (count($this->modelo
                ->where('nombre', $parametros['txt_nombre'])
                ->where('activo', 1)
                ->listar()) == 0) {

                return $this->modelo->insertar($datos);
            } else {
                return -2;
            }
        } else {
            // VALIDAR DUPLICADO EN UPDATE
            if (count($this->modelo
                ->where('nombre', $parametros['txt_nombre'])
                ->where('id_estado_reserva !', $parametros['_id'])
                ->where('activo', 1)
                ->listar()) == 0) {

                $where[0]['campo'] = 'id_estado_reserva';
                $where[0]['dato'] = $parametros['_id'];

                return $this->modelo->editar($datos, $where);
            } else {
                return -2;
            }
        }
    }

    function eliminar($id)
    {
        $datos = array(
            array('campo' => 'activo', 'dato' => 0),
        );

        $where[0]['campo'] = 'id_estado_reserva';
        $where[0]['dato'] = $id;

        return $this->modelo->editar($datos, $where);
    }

    function buscar($parametros)
    {
        $lista = array();
        $concat = "codigo, nombre, descripcion";

        $datos = $this->modelo
            ->where('activo', 1)
            ->like($concat, $parametros['query']);

        foreach ($datos as $value) {
            $text = $value['nombre'];

            $lista[] = array(
                'id' => $value['id_estado_reserva'],
                'text' => $text
            );
        }

        return $lista;
    }
}
