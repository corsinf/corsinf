<?php

require_once(dirname(__DIR__, 2) . '/GENERAL/BaseModel.php');

class th_contr_cargosM extends BaseModel
{
    // Nombre real de la tabla en la base de datos
    protected $tabla = 'th_contr_cargos';

    // Clave primaria
    protected $primaryKey = 'th_car_id AS _id';

    // Campos que puedes insertar o actualizar
  protected $camposPermitidos = [
    'th_car_nombre AS nombre',
    'th_car_descripcion AS descripcion',
    'th_car_nivel AS nivel',
    'th_car_area AS area',
    'th_car_estado AS estado',
    'th_car_fecha_creacion AS fecha_creacion',
    'th_car_fecha_modificacion AS fecha_modificacion'
];
}
