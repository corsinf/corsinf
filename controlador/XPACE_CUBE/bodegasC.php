<?php
date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 2) . '/modelo/XPACE_CUBE/bodegasM.php');

$controlador = new bodegasC();

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


class  bodegasC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new bodegasM();
    }

    function listar($id = '')
    {
        if ($id == "") {
            $datos = $this->modelo->where('estado', 1)->listar();
        } else {
            $datos = $this->modelo->where('estado', 1)->where('id_articulo', $id)->listar();
        }

        return $datos;
    }

    public function insertar_editar($parametros)
    {
        // Normalizar / limpiar inputs
        $codigo    = isset($parametros['txt_codigo']) ? trim($parametros['txt_codigo']) : '';
        $nombre    = isset($parametros['txt_nombre']) ? trim($parametros['txt_nombre']) : '';
        $descripcion = isset($parametros['txt_descripcion']) ? trim($parametros['txt_descripcion']) : '';
        $categoria = isset($parametros['ddl_categoria']) ? trim($parametros['ddl_categoria']) : '';
        $cantidad_total = (isset($parametros['txt_cantidad_total']) && $parametros['txt_cantidad_total'] !== '')
            ? (int)$parametros['txt_cantidad_total'] : 0;
        $cantidad_disponible = (isset($parametros['txt_cantidad_disponible']) && $parametros['txt_cantidad_disponible'] !== '')
            ? (int)$parametros['txt_cantidad_disponible'] : 0;
        $precio_unitario = (isset($parametros['txt_precio_unitario']) && $parametros['txt_precio_unitario'] !== '')
            ? (float)$parametros['txt_precio_unitario'] : 0.00;

        // Fecha: aceptar datetime-local (YYYY-MM-DDTHH:MM) o texto; normalizar a 'Y-m-d H:i:s'
        $fecha_ingreso = null;
        if (!empty($parametros['txt_fecha_ingreso'])) {
            // Reemplazar posible 'T' (datetime-local) por espacio
            $fraw = str_replace('T', ' ', trim($parametros['txt_fecha_ingreso']));
            $ts = strtotime($fraw);
            $fecha_ingreso = $ts ? date('Y-m-d H:i:s', $ts) : null;
        }

        // Estado (si lo envías desde el formulario)
        $estado = isset($parametros['estado']) && $parametros['estado'] !== '' ? (int)$parametros['estado'] : 1;

        // Validaciones básicas (puedes ampliarlas)
        if ($nombre === '') {
            return ['success' => false, 'message' => 'El nombre del artículo es obligatorio.'];
        }
        if ($cantidad_disponible > $cantidad_total) {
            // opcional: forzar disponible <= total o devolver error
            $cantidad_disponible = $cantidad_total;
        }

        // Preparar array de datos (siguiendo tu BaseModel)
        $datos = [
            ['campo' => 'codigo', 'dato' => $codigo],
            ['campo' => 'nombre', 'dato' => $nombre],
            ['campo' => 'descripcion', 'dato' => $descripcion],
            ['campo' => 'categoria', 'dato' => $categoria],
            ['campo' => 'cantidad_total', 'dato' => $cantidad_total],
            ['campo' => 'cantidad_disponible', 'dato' => $cantidad_disponible],
            ['campo' => 'precio_unitario', 'dato' => $precio_unitario],
            ['campo' => 'fecha_ingreso', 'dato' => $fecha_ingreso],
            ['campo' => 'estado', 'dato' => $estado],
        ];

        // Insertar o editar según _id (tu formulario debería enviar _id cuando editas)
        if (empty($parametros['_id'])) {
            // Insertar nuevo artículo
            $resultado = $this->modelo->insertar($datos);
            // Puedes devolver más info si tu modelo la retorna (ej: id insertado)
            return $resultado;
        } else {
            // Editar existente (la PK en tu modelo es id_articulo)
            $where = [];
            $where[0]['campo'] = 'id_articulo';
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
            $lista[] = array('id' => ($value['id_articulo']), 'text' => ($text), /* 'data' => $value */);
        }

        return $lista;
    }
}
