<?php
date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 2) . '/modelo/GENERAL/th_personasM.php');
require_once(dirname(__DIR__, 2) . '/modelo/FIRMAS_ELECTRONICAS/fi_personas_solicitudesM.php');
//require_once(dirname(__DIR__, 2) . '/controlador/FIRMAS_ELECTRONICAS/th_personasC.php');
require_once(dirname(__DIR__, 2) . '/db/codigos_globales.php');
require_once(dirname(__DIR__, 2) . '/lib/phpmailer/enviar_emails.php');

$controlador = new fi_personasC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar());
}

if (isset($_GET['listar_estado'])) {
    echo json_encode($controlador->listar_estado());
}

if (isset($_GET['insertar_administrador'])) {
    echo json_encode($controlador->insertar_administrador($_POST['parametros']));
}

if (isset($_GET['editar'])) {
    echo json_encode($controlador->editar($_POST['parametros']));
}

if (isset($_GET['validar_paso_2'])) {
    echo json_encode($controlador->validar_paso_2());
}

if (isset($_GET['agregar_documentos'])) {
    echo json_encode($controlador->agregar_documentos_repositorio($_FILES, $_POST));
}

if (isset($_GET['guardar_aceptacion'])) {
    echo json_encode($controlador->guardar_aceptacion());
}


class fi_personasC
{
    private $modelo;
    private $cod_global;
    private $fi_personas_solicitudes;
    private $email;

    function __construct()
    {
        $this->modelo = new th_personasM();
        $this->cod_global = new codigos_globales();
        $this->email = new enviar_emails();
        $this->fi_personas_solicitudes = new fi_personas_solicitudesM();
    }

    function listar()
    {
        $_id = isset($_SESSION['INICIO']['NO_CONCURENTE']) ? $_SESSION['INICIO']['NO_CONCURENTE'] : null;
        $datos = [];

        if (!empty($_id)) {
            $datos = $this->modelo->where('th_per_id', $_id)->listar();
        }

        return $datos;
    }

    function listar_estado()
    {
        $datos = $this->modelo->listar();
        return $datos;
    }

    function insertar_administrador($parametros)
    {
        // print_r($parametros);
        // die();

        $contador = $this->modelo->contar();
        $contrasena = 'Cors*00' . $contador;
        //$contrasena = '12345';

        $datos = array(
            array('campo' => 'th_per_correo', 'dato' => $parametros['txt_correo_index']),
            array('campo' => 'PASS', 'dato' => $this->cod_global->enciptar_clave($contrasena)),
            array('campo' => 'th_per_fecha_modificacion', 'dato' => date('Y-m-d H:i:s')),
        );

        if (count($this->modelo->where('th_per_correo', $parametros['txt_correo_index'])->listar()) == 0) {
            $id_persona_insert = $this->modelo->insertar_id($datos);
            // print_r($id_persona_insert);
            // die();
            $datos_sol = array(
                array('campo' => 'th_per_id', 'dato' => $id_persona_insert),
                array('campo' => 'fi_tfo_id', 'dato' => $parametros['ddl_formulario']),
                array('campo' => 'fi_sol_tiempo', 'dato' => $parametros['ddl_tiempo_vigencia']),
                array('campo' => 'fi_sol_fecha_modificacion', 'dato' => date('Y-m-d H:i:s')),
            );

            $datos = $this->fi_personas_solicitudes->insertar($datos_sol);
            $this->enviar_correo($parametros['txt_correo_index'], $contrasena);
        } else {
            return -2;
        }

        return $datos;
    }

