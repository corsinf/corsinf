<?php

require_once(dirname(__DIR__, 2) . '/GENERAL/BaseModel.php');

class th_contr_etapas_procesoM extends BaseModel
{
    // Nombre real de la tabla en la base de datos
    protected $tabla = 'th_cat_etapas_proceso';

    // Clave primaria (alias _id para mantener consistencia)
    protected $primaryKey = 'id_etapa_proceso AS _id';

    // Campos que puedes insertar o actualizar (aliases para uso en la app)
    protected $camposPermitidos = [
        'nombre AS nombre',
        'tipo AS tipo',
        'orden AS orden',
        'obligatoria AS obligatoria',
        'descripcion AS descripcion',
        'estado AS estado',
        'fecha_creacion AS fecha_creacion',
    ];
   


}