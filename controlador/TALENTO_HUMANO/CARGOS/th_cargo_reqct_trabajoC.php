<?php
date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 3) . '/modelo/TALENTO_HUMANO/CARGOS/th_cargo_reqct_trabajoM.php');

$controlador = new th_cargo_reqct_trabajoC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar($_POST['id'] ?? '', $_POST['reqct_id'] ?? ''));
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

if (isset($_GET['buscar_trabajo'])) {
    $parametros = array(
        'car_id' => isset($_GET['car_id']) ? $_GET['car_id'] : 0,
        'query'  => isset($_GET['q'])      ? $_GET['q']      : ''
    );
    $datos = $controlador->buscar_trabajo_no_asignado($parametros);
    echo json_encode($datos);
    exit;
}

class th_cargo_reqct_trabajoC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new th_cargo_reqct_trabajoM();
    }

    function listar_modal($id = '', $button_delete = true)
    {
        $datos = [];
        if ($id !== '') {
            $datos = $this->modelo->listar_cargo_trabajo(null, $id);
        }

        if (empty($datos)) {
            return <<<HTML
                        <div class="d-flex align-items-center bg-light border-start border-secondary border-3 p-2 shadow-sm rounded-2">
                            <i class='bx bx-info-circle me-2 text-secondary' style='font-size: 20px;'></i>
                            <div class="lh-1">
                                <div class="text-dark fw-bold small">Sin condiciones de trabajo registradas</div>
                                <div class="text-muted" style="font-size: 0.75rem;">No se han definido condiciones de trabajo para este cargo.</div>
                            </div>
                        </div>
                    HTML;
        }

        $texto = '<div class="border rounded bg-white shadow-sm overflow-hidden">';
        $texto .= '<table class="table table-hover table-sm mb-0 align-middle">';
        $texto .= '<tbody>';

        foreach ($datos as $value) {
            $id_reg      = $value['_id'];
            $descripcion = $value['req_trabajo_descripcion'];

            $button = '';
            if ($button_delete) {
                $button = <<<HTML
                                <td class="text-end pe-2 py-1" style="width: 40px;">
                                    <button type="button" 
                                            class="btn btn-outline-danger btn-xss py-0 px-2" 
                                            onclick="delete_datos_trabajo('{$id_reg}')"
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

        return $texto;
    }

    function listar($id = '', $reqct_id = '')
    {
        if ($id !== '') {
            $datos = $this->modelo->listar_cargo_trabajo(null, $id);
        }
        if ($reqct_id !== '') {
            $datos = $this->modelo->listar_cargo_trabajo($reqct_id);
        }
        return $datos;
    }

    function insertar_editar($parametros)
    {
        $id = $parametros['_id'];
        $datos = array(
            array('campo' => 'id_cargo',       'dato' => $parametros['id_cargo']),
            array('campo' => 'id_req_trabajo',  'dato' => $parametros['id_req_trabajo']),
            array('campo' => 'th_reqct_estado', 'dato' => 1),
            array('campo' => 'th_reqct_fecha_creacion', 'dato' => date('Y-m-d H:i:s')),
        );
        if ($id == '') {
            $datos = $this->modelo->insertar($datos);
        } else {
            $datos[] = ['campo' => 'th_reqct_fecha_modificacion', 'dato' => date('Y-m-d H:i:s')];
            $where[0]['campo'] = 'th_reqct_id';
            $where[0]['dato']  = $id;
            $datos = $this->modelo->editar($datos, $where);
        }
        return $datos;
    }

    function eliminar($id)
    {
        $datos = array(
            array('campo' => 'th_reqct_id', 'dato' => $id),
        );
        return $this->modelo->eliminar($datos);
    }

    public function buscar_trabajo_no_asignado($parametros)
    {
        $lista  = [];
        $car_id = isset($parametros['car_id']) ? (int)$parametros['car_id'] : 0;
        $query  = isset($parametros['query'])  ? trim($parametros['query']) : '';

        if ($car_id <= 0) {
            return $lista;
        }

        $datos = $this->modelo->listar_trabajo_no_asignado($car_id, $query);
        foreach ($datos as $item) {
            $lista[] = [
                'id'   => $item['id_req_trabajo'],
                'text' => trim($item['descripcion'])
            ];
        }
        return $lista;
    }
}
