<?php
date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 2) . '/modelo/XPACE_CUBE/espaciosM.php');

$controlador = new espaciosC();

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


class espaciosC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new espaciosM();
    }

    function listar($id = '')
    {

        $datos = $this->modelo->listar_espacios($id);

        return $datos;
    }

    public function insertar_editar($parametros)
    {
        // Normalizar y validar campos básicos
        $id_ubicacion = isset($parametros['ddl_ubicacion']) && $parametros['ddl_ubicacion'] !== ''
            ? (int)$parametros['ddl_ubicacion']
            : null;

        $id_tipo_espacio = isset($parametros['ddl_tipo_espacio']) && $parametros['ddl_tipo_espacio'] !== ''
            ? (int)$parametros['ddl_tipo_espacio']
            : null;

        $codigo = isset($parametros['txt_codigo']) ? trim($parametros['txt_codigo']) : '';
        $nombre = isset($parametros['txt_nombre']) ? trim($parametros['txt_nombre']) : '';
        $capacidad = isset($parametros['txt_capacidad']) ? (int)$parametros['txt_capacidad'] : 0;

        // Tarifas (hora y día)
        $tarifa_hora = isset($parametros['txt_tarifa_hora']) ? (float)$parametros['txt_tarifa_hora'] : 0.00;
        $tarifa_dia = isset($parametros['txt_tarifa_dia']) ? (float)$parametros['txt_tarifa_dia'] : 0.00;

        // Estado general del registro
        $estado = isset($parametros['estado']) ? (int)$parametros['estado'] : 1; // por defecto activo

        // Marcar fecha de creación solo al insertar
        $creado_en = date('Y-m-d H:i:s');

        // Estructura para el modelo (siguiendo BaseModel)
        $datos = array(
            array('campo' => 'id_ubicacion', 'dato' => $id_ubicacion),
            array('campo' => 'id_tipo_espacio', 'dato' => $id_tipo_espacio),
            array('campo' => 'codigo', 'dato' => $codigo),
            array('campo' => 'nombre', 'dato' => $nombre),
            array('campo' => 'capacidad', 'dato' => $capacidad),
            array('campo' => 'tarifa_hora', 'dato' => $tarifa_hora),
            array('campo' => 'tarifa_dia', 'dato' => $tarifa_dia),
            array('campo' => 'estado', 'dato' => $estado),
        );

        // Si es nuevo registro → insertar
        if (empty($parametros['_id'])) {
            $datos[] = array('campo' => 'creado_en', 'dato' => $creado_en);
            $resultado = $this->modelo->insertar($datos);
        }
        // Si ya existe → actualizar
        else {
            $where = array();
            $where[0]['campo'] = 'id_espacio';
            $where[0]['dato'] = (int)$parametros['_id'];

            $resultado = $this->modelo->editar($datos, $where);
        }

        return $resultado;
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
            $lista[] = array('id' => ($value['id_espacio']), 'text' => ($text), /* 'data' => $value */);
        }

        return $lista;
    }
}
