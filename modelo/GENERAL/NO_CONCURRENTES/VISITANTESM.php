<?php
require_once(dirname(__DIR__, 2) . '/GENERAL/BaseModel.php');

class VISITANTESM extends BaseModel
{
    protected $tabla = 'VISITANTES';
    protected $primaryKey = 'id_visitantes AS _id';

    protected $camposPermitidos = [
        'th_per_id',
        'PERFIL',
        'PASS',
        'POLITICAS_ACEPTACION',
        'DELETE_LOGIC',
    ];
}