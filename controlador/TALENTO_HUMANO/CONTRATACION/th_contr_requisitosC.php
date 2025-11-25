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
             $datos = $this->modelo->where('th_req_id',$id)->where('th_req_estado',1)->listar();
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
        array('campo' => 'th_req_tipo', 'dato' => $parametros['th_req_tipo'] ?? ''),
        array('campo' => 'th_req_descripcion', 'dato' => $parametros['th_req_descripcion'] ?? ''),
        array('campo' => 'th_req_obligatorio', 'dato' => $toBoolInt($parametros['th_req_obligatorio'] ?? 0)),
        array('campo' => 'th_req_ponderacion', 'dato' => $toInt($parametros['th_req_ponderacion'] ?? 0)),
        array('campo' => 'th_req_fecha_modificacion', 'dato' => date('Y-m-d H:i:s')),
    );

    // Inserción
    if (empty($parametros['_id'])) {
        $datos[] = array('campo' => 'th_req_fecha_creacion', 'dato' => date('Y-m-d H:i:s'));
        $datos[] = array('campo' => 'th_req_estado', 'dato' => 1);
        
        $id = $this->modelo->insertar_id($datos);
        
        if ($id) {
            return 1; 
        } else {
            return 0; 
        }
    } else {
        // Edición
        $where = array(); 
        $where[0]['campo'] = 'th_req_id'; 
        $where[0]['dato']  = $parametros['_id'];
        $res = $this->modelo->editar($datos, $where);
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


      function buscar($parametros)
{
    $lista = [];

    // query para filtrar (texto ingresado por el usuario)
    $query = isset($parametros['query']) ? trim($parametros['query']) : '';

    // obtener el id de la plaza desde parámetros (recomendable pasarlo)
    $pla_id = isset($parametros['pla_id']) ? intval($parametros['pla_id']) : 0;

    // si no hay plaza, devolvemos vacío (o podrías devolver todos los requisitos no asignados globalmente)
    if ($pla_id <= 0) {
        return $lista;
    }

    // pedimos al modelo los requisitos no asignados a la plaza
    $datos = $this->modelo->listar_requisitos_no_asignados($pla_id);

    foreach ($datos as $row) {
        // usar th_req_tipo como "título" (ajusta si prefieres otro campo)
        $titulo = isset($row['th_req_tipo']) ? $row['th_req_tipo'] : '';

        // comparar SOLO por el título (case-insensitive)
        if ($query === '' || stripos($titulo, $query) !== false) {
            $lista[] = [
                'id'   => $row['th_req_id'],
                'text' => $titulo,
                'data' => $row // opcional: incluir fila completa por si la UI la necesita
            ];
        }
    }

    return $lista;
}


}