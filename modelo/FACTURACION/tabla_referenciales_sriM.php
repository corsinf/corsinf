<?php

require_once(dirname(__DIR__, 1) . '/GENERAL/BaseModel.php');

class tabla_referenciales_sriM extends BaseModel
{
    protected $tabla = 'tabla_referenciales_sri';
    protected $primaryKey = 'ID AS _id';

    protected $camposPermitidos = [
        'Tipo_Referencia AS referencia',
        'Codigo AS codigo',
        'Descripcion AS descripcion'
    ];
    
}
