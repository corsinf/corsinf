<?php
date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 2) . '/modelo/XPACE_CUBE/reservasM.php');

$controlador = new reservasC();

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


class  reservasC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new reservasM();
    }

    function listar($id = '')
    {
        if ($id == "") {
            $datos = $this->modelo->where('estado', 1)->listar_reservas();
        } else {
            $datos = $this->modelo->where('estado', 1)->listar_reservas($id);
        }

        return $datos;
    }

    public function insertar_editar($parametros)
    {
        // Normalizar y limpiar inputs
        $nota = isset($parametros['txt_nota']) ? trim($parametros['txt_nota']) : '';
        $numero_personas = isset($parametros['txt_numero_personas']) && $parametros['txt_numero_personas'] !== ''
            ? (int)$parametros['txt_numero_personas'] : 0;

        $id_espacio = isset($parametros['ddl_espacio']) && $parametros['ddl_espacio'] !== ''
            ? (int)$parametros['ddl_espacio'] : null;

        $id_usuario = isset($parametros['ddl_miembro']) && $parametros['ddl_miembro'] !== ''
            ? (int)$parametros['ddl_miembro'] : null;

        // Fecha inicio/fin: aceptar datetime-local (YYYY-MM-DDTHH:MM) o texto y normalizar a Y-m-d H:i:s
        $inicio = null;
        if (!empty($parametros['txt_inicio'])) {
            $fraw = str_replace('T', ' ', trim($parametros['txt_inicio']));
            $ts = strtotime($fraw);
            $inicio = $ts ? date('Y-m-d H:i:s', $ts) : null;
        }

        $fin = null;
        if (!empty($parametros['txt_fin'])) {
            $fraw2 = str_replace('T', ' ', trim($parametros['txt_fin']));
            $ts2 = strtotime($fraw2);
            $fin = $ts2 ? date('Y-m-d H:i:s', $ts2) : null;
        }

        $id_estado_reserva = isset($parametros['ddl_estado_reserva']) && $parametros['ddl_estado_reserva'] !== ''
            ? (int)$parametros['ddl_estado_reserva'] : 1; // por defecto 1 (reservada, por ejemplo)

        $estado = isset($parametros['estado']) && $parametros['estado'] !== '' ? (int)$parametros['estado'] : 1;

        // Nombre opcional (puede venir del formulario o generarse)
        $nombre = isset($parametros['txt_nombre']) ? trim($parametros['txt_nombre']) : '';

        // Validaciones básicas
        if ($id_espacio === null) {
            return ['success' => false, 'message' => 'Debe seleccionar el espacio.'];
        }
        if ($id_usuario === null) {
            return ['success' => false, 'message' => 'Debe seleccionar el miembro.'];
        }
        if (!$inicio || !$fin) {
            return ['success' => false, 'message' => 'Debe ingresar fecha y hora de inicio y fin válidas.'];
        }
        // comprobar orden de fechas
        if (strtotime($inicio) > strtotime($fin)) {
            return ['success' => false, 'message' => 'La fecha de inicio no puede ser mayor que la fecha de fin.'];
        }
        if ($numero_personas < 0) {
            $numero_personas = 0;
        }

        // Timestamps
        $ahora = date('Y-m-d H:i:s');

        // Preparar array de datos (siguiendo tu BaseModel)
        $datos = [
            ['campo' => 'id_usuario', 'dato' => $id_usuario],
            ['campo' => 'id_espacio', 'dato' => $id_espacio],
            ['campo' => 'inicio', 'dato' => $inicio],
            ['campo' => 'fin', 'dato' => $fin],
            ['campo' => 'numero_personas', 'dato' => $numero_personas],
            ['campo' => 'id_estado_reserva', 'dato' => $id_estado_reserva],
            ['campo' => 'notas', 'dato' => $nota],
            ['campo' => 'estado', 'dato' => $estado],
            ['campo' => 'nombre', 'dato' => $nombre],
        ];

        // Insertar o editar
        if (empty($parametros['_id'])) {
            // nuevo registro: agregar creado_en
            $datos[] = ['campo' => 'creado_en', 'dato' => $ahora];
            $resultado = $this->modelo->insertar($datos);
            return $resultado;
        } else {
            // editar: actualizar actualizado_en
            $datos[] = ['campo' => 'actualizado_en', 'dato' => $ahora];

            $where = [];
            $where[0]['campo'] = 'id_reserva';
            $where[0]['dato']  = (int)$parametros['_id'];

            $resultado = $this->modelo->editar($datos, $where);
            return $resultado;
        }
    }


    function eliminar($id) {}

    //Para usar en select2
    function buscar($parametros)
    {
        $lista = array();
        $concat = "nombre, estado";
        $datos = $this->modelo->where('estado', 1)->like($concat, $parametros['query']);

        foreach ($datos as $key => $value) {
            $text = $value['nombre'];
            $lista[] = array('id' => ($value['id_reserva']), 'text' => ($text), /* 'data' => $value */);
        }

        return $lista;
    }
}
