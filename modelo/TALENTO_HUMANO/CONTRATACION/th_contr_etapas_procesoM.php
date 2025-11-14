<?php

require_once(dirname(__DIR__, 2) . '/GENERAL/BaseModel.php');

class th_contr_etapas_procesoM extends BaseModel
{
    // Nombre real de la tabla en la base de datos
    protected $tabla = 'th_contr_etapas_proceso';

    // Clave primaria (alias _id para mantener consistencia)
    protected $primaryKey = 'th_etapa_id AS _id';

    // Campos que puedes insertar o actualizar (aliases para uso en la app)
    protected $camposPermitidos = [
        'th_pla_id AS th_pla_id',
        'th_etapa_nombre AS nombre',
        'th_etapa_tipo AS tipo',
        'th_etapa_orden AS orden',
        'th_etapa_obligatoria AS obligatoria',
        'th_etapa_descripcion AS descripcion',
        'th_etapa_estado AS estado',
        'th_etapa_fecha_creacion AS fecha_creacion',
        'th_etapa_fecha_modificacion AS fecha_modificacion'
    ];
}