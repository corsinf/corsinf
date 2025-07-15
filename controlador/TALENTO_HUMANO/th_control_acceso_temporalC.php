<?php

date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 2) . '/modelo/TALENTO_HUMANO/th_control_acceso_temporalM.php');

$controlador = new th_control_acceso_temporalC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar($_POST['id'] ?? ''));
}

if (isset($_GET['insertar'])) {
    echo json_encode($controlador->insertar_editar($_POST['parametros']));
}


class th_control_acceso_temporalC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new th_control_acceso_temporalM();
    }

    function listar($id = '')
    {
        if ($id == '') {
            $datos = $this->modelo->listar();
        } else {
            $datos = $this->modelo->where('th_act_id', $id)->listar();
        }
        return $datos;
    }

    function insertar_editar($parametros)
    {
        $id = $_SESSION['INICIO']['NO_CONCURENTE'] > 1 ? $_SESSION['INICIO']['NO_CONCURENTE'] : $_SESSION['INICIO']['ID_USUARIO'];
        $fileName = null;



        if (!empty($parametros['captured_image'])) {
            $base64 = preg_replace('#^data:image/\w+;base64,#i', '', $parametros['captured_image']);
            $imageData = base64_decode($base64);

            // Obtener imagen anterior
            $datos = $this->modelo->where('th_act_id', $parametros['_id'])->listar();
            $fileNameAntigua = $datos[0]['url_foto'] ?? null;

            // Ruta donde guardar la nueva imagen
            $rutaBase = dirname(__DIR__, 3) . '/REPOSITORIO/TALENTO_HUMANO/' . $id;
            if (!file_exists($rutaBase)) {
                mkdir($rutaBase, 0777, true);
            }

            // Crear nuevo nombre y ruta
            $fileName = 'foto_' . date('Ymd_His') . '.jpg';
            $rutaCompleta = $rutaBase . '/' . $fileName;

            // Guardar nueva imagen
            file_put_contents($rutaCompleta, $imageData);

            // Borrar la imagen anterior si existe
            if (!empty($fileNameAntigua)) {
                // Convertir ruta relativa a absoluta
                $rutaAntigua = dirname(__DIR__, 3) . '/REPOSITORIO/TALENTO_HUMANO/' . $id . '/' . ltrim($fileNameAntigua); // elimina "/" inicial si hay

                if (file_exists($rutaAntigua)) {
                    unlink($rutaAntigua);
                }
            }
        } else {
            $datos = $this->modelo->where('th_act_id', $parametros['_id'])->listar();
            $fileName = $datos[0]['url_foto'];
        }

        // 2. Obtener IP y Host del cliente
        $ip_cliente_host = $_SERVER['REMOTE_ADDR'] ?? '';
        if ($ip_cliente_host != '') {
            $ip_cliente_host = gethostbyaddr($ip_cliente_host);
        }

        // 3. Construcción de los datos a insertar
        $datos = array(
            array('campo' => 'th_per_id', 'dato' => $id),
            array('campo' => 'th_act_cardNo', 'dato' => $parametros['cardNo'] ?? ''),
            array('campo' => 'th_act_tipo_registro', 'dato' => $parametros['tipo_registro'] ?? ''),
            array('campo' => 'th_act_hora', 'dato' => date('H:i:s')),
            array('campo' => 'th_act_fecha_hora', 'dato' => date('Y-m-d H:i:s')),
            array('campo' => 'th_act_fecha_creacion', 'dato' => date('Y-m-d H:i:s')),
            array('campo' => 'th_act_fecha_modificacion', 'dato' => date('Y-m-d H:i:s')),
            array('campo' => 'th_act_puerto', 'dato' => $_SERVER['REMOTE_PORT'] ?? ''),
            array('campo' => 'th_act_tipo_origen', 'dato' => 'WEB'),
            array('campo' => 'th_act_server_name', 'dato' => $_SERVER['SERVER_NAME'] ?? ''),
            array('campo' => 'th_act_server_software', 'dato' => $_SERVER['SERVER_SOFTWARE'] ?? ''),
            array('campo' => 'th_act_server_protocol', 'dato' => $_SERVER['SERVER_PROTOCOL'] ?? ''),
            array('campo' => 'th_act_server_port', 'dato' => $_SERVER['SERVER_PORT'] ?? ''),
            array('campo' => 'th_act_http_host', 'dato' => $_SERVER['HTTP_HOST'] ?? ''),
            array('campo' => 'th_act_remote_addr', 'dato' => $_SERVER['REMOTE_ADDR'] ?? ''),
            array('campo' => 'th_act_http_user_agent', 'dato' => $_SERVER['HTTP_USER_AGENT'] ?? ''),
            array('campo' => 'th_act_request_method', 'dato' => $_SERVER['REQUEST_METHOD'] ?? ''),
            array('campo' => 'th_act_request_uri', 'dato' => $_SERVER['REQUEST_URI'] ?? ''),
            array('campo' => 'th_act_host_cliente', 'dato' => $ip_cliente_host),
            array('campo' => 'th_act_http_x_forwarded_for', 'dato' => $_SERVER['HTTP_X_FORWARDED_FOR'] ?? ''),
            array('campo' => 'th_act_latitud', 'dato' => $parametros['txt_latitud'] ?? null),
            array('campo' => 'th_act_longitud', 'dato' => $parametros['txt_longitud'] ?? null),
            array('campo' => 'th_act_url_foto', 'dato' => $fileName ?? null),
            array('campo' => 'th_act_aprobado_por', 'dato' => null),
            array('campo' => 'th_act_fecha_aprobacion', 'dato' => null),
            array('campo' => 'th_act_observacion_aprobacion', 'dato' => $parametros['txt_descripcion'] ?? null),
        );

        // 4. Insertar (o actualizar si ya existe)
        if (empty($parametros['_id'])) {
            $resultado = $this->modelo->insertar($datos);
        } else {
            $where = array(
                array('campo' => 'th_act_id', 'dato' => $parametros['_id']),
            );
            // Ejecutar la actualización en la base de datos
            $base = $this->modelo->editar($datos, $where);

            return $base == 1 ? 1 : -1;
        }

        return $resultado;
    }

    function validar_formato($file)
    {
        switch ($file['txt_copia_cambiar_foto']['type']) {
            case 'image/jpeg':
            case 'image/pjpeg':
            case 'image/gif':
            case 'image/png':
            case 'image/jpg':
                return 1;
                break;
            default:
                return -1;
                break;
        }
    }
}
