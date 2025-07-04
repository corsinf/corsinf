<?php

require_once(dirname(__DIR__, 3) . '/modelo/ACTIVOS_FIJOS/articulosM.php');
require_once(dirname(__DIR__, 3) . '/modelo/ACTIVOS_FIJOS/custodioM.php');
require_once(dirname(__DIR__, 3) . '/modelo/ACTIVOS_FIJOS/localizacionM.php');
require_once(dirname(__DIR__, 3) . '/modelo/ACTIVOS_FIJOS/ac_auditoriaM.php');
require_once(dirname(__DIR__, 3) . '/modelo/ACTIVOS_FIJOS/ac_articulos_itM.php');
require_once(dirname(__DIR__, 3) . '/db/codigos_globales.php');

class ac_reportes_activos_fijosC
{
    private $articulos;
    private $custodio;
    private $localizacion;
    private $auditoria;
    private $ac_articulos_itM;
    private $codigos_globales;

    public function __construct()
    {
        $this->custodio = new custodioM();
        $this->articulos = new articulosM();
        $this->localizacion = new localizacionM();
        $this->auditoria = new ac_auditoriaM();
        $this->ac_articulos_itM = new ac_articulos_itM();
    }

    public function reporte_cedula_activo($id_articulo, $mostrar = false)
    {
        require_once('DOCUMENTOS/reporte_cedula_activo.php');

        $articulos = $this->articulos->listar_articulos_id($id_articulo);

        $datos_articulo_it = $this->ac_articulos_itM->where('ac_ait_id_articulo', $id_articulo)->listar();

        return pdf_cedula_activo($articulos, $datos_articulo_it, $mostrar);
    }

    public function reporte_auditoria_articulos($id_persona, $id_localizacion, $id_empresa,  $mostrar = false)
    {
        require_once('DOCUMENTOS/reporte_auditoria_articulos.php');
        $this->codigos_globales = new codigos_globales();

        $id_persona = $this->codigos_globales->desencriptar_alfanumerico($id_persona);
        $id_localizacion = $this->codigos_globales->desencriptar_alfanumerico($id_localizacion);
        $id_empresa = $this->codigos_globales->desencriptar_alfanumerico($id_empresa);

        $auditoria =  $this->auditoria->lista_articulos_auditorio_vista_publica($id_empresa); // Método para obtener los datos
        $custodio =  $this->custodio->buscar_custodio_vista_publica($id_persona, $id_empresa);
        $localizacion =  $this->localizacion->buscar_localizacion_vista_publica($id_localizacion, $id_empresa);

        return pdf_reporte_auditoria_articulos($auditoria, $custodio, $localizacion, $this->custodio, $this->localizacion, $id_persona, $id_localizacion, $mostrar);
    }

    public function reporte_articulos_custodio_localizacion($id_persona, $id_localizacion, $id_empresa,  $mostrar = false)
    {
        require_once('DOCUMENTOS/reporte_articulos_custodio_localizacion.php');
        $this->codigos_globales = new codigos_globales();

        $id_persona = $this->codigos_globales->desencriptar_alfanumerico($id_persona);
        $id_localizacion = $this->codigos_globales->desencriptar_alfanumerico($id_localizacion);
        $id_empresa = $this->codigos_globales->desencriptar_alfanumerico($id_empresa);

        $articulos = $this->articulos->listar_articulos_vista_publica($id_persona, $id_localizacion, $id_empresa);
        $custodio = $this->custodio->buscar_custodio_vista_publica($id_persona, $id_empresa);
        $localizacion = $this->localizacion->buscar_localizacion_vista_publica($id_localizacion, $id_empresa);

        return pdf_reporte_articulos_custodio_localizacion($articulos, $custodio, $localizacion, $mostrar);
    }
}
