<?php

use function Complex\ln;

require_once(dirname(__DIR__, 2) . '/modelo/SALUD_INTEGRAL/consultasM.php');
require_once(dirname(__DIR__, 2) . '/modelo/SALUD_INTEGRAL/ficha_MedicaM.php');
require_once(dirname(__DIR__, 2) . '/modelo/SALUD_INTEGRAL/pacientesM.php');
include_once(dirname(__DIR__, 2) . '/lib/phpmailer/enviar_emails.php');
include_once(dirname(__DIR__, 2) . '/lib/pdf/fpdf.php');
require_once(dirname(__DIR__, 2) . '/modelo/SALUD_INTEGRAL/det_consultaM.php');

//Seguros
require_once(dirname(__DIR__, 2) . '/modelo/ACTIVOS_FIJOS/contratosM.php');


include_once(dirname(__DIR__, 1) . '/SALUD_INTEGRAL/ingreso_stockC.php');
require_once(dirname(__DIR__, 2) . '/modelo/notificacionesM.php');

//HIKVISION
include_once(dirname(__DIR__, 2) . '/lib/HIKVISION/Notificaciones.php');
include_once(dirname(__DIR__, 2) . '/lib/HIKVISION/HIK_TCP.php');

require_once(dirname(__DIR__, 2) . '/modelo/SALUD_INTEGRAL/representantesM.php');

//Usuarios
require_once(dirname(__DIR__, 2) . '/modelo/usuariosM.php');

//Configuracion General
require_once(dirname(__DIR__, 2) . '/modelo/SALUD_INTEGRAL/cat_configuracionGM.php');

$controlador = new consultasC();

if (isset($_GET['listar_consulta_ficha'])) {

    $id_ficha = '';

    if (isset($_POST['id_ficha'])) {
        $id_ficha = $_POST['id_ficha'];
    }

    echo json_encode($controlador->lista_consultas_ficha($id_ficha));
}

if (isset($_GET['insertar'])) {
    echo json_encode($controlador->insertar_editar($_POST['parametros']));
}

if (isset($_GET['observacion'])) {
    echo json_encode($controlador->observaciones_consulta($_POST['parametros']));
}

if (isset($_GET['eliminar'])) {
    echo json_encode($controlador->eliminar($_POST['id']));
}

if (isset($_GET['listar_solo_consulta'])) {
    echo json_encode($controlador->lista_solo_consultas($_POST['id']));
}

if (isset($_GET['enviar_correo'])) {
    echo json_encode($controlador->enviar_correo($_POST['parametros']));
}

if (isset($_GET['enviar_correo_con'])) {
    //echo json_encode($controlador->enviar_correo_con($_POST['id']));
}

if (isset($_GET['datos_consulta'])) {

    $id_consulta = '';

    if (isset($_POST['id_consulta'])) {
        $id_consulta = $_POST['id_consulta'];
    }

    echo json_encode($controlador->carga_datos_consultas($id_consulta));
}

if (isset($_GET['pdf_consulta'])) {

    //print_r($_POST);die();
    $id_consulta = '';
    if (isset($_GET['id_consulta'])) {
        $id_consulta = $_GET['id_consulta'];
    }

    if (isset($_POST['id_consulta'])) {
        $id_consulta = $_POST['id_consulta'];
    }

    echo ($controlador->pdf_consulta_paciente($id_consulta));
}

if (isset($_GET['pdf_recetario'])) {

    //print_r($_POST);die();
    $id_consulta = '';
    if (isset($_GET['id_consulta'])) {
        $id_consulta = $_GET['id_consulta'];
    }

    if (isset($_POST['id_consulta'])) {
        $id_consulta = $_POST['id_consulta'];
    }

    echo ($controlador->pdf_recetario_consulta_paciente($id_consulta));
}

if (isset($_GET['pdf_notificacion'])) {

    $id_consulta = '';
    if (isset($_GET['id_consulta'])) {
        $id_consulta = $_GET['id_consulta'];
    }

    if (isset($_POST['id_consulta'])) {
        $id_consulta = $_POST['id_consulta'];
    }

    echo ($controlador->pdf_notificacion($id_consulta));
}

if (isset($_GET['listar_todo'])) {
    echo json_encode($controlador->listar_todo($_GET['tabla'], $_GET['fecha_inicio'], $_GET['fecha_fin']));
}

if (isset($_GET['lista_con_est'])) {
    echo json_encode($controlador->lista_consultas_estudiantes($_GET['id_paralelo']));
}

if (isset($_GET['lista_con_est_doc'])) {
    echo json_encode($controlador->lista_consultas_estudiantes_docente($_GET['id_docente'], $_GET['fecha_actual_estado']));
}

if (isset($_GET['contar_consultas_docente'])) {
    echo json_encode($controlador->contar_consultas_estudiantes_docente($_POST['id_docente']));
}


//print_r($controlador->ret(''));

/*$parametros = array(
    'sa_sec_id' => 1,
    'sa_sec_nombre' => 'hola'
);

print_r($controlador->insertar_editar($parametros));*/

/*$modelo = new consultasM();

print_r($modelo->buscar_consultas_CODIGO(1));*/

class consultasC
{
    private $modelo;
    private $ficha_medicaM;
    private $pacientesM;
    private $email;
    private $det_consultaM;
    private $ingreso_stock;
    private $notificaciones;
    private $notificaciones_HV;
    private $TCP_HV;
    private $representantesM;
    private $seguros;
    private $cod_global;
    private $usuariosM;

    private $configGM;

    //Variables para HIKVISION

    private $ip_api_hikvision;
    private $key_api_hikvision;
    private $user_api_hikvision;
    private $tcp_puerto_hikvision;
    private $puerto_api_hikvision;

    function __construct()
    {
        $this->modelo = new consultasM();
        $this->ficha_medicaM = new ficha_MedicaM();
        $this->pacientesM = new pacientesM();
        $this->email = new enviar_emails();
        $this->det_consultaM = new det_consultaM();
        $this->ingreso_stock = new ingreso_stockC();
        $this->notificaciones = new notificacionesM();
        $this->configGM = new cat_configuracionGM();

        //HIKVISION

        // Asegúrate de que las variables de sesión estén definidas antes de usarlas
        if (isset($_SESSION['INICIO'])) {
            $this->ip_api_hikvision = $_SESSION['INICIO']['IP_API_HIKVISION'] ?? '.';
            $this->key_api_hikvision = $_SESSION['INICIO']['KEY_API_HIKVISION'] ?? '.';
            $this->user_api_hikvision = $_SESSION['INICIO']['USER_API_HIKVISION'] ?? '.';
            $this->tcp_puerto_hikvision = $_SESSION['INICIO']['TCP_PUERTO_HIKVISION'] ?? '.';
            $this->puerto_api_hikvision = $_SESSION['INICIO']['PUERTO_API_HIKVISION'] ?? '.';

            // Inicializa los objetos relacionados con Hikvision
            $this->notificaciones_HV = new NotificaionesHV($this->user_api_hikvision, $this->key_api_hikvision, $this->ip_api_hikvision, $this->puerto_api_hikvision, $this->tcp_puerto_hikvision);
            $this->TCP_HV = new HIK_TCP($this->ip_api_hikvision, $this->tcp_puerto_hikvision);
        } else {
            // Manejo de errores si la sesión no está definida
            throw new Exception("La sesión 'INICIO' no está definida.");
        }

        /* ---------------------------------------- */

        $this->representantesM = new representantesM();
        $this->seguros = new contratosM();
        $this->cod_global = new codigos_globales();

        $this->usuariosM = new usuariosM();
    }

    function listar_todo($tabla, $fecha_inicio, $fecha_fin)
    {
        $datos = $this->modelo->lista_consultas_todo($tabla, $fecha_inicio, $fecha_fin);
        return $datos;
    }

    function lista_consultas_ficha($id_ficha)
    {
        $datos = $this->modelo->lista_consultas_ficha($id_ficha);
        return $datos;
    }

    function lista_solo_consultas($id)
    {
        $datos = $this->modelo->lista_solo_consultas($id);
        return $datos;
    }

    function lista_consultas_estudiantes($id_paralelo)
    {
        $datos = $this->modelo->lista_consultas_estudiantes($id_paralelo);
        return $datos;
    }

    function lista_consultas_estudiantes_docente($id_docente, $fecha_actual_estado)
    {
        $datos = $this->modelo->lista_consultas_estudiantes_docente($id_docente, $fecha_actual_estado);
        // print_r($datos);die();
        return $datos;
    }

    function contar_consultas_estudiantes_docente($id_docente)
    {
        $datos = $this->modelo->contar_consultas_estudiantes_docente($id_docente);
        return $datos;
    }

    //Retorna los valores (id) para cargar la ficha medica y el paciente
    function carga_datos_consultas($id_consulta)
    {
        $datos = $this->modelo->carga_datos_consultas($id_consulta);


        return $datos;
    }

    function insertar_editar1($parametros)
    {
        $ip_api_hikvision = $_SESSION['INICIO']['IP_API_HIKVISION'];
        $key_api_hikvision = $_SESSION['INICIO']['KEY_API_HIKVISION'];
        $user_api_hikvision = $_SESSION['INICIO']['USER_API_HIKVISION'];
        $tcp_puerto_hikvision = $_SESSION['INICIO']['TCP_PUERTO_HIKVISION'];
        $puerto_api_hikvision = $_SESSION['INICIO']['PUERTO_API_HIKVISION'];


        //&& $this->tcp_puerto_hikvision != '.'
        if ($this->user_api_hikvision != '.' && $this->key_api_hikvision != '.' && $this->ip_api_hikvision != '.' && $this->puerto_api_hikvision != '.') {
            $mensaje_alerta = '';
            $id_consulta = 40;
            if ($parametros['sa_conp_permiso_tipo'] == 'normal') {
                $mensaje_alerta = 'SALUD ' . $parametros['nombre_apellido_paciente'] . $id_consulta;
                $mensaje_TCP = 'consulta_' . $id_consulta;
                $API_response = $this->notificaciones_HV->crear_Evento_usuario($mensaje_alerta, $mensaje_TCP, 3);
                //print_r($API_response);

                //exit();
                ///////////////////////////////////////////////////////////////////////////////////////


                $max_intentos = 10;
                $intentos = 0;
                while (($API_response != '0') && $intentos < $max_intentos) {
                    usleep(500000);
                    $intentos++;
                }
                if ($API_response == '0') {
                    $this->TCP_HV->TCP_enviar($mensaje_TCP);
                } else if ($API_response == '-1') {
                    //echo "La tarea no se completo después del tiempo maximo.";

                }
                ///////////////////////////////////////////////////////////////////////////////////////
            } else if ($parametros['sa_conp_permiso_tipo'] == 'emergencia') {
                $mensaje_alerta = 'SALUD ' . $parametros['nombre_apellido_paciente'] . $id_consulta;
                $mensaje_TCP = 'consulta_' . $id_consulta;
                $API_response = $this->notificaciones_HV->crear_Evento_usuario($mensaje_alerta, $mensaje_TCP, 2);

                //echo $API_response;

                ///////////////////////////////////////////////////////////////////////////////////////
                $max_intentos = 10;
                $intentos = 0;
                while (($API_response != '0') && $intentos < $max_intentos) {
                    usleep(500000);
                    $intentos++;
                }
                if ($API_response == '0') {
                    $this->TCP_HV->TCP_enviar($mensaje_TCP);
                } else {
                }
                ///////////////////////////////////////////////////////////////////////////////////////
            }
        } else {
            echo $ip_api_hikvision;
            return -1;
        }

        //print_r($key_api_hikvision);  


    }

