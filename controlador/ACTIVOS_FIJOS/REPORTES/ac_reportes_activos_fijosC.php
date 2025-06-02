<?php

require_once(dirname(__DIR__, 3) . '/modelo/ACTIVOS_FIJOS/articulosM.php');
require_once(dirname(__DIR__, 3) . '/modelo/ACTIVOS_FIJOS/custodioM.php');
require_once(dirname(__DIR__, 3) . '/modelo/ACTIVOS_FIJOS/localizacionM.php');
require_once(dirname(__DIR__, 3) . '/modelo/ACTIVOS_FIJOS/auditoriaM.php');
require_once(dirname(__DIR__, 3) . '/db/codigos_globales.php');

class ac_reportes_activos_fijosC
{
    private $articulos;
    private $custodio;
    private $localizacion;
    private $auditoria;

    public function __construct()
    {
        $this->custodio = new custodioM();
        $this->articulos = new articulosM();
        $this->localizacion = new localizacionM();
        $this->auditoria = new auditoriaM();
    }

    public function reporte_cedula_activo($id_articulo, $mostrar = false)
    {
        require_once('DOCUMENTOS/reporte_cedula_activo.php');

        $articulos = $this->articulos->listar_articulos_id($id_articulo);

        return pdf_cedula_activo($articulos, $mostrar);
    }
}
