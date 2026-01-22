<?php

/**
 * @deprecated Archivo dado de baja el 22/01/2026.
 * @note Este archivo se mantiene como respaldo, pero ya no se utilizará en producción.
 * @warning No modificar este archivo. Para cambios, referirse a la nueva implementación.
 */

require_once(dirname(__DIR__, 3)  . '/modelo/TALENTO_HUMANO/POSTULANTES/th_pos_contacto_emergenciaM.php');

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
        foreach ($datos as $value) {

            $tr .= <<<HTML
        <tr>
            <!-- NOMBRE -->
            <td class="align-middle px-2 py-2">
                <input type="hidden"
                    id="txt_id_contacto_emergencia_{$value['_id']}"
                    value="{$value['_id']}">

                <span id="span_nombre_{$value['_id']}" class="d-block">
                    {$value['th_coem_nombre_emergencia']}
                </span>

                <input type="text"
                    class="form-control form-control-sm w-100 mt-1"
                    id="txt_nombre_contacto_emergencia_{$value['_id']}"
                    value="{$value['th_coem_nombre_emergencia']}"
                    style="display:none;"
                    required maxlength="100">
            </td>

            <!-- TELÉFONO -->
            <td class="align-middle px-2 py-2">
                <span id="span_telefono_{$value['_id']}" class="d-block">
                    {$value['th_coem_telefono_emergencia']}
                </span>

                <input type="text"
                    class="form-control form-control-sm w-100 mt-1"
                    id="txt_telefono_contacto_emergencia_{$value['_id']}"
                    value="{$value['th_coem_telefono_emergencia']}"
                    style="display:none;"
                    required maxlength="15">
            </td>

            <!-- ACCIONES -->
            <td class="align-middle px-2 py-2 text-center">
                <div class="d-flex justify-content-center gap-1">

                    <button type="button"
                        id="btn_editar_{$value['_id']}"
                        class="btn btn-sm btn-success"
                        onclick="mostrar_contacto_emergencia({$value['_id']});">
                        <i class="bx bx-pencil"></i>
                    </button>

                    <button type="button"
                        id="btn_guardar_{$value['_id']}"
                        class="btn btn-sm btn-primary"
                        onclick="guardar_cambios_contacto_emergencia({$value['_id']});"
                        style="display:none;">
                        <i class="bx bx-check"></i>
                    </button>

                    <button type="button"
                        class="btn btn-sm btn-danger"
                        onclick="delete_datos_contacto_emergencia({$value['_id']});">
                        <i class="bx bx-trash"></i>
                    </button>

                </div>
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
