<?php

require_once(dirname(__DIR__, 1) . '/GENERAL/BaseModel.php');

class contrPlazasM extends BaseModel
{
    // Nombre real de la tabla en BD
    protected $tabla = 'th_contr_plazas';

    // Clave primaria (se expone como _id, igual que en tus otros modelos)
    protected $primaryKey = 'th_pla_id AS _id';

    // Campos que puedes insertar/actualizar
    protected $camposPermitidos = [
        'th_pla_titulo',
        'th_pla_descripcion',
        'th_pla_tipo',
        'th_pla_num_vacantes',
        'th_pla_fecha_publicacion',
        'th_pla_fecha_cierre',
        'th_pla_jornada_id',
        'th_pla_salario_min',
        'th_pla_salario_max',
        'th_pla_tiempo_contrato',
        'th_pla_prioridad_interna',
        'th_pla_requiere_documentos',
        'th_pla_responsable_persona_id',
        'th_pla_observaciones',
        'th_pla_estado',
        'th_pla_fecha_creacion',
        'th_pla_fecha_modificacion',
    ];
}
