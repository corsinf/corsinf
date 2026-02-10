<?php
date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 3)  . '/modelo/TALENTO_HUMANO/CONTRATACION/th_cat_rango_profesionalM.php');

$controlador = new th_cat_rango_profesionalC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar($_POST['id'] ?? ''));
}

if (isset($_GET['insertar_editar'])) {
    echo json_encode($controlador->insertar_editar($_POST['parametros'] ?? []));
}

if (isset($_GET['eliminar'])) {
    echo json_encode($controlador->eliminar($_POST['id'] ?? ''));
}

if (isset($_GET['buscar'])) {
    $query = '';
    if (isset($_GET['q'])) {
        $query = $_GET['q'];
    }
    $parametros = array('query' => $query);
    echo json_encode($controlador->buscar($parametros));
}


class th_cat_rango_profesionalC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new th_cat_rango_profesionalM();
    }

    
    function listar($id = '')
    {
        if ($id === '') {
            $datos = $this->modelo->where('estado', 1)->listar();
        } else {
            $datos = $this->modelo->where('id', $id)->where('estado', 1)->listar();
        }
        return $datos;
    }

    function insertar_editar($parametros)
    {
        // normalizadores
        $nombre = isset($parametros['txt_nombre']) ? trim($parametros['txt_nombre']) : '';
        $descripcion = $parametros['txt_descripcion'] ?? '';
        $estado = isset($parametros['chk_estado']) ? ($parametros['chk_estado'] ? 1 : 0) : 1;

        // preparar datos comunes
        $datos = array(
            array('campo' => 'nombre', 'dato' => $nombre),
            array('campo' => 'descripcion', 'dato' => $descripcion),
            array('campo' => 'estado', 'dato' => $estado),
            array('campo' => 'fecha_modificacion', 'dato' => date('Y-m-d H:i:s')),
        );

        // Inserción
        if (empty($parametros['_id'])) {
            // validar duplicado (mismo nombre activo)
            $dup = $this->modelo->where('nombre', $nombre)->where('estado', 1)->listar();
            if (count($dup) == 0) {
                $datos[] = array('campo' => 'fecha_creacion', 'dato' => date('Y-m-d H:i:s'));
                $id = $this->modelo->insertar_id($datos);
                return 1; // coherente con tus otros controladores
            } else {
                return -2; // nombre duplicado
            }
        } else {
            // Edición: validar duplicado en otro id
            $dup = $this->modelo->where('nombre', $nombre)
                               ->where('id !', $parametros['_id'])
                               ->listar();
            if (count($dup) == 0) {
                $where[0]['campo'] = 'id';
                $where[0]['dato']  = $parametros['_id'];

                $res = $this->modelo->editar($datos, $where);
                return $res;
            } else {
                return -2; // duplicado en otro registro
            }
        }
    }
   
    function eliminar($id)
    {
        $datos = array(
            array('campo' => 'estado', 'dato' => 0),
            array('campo' => 'fecha_modificacion', 'dato' => date('Y-m-d H:i:s')),
        );

        $where[0]['campo'] = 'id';
        $where[0]['dato']  = $id;

        $res = $this->modelo->editar($datos, $where);
        return $res;
    }
   
    function buscar($parametros)
    {
        $lista = array();
        $concat = "nombre, descripcion";
        $datos = $this->modelo->where('estado', 1)->like($concat, $parametros['query']);

        foreach ($datos as $key => $value) {
            $text = $value['nombre'];
            $lista[] = array('id' => ($value['id']), 'text' => ($text) /*, 'data' => $value */);
        }

        return $lista;
    }
}