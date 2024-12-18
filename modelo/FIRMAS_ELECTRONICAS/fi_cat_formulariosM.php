<?php

require_once(dirname(__DIR__, 1) . '/GENERAL/BaseModel.php');

class fi_cat_formulariosM extends BaseModel
{
    protected $tabla = 'fi_cat_formularios';
    protected $primaryKey = 'fi_tfo_id AS _id';

    protected $camposPermitidos = [
        'fi_tfo_nombre AS nombre',
    ];
}
