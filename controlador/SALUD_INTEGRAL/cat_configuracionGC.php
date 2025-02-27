<?php
require_once(dirname(__DIR__, 2) . '/modelo/SALUD_INTEGRAL/cat_configuracionGM.php');

$controlador = new cat_configuracionGC();

//Para mostrar todos los registros con campos especificos para la vista principal
//Con respecto al nombre de la accion que va a realizar en este caso solo correos
if (isset($_GET['listar_config_general'])) {
    echo json_encode($controlador->lista_vista_med_ins('correos'));
}

if (isset($_GET['listar_config_idukay'])) {
    echo json_encode($controlador->lista_vista_med_ins('idukay'));
}

if (isset($_GET['listar_config_idukay_cron'])) {
    echo json_encode($controlador->lista_vista_med_ins('idukay_cron'));
}

if (isset($_GET['editar_config_idukay_cron'])) {
    echo json_encode($controlador->editar_idukay_cron($_POST['parametros']));
}

if (isset($_GET['vista_mod'])) {
    echo json_encode($controlador->editar($_POST['parametros']));
    //print_r($_POST['parametros']); exit;
}


class cat_configuracionGC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new cat_configuracionGM();
    }

    function lista_vista_med_ins($nombre_accion)
    {
        $datos = $this->modelo->lista_vista_conf_general($nombre_accion);
        return $datos;
    }

    function editar($parametros)
    {
        $sa_config_valor = '';
        if (isset($parametros['sa_config_valor'])) {
            $sa_config_valor = $parametros['sa_config_valor'];
        }

        $datos = array(
            array('campo' => 'sa_config_valor', 'dato' =>  $sa_config_valor),
            array('campo' => 'sa_config_estado', 'dato' => $parametros['sa_config_estado']),
        );

        $where[0]['campo'] = 'sa_config_id';
        $where[0]['dato'] = $parametros['sa_config_id'];
        $datos = $this->modelo->editar($datos, $where);

        return $datos;
    }

    public function editar_idukay_cron($parametros)
    {
        // Mapea los valores de los parámetros con los valores de sa_config_validar
        $mapeo = [
            'nombre_modulo' => $parametros['txt_nombre_modulo'],
            'nombre_empresa' => $parametros['txt_nombre_empresa'],
            'url_guardar_bat' => $parametros['txt_url_guardar_bat'],
            'script_php_motor' => $parametros['txt_script_php_motor'],
            'motor_bat' => $parametros['txt_motor_bat'],
            'hora_ejecucion_PW' => $parametros['txt_hora_PW'],
        ];

        // Itera sobre cada par clave-valor y realiza la actualización
        foreach ($mapeo as $validar => $valor) {
            // Datos a actualizar
            $datos = array(
                array('campo' => 'sa_config_valor', 'dato' => $valor)
            );

            // Condición WHERE para identificar el registro a actualizar
            $where = array(
                array('campo' => 'sa_config_validar', 'dato' => $validar),
                array('campo' => 'sa_config_nombre', 'dato' => 'idukay_cron')
            );

            // Ejecuta la actualización
            $resultado = $this->modelo->editar($datos, $where);

            // Maneja posibles errores o retorna el resultado
            if (!$resultado) {
                // Maneja el error (puede ser lanzar una excepción, loguear el error, etc.)
                throw new Exception("Error al actualizar el campo: $validar");
            }
        }

        return true;
    }
}
