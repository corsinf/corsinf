<?php
date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 3) . '/modelo/TALENTO_HUMANO/CONTRATACION/cn_cat_plaza_etapasM.php');

$controlador = new cn_cat_plaza_etapasC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar($_POST['id'] ?? ''));
}


if (isset($_GET['insertar_editar'])) {
    echo json_encode($controlador->insertar_editar($_POST['parametros']));
}

if (isset($_GET['eliminar'])) {
    echo json_encode($controlador->eliminar($_POST['id']));
}

if (isset($_GET['buscar'])) {
    $parametros = [
        'query' => $_GET['q'] ?? '',
    ];
    echo json_encode($controlador->buscar($parametros));
}


class cn_cat_plaza_etapasC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new cn_cat_plaza_etapasM();
    }

    function listar($id = '')
    {
        if ($id == '') {
            return $this->modelo->where('estado', 1)->listar();
        }
        return $this->modelo->where('id_etapa', $id)->where('estado', 1)->listar();
    }

    function insertar_editar($parametros)
    {
        $datos = [
            ['campo' => 'codigo',           'dato' => $parametros['txt_codigo']         ?? ''],
            ['campo' => 'nombre',           'dato' => $parametros['txt_nombre']         ?? ''],
            ['campo' => 'tipo',             'dato' => $parametros['ddl_etapa_tipo']      ?? null],
            ['campo' => 'requiere_puntaje', 'dato' => isset($parametros['chk_requiere_puntaje']) ? 1 : 0],
            ['campo' => 'es_final',         'dato' => isset($parametros['chk_es_final']) ? 1 : 0],
            ['campo' => 'estado',           'dato' => 1],
        ];

        if (empty($parametros['_id'])) {
            $datos[] = ['campo' => 'fecha_creacion', 'dato' => date('Y-m-d H:i:s')];
            $id = $this->modelo->insertar_id($datos);
            return $id ? 1 : 0;
        } else {
            $where = [['campo' => 'id_etapa', 'dato' => $parametros['_id']]];
            $this->modelo->editar($datos, $where);
            return 1;
        }
    }

    function eliminar($id)
    {
        $datos = [['campo' => 'estado', 'dato' => 0]];
        $where = [['campo' => 'id_etapa', 'dato' => $id]];
        return $this->modelo->editar($datos, $where);
    }

    function buscar($parametros)
    {
        $lista = [];
        $datos = $this->modelo->where('estado', 1)->like('nombre', $parametros['query']);

        foreach ($datos as $value) {
            $lista[] = [
                'id'   => $value['_id'],
                'text' => $value['nombre']
            ];
        }

        return $lista;
    }
   
}
