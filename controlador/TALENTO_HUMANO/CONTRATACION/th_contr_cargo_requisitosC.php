<?php
date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 3)  . '/modelo/TALENTO_HUMANO/CONTRATACION/th_contr_cargo_requisitosM.php');

$controlador = new th_contr_cargo_requisitosC();

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
    $parametros = array('query' => $query);
    echo json_encode($controlador->buscar($parametros));
}


if (isset($_GET['buscar_cargo_requisito'])) {
    $parametros = array(
        'query'  => isset($_GET['q']) ? $_GET['q'] : '',
        'car_id' => isset($_GET['car_id']) ? $_GET['car_id'] : 0
    );
    $datos = $controlador->buscar_cargo_requisito($parametros);
    echo json_encode($datos);
    exit;
}


class th_contr_cargo_requisitosC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new th_contr_cargo_requisitosM();
    }

  
    function listar($id = '')
    {
        if (!empty($id)) {
            $datos = $this->modelo->where('th_car_req_estado', 1)->where('th_car_req_id', $id)->listar();
            return $datos;
        } else {
            $datos = $this->modelo->where('th_car_req_estado', 1)->listar();
            return $datos;
        }
    }

    
    function insertar_editar($parametros)
    {

        // aceptar ambos nombres de campos para compatibilidad con vistas antiguas/nuevas
        $nombre = trim($parametros['th_car_req_nombre'] ?? $parametros['th_car_req_nombre'] ?? '');
        $descripcion = $parametros['th_car_req_descripcion'] ?? $parametros['th_car_req_descripcion'] ?? null;

        $datos = array(
            array('campo' => 'th_car_req_nombre', 'dato' => $nombre),
            array('campo' => 'th_car_req_descripcion', 'dato' => $descripcion),
            array('campo' => 'th_car_req_estado', 'dato' => 1),
            array('campo' => 'th_car_req_fecha_modificacion', 'dato' => date('Y-m-d H:i:s')),
        );

        // insertar
        if (empty($parametros['_id'])) {
            // validar duplicado por nombre (solo activos)
            if (count($this->modelo->where('th_car_req_nombre', $nombre)->where('th_car_req_estado', 1)->listar()) == 0) {
                $datos[] = array('campo' => 'th_car_req_fecha_creacion', 'dato' => date('Y-m-d H:i:s'));
                $id = $this->modelo->insertar_id($datos);
                return ($id) ? 1 : 0; // manteniendo convención: 1=ok, 0=falla
            } else {
                return -2; // nombre duplicado
            }
        } else {
            // editar: validar que no exista otro con mismo nombre
            if (count($this->modelo->where('th_car_req_nombre', $nombre)->where('th_car_req_id !', $parametros['_id'])->listar()) == 0) {
                $where[0]['campo'] = 'th_car_req_id';
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
            array('campo' => 'th_car_req_estado', 'dato' => 0),
            array('campo' => 'th_car_req_fecha_modificacion', 'dato' => date('Y-m-d H:i:s')),
        );

        $where[0]['campo'] = 'th_car_req_id';
        $where[0]['dato']  = $id;

        $res = $this->modelo->editar($datos, $where);
        return $res;
    }

   
    function buscar($parametros)
    {
        $lista = array();
        $concat = "th_car_req_nombre, th_car_req_descripcion";
        $datos = $this->modelo->where('th_car_req_estado', 1)->like($concat, $parametros['query']);

        foreach ($datos as $value) {
            $text = $value['th_car_req_nombre'];
            $lista[] = array('id' => $value['th_car_req_id'], 'text' => $text);
        }

        return $lista;
    }
    function buscar_cargo_requisito($parametros)
    {
        
    }
}