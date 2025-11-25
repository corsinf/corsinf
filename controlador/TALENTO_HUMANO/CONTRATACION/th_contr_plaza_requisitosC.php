<?php
date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 3)  . '/modelo/TALENTO_HUMANO/CONTRATACION/th_contr_plaza_requisitosM.php');

$controlador = new th_contr_plaza_requisitosC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar($_POST['id'] ?? '', $_POST['pla_id'] ?? ''));
}

if (isset($_GET['listar_requisitos'])) {
    echo json_encode($controlador->listar_requisitos($_POST['id'] ?? ''));
}

if (isset($_GET['insertar_editar'])) {
    echo json_encode($controlador->insertar_editar($_POST['parametros']));
}

if (isset($_GET['eliminar'])) {
    echo json_encode($controlador->eliminar($_POST['id']));
}

if (isset($_GET['buscar'])) {
    $parametros = array(
        'query' => isset($_GET['q']) ? $_GET['q'] : '',
        'pla_id' => isset($_GET['pla_id']) ? $_GET['pla_id'] : 0
    );
    
    $datos = $controlador->buscar($parametros);
    echo json_encode($datos);
    exit;
}


class th_contr_plaza_requisitosC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new th_contr_plaza_requisitosM();
    }
   
    function listar($id = '', $pla_id = '')
    {
        if ($id !== '') {
            // buscar por PK th_pr_id
            $datos = $this->modelo->where('th_pr_id', $id)->listar();
        } elseif ($pla_id !== '') {
            // buscar por plaza
            $datos = $this->modelo->where('th_pla_id', $pla_id)->where('th_car_estado', 1)->listar();
        } else {
            // todas las asociaciones activas
            $datos = $this->modelo->where('th_car_estado', 1)->listar();
        }

        return $datos;
    }
    function listar_requisitos($pla_id = '')
    {
        $datos = $this->modelo->listar_requisitos_por_plaza($pla_id);
        
        return $datos;
    }

    
    function insertar_editar($parametros)
    {
        if (!is_array($parametros)) return 0;

        $pla_id = $parametros['th_pla_id'] ?? ($parametros['pla_id'] ?? '');
        $req_id = $parametros['th_req_id'] ?? ($parametros['req_id'] ?? '');

        if ($pla_id === '' || $req_id === '') {
            return ['ok' => false, 'msg' => 'Faltan parámetros: pla_id o req_id'];
        }

        // preparar datos
        $datos = array(
            array('campo' => 'th_pla_id', 'dato' => $pla_id),
            array('campo' => 'th_req_id', 'dato' => $req_id),
            array('campo' => 'th_car_estado', 'dato' => 1),
            array('campo' => 'th_pr_fecha_modificacion', 'dato' => date('Y-m-d H:i:s'))
        );

        // insertar
        if (empty($parametros['_id'])) {
           
            $datos[] = array('campo' => 'th_pr_fecha_creacion', 'dato' => date('Y-m-d H:i:s'));
            $id = $this->modelo->insertar_id($datos);
            // retornar id insertado (coherente con tus otros controladores)
            return $id ? 1 : 0;
           
        } else {
            // edición
            $where = array();
            $where[0]['campo'] = 'th_pr_id';
            $where[0]['dato'] = $parametros['_id'];
            $res = $this->modelo->editar($datos, $where);
            return $res;
            
        }
    }

    function eliminar($id)
    {
        $datos = array(
            array('campo' => 'th_car_estado', 'dato' => 0),
            array('campo' => 'th_pr_fecha_modificacion', 'dato' => date('Y-m-d H:i:s'))
        );

        $where[0]['campo'] = 'th_pr_id';
        $where[0]['dato'] = $id;

        $res = $this->modelo->editar($datos, $where);
        return $res;
    }

   
   function buscar($parametros)
{
    $lista = [];

    $query = isset($parametros['query']) ? trim($parametros['query']) : '';
    $pla_id = isset($parametros['pla_id']) ? (int)$parametros['pla_id'] : 0;

    if ($pla_id <= 0) {
        return $lista; // 
    }

    $datos = $this->modelo->listar_requisitos_no_asignados($pla_id);

    foreach ($datos as $requisito) {
        $descripcion = isset($requisito['th_req_descripcion']) ? $requisito['th_req_descripcion'] : '';
        $tipo = isset($requisito['th_req_tipo']) ? $requisito['th_req_tipo'] : '';
        $textoCompleto = trim($tipo . ' - ' . $descripcion);
        if ($query === '' || stripos($textoCompleto, $query) !== false) {
            $lista[] = [
                'id'   => $requisito['th_req_id'],
                'text' => $textoCompleto,
            ];
        }
    }
    return $lista;
}
}