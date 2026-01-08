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
                    <p class="m-0"><strong>Escala:</strong> {$value['th_pos_dis_escala']}</p>
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
        $this->modelo->reset();

        $existe = $this->modelo
            ->where('th_pos_id', $parametros['pos_id'])
            ->where('id_discapacidad', $parametros['ddl_discapacidad'])
            ->listar();

        if ($parametros['_id'] == '') {

            if (count($existe) > 0) {
                return -2;
            }

            $datos = [
                ['campo' => 'th_pos_id', 'dato' => $parametros['pos_id']],
                ['campo' => 'id_discapacidad', 'dato' => $parametros['ddl_discapacidad']],
                ['campo' => 'th_pos_dis_porcentaje', 'dato' => $parametros['txt_porcentaje']],
                ['campo' => 'th_pos_dis_escala', 'dato' => $parametros['txt_escala']],
            ];

            return $this->modelo->insertar($datos);
        }

        $this->modelo->reset();

        $existe = $this->modelo
            ->where('th_pos_id', $parametros['pos_id'])
            ->where('id_discapacidad', $parametros['ddl_discapacidad'])
            ->where('th_pos_dis_id !', $parametros['_id'])
            ->listar();

        if (count($existe) > 0) {
            return -2;
        }

        $datos = [
            ['campo' => 'th_pos_id', 'dato' => $parametros['pos_id']],
            ['campo' => 'id_discapacidad', 'dato' => $parametros['ddl_discapacidad']],
            ['campo' => 'th_pos_dis_porcentaje', 'dato' => $parametros['txt_porcentaje']],
            ['campo' => 'th_pos_dis_escala', 'dato' => $parametros['txt_escala']],
        ];

        $where[] = [
            'campo' => 'th_pos_dis_id',
            'dato'  => $parametros['_id']
        ];

        return $this->modelo->editar($datos, $where);
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
