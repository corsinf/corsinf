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
        if ($id_espacio === '')             return -1;
        if (empty($files['archivo']['tmp_name'])) return -1;

        $archivo    = $files['archivo'];
        $mime       = mime_content_type($archivo['tmp_name']);
        $tamanio    = $archivo['size'];

        /* ---- Clasificar ---- */
        $imagenes_permitidas = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
        $videos_permitidos   = ['video/mp4', 'video/webm', 'video/ogg', 'video/quicktime'];

        if (in_array($mime, $imagenes_permitidas)) {
            $tipo = 'imagen';
        } elseif (in_array($mime, $videos_permitidos)) {
            $tipo = 'video';
        } else {
            return -2; // formato no permitido
        }

        /* ---- Validar tamaño video (50 MB) ---- */
        if ($tipo === 'video' && $tamanio > 50 * 1024 * 1024) {
            return -3; // video supera 50 MB
        }

        /* ---- Validar tamaño imagen (5 MB) ---- */
        if ($tipo === 'imagen' && $tamanio > 5 * 1024 * 1024) {
            return -4; // imagen supera 5 MB
        }

        /* ---- Rutas ---- */
        $id_empresa = $_SESSION['INICIO']['ID_EMPRESA'];
        $ruta_dir   = dirname(__DIR__, 3) . '/REPOSITORIO/HOST_TIME/ESPACIOS/'
                      . $id_empresa . '/' . $id_espacio . '/MEDIA/';

        if (!file_exists($ruta_dir)) {
            mkdir($ruta_dir, 0777, true);
        }

        if (!is_uploaded_file($archivo['tmp_name'])) return -1;

        /* ---- Guardar archivo ---- */
        $nombre_bd = '';

        if ($tipo === 'imagen') {
            $nombre_bd = $this->guardar_imagen($archivo, $ruta_dir, $id_espacio);
            if ($nombre_bd < 0) return $nombre_bd;
        } else {
            $nombre_bd = $this->guardar_video($archivo, $ruta_dir, $id_espacio, $mime);
            if ($nombre_bd < 0) return $nombre_bd;
        }

        $url_bd   = '../REPOSITORIO/HOST_TIME/ESPACIOS/'
                  . $id_empresa . '/' . $id_espacio . '/MEDIA/' . $nombre_bd;

        $extension = pathinfo($nombre_bd, PATHINFO_EXTENSION);

        $lista   = $this->modelo->listar_media($id_espacio);
        $orden   = count($lista) + 1;

        $datos = [
            ['campo' => 'id_espacio',    'dato' => (int) $id_espacio],
            ['campo' => 'tipo',          'dato' => $tipo],
            ['campo' => 'url_archivo',   'dato' => $url_bd],
            ['campo' => 'nombre_archivo','dato' => $nombre_bd],
            ['campo' => 'formato',       'dato' => $extension],
            ['campo' => 'tamanio_bytes', 'dato' => $tamanio],
            ['campo' => 'orden',         'dato' => $orden],
            ['campo' => 'es_principal',  'dato' => 0],
            ['campo' => 'is_deleted',    'dato' => 0],
            ['campo' => 'id_usuario_crea','dato'=> $_SESSION['INICIO']['ID_USUARIO'] ?? null],
            ['campo' => 'fecha_creacion','dato' => date('Y-m-d H:i:s')],
        ];

        $r = $this->modelo->insertar($datos);
        return $r > 0 ? 1 : -1;
    }

    
    private function guardar_imagen($archivo, $ruta_dir, $id_espacio)
    {
        $mime = getimagesize($archivo['tmp_name'])['mime'];
        $ts   = time();
        $nombre = 'img_' . $id_espacio . '_' . $ts . '.webp';

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
        $ts     = time();
        $nombre = 'vid_' . $id_espacio . '_' . $ts . '.' . $ext;

        if (!move_uploaded_file($archivo['tmp_name'], $ruta_dir . $nombre)) {
            return -1;
        }
        return $nombre;
    }

    
    public function eliminar($id)
    {
        $datos = [['campo' => 'is_deleted', 'dato' => 1]];
        $where = [['campo' => 'id_espacio_media', 'dato' => (int) $id]];
        return $this->modelo->editar($datos, $where);
    }

   
    public function set_principal($id, $id_espacio)
    {
        $datos = [['campo' => 'es_principal', 'dato' => 1]];
        $where = [['campo' => 'id_espacio_media', 'dato' => (int) $id]];
        return $this->modelo->editar($datos, $where);
    }
}