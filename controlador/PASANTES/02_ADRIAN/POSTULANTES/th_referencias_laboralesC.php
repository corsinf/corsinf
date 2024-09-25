<?php
require_once(dirname(__DIR__, 4) . '/modelo/PASANTES/02_ADRIAN/POSTULANTES/th_referencias_laboralesM.php');

$controlador = new th_referencias_laboralesC();

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


class th_referencias_laboralesC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new th_referencias_laboralesM();
    }

    //Funcion para listar la formacion academica del postulante
    function listar($id)
    {
        $datos = $this->modelo->where('th_pos_id', $id)->where('th_refl_estado', 1)->listar();

        $texto = '';
        foreach ($datos as $key => $value) {
            $texto .=
            '<div class="row mb-3">
                <div class="col-10">
                    <p class="fw-bold my-0 d-flex align-items-center">' . $value['th_refl_nombre_referencia'] . '</p>
                    <p class="my-0 d-flex align-items-center">' . $value['th_refl_telefono_referencia'] . '</p>
                    <a href="#" data-bs-toggle="modal" data-bs-target="#modal_ver_pdf" onclick="definir_ruta_iframe(' . $value['_id'] . ');">Ver Carta de Recomendación</a>
                </div>
                <div class="col-2 d-flex justify-content-end align-items-center">
                    <button class="btn btn-xs" style="color: white;" onclick="abrir_modal_referencias_laborales(' . $value['_id'] . ')"><i class="text-dark bx bx-pencil me-0" style="font-size: 20px;"></i></button>
                </div>
            </div>';
        }
        return $texto;
    }

    //Buscando registros por id de la formacion academica
    function listar_modal($id)
    {
        if ($id == '') {
            $datos = $this->modelo->where('th_refl_estado', 1)->listar();
        } else {
            $datos = $this->modelo->where('th_refl_id', $id)->listar();
        }
        return $datos;
    }

    function insertar_editar($parametros)
    {
        $datos = array(
            array('campo' => 'th_refl_nombre_referencia', 'dato' => $parametros['txt_nombre_referencia']),
            array('campo' => 'th_refl_telefono_referencia', 'dato' => $parametros['txt_telefono_referencia']),
            array('campo' => 'th_refl_carta_recomendacion', 'dato' => $parametros['txt_copia_carta_recomendacion']),
            array('campo' => 'th_pos_id', 'dato' => $parametros['txt_id_postulante']),

        );

        if ($parametros['_id'] == '') {
            $datos = $this->modelo->insertar($datos);
        } else {
            $where[0]['campo'] = 'th_refl_id';
            $where[0]['dato'] = $parametros['_id'];
            $datos = $this->modelo->editar($datos, $where);
        }

        return $datos;
    }

    function eliminar($id)
    {

        $datos = array(
            array('campo' => 'th_refl_estado', 'dato' => 0),
        );

        $where[0]['campo'] = 'th_refl_id';
        $where[0]['dato'] = strval($id);

        $datos = $this->modelo->editar($datos, $where);

        return $datos;
    }
}
