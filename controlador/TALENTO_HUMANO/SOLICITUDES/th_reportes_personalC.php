<?php

class th_reportes_personalC
{
    public function reporte_permiso_usuario($parametros, $modo_guardar = false)
    {
        if (is_string($parametros)) {
            $parametros = json_decode($parametros, true);
        }

        require_once('DOCUMENTOS/reporte_permiso_personal.php');
        return pdf_reporte_permiso($parametros, $modo_guardar);
    }
}