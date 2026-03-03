<?php

require_once(dirname(__DIR__, 1) . '/GENERAL/BaseModel.php');

class categoriasM extends BaseModel
{
    protected $tabla = 'categoria';
    protected $primaryKey = 'id_categoria AS _id';

    protected $camposPermitidos = [
        'nombre',
        'estado',
        'empresa',
        'imagen',
        'imagen'
    ];
    
}

?>