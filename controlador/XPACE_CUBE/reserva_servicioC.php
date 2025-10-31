<?php
date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 2) . '/modelo/XPACE_CUBE/reserva_servicioM.php');

$controlador = new reserva_servicioC();

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


class  reserva_servicioC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new reserva_servicioM();
    }

    function listar($id = '')
    {
        $datos = $this->modelo->listar_servicios_por_reserva($id);
        return $datos ?: []; // devuelve array vacío si no hay datos
    }

    public function insertar_editar($parametros)
    {
        // Normalizar entradas esperadas
        $id_reserva = (isset($parametros['ddl_reserva']) && $parametros['ddl_reserva'] !== '')
            ? (int)$parametros['ddl_reserva'] : null;

        $id_servicio = (isset($parametros['ddl_servicio']) && $parametros['ddl_servicio'] !== '')
            ? (int)$parametros['ddl_servicio'] : null;

        $cantidad = (isset($parametros['txt_cantidad']) && $parametros['txt_cantidad'] !== '')
            ? (int)$parametros['txt_cantidad'] : 1;
        if ($cantidad < 0) $cantidad = 0;

        $precio_unitario = (isset($parametros['txt_precio_unitario']) && $parametros['txt_precio_unitario'] !== '')
            ? (float)str_replace(',', '.', $parametros['txt_precio_unitario']) : 0.00;
        if ($precio_unitario < 0) $precio_unitario = 0.00;

        // checkbox por_hora puede venir como 'on' o '1' o no venir
        $por_hora = 0;
        if (isset($parametros['chk_por_hora'])) {
            $val = $parametros['chk_por_hora'];
            if ($val === 'on' || $val === '1' || $val === 1 || $val === true) {
                $por_hora = 1;
            }
        }

        $estado = isset($parametros['estado']) && $parametros['estado'] !== ''
            ? (int)$parametros['estado'] : 1; // 1 activo por defecto

        // Validaciones básicas
        if ($id_reserva === null) {
            return ['success' => false, 'message' => 'Falta seleccionar la reserva.'];
        }

        if ($id_servicio === null) {
            return ['success' => false, 'message' => 'Falta seleccionar el servicio.'];
        }

        if ($cantidad <= 0) {
            return ['success' => false, 'message' => 'La cantidad debe ser mayor a 0.'];
        }

        // Preparar array de datos según BaseModel
        $datos = [
            ['campo' => 'id_reserva', 'dato' => $id_reserva],
            ['campo' => 'id_servicio', 'dato' => $id_servicio],
            ['campo' => 'cantidad', 'dato' => $cantidad],
            ['campo' => 'precio_unitario', 'dato' => $precio_unitario],
            ['campo' => 'por_hora', 'dato' => $por_hora],
            ['campo' => 'estado', 'dato' => $estado],
        ];

        // Insertar o editar
        if (empty($parametros['_id'])) {
            // insertar
            $resultado = $this->modelo->insertar($datos);
        } else {
            // editar
            $where = [];
            $where[0]['campo'] = 'id_reserva_servicio';
            $where[0]['dato']  = (int)$parametros['_id'];
            $resultado = $this->modelo->editar($datos, $where);
        }

        return $resultado;
    }


    function eliminar($id) {}

    //Para usar en select2
    function buscar($parametros) {}
}
