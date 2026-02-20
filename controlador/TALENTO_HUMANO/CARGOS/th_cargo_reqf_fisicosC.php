<?php
date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 3) . '/modelo/TALENTO_HUMANO/CARGOS/th_cargo_reqf_fisicosM.php');

$controlador = new th_cargo_reqf_fisicosC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar($_POST['id'] ?? '', $_POST['reqf_id'] ?? ''));
}

if (isset($_GET['listar_modal'])) {
    $boton = !(isset($_POST['button_delete']) && $_POST['button_delete'] === 'false');
    echo json_encode($controlador->listar_modal($_POST['id'], $boton));
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
        'pla_id'        => isset($_GET['pla_id'])        ? $_GET['pla_id']        : 0,
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

    function listar_modal($id = '', $button_delete = true)
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

        $texto = '';

        foreach ($grupos as $cabecera => $items) {
            $texto .= <<<HTML
                            <div class="fw-bold text-primary mt-3 mb-1 d-flex align-items-center" style="font-size: 0.85rem; letter-spacing: 0.5px;">
                                <i class='bx bx-category me-2'></i>
                                <span class="text-uppercase">{$cabecera}</span>
                            </div>
                            <div class="border rounded bg-white shadow-sm overflow-hidden mb-3">
                                <table class="table table-hover table-sm mb-0 align-middle">
                                    <tbody>
                        HTML;

            foreach ($items as $item) {
                $id_reg      = $item['_id'];
                $descripcion = $item['req_fisico_det_descripcion'];

                $button = '';
                if ($button_delete) {
                    $button = <<<HTML
                                    <td class="text-end pe-2 py-1" style="width: 40px;">
                                        <button type="button" 
                                                class="btn btn-outline-danger btn-xss py-0 px-2" 
                                                onclick="delete_datos_reqf_fisico('{$id_reg}')"
                                                style="transition: all 0.2s;">
                                            <i class="bx bx-trash fs-8 me-0 icon-center-adjust"></i>
                                        </button>
                                    </td>
                                HTML;
                }

                $texto .= <<<HTML
                                <tr class="position-relative" style="transition: all 0.2s;">
                                    <td class="p-0" style="width: 3px; background-color: #198754; opacity: 0.6;"></td>
                                    
                                    <td class="ps-2 py-1" style="width: 30px;">
                                        <div class="d-flex align-items-center justify-content-center bg-light rounded text-success" style="width: 22px; height: 22px;">
                                            <i class='bx bx-check' style="font-size: 14px;"></i>
                                        </div>
                                    </td>
                                    
                                    <td class="py-1">
                                        <div class="text-dark fw-medium" style="font-size: 0.8rem; line-height: 1.2;">
                                            {$descripcion}
                                        </div>
                                    </td>
                                    
                                    {$button}

                                </tr>
                            HTML;
            }

            $texto .= '</tbody></table></div>';
        }

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
            array('campo' => 'id_cargo',          'dato' => $parametros['id_cargo']),
            array('campo' => 'id_req_fisico_det', 'dato' => $parametros['id_req_fisico_det']),
            array('campo' => 'th_reqf_estado',    'dato' => 1),
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
        $pla_id        = isset($parametros['pla_id'])        ? (int)$parametros['pla_id']        : 0;
        $id_req_fisico = isset($parametros['id_req_fisico']) ? (int)$parametros['id_req_fisico'] : 0;

        if ($car_id <= 0 || $id_req_fisico <= 0) {
            return $lista;
        }

        $datos = $this->modelo->listar_detalles_no_asignados($car_id, $id_req_fisico, $pla_id);
        foreach ($datos as $item) {
            $lista[] = [
                'id'   => $item['id_req_fisico_det'],
                'text' => trim($item['descripcion'])
            ];
        }
        return $lista;
    }
}