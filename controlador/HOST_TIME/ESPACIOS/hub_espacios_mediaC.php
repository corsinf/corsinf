<?php
date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 3) . '/modelo/HOST_TIME/ESPACIOS/hub_espacios_mediaM.php');

$controlador = new hub_espacios_mediaC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar($_POST['id_espacio'] ?? ''));
}

if (isset($_GET['insertar'])) {
    echo json_encode($controlador->insertar($_FILES, $_POST));
}

if (isset($_GET['eliminar'])) {
    echo json_encode($controlador->eliminar($_POST['id']));
}

if (isset($_GET['set_principal'])) {
    echo json_encode($controlador->set_principal($_POST['id'], $_POST['id_espacio']));
}

/* ====================================================================== */

class hub_espacios_mediaC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new hub_espacios_mediaM();
    }


    public function listar($id_espacio = '')
    {
        return $this->modelo->listar_media($id_espacio);
    }


    public function insertar($files, $post)
    {
        $id_espacio = $post['id_espacio'] ?? '';
        if ($id_espacio === '')              return -1;
        if (empty($files['archivo']['tmp_name'])) return -1;

        $archivo = $files['archivo'];
        $mime    = mime_content_type($archivo['tmp_name']);
        $tamanio = $archivo['size'];

        $imagenes_permitidas = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
        $videos_permitidos   = ['video/mp4', 'video/webm', 'video/ogg', 'video/quicktime'];

        if (in_array($mime, $imagenes_permitidas)) {
            $tipo = 'imagen';
        } elseif (in_array($mime, $videos_permitidos)) {
            $tipo = 'video';
        } else {
            return -2;
        }

        if ($tipo === 'video'  && $tamanio > 50 * 1024 * 1024) return -3;
        if ($tipo === 'imagen' && $tamanio > 5  * 1024 * 1024) return -4;

        $id_empresa = $_SESSION['INICIO']['ID_EMPRESA'];
        $ruta_dir   = dirname(__DIR__, 3) . '/REPOSITORIO/HOST_TIME/ESPACIOS/'
            . $id_empresa . '/' . $id_espacio . '/MEDIA/';

        if (!file_exists($ruta_dir)) mkdir($ruta_dir, 0777, true);
        if (!is_uploaded_file($archivo['tmp_name'])) return -1;

        $lista = $this->modelo->listar_media($id_espacio);
        $orden = count($lista) + 1;

        // Insertar registro con datos temporales para obtener el ID
        $datos = [
            ['campo' => 'id_espacio',      'dato' => (int) $id_espacio],
            ['campo' => 'tipo',            'dato' => $tipo],
            ['campo' => 'url_archivo',     'dato' => ''],
            ['campo' => 'nombre_archivo',  'dato' => ''],
            ['campo' => 'formato',         'dato' => ''],
            ['campo' => 'tamanio_bytes',   'dato' => $tamanio],
            ['campo' => 'orden',           'dato' => $orden],
            ['campo' => 'es_principal',    'dato' => 0],
            ['campo' => 'is_deleted',      'dato' => 0],
            ['campo' => 'id_usuario_crea', 'dato' => $_SESSION['INICIO']['ID_USUARIO'] ?? null],
            ['campo' => 'fecha_creacion',  'dato' => date('Y-m-d H:i:s')],
        ];

        $id_nuevo = $this->modelo->insertar_id($datos);
        if (!$id_nuevo || $id_nuevo < 1) return -1;

        // Guardar el archivo físico con el ID real
        if ($tipo === 'imagen') {
            $nombre_bd = $this->guardar_imagen($archivo, $ruta_dir, $id_nuevo);
        } else {
            $nombre_bd = $this->guardar_video($archivo, $ruta_dir, $id_nuevo, $mime);
        }

        if ($nombre_bd < 0) {
            // Revertir registro si falla el guardado físico
            $this->modelo->editar(
                [['campo' => 'is_deleted', 'dato' => 1]],
                [['campo' => 'id_espacio_media', 'dato' => $id_nuevo]]
            );
            return $nombre_bd;
        }

        $extension = pathinfo($nombre_bd, PATHINFO_EXTENSION);
        $url_bd    = '../REPOSITORIO/HOST_TIME/ESPACIOS/'
            . $id_empresa . '/' . $id_espacio . '/MEDIA/' . $nombre_bd;

        // Actualizar el registro con nombre y URL reales
        $this->modelo->editar([
            ['campo' => 'url_archivo',    'dato' => $url_bd],
            ['campo' => 'nombre_archivo', 'dato' => $nombre_bd],
            ['campo' => 'formato',        'dato' => $extension],
        ], [['campo' => 'id_espacio_media', 'dato' => $id_nuevo]]);

        return 1;
    }


    private function guardar_imagen($archivo, $ruta_dir, $id_media)
    {
        $info = getimagesize($archivo['tmp_name']);
        if (!$info) return -2;

        $mime   = $info['mime'];
        $nombre = 'espacio_' . $id_media . '.webp';

        switch ($mime) {
            case 'image/jpeg':
            case 'image/jpg':
                $img = imagecreatefromjpeg($archivo['tmp_name']);
                break;
            case 'image/png':
                $img = imagecreatefrompng($archivo['tmp_name']);
                imagepalettetotruecolor($img);
                imagealphablending($img, true);
                imagesavealpha($img, true);
                break;
            case 'image/gif':
                $img = imagecreatefromgif($archivo['tmp_name']);
                break;
            case 'image/webp':
                $img = imagecreatefromwebp($archivo['tmp_name']);
                break;
            default:
                return -2;
        }

        if (!imagewebp($img, $ruta_dir . $nombre, 82)) {
            imagedestroy($img);
            return -1;
        }

        imagedestroy($img);
        return $nombre;
    }


    private function guardar_video($archivo, $ruta_dir, $id_espacio, $mime)
    {
        $extensiones = [
            'video/mp4'       => 'mp4',
            'video/webm'      => 'webm',
            'video/ogg'       => 'ogv',
            'video/quicktime' => 'mov',
        ];
        $ext    = $extensiones[$mime] ?? 'mp4';
        $nombre = 'vid_' . $id_espacio . '_' . time() . '.' . $ext;

        if (!move_uploaded_file($archivo['tmp_name'], $ruta_dir . $nombre)) {
            return -1;
        }
        return $nombre;
    }


    public function eliminar($id)
    {
        $media = $this->modelo->where('id_espacio_media', $id)->listar();
        if (empty($media)) return -1;

        $registro    = $media[0];
        $ruta_fisica = dirname(__DIR__, 3) . '/' . str_replace('../', '', $registro['url_archivo']);

        if (file_exists($ruta_fisica)) {
            unlink($ruta_fisica);
        }

        return $this->modelo->editar(
            [['campo' => 'is_deleted', 'dato' => 1]],
            [['campo' => 'id_espacio_media', 'dato' => (int) $id]]
        );
    }
   
    public function set_principal($id, $id_espacio)
    {
        // Verificar que el registro exista
        $media = $this->modelo->where('id_espacio_media', $id)->listar();
        if (empty($media)) return -1;

        $registro = $media[0];

        if ($registro['es_principal'] == 1) {
            // Ya es principal → quitar
            $resultado = $this->modelo->editar(
                [['campo' => 'es_principal', 'dato' => 0]],
                [['campo' => 'id_espacio_media', 'dato' => (int) $id]]
            );
            return $resultado ? 2 : -1;
        }

        // No es principal → primero resetear todas las del espacio
        $this->modelo->editar(
            [['campo' => 'es_principal', 'dato' => 0]],
            [['campo' => 'id_espacio',   'dato' => (int) $id_espacio]]
        );

        // Luego asignar la seleccionada
        $resultado = $this->modelo->editar(
            [['campo' => 'es_principal', 'dato' => 1]],
            [['campo' => 'id_espacio_media', 'dato' => (int) $id]]
        );
        return $resultado ? 1 : -1;
    }
}
