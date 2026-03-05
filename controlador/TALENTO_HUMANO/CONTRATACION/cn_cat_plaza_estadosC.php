<?php
require_once(dirname(__DIR__, 3) . '/modelo/TALENTO_HUMANO/CONTRATACION/cn_cat_plaza_estadosM.php');

$controlador = new cn_cat_plaza_estadosC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar($_POST['id'] ?? ''));
}

if (isset($_GET['insertar_editar'])) {
    echo json_encode($controlador->insertar_editar($_POST['parametros']));
}

if (isset($_GET['eliminar'])) {
    echo json_encode($controlador->eliminar($_POST['_id']));
}


if (isset($_GET['buscar_plaza_estados'])) {

    $parametros = array(
        'query'    => isset($_GET['q']) ? $_GET['q'] : '',
        'orden'   => isset($_GET['orden']) ? $_GET['orden'] : 0
    );

    $datos = $controlador->buscar_plaza_estados($parametros);
    echo json_encode($datos);
    exit;
}

class cn_cat_plaza_estadosC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new cn_cat_plaza_estadosM();
    }

    function listar($id = '')
    {
        return $this->modelo->where('estado', 1)->listar($id);
    }

    function insertar_editar($parametros)
    {
        $datos = [
            ['campo' => 'codigo',              'dato' => $parametros['txt_codigo']],
            ['campo' => 'descripcion',         'dato' => $parametros['txt_descripcion']],
            ['campo' => 'orden',               'dato' => $parametros['txt_orden']],
            ['campo' => 'editable',            'dato' => $parametros['cbx_editable']],
            ['campo' => 'permite_postulacion', 'dato' => $parametros['cbx_permite_postulacion']],
            ['campo' => 'permite_evaluacion',  'dato' => $parametros['cbx_permite_evaluacion']],
            ['campo' => 'visible_postulantes', 'dato' => $parametros['cbx_visible_postulantes']],
            ['campo' => 'estado',              'dato' => 1],
        ];

        if (empty($parametros['_id'])) {
            $datos[] = ['campo' => 'fecha_creacion', 'dato' => date('Y-m-d H:i:s')];
            return $this->modelo->insertar($datos);
        } else {
            $where = [['campo' => 'id_plaza_estados', 'dato' => $parametros['_id']]];
            return $this->modelo->editar($datos, $where);
        }
    }

    function eliminar($id)
    {

        $datos = [
            ['campo' => 'is_delete', 'dato' => 1],
            ['campo' => 'modificado_usuario', 'dato' => $_SESSION['INICIO']['ID_USUARIO']],
        ];
        $where = [['campo' => 'id_plaza_estados',     'dato' => $id]];
        return $this->modelo->editar($datos, $where);
    }


    public function buscar_plaza_estados($parametros)
    {
        $lista = [];

        $concat = "codigo, descripcion";

        $orden = isset($parametros['orden']) ? (int)$parametros['orden'] : 0;

        $datos = $this->modelo->where('estado', 1)->where('orden', $orden)->like($concat, $parametros['query']);

        foreach ($datos as $key => $value) {
            $text = $value['descripcion'];
            $lista[] = array('id' => ($value['id_plaza_estados']), 'text' => ($text), /* 'data' => $value */);
        }

        return $lista;
    }
}
