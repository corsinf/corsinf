<?php

date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 2) . '/modelo/TALENTO_HUMANO/th_control_acceso_temporalM.php');
require_once(dirname(__DIR__, 2) . '/modelo/TALENTO_HUMANO/th_control_aprobacionM.php');
require_once(dirname(__DIR__, 2) . '/modelo/TALENTO_HUMANO/th_control_accesoM.php');
require_once(dirname(__DIR__, 2) . '/modelo/TALENTO_HUMANO/th_triangular_departamento_personaM.php');


$controlador = new th_control_acceso_temporalC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar($_POST['id'] ?? '', $_GET['rbx_estado_aprobacion'] ?? ''));
}

if (isset($_GET['insertar'])) {
    echo json_encode($controlador->insertar_editar($_POST['parametros']));
}

if (isset($_GET['insertar_manual'])) {
    echo json_encode($controlador->insertar_editar_manual($_POST['parametros']));
}

if (isset($_GET['aprobar_marcacion'])) {
    echo json_encode($controlador->aprobar_marcacion($_POST['parametros']));
}



class th_control_acceso_temporalC
{
    private $modelo;
    private $th_control_aprobacionM;
    private $th_control_accesoM;
    private $th_triangular_departamento_personaM;

    function __construct()
    {
        $this->modelo = new th_control_acceso_temporalM();
        $this->th_control_aprobacionM = new th_control_aprobacionM();
        $this->th_control_accesoM = new th_control_accesoM();
        $this->th_triangular_departamento_personaM = new th_triangular_departamento_personaM();
    }

