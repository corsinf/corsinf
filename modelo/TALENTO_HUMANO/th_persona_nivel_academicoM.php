<?php

require_once(dirname(__DIR__, 1) . '/GENERAL/BaseModel.php');

class th_persona_nivel_academicoM extends BaseModel
{
    // Nombre real de la tabla en la base de datos
    protected $tabla = 'th_persona_nivel_academico';

    // Clave primaria (aliased as _id para mantener compatibilidad con tu BaseModel)
    protected $primaryKey = 'th_niv_aca_id AS _id';

    // Campos que puedes insertar o actualizar (alias para usar en controladores/vistas)
    protected $camposPermitidos = [
        'th_per_id AS per_id',
        'th_niv_aca_tipo_nivel AS tipo_nivel',
        'th_niv_aca_nivel_academico AS nivel_academico',
        'th_niv_aca_titulo AS titulo',
        'th_niv_aca_registro_senescyt AS registro_senescyt',
        'th_niv_aca_estado AS estado',
        'th_niv_aca_fecha_creacion AS fecha_creacion',
        'th_niv_aca_fecha_modificacion AS fecha_modificacion'
    ];
    
}