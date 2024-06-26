<?php
require_once(dirname(__DIR__, 2) . '/modelo/RED_CONSULTORIOS/interm_examen_featExamenM.php');

$controlador = new interm_examen_featExamenC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar($_POST['t_examen_desc']));
}

if (isset($_GET['insertar'])) {
    echo json_encode($controlador->insertar_editar($_POST['parametros']));
}

if (isset($_GET['eliminar'])) {
    echo json_encode($controlador->eliminar($_POST['id']));
}




class interm_examen_featExamenC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new interm_examen_featExamenM();
    }

    function listar($t_examen_desc)
    {
        $datos = $this->modelo->where('ex_descripcion', $t_examen_desc)->listarJoin();
        return $datos;
    }

    function insertar_editar($parametros)
    {

        $datos1[0]['campo'] = 'itee_id';
        $datos1[0]['dato'] = strval($parametros['txt_id_itee']);


        $datos = array(
            array('campo' => 'ex_id', 'dato' => $parametros['txt_id_examen_itee']),
            array('campo' => 'fex_id', 'dato' => $parametros['txt_feat_id_itee']),
        );

        if ($parametros['txt_id_itee'] == '') {

            if (empty($this->modelo->where('ex_id', $parametros['txt_id_examen_itee'])->where('fex_id', $parametros['txt_feat_id_itee'])->listar())) {
                $datos = $this->modelo->insertar($datos);
            } else {
                return -2;
            }
        } else {
            $where[0]['campo'] = 'fex_id';
            $where[0]['dato'] = $parametros['txt_feat_id'];
            $datos = $this->modelo->editar($datos, $where);
        }

        //$datos = $this->modelo->insertar($datos);
        return $datos;
    }

    function eliminar($id)
    {
        $datos[0]['campo'] = 'itee_id';
        $datos[0]['dato'] = $id;

        $datos = $this->modelo->eliminar($datos);
        return $datos;
    }
}
