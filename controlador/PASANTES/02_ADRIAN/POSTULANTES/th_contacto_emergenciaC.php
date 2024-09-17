<?php
require_once(dirname(__DIR__, 4) . '/modelo/PASANTES/02_ADRIAN/POSTULANTES/th_contacto_emergenciaM.php');

$controlador = new th_contacto_emergenciaC();

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


class th_contacto_emergenciaC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new th_contacto_emergenciaM();
    }

    //Funcion para listar la formacion academica del postulante
    function listar($id)
    {
        $datos = $this->modelo->where('th_pos_id', $id)->listar();

        $tr = '';
        foreach ($datos as $key => $value) {
            $tr .=
                "<tr>
                    <td>" . $value['th_coem_nombre_emergencia'] . "</td>
                    <td>" . $value['th_coem_telefono_emergencia'] . "</td>
                    <td>
                        <button class='btn btn-sm btn-primary' onclick='abrir_modal_formacion_academica(" . $value['_id'] . ");'><i class='text-white bx bx-pencil bx-xs me-0'></i></button>    
                        <button class='btn btn-sm btn-danger' onclick='abrir_modal_formacion_academica(" . $value['_id'] . ");'><i class='text-white bx bx-trash bx-xs me-0'></i></button>
                    </td>
                </tr>";
        }
        return $tr;
    }

    //Buscando registros por id de la formacion academica
    function listar_modal($id)
    {
        $datos = $this->modelo->where('th_coem_id', $id)->listar();
        return $datos;
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
        $where[0]['campo'] = 'th_coem_id';
        $where[0]['dato'] = strval($id);

        $datos = $this->modelo->eliminar($where);

        return $datos;
    }
}
