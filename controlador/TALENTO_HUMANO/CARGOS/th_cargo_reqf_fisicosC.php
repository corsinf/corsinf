<?php
date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 3) . '/modelo/TALENTO_HUMANO/CARGOS/th_cargo_reqf_fisicosM.php');

$controlador = new th_cargo_reqf_fisicosC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar($_POST['id'] ?? '', $_POST['reqf_id'] ?? ''));
}

if (isset($_GET['listar_modal'])) {
    echo json_encode($controlador->listar_modal($_POST['id']));
}

if (isset($_GET['insertar_editar'])) {
    echo json_encode($controlador->insertar_editar($_POST['parametros']));
}

if (isset($_GET['eliminar'])) {
    echo json_encode($controlador->eliminar($_POST['id']));
}

if (isset($_GET['buscar_req_fisicos_cabecera'])) {
    $datos = $controlador->buscar_req_fisicos_cabecera();
    echo json_encode($datos);
    exit;
}

if (isset($_GET['buscar_req_fisicos_detalle'])) {
    $parametros = array(
        'car_id'        => isset($_GET['car_id'])        ? $_GET['car_id']        : 0,
        'id_req_fisico' => isset($_GET['id_req_fisico']) ? $_GET['id_req_fisico'] : 0
    );
    $datos = $controlador->buscar_detalles_no_asignados($parametros);
    echo json_encode($datos);
    exit;
}

class th_cargo_reqf_fisicosC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new th_cargo_reqf_fisicosM();
    }

    function listar_modal($id = '')
    {
        $datos = [];
        if ($id !== '') {
            $datos = $this->modelo->listar_cargo_reqf_fisicos(null, $id);
        }

        if (empty($datos)) {
            return <<<HTML
            <div class="d-flex align-items-center bg-light border-start border-secondary border-3 p-2 shadow-sm rounded-2">
              <i class='bx bx-info-circle me-2 text-secondary' style='font-size: 20px;'></i>
                <div class="lh-1">
                    <div class="text-dark fw-bold small">Sin requisitos físicos registrados</div>
                    <div class="text-muted" style="font-size: 0.75rem;">No se han definido requisitos físicos para este cargo.</div>
                </div>
            </div>
            HTML;
        }

        $grupos = [];
        foreach ($datos as $value) {
            $grupos[$value['req_fisico_descripcion']][] = $value;
        }

        $texto = '<ul class="list-unstyled mb-0">';
        foreach ($grupos as $cabecera => $items) {
            $texto .= <<<HTML
            <li class="py-1">
                <div class="fw-bold text-primary" style="font-size: 0.85rem;">
                    <i class='bx bx-category me-1'></i>{$cabecera}
                </div>
                <ul class="list-unstyled ms-3 mb-0">
            HTML;
            foreach ($items as $item) {
                $id_reg      = $item['_id'];
                $descripcion = $item['req_fisico_det_descripcion'];
                $texto .= <<<HTML
                <li class="py-1 border-bottom">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <i class='bx bx-check-circle text-success me-2' style="font-size: 18px;"></i>
                            <span class="text-dark" style="font-size: 0.9rem;">{$descripcion}</span>
                        </div>
                        <button type="button" class="btn btn-danger btn-sm py-0 px-2" onclick="delete_datos_reqf_fisico('{$id_reg}')" style="font-size: 0.75rem;">
                            <i class="bx bx-trash" style="font-size: 14px;"></i>
                        </button>
                    </div>
                </li>
                HTML;
            }
            $texto .= '</ul></li>';
        }
        $texto .= '</ul>';

        return $texto;
    }

    function listar($id = '', $reqf_id = '')
    {
        if ($id !== '') {
            $datos = $this->modelo->listar_cargo_reqf_fisicos(null, $id);
        }
        if ($reqf_id !== '') {
            $datos = $this->modelo->listar_cargo_reqf_fisicos($reqf_id);
        }
        return $datos;
    }

    function insertar_editar($parametros)
    {
        $id = $parametros['_id'];
        $datos = array(
            array('campo' => 'id_cargo',         'dato' => $parametros['id_cargo']),
            array('campo' => 'id_req_fisico_det', 'dato' => $parametros['id_req_fisico_det']),
            array('campo' => 'th_reqf_estado',   'dato' => 1),
            array('campo' => 'th_reqf_fecha_creacion', 'dato' => date('Y-m-d H:i:s')),
        );
        if ($id == '') {
            $datos = $this->modelo->insertar($datos);
        } else {
            $datos[] = ['campo' => 'th_reqf_fecha_modificacion', 'dato' => date('Y-m-d H:i:s')];
            $where[0]['campo'] = 'th_reqf_id';
            $where[0]['dato']  = $id;
            $datos = $this->modelo->editar($datos, $where);
        }
        return $datos;
    }

    function eliminar($id)
    {
        $datos = array(
            array('campo' => 'th_reqf_id', 'dato' => $id),
        );
        return $this->modelo->eliminar($datos);
    }

    public function buscar_req_fisicos_cabecera()
    {
        $lista = [];
        $datos = $this->modelo->listar_req_fisicos_cabecera();
        foreach ($datos as $item) {
            $lista[] = [
                'id'   => $item['id_req_fisico'],
                'text' => trim($item['descripcion'])
            ];
        }
        return $lista;
    }

    public function buscar_detalles_no_asignados($parametros)
    {
        $lista         = [];
        $car_id        = isset($parametros['car_id'])        ? (int)$parametros['car_id']        : 0;
        $id_req_fisico = isset($parametros['id_req_fisico']) ? (int)$parametros['id_req_fisico'] : 0;

        if ($car_id <= 0 || $id_req_fisico <= 0) {
            return $lista;
        }

        $datos = $this->modelo->listar_detalles_no_asignados($car_id, $id_req_fisico);
        foreach ($datos as $item) {
            $lista[] = [
                'id'   => $item['id_req_fisico_det'],
                'text' => trim($item['descripcion'])
            ];
        }
        return $lista;
    }
}