<?php
date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 3) . '/modelo/HOST_TIME/CATALOGOS/hub_cats_unidad_tiempoM.php');

$controlador = new hub_cats_unidad_tiempoC();

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

    $query = '';

    if (isset($_GET['q'])) {
        $query = $_GET['q'];
    }

    $parametros = array(
        'query' => $query,
    );

    echo json_encode($controlador->buscar($parametros));
}


class hub_cats_unidad_tiempoC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new hub_cats_unidad_tiempoM();
    }

    function listar($id = '')
    {
        if ($id == '') {
            $datos = $this->modelo->where('is_deleted', 0)->listar();
        } else {
            $datos = $this->modelo
                ->where('id_unidad_tiempo', $id)
                ->where('is_deleted', 0)
                ->listar();
        }
        return $datos;
    }

    function insertar_editar($parametros)
    {
        $datos = array(
            array('campo' => 'nombre', 'dato' => $parametros['txt_nombre']),
            array('campo' => 'prefijo', 'dato' => $parametros['txt_prefijo']),
        );

        if ($parametros['_id'] == '') {

            $datos[] = array('campo' => 'id_usuario_crea', 'dato' => $_SESSION['INICIO']['ID_USUARIO']);
            $datos[] = array('campo' => 'fecha_creacion', 'dato' => date('Y-m-d H:i:s'));

            return  $datos = $this->modelo->insertar($datos);
        } else {


            $datos[] = array('campo' => 'id_usuario_modifica', 'dato' => $_SESSION['INICIO']['ID_USUARIO']);
            $datos[] = array('campo' => 'fecha_modificacion', 'dato' => date('Y-m-d H:i:s'));

            $where[0]['campo'] = 'id_unidad_tiempo';
            $where[0]['dato']  = $parametros['_id'];

            return  $datos = $this->modelo->editar($datos, $where);
        }

        return $datos;
    }

    function eliminar($id)
    {
        $datos = array(
            array('campo' => 'is_deleted', 'dato' => 1),
            array('campo' => 'id_usuario_modifica', 'dato' => $_SESSION['INICIO']['ID_USUARIO']),
            array('campo' => 'fecha_modificacion', 'dato' => date('Y-m-d H:i:s')),
        );

        $where[0]['campo'] = 'id_unidad_tiempo';
        $where[0]['dato'] = $id;

        $datos = $this->modelo->editar($datos, $where);
        return $datos;
    }

    function buscar($parametros)
    {
        $lista = array();
        $concat = "nombre, prefijo";

        $datos = $this->modelo
            ->where('is_deleted', 0)
            ->like($concat, $parametros['query']);

        foreach ($datos as $value) {
            $text = $value['nombre'] . ' (' . $value['prefijo'] . ')';

            $lista[] = array(
                'id' => ($value['id_unidad_tiempo']),
                'text' => ($text)
            );
        }

        return $lista;
    }
}
