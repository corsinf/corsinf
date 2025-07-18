<?php

date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 2) . '/modelo/TALENTO_HUMANO/th_triangular_itemM.php');

$controlador = new th_triangular_itemC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar($_POST['id'] ?? ''));
}

if (isset($_GET['insertar'])) {
    echo json_encode($controlador->insertar_editar($_POST['parametros']));
}



class th_triangular_itemC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new th_triangular_itemM();
    }

    function listar($id = '')
    {
        if ($id == '') {
            $datos = $this->modelo->listar();
        } else {
            $datos = $this->modelo->where('th_tri_id', $id)->listar();
        }
        return $datos;
    }


    function insertar_editar($parametros)
    {

        if (!isset($parametros['puntos']) || !is_array($parametros['puntos'])) {
            return ['estado' => 'error', 'mensaje' => 'No se recibieron puntos válidos'];
        }

        foreach ($parametros['puntos'] as $punto) {
            if (isset($punto['lat']) && isset($punto['lng'])) {
                $datos = array(
                     array('campo' => 'th_tri_id', 'dato' => $parametros['ddl_triangular']),
                     array('campo' => 'th_itr_longitud', 'dato' => $punto['lat']),
                     array('campo' => 'th_itr_latitud', 'dato' => $punto['lng']),
                     array('campo' => 'th_itr_n_punto', 'dato' => $punto['punto']),
                     array('campo' => 'th_itr_fecha_creacion', 'dato' => date('Y-m-d H:i:s')),
                    // Agrega más campos si es necesario, como ID de zona, usuario, fecha, etc.
                );

                $datos = $this->modelo->insertar($datos);
            }
        }
        

        return $datos;
    }
}
