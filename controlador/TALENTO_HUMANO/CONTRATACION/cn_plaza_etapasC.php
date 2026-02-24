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

// ✅ NUEVO: guardar en bloque desde el drag & drop
if (isset($_GET['guardar_bulk'])) {
    echo json_encode($controlador->guardar_bulk(
        $_POST['id_plaza']         ?? '',
        $_POST['lista_destino']    ?? [],
        $_POST['lista_origen']     ?? []
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

    /**
     * Guarda en bloque las etapas de una plaza desde el drag & drop.
     *
     * - lista_destino: etapas que DEBEN quedar asignadas (con su orden)
     * - lista_origen:  etapas movidas de vuelta a origen → se eliminan (estado = 0)
     */
    function guardar_bulk($id_plaza, $lista_destino, $lista_origen)
    {
        if ($id_plaza === '') return -1;

        // 1. Etapas que vuelven al origen → desactivar
        foreach ($lista_origen as $item) {
            if (!empty($item['txt_id_plaet'])) {
                $this->eliminar($item['txt_id_plaet']);
            }
        }

        // 2. Etapas en destino → insertar si son nuevas, actualizar orden si ya existen
        foreach ($lista_destino as $item) {
            $id_plaet  = $item['txt_id_plaet'] ?? '';
            $id_etapa  = $item['txt_id_etapa'] ?? '';
            $orden     = $item['txt_orden']    ?? 0;

            if ($id_etapa === '') continue;

            if (empty($id_plaet)) {
                // Nueva asignación
                $datos = [
                    ['campo' => 'cn_pla_id',           'dato' => $id_plaza],
                    ['campo' => 'id_etapa',             'dato' => $id_etapa],
                    ['campo' => 'cn_plaet_orden',       'dato' => $orden],
                    ['campo' => 'cn_plaet_obligatoria', 'dato' => 0],
                    ['campo' => 'estado',               'dato' => 1],
                    ['campo' => 'fecha_creacion',       'dato' => date('Y-m-d H:i:s')],
                ];
                $this->modelo->insertar_id($datos);
            } else {
                // Actualizar orden
                $datos = [
                    ['campo' => 'cn_plaet_orden', 'dato' => $orden],
                    ['campo' => 'estado',          'dato' => 1],
                ];
                $where = [['campo' => 'cn_plaet_id', 'dato' => $id_plaet]];
                $this->modelo->editar($datos, $where);
            }
        }

        return 1;
    }
}