    function insertar_editar($parametros)
    {
        $datos1[0]['campo'] = 'sa_conp_id';
        $datos1[0]['dato'] = strval($parametros['sa_conp_id']);

        $datos = array(
            array('campo' => 'sa_fice_id', 'dato' => $parametros['sa_fice_id']),
            array('campo' => 'sa_conp_nivel', 'dato' => $parametros['sa_conp_nivel']),
            array('campo' => 'sa_conp_paralelo', 'dato' => $parametros['sa_conp_paralelo']),
            array('campo' => 'sa_conp_edad', 'dato' => $parametros['sa_conp_edad']),
            array('campo' => 'sa_conp_peso', 'dato' => empty($parametros['sa_conp_peso']) ? 0 : $parametros['sa_conp_peso']),
            array('campo' => 'sa_conp_altura', 'dato' => empty($parametros['sa_conp_altura']) ? 0 : $parametros['sa_conp_altura']),
            array('campo' => 'sa_conp_temperatura', 'dato' => empty($parametros['sa_conp_temperatura']) ? 0 : $parametros['sa_conp_temperatura']),
            array('campo' => 'sa_conp_presion_ar', 'dato' => empty($parametros['sa_conp_presion_ar']) ? 0 : $parametros['sa_conp_presion_ar']),
            array('campo' => 'sa_conp_frec_cardiaca', 'dato' => empty($parametros['sa_conp_frec_cardiaca']) ? 0 : $parametros['sa_conp_frec_cardiaca']),
            array('campo' => 'sa_conp_frec_respiratoria', 'dato' => empty($parametros['sa_conp_frec_respiratoria']) ? 0 : $parametros['sa_conp_frec_respiratoria']),
            array('campo' => 'sa_conp_saturacion', 'dato' => empty($parametros['sa_conp_saturacion']) ? 0 : $parametros['sa_conp_saturacion']),

            array('campo' => 'sa_conp_fecha_ingreso', 'dato' => $parametros['sa_conp_fecha_ingreso']),
            array('campo' => 'sa_conp_desde_hora', 'dato' => $parametros['sa_conp_desde_hora']),
            array('campo' => 'sa_conp_hasta_hora', 'dato' => $parametros['sa_conp_hasta_hora']),
            array('campo' => 'sa_conp_tiempo_aten', 'dato' => $parametros['sa_conp_tiempo_aten']),
            array('campo' => 'sa_conp_CIE_10_1', 'dato' => $parametros['sa_conp_CIE_10_1']),
            array('campo' => 'sa_conp_diagnostico_1', 'dato' => $parametros['sa_conp_diagnostico_1']),
            array('campo' => 'sa_conp_CIE_10_2', 'dato' => $parametros['sa_conp_CIE_10_2']),
            array('campo' => 'sa_conp_diagnostico_2', 'dato' => $parametros['sa_conp_diagnostico_2']),

            array('campo' => 'sa_conp_salud_certificado', 'dato' => $parametros['sa_conp_salud_certificado']),
            array('campo' => 'sa_conp_motivo_certificado', 'dato' => $parametros['sa_conp_motivo_certificado']),
            array('campo' => 'sa_conp_CIE_10_certificado', 'dato' => $parametros['sa_conp_CIE_10_certificado']),
            array('campo' => 'sa_conp_diagnostico_certificado', 'dato' => $parametros['sa_conp_diagnostico_certificado']),
            //array('campo' => 'sa_conp_fecha_entrega_certificado', 'dato' => $parametros['sa_conp_fecha_entrega_certificado']),
            //array('campo' => 'sa_conp_fecha_inicio_falta_certificado', 'dato' => $parametros['sa_conp_fecha_inicio_falta_certificado']),
            //array('campo' => 'sa_conp_fecha_fin_alta_certificado', 'dato' => $parametros['sa_conp_fecha_fin_alta_certificado']),
            array('campo' => 'sa_conp_dias_permiso_certificado', 'dato' => $parametros['sa_conp_dias_permiso_certificado']),

            array('campo' => 'sa_conp_permiso_salida', 'dato' => $parametros['sa_conp_permiso_salida']),
            //array('campo' => 'sa_conp_fecha_permiso_salud_salida', 'dato' => $parametros['sa_conp_fecha_permiso_salud_salida']),
            //array('campo' => 'sa_conp_hora_permiso_salida', 'dato' => $parametros['sa_conp_hora_permiso_salida']),
            //array('campo' => 'sa_conp_permiso_tipo', 'dato' => $parametros['sa_conp_permiso_tipo']),
            //array('campo' => 'sa_conp_permiso_seguro_traslado', 'dato' => $parametros['sa_conp_permiso_seguro_traslado']),
            //array('campo' => 'sa_conp_permiso_telefono_padre', 'dato' => $parametros['sa_conp_permiso_telefono_padre']),
            //array('campo' => 'sa_conp_permiso_telefono_seguro', 'dato' => $parametros['sa_conp_permiso_telefono_seguro']),

            array('campo' => 'sa_conp_notificacion_envio_representante', 'dato' => $parametros['sa_conp_notificacion_envio_representante']),
            array('campo' => 'sa_id_representante', 'dato' => $parametros['sa_id_representante']),
            array('campo' => 'sa_conp_notificacion_envio_docente', 'dato' => $parametros['sa_conp_notificacion_envio_docente']),
            array('campo' => 'sa_id_docente', 'dato' => $parametros['sa_id_docente']),
            array('campo' => 'sa_conp_notificacion_envio_inspector', 'dato' => $parametros['sa_conp_notificacion_envio_inspector']),
            array('campo' => 'sa_id_inspector', 'dato' => $parametros['sa_id_inspector']),
            array('campo' => 'sa_conp_notificacion_envio_guardia', 'dato' => $parametros['sa_conp_notificacion_envio_guardia']),
            array('campo' => 'sa_id_guardia', 'dato' => $parametros['sa_id_guardia']),
            array('campo' => 'sa_conp_tipo_consulta', 'dato' => $parametros['sa_conp_tipo_consulta']),
            array('campo' => 'sa_conp_enfermedad_actual', 'dato' => $parametros['sa_conp_enfermedad_actual']),

            array('campo' => 'sa_conp_observaciones', 'dato' => $parametros['sa_conp_observaciones']),
            array('campo' => 'sa_conp_motivo_consulta', 'dato' => $parametros['sa_conp_motivo_consulta']),
            array('campo' => 'sa_conp_tratamiento', 'dato' => $parametros['sa_conp_tratamiento']),
            array('campo' => 'sa_conp_estado_revision', 'dato' => $parametros['sa_conp_estado_revision']),

            array('campo' => 'sa_examen_fisico_regional', 'dato' => $parametros['sa_examen_fisico_regional'], 'tipo' => 'STRING'),

            array('campo' => 'sa_conp_usu_id', 'dato' => $_SESSION['INICIO']['ID_USUARIO']),

            array('campo' => 'sa_conp_condicion_alta', 'dato' => $parametros['sa_conp_condicion_alta']),
        );


        $fechas_certificado = null;
        if ($parametros['sa_conp_tipo_consulta'] === 'certificado') {
            $fechas_certificado = array(
                array('campo' => 'sa_conp_fecha_entrega_certificado', 'dato' => $parametros['sa_conp_fecha_entrega_certificado']),
                array('campo' => 'sa_conp_fecha_inicio_falta_certificado', 'dato' => $parametros['sa_conp_fecha_inicio_falta_certificado']),
                array('campo' => 'sa_conp_fecha_fin_alta_certificado', 'dato' => $parametros['sa_conp_fecha_fin_alta_certificado']),
            );
            $datos = array_merge($datos, $fechas_certificado);
        }

        //Para la salida 
        $fechas_salida = null;
        if ($parametros['sa_conp_permiso_salida'] === 'SI') {
            $fechas_salida = array(
                array('campo' => 'sa_conp_fecha_permiso_salud_salida', 'dato' => $parametros['sa_conp_fecha_permiso_salud_salida']),

                array('campo' => 'sa_conp_hora_permiso_salida', 'dato' => $parametros['sa_conp_hora_permiso_salida']),
                array('campo' => 'sa_conp_permiso_tipo', 'dato' => $parametros['sa_conp_permiso_tipo']),
            );
            $datos = array_merge($datos, $fechas_salida);
        }

        if ($parametros['sa_conp_permiso_tipo'] === 'emergencia') {
            $salida_tipo = array(
                array('campo' => 'sa_conp_permiso_seguro_traslado', 'dato' => $parametros['sa_conp_permiso_seguro_traslado']),
                array('campo' => 'sa_conp_permiso_telefono_padre', 'dato' => $parametros['sa_conp_permiso_telefono_padre']),
                array('campo' => 'sa_conp_permiso_telefono_seguro', 'dato' => $parametros['sa_conp_permiso_telefono_seguro']),
            );
            $datos = array_merge($datos, $salida_tipo);
        }

        // print_r($parametros);die();

        /*
        *
        Datos que se puede usar en el insert y en el update 
        *
        */

        /* Enviar mensaje a padre de familia Insert*/
        $tipo_consulta = $parametros['sa_conp_tipo_consulta'];
        $id_representante = $parametros['sa_pac_temp_rep_id'];
        $sa_pac_temp_rep2_id = $parametros['sa_pac_temp_rep2_id'];
        $chx_representante = $parametros['chx_representante'] ?? '';
        $chx_representante_2 = $parametros['chx_representante_2'] ?? '';


        $nombre_est = $parametros['nombre_paciente'];
        $diagnostico = '';
        $permiso_salida = '';

        if ($parametros['sa_conp_permiso_salida'] == 'SI') {
            $permiso_salida = $parametros['sa_conp_permiso_tipo'];
        } else {
            $permiso_salida = 'NO';
        }

        $icono = "bx bxs-file-plus";
        $respuesta_servicio_API = '';
        $condicion_alta = $parametros['sa_conp_condicion_alta'] ?? '';


        if ($parametros['sa_conp_id'] == '') {
            if (count($this->modelo->buscar_consultas_CODIGO($datos1[0]['dato'])) == 0) {
                // print_r('expression');die();
                //Se inserta los datos de la consulta
                $id_insert = $this->modelo->insertar_id($datos);

                // print_r($id_insert);die();

                /* ----------------------*/
                //    Notificaciones
                /* ----------------------*/

                //Inicio datos del estudiante
                if ($parametros['txt_paciente_tabla'] == 'estudiantes') {
                    //Notificacion para el docente
                    $datos_notificaciones = array(
                        array('campo' => 'GLO_modulo', 'dato' => '7'),
                        array('campo' => 'GLO_titulo', 'dato' => $parametros['sa_conp_tipo_consulta']),
                        array('campo' => 'GLO_cuerpo', 'dato' => $parametros['nombre_paciente']),
                        array('campo' => 'GLO_icono', 'dato' => $icono),
                        array('campo' => 'GLO_tabla', 'dato' => 'docentes'),
                        array('campo' => 'GLO_id_tabla', 'dato' => ''),
                        array('campo' => 'GLO_busqueda_especifica', 'dato' => $parametros['sa_id_paralelo']),
                        array('campo' => 'GLO_desc_busqueda', 'dato' => 'Para listar los estudiantes con el docente respectivo'),
                        array('campo' => 'GLO_link_redirigir', 'dato' => '../vista/inicio.php?acc=historial_salud_estudiantil'),
                        array('campo' => 'GLO_rol', 'dato' => 'docentes'),
                        array('campo' => 'GLO_observacion', 'dato' => ''),
                    );

                    $this->notificaciones->insertar($datos_notificaciones);

                    //Notificacion para el inspector
                    $datos_notificaciones = array(
                        array('campo' => 'GLO_modulo', 'dato' => '7'),
                        array('campo' => 'GLO_titulo', 'dato' => $parametros['sa_conp_tipo_consulta']),
                        array('campo' => 'GLO_cuerpo', 'dato' => $parametros['nombre_paciente']),
                        array('campo' => 'GLO_icono', 'dato' => $icono),
                        array('campo' => 'GLO_tabla', 'dato' => ''),
                        array('campo' => 'GLO_id_tabla', 'dato' => ''),
                        array('campo' => 'GLO_busqueda_especifica', 'dato' => ''),
                        array('campo' => 'GLO_desc_busqueda', 'dato' => 'Para listar todas las consultas de estudiantes'),
                        array('campo' => 'GLO_link_redirigir', 'dato' => '../vista/inicio.php?acc=consultas_estudiantes'),
                        array('campo' => 'GLO_rol', 'dato' => 'INSPECTOR'),
                        array('campo' => 'GLO_observacion', 'dato' => ''),
                    );

                    $this->notificaciones->insertar($datos_notificaciones);

                    //Notificación para el representante
                    $url_rep_noti = '../vista/inicio.php?mod=7&acc=detalle_consulta&pdf_consulta=true&id_consulta=' . $id_insert . '&tipo_consulta=' . $parametros['sa_conp_tipo_consulta'] . '&btn_regresar=represententes';
                    $datos_notificaciones = array(
                        array('campo' => 'GLO_modulo', 'dato' => '7'),
                        array('campo' => 'GLO_titulo', 'dato' => $parametros['sa_conp_tipo_consulta']),
                        array('campo' => 'GLO_cuerpo', 'dato' => $parametros['nombre_paciente']),
                        array('campo' => 'GLO_icono', 'dato' => $icono),
                        array('campo' => 'GLO_tabla', 'dato' => 'representantes'),
                        array('campo' => 'GLO_id_tabla', 'dato' => $id_representante),
                        array('campo' => 'GLO_busqueda_especifica', 'dato' => $id_insert),
                        array('campo' => 'GLO_desc_busqueda', 'dato' => 'Para mostrar consulta al representante'),
                        array('campo' => 'GLO_link_redirigir', 'dato' => $url_rep_noti),
                        array('campo' => 'GLO_rol', 'dato' => 'REPRESENTANTE'),
                        array('campo' => 'GLO_observacion', 'dato' => ''),
                    );

                    //Para arreglar
                    // $datos_notificaciones = array(
                    //     array('campo' => 'GLO_modulo', 'dato' => '7'),
                    //     array('campo' => 'GLO_titulo', 'dato' => $parametros['sa_conp_tipo_consulta']),
                    //     array('campo' => 'GLO_cuerpo', 'dato' => $parametros['nombre_paciente']),
                    //     array('campo' => 'GLO_icono', 'dato' => $icono),
                    //     array('campo' => 'GLO_tabla', 'dato' => 'representantes'),
                    //     array('campo' => 'GLO_id_tabla', 'dato' => $sa_pac_temp_rep2_id),
                    //     array('campo' => 'GLO_busqueda_especifica', 'dato' => $id_insert),
                    //     array('campo' => 'GLO_desc_busqueda', 'dato' => 'Para mostrar consulta al representante'),
                    //     array('campo' => 'GLO_link_redirigir', 'dato' => $url_rep_noti),
                    //     array('campo' => 'GLO_rol', 'dato' => 'REPRESENTANTE'),
                    //     array('campo' => 'GLO_observacion', 'dato' => ''),
                    // );

                    $this->notificaciones->insertar($datos_notificaciones);

                    /*HIKVISION*/
                    if ($parametros['sa_conp_permiso_salida'] === 'SI') {

                        /*HIKVISION*/
                        //Variable para manejar estado de HIKVISION
                        if ($this->user_api_hikvision != '.' && $this->key_api_hikvision != '.' && $this->ip_api_hikvision != '.' && $this->puerto_api_hikvision != '.') {
                            $mensaje_alerta = '';
                            $id_consulta = $id_insert;
                            $parametros['sa_conp_permiso_tipo'];
                            // print_r($parametros['sa_conp_permiso_tipo']);
                            //exit();
                            if ($parametros['sa_conp_permiso_tipo'] == 'normal') {
                                $mensaje_alerta = 'SALUD ' . $parametros['nombre_apellido_paciente'] . $id_consulta;
                                $mensaje_TCP = 'consulta_' . $id_consulta;
                                $API_response = $this->notificaciones_HV->crear_Evento_usuario($mensaje_alerta, $mensaje_TCP, 3);
                                $respuesta_servicio_API = $API_response;
                                //print_r($respuesta_servicio_API);
                                //exit;
                                ///////////////////////////////////////////////////////////////////////////////////////
                                $max_intentos = 10;
                                $intentos = 0;
                                while (($API_response != '0') && $intentos < $max_intentos) {
                                    usleep(500000);
                                    $intentos++;
                                }
                                if ($API_response == '0') {
                                    if ($this->tcp_puerto_hikvision != '.') {
                                        $this->TCP_HV->TCP_enviar($mensaje_TCP);
                                    }
                                } else {
                                    //echo "La tarea no se completó después del tiempo máximo.";
                                }
                                ///////////////////////////////////////////////////////////////////////////////////////
                            } else if ($parametros['sa_conp_permiso_tipo'] == 'emergencia') {
                                $mensaje_alerta = 'SALUD ' . $parametros['nombre_apellido_paciente'] . $id_consulta;
                                $mensaje_TCP = 'consulta_' . $id_consulta;
                                $API_response = $this->notificaciones_HV->crear_Evento_usuario($mensaje_alerta, $mensaje_TCP, 2);
                                $respuesta_servicio_API = $API_response;
                                ///////////////////////////////////////////////////////////////////////////////////////
                                $max_intentos = 10;
                                $intentos = 0;
                                while (($API_response != '0') && $intentos < $max_intentos) {
                                    usleep(500000);
                                    $intentos++;
                                }
                                if ($API_response == '0') {
                                    if ($this->tcp_puerto_hikvision != '.') {
                                        $this->TCP_HV->TCP_enviar($mensaje_TCP);
                                    }
                                } else {
                                    //echo "La tarea no se completó después del tiempo máximo.";
                                }
                                ///////////////////////////////////////////////////////////////////////////////////////
                            }
                        }
                    }
                }
                //Fin datos del estudiante

                /////////////////////////////////////////////////////////////////////////////////

                //echo($idConsultaPrincipal);die();
                //Inicio farmacologia
                if (!empty($parametros['filas_tabla_farmacologia'])) {

                    foreach ($parametros['filas_tabla_farmacologia'] as $fila) {
                        $datos_farmacologia = array();

                        $estado_entrega = -1;
                        if ($fila['sa_det_conp_estado_entrega'] == 'true') {
                            $estado_entrega = 1;
                        } elseif ($fila['sa_det_conp_estado_entrega'] == 'false') {
                            $estado_entrega = 0;
                        }

                        $datos_farmacologia = array(
                            array('campo' => 'sa_id_conp', 'dato' => $id_insert),
                            array('campo' => 'sa_det_conp_id_cmed_cins', 'dato' => $fila['sa_det_conp_id_cmed_cins']),
                            array('campo' => 'sa_det_conp_tipo', 'dato' => $fila['sa_det_conp_tipo']),
                            array('campo' => 'sa_det_conp_nombre', 'dato' => $fila['sa_det_conp_nombre']),
                            array('campo' => 'sa_det_conp_cantidad', 'dato' => $fila['sa_det_conp_cantidad']),
                            array('campo' => 'sa_det_conp_dosificacion', 'dato' => $fila['sa_det_conp_dosificacion']),
                            array('campo' => 'sa_det_conp_estado_entrega', 'dato' =>  $estado_entrega),
                        );

                        $datos_farmacologia = $this->det_consultaM->insertar($datos_farmacologia);

                        //Insertar 
                        ////////////////////////////////////////////////////////////////////////////////////////////////////////////
                        //Para reducir el stock
                        if ($estado_entrega == 1) {
                            $tipo_tabla = '';
                            if ($fila['sa_det_conp_tipo'] == 'medicamentos') {
                                $tipo_tabla = ucfirst($fila['sa_det_conp_tipo']);
                                $tipo_tabla = substr($tipo_tabla, 0, -1);
                            } else if ($fila['sa_det_conp_tipo'] == 'insumos') {
                                $tipo_tabla = ucfirst($fila['sa_det_conp_tipo']);
                            }

                            $datos_stock = array(
                                'orden' => 'Consulta - ' . $id_insert,
                                'ddl_tipo' => $tipo_tabla,
                                'ddl_lista_productos' => $fila['sa_det_conp_id_cmed_cins'],
                                'txt_canti' => $fila['sa_det_conp_cantidad'],
                                'txt_subtotal' => 0,
                                'txt_total' => 0,
                            );

                            //print_r($tipo_tabla);die();
                            $this->ingreso_stock->producto_nuevo_salida($datos_stock);
                        }
                        ////////////////////////////////////////////////////////////////////////////////////////////////////////////
                    }
                }
                //Fin farmacologia

                if ($respuesta_servicio_API == -10) {
                    return -10;
                } else {
                    $datos = 1;
                }
            } else {
                return -2 . ' . ' . $datos1[0]['dato'];
            }
        } else {
            $where[0]['campo'] = 'sa_conp_id';
            $where[0]['dato'] = $parametros['sa_conp_id'];
            //$datos[] = array('campo' => 'sa_conp_estado', 'dato' => 1);
            $datos = $this->modelo->editar($datos, $where);

            /* ----------------------*/
            //    Notificaciones
            /* ----------------------*/


            //Inicio datos del estudiante
            if ($parametros['txt_paciente_tabla'] == 'estudiantes') {
                //Notificacion para el docente
                $datos_notificaciones = array(
                    array('campo' => 'GLO_modulo', 'dato' => '7'),
                    array('campo' => 'GLO_titulo', 'dato' => $parametros['sa_conp_tipo_consulta']),
                    array('campo' => 'GLO_cuerpo', 'dato' => $parametros['nombre_paciente']),
                    array('campo' => 'GLO_icono', 'dato' => $icono),
                    array('campo' => 'GLO_tabla', 'dato' => 'docentes'),
                    array('campo' => 'GLO_id_tabla', 'dato' => ''),
                    array('campo' => 'GLO_busqueda_especifica', 'dato' => $parametros['sa_id_paralelo']),
                    array('campo' => 'GLO_desc_busqueda', 'dato' => 'Para listar los estudiantes con el docente respectivo'),
                    array('campo' => 'GLO_link_redirigir', 'dato' => '../vista/inicio.php?acc=historial_salud_estudiantil'),
                    array('campo' => 'GLO_rol', 'dato' => 'docentes'),
                    array('campo' => 'GLO_observacion', 'dato' => ''),
                );

                $this->notificaciones->insertar($datos_notificaciones);

                //Notificacion para el inspector
                $datos_notificaciones = array(
                    array('campo' => 'GLO_modulo', 'dato' => '7'),
                    array('campo' => 'GLO_titulo', 'dato' => $parametros['sa_conp_tipo_consulta']),
                    array('campo' => 'GLO_cuerpo', 'dato' => $parametros['nombre_paciente']),
                    array('campo' => 'GLO_icono', 'dato' => $icono),
                    array('campo' => 'GLO_tabla', 'dato' => ''),
                    array('campo' => 'GLO_id_tabla', 'dato' => ''),
                    array('campo' => 'GLO_busqueda_especifica', 'dato' => ''),
                    array('campo' => 'GLO_desc_busqueda', 'dato' => 'Para listar todas las consultas de estudiantes'),
                    array('campo' => 'GLO_link_redirigir', 'dato' => '../vista/inicio.php?acc=consultas_estudiantes'),
                    array('campo' => 'GLO_rol', 'dato' => 'INSPECTOR'),
                    array('campo' => 'GLO_observacion', 'dato' => ''),
                );

                $this->notificaciones->insertar($datos_notificaciones);

                //Notificacion para el representante
                $url_rep_noti = '../vista/inicio.php?mod=7&acc=detalle_consulta&pdf_consulta=true&id_consulta=' . $parametros['sa_conp_id'] . '&tipo_consulta=' . $parametros['sa_conp_tipo_consulta'] . '&btn_regresar=represententes';
                $datos_notificaciones = array(
                    array('campo' => 'GLO_modulo', 'dato' => '7'),
                    array('campo' => 'GLO_titulo', 'dato' => $parametros['sa_conp_tipo_consulta']),
                    array('campo' => 'GLO_cuerpo', 'dato' => $parametros['nombre_paciente']),
                    array('campo' => 'GLO_icono', 'dato' => $icono),
                    array('campo' => 'GLO_tabla', 'dato' => 'representantes'),
                    array('campo' => 'GLO_id_tabla', 'dato' => $id_representante),
                    array('campo' => 'GLO_busqueda_especifica', 'dato' => $parametros['sa_conp_id']),
                    array('campo' => 'GLO_desc_busqueda', 'dato' => 'Para mostrar consulta al representante'),
                    array('campo' => 'GLO_link_redirigir', 'dato' => $url_rep_noti),
                    array('campo' => 'GLO_rol', 'dato' => 'REPRESENTANTE'),
                    array('campo' => 'GLO_observacion', 'dato' => ''),
                );

                $this->notificaciones->insertar($datos_notificaciones);

                /*HIKVISION*/
                if ($parametros['sa_conp_permiso_salida'] === 'SI') {

                    /*HIKVISION*/
                    //Variable para manejar estado de HIKVISION
                    if ($this->user_api_hikvision != '.' && $this->key_api_hikvision != '.' && $this->ip_api_hikvision != '.' && $this->puerto_api_hikvision != '.' && $this->tcp_puerto_hikvision != '.') {
                        $mensaje_alerta = '';
                        $id_consulta = $parametros['sa_conp_id'];
                        if ($parametros['sa_conp_permiso_tipo'] == 'normal') {
                            $mensaje_alerta = 'SALUD ' . $parametros['nombre_apellido_paciente'] . $id_consulta;
                            $mensaje_TCP = 'consulta_' . $id_consulta;
                            $API_response = $this->notificaciones_HV->crear_Evento_usuario($mensaje_alerta, $mensaje_TCP, 3);
                            $respuesta_servicio_API = $API_response;
                            ///////////////////////////////////////////////////////////////////////////////////////
                            $max_intentos = 10;
                            $intentos = 0;
                            while (($API_response != '0') && $intentos < $max_intentos) {
                                usleep(500000);
                                $intentos++;
                            }
                            if ($API_response == '0') {
                                if ($this->tcp_puerto_hikvision != '.') {
                                    $this->TCP_HV->TCP_enviar($mensaje_TCP);
                                }
                            } else {
                                //echo "La tarea no se completó después del tiempo máximo.";
                            }
                            ///////////////////////////////////////////////////////////////////////////////////////
                        } else if ($parametros['sa_conp_permiso_tipo'] == 'emergencia') {
                            $mensaje_alerta = 'SALUD ' . $parametros['nombre_apellido_paciente'] . $id_consulta;
                            $mensaje_TCP = 'consulta_' . $id_consulta;
                            $API_response = $this->notificaciones_HV->crear_Evento_usuario($mensaje_alerta, $mensaje_TCP, 2);
                            $respuesta_servicio_API = $API_response;
                            ///////////////////////////////////////////////////////////////////////////////////////
                            $max_intentos = 10;
                            $intentos = 0;
                            while (($API_response != '0') && $intentos < $max_intentos) {
                                usleep(500000);
                                $intentos++;
                            }
                            if ($API_response == '0') {
                                if ($this->tcp_puerto_hikvision != '.') {
                                    $this->TCP_HV->TCP_enviar($mensaje_TCP);
                                }
                            } else {
                                //echo "La tarea no se completó después del tiempo máximo.";
                            }
                            ///////////////////////////////////////////////////////////////////////////////////////
                        }
                    }
                }
            }
            //Fin datos del estudiante

            /////////////////////////////////////////////////////////////////////////////////
            //Inicio farmacologia
            if (!empty($parametros['filas_tabla_farmacologia'])) {
                foreach ($parametros['filas_tabla_farmacologia'] as $fila) {
                    $datos_farmacologia = array();

                    if ($fila['sa_det_conp_id'] == '') {

                        $estado_entrega = -1;
                        if ($fila['sa_det_conp_estado_entrega'] == 'true') {
                            $estado_entrega = 1;
                        } elseif ($fila['sa_det_conp_estado_entrega'] == 'false') {
                            $estado_entrega = 0;
                        }

                        $datos_farmacologia = array(
                            array('campo' => 'sa_id_conp', 'dato' => $parametros['sa_conp_id']),
                            array('campo' => 'sa_det_conp_id_cmed_cins', 'dato' => $fila['sa_det_conp_id_cmed_cins']),
                            array('campo' => 'sa_det_conp_tipo', 'dato' => $fila['sa_det_conp_tipo']),
                            array('campo' => 'sa_det_conp_nombre', 'dato' => $fila['sa_det_conp_nombre']),
                            array('campo' => 'sa_det_conp_cantidad', 'dato' => $fila['sa_det_conp_cantidad']),
                            array('campo' => 'sa_det_conp_dosificacion', 'dato' => $fila['sa_det_conp_dosificacion']),
                            array('campo' => 'sa_det_conp_estado_entrega', 'dato' =>  $estado_entrega),
                        );

                        $datos_farmacologia = $this->det_consultaM->insertar($datos_farmacologia);

                        //Modificar
                        ////////////////////////////////////////////////////////////////////////////////////////////////////////////
                        //Para reducir el stock
                        if ($estado_entrega == 1) {
                            $tipo_tabla = '';
                            if ($fila['sa_det_conp_tipo'] == 'medicamentos') {
                                $tipo_tabla = ucfirst($fila['sa_det_conp_tipo']);
                                $tipo_tabla = substr($tipo_tabla, 0, -1);
                            } else if ($fila['sa_det_conp_tipo'] == 'insumos') {
                                $tipo_tabla = ucfirst($fila['sa_det_conp_tipo']);
                            }

                            $datos_stock = array(
                                'orden' => 'Consulta - ' . $parametros['sa_conp_id'],
                                'ddl_tipo' => $tipo_tabla,
                                'ddl_lista_productos' => $fila['sa_det_conp_id_cmed_cins'],
                                'txt_canti' => $fila['sa_det_conp_cantidad'],
                                'txt_subtotal' => 0,
                                'txt_total' => 0,
                            );

                            //print_r($tipo_tabla);die();
                            $this->ingreso_stock->producto_nuevo_salida($datos_stock);
                        }
                        ////////////////////////////////////////////////////////////////////////////////////////////////////////////
                    } else {

                        $estado_entrega = -1;
                        if ($fila['sa_det_conp_estado_entrega'] == 'true') {
                            $estado_entrega = 1;
                        } elseif ($fila['sa_det_conp_estado_entrega'] == 'false') {
                            $estado_entrega = 0;
                        }

                        $datos_farmacologia = array(
                            array('campo' => 'sa_det_conp_id_cmed_cins', 'dato' => $fila['sa_det_conp_id_cmed_cins']),
                            array('campo' => 'sa_det_conp_tipo', 'dato' => $fila['sa_det_conp_tipo']),
                            array('campo' => 'sa_det_conp_nombre', 'dato' => $fila['sa_det_conp_nombre']),
                            array('campo' => 'sa_det_conp_cantidad', 'dato' => $fila['sa_det_conp_cantidad']),
                            array('campo' => 'sa_det_conp_dosificacion', 'dato' => $fila['sa_det_conp_dosificacion']),
                            array('campo' => 'sa_det_conp_estado_entrega', 'dato' => $estado_entrega),
                        );

                        $where[0]['campo'] = 'sa_det_conp_id';
                        $where[0]['dato'] = $fila['sa_det_conp_id'];

                        $datos_farmacologia = $this->det_consultaM->editar($datos_farmacologia, $where);

                        //Modificar
                        ////////////////////////////////////////////////////////////////////////////////////////////////////////////
                        //Para reducir el stock
                        if ($estado_entrega == 1) {
                            $tipo_tabla = '';
                            if ($fila['sa_det_conp_tipo'] == 'medicamentos') {
                                $tipo_tabla = ucfirst($fila['sa_det_conp_tipo']);
                                $tipo_tabla = substr($tipo_tabla, 0, -1);
                            } else if ($fila['sa_det_conp_tipo'] == 'insumos') {
                                $tipo_tabla = ucfirst($fila['sa_det_conp_tipo']);
                            }

                            $datos_stock = array(
                                'orden' => 'Consulta - ' . $parametros['sa_conp_id'],
                                'ddl_tipo' => $tipo_tabla,
                                'ddl_lista_productos' => $fila['sa_det_conp_id_cmed_cins'],
                                'txt_canti' => $fila['sa_det_conp_cantidad'],
                                'txt_subtotal' => 0,
                                'txt_total' => 0,
                            );

                            //print_r($tipo_tabla);die();
                            $this->ingreso_stock->producto_nuevo_salida($datos_stock);
                        }
                        ////////////////////////////////////////////////////////////////////////////////////////////////////////////
                    }
                }
            }
            //Fin farmacologia
        }

        /*$where[0]['campo'] = 'sa_conp_id';
        $where[0]['dato'] = $parametros['sa_conp_id'];
        $datos = $this->modelo->editar($datos, $where);*/

        //$datos = $this->modelo->insertar($datos);
        if ($respuesta_servicio_API == -10) {
            return -10;
        } else {
            if ($datos == 1) {
                /////////////////////////////////////////////////////////////////////////////////
                //Para enviar correo a los padres de familia
                if ($parametros['txt_paciente_tabla'] == 'estudiantes') {
                    $farmacos = '';
                    if (!empty($parametros['filas_tabla_farmacologia'])) {
                        $farmacos = $parametros['filas_tabla_farmacologia'];
                    }



                    if ($tipo_consulta == 'consulta') {
                        $diagnostico = $parametros['sa_conp_diagnostico_1'];

                        if ($chx_representante === 'true' && $chx_representante != '') {
                            $this->enviar_correo_con($id_representante, $nombre_est, $diagnostico, $tipo_consulta, $permiso_salida, $condicion_alta, $farmacos);
                        }

                        if ($chx_representante_2 === 'true' && $chx_representante_2 != '') {
                            $this->enviar_correo_con($sa_pac_temp_rep2_id, $nombre_est, $diagnostico, $tipo_consulta, $permiso_salida, $condicion_alta, $farmacos);
                        }

                        //echo $chx_representante_2; exit; die();
                        //print_r($variable);exit();die();
                    } else {
                        $diagnostico = $parametros['sa_conp_diagnostico_certificado'];
                        if ($chx_representante === 'true' && $chx_representante != '') {
                            $this->enviar_correo_con($id_representante, $nombre_est, $diagnostico, $tipo_consulta, $permiso_salida, $condicion_alta, $farmacos);
                        }

                        if ($chx_representante_2 === 'true' && $chx_representante_2 != '') {
                            $this->enviar_correo_con($sa_pac_temp_rep2_id, $nombre_est, $diagnostico, $tipo_consulta, $permiso_salida, $condicion_alta, $farmacos);
                        }
                    }
                }
                /////////////////////////////////////////////////////////////////////////////////
            }

            return $datos;
        }
    }

