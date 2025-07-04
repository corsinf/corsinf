<?php
require_once(dirname(__DIR__, 3) . '/modelo/ACTIVOS_FIJOS/REPORTES/ac_descargasM.php');
require_once(dirname(__DIR__, 3) . '/db/codigos_globales.php');


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
}
