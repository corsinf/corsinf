<?php
include('../modelo/permisos_salidaM.php');

include('../lib/HIKVISION/Notificaciones.php');
include('../lib/HIKVISION/HIK_TCP.php');


$controlador = new permisos_salidaC();

//Para mostrar todos los registros con campos especificos para la vista principal
if (isset($_GET['listar_todo'])) {
    echo json_encode($controlador->lista_todo_permisos_salida());
}

if (isset($_GET['insertar'])) {
    echo json_encode($controlador->insertar($_POST['parametros']));
}

if (isset($_GET['eliminar'])) {
    echo json_encode($controlador->eliminar($_POST['id']));
}

if (isset($_GET['llegada'])) {
    echo json_encode($controlador->llegada($_POST['id']));
}


class permisos_salidaC
{
    private $modelo;
    private $notificaciones_HV;
    private $TCP_HV;

    //Variables para HIKVISION

    private $ip_api_hikvision;
    private $key_api_hikvision;
    private $user_api_hikvision;
    private $tcp_puerto_hikvision;
    private $puerto_api_hikvision;

    function __construct()
    {
        $this->modelo = new permisos_salidaM();

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
    }

    function lista_todo_permisos_salida()
    {
        $datos = $this->modelo->lista_permisos_salida_todo();
        return $datos;
    }

    function insertar($parametros)
    {
        date_default_timezone_set('America/Guayaquil');
        $hora_actual = date('H:i:s');

        $datos = array(
            array('campo' => 'ac_ps_id_autoriza', 'dato' => $parametros['ac_ps_id_autoriza']),
            array('campo' => 'ac_ps_tabla', 'dato' => 'estudiantes'),
            array('campo' => 'ac_ps_id_tabla', 'dato' => $parametros['ac_ps_id_tabla']),
            array('campo' => 'ac_ps_nombre', 'dato' => $parametros['ac_ps_nombre']),
            array('campo' => 'ac_ps_hora_salida', 'dato' => $hora_actual),
            //array('campo' => 'ac_ps_hora_entrada', 'dato' => $parametros['ac_ps_hora_entrada']),
            array('campo' => 'ac_ps_estado_salida', 'dato' => $parametros['ac_ps_estado_salida']),
            //array('campo' => 'ac_ps_codigo_TCP_HIK', 'dato' => 'prueba'),
            array('campo' => 'ac_ps_prioridad', 'dato' => $parametros['ac_ps_prioridad']),
            array('campo' => 'ac_ps_observacion', 'dato' => $parametros['ac_ps_observacion']),
        );

        //Ingresa los datos
        $id = $this->modelo->insertar_id($datos);

        $respuesta_servicio_API = '';

        if ($this->user_api_hikvision != '.' && $this->key_api_hikvision != '.' && $this->ip_api_hikvision != '.' && $this->puerto_api_hikvision != '.') {
            //Permiso del inspector
            $mensaje_HV = 'per_ins_' . $id;

            $datos_edit = array(
                //array('campo' => 'ac_ps_hora_entrada', 'dato' => $parametros['ac_ps_hora_entrada']),
                array('campo' => 'ac_ps_codigo_TCP_HIK', 'dato' => $mensaje_HV),
            );

            $where[0]['campo'] = 'ac_ps_id';
            $where[0]['dato'] = $id;
            $datos = $this->modelo->editar($datos_edit, $where);

            /*HIKVISION*/
            $mensaje_alerta = '';
            if ($parametros['ac_ps_estado_salida'] == '1') {
                $mensaje_alerta = 'PSR_' . $parametros['ac_ps_nombre'] . '_' . $id;
                $API_response = $this->notificaciones_HV->crear_Evento_usuario($mensaje_alerta, $mensaje_HV, $parametros['ac_ps_prioridad']);
                $respuesta_servicio_API = $API_response;
                ///////////////////////////////////////////////////////////////////////////////////////
                $max_intentos = 10;
                $intentos = 0;
                while (($API_response != '0') && $intentos < $max_intentos) {
                    usleep(500000);
                    $intentos++;
                }
                if ($API_response == '0') {
                    $this->TCP_HV->TCP_enviar($mensaje_HV);
                } else {
                    //echo "La tarea no se completó después del tiempo máximo.";
                }
                ///////////////////////////////////////////////////////////////////////////////////////
            } else if ($parametros['ac_ps_estado_salida'] == '0') {
                $mensaje_alerta = 'PSL_' . $parametros['ac_ps_nombre'] . '_' . $id;
                $API_response = $this->notificaciones_HV->crear_Evento_usuario($mensaje_alerta, $mensaje_HV, $parametros['ac_ps_prioridad']);
                $respuesta_servicio_API = $API_response;

                ///////////////////////////////////////////////////////////////////////////////////////
                $max_intentos = 10;
                $intentos = 0;
                while (($API_response != '0') && $intentos < $max_intentos) {
                    usleep(500000);
                    $intentos++;
                }
                if ($API_response == '0') {
                    $this->TCP_HV->TCP_enviar($mensaje_HV);
                } else {
                    //echo "La tarea no se completó después del tiempo máximo.";
                }
                ///////////////////////////////////////////////////////////////////////////////////////
            }
        }


        if ($respuesta_servicio_API == -10) {
            return -10;
        } else {
            return $datos;
        }
    }

    function llegada($id)
    {
        date_default_timezone_set('America/Guayaquil');
        $hora_actual = date('H:i:s');

        $datos_edit = array(
            array('campo' => 'ac_ps_hora_entrada', 'dato' => $hora_actual),

        );

        $where[0]['campo'] = 'ac_ps_id';
        $where[0]['dato'] = $id;
        $datos = $this->modelo->editar($datos_edit, $where);

        return $datos;
    }

    function eliminar($id)
    {
        $datos[0]['campo'] = 'ac_ps_id';
        $datos[0]['dato'] = $id;
        $datos = $this->modelo->eliminar($datos);
        return $datos;
    }
}
