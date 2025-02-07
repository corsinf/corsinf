<?php

require_once(dirname(__DIR__, 2) . '/GENERAL/BaseModel.php');

class th_cat_habilidadesM extends BaseModel
{
    protected $tabla = 'th_cat_habilidades';
    protected $primaryKey = 'th_hab_id';

    protected $camposPermitidos = [
        'th_hab_nombre',
        'th_hab_estado',
        'th_tiph_id',
    ];
}

