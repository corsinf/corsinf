<?php
date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 2) . '/modelo/XPACE_CUBE/movimiento_bodegaM.php');

$controlador = new movimiento_bodegaC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar($_POST['id'] ?? ''));
}

if (isset($_GET['insertar'])) {
    echo json_encode($controlador->insertar_editar($_POST['parametros']));
}

if (isset($_GET['eliminar'])) {
    echo json_encode($controlador->eliminar($_POST['id']));
}

if (isset($_GET['buscar'])) {
    $query = '';

    if (isset($_GET['q'])) {
        $query = $_GET['q'];
    }

    $parametros = array(
        'query' => $query,
    );

    echo json_encode($controlador->buscar($parametros));
}


class  movimiento_bodegaC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new movimiento_bodegaM();
    }

    function listar($id = '')
    {
        if ($id == "") {
            $datos = $this->modelo->where('estado', 1)->listar_movimientos();
        } else {
            $datos = $this->modelo->where('estado', 1)->listar_movimientos($id);
        }

        return $datos;
    }

    public function insertar_editar($parametros)
    {
        // Normalizar fecha de movimiento (si viene en formato 'YYYY-MM-DD HH:MM:SS' o similar)
        $fecha_movimiento = null;
        if (!empty($parametros['txt_fecha_movimiento'])) {
            $ts = strtotime($parametros['txt_fecha_movimiento']);
            $fecha_movimiento = $ts ? date('Y-m-d H:i:s', $ts) : null;
        }

        // Normalizar cantidad
        $cantidad = 0;
        if (isset($parametros['txt_cantidad']) && $parametros['txt_cantidad'] !== '') {
            $cantidad = (int) $parametros['txt_cantidad'];
            if ($cantidad < 0) $cantidad = 0;
        }

        // Valores por defecto/seguridad
        $id_articulo = isset($parametros['ddl_articulo']) && $parametros['ddl_articulo'] !== '' ? (int)$parametros['ddl_articulo'] : null;
        $id_reserva = isset($parametros['ddl_reserva']) && $parametros['ddl_reserva'] !== '' ? (int)$parametros['ddl_reserva'] : null;
        $tipo_movimiento = isset($parametros['txt_tipo_movimiento']) ? trim($parametros['txt_tipo_movimiento']) : '';
        $motivo = isset($parametros['txt_motivo']) ? trim($parametros['txt_motivo']) : '';
        $estado = isset($parametros['estado']) ? (int)$parametros['estado'] : 1; // por defecto activo

        // Preparar array de datos para insertar/editar (siguiendo estructura de tu BaseModel)
        $datos = array(
            array('campo' => 'id_articulo', 'dato' => $id_articulo),
            array('campo' => 'id_reserva', 'dato' => $id_reserva),
            array('campo' => 'tipo_movimiento', 'dato' => $tipo_movimiento),
            array('campo' => 'cantidad', 'dato' => $cantidad),
            array('campo' => 'motivo', 'dato' => $motivo),
            array('campo' => 'fecha_movimiento', 'dato' => $fecha_movimiento),
            array('campo' => 'estado', 'dato' => $estado),
        );
        if (empty($parametros['_id'])) {
            $resultado = $this->modelo->insertar($datos);
        } else {
            $where = array();
            $where[0]['campo'] = 'id_movimiento';
            $where[0]['dato'] = (int)$parametros['_id'];
            $resultado = $this->modelo->editar($datos, $where);
        }

        return $resultado;
    }


    function eliminar($id) {}

    //Para usar en select2
    function buscar($parametros) {}
}