    function observaciones_consulta($parametros)
    {

        $datos = array(
            array('campo' => 'sa_conp_observaciones', 'dato' => $parametros['sa_conp_observaciones']),
        );

        $where[0]['campo'] = 'sa_conp_id';
        $where[0]['dato'] = $parametros['sa_conp_id'];
        //$datos[] = array('campo' => 'sa_conp_estado', 'dato' => 1);
        $datos = $this->modelo->editar($datos, $where);


        return $datos;
    }

    function eliminar($id)
    {
        $datos[0]['campo'] = 'sa_conp_id';
        $datos[0]['dato'] = $id;
        $datos = $this->modelo->eliminar($datos);
        return $datos;
    }

    // Atención Departamento Médico 
    // Nombres del estudiante: Juan Díaz.
    // Motivo: Cefalea 
    // Medicación administrada: ibuprofeno 400mg
    // Condición de alta: regresa a clases. 
    // Agradecida por su atención.
    // Att: Departamento Médico.
    function enviar_correo_con($id_representante, $nombres_est = '', $diagnostico = '', $tipo_consulta = '', $permiso_salida = '', $condicion_alta, $farmacos)
    {
        date_default_timezone_set('America/Guayaquil');
        $fecha_actual = date('Y-m-d H:i:s');
        $mensaje = '';

        $correo_rep = $this->representantesM->lista_representantes($id_representante);
        $correo_rep = $correo_rep[0]['sa_rep_correo'];

        $tipo_usuario = '';
        if (strtoupper($_SESSION['INICIO']['TIPO']) == 'MEDICO') {
            $tipo_usuario = 'Dra. ';
        } else {
            $tipo_usuario = '';
        }

        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        //Seccion para farmacos
        $salida_farmacos = '';
        if ($farmacos != '') {
            $salida_farmacos .= '<b>Medicamento/s recetado/s: </b>' . "<br>";
            foreach ($farmacos as $fila) {
                $cantidad = '';
                if ($fila['sa_det_conp_cantidad'] == 0) {
                    $cantidad = 'Según la dosis indicada';
                } else {
                    $cantidad = $fila['sa_det_conp_cantidad'];
                }
                $salida_farmacos .= $fila['sa_det_conp_nombre'] . ', Cantidad: ' . $cantidad . '<br>';
            }
        }

        // print_r($salida_farmacos);
        // exit();
        // die();
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        if ($tipo_consulta == 'consulta') {
            $mensaje .= 'Le comunico sobre la atención brindada a <b>' . $nombres_est . ".</b><br><br>";
            $mensaje .= '<b>Diagnóstico presuntivo: </b>' . $diagnostico . "<br><br>";
            $mensaje .= '<b>Hora de atención: </b>' . $fecha_actual . "<br><br>";
            $mensaje .= '<b>Motivo: </b>' . strtoupper($tipo_consulta) . "<br><br>";
            $mensaje .= '<b>Permiso de salida: </b>' . strtoupper($permiso_salida) . "<br><br>";
            $mensaje .= '<b>Condición de alta: </b>' . strtoupper($condicion_alta) . "<br><br>";
            $mensaje .= $salida_farmacos . "<br>";
            $mensaje .= '<b>Atendido por: </b>' . $tipo_usuario . strtoupper($_SESSION['INICIO']['USUARIO']) . "<br><br>";
        } else {
            $mensaje .= 'Le comunico para informarle que se recibió el certficado médico de <b>' . $nombres_est . ".</b><br><br>";
            $mensaje .= '<b>Diagnóstico presuntivo: </b>' . $diagnostico . "<br><br>";
            $mensaje .= '<b>Hora de recepción: </b>' . $fecha_actual . "<br><br>";
            $mensaje .= '<b>Motivo: </b>' . strtoupper($tipo_consulta) . "<br><br>";
            $mensaje .= '<b>Atendido por: </b>' . $tipo_usuario . strtoupper($_SESSION['INICIO']['USUARIO']) . "<br><br>";
        }

        // print_r($parametros);die();
        $to_correo = $correo_rep;
        $titulo_correo = 'ATENCION - DEPARTAMENTO MEDICO';
        $cuerpo_correo = $mensaje;
        $cuerpo_correo = utf8_decode($mensaje);

        $validacion_correo_representantes = $this->configGM->validacion('enviar_correos_consultas_reps');

        if ($validacion_correo_representantes == 1) {
            return $this->email->enviar_email($to_correo, $cuerpo_correo, $titulo_correo, $correo_respaldo = 'soporte@corsinf.com', $archivos = false, $titulo_correo, true);
        } else {
            return true;
        }
    }

