<?php

require_once(dirname(__DIR__, 2) . '/GENERAL/BaseModel.php');

class th_contr_cargo_requisitosM extends BaseModel
{
    // Nombre real de la tabla
    protected $tabla = 'th_contr_cargo_requisitos';

    // Primary key
    protected $primaryKey = 'th_car_req_id AS _id';

    // Campos permitidos para insertar/editar
    protected $camposPermitidos = [
        'th_car_req_nombre AS nombre',
        'th_car_req_descripcion AS descripcion',
        'th_car_req_estado AS estado',
        'th_car_req_fecha_creacion AS fecha_creacion',
        'th_car_req_fecha_modificacion AS fecha_modificacion'
    ];
}