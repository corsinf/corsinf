<?php
require_once(dirname(__DIR__, 3) . '/modelo/TALENTO_HUMANO/CONTRATACION/cn_plaza_historialM.php');
require_once(dirname(__DIR__, 3) . '/modelo/TALENTO_HUMANO/CONTRATACION/cn_plazaM.php');


$controlador = new cn_plaza_historialC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar($_POST['id'] ?? ''));
}

if (isset($_GET['insertar_editar'])) {
    echo json_encode($controlador->insertar_editar($_POST['parametros']));
}

if (isset($_GET['eliminar'])) {
    echo json_encode($controlador->eliminar($_POST['id']));
}
if (isset($_GET['listar_por_orden'])) {
    echo json_encode($controlador->listar_por_orden($_POST['orden'] ?? 1));
}

class cn_plaza_historialC
{
    private $modelo;
    private $cn_plaza;

    function __construct()
    {
        $this->modelo = new cn_plaza_historialM();
        $this->cn_plaza = new cn_plazaM();
    }

    function listar($id_plaza = '')
    {
        return $this->modelo->listar_historial_plaza($id_plaza);
    }

    function listar_por_orden($orden = 1)
    {
        return $this->modelo->where('estado', 1)->where('orden', intval($orden))->where('is_delete', 0)->listar();
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

        $datos = [
            ['campo' => 'is_delete', 'dato' => 1],
            ['campo' => 'modificado_usuario', 'dato' => $_SESSION['INICIO']['ID_USUARIO']],
        ];

        $plaza_historial = $this->modelo->where('id_plaza_historial', $id)->listar();
        $id_plaza_estados = 0;
        $cn_pla_id = 0;
        if ($plaza_historial) {

            if ($plaza_historial[0]['id_plaza_estados'] >= 2 && $plaza_historial[0]['id_plaza_estados'] <= 3) {
                $id_plaza_estados = 1;
            } else if ($plaza_historial[0]['id_plaza_estados'] == 5) {
                $id_plaza_estados = 2;
            } else if ($plaza_historial[0]['id_plaza_estados'] >= 7 && $plaza_historial[0]['id_plaza_estados'] <= 10) {
                $id_plaza_estados = 6;
            }
            $cn_pla_id = $plaza_historial[0]['cn_pla_id'];
        }

        $datos_plaza = [['campo' => 'id_plaza_estados', 'dato' => $id_plaza_estados]];
        $where_plaza = [['campo' => 'cn_pla_id',     'dato' => $cn_pla_id]];

        $this->cn_plaza->editar($datos_plaza, $where_plaza);

        $where = [['campo' => 'id_plaza_historial',     'dato' => $id]];
        return $this->modelo->editar($datos, $where);
    }
}
