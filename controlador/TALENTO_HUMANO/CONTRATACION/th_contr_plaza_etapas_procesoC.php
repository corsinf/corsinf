<?php
date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 3)  . '/modelo/TALENTO_HUMANO/CONTRATACION/th_contr_plaza_etapas_procesoM.php');

$controlador = new th_contr_plaza_etapas_procesoC();

// Rutas / endpoints
if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar($_POST['id'] ?? '', $_POST['pla_id'] ?? ''));
    exit;
}

if (isset($_GET['listar_etapas'])) {
    echo json_encode($controlador->listar_etapas($_POST['id'] ?? ''));
    exit;
}

if (isset($_GET['insertar_editar'])) {
    echo json_encode($controlador->insertar_editar($_POST['parametros'] ?? []));
    exit;
}

if (isset($_GET['eliminar'])) {
    echo json_encode($controlador->eliminar($_POST['id'] ?? ''));
    exit;
}

if (isset($_GET['buscar'])) {
    $parametros = array(
        'query'  => isset($_GET['q']) ? $_GET['q'] : '',
        'pla_id' => isset($_GET['pla_id']) ? $_GET['pla_id'] : 0
    );
    $datos = $controlador->buscar($parametros);
    echo json_encode($datos);
    exit;
}



class th_contr_plaza_etapas_procesoC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new th_contr_plaza_etapas_procesoM();
    }

   
    function listar($id = '', $pla_id = '')
    {
        if ($id !== '') {
            $datos = $this->modelo->where('th_pla_eta_id', $id)->listar();
        } elseif ($pla_id !== '') {
            $datos = $this->modelo->where('th_pla_id', $pla_id)->where('th_pla_eta_estado', 1)->listar();
        } else {
            $datos = $this->modelo->where('th_pla_eta_estado', 1)->listar();
        }

        return $datos;
    }

   
    function listar_etapas($pla_id = '')
    {
        return $this->modelo->listar_etapas_por_plaza($pla_id);
    }

    
    function insertar_editar($parametros)
    {
        if (!is_array($parametros)) return 0;

        $pla_id = $parametros['th_pla_id'] ?? ($parametros['pla_id'] ?? '');
        $eta_id = $parametros['th_eta_id'] ?? ($parametros['eta_id'] ?? '');

        if ($pla_id === '' || $eta_id === '') {
            return ['ok' => false, 'msg' => 'Faltan parámetros: pla_id o eta_id'];
        }

        $now = date('Y-m-d H:i:s');

        $datos = array(
            array('campo' => 'th_pla_id', 'dato' => $pla_id),
            array('campo' => 'th_eta_id', 'dato' => $eta_id),
            array('campo' => 'th_pla_eta_estado', 'dato' => 1),
            array('campo' => 'th_pla_eta_modificacion', 'dato' => $now)
        );

        // Inserción
        if (empty($parametros['_id'])) {
            $datos[] = array('campo' => 'th_pla_eta_fecha_creacion', 'dato' => $now);
            $id = $this->modelo->insertar_id($datos);
            // devolver 1 para mantener comportamiento similar a los controladores previos
            return $id ? 1 : 0;
        } else {
            // Edición
            $where = array();
            $where[0]['campo'] = 'th_pla_eta_id';
            $where[0]['dato'] = $parametros['_id'];
            $res = $this->modelo->editar($datos, $where);
            return $res;
        }
    }

    
    function eliminar($id)
    {
        $datos = array(
            array('campo' => 'th_pla_eta_estado', 'dato' => 0),
            array('campo' => 'th_pla_eta_modificacion', 'dato' => date('Y-m-d H:i:s'))
        );

        $where[0]['campo'] = 'th_pla_eta_id';
        $where[0]['dato'] = $id;

        $res = $this->modelo->editar($datos, $where);
        return $res;
    }

    
    function buscar($parametros)
    {
        $lista = [];

        $query = isset($parametros['query']) ? trim($parametros['query']) : '';
        $pla_id = isset($parametros['pla_id']) ? (int)$parametros['pla_id'] : 0;

        if ($pla_id <= 0) return $lista;

        $datos = $this->modelo->listar_etapas_no_asignadas($pla_id);

        foreach ($datos as $et) {
            $nombre = isset($et['th_etapa_nombre']) ? $et['th_etapa_nombre'] : '';
            $descripcion = isset($et['th_etapa_descripcion']) ? $et['th_etapa_descripcion'] : '';
        
        $textoCompleto = trim($nombre . ' - ' . $descripcion);
            if ($query === '' || stripos($textoCompleto, $query) !== false) {
                $lista[] = [
                    'id' => $et['th_etapa_id'],
                    'text' => $textoCompleto,
                    'data' => $et
                ];
            }
        }

        return $lista;
    }
}