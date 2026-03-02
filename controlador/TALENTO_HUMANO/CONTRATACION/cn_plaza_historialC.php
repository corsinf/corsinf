<?php
require_once(dirname(__DIR__, 3) . '/modelo/TALENTO_HUMANO/CONTRATACION/cn_plaza_historialM.php');

$controlador = new cn_plaza_historialC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar($_POST['cn_pla_id'] ?? ''));
}

if (isset($_GET['insertar_editar'])) {
    echo json_encode($controlador->insertar_editar($_POST['parametros']));
}

if (isset($_GET['eliminar'])) {
    echo json_encode($controlador->eliminar($_POST['_id']));
}

class cn_plaza_historialC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new cn_plaza_historialM();
    }

    function listar($id_plaza = '')
    {
        if ($id_plaza != '') {
            $this->modelo->where('cn_pla_id', $id_plaza);
        }
        return $this->modelo->listar();
    }

    function guardar_estado($id)
    {
        $datos = [['campo' => 'estado', 'dato' => 0]];
        $where = [['campo' => 'id_plaza_estados', 'dato' => $id]];
        return $this->modelo->editar($datos, $where);
    }

    function insertar_editar($parametros)
    {
        $datos = [
            ['campo' => 'cn_pla_id',        'dato' => $parametros['cn_pla_id']],
            ['campo' => 'id_plaza_estados', 'dato' => $parametros['id_plaza_estados']],
            ['campo' => 'id_usuario',       'dato' => $_SESSION['INICIO']['ID_USUARIO']],
            ['campo' => 'accion',           'dato' => $parametros['txt_accion']],
        ];

        if (empty($parametros['_id'])) {
            $datos[] = ['campo' => 'fecha_creacion', 'dato' => date('Y-m-d H:i:s')];
            return $this->modelo->insertar($datos);
        } else {
            $where = [['campo' => 'id_plaza_historial', 'dato' => $parametros['_id']]];
            return $this->modelo->editar($datos, $where);
        }
    }

    function eliminar($id)
    {
        $datos = [['campo' => 'id_plaza_historial', 'dato' => $id]];
        return $this->modelo->eliminar($datos);
    }
}
