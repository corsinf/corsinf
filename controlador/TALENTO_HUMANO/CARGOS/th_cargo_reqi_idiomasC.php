<?php
date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 3)  . '/modelo/TALENTO_HUMANO/CARGOS/th_cargo_reqi_idiomasM.php');

$controlador = new th_cargo_reqi_idiomasC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar($_POST['id'] ?? '', $_POST['idioma_id'] ?? ''));
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

if (isset($_GET['buscar_idiomas'])) {
    $parametros = array(
        'query' => isset($_GET['q']) ? $_GET['q'] : '',
        'car_id' => isset($_GET['car_id']) ? $_GET['car_id'] : 0
    );

    $datos = $controlador->buscar_idiomas($parametros);
    echo json_encode($datos);
    exit;
}

class th_cargo_reqi_idiomasC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new th_cargo_reqi_idiomasM();
    }

    function listar_modal($id = '')
    {
        $datos = [];
        if ($id !== '') {
            $datos = $this->modelo->listar_cargo_idiomas(null, $id);
        }

        if (empty($datos)) {
            return
                <<<HTML
            <div class="d-flex align-items-center bg-light border-start border-secondary border-3 p-2 shadow-sm rounded-2">
              <i class='bx bx-info-circle me-2 text-secondary' style='font-size: 20px;'></i>
                <div class="lh-1">
                    <div class="text-dark fw-bold small">Sin idiomas registrados</div>
                    <div class="text-muted" style="font-size: 0.75rem;">No se han definido requisitos de idiomas.</div>
                </div>
            </div>
            HTML;
        }

        $texto = '<ul class="list-unstyled mb-0">';
        foreach ($datos as $value) {
            $id_reg = $value['_id'];
            $idioma = $value['idioma_descripcion'];
            $nivel = $value['nivel_descripcion'];

            $texto .= <<<HTML
        <li class="py-2 border-bottom">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <i class='bx bx-check-circle text-success me-2' style="font-size: 18px;"></i>
                    <div>
                        <span class="text-dark" style="font-size: 0.9rem;">{$idioma}</span>
                        <span class="badge bg-primary ms-2" style="font-size: 0.7rem;">{$nivel}</span>
                    </div>
                </div>
                <button type="button" class="btn btn-danger btn-sm py-0 px-2" onclick="delete_datos_idioma('{$id_reg}')" style="font-size: 0.75rem;">
                    <i class="bx bx-trash" style="font-size: 14px;"></i>
                </button>
            </div>
        </li>
    HTML;
        }
        $texto .= '</ul>';

        return $texto;
    }

    function listar($id = '', $idioma_id = '')
    {
        if ($id !== '') {
            $datos = $this->modelo->listar_cargo_idiomas(null, $id);
        }
        if ($idioma_id !== '') {
            $datos = $this->modelo->listar_cargo_idiomas($idioma_id);
        }
        return $datos;
    }

    function insertar_editar($parametros)
    {
        $id = $parametros['_id'];
        $datos = array(
            array('campo' => 'id_cargo', 'dato' =>  $parametros['id_cargo']),
            array('campo' => 'id_idiomas', 'dato' => $parametros['id_idiomas']),
            array('campo' => 'id_idiomas_nivel', 'dato' => $parametros['id_idiomas_nivel']),
            array('campo' => 'th_reqid_estado', 'dato' => 1),
            array('campo' => 'th_reqid_fecha_creacion', 'dato' => date('Y-m-d H:i:s')),
        );
        if ($id == '') {
            $datos = $this->modelo->insertar($datos);
        } else {
            $datos[] = ['campo' => 'th_reqid_fecha_modificacion', 'dato' => date('Y-m-d H:i:s')];
            $where[0]['campo'] = 'th_reqid_experiencia_id';
            $where[0]['dato'] = $id;
            $datos = $this->modelo->editar($datos, $where);
        }

        return $datos;
    }

    function eliminar($id)
    {
        $datos = array(
            array('campo' => 'th_reqid_experiencia_id', 'dato' => $id),
        );
        $datos = $this->modelo->eliminar($datos);
        return $datos;
    }

    public function buscar_idiomas($parametros)
    {
        $lista = [];

        $query = isset($parametros['query']) ? trim($parametros['query']) : '';
        $car_id = isset($parametros['car_id']) ? (int)$parametros['car_id'] : 0;

        if ($car_id <= 0) {
            return $lista;
        }

        $datos = $this->modelo->listar_idiomas_no_asignados($car_id);

        foreach ($datos as $item) {
            $texto = trim($item['descripcion']);

            if ($query === '' || stripos($texto, $query) !== false) {
                $lista[] = [
                    'id'   => $item['id_idiomas'],
                    'text' => $texto
                ];
            }
        }

        return $lista;
    }
}
