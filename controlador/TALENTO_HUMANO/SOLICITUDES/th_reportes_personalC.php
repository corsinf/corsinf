<?php

class th_reportes_personalC
{
    public function reporte_permiso_usuario($parametros, $mostrar = false)
    {
        require_once('DOCUMENTOS/reporte_permiso_personal.php');

        return pdf_reporte_permiso($parametros, $mostrar);
    }
}