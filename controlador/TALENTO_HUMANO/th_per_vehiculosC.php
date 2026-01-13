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

        $texto = '';
        foreach ($datos as $key => $value) {
            // Obtener descripción del tipo de vehículo
            $tipo_vehiculo = isset($value['tipo_vehiculo_descripcion']) ? $value['tipo_vehiculo_descripcion'] : 'N/A';
            $placa_original = $value['th_per_veh_placa_original'];
            $placa_sintesis = $value['th_per_veh_placa_sintesis'] ? $value['th_per_veh_placa_sintesis'] : 'N/A';

            $texto .=
                <<<HTML
                    <div class="row mb-col">
                        <div class="col-10">
                            <h6 class="fw-bold">{$tipo_vehiculo}</h6>
                            <p class="m-0"><strong>Placa Original:</strong> {$placa_original}</p>
                            <p class="m-0"><strong>Placa Síntesis:</strong> {$placa_sintesis}</p>
                        </div>
                        <div class="col-2 d-flex justify-content-end align-items-start">
                            <button class="btn icon-hover" style="color: white;" onclick="abrir_modal_vehiculo({$value['_id']});">
                                <i class="text-dark bx bx-pencil bx-sm"></i>
                            </button>
                        </div>
                    </div>
                HTML;
        }
        
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