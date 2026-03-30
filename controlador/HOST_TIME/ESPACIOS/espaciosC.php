<?php
date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 3) . '/modelo/HOST_TIME/ESPACIOS/espaciosM.php');


$controlador = new espaciosC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar($_POST['id'] ?? ''));
}
if (isset($_GET['listar_pisos_por_ubicacion'])) {
    echo json_encode($controlador->listar_pisos_por_ubicacion($_POST['id'] ?? ''));
}

if (isset($_GET['listar_tipos_por_ubicacion_piso'])) {
    echo json_encode($controlador->listar_tipos_por_ubicacion_piso($_POST['id_ubicacion'] ?? '', $_POST['id_piso'] ?? ''));
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


if (isset($_GET['insertar_imagen'])) {
    echo json_encode($controlador->insertar_imagen($_FILES, $_POST));
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
    function listar_pisos_por_ubicacion($id = '')
    {

        $datos = $this->modelo->listar_pisos_por_ubicacion($id);

        return $datos;
    }
    function listar_tipos_por_ubicacion_piso($id_ubicacion = '', $id_piso = '')
    {

        $datos = $this->modelo->listar_tipos_por_ubicacion_piso($id_ubicacion, $id_piso);

        return $datos;
    }

    public function insertar_editar($parametros)
    {
        $id_usuario_sesion = $_SESSION['INICIO']['ID_USUARIO'] ?? null;
        $fecha_actual = date('Y-m-d H:i:s');

        // Mapeo de campos desde el formulario (parametros)
        $datos = array(
            array('campo' => 'id_ubicacion', 'dato' => (int)$parametros['ddl_ubicacion']),
            array('campo' => 'id_tipo_espacio', 'dato' => (int)$parametros['ddl_tipo_espacio']),
            array('campo' => 'id_numero_piso', 'dato' => (int)$parametros['ddl_numero_piso']),
            array('campo' => 'codigo', 'dato' => trim($parametros['txt_codigo'])),
            array('campo' => 'nombre', 'dato' => trim($parametros['txt_nombre'])),
            array('campo' => 'capacidad_minima', 'dato' => (int)$parametros['txt_capacidad_min']),
            array('campo' => 'capacidad_maxima', 'dato' => (int)$parametros['txt_capacidad_max']),
            array('campo' => 'es_exclusivo', 'dato' => isset($parametros['chk_exclusivo']) ? 1 : 0),
            array('campo' => 'id_estado_espacio', 'dato' => (int)($parametros['ddl_estado'] ?? 1)),
            array('campo' => 'is_deleted', 'dato' => 0),
        );

        if (empty($parametros['_id'])) {
            // Campos para nuevo registro
            $datos[] = array('campo' => 'id_usuario_crea', 'dato' => $id_usuario_sesion);
            $datos[] = array('campo' => 'fecha_creacion', 'dato' => $fecha_actual);
            $resultado = $this->modelo->insertar($datos);
        } else {
            // Campos para actualización
            $datos[] = array('campo' => 'id_usuario_modifica', 'dato' => $id_usuario_sesion);
            $datos[] = array('campo' => 'fecha_modificacion', 'dato' => $fecha_actual);

            $where = array();
            $where[0]['campo'] = 'id_espacio';
            $where[0]['dato'] = (int)$parametros['_id'];

            $resultado = $this->modelo->editar($datos, $where);
        }

        return $resultado;
    }

    // Soft delete (recomendado dado que tienes is_deleted en la tabla)
    public function eliminar($id)
    {
        $datos = array(
            array('campo' => 'is_deleted', 'dato' => 1)
        );
        $where = array(
            array('campo' => 'id_espacio', 'dato' => (int)$id)
        );
        return $this->modelo->editar($datos, $where);
    }

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

    function insertar_imagen($file, $parametros)
    {
        $id_espacio = $parametros['txt_espacio_id_foto'] ?? '';

        if ($id_espacio == '') return -1;
        if (empty($file['txt_copia_imagen_espacio']['tmp_name'])) return -1;

        return $this->guardar_archivo($file, $id_espacio);
    }

    private function guardar_archivo($file, $id_espacio)
    {
        $id_empresa = $_SESSION['INICIO']['ID_EMPRESA'];
        $ruta = dirname(__DIR__, 3) . '/REPOSITORIO/HOST_TIME/ESPACIOS/' . $id_empresa . '/' . $id_espacio . '/';

        if (!file_exists($ruta)) {
            mkdir($ruta, 0777, true);
        }

        if ($this->validar_formato($file) !== 1) return -2;

        $tmp = $file['txt_copia_imagen_espacio']['tmp_name'];
        $mime = getimagesize($tmp)['mime'];
        $nombre = 'imagen_espacio_' . $id_espacio . '.webp';
        $ruta_fisica = $ruta . $nombre;
        $ruta_bd = '../REPOSITORIO/HOST_TIME/ESPACIOS/' . $id_empresa . '/' . $id_espacio . '/' . $nombre;

        if (!is_uploaded_file($tmp)) return -1;

        switch ($mime) {
            case 'image/jpeg':
            case 'image/jpg':
                $img = imagecreatefromjpeg($tmp);
                break;
            case 'image/png':
                $img = imagecreatefrompng($tmp);
                imagepalettetotruecolor($img);
                imagealphablending($img, true);
                imagesavealpha($img, true);
                break;
            case 'image/gif':
                $img = imagecreatefromgif($tmp);
                break;
            default:
                return -1;
        }

        if (!imagewebp($img, $ruta_fisica, 80)) {
            imagedestroy($img);
            return -1;
        }

        imagedestroy($img);

        $datos = [['campo' => 'imagen', 'dato' => $ruta_bd]];
        $where = [['campo' => 'id_espacio', 'dato' => $id_espacio]];
        $r = $this->modelo->editar($datos, $where);
        return $r == 1 ? 1 : -1;
    }

    function validar_formato($file)
    {
        $tipos = ['image/jpeg', 'image/pjpeg', 'image/gif', 'image/png', 'image/jpg'];
        return in_array($file['txt_copia_imagen_espacio']['type'], $tipos) ? 1 : -1;
    }
}