    function enviar_correo($parametros)
{
     echo "✔ La función enviar_correo() SI está entrando<br>";
    // VALORES ESTÁTICOS — NO DEPENDE DE $parametros
    $to_correo      = "elvisfabian1296@gmail.com";   // DESTINATARIO FIJO
    $titulo_correo  = "Prueba de correo";            // ASUNTO FIJO
    $cuerpo_correo  = "<h1>Hola mundo</h1>";         // MENSAJE FIJO
    $correo_respaldo = "soporte@corsinf.com";        // FROM FIJO
    $archivos        = false;                        // SIN ADJUNTOS
    $nombre          = "Sistema";                    // NOMBRE FROM
    $HTML            = true;                         // ENVÍA HTML

    // IMPORTANTE: usar tu clase enviar_emails
    return $this->email->enviar_email(
        $to_correo,
        $cuerpo_correo,
        $titulo_correo,
        $correo_respaldo,
        $archivos,
        $nombre,
        $HTML
    );
}


    function pdf_consulta_paciente($id_consulta)
    {
        $datos = $this->modelo->lista_solo_consultas($id_consulta);
        $ficha_medica = $this->ficha_medicaM->lista_ficha_medica_id($datos[0]['sa_fice_id']);
        $paciente = $this->pacientesM->obtener_informacion_pacienteM($ficha_medica[0]['sa_fice_pac_id']);

        //Consulta 
        $sa_conp_id = $datos[0]['sa_conp_id'];
        $sa_fice_id = $datos[0]['sa_fice_id'];
        $sa_conp_nivel = $datos[0]['sa_conp_nivel'];
        $sa_conp_paralelo = $datos[0]['sa_conp_paralelo'];
        $sa_conp_edad = $datos[0]['sa_conp_edad'];
        $sa_conp_peso = $datos[0]['sa_conp_peso'];
        $sa_conp_altura = $datos[0]['sa_conp_altura'];
        $sa_conp_temperatura = $datos[0]['sa_conp_temperatura'];
        $sa_conp_presion_ar = $datos[0]['sa_conp_presion_ar'];
        $sa_conp_frec_cardiaca = $datos[0]['sa_conp_frec_cardiaca'];
        $sa_conp_frec_respiratoria = $datos[0]['sa_conp_frec_respiratoria'];
        $sa_conp_saturacion = $datos[0]['sa_conp_saturacion'];


        $sa_conp_fecha_ingreso = $datos[0]['sa_conp_fecha_ingreso'];
        $sa_conp_fecha_ingreso = $sa_conp_fecha_ingreso;

        $sa_conp_desde_hora = $datos[0]['sa_conp_desde_hora'];
        if ($sa_conp_desde_hora !== null) {
            $sa_conp_desde_hora = preg_replace('/\.?0+$/', '', $sa_conp_desde_hora);
        }

        $sa_conp_hasta_hora = $datos[0]['sa_conp_hasta_hora'];
        if ($sa_conp_hasta_hora !== null) {
            $sa_conp_hasta_hora = preg_replace('/\.?0+$/', '', $sa_conp_hasta_hora);
        }

        $sa_conp_tiempo_aten = $datos[0]['sa_conp_tiempo_aten'];

        $sa_conp_CIE_10_1 = $datos[0]['sa_conp_CIE_10_1'];
        $sa_conp_diagnostico_1 = $datos[0]['sa_conp_diagnostico_1'];
        $sa_conp_CIE_10_2 = $datos[0]['sa_conp_CIE_10_2'];
        $sa_conp_diagnostico_2 = $datos[0]['sa_conp_diagnostico_2'];

        $sa_conp_salud_certificado = $datos[0]['sa_conp_salud_certificado'];
        $sa_conp_motivo_certificado = $datos[0]['sa_conp_motivo_certificado'];
        $sa_conp_CIE_10_certificado = $datos[0]['sa_conp_CIE_10_certificado'];
        $sa_conp_diagnostico_certificado = $datos[0]['sa_conp_diagnostico_certificado'];

        $sa_conp_fecha_entrega_certificado = $datos[0]['sa_conp_fecha_entrega_certificado'];
        if ($sa_conp_fecha_entrega_certificado !== null) {
            $sa_conp_fecha_entrega_certificado = $sa_conp_fecha_entrega_certificado;
        }

        $sa_conp_fecha_inicio_falta_certificado = $datos[0]['sa_conp_fecha_inicio_falta_certificado'];
        if ($sa_conp_fecha_inicio_falta_certificado !== null) {
            $sa_conp_fecha_inicio_falta_certificado = $sa_conp_fecha_inicio_falta_certificado;
        }

        $sa_conp_fecha_fin_alta_certificado = $datos[0]['sa_conp_fecha_fin_alta_certificado'];
        if ($sa_conp_fecha_fin_alta_certificado !== null) {
            $sa_conp_fecha_fin_alta_certificado = $sa_conp_fecha_fin_alta_certificado;
        }

        $sa_conp_dias_permiso_certificado = $datos[0]['sa_conp_dias_permiso_certificado'];

        $sa_conp_permiso_salida = $datos[0]['sa_conp_permiso_salida'];

        $sa_conp_fecha_permiso_salud_salida = $datos[0]['sa_conp_fecha_permiso_salud_salida'];
        if ($sa_conp_fecha_permiso_salud_salida !== null) {
            $sa_conp_fecha_permiso_salud_salida = $sa_conp_fecha_permiso_salud_salida;
        }

        $sa_conp_hora_permiso_salida = $datos[0]['sa_conp_hora_permiso_salida'];
        if ($sa_conp_hora_permiso_salida !== null) {
            $sa_conp_hora_permiso_salida = preg_replace('/\.?0+$/', '', $sa_conp_hora_permiso_salida);
        }

        $sa_conp_permiso_tipo = $datos[0]['sa_conp_permiso_tipo'];
        $sa_conp_permiso_seguro_traslado = $datos[0]['sa_conp_permiso_seguro_traslado'];
        $sa_conp_permiso_telefono_padre = $datos[0]['sa_conp_permiso_telefono_padre'];
        $sa_conp_permiso_telefono_seguro = $datos[0]['sa_conp_permiso_telefono_seguro'];

        $sa_conp_motivo_consulta = $datos[0]['sa_conp_motivo_consulta'];
        $sa_conp_observaciones = $datos[0]['sa_conp_observaciones'];
        $sa_conp_tratamiento = $datos[0]['sa_conp_tratamiento'];
        $sa_conp_tipo_consulta = $datos[0]['sa_conp_tipo_consulta'];

        $sa_examen_fisico_regional = $datos[0]['sa_examen_fisico_regional'];
        $sa_conp_condicion_alta = $datos[0]['sa_conp_condicion_alta'];


        //Ficha medica
        $sa_fice_pac_grupo_sangre = $ficha_medica[0]['sa_fice_pac_grupo_sangre'];
        $sa_fice_pac_direccion_domicilio = $ficha_medica[0]['sa_fice_pac_direccion_domicilio'];

        $sa_fice_rep_1_primer_apellido = $ficha_medica[0]['sa_fice_rep_1_primer_apellido'];
        $sa_fice_rep_1_segundo_apellido = $ficha_medica[0]['sa_fice_rep_1_segundo_apellido'];
        $sa_fice_rep_1_primer_nombre = $ficha_medica[0]['sa_fice_rep_1_primer_nombre'];
        $sa_fice_rep_1_segundo_nombre = $ficha_medica[0]['sa_fice_rep_1_segundo_nombre'];
        $sa_fice_rep_1_completo = $sa_fice_rep_1_primer_apellido . ' ' . $sa_fice_rep_1_segundo_apellido . ' ' . $sa_fice_rep_1_primer_nombre . ' ' . $sa_fice_rep_1_segundo_nombre;
        $sa_fice_rep_1_parentesco = $ficha_medica[0]['sa_fice_rep_1_parentesco'];
        $sa_fice_rep_1_telefono_1 = $ficha_medica[0]['sa_fice_rep_1_telefono_1'];
        $sa_fice_rep_1_telefono_2 = $ficha_medica[0]['sa_fice_rep_1_telefono_2'];

        $sa_fice_rep_2_primer_apellido = $ficha_medica[0]['sa_fice_rep_2_primer_apellido'];
        $sa_fice_rep_2_segundo_apellido = $ficha_medica[0]['sa_fice_rep_2_segundo_apellido'];
        $sa_fice_rep_2_primer_nombre = $ficha_medica[0]['sa_fice_rep_2_primer_nombre'];
        $sa_fice_rep_2_segundo_nombre = $ficha_medica[0]['sa_fice_rep_2_segundo_nombre'];
        $sa_fice_rep_2_completo = $sa_fice_rep_2_primer_apellido . ' ' . $sa_fice_rep_2_segundo_apellido . ' ' . $sa_fice_rep_2_primer_nombre . ' ' . $sa_fice_rep_2_segundo_nombre;

        $sa_fice_rep_2_parentesco = $ficha_medica[0]['sa_fice_rep_2_parentesco'];
        $sa_fice_rep_2_telefono_1 = $ficha_medica[0]['sa_fice_rep_2_telefono_1'];
        $sa_fice_rep_2_telefono_2 = $ficha_medica[0]['sa_fice_rep_2_telefono_2'];

        $sa_fice_pregunta_1_obs = $ficha_medica[0]['sa_fice_pregunta_1_obs'];
        $sa_fice_pregunta_2_obs = $ficha_medica[0]['sa_fice_pregunta_2_obs'];
        $sa_fice_pregunta_3_obs = $ficha_medica[0]['sa_fice_pregunta_3_obs'];
        $sa_fice_pregunta_4_obs = $ficha_medica[0]['sa_fice_pregunta_4_obs'];
        $sa_fice_pregunta_5_obs = $ficha_medica[0]['sa_fice_pregunta_5_obs'];

        //Pacientes
        $sa_pac_temp_cedula = $paciente[0]['sa_pac_temp_cedula'];
        $sa_pac_temp_primer_nombre = $paciente[0]['sa_pac_temp_primer_nombre'];
        $sa_pac_temp_segundo_nombre = $paciente[0]['sa_pac_temp_segundo_nombre'];
        $sa_pac_temp_primer_apellido = $paciente[0]['sa_pac_temp_primer_apellido'];
        $sa_pac_temp_segundo_apellido = $paciente[0]['sa_pac_temp_segundo_apellido'];
        $sa_pac_tabla = $paciente[0]['sa_pac_tabla'];
        $sa_pac_id_comunidad = $paciente[0]['sa_pac_id_comunidad'];

        //$sa_pac_temp_fecha_nacimiento = $paciente[0]['sa_pac_temp_fecha_nacimiento'];

        $sa_pac_temp_fecha_nacimiento = $paciente[0]['sa_pac_temp_fecha_nacimiento'];
        if ($sa_pac_temp_fecha_nacimiento !== null) {
            $sa_pac_temp_fecha_nacimiento = $sa_pac_temp_fecha_nacimiento;
        }

        $sa_pac_temp_correo = $paciente[0]['sa_pac_temp_correo'];

        // print_r($parametros);die();
        $id = $this->cod_global->id_tabla($sa_pac_tabla);
        $datos_1 = $this->seguros->lista_articulos_seguro_detalle($sa_pac_tabla, $sa_pac_id_comunidad, $_SESSION['INICIO']['MODULO_SISTEMA'], $id[0]['ID'], false, $sa_conp_permiso_seguro_traslado);

        $nombre_seguro = '';
        if (!empty($datos_1)) {
            $nombre_seguro = ($datos_1[0]['plan_seguro']);
        }

        //Usuario
        $usuario = $this->usuariosM->lista_usuarios($datos[0]['sa_conp_usu_id']);
        $nombre_medico = $usuario;


        //exit();

        $logo = '../../assets/images/favicon-32x32.png';
        if (($_SESSION['INICIO']['LOGO']) == '.' || $_SESSION['INICIO']['LOGO'] == '' || $_SESSION['INICIO']['LOGO'] == null) {
            $logo;
        } else {
            $logo = '../' . $_SESSION['INICIO']['LOGO'];
        }



        $pdf = new FPDF('P', 'mm', 'A4');

        $pdf->AddPage();

        $pdf->Image($logo, 15, 10, 30, 30);

        $pdf->Cell(40, 10, utf8_decode(''), 'L T', 0);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(90, 10, utf8_decode('UNIDAD EDUCATIVA SAINT DOMINIC'), 1, 0, 'C');
        $pdf->Cell(20, 10, utf8_decode('Código:'), 1, 0, 'R');
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(40, 10, utf8_decode('AM - ' . $sa_conp_id), 1, 1, 'C');


        $pdf->Cell(40, 10, utf8_decode(''), 'L', 0);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(90, 10, utf8_decode('DEPARTAMENTO MÉDICO'), 1, 0, 'C');
        $pdf->Cell(20, 10, utf8_decode('Versión:'), 1, 0, 'R');
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(40, 10, utf8_decode('1.0'), 1, 1, 'C');

        $pdf->Cell(40, 10, utf8_decode(''), 'L B', 0, 'C');
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(90, 10, utf8_decode('FORMULARIO - ' . strtoupper($sa_conp_tipo_consulta)), 1, 0, 'C');
        $pdf->Cell(20, 10, utf8_decode('Página:'), 1, 0, 'R');
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(40, 10, utf8_decode('1 de 1'), 1, 1, 'C');

        $pdf->ln('8');

        /////////////////////////////////////////////////////////////////////////////////////////////
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(190, 10, utf8_decode('  DATOS PERSONALES DEL USUARIO / PACIENTE'), 1, 1, 'L');

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(22, 7, utf8_decode('CÉDULA'), 1, 0, 'C');
        $pdf->Cell(42, 7, utf8_decode('PRIMER APELLIDO'), 1, 0, 'C');
        $pdf->Cell(42, 7, utf8_decode('SEGUNDO APELLIDO'), 1, 0, 'C');
        $pdf->Cell(42, 7, utf8_decode('PRIMER NOMBRE'), 1, 0, 'C');
        $pdf->Cell(42, 7, utf8_decode('SEGUNDO NOMBRE'), 1, 1, 'C');

        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(22, 8, utf8_decode($sa_pac_temp_cedula), 1, 0, 'C');
        $pdf->Cell(42, 8, utf8_decode($sa_pac_temp_primer_apellido), 1, 0, 'C');
        $pdf->Cell(42, 8, utf8_decode($sa_pac_temp_segundo_apellido), 1, 0, 'C');
        $pdf->Cell(42, 8, utf8_decode($sa_pac_temp_primer_nombre), 1, 0, 'C');
        $pdf->Cell(42, 8, utf8_decode($sa_pac_temp_segundo_nombre), 1, 1, 'C');

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(70, 7, utf8_decode('FECHA DE NACIMIENTO'), 1, 0, 'C');
        $pdf->Cell(20, 7, utf8_decode('EDAD'), 1, 0, 'C');
        $pdf->Cell(100, 7, utf8_decode('CORREO'), 1, 1, 'C');

        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(70, 8, ($sa_pac_temp_fecha_nacimiento), 1, 0, 'C');
        $pdf->Cell(20, 8, utf8_decode($sa_conp_edad . ' años'), 1, 0, 'C');
        $pdf->Cell(100, 8, utf8_decode($sa_pac_temp_correo), 1, 1, 'C');

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(50, 7, utf8_decode('GRUPO SANGUÍNEO'), 1, 0, 'C');
        $pdf->Cell(140, 7, utf8_decode('DIRECCIÓN'), 1, 1, 'C');

        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(50, 8, utf8_decode($sa_fice_pac_grupo_sangre), 1, 0, 'C');
        $pdf->Cell(140, 8, utf8_decode($sa_fice_pac_direccion_domicilio), 1, 1, 'C');

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(47.5, 7, utf8_decode('FECHA DE INGRESO'), 1, 0, 'C');
        $pdf->Cell(47.5, 7, utf8_decode('HORA DE ATENCIÓN'), 1, 0, 'C');
        $pdf->Cell(47.5, 7, utf8_decode('HORA FIN DE ATENCIÓN'), 1, 0, 'C');
        $pdf->Cell(47.5, 7, utf8_decode('TIEMPO DE ATENCIÓN'), 1, 1, 'C');

        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(47.5, 8, ($sa_conp_fecha_ingreso), 1, 0, 'C');
        $pdf->Cell(47.5, 8, ($sa_conp_desde_hora), 1, 0, 'C');
        $pdf->Cell(47.5, 8, ($sa_conp_hasta_hora), 1, 0, 'C');
        $pdf->Cell(47.5, 8, ($sa_conp_tiempo_aten), 1, 1, 'C');


        /////////////////////////////////////////////////////////////////////////////////////////////
        $pdf->ln('4');

        /////////////////////////////////////////////////////////////////////////////////////////////
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(190, 10, utf8_decode('  DATOS DE CONTACTO'), 1, 1, 'L');

        if ($sa_pac_tabla == 'estudiantes') {
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(70, 7, utf8_decode('EN CASO NECESARIO LLAMAR A:'), 1, 0, 'C');
            $pdf->Cell(40, 7, utf8_decode('PARENTESCO'), 1, 0, 'C');
            $pdf->Cell(40, 7, utf8_decode('TELÉFONO 1'), 1, 0, 'C');
            $pdf->Cell(40, 7, utf8_decode('TELÉFONO 2'), 1, 1, 'C');

            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(70, 8, utf8_decode($sa_fice_rep_1_completo), 1, 0, 'C');
            $pdf->Cell(40, 8, utf8_decode($sa_fice_rep_1_parentesco), 1, 0, 'C');
            $pdf->Cell(40, 8, utf8_decode($sa_fice_rep_1_telefono_1), 1, 0, 'C');
            $pdf->Cell(40, 8, utf8_decode($sa_fice_rep_1_telefono_2), 1, 1, 'C');
        }

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(70, 7, utf8_decode('EN CASO NECESARIO LLAMAR A:'), 1, 0, 'C');
        $pdf->Cell(40, 7, utf8_decode('PARENTESCO'), 1, 0, 'C');
        $pdf->Cell(40, 7, utf8_decode('TELÉFONO 1'), 1, 0, 'C');
        $pdf->Cell(40, 7, utf8_decode('TELÉFONO 2'), 1, 1, 'C');



        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(70, 8, utf8_decode($sa_fice_rep_2_completo), 1, 0, 'C');
        $pdf->Cell(40, 8, utf8_decode($sa_fice_rep_2_parentesco), 1, 0, 'C');
        $pdf->Cell(40, 8, utf8_decode($sa_fice_rep_2_telefono_1), 1, 0, 'C');
        $pdf->Cell(40, 8, utf8_decode($sa_fice_rep_2_telefono_2), 1, 1, 'C');



        /////////////////////////////////////////////////////////////////////////////////////////////
        $pdf->ln('4');

        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(190, 10, utf8_decode('  DIAGNÓSTICOS'), 1, 1, 'L');

        if ($sa_conp_tipo_consulta == 'consulta') {
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(32, 7, utf8_decode('DIAGNÓSTICO 1: '), 1, 0, 'C');
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(158, 7, utf8_decode($sa_conp_diagnostico_1), 1, 1, 'C');

            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(32, 7, utf8_decode('DIAGNÓSTICO 2: '), 1, 0, 'C');
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(158, 7, utf8_decode($sa_conp_diagnostico_2), 1, 1, 'C');
        } else {
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(32, 7, utf8_decode('DIAGNÓSTICO: '), 1, 0, 'C');
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(158, 7, utf8_decode($sa_conp_diagnostico_certificado), 1, 1, 'C');

            //DATOS DEL CERTIFICADO

            //$sa_conp_salud_certificado
            //$sa_conp_motivo_certificado

            $pdf->ln('4');

            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(47.5, 7, utf8_decode('FECHA DE ENTREGA'), 1, 0, 'C');
            $pdf->Cell(47.5, 7, utf8_decode('FECHA INICIO'), 1, 0, 'C');
            $pdf->Cell(47.5, 7, utf8_decode('FECHA FIN'), 1, 0, 'C');
            $pdf->Cell(47.5, 7, utf8_decode('DÍAS DE PERMISO'), 1, 1, 'C');

            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(47.5, 8, ($sa_conp_fecha_entrega_certificado), 1, 0, 'C');
            $pdf->Cell(47.5, 8, ($sa_conp_fecha_inicio_falta_certificado), 1, 0, 'C');
            $pdf->Cell(47.5, 8, ($sa_conp_fecha_fin_alta_certificado), 1, 0, 'C');
            $pdf->Cell(47.5, 8, ($sa_conp_dias_permiso_certificado), 1, 1, 'C');

            $pdf->ln('4');

            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(0, 7, utf8_decode('SALUD CERTIFICADO'), 1, 1, 'L');
            $pdf->SetFont('Arial', '', 9);
            $pdf->MultiCell(0, 5, utf8_decode($sa_conp_salud_certificado), 1, 'L');

            $pdf->ln('4');
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(0, 7, utf8_decode('MOTIVO DEL CERTIFICADO'), 1, 1, 'l');
            $pdf->SetFont('Arial', '', 9);
            $pdf->MultiCell(0, 5, utf8_decode($sa_conp_motivo_certificado), 1, 'L');
        }



        /////////////////////////////////////////////////////////////////////////////////////////////
        $pdf->ln('4');

        if ($sa_conp_tipo_consulta == 'consulta') {
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->Cell(190, 10, utf8_decode('  CONSTANTES VITALES'), 1, 1, 'L');

            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(28.2, 7, utf8_decode('TEMPERATURA'), 1, 0, 'C');
            $pdf->Cell(31, 7, utf8_decode('PRESIÓN ARTERIAL'), 1, 0, 'C');
            $pdf->Cell(20, 7, utf8_decode('PULOS / min'), 1, 0, 'C');
            $pdf->Cell(41.2, 7, utf8_decode('FRECIENCIA RESPITARORIA'), 1, 0, 'C');
            $pdf->Cell(20, 7, utf8_decode('PESO (kg)'), 1, 0, 'C');
            $pdf->Cell(20, 7, utf8_decode('TALLA (m)'), 1, 0, 'C');
            $pdf->Cell(29.6, 7, utf8_decode('SARURACIÓN'), 1, 1, 'C');

            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(28.2, 8, utf8_decode($sa_conp_temperatura), 1, 0, 'C');
            $pdf->Cell(31, 8, utf8_decode($sa_conp_presion_ar), 1, 0, 'C');
            $pdf->Cell(20, 8, utf8_decode($sa_conp_frec_cardiaca), 1, 0, 'C');
            $pdf->Cell(41.2, 8, utf8_decode($sa_conp_frec_respiratoria), 1, 0, 'C');
            $pdf->Cell(20, 8, utf8_decode($sa_conp_peso), 1, 0, 'C');
            $pdf->Cell(20, 8, utf8_decode($sa_conp_altura), 1, 0, 'C');
            $pdf->Cell(29.6, 8, utf8_decode($sa_conp_saturacion), 1, 1, 'C');
        }
        /////////////////////////////////////////////////////////////////////////////////////////////
        $pdf->ln('4');

        if ($sa_conp_permiso_salida == 'SI' && $sa_conp_permiso_tipo == 'emergencia') {
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->Cell(190, 10, utf8_decode('  REFERENCIA'), 1, 1, 'L');

            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(160, 7, utf8_decode('PACIENTE REFERIDO A:'), 1, 0, 'C');
            $pdf->Cell(30, 7, utf8_decode('TELÉFONO'), 1, 1, 'C');

            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(160, 8, utf8_decode($nombre_seguro), 1, 0, 'C');
            $pdf->Cell(30, 8, utf8_decode($sa_conp_permiso_telefono_seguro), 1, 1, 'C');

            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(47.5, 7, utf8_decode('TELÉFONO RESPONSABLE:'), 1, 0, 'C');
            $pdf->Cell(47.5, 7, utf8_decode('FECHA DE SALIDA:'), 1, 0, 'C');
            $pdf->Cell(47.5, 7, utf8_decode('HORA DE SALIDA'), 1, 0, 'C');
            $pdf->Cell(47.5, 7, utf8_decode('TIPO DE SALIDA'), 1, 1, 'C');

            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(47.5, 8, utf8_decode($sa_conp_permiso_telefono_padre), 1, 0, 'C');
            $pdf->Cell(47.5, 8, ($sa_conp_fecha_permiso_salud_salida), 1, 0, 'C');
            $pdf->Cell(47.5, 8, ($sa_conp_hora_permiso_salida), 1, 0, 'C');
            $pdf->Cell(47.5, 8, utf8_decode(strtoupper($sa_conp_permiso_tipo)), 1, 1, 'C');
        } else if ($sa_conp_permiso_salida == 'SI' && $sa_conp_permiso_tipo == 'normal') {
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->Cell(190, 10, utf8_decode('  DATOS DE SALIDA'), 1, 1, 'L');

            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(63.33, 7, utf8_decode('FECHA DE SALIDA:'), 1, 0, 'C');
            $pdf->Cell(63.33, 7, utf8_decode('HORA DE SALIDA'), 1, 0, 'C');
            $pdf->Cell(63.33, 7, utf8_decode('TIPO DE SALIDA'), 1, 1, 'C');

            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(63.33, 8, ($sa_conp_fecha_permiso_salud_salida), 1, 0, 'C');
            $pdf->Cell(63.33, 8, ($sa_conp_hora_permiso_salida), 1, 0, 'C');
            $pdf->Cell(63.33, 8, utf8_decode(strtoupper($sa_conp_permiso_tipo)), 1, 1, 'C');
        }



        /////////////////////////////////////////////////////////////////////////////////////////////
        //Ficha medica Ecamen fisico regional
        /////////////////////////////////////////////////////////////////////////////////////////////
        $pdf->AddPage();

        if ($sa_conp_tipo_consulta == 'consulta') {

            $jsonArray = json_decode($sa_examen_fisico_regional, true);

            $numValoresPiel = count($jsonArray['Piel']);
            $numValoresOjos = count($jsonArray['Ojos']);
            $numValoresOido = count($jsonArray['Oído']);
            $numValoresOroFaringe = count($jsonArray['Oro_faringe']);
            $numValoresNariz = count($jsonArray['Nariz']);
            $numValoresCuello = count($jsonArray['Cuello']);
            $numValoresTorax1 = count($jsonArray['Torax_1']);
            $numValoresTorax2 = count($jsonArray['Torax_2']);
            $numValoresAbdomen = count($jsonArray['Abdomen']);
            $numValoresColumna = count($jsonArray['Columna']);
            $numValoresPelvis = count($jsonArray['Pelvis']);
            $numValoresExtremidades = count($jsonArray['Extremidades']);
            $numValoresNeurologico = count($jsonArray['Neurológico']);

            if (
                $numValoresPiel > 0 ||
                $numValoresOjos > 0 ||
                $numValoresOido > 0 ||
                $numValoresOroFaringe > 0 ||
                $numValoresNariz > 0 ||
                $numValoresCuello > 0 ||
                $numValoresTorax1 > 0 ||
                $numValoresTorax2 > 0 ||
                $numValoresAbdomen > 0 ||
                $numValoresColumna > 0 ||
                $numValoresPelvis > 0 ||
                $numValoresExtremidades > 0 ||
                $numValoresNeurologico > 0
            ) {
                $pdf->ln('10');

                $pdf->SetFont('Arial', 'B', 12);
                $pdf->Cell(190, 10, utf8_decode('  EXAMEN FÍSICO REGIONAL'), 1, 1, 'L');

                $pdf->SetFont('Arial', '', 10);


                //190 63.33
                //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



                $pdf->SetFont('Arial', 'B', 12);



                //1 Piel
                $Piel_a = isset($jsonArray['Piel']['a']) ? 'X' : '';
                $Piel_b = isset($jsonArray['Piel']['b']) ? 'X' : '';
                $Piel_c = isset($jsonArray['Piel']['c']) ? 'X' : '';

                //2 Ojos
                $Ojos_a = isset($jsonArray['Ojos']['a']) ? 'X' : '';
                $Ojos_b = isset($jsonArray['Ojos']['b']) ? 'X' : '';
                $Ojos_c = isset($jsonArray['Ojos']['c']) ? 'X' : '';
                $Ojos_d = isset($jsonArray['Ojos']['d']) ? 'X' : '';
                $Ojos_e = isset($jsonArray['Ojos']['e']) ? 'X' : '';

                //3 Oído
                $Oido_a = isset($jsonArray['Oído']['a']) ? 'X' : '';
                $Oido_b = isset($jsonArray['Oído']['b']) ? 'X' : '';
                $Oido_c = isset($jsonArray['Oído']['c']) ? 'X' : '';

                //4 Oro_faringe
                $Oro_faringe_a = isset($jsonArray['Oro_faringe']['a']) ? 'X' : '';
                $Oro_faringe_b = isset($jsonArray['Oro_faringe']['b']) ? 'X' : '';
                $Oro_faringe_c = isset($jsonArray['Oro_faringe']['c']) ? 'X' : '';
                $Oro_faringe_d = isset($jsonArray['Oro_faringe']['d']) ? 'X' : '';
                $Oro_faringe_e = isset($jsonArray['Oro_faringe']['e']) ? 'X' : '';

                //5 Nariz
                $Nariz_a = isset($jsonArray['Nariz']['a']) ? 'X' : '';
                $Nariz_b = isset($jsonArray['Nariz']['b']) ? 'X' : '';
                $Nariz_c = isset($jsonArray['Nariz']['c']) ? 'X' : '';
                $Nariz_d = isset($jsonArray['Nariz']['d']) ? 'X' : '';

                //6 Cuello
                $Cuello_a = isset($jsonArray['Cuello']['a']) ? 'X' : '';
                $Cuello_b = isset($jsonArray['Cuello']['b']) ? 'X' : '';

                //7 Tórax
                $Torax_1_a = isset($jsonArray['Torax_1']['a']) ? 'X' : '';
                $Torax_1_b = isset($jsonArray['Torax_1']['b']) ? 'X' : '';

                //8 Tórax
                $Torax_2_a = isset($jsonArray['Torax_2']['a']) ? 'X' : '';
                $Torax_2_b = isset($jsonArray['Torax_2']['b']) ? 'X' : '';

                //9 Abdomen
                $Abdomen_a = isset($jsonArray['Abdomen']['a']) ? 'X' : '';
                $Abdomen_b = isset($jsonArray['Abdomen']['b']) ? 'X' : '';

                //10 Columna
                $Columna_a = isset($jsonArray['Columna']['a']) ? 'X' : '';
                $Columna_b = isset($jsonArray['Columna']['b']) ? 'X' : '';
                $Columna_c = isset($jsonArray['Columna']['c']) ? 'X' : '';

                //11 Pelvis
                $Pelvis_a = isset($jsonArray['Pelvis']['a']) ? 'X' : '';
                $Pelvis_b = isset($jsonArray['Pelvis']['b']) ? 'X' : '';

                //12 Extremidades
                $Extremidades_a = isset($jsonArray['Extremidades']['a']) ? 'X' : '';
                $Extremidades_b = isset($jsonArray['Extremidades']['b']) ? 'X' : '';
                $Extremidades_c = isset($jsonArray['Extremidades']['c']) ? 'X' : '';

                //13 Neurológico
                $Neurologico_a = isset($jsonArray['Neurológico']['a']) ? 'X' : '';
                $Neurologico_b = isset($jsonArray['Neurológico']['b']) ? 'X' : '';
                $Neurologico_c = isset($jsonArray['Neurológico']['c']) ? 'X' : '';
                $Neurologico_d = isset($jsonArray['Neurológico']['d']) ? 'X' : '';


                $pdf->SetFont('Arial', 'B', 8);
                // Primera fila de celdas




                $pdf->SetFillColor(211, 228, 251);
                $pdf->Cell(6, 7, utf8_decode(''), 'LT', 0, 'L', true);
                $pdf->SetFillColor(211, 228, 251);
                $pdf->Cell(35.5, 7, utf8_decode('a. Cicatrices'), 1, 0, 'L', true);
                $pdf->Cell(6, 7, utf8_decode($Piel_a), 1, 0, 'L');

                $pdf->SetFillColor(211, 228, 251);
                $pdf->Cell(6, 7, utf8_decode(''), 'LT', 0, 'L', true);
                $pdf->SetFillColor(211, 228, 251);
                $pdf->Cell(35.5, 7, utf8_decode('a. Labios'), 1, 0, 'L', true);
                $pdf->Cell(6, 7, utf8_decode($Oro_faringe_a), 1, 0, 'L');

                $pdf->Cell(6, 7, utf8_decode(''), 'LT', 0, 'L', true);
                $pdf->Cell(35.5, 7, utf8_decode('a. Mamas'), 1, 0, 'L', true);
                $pdf->Cell(6, 7, utf8_decode($Torax_1_a), 1, 0, 'L');

                $pdf->Cell(6, 7, utf8_decode(''), 'LT', 0, 'L', true);
                $pdf->Cell(35.5, 7, utf8_decode('a. Vascular'), 1, 0, 'L', true);
                $pdf->Cell(6, 7, utf8_decode($Extremidades_a), 1, 1, 'L');


                // Segunda fila de celdas
                $pdf->SetFillColor(211, 228, 251);
                $pdf->Cell(6, 7, utf8_decode(''), 'L', 0, 'L', true);
                $pdf->SetFillColor(211, 228, 251);
                $pdf->Cell(35.5, 7, utf8_decode('b. Tatuajes'), 1, 0, 'L', true);
                $pdf->Cell(6, 7, utf8_decode($Piel_b), 1, 0, 'L');

                $pdf->Cell(6, 7, utf8_decode(''), 'L', 0, 'L', true);
                $pdf->Cell(35.5, 7, utf8_decode('b. Lengua'), 1, 0, 'L', true);
                $pdf->Cell(6, 7, utf8_decode($Oro_faringe_b), 1, 0, 'L');

                $pdf->Cell(6, 7, utf8_decode(''), 'L', 0, 'L', true);
                $pdf->Cell(35.5, 7, utf8_decode('b. Corazón'), 1, 0, 'L', true);
                $pdf->Cell(6, 7, utf8_decode($Torax_1_b), 1, 0, 'L');

                $pdf->Cell(6, 7, utf8_decode(''), 'L', 0, 'L', true);
                $pdf->Cell(35.5, 7, utf8_decode('b. Miembros superiores'), 1, 0, 'L', true);
                $pdf->Cell(6, 7, utf8_decode($Extremidades_b), 1, 1, 'L');


                // Tercera fila de celdas
                $pdf->SetFillColor(211, 228, 251);
                $pdf->Cell(6, 7, utf8_decode(''), 'L', 0, 'L', true);
                $pdf->SetFillColor(211, 228, 251);
                $pdf->Cell(35.5, 7, utf8_decode('c. Piel y Faneras'), 1, 0, 'L', true);
                $pdf->Cell(6, 7, utf8_decode($Piel_c), 1, 0, 'L');

                $pdf->Cell(6, 7, utf8_decode(''), 'L', 0, 'L', true);
                $pdf->Cell(35.5, 7, utf8_decode('c. Faringe'), 1, 0, 'L', true);
                $pdf->Cell(6, 7, utf8_decode($Oro_faringe_c), 1, 0, 'L');

                $pdf->Cell(6, 7, utf8_decode(''), 'LT', 0, 'L', true);
                $pdf->Cell(35.5, 7, utf8_decode('a. Pulmones'), 1, 0, 'L', true);
                $pdf->Cell(6, 7, utf8_decode($Torax_2_a), 1, 0, 'L');

                $pdf->Cell(6, 7, utf8_decode(''), 'L', 0, 'L', true);
                $pdf->Cell(35.5, 7, utf8_decode('c. Miembros inferiores'), 1, 0, 'L', true);
                $pdf->Cell(6, 7, utf8_decode($Extremidades_c), 1, 1, 'L');


                // Cuarta fila de celdas
                $pdf->Cell(6, 7, utf8_decode(''), 'LT', 0, 'L', true);
                $pdf->Cell(35.5, 7, utf8_decode('a. Párpados'), 1, 0, 'L', true);
                $pdf->Cell(6, 7, utf8_decode($Ojos_a), 1, 0, 'L');

                $pdf->Cell(6, 7, utf8_decode(''), 'L', 0, 'L', true);
                $pdf->Cell(35.5, 7, utf8_decode('d. Amígdalas'), 1, 0, 'L', true);
                $pdf->Cell(6, 7, utf8_decode($Oro_faringe_d), 1, 0, 'L');

                $pdf->Cell(6, 7, utf8_decode(''), 'L', 0, 'L', true);
                $pdf->Cell(35.5, 7, utf8_decode('b. Parrilla Costal'), 1, 0, 'L', true);
                $pdf->Cell(6, 7, utf8_decode($Torax_2_b), 1, 0, 'L');

                $pdf->Cell(6, 7, utf8_decode(''), 'LT', 0, 'L', true);
                $pdf->Cell(35.5, 7, utf8_decode('a. Fuerza'), 1, 0, 'L', true);
                $pdf->Cell(6, 7, utf8_decode($Neurologico_a), 1, 1, 'L');


                // Quinta fila de celdas
                $pdf->Cell(6, 7, utf8_decode(''), 'L', 0, 'L', true);
                $pdf->Cell(35.5, 7, utf8_decode('b. Conjuntivas'), 1, 0, 'L', true);
                $pdf->Cell(6, 7, utf8_decode($Ojos_b), 1, 0, 'L');

                $pdf->Cell(6, 7, utf8_decode(''), 'L', 0, 'L', true);
                $pdf->Cell(35.5, 7, utf8_decode('e. Dentadura'), 1, 0, 'L', true);
                $pdf->Cell(6, 7, utf8_decode($Oro_faringe_e), 1, 0, 'L');

                $pdf->Cell(6, 7, utf8_decode(''), 'LT', 0, 'L', true);
                $pdf->Cell(35.5, 7, utf8_decode('a. Vísceras'), 1, 0, 'L', true);
                $pdf->Cell(6, 7, utf8_decode($Abdomen_a), 1, 0, 'L');

                $pdf->Cell(6, 7, utf8_decode(''), 'L', 0, 'L', true);
                $pdf->Cell(35.5, 7, utf8_decode('b. Sensibilidad'), 1, 0, 'L', true);
                $pdf->Cell(6, 7, utf8_decode($Neurologico_b), 1, 1, 'L');


                // Sexta fila de celdas
                $pdf->Cell(6, 7, utf8_decode(''), 'L', 0, 'L', true);
                $pdf->Cell(35.5, 7, utf8_decode('c. Pupilas'), 1, 0, 'L', true);
                $pdf->Cell(6, 7, utf8_decode($Ojos_c), 1, 0, 'L');

                $pdf->Cell(6, 7, utf8_decode(''), 'LT', 0, 'L', true);
                $pdf->Cell(35.5, 7, utf8_decode('a. Tabique'), 1, 0, 'L', true);
                $pdf->Cell(6, 7, utf8_decode($Nariz_a), 1, 0, 'L');

                $pdf->Cell(6, 7, utf8_decode(''), 'L', 0, 'L', true);
                $pdf->Cell(35.5, 7, utf8_decode('b. Pared Abdominal'), 1, 0, 'L', true);
                $pdf->Cell(6, 7, utf8_decode($Abdomen_b), 1, 0, 'L');

                $pdf->Cell(6, 7, utf8_decode(''), 'L', 0, 'L', true);
                $pdf->Cell(35.5, 7, utf8_decode('c. Marcha'), 1, 0, 'L', true);
                $pdf->Cell(6, 7, utf8_decode($Neurologico_c), 1, 1, 'L');


                // Septima fila de celdas
                $pdf->Cell(6, 7, utf8_decode(''), 'L', 0, 'L', true);
                $pdf->Cell(35.5, 7, utf8_decode('d. Córnea'), 1, 0, 'L', true);
                $pdf->Cell(6, 7, utf8_decode($Ojos_d), 1, 0, 'L');

                $pdf->Cell(6, 7, utf8_decode(''), 'L', 0, 'L', true);
                $pdf->Cell(35.5, 7, utf8_decode('b. Cornetes'), 1, 0, 'L', true);
                $pdf->Cell(6, 7, utf8_decode($Nariz_b), 1, 0, 'L');

                $pdf->Cell(6, 7, utf8_decode(''), 'LT', 0, 'L', true);
                $pdf->Cell(35.5, 7, utf8_decode('a. Flexibilidad'), 1, 0, 'L', true);
                $pdf->Cell(6, 7, utf8_decode($Columna_a), 1, 0, 'L');

                $pdf->Cell(6, 7, utf8_decode(''), 'LB', 0, 'L', true);
                $pdf->Cell(35.5, 7, utf8_decode('d. Reflejos'), 1, 0, 'L', true);
                $pdf->Cell(6, 7, utf8_decode($Neurologico_d), 1, 1, 'L');


                // Octava fila de celdas
                $pdf->Cell(6, 7, utf8_decode(''), 'L', 0, 'L', true);
                $pdf->Cell(35.5, 7, utf8_decode('e. Motilidad'), 1, 0, 'L', true);
                $pdf->Cell(6, 7, utf8_decode($Ojos_e), 1, 0, 'L');

                $pdf->Cell(6, 7, utf8_decode(''), 'L', 0, 'L', true);
                $pdf->Cell(35.5, 7, utf8_decode('c. Mucosas'), 1, 0, 'L', true);
                $pdf->Cell(6, 7, utf8_decode($Nariz_c), 1, 0, 'L');

                $pdf->Cell(6, 7, utf8_decode(''), 'L', 0, 'L', true);
                $pdf->Cell(35.5, 7, utf8_decode('b. Desviación'), 1, 0, 'L', true);
                $pdf->Cell(6, 7, utf8_decode($Columna_b), 1, 1, 'L');

                // Novena fila de celdas
                $pdf->Cell(6, 7, utf8_decode(''), 'LT', 0, 'L', true);
                $pdf->Cell(35.5, 7, utf8_decode('a. C. auditivo externo'), 1, 0, 'L', true);
                $pdf->Cell(6, 7, utf8_decode($Oido_a), 1, 0, 'L');

                $pdf->Cell(6, 7, utf8_decode(''), 'L', 0, 'L', true);
                $pdf->Cell(35.5, 7, utf8_decode('d. Senos paranasales'), 1, 0, 'L', true);
                $pdf->Cell(6, 7, utf8_decode($Nariz_d), 1, 0, 'L');

                $pdf->Cell(6, 7, utf8_decode(''), 'L', 0, 'L', true);
                $pdf->Cell(35.5, 7, utf8_decode('c. Dolor'), 1, 0, 'L', true);
                $pdf->Cell(6, 7, utf8_decode($Columna_c), 1, 1, 'L');


                // Decima fila de celdas
                $pdf->Cell(6, 7, utf8_decode(''), 'L', 0, 'L', true);
                $pdf->Cell(35.5, 7, utf8_decode('b. Pabellón'), 1, 0, 'L', true);
                $pdf->Cell(6, 7, utf8_decode($Oido_b), 1, 0, 'L');

                $pdf->Cell(6, 7, utf8_decode(''), 'LT', 0, 'L', true);
                $pdf->Cell(35.5, 7, utf8_decode('a. Tiroides/masas'), 1, 0, 'L', true);
                $pdf->Cell(6, 7, utf8_decode($Cuello_a), 1, 0, 'L');

                $pdf->Cell(6, 7, utf8_decode(''), 'LT', 0, 'L', true);
                $pdf->Cell(35.5, 7, utf8_decode('a. Pelvis'), 1, 0, 'L', true);
                $pdf->Cell(6, 7, utf8_decode($Pelvis_a), 1, 1, 'L');


                // Decimo primero fila de celdas
                $pdf->Cell(6, 7, utf8_decode(''), 'LB', 0, 'L', true);
                $pdf->Cell(35.5, 7, utf8_decode('c. Timpanos'), 1, 0, 'L', true);
                $pdf->Cell(6, 7, utf8_decode($Oido_c), 1, 0, 'L');

                $pdf->Cell(6, 7, utf8_decode(''), 'LB', 0, 'L', true);
                $pdf->Cell(35.5, 7, utf8_decode('b. Movilidad'), 1, 0, 'L', true);
                $pdf->Cell(6, 7, utf8_decode($Cuello_b), 1, 0, 'L');

                $pdf->Cell(6, 7, utf8_decode(''), 'LB', 0, 'L', true);
                $pdf->Cell(35.5, 7, utf8_decode('b. Genitales'), 1, 0, 'L', true);
                $pdf->Cell(6, 7, utf8_decode($Pelvis_b), 1, 1, 'L');

                //Para poner los textos en la tabla
                $pdf->RotatedText(14, 44, utf8_decode('1. Piel'), 90);
                $pdf->RotatedText(14, 72, utf8_decode('2. Ojos'), 90);
                $pdf->RotatedText(14, 101, utf8_decode('3. Oído'), 90);

                $pdf->RotatedText(61.5, 56, utf8_decode('4. Oro faringe'), 90);
                $pdf->RotatedText(61.5, 84, utf8_decode('5. Naríz'), 90);
                $pdf->RotatedText(61.5, 105.5, utf8_decode('6.Cuello'), 90);

                $pdf->RotatedText(109, 42, utf8_decode('7. Tórax'), 90);
                $pdf->RotatedText(109, 56.5, utf8_decode('8. Tórax'), 90);
                $pdf->SetFont('Arial', 'B', 7);

                $pdf->RotatedText(109, 71.7, utf8_decode('9.Abdomen'), 90);
                $pdf->SetFont('Arial', 'B', 8);

                $pdf->RotatedText(109, 91, utf8_decode('10. Columna'), 90);
                $pdf->RotatedText(109, 106.5, utf8_decode('11. Pelvis'), 90);

                $pdf->SetFont('Arial', 'B', 7);
                $pdf->RotatedText(156.5, 50.5, utf8_decode('12. Extremidades'), 90);

                $pdf->SetFont('Arial', 'B', 8);
                $pdf->RotatedText(156.5, 76, utf8_decode('13. Neurológico'), 90);


                //$pdf->ln('1');
                $pdf->SetFont('Arial', 'B', 12);
                $pdf->Cell(190, 10, utf8_decode('  OBSERVACIONES'), 1, 1, 'L');

                $pdf->SetFont('Arial', '', 10);
                $observaciones = isset($jsonArray['Observaciones']['sa_examen_fisico_regional_obs']) ? $jsonArray['Observaciones']['sa_examen_fisico_regional_obs'] : '';

                $pdf->MultiCell(190, 6, utf8_decode($observaciones), 1);
            }
        }



        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        //Obervaciones
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        $pdf->ln('10');

        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(190, 10, utf8_decode('  INFORMACIÓN ADICIONAL'), 1, 1, 'L');


        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(0, 7, utf8_decode('OBSERVACIONES'), 1, 1, 'l');
        $pdf->SetFont('Arial', '', 9);
        $pdf->MultiCell(0, 6, utf8_decode($sa_conp_observaciones), 1, 'L');

        if ($sa_conp_tipo_consulta == 'consulta') {
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(0, 7, utf8_decode('CONDICIÓN DE ALTA'), 1, 1, 'l');
            $pdf->SetFont('Arial', '', 9);
            $pdf->MultiCell(0, 6, utf8_decode($sa_conp_condicion_alta), 1, 'L');

            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(0, 7, utf8_decode('MOTIVO DE LA CONSULTA'), 1, 1, 'l');
            $pdf->SetFont('Arial', '', 9);
            $pdf->MultiCell(0, 6, utf8_decode($sa_conp_motivo_consulta), 1, 'L');

            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(0, 7, utf8_decode('TRATAMIENTO'), 1, 1, 'l');
            $pdf->SetFont('Arial', '', 9);
            $pdf->MultiCell(0, 6, utf8_decode($sa_conp_tratamiento), 1, 'L');
        }

        $pdf->ln('10');


        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(190, 10, utf8_decode('  FICHA MÉDICA'), 1, 1, 'L');
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(190, 7, utf8_decode('   1.- ¿Ha sido diagnosticado con alguna enfermedad?:'), 1, 1, 'L');
        $pdf->SetFont('Arial', '', 9);
        $pdf->MultiCell(190, 6, utf8_decode($sa_fice_pregunta_1_obs), 1);

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(190, 7, utf8_decode('   2.- ¿Tiene algún antecedente familiar de importancia?:'), 1, 1, 'L');
        $pdf->SetFont('Arial', '', 9);
        $pdf->MultiCell(190, 6, utf8_decode($sa_fice_pregunta_2_obs), 1);

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(190, 7, utf8_decode('   3.- ¿Ha sido sometido a cirugías previas?:'), 1, 1, 'L');
        $pdf->SetFont('Arial', '', 9);
        $pdf->MultiCell(190, 6, utf8_decode($sa_fice_pregunta_3_obs), 1);

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(190, 7, utf8_decode('   4.- ¿Tiene alergias?:'), 1, 1, 'L');
        $pdf->SetFont('Arial', '', 9);
        $pdf->MultiCell(190, 6, utf8_decode($sa_fice_pregunta_4_obs), 1);

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(190, 7, utf8_decode('   5.- ¿Qué medicamentos usa?:'), 1, 1, 'L');
        $pdf->SetFont('Arial', '', 9);
        $pdf->MultiCell(190, 6, utf8_decode($sa_fice_pregunta_5_obs), 1);

        /////////////////////////////////////////////////////////////////////////////////////////////



        $pdf->ln('8');

        $pdf->SetFont('Arial', '', 9);


        $nombre_medico_tipo = empty($nombre_medico[0]['tipo']) ? 'Vacio' : $nombre_medico[0]['tipo'];
        $nombre_medico_nombre = empty($nombre_medico[0]['nom']) ? 'Vacio' : $nombre_medico[0]['nom'];

        $tipo_usuario = '';
        if (strtoupper($nombre_medico_tipo) == 'MEDICO') {
            $tipo_usuario = 'Dra. ';
        } else {
            $tipo_usuario = '';
        }

        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(60, 10, utf8_decode($tipo_usuario . $nombre_medico_nombre), '0', 0, 'C');
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(60, 10, utf8_decode(''), '0', 1, 'R');

        $pdf->Cell(60, 3, utf8_decode('Médico Institucional'), '0', 0, 'C');

        //Footer
        $pdf->setY(271.9);
        $pdf->setX(30);
        $pdf->SetFont('Arial', 'I', 8);
        $pdf->Cell(148.5, 5, 'Desarrollado por Corsinf', 0, 0, 'C');
        $pdf->SetFont('Arial', 'B', 12);


        $pdf->Output();
    }

