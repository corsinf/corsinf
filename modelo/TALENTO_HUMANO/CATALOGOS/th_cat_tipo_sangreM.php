<?php

require_once(dirname(__DIR__, 2) . '/GENERAL/BaseModel.php');

class th_cat_tipo_sangreM extends BaseModel
{
    // Nombre real de la tabla en la base de datos
    protected $tabla = 'th_cat_tipo_sangre';

    // Clave primaria
    protected $primaryKey = 'id_tipo_sangre AS _id';

    // Campos que puedes insertar o actualizar
    protected $camposPermitidos = [
        'descripcion AS descripcion',
        'estado AS estado',
        'fecha_creacion AS fecha_creacion'
    ];
}
