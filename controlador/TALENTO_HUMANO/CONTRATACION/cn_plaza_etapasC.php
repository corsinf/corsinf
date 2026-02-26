<?php
date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 3) . '/modelo/TALENTO_HUMANO/CONTRATACION/cn_plaza_etapasM.php');

$controlador = new cn_plaza_etapasC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar($_POST['id'] ?? '', $_POST['id_plaza'] ?? ''));
}

if (isset($_GET['insertar_editar'])) {
    echo json_encode($controlador->insertar_editar($_POST['parametros']));
}

if (isset($_GET['eliminar'])) {
    echo json_encode($controlador->eliminar($_POST['_id']));
}

if (isset($_GET['guardar_bulk'])) {
    echo json_encode($controlador->guardar_bulk(
        $_POST['id_plaza']         ?? '',
        $_POST['lista_destino']    ?? [],
        $_POST['lista_origen']     ?? []
    ));
}

if (isset($_GET['crear_plaza_etapas'])) {
    echo json_encode($controlador->crear_plaza_etapas(
        $_POST['cn_pla_id'] ?? 0,
    ));
}


class cn_plaza_etapasC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new cn_plaza_etapasM();
    }

    function listar($id = '', $id_plaza = '')
    {
        if ($id != '') {
            return $this->modelo->where('estado', 1)->listar();
        } else if ($id_plaza != '') {
            return $this->modelo->listar_etapas_por_plaza($id_plaza);
        } else {

            return $this->modelo->where('cn_pla_id', $id)->where('estado', 1)->listar();
        }
    }

    function insertar_editar($parametros)
    {
        $datos = [
            ['campo' => 'cn_pla_id',           'dato' => $parametros['cn_pla_id']            ?? null],
            ['campo' => 'id_etapa',             'dato' => $parametros['id_etapa']             ?? null],
            ['campo' => 'cn_plaet_orden',       'dato' => $parametros['cn_plaet_orden']       ?? null],
            ['campo' => 'cn_plaet_obligatoria', 'dato' => $parametros['cn_plaet_obligatoria'] ?? 0],
            ['campo' => 'estado',               'dato' => 1],
        ];

        if (empty($parametros['_id'])) {
            $datos[] = ['campo' => 'fecha_creacion', 'dato' => date('Y-m-d H:i:s')];
            return $this->modelo->insertar_id($datos) ? 1 : 0;
        } else {
            $where = [['campo' => 'cn_plaet_id', 'dato' => $parametros['_id']]];
            return $this->modelo->editar($datos, $where);
        }
    }

    function eliminar($id)
    {
        $datos = [['campo' => 'cn_plaet_id', 'dato' => $id]];
        return $this->modelo->eliminar($datos);
    }

    function crear_plaza_etapas($cn_pla_id)
    {
        if (empty($cn_pla_id)) {
            return ['error' => 'Parámetros inválidos'];
        }
        return $this->modelo->ejecutar_crear_plaza_etapas($cn_pla_id);
    }
    /**
     * Guarda en bloque las etapas de una plaza desde el drag & drop.
     *
     * - lista_destino: etapas que DEBEN quedar asignadas (con su orden)
     * - lista_origen:  etapas movidas de vuelta a origen → se eliminan (estado = 0)
     */
    function guardar_bulk($id_plaza, $lista_destino, $lista_origen)
    {
        if ($id_plaza === '') return -1;

        // PASO 1: Desactivar etapas devueltas al origen
        foreach ($lista_origen as $item) {
            if (!empty($item['txt_id_plaet'])) {
                $this->eliminar($item['txt_id_plaet']);
            }
        }

        // PASO 2a: Poner órdenes negativos temporales para evitar colisión de unique key de orden
        foreach ($lista_destino as $item) {
            $id_plaet = $item['txt_id_plaet'] ?? '';
            $orden    = $item['txt_orden']    ?? 0;

            if (!empty($id_plaet)) {
                $datos = [['campo' => 'cn_plaet_orden', 'dato' => $orden * -1]];
                $where = [['campo' => 'cn_plaet_id', 'dato' => $id_plaet]];
                $this->modelo->editar($datos, $where);
            }
        }

        // PASO 2b: Insertar nuevos / actualizar orden definitivo
        foreach ($lista_destino as $item) {
            $id_plaet    = $item['txt_id_plaet']   ?? '';
            $id_etapa    = $item['txt_id_etapa']   ?? '';
            $orden       = $item['txt_orden']       ?? 0;
            $obligatoria = !empty($item['txt_obligatoria']) ? 1 : 0;

            if ($id_etapa === '') continue;

            if (empty($id_plaet)) {
                // Verificar si ya existe el par (cn_pla_id, id_etapa) antes de insertar
                $existe = $this->modelo->buscar_existente($id_plaza, $id_etapa);

                if ($existe) {
                    // Ya existe pero sin id_plaet en el frontend → solo actualizar
                    $datos = [
                        ['campo' => 'cn_plaet_orden',       'dato' => $orden],
                        ['campo' => 'cn_plaet_obligatoria', 'dato' => $obligatoria],
                        ['campo' => 'estado',                'dato' => 1],
                    ];
                    $where = [['campo' => 'cn_plaet_id', 'dato' => $existe]];
                    $this->modelo->editar($datos, $where);
                } else {
                    // Realmente es nuevo
                    $datos = [
                        ['campo' => 'cn_pla_id',           'dato' => $id_plaza],
                        ['campo' => 'id_etapa',             'dato' => $id_etapa],
                        ['campo' => 'cn_plaet_orden',       'dato' => $orden],
                        ['campo' => 'cn_plaet_obligatoria', 'dato' => $obligatoria],
                        ['campo' => 'estado',               'dato' => 1],
                        ['campo' => 'fecha_creacion',       'dato' => date('Y-m-d H:i:s')],
                    ];
                    $this->modelo->insertar($datos);
                }
            } else {
                // Actualizar orden definitivo
                $datos = [
                    ['campo' => 'cn_plaet_orden',       'dato' => $orden],
                    ['campo' => 'cn_plaet_obligatoria', 'dato' => $obligatoria],
                    ['campo' => 'estado',                'dato' => 1],
                ];
                $where = [['campo' => 'cn_plaet_id', 'dato' => $id_plaet]];
                $this->modelo->editar($datos, $where);
            }
        }

        return 1;
    }
}
