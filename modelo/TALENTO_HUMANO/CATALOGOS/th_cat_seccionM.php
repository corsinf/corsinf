<?php

require_once(dirname(__DIR__, 2) . '/GENERAL/BaseModel.php');

class th_cat_seccionM extends BaseModel
{
    // Nombre de la tabla
    protected $tabla = 'th_cat_seccion';

    // Clave primaria
    protected $primaryKey = 'id_seccion AS _id';

    // Campos permitidos para insertar / editar
    protected $camposPermitidos = [
        'descripcion AS descripcion',
        'estado AS estado',
        'fecha_creacion AS fecha_creacion'
    ];
}
