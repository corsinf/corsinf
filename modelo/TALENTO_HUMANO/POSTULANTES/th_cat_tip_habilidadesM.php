<?php

require_once(dirname(__DIR__, 2) . '/BaseModel.php');

class th_cat_tip_habilidadesM extends BaseModel
{
    protected $tabla = 'th_cat_tip_habilidades';
    protected $primaryKey = 'th_tiph_id AS _id';

    protected $camposPermitidos = [
        'th_tiph_nombre',
    ];
}