    function editar($parametros)
    {
        $_id = isset($_SESSION['INICIO']['NO_CONCURENTE']) ? $_SESSION['INICIO']['NO_CONCURENTE'] : null;
        $datos = [];

        if (!empty($_id)) {
            $txt_fecha_nacimiento = !empty($parametros['txt_fecha_nacimiento']) ? $parametros['txt_fecha_nacimiento'] : null;
            // print_r($_id);
            // die();

            $datos = array(
                array('campo' => 'th_per_primer_apellido', 'dato' => $parametros['txt_primer_apellido']),
                array('campo' => 'th_per_segundo_apellido', 'dato' => $parametros['txt_segundo_apellido']),
                array('campo' => 'th_per_primer_nombre', 'dato' => $parametros['txt_primer_nombre']),
                array('campo' => 'th_per_segundo_nombre', 'dato' => $parametros['txt_segundo_nombre']),
                array('campo' => 'th_per_cedula', 'dato' => $parametros['txt_cedula']),
                array('campo' => 'th_per_sexo', 'dato' => $parametros['ddl_sexo']),
                array('campo' => 'th_per_fecha_nacimiento', 'dato' => $txt_fecha_nacimiento),
                array('campo' => 'th_per_nacionalidad', 'dato' => $parametros['ddl_nacionalidad']),
                array('campo' => 'th_per_telefono_1', 'dato' => $parametros['txt_telefono_1']),
                array('campo' => 'th_per_telefono_2', 'dato' => $parametros['txt_telefono_2']),
                //array('campo' => 'th_per_correo', 'dato' => $parametros['txt_correo']),
                array('campo' => 'th_per_direccion', 'dato' => $parametros['txt_direccion']),
                array('campo' => 'th_per_estado_civil', 'dato' => $parametros['ddl_estado_civil']),
                array('campo' => 'th_prov_id', 'dato' => $parametros['ddl_provincias']),
                array('campo' => 'th_ciu_id', 'dato' => $parametros['ddl_ciudad']),
                array('campo' => 'th_parr_id', 'dato' => $parametros['ddl_parroquia']),
                array('campo' => 'th_per_postal', 'dato' => $parametros['txt_codigo_postal']),
                array('campo' => 'th_per_observaciones', 'dato' => $parametros['txt_observaciones']),
                // array('campo' => 'th_per_foto_url', 'dato' => $parametros['txt_foto_url']),
                //array('campo' => 'PASS', 'dato' => $this->cod_global->enciptar_clave($parametros['txt_cedula'])),
                array('campo' => 'th_per_fecha_modificacion', 'dato' => date('Y-m-d H:i:s')),
            );

            if (count($this->modelo->where('th_per_cedula', $parametros['txt_cedula'])->where('th_per_id !', $_id)->listar()) == 0) {
                $where[0]['campo'] = 'th_per_id';
                $where[0]['dato'] = $_id;
                $datos = $this->modelo->editar($datos, $where);

                $id_ultimo_solicitud = $this->fi_personas_solicitudes->where('th_per_id', $_id)->orderBy('fi_sol_id', 'DESC')->listar(1);

                $datos_2 = array(
                    array('campo' => 'fi_sol_realizado', 'dato' => 1),
                );

                $where = array(
                    array('campo' => 'fi_sol_id', 'dato' => $id_ultimo_solicitud[0]['_id']),
                );

                $datos_2 = $this->fi_personas_solicitudes->editar($datos_2, $where);
            } else {
                return -2;
            }
        }

        return $datos;
    }

    function validar_paso_2()
    {
        $_id = isset($_SESSION['INICIO']['NO_CONCURENTE']) ? $_SESSION['INICIO']['NO_CONCURENTE'] : null;
        $datos = [];

        if (!empty($_id)) {
            $datos = $this->fi_personas_solicitudes->where('th_per_id', $_id)->orderBy('fi_sol_id', 'DESC')->listar(1);
        }

        if (!empty($datos) && isset($datos[0])) {
            return $datos[0];
        }

        return $datos;
    }

    function agregar_documentos_repositorio($file, $parametros)
    {
        $_id = isset($_SESSION['INICIO']['NO_CONCURENTE']) ? $_SESSION['INICIO']['NO_CONCURENTE'] : null;
        $persona_cedula = $this->modelo->where('th_per_id', $_id)->listar(1)[0]['cedula'];
        $persona = array(
            'txt_cedula' => $persona_cedula
        );

        $ultimo_id = $this->validar_paso_2()['_id'];
        $parametros = array_merge($parametros, $persona);

        $datos = array(
            array('campo' => 'fi_sol_realizado_2', 'dato' => 1),
        );

        $where = array(
            array('campo' => 'fi_sol_id', 'dato' => $ultimo_id),
        );

        $datos = $this->fi_personas_solicitudes->editar($datos, $where);

        // print_r($persona);
        // exit();
        // die();

        if ($file['file_foto_personal']['tmp_name'] != '' && $file['file_foto_personal']['tmp_name'] != null) {
            $datos = $this->guardar_archivo($file, $parametros, $ultimo_id, 'file_foto_personal', 'fi_sol_id', 'fi_sol_archivo_foto', 'foto_');
        }

        if ($file['file_copia_cedula']['tmp_name'] != '' && $file['file_copia_cedula']['tmp_name'] != null) {
            $datos = $this->guardar_archivo($file, $parametros, $ultimo_id, 'file_copia_cedula', 'fi_sol_id', 'fi_sol_archivo_cedula', 'cedula_');
        }

        if ($file['file_cert_ruc']['tmp_name'] != '' && $file['file_cert_ruc']['tmp_name'] != null) {
            $datos = $this->guardar_archivo($file, $parametros, $ultimo_id, 'file_cert_ruc', 'fi_sol_id', 'fi_sol_archivo_ruc', 'cert_RUC_');
        }

        if ($file['file_cert_juridico']['tmp_name'] != '' && $file['file_cert_juridico']['tmp_name'] != null) {
            $datos = $this->guardar_archivo($file, $parametros, $ultimo_id, 'file_cert_juridico', 'fi_sol_id', 'fi_sol_archivo_juridico', 'cert_juridico_');
        }

        return $datos;
    }

