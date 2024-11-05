<?php
require_once(dirname(__DIR__, 4) . '/modelo/PASANTES/02_ADRIAN/POSTULANTES/th_formacion_academicaM.php');

$controlador = new th_formacion_academicaC();

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


class th_formacion_academicaC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new th_formacion_academicaM();
    }

    //Funcion para listar la formacion academica del postulante
    function listar($id)
    {
        $datos = $this->modelo->where('th_pos_id', $id)->where('th_fora_estado', 1)->listar();

        $texto = '';
        foreach ($datos as $key => $value) {

            $fechaFin = $value['th_fora_fecha_fin_formacion'] == '' ? 'Actualidad' : $value['th_fora_fecha_fin_formacion'];

            $texto .=
                <<<HTML
                    <div class="row mb-col">
                        <div class="col-10">
                            <h6 class="fw-bold">{$value['th_fora_titulo_obtenido']}</h6>
                            <p class="m-0">{$value['th_fora_institución']}</p>
                            <p class="m-0">{$value['th_fora_fecha_inicio_formacion']} - {$fechaFin}</p>
                        </div>
                        <div class="col-2 d-flex justify-content-end align-items-start">
                            <button class="btn" style="color: white;" onclick="abrir_modal_formacion_academica({$value['_id']});">
                                <i class="text-dark bx bx-pencil bx-sm"></i>
                            </button>
                        </div>
                    </div>
                HTML;
        }
        
        return $texto;
    }

    //Buscando registros por id de la formacion academica
    function listar_modal($id)
    {

        if ($id == '') {
            $datos = $this->modelo->where('th_fora_estado', 1)->listar();
        } else {
            $datos = $this->modelo->where('th_fora_id', $id)->listar();
        }
        return $datos;
    }

    function insertar_editar($parametros)
    {
        $datos = array(
            array('campo' => 'th_fora_titulo_obtenido', 'dato' => $parametros['txt_titulo_obtenido']),
            array('campo' => 'th_fora_institución', 'dato' => $parametros['txt_institucion']),
            array('campo' => 'th_fora_fecha_inicio_formacion', 'dato' => $parametros['txt_fecha_inicio_academico']),
            array('campo' => 'th_fora_fecha_fin_formacion', 'dato' => $parametros['txt_fecha_final_academico']),
            array('campo' => 'th_pos_id', 'dato' => $parametros['txt_id_postulante']),

        );

        if ($parametros['_id'] == '') {
            $datos = $this->modelo->insertar($datos);
        } else {
            $where[0]['campo'] = 'th_fora_id';
            $where[0]['dato'] = $parametros['_id'];
            $datos = $this->modelo->editar($datos, $where);
        }

        return $datos;
    }

    function eliminar($id)
    {

        $datos = array(
            array('campo' => 'th_fora_estado', 'dato' => 0),
        );

        $where[0]['campo'] = 'th_fora_id';
        $where[0]['dato'] = strval($id);

        $datos = $this->modelo->editar($datos, $where);

        return $datos;
    }
}
