<?php

date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 2) . '/modelo/TALENTO_HUMANO/th_control_acceso_temporalM.php');
require_once(dirname(__DIR__, 2) . '/modelo/TALENTO_HUMANO/th_control_aprobacionM.php');


$controlador = new th_control_acceso_temporalC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar($_POST['id'] ?? ''));
}

if (isset($_GET['insertar'])) {
    echo json_encode($controlador->insertar_editar($_POST['parametros']));
}

if (isset($_GET['aprobar_marcacion'])) {
    echo json_encode($controlador->aprobar_marcacion($_POST['parametros']));
}



class th_control_acceso_temporalC
{
    private $modelo;
    private $th_control_aprobacionM;

    function __construct()
    {
        $this->modelo = new th_control_acceso_temporalM();
        $this->th_control_aprobacionM = new th_control_aprobacionM();
    }

    function listar($id = '')
    {
        if ($id == '') {
            $id_persona = $_SESSION['INICIO']['NO_CONCURENTE'] ?? '';
            $datos = $this->modelo->listar_accesos_temporales($id_persona);
        } else {
            $datos = $this->modelo->where('th_act_id', $id)->listar();

            $id_empresa = $_SESSION['INICIO']['ID_EMPRESA'];
            if (!empty($datos)) {
                foreach ($datos as &$item) {
                    $fileName = $item['url_foto'] ?? '';
                    $per_id = $item['per_id'] ?? '';
                    $item['url_foto_completa'] = $fileName
                        ? "../REPOSITORIO/TALENTO_HUMANO/{$id_empresa}/MARCACIONES/{$per_id}/{$fileName}"
                        : '';
                }
            }
        }
        return $datos;
    }

    function insertar_editar($parametros)
    {
        $id = (isset($_SESSION['INICIO']['NO_CONCURENTE']) && $_SESSION['INICIO']['NO_CONCURENTE']) ? $_SESSION['INICIO']['NO_CONCURENTE'] : -10;

        if ($id == -10) {
            return $id;
            exit;
        }

        $fileName = null;

        if (!empty($parametros['captured_image'])) {
            $base64 = preg_replace('#^data:image/\w+;base64,#i', '', $parametros['captured_image']);
            $imageData = base64_decode($base64);

            // Obtener imagen anterior
            $datos = $this->modelo->where('th_act_id', $parametros['_id'])->listar();
            $fileNameAntigua = $datos[0]['url_foto'] ?? null;

            // Ruta donde guardar la nueva imagen
            $id_empresa = $_SESSION['INICIO']['ID_EMPRESA'];

            $rutaBase = dirname(__DIR__, 2) . '/REPOSITORIO/TALENTO_HUMANO/' . $id_empresa . '/MARCACIONES/' . $id;
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

            if (empty($datos) || empty($datos[0]['url_foto'])) {
                return -1;
            }

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
            // array('campo' => 'th_act_aprobado_por', 'dato' => null),
            // array('campo' => 'th_act_fecha_aprobacion', 'dato' => null),
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

    function aprobar_marcacion($parametros)
    {
        $id_usuario = $_SESSION['INICIO']['ID_USUARIO'] ?? '';

        $usuario_aprobacion = $this->th_control_aprobacionM->where('usu_id', $id_usuario)->listar();

        $estado_marcacion = '';
        if ($parametros['estado_marcacion'] == 1) {
            $estado_marcacion = 'APROBADO';

            $marcacion_aprobar = $this->modelo->listar_accesos_temporales('', $parametros['id_marcacion']);

            $datos = array(
                // array('campo' => 'th_per_id', 'dato' => $parametros['cardNo'] ?? ''),
                // array('campo' => 'th_act_cardNo', 'dato' => $parametros['cardNo'] ?? ''),
                // array('campo' => 'th_act_tipo_registro', 'dato' => $parametros['tipo_registro'] ?? ''),
                // array('campo' => 'th_act_hora', 'dato' => date('H:i:s')),
                // array('campo' => 'th_act_fecha_hora', 'dato' => date('Y-m-d H:i:s')),
                // array('campo' => 'th_act_fecha_modificacion', 'dato' => date('Y-m-d H:i:s')),
                // array('campo' => 'th_act_puerto', 'dato' => $_SERVER['REMOTE_PORT'] ?? ''),
            );

            print_r($marcacion_aprobar);
            exit();
            die();
        } else if ($parametros['estado_marcacion'] == 2) {
            $estado_marcacion = 'RECHAZADO';
        }

        if (count($usuario_aprobacion) == 1) {
            $datos = array(
                array('campo' => 'th_act_aprobado_por', 'dato' => $id_usuario),
                array('campo' => 'th_act_fecha_aprobacion', 'dato' => date('Y-m-d H:i:s')),
                array('campo' => 'th_act_estado_aprobacion', 'dato' => $estado_marcacion),
            );

            $where = array(
                array('campo' => 'th_act_id', 'dato' => $parametros['id_marcacion']),
            );

            $datos = $this->modelo->editar($datos, $where);
            return $datos;
        }
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
