<?php

require_once(dirname(__DIR__, 2) . '/GENERAL/BaseModel.php');

class th_cat_comisionM extends BaseModel
{
    // Nombre real de la tabla en la base de datos
    protected $tabla = 'th_cat_comision';

    // Clave primaria
    protected $primaryKey = 'id_comision AS _id';

    // Campos que puedes insertar o actualizar
    protected $camposPermitidos = [
        'codigo AS codigo',
        'nombre AS nombre',
        'descripcion AS descripcion',
        'estado AS estado',
        'fecha_creacion AS fecha_creacion'
    ];
}
