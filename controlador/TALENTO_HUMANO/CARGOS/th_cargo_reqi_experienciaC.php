<?php
date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 3)  . '/modelo/TALENTO_HUMANO/CARGOS/th_cargo_reqi_experienciaM.php');

$controlador = new th_cargo_reqi_experienciaC();

// Rutas para AJAX
if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar($_POST['id'] ?? '', $_POST['car_id'] ?? ''));
}

if (isset($_GET['listar_modal'])) {
    echo json_encode($controlador->listar_modal($_POST['id']));
}


if (isset($_GET['insertar_editar'])) {
    echo json_encode($controlador->insertar_editar($_POST['parametros']));
}

if (isset($_GET['eliminar'])) {
    echo json_encode($controlador->eliminar($_POST['id']));
}

class th_cargo_reqi_experienciaC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new th_cargo_reqi_experienciaM();
    }

    function listar($id = '', $car_id = '')
    {
        if ($id == '' && $car_id == '') {
            $datos =  $this->modelo->where('th_reqe_estado', 1)->listar();
        } else if ($car_id !== '') {
            $datos =  $this->modelo->where('id_cargo', $id)->where('th_reqe_estado', 1)->listar();
        } else if ($id !== '') {
            $datos =  $this->modelo->where('th_reqe_experiencia_id', $id)->where('th_reqe_estado', 1)->listar();
        }
        return $datos;
    }
    function listar_modal($id = '')
    {
        $datos = $this->modelo->where('id_cargo', $id)->where('th_reqe_estado', 1)->listar();

        if (empty($datos)) {
            return [
                'html' => <<<HTML
            <div class="d-flex align-items-center bg-light border-start border-secondary border-3 p-2 shadow-sm rounded-2">
                <i class='bx bx-info-circle me-2 text-secondary' style='font-size: 20px;'></i>
                <div class="lh-1">
                    <div class="text-dark fw-bold small">Sin experiencia registrada</div>
                    <div class="text-muted" style="font-size: 0.75rem;">No se han definido años de experiencia.</div>
                </div>
            </div>
HTML,
                'tiene_registros' => false
            ];
        }

        // Iniciamos la lista sin estilos de bullet por defecto para controlarlos con Boxicons
        $texto = '<ul class="list-unstyled mb-0">';

        foreach ($datos as $value) {
            $id_reg = $value['_id'];
            $anios = $value['th_reqe_anios'];
            $label_anios = ($anios == 1) ? 'AÑO' : 'AÑOS';

            $texto .= <<<HTML
       <li class="py-2 border-bottom">
    <div class="d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center">
            <i class='bx bx-check-circle text-success me-2' style="font-size: 18px;"></i>
            <span class="text-dark" style="font-size: 0.9rem;">{$anios} {$label_anios}</span>
        </div>
        <button type="button" class="btn btn-danger btn-sm py-0 px-2" onclick="delete_datos_experiencia_necesaria('{$id_reg}')" style="font-size: 0.75rem;">
            <i class="bx bx-trash" style="font-size: 14px;"></i>
        </button>
    </div>
</li>
HTML;
        }

        $texto .= '</ul>';

        return [
            'html' => $texto,
            'tiene_registros' => true
        ];
    }

    function insertar_editar($parametros)
    {
        $id = $parametros['_id'];
        $datos = array(
            array('campo' => 'id_cargo', 'dato' => $parametros['id_cargo']),
            array('campo' => 'th_reqe_anios', 'dato' => $parametros['th_reqe_anios']),
            array('campo' => 'th_reqe_estado', 'dato' => 1),
        );

        if ($id == '') {
            $datos[] = array('campo' => 'th_reqe_fecha_creacion', 'dato' => date('Y-m-d H:i:s'));
            $result = $this->modelo->insertar($datos);
            // Edición
            $datos[] = array('campo' => 'th_reqe_fecha_modificacion', 'dato' => date('Y-m-d H:i:s'));
            $where[0]['campo'] = 'th_reqe_experiencia_id';
            $where[0]['dato'] = $id;
            $result = $this->modelo->editar($datos, $where);
        }

        return $result;
    }

    function eliminar($id)
    {
        $datos = array(
            array('campo' => 'th_reqe_experiencia_id', 'dato' => $id),
        );
        return $this->modelo->eliminar($datos);
    }
}
