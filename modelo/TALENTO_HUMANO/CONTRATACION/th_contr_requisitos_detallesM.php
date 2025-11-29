<?php

require_once(dirname(__DIR__, 2) . '/GENERAL/BaseModel.php');

class th_contr_requisitos_detallesM extends BaseModel
{
    // Nombre real de la tabla
    protected $tabla = 'th_contr_requisitos_detalles';

    // Primary key (alias _id)
    protected $primaryKey = 'th_reqdet_id AS _id';

    // Campos permitidos para insertar/editar (alias para uso en vistas)
    protected $camposPermitidos = [
        'th_reqdet_nombre AS nombre',
        'th_reqdet_descripcion AS descripcion',
        'th_reqdet_tipo_dato AS tipo_dato',
        'th_reqdet_obligatorio AS obligatorio',
        'th_reqdet_estado AS estado',
        'th_reqdet_fecha_creacion AS fecha_creacion',
        'th_reqdet_fecha_modificacion AS fecha_modificacion'
    ];
}