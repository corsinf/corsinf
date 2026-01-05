<?php
require_once(dirname(__DIR__, 2) . '/modelo/TALENTO_HUMANO/th_per_comisionM.php');

$controlador = new th_per_comisionC();

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

class th_per_comisionC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new th_per_comisionM();
    }

    function listar($id)
    {
        $datos = $this->modelo->listar_comision_por_persona($id);
        $texto = '';

        foreach ($datos as $value) {

            $texto .= <<<HTML
                <div class="row mb-col">
                    <div class="col-10">
                        <p class="m-0"><strong>Código:</strong> {$value['comision_codigo']}</p>
                        <p class="m-0"><strong>Comisión:</strong> {$value['comision_nombre']}</p>
                        <p class="m-0"><strong>Descripción:</strong> {$value['comision_descripcion']}</p>
                    </div>
                    <div class="col-2 d-flex justify-content-end">
                        <button class="btn icon-hover" onclick="abrir_modal_comision('{$value['_id']}');">
                            <i class="bx bx-pencil bx-sm text-dark"></i>
                        </button>
                    </div>
                </div>
                <hr>
            HTML;
        }

        if (empty($datos)) {
            $texto = '<div class="alert alert-info">No hay registros de comisión.</div>';
        }

        return $texto;
    }

    function listar_modal($id)
    {
        return $this->modelo->listar_comision_por_id($id);
    }

    function insertar_editar($parametros)
    {
        $datos = [
            ['campo' => 'th_per_id', 'dato' => $parametros['per_id']],
            ['campo' => 'id_comision', 'dato' => $parametros['ddl_comision']]
        ];

        if ($parametros['_id'] == '') {
            return $this->modelo->insertar($datos);
        }

        $where[] = [
            'campo' => 'th_per_com_id',
            'dato'  => $parametros['_id']
        ];

        return $this->modelo->editar($datos, $where);
    }

    function eliminar($id)
    {
        $datos = [
            ['campo' => 'th_per_com_estado', 'dato' => 0]
        ];

        $where[] = [
            'campo' => 'th_per_com_id',
            'dato'  => intval($id)
        ];

        return $this->modelo->editar($datos, $where);
    }
}