    function guardar_aceptacion()
    {
        $th_persona_id = $this->validar_paso_2();
        $ultimo_id = $th_persona_id['_id'];
        $th_per_id = $th_persona_id['persona_id'];

        $datos = array(
            array('campo' => 'fi_sol_estado', 'dato' => 1),
        );

        $where = array(
            array('campo' => 'fi_sol_id', 'dato' => $ultimo_id),
        );

        $datos = $this->fi_personas_solicitudes->editar($datos, $where);

        $datos = array(
            array('campo' => 'th_per_estado', 'dato' => 2),
        );

        $where = array(
            array('campo' => 'th_per_id', 'dato' => $th_per_id),
        );

        $datos = $this->modelo->editar($datos, $where);


        return $datos;
    }

    private function guardar_archivo($file, $post, $id_insertar_editar, $name_input_file = '', $atributo_id = '', $atributo_db = '', $nombre_archivo_adicional = '')
    {
        $id_empresa = $_SESSION['INICIO']['ID_EMPRESA'];
        $ruta = dirname(__DIR__, 2) . '/REPOSITORIO/FIRMAS_ELECTRONICAS/' . $id_empresa . '/'; //ruta carpeta donde queremos copiar los archivos
        $ruta .= $post['txt_cedula'] . '/' . 'DOCUMENTOS_CERT/';

        if (!file_exists($ruta)) {
            mkdir($ruta, 0777, true);
        }

        if ($this->validar_formato_archivo($file, $name_input_file) === 1) {
            $uploadfile_temporal = $file[$name_input_file]['tmp_name'];
            $extension = pathinfo($file[$name_input_file]['name'], PATHINFO_EXTENSION);
            //Para referencias laborales
            $nombre = 'documento_' . $nombre_archivo_adicional . $id_insertar_editar . '.' . $extension;
            $nuevo_nom = $ruta . $nombre;

            $nombre_ruta = '../REPOSITORIO/FIRMAS_ELECTRONICAS/' . $id_empresa . '/' . $post['txt_cedula'] . '/' . 'DOCUMENTOS_CERT/';
            $nombre_ruta .= $nombre;

            if (is_uploaded_file($uploadfile_temporal)) {
                if (move_uploaded_file($uploadfile_temporal, $nuevo_nom)) {

                    $datos = array(
                        array('campo' => $atributo_db, 'dato' => $nombre_ruta),
                    );

                    $where = array(
                        array('campo' => $atributo_id, 'dato' => $id_insertar_editar),
                    );

                    // Ejecutar la actualización en la base de datos
                    $base = $this->fi_personas_solicitudes->editar($datos, $where);

                    return $base == 1 ? 1 : -1;
                } else {
                    return -1;
                }
            } else {
                return -1;
            }
        } else {
            return -2;
        }
    }

    private function validar_formato_archivo($file, $name_input_file = '')
    {
        switch ($file[$name_input_file]['type']) {
            case 'application/pdf':
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

    function enviar_correo($correo, $contrasena)
    {
        // $ruta_imagen = '../../img/empresa/apudata.jpeg'; // Cambia esto por la ruta de tu imagen

        // if (file_exists($ruta_imagen)) {
        //     echo "La imagen existe.";
        // } else {
        //     echo "La imagen no se encuentra en la ruta especificada.";
        // }

        date_default_timezone_set('America/Guayaquil');
        $fecha_actual = date('Y-m-d H:i:s');
        $url_plataforma = 'https://corsinf.com:447/corsinf/login.php';
        $url_imagen = 'https://corsinf.com:447/corsinf/img/Firmas/banner_2.jpg';

        $mensaje = '';
        $mensaje .= 'Le informamos que hemos recibido su solicitud para una firma electrónica.<br><br>';
        $mensaje .= '<b>Correo asociado: </b>' . $correo . "<br><br>";
        $mensaje .= '<b>Contraseña asignada: </b>' . $contrasena . "<br><br>";
        $mensaje .= '<b>Fecha de solicitud: </b>' . $fecha_actual . "<br><br>";
        $mensaje .= '<b>Acceda a la plataforma usando la siguiente URL: </b><a href="' . $url_plataforma . '">' . $url_plataforma . '</a><br><br>';
        $mensaje .= '<br><img src="' . $url_imagen . '" alt="Banner Corsinf"><br>';

        $to_correo = $correo;
        $titulo_correo = 'CORSINF - FIRMA ELECTRONICA';
        $cuerpo_correo = utf8_decode($mensaje);

        //return 1;
        return $this->email->enviar_email($to_correo, $cuerpo_correo, $titulo_correo, 'soporte@corsinf.com', false, $titulo_correo, true);
    }
}
