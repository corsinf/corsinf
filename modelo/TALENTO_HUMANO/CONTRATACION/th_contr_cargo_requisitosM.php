<?php

require_once(dirname(__DIR__, 2) . '/GENERAL/BaseModel.php');

class th_contr_cargo_requisitosM extends BaseModel
{
    // Nombre real de la tabla
    protected $tabla = 'th_cat_cargo_requisitos';

    // Primary key
    protected $primaryKey = 'id_cargo_requisitos AS _id';

    // Campos permitidos para insertar/editar
    protected $camposPermitidos = [
        'nombre ',
        'descripcion ',
        'estado ',
        'fecha_creacion ',
        'fecha_modificacion '
    ];
}