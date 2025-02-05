<?php
require_once(dirname(__DIR__, 4) . '/modelo/PASANTES/02_ADRIAN/POSTULANTES/th_pos_experiencia_laboralM.php');

$controlador = new th_pos_experiencia_laboralC();

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


class th_pos_experiencia_laboralC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new th_pos_experiencia_laboralM();
    }

    //Funcion para listar la experiencia previa del postulante
    function listar($id)
    {
        $datos = $this->modelo->where('th_pos_id', $id)->where('th_expl_estado', 1)->orderBy('th_expl_cbx_fecha_fin_experiencia','DESC')->orderBy('th_expl_fecha_fin_experiencia', 'DESC')->listar();
        //$datos = $this->modelo->where('th_pos_id', $id)->where('th_expl_estado', 1)->orderBy('th_expl_fecha_fin_experiencia', 'DESC')->listar();
        $texto = '';
        
        foreach ($datos as $key => $value) {
            //Formato de fechas de experiencia laboral
            $fecha_inicio_experiencia = date('d/m/Y', strtotime($value['th_expl_fecha_inicio_experiencia']));
            //$fecha_fin_experiencia = $value['th_expl_fecha_fin_experiencia'] == '' ? 'Actualidad' : date('d/m/Y', strtotime($value['th_expl_fecha_fin_experiencia']));
            $fecha_fin_experiencia = $value['th_expl_cbx_fecha_fin_experiencia'] == 1 ? 'Actualidad' : date('d/m/Y', strtotime($value['th_expl_fecha_fin_experiencia']));
            $texto .=
                <<<HTML
                    <div class="row mb-col">
                        <div class="col-10">
                            <h6 class="fw-bold">{$value['th_expl_nombre_empresa']}</h6>
                            <p class="m-0">{$value['th_expl_cargos_ocupados']}</p>
                            <p class="m-0">{$fecha_inicio_experiencia} - {$fecha_fin_experiencia}</p>
                            <p class="m-0">{$value['th_expl_responsabilidades_logros']}</p>
                        </div>
                        <div class="col-2 d-flex justify-content-end align-items-start">
                            <button class="btn icon-hover" style="color: white;" onclick="abrir_modal_experiencia_laboral({$value['_id']});">
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
            $datos = $this->modelo->where('th_expl_estado', 1)->listar();
        } else {
            $datos = $this->modelo->where('th_expl_id', $id)->listar();
        }
        return $datos;
    }

    function insertar_editar($parametros)
    {
        $datos = array(
            array('campo' => 'th_expl_nombre_empresa', 'dato' => $parametros['txt_nombre_empresa']),
            array('campo' => 'th_expl_cargos_ocupados', 'dato' => $parametros['txt_cargos_ocupados']),
            array('campo' => 'th_expl_fecha_inicio_experiencia', 'dato' => $parametros['txt_fecha_inicio_laboral']),
            array('campo' => 'th_expl_fecha_fin_experiencia', 'dato' => $parametros['txt_fecha_final_laboral']),
            array('campo' => 'th_expl_cbx_fecha_fin_experiencia', 'dato' => $parametros['cbx_fecha_final_laboral']),
            array('campo' => 'th_expl_responsabilidades_logros', 'dato' => $parametros['txt_responsabilidades_logros']),
            array('campo' => 'th_pos_id', 'dato' => $parametros['txt_id_postulante']),

        );

        if ($parametros['_id'] == '') {
            $datos = $this->modelo->insertar($datos);
        } else {
            $where[0]['campo'] = 'th_expl_id';
            $where[0]['dato'] = $parametros['_id'];
            $datos = $this->modelo->editar($datos, $where);
        }

        return $datos;
    }

    function eliminar($id)
    {
        $datos = array(
            array('campo' => 'th_expl_estado', 'dato' => 0),
        );

        $where[0]['campo'] = 'th_expl_id';
        $where[0]['dato'] = strval($id);

        $datos = $this->modelo->editar($datos, $where);

        return $datos;
    }
}
