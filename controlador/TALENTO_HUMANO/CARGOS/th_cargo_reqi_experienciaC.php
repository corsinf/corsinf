<?php
date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 3) . '/modelo/TALENTO_HUMANO/CARGOS/th_cargo_reqi_experienciaM.php');

$controlador = new th_cargo_reqi_experienciaC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar($_POST['id'] ?? '', $_POST['car_id'] ?? ''));
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

if (isset($_GET['buscar_rango_profesional'])) {
    $parametros = [
        'query'  => $_GET['q']      ?? '',
        'car_id' => $_GET['car_id'] ?? 0,
        'pla_id' => $_GET['pla_id'] ?? 0,
    ];
    echo json_encode($controlador->buscar_rangos_profesionales($parametros));
    exit;
}


class th_cargo_reqi_experienciaC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new th_cargo_reqi_experienciaM();
    }

    function listar($id = '', $car_id = '')
    {
        if ($id !== '') {
            return $this->modelo->listar_cargo_experiencia($id);
        }
        if ($car_id !== '') {
            return $this->modelo->listar_cargo_experiencia('', $car_id);
        }
        return [];
    }

    function listar_modal($id = '', $button_delete = true)
    {
        $datos = $this->modelo->listar_cargo_experiencia('', $id);

        if (empty($datos)) {
            return [
                'html' => <<<HTML
                    <div class="d-flex align-items-center bg-light border-start border-secondary border-3 p-2 shadow-sm rounded-2">
                        <i class='bx bx-info-circle me-2 text-secondary' style='font-size: 20px;'></i>
                        <div class="lh-1">
                            <div class="text-dark fw-bold small">Sin experiencia registrada</div>
                            <div class="text-muted" style="font-size: 0.75rem;">No se ha definido el rango profesional.</div>
                        </div>
                    </div>
                HTML,
                'tiene_registros' => false
            ];
        }

        $texto  = '<div class="border rounded bg-white shadow-sm overflow-hidden">';
        $texto .= '<table class="table table-hover table-sm mb-0 align-middle"><tbody>';

        foreach ($datos as $value) {
            $id_reg      = $value['_id'];
            $nombre      = htmlspecialchars($value['rango_nombre']);
            $min         = $value['min_anios_exp'];
            $max         = $value['max_anios_exp'];
            $rango_texto = "($min - $max años)";

            $button = '';
            if ($button_delete) {
                $button = <<<HTML
                    <td class="text-end pe-2 py-1" style="width:40px;">
                        <button type="button" class="btn btn-outline-danger btn-xss py-0 px-2"
                                onclick="delete_datos_experiencia_necesaria('{$id_reg}')">
                            <i class="bx bx-trash fs-8"></i>
                        </button>
                    </td>
                HTML;
            }

            $texto .= <<<HTML
                <tr>
                    <td class="p-0" style="width:3px;background-color:#198754;opacity:0.6;"></td>
                    <td class="ps-2 py-1" style="width:30px;">
                        <div class="d-flex align-items-center justify-content-center bg-light rounded text-success" style="width:22px;height:22px;">
                            <i class='bx bx-check' style="font-size:14px;"></i>
                        </div>
                    </td>
                    <td class="py-1">
                        <div class="text-dark fw-medium" style="font-size:0.8rem;">
                            {$nombre} <span class="text-muted">{$rango_texto}</span>
                        </div>
                    </td>
                    {$button}
                </tr>
            HTML;
        }

        $texto .= '</tbody></table></div>';

        return ['html' => $texto, 'total_registros' => count($datos)];
    }

    function insertar_editar($parametros)
    {
        $id = $parametros['_id'];
        $datos = [
            ['campo' => 'id_cargo',            'dato' => $parametros['id_cargo']],
            ['campo' => 'id_rango_profesional', 'dato' => $parametros['id_rango_profesional']],
            ['campo' => 'th_reqe_estado',       'dato' => 1],
        ];

        if ($id == '') {
            $datos[] = ['campo' => 'th_reqe_fecha_creacion', 'dato' => date('Y-m-d H:i:s')];
            return $this->modelo->insertar($datos);
        } else {
            $datos[] = ['campo' => 'th_reqe_fecha_modificacion', 'dato' => date('Y-m-d H:i:s')];
            $where   = [['campo' => 'th_reqe_experiencia_id', 'dato' => $id]];
            return $this->modelo->editar($datos, $where);
        }
    }

    function eliminar($id)
    {
        return $this->modelo->eliminar([
            ['campo' => 'th_reqe_experiencia_id', 'dato' => $id]
        ]);
    }

    public function buscar_rangos_profesionales($parametros)
    {
        $lista  = [];
        $query  = trim($parametros['query'] ?? '');
        $car_id = (int)($parametros['car_id'] ?? 0);
        $pla_id = (int)($parametros['pla_id'] ?? 0);

        if ($car_id <= 0) return $lista;

        $datos = $this->modelo->listar_rangos_no_asignados($car_id, $pla_id);

        foreach ($datos as $item) {
            $texto = trim($item['nombre']) . ' (' . $item['min_anios_exp'] . ' - ' . $item['max_anios_exp'] . ' años)';
            if ($query === '' || stripos($texto, $query) !== false) {
                $lista[] = [
                    'id'   => $item['id_rango_profesional'],
                    'text' => $texto
                ];
            }
        }

        return $lista;
    }
}