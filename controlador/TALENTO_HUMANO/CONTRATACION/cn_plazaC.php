<?php
date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 3) . '/modelo/TALENTO_HUMANO/CONTRATACION/cn_plazaM.php');
require_once(dirname(__DIR__, 3) . '/modelo/TALENTO_HUMANO/CONTRATACION/cn_plaza_historialM.php');


$controlador = new cn_plazaC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar($_POST['id'] ?? '', $_POST['estado'] ?? 1, $_POST['estados_plaza'] ?? ''));
}

if (isset($_GET['listar_plaza_detalle_completo'])) {
    ($controlador->listar_plaza_detalle_completo($_POST['id_plaza'] ?? ''));
}

if (isset($_GET['listar_plaza'])) {
    echo json_encode($controlador->listar_plaza($_POST['id'] ?? ''));
}

if (isset($_GET['insertar_editar'])) {
    echo json_encode($controlador->insertar_editar($_POST['parametros']));
}

if (isset($_GET['cambiar_estado_plaza'])) {
    echo json_encode($controlador->cambiar_estado_plaza($_POST['parametros']));
}

if (isset($_GET['eliminar'])) {
    echo json_encode($controlador->eliminar($_POST['_id']));
}

if (isset($_GET['buscar'])) {
    $query = isset($_GET['q']) ? $_GET['q'] : '';
    echo json_encode($controlador->buscar(['query' => $query]));
}

class cn_plazaC
{
    private $modelo;
    private $cn_plaza_historial;

    function __construct()
    {
        $this->modelo = new cn_plazaM();
        $this->cn_plaza_historial = new cn_plaza_historialM();
    }

    function listar($id = '', $estado = 1, $estados_plaza = '')
    {
        if ($id == '') {
            return $this->modelo->listar_plaza_por_id('', $estado, $estados_plaza);
        }
        return $this->modelo->listar_plaza_por_id($id);
    }

    function listar_plaza($id = '')
    {
        return $this->modelo->where('cn_pla_id', $id)->where('cn_pla_estado', 1)->listar();
    }

    public function listar_plaza_detalle_completo($id_plaza)
    {
        $id_plaza = intval($id_plaza);

        $data = $this->modelo->listar_plaza_cargo_detalle_completo($id_plaza);

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    function insertar_editar($parametros)
    {

        $datos = [
            ['campo' => 'cn_pla_titulo',              'dato' => $parametros['txt_cn_pla_titulo'] ?? ''],
            ['campo' => 'cn_pla_descripcion',         'dato' => $parametros['txt_cn_pla_descripcion'] ?? ''],
            ['campo' => 'id_cargo',                   'dato' => $parametros['ddl_cargo'] ?? null],
            ['campo' => 'th_dep_id',                  'dato' => $parametros['ddl_th_dep_id'] ?? null],
            ['campo' => 'id_tipo_seleccion',          'dato' => $parametros['ddl_id_tipo_seleccion'] ?? null],
            ['campo' => 'cn_pla_num_vacantes',        'dato' => $parametros['txt_cn_pla_num_vacantes'] ?? null],
            ['campo' => 'id_nomina',                  'dato' => $parametros['ddl_id_nomina'] ?? null],
            ['campo' => 'id_seccion',                  'dato' => $parametros['ddl_id_seccion'] ?? null],
            ['campo' => 'cn_pla_fecha_publicacion',   'dato' => $parametros['txt_cn_pla_fecha_publicacion'] ?? null],
            ['campo' => 'cn_pla_fecha_cierre',        'dato' => $parametros['txt_cn_pla_fecha_cierre'] ?? null],
            ['campo' => 'cn_pla_salario_min',         'dato' => $parametros['txt_cn_pla_salario_min'] ?? null],
            ['campo' => 'cn_pla_salario_max',         'dato' => $parametros['txt_cn_pla_salario_max'] ?? null],
            ['campo' => 'th_per_id_responsable',      'dato' => $parametros['ddl_cn_pla_responsable'] ?? null],
            ['campo' => 'cn_pla_req_disponibilidad',  'dato' => $parametros['cbx_cn_pla_req_disponibilidad'] ?? 0],
            ['campo' => 'cn_pla_req_prioridad_interna', 'dato' => $parametros['cbx_cn_pla_prioridad_interna'] ?? 0],
            ['campo' => 'cn_pla_req_documentos',      'dato' => $parametros['cbx_cn_pla_req_documentos'] ?? 0],
            ['campo' => 'cn_pla_observaciones',       'dato' => $parametros['txt_cn_pla_observaciones'] ?? null],
            ['campo' => 'cn_pla_estado',              'dato' => 1],
            ['campo' => 'cn_pla_fecha_modificacion',  'dato' => date('Y-m-d H:i:s')],
        ];

        if (empty($parametros['_id'])) {
            $id = $this->modelo->insertar_id($datos);
            return $id ? $id : 0;
        } else {
            $where = [['campo' => 'cn_pla_id', 'dato' => $parametros['_id']]];
            $this->modelo->editar($datos, $where);
            return $parametros['_id'];
        }
    }

    function eliminar($id)
    {
        $datos = [['campo' => 'cn_pla_estado', 'dato' => 0]];
        $where = [['campo' => 'cn_pla_id',     'dato' => $id]];
        return $this->modelo->editar($datos, $where);
    }


    function cambiar_estado_plaza($parametros)
    {


        $datos = [
            ['campo' => 'id_plaza_estados', 'dato' => $parametros['id_plaza_estados']]
        ];
 
        $where = [
            ['campo' => 'cn_pla_id', 'dato' => $parametros['_id']]
        ];
        $datos_plaza_his = [
            ['campo' => 'cn_pla_id',        'dato' => $parametros['_id']],
            ['campo' => 'id_plaza_estados', 'dato' => $parametros['id_plaza_estados']],
            ['campo' => 'id_usuario',       'dato' => $_SESSION['INICIO']['ID_USUARIO']],
            ['campo' => 'accion',           'dato' => $parametros['accion']],
        ];

        if ($parametros['id_plaza_estados'] == 5) {

            $datos[] = ['campo' => 'cn_pla_fecha_publicacion', 'dato' => $parametros['fecha_publicacion'] ?? null];
            $datos[] = ['campo' => 'cn_pla_fecha_cierre',      'dato' => $parametros['fecha_cierre']      ?? null];
        }

        $this->cn_plaza_historial->insertar_id($datos_plaza_his);



        return $this->modelo->editar($datos, $where);
    }



    function buscar($parametros)
    {
        $lista = [];
        $query = trim($parametros['query'] ?? '');
        $datos = $this->modelo->where('cn_pla_estado', 1)->listar();

        foreach ($datos as $plaza) {
            $titulo = $plaza['cn_pla_titulo'] ?? '';
            if ($query === '' || stripos($titulo, $query) !== false) {
                $lista[] = [
                    'id'   => $plaza['cn_pla_id'],
                    'text' => $titulo,
                ];
            }
        }
        return $lista;
    }
}
