<?php
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
            $estado_laboral = isset($value['th_est_estado_laboral']) ? $value['th_est_estado_laboral'] : 'N/A';
            $cargo = isset($value['cargo_nombre']) ? $value['cargo_nombre'] : 'N/A';
            $seccion = isset($value['seccion_descripcion']) ? $value['seccion_descripcion'] : 'N/A';
            $fecha_contratacion = isset($value['th_est_fecha_contratacion']) && $value['th_est_fecha_contratacion'] 
                ? date('d/m/Y', strtotime($value['th_est_fecha_contratacion'])) 
                : 'N/A';
            $fecha_salida = isset($value['th_est_fecha_salida']) && $value['th_est_fecha_salida']
                ? date('d/m/Y', strtotime($value['th_est_fecha_salida'])) 
                : 'N/A';

            $badge_class = '';
            switch ($estado_laboral) {
                case 'Activo':
                    $badge_class = 'bg-success';
                    break;
                case 'Inactivo':
                    $badge_class = 'bg-danger';
                    break;
                case 'Prueba':
                    $badge_class = 'bg-warning';
                    break;
                case 'Pasante':
                    $badge_class = 'bg-info';
                    break;
                case 'Freelancer':
                    $badge_class = 'bg-primary';
                    break;
                case 'Autonomo':
                    $badge_class = 'bg-secondary';
                    break;
                default:
                    $badge_class = 'bg-secondary';
            }

            $texto .=
                <<<HTML
                    <div class="row mb-col">
                        <div class="col-10">
                            <div class="d-flex align-items-center mb-2">
                                <h6 class="fw-bold mb-0 me-2">Estado:</h6>
                                <span class="badge {$badge_class}">{$estado_laboral}</span>
                            </div>
                            <p class="m-0"><strong>Cargo:</strong> {$cargo}</p>
                            <p class="m-0"><strong>Sección:</strong> {$seccion}</p>
                            <p class="m-0"><strong>Fecha de Contratación:</strong> {$fecha_contratacion}</p>
                            <p class="m-0"><strong>Fecha de Salida:</strong> {$fecha_salida}</p>
                        </div>
                        <div class="col-2 d-flex justify-content-end align-items-start">
                            <button class="btn icon-hover" style="color: white;" onclick="abrir_modal_estado_laboral('{$value['_id']}');">
                                <i class="text-dark bx bx-pencil bx-sm"></i>
                            </button>
                        </div>
                    </div>
                    <hr>
                HTML;
        }

        if (empty($datos)) {
            $texto = '<div class="alert alert-info">No hay registros de estado laboral.</div>';
        }
        
        return $texto;
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
            array('campo' => 'th_est_estado_laboral', 'dato' => $parametros['ddl_estado_laboral']),
            array('campo' => 'th_est_fecha_contratacion', 'dato' => $parametros['txt_fecha_contratacion_estado']),
            array('campo' => 'th_est_fecha_salida', 'dato' => $parametros['txt_fecha_salida_estado']),
        );

        if ($parametros['_id'] == '') {
            $datos = $this->modelo->insertar($datos);
        } else {
            $where[0]['campo'] = 'th_est_id';
            $where[0]['dato'] = $parametros['_id'];
            $datos = $this->modelo->editar($datos, $where);
        }

        return $datos;
    }

    function eliminar($id)
    {
        $datos = array(
            array('campo' => 'th_est_estado', 'dato' => 0),
        );

        $where[0]['campo'] = 'th_est_id';
        $where[0]['dato'] = strval($id);

        $datos = $this->modelo->editar($datos, $where);

        return $datos;
    }
}