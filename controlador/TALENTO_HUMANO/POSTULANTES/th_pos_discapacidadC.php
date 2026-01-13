<?php
require_once(dirname(__DIR__, 3) . '/modelo/TALENTO_HUMANO/POSTULANTES/th_pos_discapacidadM.php');

$controlador = new th_pos_discapacidadC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar($_POST['id']));
}

if (isset($_GET['listar_modal'])) {
    echo json_encode($controlador->listar_modal($_POST['id']));
}

if (isset($_GET['insertar'])) {
    echo json_encode($controlador->guardar($_POST['parametros']));
}

if (isset($_GET['eliminar'])) {
    echo json_encode($controlador->eliminar($_POST['id']));
}

class th_pos_discapacidadC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new th_pos_discapacidadM();
    }

    function listar($id)
    {
        $datos = $this->modelo->listar_por_persona($id);
        $texto = '';

        if (empty($datos)) {
            return '<div class="alert alert-info">No registra discapacidad.</div>';
        }

        foreach ($datos as $value) {

            $texto .= <<<HTML
            <div class="row mb-col">
                <div class="col-10">
                    <p class="m-0"><strong>Discapacidad:</strong> {$value['discapacidad']}</p>
                    <p class="m-0"><strong>Porcentaje:</strong> {$value['th_pos_dis_porcentaje']}%</p>
                    <p class="m-0"><strong>Escala:</strong> {$value['escala_discapacidad']}</p>
                </div>
                <div class="col-2 d-flex justify-content-end">
                    <button class="btn icon-hover"
                        onclick="abrir_modal_discapacidad('{$value['_id']}');">
                        <i class="bx bx-pencil bx-sm text-dark"></i>
                    </button>
                </div>
            </div>
            <hr>
        HTML;
        }

        return $texto;
    }


    function listar_modal($id)
    {
        return $this->modelo->listar_por_id($id);
    }

    function guardar($parametros)
    {
        $id_postulante = $parametros['pos_id'];
        $id_discapacidad = $parametros['ddl_discapacidad'];
        $id_escala = $parametros['ddl_discapacidad_escala'];
        $id_registro = $parametros['_id'];

        $this->modelo->reset();
        $this->modelo->where('th_pos_id', $id_postulante)
            ->where('id_discapacidad', $id_discapacidad)
            ->where('id_escala_dis', $id_escala);

        if ($id_registro != '') {
            $this->modelo->where('th_pos_dis_id !', $id_registro);
        }

        $existe = $this->modelo->listar();

        if (count($existe) > 0) {
            return -2;
        }

        $datos = [
            ['campo' => 'th_pos_id', 'dato' => $id_postulante],
            ['campo' => 'id_discapacidad', 'dato' => $id_discapacidad],
            ['campo' => 'id_escala_dis', 'dato' => $id_escala],
            ['campo' => 'th_pos_dis_porcentaje', 'dato' => $parametros['txt_porcentaje']],
        ];

        if ($id_registro == '') {
            return $this->modelo->insertar($datos);
        } else {
            $where[] = [
                'campo' => 'th_pos_dis_id',
                'dato'  => $id_registro
            ];
            return $this->modelo->editar($datos, $where);
        }
    }


    function eliminar($id)
    {
        $datos = array(
            array('campo' => 'th_pos_dis_estado', 'dato' => 0),
        );

        $where[0]['campo'] = 'th_pos_dis_id';
        $where[0]['dato'] = strval($id);
        $datos = $this->modelo->eliminar($where);

        return $datos;
    }
}
