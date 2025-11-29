<?php
date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 3)  . '/modelo/TALENTO_HUMANO/CONTRATACION/th_contr_cargosM.php');

$controlador = new th_contr_cargosC();

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

    $parametros = array(
        'query' => $query,
    );

    echo json_encode($controlador->buscar($parametros));
}


class  th_contr_cargosC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new th_contr_cargosM();
    }

    function listar($id = '')
    {
       
        $datos = $this->modelo->listar_cargos_con_departamentos($id);
        return $datos; 

    }

    function insertar_editar($parametros)
{
        $toInt = function ($v) { return ($v === '' || $v === null) ? null : (int)$v; };
        $th_dep_id = $toInt($parametros['ddl_departamentos'] ?? $parametros['ddl_departamentos'] ?? null);
        $th_niv_id = $toInt($parametros['ddl_niveles'] ?? $parametros['ddl_niveles'] ?? null);
    // Preparar datos comunes
    $datos = array(
        array('campo' => 'th_car_nombre', 'dato' => $parametros['txt_th_car_nombre'] ?? ''),
        array('campo' => 'th_car_descripcion', 'dato' => $parametros['txt_th_car_descripcion'] ?? ''),
        array('campo' => 'th_dep_id', 'dato' => $th_dep_id ?? ''),
        array('campo' => 'th_niv_id', 'dato' => $th_niv_id ?? ''),
        array('campo' => 'th_car_estado', 'dato' => isset($parametros['chk_th_car_estado']) ? ($parametros['chk_th_car_estado'] ? 1 : 0) : 1),
        array('campo' => 'th_car_fecha_modificacion', 'dato' => date('Y-m-d H:i:s')),
    );

    // Inserción
    if (empty($parametros['_id'])) {
        // Verificar que no exista otro cargo activo con el mismo nombre
        if (count($this->modelo->where('th_car_nombre', $parametros['txt_th_car_nombre'])->where('th_car_estado', 1)->listar()) == 0) {
            // agregar fecha de creación
            $datos[] = array('campo' => 'th_car_fecha_creacion', 'dato' => date('Y-m-d H:i:s'));

            // insertar y obtener id (si lo necesitas)
            $id = $this->modelo->insertar_id($datos);

            // devolver 1 para indicar éxito (coherente con tus respuestas JS)
            return 1;
        } else {
            return -2; // nombre duplicado
        }
    } else {
        // Edición: verificar que no exista otro registro con el mismo nombre
        if (count($this->modelo->where('th_car_nombre', $parametros['txt_th_car_nombre'])->where('th_car_id !', $parametros['_id'])->listar()) == 0) {
            $where[0]['campo'] = 'th_car_id';
            $where[0]['dato'] = $parametros['_id'];

            $res = $this->modelo->editar($datos, $where);
            return $res;
        } else {
            return -2; // nombre duplicado en otro registro
        }
    }
}


    function eliminar($id)
    {

         $datos = array(
            array('campo' => 'th_car_estado', 'dato' => 0),
        );

        $where[0]['campo'] = 'th_car_id';
        $where[0]['dato'] = $id;

        $datos = $this->modelo->editar($datos, $where);
        return $datos;
        
    }

    //Para usar en select2
    function buscar($parametros)
    {
        $lista = array();
        $concat = "th_car_nombre, th_car_estado";
        $datos = $this->modelo->where('th_car_estado', 1)->like($concat, $parametros['query']);

        foreach ($datos as $key => $value) {
            $text = $value['th_car_nombre'];
            $lista[] = array('id' => ($value['th_car_id']), 'text' => ($text), /* 'data' => $value */);
        }

        return $lista;
       
    }

    
}