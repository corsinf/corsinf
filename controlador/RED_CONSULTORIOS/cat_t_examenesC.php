<?php
require_once(dirname(__DIR__, 2) . '/modelo/RED_CONSULTORIOS/cat_t_examenesM.php');

$controlador = new cat_t_examenesC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar());
}

if (isset($_GET['insertar'])) {
    echo json_encode($controlador->insertar_editar($_POST['parametros']));
}

if (isset($_GET['eliminar'])) {
    echo json_encode($controlador->eliminar($_POST['id']));
}


class cat_t_examenesC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new cat_t_examenesM();
    }

    function listar()
    {
        $datos = $this->modelo->where('t_ex_estado', '1')->listar();
        return $datos;
    }

    function insertar_editar($parametros)
    {

        $datos1[0]['campo'] = 'sa_sec_id';
        $datos1[0]['dato'] = strval($parametros['sa_sec_id']);
        $datos[1]['campo'] = 'sa_sec_nombre';
        $datos[1]['dato'] = $parametros['sa_sec_nombre'];

        $datos = array(
            array('campo' => 'sa_fice_id', 'dato' => $parametros['sa_fice_id']),
            array('campo' => 'sa_conp_nivel', 'dato' => $parametros['sa_conp_nivel']),
            array('campo' => 'sa_conp_paralelo', 'dato' => $parametros['sa_conp_paralelo']),
            array('campo' => 'sa_conp_edad', 'dato' => $parametros['sa_conp_edad']),
        );

        if ($parametros['sa_sec_id'] == '') {

            $datos = $this->modelo->insertar($datos);
            
        } else {
            $where[0]['campo'] = 'sa_sec_id';
            $where[0]['dato'] = $parametros['sa_sec_id'];
            $datos = $this->modelo->editar($datos, $where);
        }
        //$datos = $this->modelo->insertar($datos);
        return $datos;
    }

    function eliminar($id)
    {
        $datos[0]['campo'] = 'sa_sec_id';
        $datos[0]['dato'] = $id;

        $datos = $this->modelo->eliminar($datos);
        return $datos;
    }
}
