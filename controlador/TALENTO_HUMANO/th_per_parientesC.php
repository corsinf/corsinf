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
            $contacto_emergencia = $value['contacto_emergencia'] == 1 ? '<span class="badge bg-danger ms-2">Contacto Emergencia</span>' : '';
            $edad = '';

            $edad = '';

            if (
                !empty($value['fecha_nacimiento']) &&
                $value['fecha_nacimiento'] !== '1900-01-01' &&
                $value['fecha_nacimiento'] !== '1900-01-01 00:00:00'
            ) {
                $fecha_nac = new DateTime($value['fecha_nacimiento']);
                $hoy = new DateTime();
                $edad_calculada = $hoy->diff($fecha_nac)->y;
                $edad = " ({$edad_calculada} años)";
            }


            $telefono = !empty($value['numero_telefono']) ? "<p class='m-0'><strong>Teléfono:</strong> {$value['numero_telefono']}</p>" : '';

            $texto .= <<<HTML
                <div class="row mb-col">
                    <div class="col-10">
                        <p class="m-0"><strong>Parentesco:</strong> {$value['parentesco_nombre']} {$contacto_emergencia}</p>
                        <p class="m-0"><strong>Nombre:</strong> {$value['nombres']} {$value['apellidos']}{$edad}</p>
                        {$telefono}
                    </div>
                    <div class="col-2 d-flex justify-content-end">
                        <button class="btn icon-hover" onclick="abrir_modal_pariente('{$value['_id']}');">
                            <i class="bx bx-pencil bx-sm text-dark"></i>
                        </button>
                    </div>
                </div>
                <hr>
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
}
