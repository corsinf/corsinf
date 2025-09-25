<?php

require_once(dirname(__DIR__, 2) . '/GENERAL/BaseModel.php');

class po_procesosM extends BaseModel
{
    protected $tabla = 'po_procesos';
    protected $primaryKey = 'po_id AS _id';

    protected $camposPermitidos = [
        // 'id_empresa AS id_empresa',
        'po_nivel AS nivel',
        'po_TP AS TP',
        'po_proceso AS proceso',
        'po_DC AS DC',
        'po_cmds AS cmds',
        'po_picture AS picture',
        'po_color AS color',
        'po_cta_costo AS cta_costo',
        'po_mi_cta AS mi_cta',
        'po_estado AS estado',
        'po_fecha_creacion AS fecha_creacion',
    ];
}