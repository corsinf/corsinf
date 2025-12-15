<?php

require_once(dirname(__DIR__, 2) . '/GENERAL/BaseModel.php');

class th_contr_cargo_competencias_detalleM extends BaseModel
{
    // Nombre real de la tabla en la base de datos
    protected $tabla = 'th_contr_cargo_competencias_detalle';

    // Clave primaria (aliased as _id para mantener compatibilidad con tu BaseModel)
    protected $primaryKey = 'th_carcompdet_id AS _id';

    // Campos que puedes insertar o actualizar (alias para usar en controladores/vistas)
    protected $camposPermitidos = [
        'th_carcomp_id AS th_carcomp_id',
        'th_carcompdet_subcompetencia AS subcompetencia',
        'th_carcompdet_descripcion AS descripcion',
        'th_carcompdet_nivel_utilizacion AS nivel_utilizacion',
        'th_carcompdet_nivel_contribucion AS nivel_contribucion',
        'th_carcompdet_nivel_habilidad AS nivel_habilidad',
        'th_carcompdet_nivel_maestria AS nivel_maestria',
        'th_carcompdet_indicador_medicion AS indicador_medicion',
        'th_carcompdet_comportamientos_observables AS comportamientos_observables',
        'th_carcompdet_orden AS orden',
        'th_carcompdet_estado AS estado',
        'th_carcompdet_fecha_creacion AS fecha_creacion',
        'th_carcompdet_fecha_modificacion AS fecha_modificacion'
    ];
}