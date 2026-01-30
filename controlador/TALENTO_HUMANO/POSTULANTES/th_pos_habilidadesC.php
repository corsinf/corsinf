<?php
require_once(dirname(__DIR__, 3)  . '/modelo/TALENTO_HUMANO/POSTULANTES/th_pos_habilidadesM.php');

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
        $datos = $this->modelo->where('th_pos_id', $id)->where('th_tiph_id', 2)->orderBy('th_hab_nombre', 'ASC')->listarJoin();

        if (empty($datos)) {
            $texto = '<div  class="alert alert-info mb-0"><p>No hay información adicional registrada.</p></div>';
        } else {

            $texto = '<div class="d-flex flex-wrap gap-2 p-2">'; // Contenedor Flex para que se alineen solas
            foreach ($datos as $key => $value) {
                $texto .= <<<HTML
                            <div class="badge rounded-pill d-flex align-items-center border-0" 
                                style="background-color: #e8f5e9; color: #2e7d32; padding: 0.5rem 0.8rem; font-size: 0.85rem;">
                                
                                <span class="me-2 fw-bold">
                                    <i class="bx bx-check-double me-1"></i> {$value['th_hab_nombre']}
                                </span>
                                
                                <button type="button" 
                                        class="btn p-0 d-flex align-items-center shadow-none" 
                                        style="color: #2e7d32; transition: 0.2s;"
                                        onmouseover="this.style.color='#d32f2f'" 
                                        onmouseout="this.style.color='#2e7d32'"
                                        onclick="delete_datos_aptitudes({$value['th_habp_id']})">
                                    <i class="bx bx-x-circle fs-5"></i>
                                </button>
                                
                            </div>
                        HTML;
            }

            $texto .= '</div>';
        }

        return $texto;
    }

    function listar_aptitudes_blandas($id)
    {
        $datos = $this->modelo->where('th_pos_id', $id)->where('th_tiph_id', 1)->orderBy('th_hab_nombre', 'ASC')->listarJoin();

        $texto = '<div class="d-flex flex-wrap gap-2 p-2">';
        foreach ($datos as $key => $value) {
            $texto .= <<<HTML
                            <div class="badge rounded-pill d-flex align-items-center" 
                                style="background-color: #e7f1ff; color: #0d6efd; border: 1px solid #cfe2ff; padding: 8px 12px; font-weight: 500;">
                                
                                <span class="me-2 text-uppercase" style="font-size: 0.85rem; letter-spacing: 0.5px;">
                                    {$value['th_hab_nombre']}
                                </span>
                                
                                <button type="button" 
                                        class="btn p-0 d-flex align-items-center justify-content-center" 
                                        style="color: #0d6efd; transition: 0.3s;"
                                        onmouseover="this.style.color='#dc3545'" 
                                        onmouseout="this.style.color='#0d6efd'"
                                        onclick="delete_datos_aptitudes({$value['th_habp_id']})">
                                    <i class="bx bx-x-circle fs-5"></i>
                                </button>
                            </div>
                        HTML;
        }
        $texto .= '</div>';

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
