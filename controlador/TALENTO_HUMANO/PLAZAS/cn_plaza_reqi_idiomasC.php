<?php
date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 3) . '/modelo/TALENTO_HUMANO/PLAZAS/cn_plaza_reqi_idiomasM.php');

$controlador = new cn_plaza_reqi_idiomasC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar($_POST['id'] ?? '', $_POST['idioma_id'] ?? ''));
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

if (isset($_GET['buscar_idiomas'])) {
    $parametros = array(
        'query'  => isset($_GET['q'])      ? $_GET['q']      : '',
        'pla_id' => isset($_GET['pla_id']) ? $_GET['pla_id'] : 0
    );

    $datos = $controlador->buscar_idiomas($parametros);
    echo json_encode($datos);
    exit;
}



class cn_plaza_reqi_idiomasC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new cn_plaza_reqi_idiomasM();
    }

    function listar_modal($id = '', $button_delete = true)
    {
        $datos = [];
        if ($id !== '') {
            $datos = $this->modelo->listar_plaza_idiomas(null, $id);
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

        $texto = '<div class="border rounded bg-white shadow-sm overflow-hidden">';
        $texto .= '<table class="table table-hover table-sm mb-0 align-middle">';
        $texto .= '<tbody>';

        foreach ($datos as $value) {
            $id_reg = $value['_id'];
            $idioma = $value['idioma_descripcion'];
            $nivel  = $value['nivel_descripcion'];

            $button = '';
            if ($button_delete) {
                $button = <<<HTML
                                <td class="text-end pe-2 py-1" style="width: 40px;">
                                    <button type="button" 
                                            class="btn btn-outline-danger btn-xss py-0 px-2" 
                                            onclick="delete_datos_idioma('{$id_reg}')"
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
                                    <div class="d-flex align-items-center">
                                        <span class="text-dark fw-medium" style="font-size: 0.8rem; line-height: 1.2;">
                                            {$idioma}
                                        </span>
                                        <span class="badge bg-soft-primary text-primary border border-primary-subtle ms-2" style="font-size: 0.65rem; padding: 2px 5px;">
                                            {$nivel}
                                        </span>
                                    </div>
                                </td>
                                
                                {$button}
                                
                            </tr>
                    HTML;
        }

        $texto .= '</tbody></table></div>';

        return $texto;
    }

    function listar($id = '', $idioma_id = '')
    {
        if ($id !== '') {
            $datos = $this->modelo->listar_plaza_idiomas(null, $id);
        }
        if ($idioma_id !== '') {
            $datos = $this->modelo->listar_plaza_idiomas($idioma_id);
        }
        return $datos;
    }

    function insertar_editar($parametros)
    {
        $id = $parametros['_id'];
        $datos = array(
            array('campo' => 'cn_pla_id',              'dato' => $parametros['cn_pla_id']),
            array('campo' => 'id_idiomas',              'dato' => $parametros['id_idiomas']),
            array('campo' => 'id_idiomas_nivel',        'dato' => $parametros['id_idiomas_nivel']),
            array('campo' => 'cn_reqid_estado',         'dato' => 1),
            array('campo' => 'cn_reqid_fecha_creacion', 'dato' => date('Y-m-d H:i:s')),
        );
        if ($id == '') {
            $datos = $this->modelo->insertar($datos);
        } else {
            $datos[] = ['campo' => 'cn_reqid_fecha_modificacion', 'dato' => date('Y-m-d H:i:s')];
            $where[0]['campo'] = 'cn_reqid_experiencia_id';
            $where[0]['dato']  = $id;
            $datos = $this->modelo->editar($datos, $where);
        }

        return $datos;
    }

    function eliminar($id)
    {
        $datos = array(
            array('campo' => 'cn_reqid_experiencia_id', 'dato' => $id),
        );
        return $this->modelo->eliminar($datos);
    }

    public function buscar_idiomas($parametros)
    {
        $lista  = [];
        $query  = isset($parametros['query'])  ? trim($parametros['query'])  : '';
        $pla_id = isset($parametros['pla_id']) ? (int)$parametros['pla_id'] : 0;

        if ($pla_id <= 0) {
            return $lista;
        }

        $datos = $this->modelo->listar_idiomas_no_asignados($pla_id);

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