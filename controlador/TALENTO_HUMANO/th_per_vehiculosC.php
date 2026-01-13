<?php
require_once(dirname(__DIR__, 2) . '/modelo/TALENTO_HUMANO/th_per_vehiculosM.php');

$controlador = new th_per_vehiculosC();

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

class th_per_vehiculosC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new th_per_vehiculosM();
    }

    // Función para listar los vehículos de la persona
    function listar($id)
    {
        $datos = $this->modelo->listar_vehiculos_por_persona_con_tipo($id);

        if (empty($datos)) {
            return '<div class="text-center py-5">
                    <i class="bx bx-car bx-lg text-muted mb-3"></i>
                    <p class="text-muted">No hay vehículos registrados</p>
                </div>';
        }

        $texto = '<div class="row">';

        foreach ($datos as $key => $value) {
            $tipo_vehiculo = isset($value['tipo_vehiculo_descripcion']) ? $value['tipo_vehiculo_descripcion'] : 'N/A';
            $placa_original = $value['th_per_veh_placa_original'];
            $placa_sintesis = $value['th_per_veh_placa_sintesis'] ? $value['th_per_veh_placa_sintesis'] : 'N/A';

            // Iconos según tipo de vehículo
            $icono = 'bx-car';
            if (stripos($tipo_vehiculo, 'moto') !== false) {
                $icono = 'bx-cycling';
            } elseif (stripos($tipo_vehiculo, 'camión') !== false || stripos($tipo_vehiculo, 'camion') !== false) {
                $icono = 'bxs-truck';
            }

            $texto .= <<<HTML
            <div class="col-12 col-md-6 col-lg-4 mb-col">
                <div class="card border-0 shadow-sm custom-card-compact">
                    <div class="card-body p-2 d-flex align-items-center justify-content-between">
                        
                        <div class="d-flex align-items-center flex-grow-1 overflow-hidden">
                            <div class="mini-status-icon bg-primary-soft text-primary me-2">
                                <i class="bx {$icono} fs-5"></i>
                            </div>
                            <div class="text-truncate">
                                <h6 class="mb-0 fw-bold text-dark lh-1" style="font-size: 0.85rem;">
                                    {$tipo_vehiculo}
                                </h6>
                                <div class="d-flex align-items-center mt-1">
                                    <span class="badge-plate me-1" title="Original">{$placa_original}</span>
                                    <span class="badge-plate-alt" title="Síntesis">{$placa_sintesis}</span>
                                </div>
                            </div>
                        </div>

                        <button class="btn btn-edit-minimal ms-2" 
                                onclick="abrir_modal_vehiculo({$value['_id']});"
                                title="Editar vehículo">
                            <i class="bx bx-pencil"></i>
                        </button>
                        
                    </div>
                </div>
            </div>
        HTML;
        }

        $texto .= '</div>';

        return $texto;
    }

    // Buscando registros por id del vehículo
    function listar_modal($id)
    {
        $datos = $this->modelo->listar_vehiculos_con_tipo($id);
        return $datos;
    }

    function insertar_editar($parametros)
    {
        $datos = array(
            array('campo' => 'th_per_id', 'dato' => $parametros['txt_id_persona']),
            array('campo' => 'id_vehiculo', 'dato' => $parametros['ddl_tipo_vehiculo']),
            array('campo' => 'th_per_veh_placa_original', 'dato' => strtoupper($parametros['txt_placa_original'])),
        );

        if ($parametros['_id'] == '') {
            $datos = $this->modelo->insertar($datos);
        } else {
            $where[0]['campo'] = 'th_per_veh_id';
            $where[0]['dato'] = $parametros['_id'];
            $datos = $this->modelo->editar($datos, $where);
        }

        return $datos;
    }

    function eliminar($id)
    {
        $datos = array(
            array('campo' => 'th_per_veh_estado', 'dato' => 0),
        );

        $where[0]['campo'] = 'th_per_veh_id';
        $where[0]['dato'] = strval($id);

        $datos = $this->modelo->editar($datos, $where);

        return $datos;
    }
}
