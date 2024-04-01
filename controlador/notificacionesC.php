<?php
include('../modelo/notificacionesM.php');

$controlador = new notificacionesC();

//Para mostrar todos los registros con campos especificos para la vista principal

if (isset($_GET['listar'])) {
    echo json_encode($controlador->lista_notificaciones($_POST['parametros']));
}


if (isset($_GET['insertar'])) {
    //echo json_encode($controlador->insertar($_POST['parametros']));
}

class notificacionesC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new notificacionesM();
    }

    function lista_notificaciones($parametros)
    {
        $datos = $this->modelo->lista_notificaciones($parametros);
        return $datos;
    }

    function insertar($parametros)
    {

        $datos = array(
            array('campo' => 'GLO_modulo', 'dato' => $parametros['GLO_modulo']),
            array('campo' => 'GLO_titulo', 'dato' => $parametros['GLO_titulo']),
            array('campo' => 'GLO_cuerpo', 'dato' => $parametros['GLO_cuerpo']),
            array('campo' => 'GLO_icono', 'dato' => $parametros['GLO_icono']),
            array('campo' => 'GLO_tabla', 'dato' => $parametros['GLO_tabla']),
            array('campo' => 'GLO_id_tabla', 'dato' => $parametros['GLO_id_tabla']),
            array('campo' => 'GLO_busqueda_especifica', 'dato' => $parametros['GLO_busqueda_especifica']),
            array('campo' => 'GLO_desc_busqueda', 'dato' => $parametros['GLO_desc_busqueda']),
            array('campo' => 'GLO_link_redirigir', 'dato' => $parametros['GLO_link_redirigir']),
            array('campo' => 'GLO_rol', 'dato' => $parametros['GLO_rol']),
            array('campo' => 'GLO_observacion', 'dato' => $parametros['GLO_observacion']),
        );

        //print_r($parametros); exit();

        $datos = $this->modelo->insertar($datos);

        return $datos;
    }
}
