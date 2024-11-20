<?php
require_once(dirname(__DIR__, 4) . '/modelo/PASANTES/02_ADRIAN/POSTULANTES/th_pos_documentosM.php');

$controlador = new th_pos_documentosC();

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


class th_pos_documentosC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new th_pos_documentosM();
    }

    //Funcion para listar los documentos de identificación del postulante
    function listar($id)
    {
        $datos = $this->modelo->where('th_pos_id', $id)->where('th_poi_estado', 1)->listar();

        $texto = '';
        foreach ($datos as $key => $value) {

            //$fecha_fin = $value['th_expl_fecha_fin_experiencia'] == '' ? 'Actualidad' : $value['th_expl_fecha_fin_experiencia'];

            $texto .=
                <<<HTML
                    <div class="row mb-col">
                        <div class="col-10">
                            <h6 class="fw-bold mt-3 mb-2">{$value['th_poi_tipo']}</h6>
                            <!-- <p class="m-0">{$value['th_expl_cargos_ocupados']}</p> -->
                          
                        </div>
                        <div class="col-2 d-flex justify-content-end align-items-start">
                            <button class="btn" style="color: white;" onclick="abrir_modal_documento_identificacion({$value['_id']});">
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
            $datos = $this->modelo->where('th_poi_estado', 1)->listar();
        } else {
            $datos = $this->modelo->where('th_poi_id', $id)->listar();
        }
        return $datos;
    }

    function insertar_editar($parametros)
    {
        $datos = array(
            array('campo' => 'th_pos_id', 'dato' => $parametros['txt_id_postulante']),
            array('campo' => 'th_poi_tipo', 'dato' => $parametros['ddl_tipo_documento_identidad']),
            array('campo' => 'th_poi_ruta_archivo', 'dato' => $parametros['txt_agregar_documento_identidad']),
      
        );

        if ($parametros['_id'] == '') {
            $datos = $this->modelo->insertar($datos);
        } else {
            $where[0]['campo'] = 'th_poi_id';
            $where[0]['dato'] = $parametros['_id'];
            $datos = $this->modelo->editar($datos, $where);
        }

        return $datos;
    }

    function eliminar($id)
    {
        $datos = array(
            array('campo' => 'th_poi_estado', 'dato' => 0),
        );

        $where[0]['campo'] = 'th_poi_id';
        $where[0]['dato'] = strval($id);

        $datos = $this->modelo->eliminar($datos, $where);

        return $datos;
    }
}
