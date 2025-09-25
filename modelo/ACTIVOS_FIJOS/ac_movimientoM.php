<?php

require_once(dirname(__DIR__, 1) . '/GENERAL/BaseModel.php');

class ac_movimientoM extends BaseModel
{
    protected $tabla = 'ac_movimiento';
    protected $primaryKey = 'id_movimiento AS _id';

    protected $camposPermitidos = [
        'id_plantilla',
        'obs_movimiento',
        'fecha_movimiento',
        'responsable',
        'seccion',
        'dato_anterior',
        'dato_nuevo',
        'codigo_ant',
        'codigo_nue',
        'id_usuario',
    ];
}
