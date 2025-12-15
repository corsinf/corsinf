<?php

require_once(dirname(__DIR__, 2) . '/GENERAL/BaseModel.php');

class th_contr_cargo_funcionesM extends BaseModel
{
    // Nombre de la tabla en la BD
    protected $tabla = 'th_contr_cargo_funciones';

    // Clave primaria
    protected $primaryKey = 'th_carfun_id AS _id';

    // Campos permitidos para insert/update
    protected $camposPermitidos = [
        'th_car_id ',
        'th_carfun_nombre ',
        'th_carfun_descripcion ',
        'th_carfun_frecuencia ',
        'th_carfun_porcentaje_tiempo ',
        'th_carfun_es_principal ',
        'th_carfun_orden ',
        'th_carfun_estado ',
        'th_carfun_fecha_creacion ',
        'th_carfun_fecha_modificacion '
    ];
}