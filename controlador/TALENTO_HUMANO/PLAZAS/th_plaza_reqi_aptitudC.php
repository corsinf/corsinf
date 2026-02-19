<?php
date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 3) . '/modelo/TALENTO_HUMANO/PLAZAS/th_plaza_reqi_aptitudM.php');

$controlador = new th_plaza_reqi_aptitudC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar($_POST['id'] ?? '', $_POST['aptitud_id'] ?? ''));
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

if (isset($_GET['buscar_habilidades_tecnicas'])) {
    $parametros = array(
        'query'  => isset($_GET['q'])      ? $_GET['q']      : '',
        'pla_id' => isset($_GET['pla_id']) ? $_GET['pla_id'] : 0
    );

    $datos = $controlador->buscar_habilidades_tecnicas($parametros);
    echo json_encode($datos);
    exit;
}

if (isset($_GET['buscar_habilidades_blandas'])) {
    $parametros = array(
        'query'  => isset($_GET['q'])      ? $_GET['q']      : '',
        'pla_id' => isset($_GET['pla_id']) ? $_GET['pla_id'] : 0
    );

    $datos = $controlador->buscar_habilidades_blandas($parametros);
    echo json_encode($datos);
    exit;
}



class th_plaza_reqi_aptitudC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new th_plaza_reqi_aptitudM();
    }

    function listar_modal($id = '', $button_delete = true)
    {
        $datos = [];
        if ($id !== '') {
            $datos = $this->modelo->listar_plaza_aptitudes(null, $id);
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

        $texto = '';

        foreach ($grupos as $cabecera => $config) {
            $color = $config['color'];
            $icono = $config['icono'];
            $items = $config['items'];

            $texto .= <<<HTML
                            <div class="fw-bold {$color} mt-3 mb-1 d-flex align-items-center" style="font-size: 0.85rem; letter-spacing: 0.5px;">
                                <i class='bx {$icono} me-2'></i>
                                <span class="text-uppercase">{$cabecera}</span>
                            </div>
                            <div class="border rounded bg-white shadow-sm overflow-hidden mb-3">
                                <table class="table table-hover table-sm mb-0 align-middle">
                                    <tbody>
                        HTML;

            if (empty($items)) {
                $texto .= <<<HTML
                                <tr>
                                    <td class="p-0" style="width: 3px; background-color: #6c757d; opacity: 0.5;"></td>
                                    <td class="py-2 ps-3">
                                        <div class="d-flex align-items-center text-muted" style="font-size: 0.75rem;">
                                            <i class='bx bx-info-circle me-2'></i>
                                            No hay habilidades en esta categoría.
                                        </div>
                                    </td>
                                </tr>
                            HTML;
            } else {
                foreach ($items as $item) {
                    $id_reg    = $item['_id'];
                    $habilidad = htmlspecialchars($item['habilidad_nombre']);

                    $button = '';
                    if ($button_delete) {
                        $button = <<<HTML
                                        <td class="text-end pe-2 py-1" style="width: 40px;">
                                            <button type="button" 
                                                    class="btn btn-outline-danger btn-xss py-0 px-2" 
                                                    onclick="delete_datos_aptitud('{$id_reg}')"
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
                                                {$habilidad}
                                            </div>
                                        </td>
                                        
                                        {$button}
                                        
                                    </tr>
                                HTML;
                }
            }

            $texto .= '</tbody></table></div>';
        }

        return $texto;
    }

    function listar($id = '', $aptitud_id = '')
    {
        if ($id !== '') {
            $datos = $this->modelo->listar_plaza_aptitudes(null, $id);
        }
        if ($aptitud_id !== '') {
            $datos = $this->modelo->listar_plaza_aptitudes($aptitud_id);
        }
        return $datos;
    }

    function insertar_editar($parametros)
    {
        $id          = $parametros['_id'];
        $cn_pla_id   = $parametros['cn_pla_id'];
        $hab_tecnica = $parametros['cn_hab_id_tecnica'] ?? '';
        $hab_blanda  = $parametros['cn_hab_id_blanda']  ?? '';

        // Si es edición, actualizamos el registro puntual
        if ($id !== '') {
            $hab_id = ($hab_tecnica !== '') ? $hab_tecnica : $hab_blanda;

            $datos = array(
                array('campo' => 'cn_pla_id',                   'dato' => $cn_pla_id),
                array('campo' => 'cn_hab_id',                   'dato' => $hab_id),
                array('campo' => 'cn_reqa_estado',              'dato' => 1),
                array('campo' => 'cn_reqa_fecha_modificacion',  'dato' => date('Y-m-d H:i:s')),
            );
            $where[0]['campo'] = 'cn_reqa_experiencia_id';
            $where[0]['dato']  = $id;
            return $this->modelo->editar($datos, $where);
        }

        // Si es inserción, guardamos los que vengan (uno o ambos)
        $resultado = 0;

        if ($hab_tecnica !== '') {
            $datos = array(
                array('campo' => 'cn_pla_id',               'dato' => $cn_pla_id),
                array('campo' => 'cn_hab_id',               'dato' => $hab_tecnica),
                array('campo' => 'cn_reqa_estado',          'dato' => 1),
                array('campo' => 'cn_reqa_fecha_creacion',  'dato' => date('Y-m-d H:i:s')),
            );
            $resultado = $this->modelo->insertar($datos);
        }

        if ($hab_blanda !== '') {
            $datos = array(
                array('campo' => 'cn_pla_id',               'dato' => $cn_pla_id),
                array('campo' => 'cn_hab_id',               'dato' => $hab_blanda),
                array('campo' => 'cn_reqa_estado',          'dato' => 1),
                array('campo' => 'cn_reqa_fecha_creacion',  'dato' => date('Y-m-d H:i:s')),
            );
            $resultado = $this->modelo->insertar($datos);
        }

        return $resultado;
    }

    function eliminar($id)
    {
        $datos = array(
            array('campo' => 'cn_reqa_experiencia_id', 'dato' => $id),
        );
        return $this->modelo->eliminar($datos);
    }

    public function buscar_habilidades_tecnicas($parametros)
    {
        $lista  = [];
        $query  = isset($parametros['query'])  ? trim($parametros['query'])  : '';
        $pla_id = isset($parametros['pla_id']) ? (int)$parametros['pla_id'] : 0;

        if ($pla_id <= 0) return $lista;

        $datos = $this->modelo->listar_habilidades_tenicas_no_asignadas($pla_id);

        foreach ($datos as $item) {
            $texto = trim($item['th_hab_nombre']);
            if ($query === '' || stripos($texto, $query) !== false) {
                $lista[] = ['id' => $item['th_hab_id'], 'text' => $texto];
            }
        }

        return $lista;
    }

    public function buscar_habilidades_blandas($parametros)
    {
        $lista  = [];
        $query  = isset($parametros['query'])  ? trim($parametros['query'])  : '';
        $pla_id = isset($parametros['pla_id']) ? (int)$parametros['pla_id'] : 0;

        if ($pla_id <= 0) return $lista;

        $datos = $this->modelo->listar_habilidades_blandas_no_asignadas($pla_id);

        foreach ($datos as $item) {
            $texto = trim($item['th_hab_nombre']);
            if ($query === '' || stripos($texto, $query) !== false) {
                $lista[] = ['id' => $item['th_hab_id'], 'text' => $texto];
            }
        }

        return $lista;
    }
}