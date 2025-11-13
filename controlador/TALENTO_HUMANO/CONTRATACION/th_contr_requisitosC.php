<?php
date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 3)  . '/modelo/TALENTO_HUMANO/CONTRATACION/th_contr_requisitosM.php');

$controlador = new th_contr_requisitosC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar($_POST['id'] ?? ''));
}
if (isset($_GET['listar_requisito'])) {
    echo json_encode($controlador->listar_requisito($_POST['id'] ?? ''));
}

if (isset($_GET['insertar_editar'])) {
    echo json_encode($controlador->insertar_editar($_POST['parametros']));
}

if (isset($_GET['eliminar'])) {
    echo json_encode($controlador->eliminar($_POST['_id']));
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


class  th_contr_requisitosC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new th_contr_requisitosM();
    }

    function listar($id = '')
    {
       
        if($id == ''){

            $datos = $this->modelo->where('th_req_estado',1)->listar();

        }else{
             $datos = $this->modelo->where('th_pla_id',$id)->where('th_req_estado',1)->listar();
        }
       

        return $datos; 

    }

    function listar_requisito($id = '')
    {
        $datos = $this->modelo->where('th_req_id',$id)->where('th_req_estado',1)->listar();
        return $datos; 
    }

     function insertar_editar($parametros)
{
    $toInt = function ($v) { return ($v === '' || $v === null) ? null : (int)$v; };
    $toBoolInt = function ($v) { return ($v === 1 || $v === '1' || $v === true || $v === 'true') ? 1 : 0; };

    $datos = array(
        array('campo' => 'th_pla_id', 'dato' => $parametros['th_pla_id']),
        array('campo' => 'th_req_tipo', 'dato' => $parametros['th_req_tipo'] ?? ''),
        array('campo' => 'th_req_descripcion', 'dato' => $parametros['th_req_descripcion'] ?? ''),
        array('campo' => 'th_req_obligatorio', 'dato' => $toBoolInt($parametros['th_req_obligatorio'] ?? 0)),
        array('campo' => 'th_req_ponderacion', 'dato' => $toInt($parametros['th_req_ponderacion'] ?? 0)),
        // ⚠️ REMOVIDO th_req_estado porque no lo envías desde JavaScript
        array('campo' => 'th_req_fecha_modificacion', 'dato' => date('Y-m-d H:i:s')),
    );

    // Inserción
    if (empty($parametros['_id'])) {
        $datos[] = array('campo' => 'th_req_fecha_creacion', 'dato' => date('Y-m-d H:i:s'));
        // ⚠️ AGREGAR: estado inicial en 1 al crear
        $datos[] = array('campo' => 'th_req_estado', 'dato' => 1);
        
        $id = $this->modelo->insertar_id($datos);
        
        // ⚠️ IMPORTANTE: Asegurar que devuelve exactamente 1 o array con status
        if ($id) {
            return 1; // o return array('status' => 1, 'id' => $id);
        } else {
            return 0; // o return array('status' => 0, 'msg' => 'Error al insertar');
        }
    } else {
        // Edición
        $where = array(); // ⚠️ Inicializar array correctamente
        $where[0]['campo'] = 'th_req_id'; // ⚠️ Verificar que este sea el nombre correcto del campo ID
        $where[0]['dato']  = $parametros['_id'];
        
        $res = $this->modelo->editar($datos, $where);
        
        // ⚠️ IMPORTANTE: Normalizar respuesta
        return ($res) ? 1 : 0;
    }
}
    function eliminar($id)
    {
        // Soft delete: marcar estado = 0 y actualizar fecha
        $datos = array(
            array('campo' => 'th_req_estado', 'dato' => 0),
            array('campo' => 'th_req_fecha_modificacion', 'dato' => date('Y-m-d H:i:s')),
        );

        $where[0]['campo'] = 'th_req_id';
        $where[0]['dato'] = $id;
        $res = $this->modelo->editar($datos, $where);
        return $res;
    }


    //Para usar en select2
    function buscar($parametros)
    {

        
       
    }
}