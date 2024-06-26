<?php
require_once(dirname(__DIR__, 2) . '/modelo/RED_CONSULTORIOS/cat_examenesM.php');

$controlador = new cat_examenesC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar());
}

if (isset($_GET['insertar'])) {
    echo json_encode($controlador->insertar_editar($_POST['parametros']));
}

if (isset($_GET['eliminar'])) {
    echo json_encode($controlador->eliminar($_POST['id']));
}


class cat_examenesC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new cat_examenesM();
    }

    function listar()
    {
        $datos = $this->modelo->where('ex_estado', '1')->listar();
        return $datos;
    }

    function insertar_editar($parametros)
    {

        $datos1[0]['campo'] = 'ex_id';
        $datos1[0]['dato'] = strval($parametros['txt_id_examen']);


        $datos = array(
            array('campo' => 'ex_descripcion', 'dato' => $parametros['txt_examen']),
        );

        if ($parametros['txt_id_examen'] == '') {

            if (empty($this->modelo->where('ex_descripcion', $parametros['txt_examen'])->listar())) {
                $datos = $this->modelo->insertar($datos);
            } else {
                return -2;
            }
        } else {
            $where[0]['campo'] = 'ex_id';
            $where[0]['dato'] = $parametros['txt_id_examen'];
            $datos = $this->modelo->editar($datos, $where);
        }

        //$datos = $this->modelo->insertar($datos);
        return $datos;
    }

    function eliminar($id)
    {
        $datos[0]['campo'] = 'ex_id';
        $datos[0]['dato'] = $id;

        $datos = $this->modelo->eliminar($datos);
        return $datos;
    }
}
