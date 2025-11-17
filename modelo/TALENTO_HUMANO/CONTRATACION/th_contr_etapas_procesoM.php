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
    public function listar_etapa_plaza($id_etapa = '', $id_plaza = '')
{
    $sql = "
        SELECT
            e.th_etapa_id                       AS _id,
            e.th_pla_id                         AS th_pla_id,

            -- nombre corregido
            e.th_etapa_nombre                   AS nombre,

            e.th_etapa_tipo                     AS tipo,
            e.th_etapa_orden                    AS orden,
            e.th_etapa_obligatoria              AS obligatoria,
            e.th_etapa_descripcion              AS descripcion,
            e.th_etapa_estado                   AS estado,
            e.th_etapa_fecha_creacion           AS fecha_creacion,
            e.th_etapa_fecha_modificacion       AS fecha_modificacion,

            -- Datos de la plaza asociada
            p.th_pla_id                         AS plaza_id,
            p.th_pla_titulo                     AS plaza_titulo,
            p.th_pla_descripcion                AS plaza_descripcion,
            p.th_pla_tipo                       AS plaza_tipo,
            p.th_pla_num_vacantes               AS plaza_num_vacantes,
            p.th_pla_fecha_publicacion          AS plaza_fecha_publicacion,
            p.th_pla_fecha_cierre               AS plaza_fecha_cierre,
            p.th_pla_jornada_id                 AS plaza_jornada_id,
            p.th_pla_salario_min                AS plaza_salario_min,
            p.th_pla_salario_max                AS plaza_salario_max,
            p.th_pla_estado                     AS plaza_estado
        FROM th_contr_etapas_proceso e
        INNER JOIN th_contr_plazas p ON e.th_pla_id = p.th_pla_id
        WHERE 1 = 1
    ";

    // Filtro de estado activo
    $sql .= " AND e.th_etapa_estado = 1";

    // Filtro por ID de etapa (opcional)
    if ($id_etapa !== '' && $id_etapa !== null) {
        $id = (int) $id_etapa;
        $sql .= " AND e.th_etapa_id = {$id}";
    }

    // Filtro por ID de plaza (opcional)
    if ($id_plaza !== '' && $id_plaza !== null) {
        $idp = (int) $id_plaza;
        $sql .= " AND e.th_pla_id = {$idp}";
    }

    $sql .= " ORDER BY e.th_etapa_orden ASC, e.th_etapa_fecha_creacion DESC";

    return $this->db->datos($sql);
}


}