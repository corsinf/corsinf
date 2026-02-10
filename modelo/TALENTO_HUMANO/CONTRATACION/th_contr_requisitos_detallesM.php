<?php

require_once(dirname(__DIR__, 2) . '/GENERAL/BaseModel.php');

class th_contr_requisitos_detallesM extends BaseModel
{
    // Nombre real de la tabla
    protected $tabla = 'th_cat_requisitos_detalles';

    // Primary key (alias _id)
    protected $primaryKey = 'id_requisitos_detalle AS _id';

    // Campos permitidos para insertar/editar (alias para uso en vistas)
    protected $camposPermitidos = [
        'nombre ',
        'descripcion ',
        'tipo_dato ',
        'obligatorio ',
        'estado ',
        'fecha_creacion ',
        'fecha_modificacion '
    ];
}