    function pdf_recetario_consulta_paciente($id_consulta)
    {
        $datos = $this->modelo->lista_solo_consultas($id_consulta);
        $ficha_medica = $this->ficha_medicaM->lista_ficha_medica_id($datos[0]['sa_fice_id']);
        $paciente = $this->pacientesM->obtener_informacion_pacienteM($ficha_medica[0]['sa_fice_pac_id']);
        $detalle_consulta = $this->det_consultaM->lista_det_consulta_consulta($id_consulta);
        $usuario = $this->usuariosM->lista_usuarios($datos[0]['sa_conp_usu_id']);
        $nombre_medico = $usuario;

        //Pacientes
        $sa_pac_temp_cedula = $paciente[0]['sa_pac_temp_cedula'];
        $sa_pac_temp_primer_nombre = $paciente[0]['sa_pac_temp_primer_nombre'];
        $sa_pac_temp_segundo_nombre = $paciente[0]['sa_pac_temp_segundo_nombre'];
        $sa_pac_temp_primer_apellido = $paciente[0]['sa_pac_temp_primer_apellido'];
        $sa_pac_temp_segundo_apellido = $paciente[0]['sa_pac_temp_segundo_apellido'];

        $nombre_completo = $sa_pac_temp_primer_apellido . ' ' .  $sa_pac_temp_segundo_apellido . ' ' .  $sa_pac_temp_primer_nombre . ' ' . $sa_pac_temp_segundo_nombre;

        $logo = '../../assets/images/favicon-32x32.png';
        if (($_SESSION['INICIO']['LOGO']) == '.' || $_SESSION['INICIO']['LOGO'] == '' || $_SESSION['INICIO']['LOGO'] == null) {
            $logo;
        } else {
            $logo = '../' . $_SESSION['INICIO']['LOGO'];
        }

        $pdf = new FPDF('L', 'mm', 'A4');
        $pdf->AddPage();

        $pdf->Image($logo, 12, 10, 20, 20);
        $pdf->Image($logo, 158.5, 10, 20, 20);

        $pdf->SetLeftMargin(10);
        $pdf->SetRightMargin(10);
        $pdf->SetTopMargin(10);
        $pdf->SetAutoPageBreak(true, 1);

        //Footer
        $pdf->setY(201);
        $pdf->setX(1);
        $pdf->SetFont('Arial', 'I', 8);
        $pdf->Cell(148.5, 5, 'Desarrollado por Corsinf', 0, 0, 'C');

        $pdf->SetFont('Arial', 'B', 14);

        $pdf->setY(201);
        $pdf->setX(148.5);
        $pdf->SetFont('Arial', 'I', 8);
        $pdf->Cell(148.5, 5, 'Desarrollado por Corsinf', 0, 0, 'C');

        $pdf->SetFont('Arial', 'B', 14);



        //210 x 297


        //x1 y1 x2 y2
        //105 148.5


        $pdf->Line(148.5, 0, 148.5, 210);

        $pdf->SetTextColor(57, 80, 122);
        $pdf->SetFont('Arial', 'B', 12);

        $pdf->setY(10);
        $pdf->setX(40);
        $pdf->Cell(90, 5, utf8_decode('UNIDAD EDUCATIVA SAINT DOMINIC'), 0, 1, 'C');

        $pdf->setY(15);
        $pdf->setX(40);
        $pdf->Cell(90, 5, utf8_decode('DEPARTAMENTO MÉDICO'), 0, 1, 'C');

        $pdf->setY(20);
        $pdf->setX(40);
        $pdf->Cell(90, 5, utf8_decode('RECETARIO'), 0, 1, 'C');

        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->setY(40);
        $pdf->setX(10);
        $pdf->Cell(18, 7, utf8_decode('Nombre: '), 0, 0, '');
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(90, 7, utf8_decode($nombre_completo), 0, 1, 'L');


        $tipo_usuario = '';
        $nombre_medico_tipo = empty($nombre_medico[0]['tipo']) ? 'Vacio' : $nombre_medico[0]['tipo'];
        $nombre_medico_nombre = empty($nombre_medico[0]['nom']) ? 'Vacio' : $nombre_medico[0]['nom'];

        if (strtoupper($nombre_medico_tipo) == 'MEDICO') {
            $tipo_usuario = 'Dra. ';
        } else {
            $tipo_usuario = '';
        }

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(27, 7, utf8_decode('Recetado por: '), 0, 0, '');
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(81, 7, utf8_decode($tipo_usuario . $nombre_medico_nombre), 0, 1, 'L');

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetTextColor(57, 80, 122);
        $pdf->Cell(90, 5, utf8_decode('Rp. '), 0, 1, 'L');

        $pdf->Ln(2);

        $medicamentos = array(
            array("1", "Medicamento 1", "1", "1 mes", "Tomar tres veces en el día"),
            array("2", "Medicamento 2", "2", "2 meses", "Tomar tres veces en el día"),
            array("3", "Medicamento 3", "2", "1 mes", "Tomar tres veces en el día"),
            array("4", "Medicamento 4", "2", "1 mes", "Tomar tres veces en el día"),
            array("5", "Medicamento 5", "3", "1 mes", "Tomar tres veces en el día"),
            array("6", "Medicamento 6", "1", "12 meses", "Tomar tres veces en el día"),
            array("7", "Medicamento 6", "1", "12 meses", "Tomar tres veces en el día"),
            array("8", "Medicamento 6", "1", "12 meses", "Tomar tres veces en el día"),
            array("9", "Medicamento 6", "1", "12 meses", "Tomar tres veces en el día"),
            array("10", "Medicamento 6", "1", "12 meses", "Tomar tres veces en el díaomar tres veces en el díaomar tres veces en el díaomar tres veces en el díaomar tres veces en el día"),

        );

        $pdf->SetTextColor(0, 0, 0);

        $pdf->Cell('8.5', 5, '#', 0, 0, 'C');
        $pdf->Cell('100', 5, utf8_decode('Famacología'), 0, 0, 'L');
        $pdf->Cell('20', 5, 'Cantidad', 0, 1, 'C');

        $pdf->SetFont('Arial', '', 9);
        $contador = 1;
        foreach ($detalle_consulta as $row) {
            $pdf->Cell('8.5', 5, utf8_decode($contador), 0, 0, 'C');
            $pdf->Cell('100', 5, utf8_decode($row['sa_det_conp_nombre']), 0, 0, 'L');
            $pdf->Cell('20', 5, utf8_decode($row['sa_det_conp_cantidad']), 0, 1, 'C');
            $contador++;
        }


        /*$pdf->Ln(25);

        //$pdf->setX(10);

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(128.5, 7, "FIRMA Y SELLO", 0, 1, 'C');
        $pdf->SetFont('Arial', '', 10);
        $pdf->Ln(25);
        $pdf->Cell(128.5, 7, "_________________________________________", 0, 1, 'C');
        $pdf->Cell(128.5, 7, "Nombre del Medico/Doctor", 0, 1, 'C');

        $pdf->Cell(128.5, 7, "Powered by Evilnapsis", 0, 1, 'C');*/


        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        //Inficaciones
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        $pdf->SetTextColor(57, 80, 122);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->setY(10);
        $pdf->setX(190);
        $pdf->Cell(90, 5, utf8_decode('UNIDAD EDUCATIVA SAINT DOMINIC'), 0, 1, 'C');

        $pdf->setY(15);
        $pdf->setX(190);
        $pdf->Cell(90, 5, utf8_decode('DEPARTAMENTO MÉDICO'), 0, 1, 'C');

        $pdf->setY(20);
        $pdf->setX(190);
        $pdf->Cell(90, 5, utf8_decode('RECETARIO'), 0, 1, 'C');

        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->setY(40);
        $pdf->setX(158.5);
        $pdf->Cell(18, 7, utf8_decode('Nombre: '), 0, 0, '');
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(158.5, 7, utf8_decode($nombre_completo), 0, 1, 'L');

        $pdf->setY(47);
        $pdf->setX(158.5);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(27, 7, utf8_decode('Recetado por: '), 0, 0, '');
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(81, 7, utf8_decode($tipo_usuario . $nombre_medico_nombre), 0, 1, 'L');

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetTextColor(57, 80, 122);
        $pdf->setY(54);
        $pdf->setX(158.5);
        $pdf->Cell(90, 5, utf8_decode('Indicaciones. '), 0, 1, 'L');

        $pdf->Ln(2);



        $pdf->SetTextColor(0, 0, 0);
        $pdf->setX(158.5);
        $pdf->Cell('8.5', 5, '#', 0, 0, 'C');
        $pdf->setX(167);
        $pdf->Cell('100', 5, utf8_decode('Famacología'), 0, 0, 'L');
        $pdf->setX(267);
        $pdf->Cell('20', 5, 'Cantidad', 0, 1, 'C');

        $contador_rec = 1;
        foreach ($detalle_consulta as $row) {
            $pdf->SetFont('Arial', '', 9);
            $pdf->SetTextColor(0, 0, 0);

            $pdf->setX(158.5);
            $pdf->Cell('8.5', 3, utf8_decode($contador_rec), 0, 0, 'C');

            $pdf->setX(167);
            $pdf->Cell('100', 3, utf8_decode($row['sa_det_conp_nombre']), 0, 0, 'L');

            $pdf->setX(267);
            $pdf->Cell('20', 3, utf8_decode($row['sa_det_conp_cantidad']), 0, 1, 'C');
            $pdf->setX(167);

            $pdf->SetFont('Arial', '', 8);
            $pdf->SetTextColor(57, 80, 122);
            $pdf->MultiCell(120, 5, "Indicaciones: " . utf8_decode($row['sa_det_conp_dosificacion']), 0, 'L');

            $contador_rec++;
        }

        $pdf->Ln(2);


        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetTextColor(0, 0, 0);

        $pdf->setX(158.5);
        $pdf->Cell(90, 5, utf8_decode('Observaciones: '), 0, 1, 'L');
        $pdf->setX(158.5);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(120, 5,  utf8_decode($datos[0]['sa_conp_tratamiento']), 0, 'L');


        $pdf->output();
    }

