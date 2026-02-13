<?php
date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 3)  . '/modelo/TALENTO_HUMANO/CARGOS/th_cargo_reqi_aptitudM.php');

$controlador = new th_cargo_reqi_aptitudC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar($_POST['id'] ?? '', $_POST['aptitud_id'] ?? ''));
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

if (isset($_GET['buscar_habilidades_tecnicas'])) {
    $parametros = array(
        'query' => isset($_GET['q']) ? $_GET['q'] : '',
        'car_id' => isset($_GET['car_id']) ? $_GET['car_id'] : 0
    );

    $datos = $controlador->buscar_habilidades_tecnicas($parametros);
    echo json_encode($datos);
    exit;
}
if (isset($_GET['buscar_habilidades_blandas'])) {
    $parametros = array(
        'query' => isset($_GET['q']) ? $_GET['q'] : '',
        'car_id' => isset($_GET['car_id']) ? $_GET['car_id'] : 0
    );

    $datos = $controlador->buscar_habilidades_blandas($parametros);
    echo json_encode($datos);
    exit;
}

class th_cargo_reqi_aptitudC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new th_cargo_reqi_aptitudM();
    }

    function listar_modal($id = '')
    {
        $datos = [];
        if ($id !== '') {
            $datos = $this->modelo->listar_cargo_aptitudes(null, $id);
        }

        // Grupos fijos — siempre se muestran los dos aunque estén vacíos
        $grupos = [
            'APTITUDES TÉCNICAS' => [
                'color' => 'text-primary',
                'icono' => 'bx-chip',
                'items' => []
            ],
            'HABILIDADES BLANDAS / INICIATIVA' => [
                'color' => 'text-primary',
                'icono' => 'bx-user-voice',
                'items' => []
            ],
        ];

        foreach ($datos as $value) {
            if ($value['th_tiph_id'] == 1) {
                $grupos['APTITUDES TÉCNICAS']['items'][] = $value;
            } else {
                $grupos['HABILIDADES BLANDAS / INICIATIVA']['items'][] = $value;
            }
        }

        // Si los dos grupos están vacíos → mensaje general
        $total = array_sum(array_map(fn($g) => count($g['items']), $grupos));
        if ($total === 0) {
            return <<<HTML
        <div class="d-flex align-items-center bg-light border-start border-secondary border-3 p-2 shadow-sm rounded-2">
            <i class='bx bx-info-circle me-2 text-secondary' style='font-size: 20px;'></i>
            <div class="lh-1">
                <div class="text-dark fw-bold small">Sin aptitudes registradas</div>
                <div class="text-muted" style="font-size: 0.75rem;">No se han definido habilidades requeridas.</div>
            </div>
        </div>
HTML;
        }

        $texto = '<ul class="list-unstyled mb-0">';

        foreach ($grupos as $cabecera => $config) {
            $color = $config['color'];
            $icono = $config['icono'];
            $items = $config['items'];

            // Cabecera del grupo
            $texto .= <<<HTML
        <li class="py-1">
            <div class="fw-bold {$color} mt-2 mb-1" style="font-size: 0.85rem;">
                <i class='bx {$icono} me-1'></i>{$cabecera}
            </div>
HTML;

            // Sin items en este grupo
            if (empty($items)) {
                $texto .= <<<HTML
            <div class="d-flex align-items-center bg-light border-start border-secondary border-3 p-2 ms-3 rounded-2 mb-1">
                <i class='bx bx-info-circle me-2 text-secondary' style='font-size: 16px;'></i>
                <span class="text-muted" style="font-size: 0.75rem;">No hay habilidades en esta categoría.</span>
            </div>
HTML;
            } else {
                $texto .= '<ul class="list-unstyled ms-3 mb-0">';
                foreach ($items as $item) {
                    $id_reg    = $item['_id'];
                    $habilidad = htmlspecialchars($item['habilidad_nombre']);

                    $texto .= <<<HTML
                <li class="py-1 border-bottom">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <i class='bx bx-check-circle text-success me-2' style="font-size: 18px;"></i>
                            <span class="text-dark" style="font-size: 0.9rem;">{$habilidad}</span>
                        </div>
                        <button type="button" class="btn btn-danger btn-sm py-0 px-2"
                            onclick="delete_datos_aptitud('{$id_reg}')"
                            style="font-size: 0.75rem;">
                            <i class="bx bx-trash" style="font-size: 14px;"></i>
                        </button>
                    </div>
                </li>
HTML;
                }
                $texto .= '</ul>';
            }

            $texto .= '</li>';
        }

        $texto .= '</ul>';

        return $texto;
    }

    function listar($id = '', $aptitud_id = '')
    {
        if ($id !== '') {
            $datos = $this->modelo->listar_cargo_aptitudes(null, $id);
        }
        if ($aptitud_id !== '') {
            $datos = $this->modelo->listar_cargo_aptitudes($aptitud_id);
        }
        return $datos;
    }

    function insertar_editar($parametros)
    {
        $id           = $parametros['_id'];
        $id_cargo     = $parametros['id_cargo'];
        $hab_tecnica  = $parametros['th_hab_id_tecnica'] ?? '';
        $hab_blanda   = $parametros['th_hab_id_blanda']  ?? '';

        // Si es edición (viene _id), actualizamos el registro puntual
        if ($id !== '') {
            // Detectamos cuál DDL viene con valor para editar
            $hab_id = ($hab_tecnica !== '') ? $hab_tecnica : $hab_blanda;

            $datos = array(
                array('campo' => 'id_cargo',                    'dato' => $id_cargo),
                array('campo' => 'th_hab_id',                   'dato' => $hab_id),
                array('campo' => 'th_reqa_estado',              'dato' => 1),
                array('campo' => 'th_reqa_fecha_modificacion',  'dato' => date('Y-m-d H:i:s')),
            );
            $where[0]['campo'] = 'th_reqa_experiencia_id';
            $where[0]['dato']  = $id;
            return $this->modelo->editar($datos, $where);
        }

        // Si es inserción, guardamos los que vengan (uno o ambos)
        $resultado = 0;

        if ($hab_tecnica !== '') {
            $datos = array(
                array('campo' => 'id_cargo',               'dato' => $id_cargo),
                array('campo' => 'th_hab_id',              'dato' => $hab_tecnica),
                array('campo' => 'th_reqa_estado',         'dato' => 1),
                array('campo' => 'th_reqa_fecha_creacion', 'dato' => date('Y-m-d H:i:s')),
            );
            $resultado = $this->modelo->insertar($datos);
        }

        if ($hab_blanda !== '') {
            $datos = array(
                array('campo' => 'id_cargo',               'dato' => $id_cargo),
                array('campo' => 'th_hab_id',              'dato' => $hab_blanda),
                array('campo' => 'th_reqa_estado',         'dato' => 1),
                array('campo' => 'th_reqa_fecha_creacion', 'dato' => date('Y-m-d H:i:s')),
            );
            $resultado = $this->modelo->insertar($datos);
        }

        // Retorna 1 si al menos una inserción fue exitosa
        return $resultado;
    }

    function eliminar($id)
    {
        $datos = array(
            array('campo' => 'th_reqa_experiencia_id', 'dato' => $id),
        );
        $datos = $this->modelo->eliminar($datos);
        return $datos;
    }

    public function buscar_habilidades_tecnicas($parametros)
    {
        $lista = [];

        $query = isset($parametros['query']) ? trim($parametros['query']) : '';
        $car_id = isset($parametros['car_id']) ? (int)$parametros['car_id'] : 0;

        if ($car_id <= 0) {
            return $lista;
        }

        $datos = $this->modelo->listar_habilidades_tenicas_no_asignadas($car_id);

        foreach ($datos as $item) {
            $texto = trim($item['th_hab_nombre']);

            if ($query === '' || stripos($texto, $query) !== false) {
                $lista[] = [
                    'id'   => $item['th_hab_id'],
                    'text' => $texto
                ];
            }
        }

        return $lista;
    }
    public function buscar_habilidades_blandas($parametros)
    {
        $lista = [];

        $query = isset($parametros['query']) ? trim($parametros['query']) : '';
        $car_id = isset($parametros['car_id']) ? (int)$parametros['car_id'] : 0;

        if ($car_id <= 0) {
            return $lista;
        }

        $datos = $this->modelo->listar_habilidades_blandas_no_asignadas($car_id);

        foreach ($datos as $item) {
            $texto = trim($item['th_hab_nombre']);

            if ($query === '' || stripos($texto, $query) !== false) {
                $lista[] = [
                    'id'   => $item['th_hab_id'],
                    'text' => $texto
                ];
            }
        }

        return $lista;
    }
}
