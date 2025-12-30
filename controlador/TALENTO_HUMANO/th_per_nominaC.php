<?php
require_once(dirname(__DIR__, 2) . '/modelo/TALENTO_HUMANO/th_per_nominaM.php');

$controlador = new th_per_nominaC();

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

class th_per_nominaC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new th_per_nominaM();
    }

    function listar($id)
    {
        $datos = $this->modelo->listar_nomina_por_persona($id);

        $texto = '';

        foreach ($datos as $value) {

            $nomina = $value['nomina_nombre'] ?? 'N/A';
            $tipo   = $value['nomina_tipo'] ?? 'N/A';
            $remu   = number_format($value['th_per_nom_remuneracion'], 2);

            $fecha_ini = $value['th_per_nom_fecha_ini']
                ? date('d/m/Y', strtotime($value['th_per_nom_fecha_ini']))
                : 'N/A';

            $fecha_fin = $value['th_per_nom_fecha_fin']
                ? date('d/m/Y', strtotime($value['th_per_nom_fecha_fin']))
                : 'Actual';

            $texto .= <<<HTML
                <div class="row mb-col">
                    <div class="col-10">
                        <p class="m-0"><strong>Nómina:</strong> {$nomina}</p>
                        <p class="m-0"><strong>Tipo:</strong> {$tipo}</p>
                        <p class="m-0"><strong>Remuneración:</strong> $ {$remu}</p>
                        <p class="m-0"><strong>Desde:</strong> {$fecha_ini}</p>
                        <p class="m-0"><strong>Hasta:</strong> {$fecha_fin}</p>
                    </div>
                    <div class="col-2 d-flex justify-content-end">
                        <button class="btn icon-hover" onclick="abrir_modal_nomina('{$value['_id']}');">
                            <i class="bx bx-pencil bx-sm text-dark"></i>
                        </button>
                    </div>
                </div>
                <hr>
            HTML;
        }

        if (empty($datos)) {
            $texto = '<div class="alert alert-info">No hay registros de nómina.</div>';
        }

        return $texto;
    }

    function listar_modal($id)
    {
        return $this->modelo->listar_nomina_por_id($id);
    }

    function insertar_editar($parametros)
    {
        $datos = [
            ['campo' => 'th_per_id', 'dato' => $parametros['per_id']],
            ['campo' => 'id_nomina', 'dato' => $parametros['ddl_nomina']],
            ['campo' => 'th_per_nom_remuneracion', 'dato' => $parametros['txt_remuneracion']],
            ['campo' => 'th_per_nom_fecha_ini', 'dato' => $parametros['txt_fecha_ini']],
            ['campo' => 'th_per_nom_fecha_fin', 'dato' => $parametros['txt_fecha_fin']],
        ];

        if ($parametros['_id'] == '') {
            return $this->modelo->insertar($datos);
        }

        $where[0] = [
            'campo' => 'th_per_nom_id',
            'dato'  => $parametros['_id']
        ];

        return $this->modelo->editar($datos, $where);
    }

    function eliminar($id)
    {
        $datos = [
            ['campo' => 'th_per_nom_estado', 'dato' => 0]
        ];

        $where[0] = [
            'campo' => 'th_per_nom_id',
            'dato'  => intval($id)
        ];

        return $this->modelo->editar($datos, $where);
    }
}
