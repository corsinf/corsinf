<?php
date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 2) . '/modelo/TALENTO_HUMANO/th_per_informacion_adicionalM.php');

$controlador = new th_per_informacion_adicionalC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar($_POST['id'] ?? ''));
}


if (isset($_GET['insertar'])) {
    echo json_encode($controlador->insertar_editar($_POST['parametros']));
}

if (isset($_GET['eliminar'])) {
    echo json_encode($controlador->eliminar($_POST['id']));
}

class th_per_informacion_adicionalC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new th_per_informacion_adicionalM();
    }

    function listar($id)
    {
        $datos = $this->modelo
            ->where('th_per_id', $id)
            ->where('th_inf_adi_estado', 1)
            ->listar();

        if (empty($datos)) {
            return <<<HTML
                        <div class="d-flex align-items-center bg-white border border-start-0 shadow-sm rounded-3" 
                            style="border-left: 4px solid !important; padding: 12px 24px; max-width: 600px;">
                        
                        <i class='bx bx-data me-3 text-primary' style='font-size: 28px;'></i>

                        <div class="lh-sm">
                            <div class="text-dark fw-bold mb-1" style="font-size: 1rem;">
                            Sin registros en este apartado
                            </div>
                            <div class="text-secondary" style="font-size: 0.85rem;">
                            No hemos encontrado información disponible para mostrar en esta sección.
                            </div>
                        </div>
                        </div>
                    HTML;
        }

        $texto = '';

        foreach ($datos as $value) {

            $tiempo = !empty($value['tiempo_trabajo'])
                ? $value['tiempo_trabajo']
                : 'No registrado';

            $remuneracion = !empty($value['remuneracion_promedio'])
                ? '$ ' . number_format($value['remuneracion_promedio'], 2)
                : 'No registrado';

            $texto .= <<<HTML
            <div class="row align-items-center mb-2">
                <div class="col-10">
                    <p class="m-0">
                        <strong>Tiempo de trabajo:</strong> {$tiempo}
                    </p>
                    <!-- <p class="m-0">
                        <strong>Remuneración promedio:</strong> {$remuneracion}
                    </p> -->
                </div>
            </div>
            <hr>
        HTML;
        }

        if (empty($datos)) {
            $texto = '
            <div class="alert alert-info mb-0">
                No hay información adicional registrada.
            </div>';
        }

        return $texto;
    }



    function insertar_editar($parametros)
    {
        $id = isset($parametros['_id']) ? intval($parametros['_id']) : 0;

        $datos = array(
            array('campo' => 'th_per_id', 'dato' => $parametros['th_per_id']),
            array('campo' => 'th_inf_adi_tiempo_trabajo', 'dato' => $parametros['tiempo_trabajo']),
            array('campo' => 'th_inf_adi_remuneracion_promedio', 'dato' => $parametros['remuneracion_promedio']),
            array('campo' => 'th_inf_adi_fecha_modificacion', 'dato' => date('Y-m-d H:i:s')),
        );


        if ($id == 0) {
            $existe = $this->modelo
                ->where('th_per_id', $parametros['th_per_id'])
                ->where('th_inf_adi_estado', 1)
                ->listar();

            if (count($existe) > 0) {
                return -2; // Ya existe información adicional
            }

            $datos_insert = $this->modelo->insertar_id($datos);
            return $datos_insert ? 1 : 0;
        } else {

            $existe = $this->modelo
                ->where('th_per_id', $parametros['th_per_id'])
                ->where('th_inf_adi_id !', $id)
                ->where('th_inf_adi_estado', 1)
                ->listar();

            if (count($existe) > 0) {
                return -2;
            }

            $where[0]['campo'] = 'th_inf_adi_id';
            $where[0]['dato'] = $id;

            $datos = $this->modelo->editar($datos, $where);
            return $datos;
        }
    }
    function eliminar($id)
    {
        $datos = array(
            array('campo' => 'th_inf_adi_estado', 'dato' => 0),
            array('campo' => 'th_inf_adi_fecha_modificacion', 'dato' => date('Y-m-d H:i:s')),
        );

        $where[0]['campo'] = 'th_inf_adi_id';
        $where[0]['dato'] = $id;

        $datos = $this->modelo->editar($datos, $where);
        return $datos;
    }
}