    function pdf_notificacion($id_consulta)
    {
        $datos = $this->modelo->lista_solo_consultas($id_consulta);
        $ficha_medica = $this->ficha_medicaM->lista_ficha_medica_id($datos[0]['sa_fice_id']);
        $paciente = $this->pacientesM->obtener_informacion_pacienteM($ficha_medica[0]['sa_fice_pac_id']);

        $usuario = $this->usuariosM->lista_usuarios($datos[0]['sa_conp_usu_id']);

        //Consulta 
        $sa_conp_id = $datos[0]['sa_conp_id'];

        $sa_conp_nivel = $datos[0]['sa_conp_nivel'];
        $sa_conp_paralelo = $datos[0]['sa_conp_paralelo'];

        $sa_conp_fecha_ingreso = $datos[0]['sa_conp_fecha_ingreso'];
        $sa_conp_fecha_ingreso = $sa_conp_fecha_ingreso;

        $sa_conp_desde_hora = $datos[0]['sa_conp_desde_hora'];
        if ($sa_conp_desde_hora !== null) {
            $sa_conp_desde_hora = preg_replace('/\.?0+$/', '', $sa_conp_desde_hora);
        }

        $sa_conp_hasta_hora = $datos[0]['sa_conp_hasta_hora'];
        if ($sa_conp_hasta_hora !== null) {
            $sa_conp_hasta_hora = preg_replace('/\.?0+$/', '', $sa_conp_hasta_hora);
        }

        $sa_conp_diagnostico_1 = $datos[0]['sa_conp_diagnostico_1'];

        $sa_conp_diagnostico_certificado = $datos[0]['sa_conp_diagnostico_certificado'];

        $sa_conp_permiso_salida = $datos[0]['sa_conp_permiso_salida'];

        $sa_conp_tipo_consulta = $datos[0]['sa_conp_tipo_consulta'];

        //Pacientes
        $sa_pac_temp_cedula = $paciente[0]['sa_pac_temp_cedula'];
        $sa_pac_temp_primer_nombre = $paciente[0]['sa_pac_temp_primer_nombre'];
        $sa_pac_temp_segundo_nombre = $paciente[0]['sa_pac_temp_segundo_nombre'];
        $sa_pac_temp_primer_apellido = $paciente[0]['sa_pac_temp_primer_apellido'];
        $sa_pac_temp_segundo_apellido = $paciente[0]['sa_pac_temp_segundo_apellido'];
        $sa_pac_tabla = $paciente[0]['sa_pac_tabla'];

        $nombre_completo = $sa_pac_temp_primer_apellido . ' ' .  $sa_pac_temp_segundo_apellido . ' ' .  $sa_pac_temp_primer_nombre . ' ' . $sa_pac_temp_segundo_nombre;


        //Valores para notificacion ///////////////////////////////////////////////////////////////////////////////////
        $fecha_creado = $sa_conp_fecha_ingreso;

        $grado = $sa_conp_nivel;
        $paralelo = $sa_conp_paralelo;
        //CONSULTA
        $hora_desde = $sa_conp_desde_hora;
        $hora_hasta = $sa_conp_hasta_hora;
        $diagnostico_consulta = $sa_conp_diagnostico_1;
        //cERTIFICADO
        $diagnostico_certificado = $sa_conp_diagnostico_certificado;

        $nombre_medico = empty($usuario) ? 'Vacio' : $usuario;

        $logo = '../../assets/images/favicon-32x32.png';
        if (($_SESSION['INICIO']['LOGO']) == '.' || $_SESSION['INICIO']['LOGO'] == '' || $_SESSION['INICIO']['LOGO'] == null) {
            $logo;
        } else {
            $logo = '../' . $_SESSION['INICIO']['LOGO'];
        }


        $pdf = new FPDF('P', 'mm', 'A4');
        $pdf->AddPage();

        $pdf->Image($logo, 15, 10, 30, 30);

        $pdf->SetFont('Arial', 'B', 12);

        $pdf->Cell(40, 10, utf8_decode(''), 'L T', 0);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(90, 10, utf8_decode('UNIDAD EDUCATIVA SAINT DOMINIC'), 1, 0, 'C');
        $pdf->Cell(20, 10, utf8_decode('Código:'), 1, 0, 'R');
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(40, 10, utf8_decode('AM - ' . $sa_conp_id), 1, 1, 'C');


        $pdf->Cell(40, 10, utf8_decode(''), 'L', 0);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(90, 10, utf8_decode('DEPARTAMENTO MÉDICO'), 1, 0, 'C');
        $pdf->Cell(20, 10, utf8_decode('Versión:'), 1, 0, 'R');
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(40, 10, utf8_decode('1.0'), 1, 1, 'C');


        $pdf->Cell(40, 10, utf8_decode(''), 'L B', 0, 'C');
        $pdf->SetFont('Arial', 'B', 12);

        if ($sa_conp_permiso_salida == 'SI') {
            $pdf->Cell(90, 10, utf8_decode('PERMISO DE SALIDA'), 1, 0, 'C');
        } else {
            $pdf->Cell(90, 10, utf8_decode(strtoupper($sa_conp_tipo_consulta)), 1, 0, 'C');
        }

        $pdf->Cell(20, 10, utf8_decode('Página:'), 1, 0, 'R');
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(40, 10, utf8_decode('1 de 1'), 1, 1, 'C');

        $pdf->ln('8');

        $pdf->Cell(0, 10, utf8_decode('Fecha: ' . $fecha_creado), 0, 1, 'L');
        $pdf->ln('3');

        if ($sa_pac_tabla == 'estudiantes') {
            if ($sa_conp_permiso_salida == 'SI') {
                $mensaje_salida = 'Certifico que él/la estudiante  ' . $nombre_completo . ' del grado ' . $grado . ' paralelo ' . $paralelo . ' requiere salir del plantel para recibir atención médica externa';
                $pdf->MultiCell(0, 6, utf8_decode($mensaje_salida), 0, 'J');
            } else if ($sa_conp_tipo_consulta == 'consulta') {
                $mensaje_consulta = 'Certifico que él/la estudiante ' . $nombre_completo . ' del grado ' . $grado . ' paralelo ' . $paralelo . ' se encontró en el departamento médico desde ' . $hora_desde . ' hasta ' . $hora_hasta . '.';
                $pdf->MultiCell(0, 6, utf8_decode($mensaje_consulta), 0, 'J');
            } else if ($sa_conp_tipo_consulta == 'certificado') {
                $mensaje_certificado = 'Certifico que él/la representante de ' . $nombre_completo . ' del grado ' . $grado . ' paralelo ' . $paralelo . ' entrega certificado médico de representado con diagnóstico ' . $diagnostico_certificado;
                $pdf->MultiCell(0, 6, utf8_decode($mensaje_certificado), 0, 'J');
            }
        } else {
            $mensaje = 'Certifico que  ' . $nombre_completo . ' estaba en el cosultoria con diagnóstico ' . $diagnostico_consulta . ' desde ' . $hora_desde . ' hasta ' . $hora_hasta . '.';
            $pdf->MultiCell(0, 6, utf8_decode($mensaje), 0, 'J');
        }

        $pdf->ln('25');

        $tipo_usuario = '';


        $nombre_medico_tipo = empty($nombre_medico[0]['tipo']) ? 'Vacio' : $nombre_medico[0]['tipo'];
        //print_r($nombre_medico_tipo);die();
        $nombre_medico_nombre = empty($nombre_medico[0]['nom']) ? 'Vacio' : $nombre_medico[0]['nom'];

        if (strtoupper($nombre_medico_tipo) == 'MEDICO') {
            $tipo_usuario = 'Dra. ';
        } else {
            $tipo_usuario = '';
        }

        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(60, 10, utf8_decode($tipo_usuario . $nombre_medico_nombre), '0', 0, 'C');
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(60, 10, utf8_decode(''), '0', 1, 'R');

        $pdf->Cell(60, 3, utf8_decode('Médico Institucional'), '0', 0, 'C');

        //print_r($nombre_medico); exit();

        //Footer
        $pdf->setY(0);
        $pdf->setX(30);
        $pdf->SetFont('Arial', 'I', 8);
        $pdf->Cell(148.5, 5, 'Desarrollado por Corsinf', 0, 0, 'C');

        $pdf->Output();
    }
}