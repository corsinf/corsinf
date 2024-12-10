<?php
require_once(dirname(__DIR__, 4) . '/modelo/PASANTES/02_ADRIAN/POSTULANTES/th_pos_contacto_emergenciaM.php');

$controlador = new th_pos_contacto_emergenciaC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar($_POST['id']));
}

if (isset($_GET['insertar'])) {
    echo json_encode($controlador->insertar_editar($_POST['parametros']));
}

if (isset($_GET['eliminar'])) {
    echo json_encode($controlador->eliminar($_POST['id']));
}


class th_pos_contacto_emergenciaC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new th_pos_contacto_emergenciaM();
    }

    //Funcion para listar la formacion academica del postulante
    function listar($id)
    {
        $datos = $this->modelo->where('th_pos_id', $id)->listar();

        $tr = '';
        foreach ($datos as $key => $value) {
            $tr .=
                <<<HTML
                    <tr>
                        <td>
                            <input type="text" id="txt_id_contacto_emergencia_{$value['_id']}" value="{$value['_id']}" style="display:none;">
                            <span id="span_nombre_{$value['_id']}">{$value['th_coem_nombre_emergencia']}</span>
                            <input type="text" class="form-control form-control-sm" id="txt_nombre_contacto_emergencia_{$value['_id']}" value="{$value['th_coem_nombre_emergencia']}" style="display:none;" required maxlength="100">
                        </td>
                        <td>
                            <span id="span_telefono_{$value['_id']}">{$value['th_coem_telefono_emergencia']}</span>
                            <input type="text" class="form-control form-control-sm" id="txt_telefono_contacto_emergencia_{$value['_id']}" value="{$value['th_coem_telefono_emergencia']}" style="display:none;" required maxlength="15">
                        </td>
                        <td>
                            <button type="button" id="btn_editar_{$value['_id']}" class="btn btn-xs btn-success" onclick="mostrar_contacto_emergencia({$value['_id']});">
                                <i class="text-white bx bx-pencil bx-xs me-0"></i>
                            </button>
                            <button type="button" id="btn_guardar_{$value['_id']}" class="btn btn-xs btn-success" onclick="guardar_cambios_contacto_emergencia({$value['_id']});" style="display:none;">
                                <i class="text-white bx bx-check bx-xs me-0"></i>
                            </button>
                            <button type="button" class="btn btn-xs btn-danger" onclick="delete_datos_contacto_emergencia({$value['_id']});">
                                <i class="text-white bx bx-trash bx-xs me-0"></i>
                            </button>
                        </td>
                    </tr>
                HTML;
        }
        
        return $tr;
    }


    function insertar_editar($parametros)
    {
        $datos = array(
            array('campo' => 'th_coem_nombre_emergencia', 'dato' => $parametros['txt_nombre_contacto_emergencia']),
            array('campo' => 'th_coem_telefono_emergencia', 'dato' => $parametros['txt_telefono_contacto_emergencia']),
            array('campo' => 'th_pos_id', 'dato' => $parametros['txt_id_postulante']),

        );

        if ($parametros['_id'] == '') {
            $datos = $this->modelo->insertar($datos);
        } else {
            $where[0]['campo'] = 'th_coem_id';
            $where[0]['dato'] = $parametros['_id'];
            $datos = $this->modelo->editar($datos, $where);
        }

        return $datos;
    }

    function eliminar($id)
    {
        $datos[0]['campo'] = 'th_coem_id';
        $datos[0]['dato'] = strval($id);

        $datos = $this->modelo->eliminar($datos);
        return $datos;
    }
}
