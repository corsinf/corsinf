<?php
// Ajusta la ruta según tu estructura de carpetas
require_once(dirname(__DIR__, 2) . '/modelo/TALENTO_HUMANO/th_per_estado_laboralM.php');

$controlador = new th_per_estado_laboralC();

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

class th_per_estado_laboralC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new th_per_estado_laboralM();
    }

    function listar($id)
    {
        $datos = $this->modelo->listar_estado_laboral_por_persona($id);
        $texto = '';

        foreach ($datos as $key => $value) {
            $estado_laboral = $value['estado_laboral_descripcion'] ?? 'N/A';
            $cargo = $value['cargo_nombre'] ?? 'N/A';
            $seccion = $value['seccion_descripcion'] ?? 'N/A';
            $nomina = $value['nomina_nombre'] ?? 'N/A';

            // Remuneración formateada
            $remuneracion = !empty($value['th_est_remuneracion'])
                ? '<p class="mb-1"><strong>Remuneración:</strong> $' . number_format($value['th_est_remuneracion'], 2) . '</p>'
                : '';

            // Fechas
            $fecha_contratacion = !empty($value['th_est_fecha_contratacion'])
                ? date('d/m/Y', strtotime($value['th_est_fecha_contratacion']))
                : 'N/A';

            $fecha_salida = 'Indefinido';
            if (!empty($value['th_est_fecha_salida']) && $value['th_est_fecha_salida'] !== '1900-01-01') {
                $fecha_salida = date('d/m/Y', strtotime($value['th_est_fecha_salida']));
            }

            // Badge Tipo de Cambio (Radio Button logic)
            $badge_tipo_class = ($value['th_est_check_estado_laboral'] == 1) ? 'bg-info' : 'bg-warning text-dark';
            $tipo_badge_text = ($value['th_est_check_estado_laboral'] == 1) ? 'RECATEGORIZACIÓN' : 'DADO DE BAJA';

            // Badge Estado dinámico
            $badge_class = 'bg-secondary';
            switch ($estado_laboral) {
                case 'Activo':
                    $badge_class = 'bg-success';
                    break;
                case 'Inactivo':
                    $badge_class = 'bg-danger';
                    break;
                case 'Prueba':
                    $badge_class = 'bg-warning text-dark';
                    break;
            }

            $texto .= <<<HTML
       <div class="row mb-col">
                    <div class="col-10" style="cursor: pointer;" onclick="abrir_modal_estado_laboral('{$value['_id']}')">
                        <div class="mb-2">
                            <span class="badge {$badge_class}">{$estado_laboral}</span>
                        </div>
                        <p class="mb-1"><strong>Cargo:</strong> {$cargo}</p>
                        <p class="mb-1"><strong>Sección:</strong> {$seccion}</p>
                        <p class="mb-1"><strong>Nómina:</strong> {$nomina}</p>
                        {$remuneracion}
                        <div class="d-flex gap-3 small text-muted mt-2">
                            <span><i class="bx bx-calendar"></i> Inicia: {$fecha_contratacion}</span>
                            <span><i class="bx bx-calendar-x"></i> Fin: {$fecha_salida}</span>
                        </div>
                    </div>
                    
                    <div class="col-2 text-end">
                        <button class="btn btn-sm btn-light border icon-hover" title="Editar Registro" onclick="abrir_modal_estado_laboral('{$value['_id']}')">
                            <i class="bx bx-pencil fs-5 text-dark"></i>
                        </button>
                    </div>
        </div>
HTML;
        }

        if (empty($datos)) {
            $texto = '<div class="alert alert-info text-center">No hay registros de estado laboral.</div>';
        }

        return [
            'html' => $texto,
            'tiene_registros' => !empty($datos)
        ];
    }

    function listar_modal($id)
    {
        $datos = $this->modelo->listar_estado_laboral_por_id($id);
        return $datos;
    }

    function insertar_editar($parametros)
    {
        $datos = array(
            array('campo' => 'th_per_id', 'dato' => $parametros['per_id']),
            array('campo' => 'id_cargo', 'dato' => $parametros['ddl_cargo']),
            array('campo' => 'id_seccion', 'dato' => $parametros['ddl_seccion']),
            array('campo' => 'id_nomina', 'dato' => $parametros['ddl_nomina']),
            array('campo' => 'id_estado_laboral', 'dato' => $parametros['ddl_estado_laboral']),
            array('campo' => 'th_est_remuneracion', 'dato' => $parametros['txt_remuneracion']),
            array('campo' => 'th_est_check_estado_laboral', 'dato' => $parametros['tipo_cambio']), // Ahora viene del radio button
            array('campo' => 'th_est_fecha_contratacion', 'dato' => $parametros['txt_fecha_contratacion_estado']),
            array('campo' => 'th_est_fecha_salida', 'dato' => $parametros['txt_fecha_salida_estado']),
        );

        if ($parametros['_id'] == '') {
            // INSERTAR
            $datos[] = array('campo' => 'th_est_fecha_creacion', 'dato' => date('Y-m-d H:i:s'));
            $resultado = $this->modelo->insertar($datos);
        } else {
            // EDITAR
            $datos[] = array('campo' => 'th_est_fecha_modificacion', 'dato' => date('Y-m-d H:i:s'));
            $where[0]['campo'] = 'th_est_id';
            $where[0]['dato'] = $parametros['_id'];
            $resultado = $this->modelo->editar($datos, $where);
        }

        return $resultado;
    }

    function eliminar($id)
    {
        $datos = array(
            array('campo' => 'th_est_estado', 'dato' => 0),
            array('campo' => 'th_est_fecha_modificacion', 'dato' => date('Y-m-d H:i:s')),
        );

        $where[0]['campo'] = 'th_est_id';
        $where[0]['dato'] = strval($id);
        $resultado = $this->modelo->editar($datos, $where);

        return $resultado;
    }
}
