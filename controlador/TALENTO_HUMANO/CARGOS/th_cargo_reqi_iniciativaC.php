<?php
date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 3)  . '/modelo/TALENTO_HUMANO/CARGOS/th_cargo_reqi_iniciativaM.php');

$controlador = new th_cargo_reqi_iniciativaC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar($_POST['id'] ?? ''));
}

if (isset($_GET['listar_modal'])) {
    echo json_encode($controlador->listar_modal($_POST['id']));
}

if (isset($_GET['insertar_editar'])) {
    echo json_encode($controlador->insertar_editar($_POST['parametros']));
}

if (isset($_GET['eliminar'])) {
    echo json_encode($controlador->eliminar($_POST['id_cargo'], $_POST['id_iniciativa']));
}

if (isset($_GET['buscar_iniciativas'])) {
    $parametros = array(
        'query' => isset($_GET['q']) ? $_GET['q'] : '',
        'car_id' => isset($_GET['car_id']) ? $_GET['car_id'] : 0
    );

    $datos = $controlador->buscar_iniciativas($parametros);
    echo json_encode($datos);
    exit;
}

class th_cargo_reqi_iniciativaC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new th_cargo_reqi_iniciativaM();
    }

    function listar_modal($id = '')
    {
        $datos = [];
        if ($id !== '') {
            $datos = $this->modelo->listar_cargo_iniciativas($id);
        }

        $total_registros = count($datos);

        if (empty($datos)) {
            return [
                'html' => <<<HTML
            <div class="d-flex align-items-center py-2 ps-2 border-start border-2 border-secondary bg-light-subtle rounded-1">
                <i class='bx bx-info-circle me-2 text-secondary' style="font-size: 16px;"></i>
                <div class="lh-1">
                    <div class="text-dark fw-bold" style="font-size: 0.8rem;">Sin iniciativas registradas</div>
                </div>
            </div>
HTML,
                'total' => $total_registros
            ];
        }

        $texto = '<ul class="list-unstyled mb-0">';
        foreach ($datos as $value) {
            $id_cargo = $value['id_cargo'];
            $id_iniciativa = $value['id_req_iniciativa'];
            $descripcion = $value['iniciativa_descripcion'];

            $texto .= <<<HTML
        <li class="py-2 border-bottom">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                   <i class='bx bx-check-circle text-success me-2' style="font-size: 18px;"></i>
                    <span class="text-dark" style="font-size: 0.9rem;">{$descripcion}</span>
                </div>
                <button type="button" class="btn btn-danger btn-sm py-0 px-2" onclick="delete_datos_iniciativa('{$id_cargo}', '{$id_iniciativa}')" style="font-size: 0.75rem;">
                    <i class="bx bx-trash" style="font-size: 14px;"></i>
                </button>
            </div>
        </li>
HTML;
        }
        $texto .= '</ul>';

        return [
            'html' => $texto,
            'total' => $total_registros
        ];
    }

    function listar($id = '')
    {
        if ($id !== '') {
            $datos = $this->modelo->listar_cargo_iniciativas($id);
        }
        return $datos;
    }

    function insertar_editar($parametros)
    {
        $datos = array(
            array('campo' => 'id_cargo', 'dato' =>  $parametros['id_cargo']),
            array('campo' => 'id_req_iniciativa', 'dato' => $parametros['id_req_iniciativa']),
            array('campo' => 'th_reqini_estado', 'dato' => 1),
            array('campo' => 'th_reqini_fecha_creacion', 'dato' => date('Y-m-d H:i:s')),
        );

        $datos = $this->modelo->insertar($datos);

        return $datos;
    }

    function eliminar($id_cargo, $id_iniciativa)
    {
        $datos = array(
            array('campo' => 'id_cargo', 'dato' => $id_cargo),
        );
        $datos = $this->modelo->eliminar($datos);
        return $datos;
    }

    public function buscar_iniciativas($parametros)
    {
        $lista = [];

        $query = isset($parametros['query']) ? trim($parametros['query']) : '';
        $car_id = isset($parametros['car_id']) ? (int)$parametros['car_id'] : 0;

        if ($car_id <= 0) {
            return $lista;
        }

        $datos = $this->modelo->listar_iniciativas_no_asignadas($car_id);

        foreach ($datos as $item) {
            $texto = trim($item['descripcion']);

            if ($query === '' || stripos($texto, $query) !== false) {
                $lista[] = [
                    'id'   => $item['id_req_iniciativa'],
                    'text' => $texto
                ];
            }
        }

        return $lista;
    }
}
