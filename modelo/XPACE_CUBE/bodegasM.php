<?php

require_once(dirname(__DIR__, 1) . '/GENERAL/BaseModel.php');

class bodegasM extends BaseModel
{
    // Nombre real de la tabla en la base de datos
    protected $tabla = 'hub_bodega';

    // Clave primaria (alias opcional para _id)
    protected $primaryKey = 'id_articulo AS _id';

    // Campos que se pueden insertar o actualizar
    protected $camposPermitidos = [
        'codigo',
        'nombre',
        'descripcion',
        'categoria',
        'cantidad_total',
        'cantidad_disponible',
        'precio_unitario',
        'fecha_ingreso',
        'estado',
    ];
}
