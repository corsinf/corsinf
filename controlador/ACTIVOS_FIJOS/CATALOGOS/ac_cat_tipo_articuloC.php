<?php
require_once(dirname(__DIR__, 3) . '/modelo/ACTIVOS_FIJOS/CATALOGOS/ac_cat_tipo_articuloM.php');

$controlador = new ac_cat_tipo_articuloC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar($_POST['_id']));
}

class ac_cat_tipo_articuloC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new ac_cat_tipo_articuloM();
    }

    function listar($id)
    {
        if ($id == '') {
            $datos = $this->modelo->where('ESTADO', 'A')->listar();
        } else {
            $datos = $this->modelo->where('ID_TIPO_ARTICULO', $id)->listar();
        }

        return $datos;
    }
}
