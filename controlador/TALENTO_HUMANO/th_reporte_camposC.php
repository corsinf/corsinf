
<?php
date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 2) . '/modelo/TALENTO_HUMANO/th_reporte_camposM.php');

$controlador = new th_reporte_camposC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar($_POST['id'] ?? ''));
}

if (isset($_GET['insertar'])) {
    echo json_encode($controlador->insertar_editar($_POST['parametros']));
}

if (isset($_GET['eliminar'])) {
    echo json_encode($controlador->eliminar($_POST['id']));
}



class th_reporte_camposC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new th_reporte_camposM();
    }

    function listar($id_reporte = '')
    {
        if ($id_reporte == '') {
            $datos = $this->modelo->listar();
        } else {
            $datos = $this->modelo->listar_reporte_campos($id_reporte);
        }
        return $datos;
    }

    function insertar_editar($parametros)
    {
        if (isset($parametros['lista_destino_valores']) && is_array($parametros['lista_destino_valores'])) {
            $datos = $this->modelo->editar_insertarM($parametros['lista_destino_valores']);
        }

        if (isset($parametros['lista_origen_valores']) && is_array($parametros['lista_origen_valores'])) {
            $datos = $this->modelo->eliminar_registros($parametros['lista_origen_valores']);
        }

        return $datos;
    }


    function eliminar($id)
    {
        $datos = array(
            array('campo' => 'th_rec_id', 'dato' => $id),
        );

        $datos = $this->modelo->eliminar($datos);
        return $datos;
    }
}