    function listar($id = '', $estado_aprobacion = '')
    {
        // print_r($estado_aprobacion); exit(); die();
        if ($id == '') {
            $id_persona = $_SESSION['INICIO']['NO_CONCURENTE'] ?? '';
            $datos = $this->modelo->listar_accesos_temporales($id_persona, '', $estado_aprobacion);
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

        //Validacion para que este en la zona
        $validacion_triangulacion = $this->validar_triangulacion($parametros['txt_latitud'] ?? null, $parametros['txt_longitud'] ?? null);

        // print_r($validacion_triangulacion); exit(); die();
        if ($validacion_triangulacion['dentro'] == 0) {
            return -12;
        }

        // print_r($validacion_triangulacion['zona']);
        // exit();
        // die();

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

            array('campo' => 'th_tri_id', 'dato' => $validacion_triangulacion['zona']['_id'] ?? null),
            array('campo' => 'th_tri_nombre', 'dato' => $validacion_triangulacion['zona']['nombre'] ?? null),
            array('campo' => 'th_tri_origen', 'dato' => $validacion_triangulacion['zona']['origen'] ?? null),
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

        if (count($usuario_aprobacion) == 0 || $id_usuario == 2) {
            return -2;
        }



        $estado_marcacion = '';
        if ($parametros['estado_marcacion'] == 1) {
            $estado_marcacion = 'APROBADO';

            if (isset($parametros['id_marcacion'])) {

                $marcacion_aprobar = $this->modelo->listar_accesos_temporales('', $parametros['id_marcacion']);

                $datos = array(
                    array('campo' => 'th_per_id', 'dato' => $marcacion_aprobar[0]['per_id'] ?? ''),
                    array('campo' => 'th_acc_tipo_registro', 'dato' => $marcacion_aprobar[0]['tipo_registro'] ?? ''),
                    array('campo' => 'th_dis_id', 'dato' => $marcacion_aprobar[0]['remote_addr'] ?? ''),
                    array('campo' => 'th_acc_hora', 'dato' => $marcacion_aprobar[0]['hora'] ?? ''),
                    array('campo' => 'th_acc_fecha_hora', 'dato' => $marcacion_aprobar[0]['fecha_hora'] ?? ''),
                    array('campo' => 'th_acc_tipo_origen', 'dato' => 'WEB'),
                    array('campo' => 'th_act_id', 'dato' => $marcacion_aprobar[0]['_id'] ?? ''),
                );

                $contar_marcaciones = count($this->th_control_accesoM->where('th_act_id', $parametros['id_marcacion'])->listar());
                if ($contar_marcaciones == 0) {
                    // $salida .= $id_marcacion_temporal . ' - ' . $contar_marcaciones . '<br>';
                    $datos = $this->th_control_accesoM->insertar($datos);
                    $editar = $this->editar_marcacion_temporal($id_usuario, $estado_marcacion, $this->modelo, $parametros['id_marcacion']);
                }

                return 1;
            }

            if (isset($parametros['marcaciones_seleccionadas'])) {

                foreach ($parametros['marcaciones_seleccionadas'] as $id_marcacion_temporal) {
                    //////////////////////////////////////////////////////////////////////////////////////////
                    //Para realizar el cambio de estado en la tabla temporal de marcaciones
                    $editar = $this->editar_marcacion_temporal($id_usuario, $estado_marcacion, $this->modelo, $id_marcacion_temporal);
                    //////////////////////////////////////////////////////////////////////////////////////////
                    // Crear el array $datos para cada persona seleccionada
                    $marcacion_aprobar = $this->modelo->listar_accesos_temporales('', $id_marcacion_temporal);

                    $datos = array(
                        array('campo' => 'th_per_id', 'dato' => $marcacion_aprobar[0]['per_id'] ?? ''),
                        array('campo' => 'th_acc_tipo_registro', 'dato' => $marcacion_aprobar[0]['tipo_registro'] ?? ''),
                        array('campo' => 'th_dis_id', 'dato' => $marcacion_aprobar[0]['remote_addr'] ?? ''),
                        array('campo' => 'th_acc_hora', 'dato' => $marcacion_aprobar[0]['hora'] ?? ''),
                        array('campo' => 'th_acc_fecha_hora', 'dato' => $marcacion_aprobar[0]['fecha_hora'] ?? ''),
                        array('campo' => 'th_acc_tipo_origen', 'dato' => 'WEB'),
                        array('campo' => 'th_act_id', 'dato' => $marcacion_aprobar[0]['_id'] ?? ''),
                    );

                    $contar_marcaciones = count($this->th_control_accesoM->where('th_act_id', $id_marcacion_temporal)->listar());
                    if ($contar_marcaciones == 0) {
                        // $salida .= $id_marcacion_temporal . ' - ' . $contar_marcaciones . '<br>';
                        $datos = $this->th_control_accesoM->insertar($datos);
                    }
                }
                return 1;
            } else {
                return -4;
            }
        } else if ($parametros['estado_marcacion'] == 2) {
            $estado_marcacion = 'RECHAZADO';

            if (isset($parametros['id_marcacion'])) {
                $editar = $this->editar_marcacion_temporal($id_usuario, $estado_marcacion, $this->modelo, $parametros['id_marcacion']);
                return $editar;
            }

            if (isset($parametros['marcaciones_seleccionadas'])) {
                foreach ($parametros['marcaciones_seleccionadas'] as $id_marcacion_temporal) {
                    //////////////////////////////////////////////////////////////////////////////////////////
                    //Para realizar el cambio de estado en la tabla temporal de marcaciones
                    $editar = $this->editar_marcacion_temporal($id_usuario, $estado_marcacion, $this->modelo, $id_marcacion_temporal);
                    //////////////////////////////////////////////////////////////////////////////////////////
                }
                return 1;
            } else {
                return -4;
            }
        }

        return 1;
    }

    //Para cambiar de estado y aprobar por usuario
    function editar_marcacion_temporal($id_usuario, $estado_marcacion, $modelo, $id_marcacion)
    {
        $datos = array(
            array('campo' => 'th_act_aprobado_por', 'dato' => $id_usuario),
            array('campo' => 'th_act_fecha_aprobacion', 'dato' => date('Y-m-d H:i:s')),
            array('campo' => 'th_act_estado_aprobacion', 'dato' => $estado_marcacion),
        );

        $where = array(
            array('campo' => 'th_act_id', 'dato' => $id_marcacion),
        );

        $datos = $modelo->editar($datos, $where);
        return $datos;
    }

    function insertar_editar_manual($parametros)
    {
        $ip_cliente_host = $_SERVER['REMOTE_ADDR'] ?? '';
        if ($ip_cliente_host != '') {
            $ip_cliente_host = gethostbyaddr($ip_cliente_host);
        }

        $fecha_hora = $parametros['txt_fecha_hora'];
        $fecha_hora_format = new DateTime($fecha_hora);
        $txt_hora = $fecha_hora_format->format('H:i:s'); 

        $txt_fecha_hora = $fecha_hora_format->format('Y-m-d H:i:s'); 

        // print_r($txt_fecha_hora); exit(); die();

        $datos = array(
            array('campo' => 'th_per_id', 'dato' => $parametros['ddl_personas'] ?? ''),

            array('campo' => 'th_act_hora', 'dato' => $txt_hora ?? ''),
            array('campo' => 'th_act_fecha_hora', 'dato' => $txt_fecha_hora ?? ''),

            array('campo' => 'th_act_fecha_modificacion', 'dato' => date('Y-m-d H:i:s')),
            array('campo' => 'th_act_puerto', 'dato' => $_SERVER['REMOTE_PORT'] ?? ''),
            array('campo' => 'th_act_tipo_origen', 'dato' => 'WEB_MANUAL'),
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

            array('campo' => 'th_act_observacion_aprobacion', 'dato' => $parametros['txt_descripcion'] ?? null),
            
            array('campo' => 'th_tri_origen', 'dato' => 'MANUAL'),
        );

        $datos = $this->modelo->insertar($datos);

        return $datos;
    }

    function validar_triangulacion($longitud = 0, $latitud = 0)
    {
        $id_persona = (isset($_SESSION['INICIO']['NO_CONCURENTE']) && $_SESSION['INICIO']['NO_CONCURENTE']) ? $_SESSION['INICIO']['NO_CONCURENTE'] : -10;

        if ($id_persona == -10) {
            return ['error' => 'Persona no válida', 'tri_id' => null, 'zona' => null, 'dentro' => false];
        }

        $zonas = $this->th_triangular_departamento_personaM->validar_triangulacion($id_persona);

        // Agrupar los puntos por th_tri_id y guardar zona base
        $poligonos = [];
        $datos_zonas = [];

        foreach ($zonas as $zona) {
            $tri_id = $zona['_id'];
            $lat = floatval($zona['latitud']);
            $lng = floatval($zona['longitud']);

            $poligonos[$tri_id][] = [$lat, $lng];

            // Guardamos solo una vez la info general de la zona
            if (!isset($datos_zonas[$tri_id])) {
                $datos_zonas[$tri_id] = $zona;
            }
        }

        // Validar si el punto está dentro de alguna de las zonas
        foreach ($poligonos as $tri_id => $puntos) {
            if ($this->punto_en_poligono($latitud, $longitud, $puntos)) {
                return [
                    'tri_id' => $tri_id,
                    'zona' => $datos_zonas[$tri_id],
                    'dentro' => 1
                ];
            }
        }

        return [
            'tri_id' => null,
            'zona' => null,
            'dentro' => 0
        ];
    }

    // Función auxiliar para verificar si el punto está dentro del polígono
    function punto_en_poligono($lat, $lng, $puntos)
    {
        $intersecciones = 0;
        $numPuntos = count($puntos);

        for ($i = 0; $i < $numPuntos; $i++) {
            $j = ($i + 1) % $numPuntos;

            $lat_i = $puntos[$i][0];
            $lng_i = $puntos[$i][1];
            $lat_j = $puntos[$j][0];
            $lng_j = $puntos[$j][1];

            if ((($lng_i > $lng) != ($lng_j > $lng)) &&
                ($lat < ($lat_j - $lat_i) * ($lng - $lng_i) / ($lng_j - $lng_i + 1e-10) + $lat_i)
            ) {
                $intersecciones++;
            }
        }

        return ($intersecciones % 2) === 1;
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
