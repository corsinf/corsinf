<?php
date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 3) . '/modelo/TALENTO_HUMANO/PLAZAS/cn_plaza_reqi_experienciaM.php');

$controlador = new cn_plaza_reqi_experienciaC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar($_POST['id'] ?? '', $_POST['pla_id'] ?? ''));
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


class cn_plaza_reqi_experienciaC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new cn_plaza_reqi_experienciaM();
    }

    function listar($id = '', $pla_id = '')
    {
        if ($id !== '') {
            return $this->modelo->listar_plaza_experiencia($id);
        }
        if ($pla_id !== '') {
            return $this->modelo->listar_plaza_experiencia('', $pla_id);
        }
        return [];
    }

    function listar_modal($id = '', $button_delete = true)
    {
        $datos = $this->modelo->listar_plaza_experiencia('', $id);

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
            ['campo' => 'cn_pla_id',            'dato' => $parametros['cn_pla_id']],
            ['campo' => 'id_rango_profesional',  'dato' => $parametros['id_rango_profesional']],
            ['campo' => 'cn_reqe_estado',        'dato' => 1],
        ];

        if ($id == '') {
            $datos[] = ['campo' => 'cn_reqe_fecha_creacion', 'dato' => date('Y-m-d H:i:s')];
            return $this->modelo->insertar($datos);
        } else {
            $datos[] = ['campo' => 'cn_reqe_fecha_modificacion', 'dato' => date('Y-m-d H:i:s')];
            $where   = [['campo' => 'cn_reqe_experiencia_id', 'dato' => $id]];
            return $this->modelo->editar($datos, $where);
        }
    }

    function eliminar($id)
    {
        return $this->modelo->eliminar([
            ['campo' => 'cn_reqe_experiencia_id', 'dato' => $id]
        ]);
    }
}
