<?php

require_once(dirname(__DIR__, 2) . '/GENERAL/BaseModel.php');

class hub_turnosM extends BaseModel
{
    protected $tabla = 'hub_turnos';

    // Clave primaria
    protected $primaryKey = 'hub_tur_id AS _id';

    protected $camposPermitidos = [
        'hub_tur_nombre AS nombre',
        'hub_tur_hora_entrada AS hora_entrada',
        'hub_tur_hora_salida AS hora_salida',
        'is_deleted',
        'id_usuario_crea',
        'fecha_creacion',
        'id_usuario_modifica',
        'fecha_modificacion',
        'hub_tur_color AS color',
    ];
}
