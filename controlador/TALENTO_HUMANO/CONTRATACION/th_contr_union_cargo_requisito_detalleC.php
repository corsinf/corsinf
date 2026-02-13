<?php

/**
 * @deprecated Archivo dado de baja el 13/02/2026.
 * @note Este archivo se mantiene como respaldo, pero ya no se utilizará en producción.
 * @warning No modificar este archivo. Para cambios, referirse a la nueva implementación.
 */

date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 3)  . '/modelo/TALENTO_HUMANO/CONTRATACION/th_contr_union_cargo_requisito_detalleM.php');

$controlador = new th_contr_union_cargo_requisito_detalleC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar($_POST['id'] ?? ''));
    exit;
}

if (isset($_GET['insertar_editar'])) {
    echo json_encode($controlador->insertar_editar($_POST['parametros']));
    exit;
}

if (isset($_GET['eliminar'])) {
    echo json_encode($controlador->eliminar($_POST['id']));
    exit;
}

if (isset($_GET['buscar'])) {
    $parametros = array(
        'query' => isset($_GET['q']) ? $_GET['q'] : '',
        'th_car_req_id' => isset($_GET['th_car_req_id']) ? $_GET['th_car_req_id'] : 0
    );

    $datos = $controlador->buscar($parametros);
    echo json_encode($datos);
    exit;
}



class th_contr_union_cargo_requisito_detalleC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new th_contr_union_cargo_requisito_detalleM();
    }


    function listar($id = '')
    {
        $datos = $this->modelo->listar_detalles_asignados($id);
        return $datos;
    }


    function insertar_editar($parametros)
    {
        $ddl_requisito_detalle = $parametros['ddl_requisito_detalle'] ?? $parametros['ddl_requisito_detalle'] ?? null;
        // Preparar datos comunes
        $datos = array(
            array('campo' => 'th_car_req_id', 'dato' => $parametros['th_car_req_id'] ?? ''),
            array('campo' => 'th_reqdet_id', 'dato' => $ddl_requisito_detalle),
            array('campo' => 'th_req_reqdet_estado', 'dato' => 1),
            array('campo' => 'th_req_reqdet_fecha_modificacion', 'dato' => date('Y-m-d H:i:s')),
        );

        // Inserción
        if (empty($parametros['_id'])) {

            $datos[] = array('campo' => 'th_req_reqdet_fecha_creacion', 'dato' => date('Y-m-d H:i:s'));
            $res = $this->modelo->insertar_id($datos);

            return 1;
        } else {
            $where[0]['campo'] = 'th_req_reqdet_id';
            $where[0]['dato'] = $parametros['_id'];

            $res = $this->modelo->editar($datos, $where);
            return $res;
        }
    }


    function eliminar($id)
    {
        $datos = array(
            array('campo' => 'th_req_reqdet_estado', 'dato' => 0),
        );

        $where[0]['campo'] = 'th_req_reqdet_id';
        $where[0]['dato'] = $id;

        $datos = $this->modelo->editar($datos, $where);
        return $datos;
    }


    function buscar($parametros)
    {
        $lista = [];

        $query  = isset($parametros['query']) ? trim($parametros['query']) : '';
        $th_car_req_id = isset($parametros['th_car_req_id']) ? (int)$parametros['th_car_req_id'] : 0;

        if ($th_car_req_id <= 0) {
            return $lista;
        }

        // obtiene los requisitos NO asignados al cargo
        $datos = $this->modelo->listar_detalles_no_asignados($th_car_req_id);

        foreach ($datos as $req) {

            $nombre      = $req['nombre'] ?? '';
            $texto       = trim($nombre); // igual que tu ejemplo tipo-texto

            // filtro por búsqueda
            if ($query === '' || stripos($texto, $query) !== false) {
                $lista[] = [
                    'id'   => $req['id_requisitos_detalle'],
                    'text' => $texto
                ];
            }
        }

        return $lista;
    }
}
