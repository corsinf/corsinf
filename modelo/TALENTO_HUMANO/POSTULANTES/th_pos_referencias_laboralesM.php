<?php

require_once(dirname(__DIR__, 2) . '/BaseModel.php');

class th_pos_referencias_laboralesM extends BaseModel
{
    protected $tabla = 'th_pos_referencias_laborales';
    protected $primaryKey = 'th_refl_id AS _id';

    protected $camposPermitidos = [
        'th_refl_nombre_referencia',
        'th_refl_telefono_referencia',
        'th_refl_carta_recomendacion',
        'th_pos_id',
        'th_refl_estado',
        'th_refl_fecha_creacion',
        'th_refl_fecha_modificacion',
        'th_refl_correo',
        'th_refl_nombre_empresa',
    ];
}
