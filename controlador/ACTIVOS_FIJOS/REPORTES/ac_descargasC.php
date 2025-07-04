<?php
require_once(dirname(__DIR__, 3) . '/modelo/ACTIVOS_FIJOS/REPORTES/ac_descargasM.php');
require_once(dirname(__DIR__, 3) . '/db/codigos_globales.php');

$controlador = new ac_descargasC();
if (isset($_GET['lista_drop'])) {
    $q = isset($_GET['q']) ? $_GET['q'] : '';
    $lote = isset($_GET['lote']) ? $_GET['lote'] : 'lote_1'; // Por defecto lote_1
    echo json_encode($controlador->lista_lote_drop($lote, $q));
}

if (isset($_GET['parametros_lote'])) {

    echo json_encode($controlador->tomar_valores_lote($_POST['parametros']));
}


class ac_descargasC
{
    private $descargas;

    public function __construct()
    {
        $this->descargas = new ac_descargasM();
    }

    public function cargar_lotes()
    {
        $lotes = $this->descargas->listar();
        return $lotes;
    }


    function lista_lote_drop($lote, $q)
    {
        $datos = $this->descargas->listar_datos_lote($lote, $q);
        $datos2 = array();

        foreach ($datos as $value) {
            $datos2[] = array(
                'id' => $value['numero_lote'],
                'text' => $value['numero_lote']
            );
        }

        return $datos2;
    }

    function tomar_valores_lote($parametros)
    {
        print_r($parametros);
        die();
    }
}
