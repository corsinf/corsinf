<?php
require_once(dirname(__DIR__, 2) . '/modelo/TALENTO_HUMANO/th_per_parientesM.php');

$controlador = new th_per_parientesC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar($_POST['id']));
}

if (isset($_GET['listar_modal'])) {
    echo json_encode($controlador->listar_modal($_POST['id']));
}

if (isset($_GET['insertar'])) {
    echo json_encode($controlador->insertar_editar($_POST['parametros']));
}

if (isset($_GET['eliminar'])) {
    echo json_encode($controlador->eliminar($_POST['id']));
}


if (isset($_GET['buscar'])) {
    $query = '';
    $th_per_id = '';
    if (isset($_GET['q'])) {
        $query = $_GET['q'];
    }
    if (isset($_GET['th_per_id'])) {
        $th_per_id = $_GET['th_per_id'];
    }
    $parametros = array(
        'th_per_id' => $th_per_id,
        'query' => $query,
    );

    echo json_encode($controlador->buscar_parientes_persona($parametros));
}

class th_per_parientesC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new th_per_parientesM();
    }

    function listar($id)
    {
        $datos = $this->modelo->listar_parientes_por_persona($id);
        $texto = '';

        foreach ($datos as $value) {
            // Es de emergencia?
            $es_emergencia = ($value['contacto_emergencia'] == 1);

            $badge_emergencia = $es_emergencia
                ? '<span class="badge rounded-pill bg-danger-subtle text-danger border border-danger-subtle ms-1" style="font-size: 0.6rem;">S.O.S</span>'
                : '';

            // Lógica de teléfono y botón de llamada
            $telefono_limpio = preg_replace('/[^0-9+]/', '', $value['numero_telefono']); // Limpia espacios/guiones para el enlace tel:
            $btn_llamar = '';

            if ($es_emergencia && !empty($telefono_limpio)) {
                $btn_llamar = <<<HTML
                                    <a href="tel:{$telefono_limpio}" class="btn btn-sm btn-call-emergency me-1" title="Llamar ahora">
                                        <i class="bx bxs-phone-call"></i>
                                    </a>
                                HTML;
            }

            // Edad (Simplificada)
            $edad_texto = '';
            if (!empty($value['fecha_nacimiento']) && !str_contains($value['fecha_nacimiento'], '1900-01-01')) {
                $fecha_nac = new DateTime($value['fecha_nacimiento']);
                $hoy = new DateTime();
                $edad_texto = " <span class='text-muted fw-normal small'>({$hoy->diff($fecha_nac)->y} años)</span>";
            }

            $texto .= <<<HTML
                            <div class="row align-items-center py-2 border-bottom g-0 item-pariente">
                                <div class="col-auto me-2">
                                    <div class="avatar-mini bg-light rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bx bx-user text-secondary"></i>
                                    </div>
                                </div>

                                <div class="col overflow-hidden">
                                    <div class="d-flex align-items-center mb-0">
                                        <span class="fw-bold text-dark text-truncate small">
                                            {$value['nombres']} {$value['apellidos']}
                                        </span>
                                        {$badge_emergencia}
                                    </div>
                                    <div class="d-flex align-items-center gap-2">
                                        <small class="text-primary fw-semibold" style="font-size: 0.7rem; text-uppercase;">
                                            {$value['parentesco_nombre']}
                                        </small>
                                        <small class="text-muted" style="font-size: 0.75rem;">{$value['numero_telefono']}{$edad_texto}</small>
                                    </div>
                                </div>

                                <div class="col-auto d-flex align-items-center">
                                    {$btn_llamar}
                                    <button class="btn btn-sm btn-edit-minimal" 
                                            onclick="abrir_modal_pariente('{$value['_id']}');"
                                            title="Editar">
                                        <i class="bx bx-pencil"></i>
                                    </button>
                                </div>
                            </div>
                        HTML;
        }

        if (empty($datos)) {
            $texto = '<div class="alert alert-info">No hay registros de parientes.</div>';
        }

        return $texto;
    }

    function listar_modal($id)
    {
        return $this->modelo->listar_pariente_por_id($id);
    }

    function insertar_editar($parametros)
    {
        $id_parentesco = $parametros['ddl_parentesco'];
        $per_id = $parametros['per_id'];
        $id_registro = $parametros['_id'];
        $fecha_nacimiento = $parametros['txt_fecha_nacimiento'];

        $info_parentesco = $this->modelo->obtener_info_parentesco($id_parentesco);

        if (empty($info_parentesco)) {
            return -1;
        }

        $config = $info_parentesco[0];

        if ($config['requiere_fec_nac'] == 1 && empty($fecha_nacimiento)) {
            return -3;
        }

        $datos = [
            ['campo' => 'th_per_id', 'dato' => $per_id],
            ['campo' => 'id_parentesco', 'dato' => $id_parentesco],
            ['campo' => 'th_ppa_nombres', 'dato' => $parametros['txt_nombres']],
            ['campo' => 'th_ppa_apellidos', 'dato' => $parametros['txt_apellidos']],
            ['campo' => 'th_ppa_numero_telefono', 'dato' => $parametros['txt_telefono']],
            ['campo' => 'th_ppa_fecha_nacimiento', 'dato' => $fecha_nacimiento],
            ['campo' => 'th_ppa_contacto_emergencia', 'dato' => $parametros['chk_contacto_emergencia']]
        ];

        if ($id_registro == '') {
            $total_existente = $this->modelo->contar_parientes_por_tipo($per_id, $id_parentesco);

            if ($config['cantidad'] > 0 && $total_existente >= $config['cantidad']) {
                return -2;
            }

            $datos[] = ['campo' => 'th_ppa_fecha_creacion', 'dato' => date('Y-m-d H:i:s')];
            return $this->modelo->insertar($datos);
        } else {
            // Editar: Validar cantidad máxima excluyendo el actual
            $parentesco_otros = $this->modelo->where('th_per_id', $per_id)
                ->where('id_parentesco', $id_parentesco)
                ->where('th_ppa_id!', $id_registro)
                ->listar();

            $total_otros = count($parentesco_otros);

            if ($config['cantidad'] > 0 && $total_otros >= $config['cantidad']) {
                return -2;
            }

            $datos[] = ['campo' => 'th_ppa_fecha_modificacion', 'dato' => date('Y-m-d H:i:s')];
            $where[0]['campo'] = 'th_ppa_id';
            $where[0]['dato'] = $id_registro;

            return $this->modelo->editar($datos, $where);
        }
    }

    function eliminar($id)
    {
        $datos = [
            ['campo' => 'th_ppa_id', 'dato' => $id]
        ];
        return $this->modelo->eliminar($datos);
    }

    public function buscar_parientes_persona($parametros)
    {
        $lista = array();

        // Llamamos al modelo con el ID de la persona y el query de búsqueda
        $datos = $this->modelo->buscar_familiares_con_parentesco($parametros);

        foreach ($datos as $value) {
            // Armamos el nombre completo del pariente
            $nombre_familiar = trim($value['th_ppa_apellidos'] . ' ' . $value['th_ppa_nombres']);

            // Obtenemos la descripción del parentesco
            $parentesco = $value['parentesco_nombre'];

            // Formato solicitado: Apellidos Nombres - Parentesco
            $text = $nombre_familiar . ' - ' . $parentesco;

            $lista[] = array(
                'id'   => $value['id'],
                'fecha_nacimiento'   => $value['th_ppa_fecha_nacimiento'],
                'parentesco'   => $parentesco,
                'text' => $text
            );
        }

        return $lista;
    }
}
