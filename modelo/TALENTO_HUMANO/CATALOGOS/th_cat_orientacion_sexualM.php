<?php

require_once(dirname(__DIR__, 2) . '/GENERAL/BaseModel.php');

class th_cat_orientacion_sexualM extends BaseModel
{
    // Nombre real de la tabla en la base de datos
    protected $tabla = 'th_cat_orientacion_sexual';

    // Clave primaria
    protected $primaryKey = 'id_orientacion_sexual AS _id';

    // Campos que puedes insertar o actualizar
    protected $camposPermitidos = [
        'descripcion AS descripcion',
        'estado AS estado',
        'fecha_creacion AS fecha_creacion'
    ];
}
