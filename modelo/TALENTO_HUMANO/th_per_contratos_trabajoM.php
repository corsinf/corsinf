<?php

require_once(dirname(__DIR__, 1) . '/GENERAL/BaseModel.php');

class th_per_contratos_trabajoM extends BaseModel
{
    protected $tabla = 'th_per_contratos_trabajos';
    protected $primaryKey = 'th_ctr_id AS _id';

    protected $camposPermitidos = [
        'th_ctr_id',
        'th_per_id',
        'th_ctr_nombre_empresa',
        'th_ctr_tipo_contrato',
        'th_ctr_ruta_archivo',
        'th_ctr_fecha_inicio_contrato',
        'th_ctr_fecha_fin_contrato',
        'th_ctr_estado',        
        'th_ctr_fecha_creacion',
        'th_ctr_fecha_modificacion',
        'th_ctr_cbx_fecha_fin_experiencia',
        

    ];
}

