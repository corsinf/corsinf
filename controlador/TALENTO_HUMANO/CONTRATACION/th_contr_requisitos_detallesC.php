<?php
date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 3)  . '/modelo/TALENTO_HUMANO/CONTRATACION/th_cat_requisitos_detallesM.php');

$controlador = new th_cat_requisitos_detallesC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar($_POST['id'] ?? ''));
}

if (isset($_GET['insertar_editar'])) {
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
    // permite pasar opcionalmente th_req_id para filtrar por categoría
    $parametros = array(
        'query' => $query,
    );
    echo json_encode($controlador->buscar($parametros));
}


class th_cat_requisitos_detallesC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new th_cat_requisitos_detallesM();
    }

    /**
     * Listar
     * Si recibe $id devuelve solo ese registro, si no devuelve todos los activos.
     */
    function listar($id = '')
    {
        if (!empty($id)) {
            $datos = $this->modelo->where('id_requisitos_detalle', $id)->listar();
            return $datos;
        } else {
            // por defecto solo activos
            $datos = $this->modelo->where('estado', 1)->listar();
            return $datos;
        }
    }


    function insertar_editar($parametros)
    {
        $toInt = function ($v) {
            return ($v === '' || $v === null) ? null : (int)$v;
        };

        // Normalizar inputs
        $nombre = trim($parametros['txt_th_reqdet_nombre'] ?? '');
        $descripcion = $parametros['txt_th_reqdet_descripcion'] ?? null;
        $tipo_dato = $parametros['ddl_th_reqdet_tipo_dato'] ?? null;
        $obligatorio = isset($parametros['chk_th_reqdet_obligatorio']) ? ($parametros['chk_th_reqdet_obligatorio'] ? 1 : 0) : 0;
        $estado = isset($parametros['chk_th_reqdet_estado']) ? ($parametros['chk_th_reqdet_estado'] ? 1 : 0) : 1;

        // Preparar datos
        $datos = array(
            array('campo' => 'nombre', 'dato' => $nombre),
            array('campo' => 'descripcion', 'dato' => $descripcion),
            array('campo' => 'tipo_dato', 'dato' => $tipo_dato),
            array('campo' => 'obligatorio', 'dato' => $obligatorio),
            array('campo' => 'estado', 'dato' => $estado),
            array('campo' => 'fecha_modificacion', 'dato' => date('Y-m-d H:i:s')),
        );

        if (empty($parametros['_id'])) {
            // validar duplicado por nombre (solo activos)
            if (count($this->modelo->where('nombre', $nombre)->where('estado', 1)->listar()) == 0) {
                $datos[] = array('campo' => 'fecha_creacion', 'dato' => date('Y-m-d H:i:s'));
                $id = $this->modelo->insertar_id($datos);
                return ($id) ? 1 : 0; // manteniendo convención: 1=ok, 0=falla
            } else {
                return -2; // nombre duplicado
            }
        } else {
            // editar: validar que no exista otro con mismo nombre
            if (count($this->modelo->where('nombre', $nombre)->where('id_requisitos_detalle !', $parametros['_id'])->listar()) == 0) {
                $where[0]['campo'] = 'id_requisitos_detalle';
                $where[0]['dato']  = $parametros['_id'];
                $res = $this->modelo->editar($datos, $where);
                return $res;
            } else {
                return -2; // duplicado en otro registro
            }
        }

        return -2;
    }


    function eliminar($id)
    {
        $datos = array(
            array('campo' => 'estado', 'dato' => 0),
            array('campo' => 'fecha_modificacion', 'dato' => date('Y-m-d H:i:s')),
        );

        $where[0]['campo'] = 'id_requisitos_detalle';
        $where[0]['dato']  = $id;

        $res = $this->modelo->editar($datos, $where);
        return $res;
    }


    function buscar($parametros)
    {
        $lista = array();
        $concat = "nombre, descripcion";

        $query = $parametros['query'] ?? '';

        $builder = $this->modelo->where('estado', 1);

        $datos = $builder->like($concat, $query);

        foreach ($datos as $value) {
            $text = $value['nombre'];
            $lista[] = array('id' => $value['id_requisitos_detalle'], 'text' => $text);
        }

        return $lista;
    }
}
