<?php

require_once(dirname(__DIR__, 4) . '/modelo/PASANTES/02_ADRIAN/POSTULANTES/th_pos_estado_laboralM.php');

$controlador = new th_pos_estado_laboralC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar($_POST['id']));
}

if (isset($_GET['insertar'])) {
    echo json_encode($controlador->insertar_editar($_POST['parametros']));
}

if (isset($_GET['eliminar'])) {
    echo json_encode($controlador->eliminar($_POST['id']));
}

if (isset($_GET['listar_modal'])) {
    echo json_encode($controlador->listar_modal($_POST['id']));
}

class th_pos_estado_laboralC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new th_pos_estado_laboralM();
    }

    function listar($id)
    {
        $datos = $this->modelo->where('th_pos_id', $id)-> orderBy('th_est_fecha_salida','DESC')->listar();

        $texto = '';
        foreach ($datos as $key => $value) {
            // Formatear las fechas antes de incluirlas en el HTML
            $fecha_contratacion = date('d/m/Y', strtotime($value['th_est_fecha_contratacion']));
            $fecha_salida = date('d/m/Y', strtotime($value['th_est_fecha_salida']));
            $texto .=
                <<<HTML
                    <div class="row mb-col">
                        <div class="col-10">
                            <h6 class="fw-bold">{$value['th_est_estado_laboral']}</h6>
                            <p class="m-0">{$fecha_contratacion} - {$fecha_salida}</p>                            
                        </div>
                        <div class="col-2 d-flex justify-content-end align-items-start">
                            <button class="btn" style="color: white;" onclick="abrir_modal_estado_laboral({$value['_id']});">
                                <i class="text-dark bx bx-pencil bx-sm"></i>
                            </button>
                        </div>
                    </div>
                HTML;
        }
        return $texto;
    }

    function listar_modal($id)
    {

        if ($id == '') {
            $datos = $this->modelo->listar();
        } else {
            $datos = $this->modelo->where('th_est_id', $id)->listar();
        }
        return $datos;
    }

    function insertar_editar($parametros)
    {
        //print_r($parametros); exit(); die();

        $datos = array(

            array('campo' => 'th_pos_id', 'dato' => $parametros['id_postulante']),
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
            array('campo' => 'th_est_id', 'dato' =>$id),
        );

        $datos = $this->modelo->eliminar($datos);
        return $datos;
    }
}
