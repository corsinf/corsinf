<?php
date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 3)  . '/modelo/TALENTO_HUMANO/CONTRATACION/th_contr_cargo_aspectos_intrinsecosM.php');

$controlador = new th_contr_cargo_aspectos_intrinsecosC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar($_POST['id'] ?? ''));
}

if (isset($_GET['listar_aspecto_cargo'])) {
    echo json_encode($controlador->listar_aspecto_cargo($_POST['id'] ?? ''));
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


class th_contr_cargo_aspectos_intrinsecosC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new th_contr_cargo_aspectos_intrinsecosM();
    }

    function listar($id = '')
    {
       
        if ($id == '') {
            $datos = $this->modelo->listar(); // listar todos
        } else {
            $datos = $this->modelo->listar();
        }

        return $datos;
    }
    function listar_aspecto_cargo($id = '')
    {
       
        if ($id == '') {
            $datos = $this->modelo->listar(); // listar todos
        } else {
            $datos = $this->modelo->listar_aspecto_cargo_completo($id);
        }

        return $datos;
    }

    
    function insertar_editar($parametros)
{
    $toInt = function ($v) { 
        return ($v === '' || $v === null) ? null : (int)$v; 
    };

    $toText = function ($v) {
        if ($v === '' || $v === null) return null;
        $clean = trim($v);
        return $clean === '' ? null : $clean;
    };

    $th_car_id = $toInt($parametros['ddl_th_car_id'] ?? ($parametros['th_car_id'] ?? null));
    $nivel_cargo = $parametros['txt_th_carasp_nivel_cargo'] ?? ($parametros['th_carasp_nivel_cargo'] ?? null);
    $subordinacion_id = $toInt($parametros['th_carasp_subordinacion_id'] ?? null);
    $subordinacion_texto = $toText($parametros['txt_th_carasp_subordinacion'] ?? ($parametros['th_carasp_subordinacion'] ?? null));
    if ($subordinacion_id !== null) {
        $subordinacion_texto = null;
    } else {
        $subordinacion_id = null;
    }
   
    $supervision_id = $toInt($parametros['th_carasp_supervision_id'] ?? null);
    $supervision_texto = $toText($parametros['txt_th_carasp_supervision'] ?? ($parametros['th_carasp_supervision'] ?? null));
    if ($supervision_id !== null) {
        $supervision_texto = null;
    } else {
        $supervision_id = null;
    }
   
    $comunicaciones_id = $toInt($parametros['th_carasp_comunicaciones_id'] ?? null);
    $comunicaciones_texto = $toText($parametros['txt_th_carasp_comunicaciones_colaterales'] ?? ($parametros['th_carasp_comunicaciones_colaterales'] ?? null));
    if ($comunicaciones_id !== null) {
        $comunicaciones_texto = null;
    } else {
        $comunicaciones_id = null;
    }
    $datos = array(
        array('campo' => 'th_car_id', 'dato' => $th_car_id),
        array('campo' => 'th_carasp_nivel_cargo', 'dato' => $nivel_cargo),
        array('campo' => 'th_carasp_subordinacion', 'dato' => $subordinacion_texto),
        array('campo' => 'th_carasp_subordinacion_id', 'dato' => $subordinacion_id),
        array('campo' => 'th_carasp_supervision', 'dato' => $supervision_texto),
        array('campo' => 'th_carasp_supervision_id', 'dato' => $supervision_id),
        array('campo' => 'th_carasp_comunicaciones_colaterales', 'dato' => $comunicaciones_texto),
        array('campo' => 'th_carasp_comunicaciones_id', 'dato' => $comunicaciones_id),
        array('campo' => 'th_carasp_estado', 'dato' => 1),
        array('campo' => 'th_carasp_fecha_modificacion', 'dato' => date('Y-m-d H:i:s')),
    );

   
    if (empty($parametros['_id'])) {
       
            $datos[] = array('campo' => 'th_carasp_fecha_creacion', 'dato' => date('Y-m-d H:i:s'));
            
            $id = $this->modelo->insertar_id($datos);
            
            return $id ? 1 : -2;
    } 
  
    else {
       
            $where = array(
                array('campo' => 'th_carasp_id', 'dato' => $parametros['_id'])
            );
            $res = $this->modelo->editar($datos, $where);
        
            return $res;
    }
}

    
    function eliminar($id)
    {
        $datos = array(
            array('campo' => 'th_carasp_estado', 'dato' => 0),
        );

        $where[0]['campo'] = 'th_carasp_id';
        $where[0]['dato']  = $id;

        $res = $this->modelo->editar($datos, $where);
        return $res;
    }

   
    function buscar($parametros)
    {
        
    }
}