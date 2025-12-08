<?php

require_once(dirname(__DIR__, 2) . '/GENERAL/BaseModel.php');

class th_contr_competenciasM extends BaseModel
{
    // Nombre real de la tabla en la base de datos
    protected $tabla = 'th_contr_competencias';

    // Clave primaria con alias _id para compatibilidad con BaseModel
    protected $primaryKey = 'th_comp_id AS _id';

    // Campos permitidos para inserción / actualización
    protected $camposPermitidos = [
        'th_comp_codigo AS codigo',
        'th_comp_nombre AS nombre',
        'th_comp_categoria AS categoria',
        'th_comp_tipo_disc AS tipo_disc',
        'th_comp_descripcion AS descripcion',
        'th_comp_definicion_completa AS definicion_completa',
        'th_comp_comportamientos_esperados AS comportamientos_esperados',
        'th_comp_es_disc AS es_disc',
        'th_comp_estado AS estado',
        'th_comp_fecha_creacion AS fecha_creacion',
        'th_comp_fecha_modificacion AS fecha_modificacion'
    ];
}