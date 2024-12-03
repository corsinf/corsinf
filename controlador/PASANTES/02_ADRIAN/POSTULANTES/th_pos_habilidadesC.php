<?php
require_once(dirname(__DIR__, 4) . '/modelo/PASANTES/02_ADRIAN/POSTULANTES/th_pos_habilidadesM.php');

$controlador = new th_pos_habilidadesC();

if (isset($_GET['cargar_datos_aptitudes_tecnicas'])) {
    echo json_encode($controlador->listar_aptitudes_tecnicas($_POST['id']));
}

if (isset($_GET['cargar_datos_aptitudes_blandas'])) {
    echo json_encode($controlador->listar_aptitudes_blandas($_POST['id']));
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

if (isset($_GET['listar_aptitudes_blandas_postulante'])) {
    echo json_encode($controlador->listar_aptitudes_blandas_postulante($_POST['id_postulante']));
}

if (isset($_GET['listar_aptitudes_tecnicas_postulante'])) {
    echo json_encode($controlador->listar_aptitudes_tecnicas_postulante($_POST['id_postulante']));
}


class th_pos_habilidadesC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new th_pos_habilidadesM();
    }

    function listar_aptitudes_tecnicas($id)
    {
        $datos = $this->modelo->where('th_pos_id', $id)->where('th_tiph_id', 2)->listarJoin();

        $texto = '';
        foreach ($datos as $key => $value) {
            $texto .=
                <<<HTML
                    <div class="row mt-1">
                        <div class="col-8">
                            <ul>
                                <li>{$value['th_hab_nombre']}</li>
                            </ul>
                        </div>
                        
                        <div class="col-4 d-flex justify-content-end align-items-center">
                            <button class="btn" style="color: white;" onclick="delete_datos_aptitudes({$value['th_habp_id']})">
                                <i class="text-danger bx bx-trash bx-sm"></i>
                            </button>
                        </div>
                    </div>
                HTML;
        }

        return $texto;
    }

    function listar_aptitudes_blandas($id)
    {
        $datos = $this->modelo->where('th_pos_id', $id)->where('th_tiph_id', 1)->listarJoin();

        $texto = '';
        foreach ($datos as $key => $value) {
            $texto .=
                '<div class="row mt-1">
                    <div class="col-8">
                        <ul>
                            <li>' . $value['th_hab_nombre'] . '</li>
                        </ul>
                    </div>
                            
                    <div class="col-4 d-flex justify-content-end align-items-center">
                        <button class="btn" style="color: white;" onclick="delete_datos_aptitudes(' . $value['th_habp_id'] . ')">
                        <i class="text-danger bx bx-trash bx-sm"></i>
                        </button>
                    </div>
                </div>';
        }

        return $texto;
    }

    //Buscando registros por id de la formacion academica
    function listar_modal($id)
    {

        if ($id == '') {
            $datos = $this->modelo->where('th_habp_estado', 1)->listar();
        } else {
            $datos = $this->modelo->where('th_habp_id', $id)->listar();
        }
        return $datos;
    }

    function insertar_editar($parametros)
    {

        foreach ($parametros['txt_id_aptitudes'] as $aptitud_id) {
            $datos = array(
                array('campo' => 'th_hab_id', 'dato' => intval($aptitud_id)),
                array('campo' => 'th_pos_id', 'dato' => $parametros['txt_id_postulante']),
            );

            if ($parametros['_id'] == '') {
                $datos = $this->modelo->insertar($datos);
            } else {
                $where[0]['campo'] = 'th_habp_id';
                $where[0]['dato'] = $parametros['_id'];
                $datos = $this->modelo->editar($datos, $where);
            }
        }

        return $datos;
    }

    function eliminar($id)
    {

        $datos = array(
            array('campo' => 'th_habp_estado', 'dato' => 0),
        );

        $where[0]['campo'] = 'th_habp_id';
        $where[0]['dato'] = strval($id);

        $datos = $this->modelo->editar($datos, $where);

        return $datos;
    }

    function listar_aptitudes_blandas_postulante($id_postulante)
    {
        //corregir
        $datos = $this->modelo->listar_habilidades_postulante($id_postulante, 1);
        
        $option = '';
        foreach ($datos as $key => $value) {
            $option .= "<option value='" . $value['th_hab_id'] . "'>" . $value['th_hab_nombre'] . "</option>";
        }

        return $option;
    }

    function listar_aptitudes_tecnicas_postulante($id_postulante)
    {
        $datos = $this->modelo->listar_habilidades_postulante($id_postulante, 2);

        $option = '';
        foreach ($datos as $key => $value) {
            $option .= "<option value='" . $value['th_hab_id'] . "'>" . $value['th_hab_nombre'] . "</option>";
        }

        return $option;
    }
}
