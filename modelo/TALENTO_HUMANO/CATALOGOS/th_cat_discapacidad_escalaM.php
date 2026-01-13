<?php

require_once(dirname(__DIR__, 2) . '/GENERAL/BaseModel.php');

class th_cat_discapacidad_escalaM extends BaseModel
{
    // Nombre real de la tabla en la base de datos
    protected $tabla = 'th_cat_discapacidad_escala';

    // Clave primaria
    protected $primaryKey = 'id_escala_dis AS _id';

    // Campos que puedes insertar o actualizar
    protected $camposPermitidos = [
        'id_discapacidad AS id_discapacidad',
        'descripcion AS descripcion',
        'estado AS estado',
        'fecha_creacion AS fecha_creacion'
    ];
}