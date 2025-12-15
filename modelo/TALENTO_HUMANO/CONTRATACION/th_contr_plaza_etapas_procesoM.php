<?php

require_once(dirname(__DIR__, 2) . '/GENERAL/BaseModel.php');

class th_contr_plaza_etapas_procesoM extends BaseModel
{
    protected $tabla = 'th_contr_plaza_etapas';

    protected $primaryKey = 'th_pla_eta_id AS _id';

    protected $camposPermitidos = [
        'th_pla_id AS pla_id',
        'th_eta_id AS eta_id',
        'th_pla_eta_estado AS estado',
        'th_pla_eta_fecha_creacion AS fecha_creacion',
        'th_pla_eta_modificacion AS fecha_modificacion'
    ];

    
    public function listar_etapas_no_asignadas($pla_id)
    {
         $pla_id = intval($pla_id);

    $sql = "
        SELECT e.*
        FROM th_contr_etapas_proceso e
        LEFT JOIN th_contr_plaza_etapas pe
            ON pe.th_eta_id = e.th_etapa_id
            AND pe.th_pla_id = $pla_id
            AND pe.th_pla_eta_estado = 1
        WHERE e.th_etapa_estado = 1
          AND pe.th_eta_id IS NULL
        ORDER BY e.th_etapa_orden, e.th_etapa_nombre;
    ";

    return $this->db->datos($sql);
    }

    public function listar_etapas_por_plaza($pla_id)
    {
        $pla_id = intval($pla_id);

        $sql = "
        SELECT
            pe.th_pla_eta_id,
            pe.th_pla_id,
            pe.th_eta_id,
            pe.th_pla_eta_estado,
            pe.th_pla_eta_fecha_creacion,
            pe.th_pla_eta_modificacion,
            e.th_etapa_id,
            e.th_etapa_nombre,
            e.th_etapa_tipo,
            e.th_etapa_orden,
            e.th_etapa_obligatoria,
            e.th_etapa_descripcion,
            e.th_etapa_estado,
            e.th_etapa_fecha_creacion
        FROM th_contr_plaza_etapas pe
        INNER JOIN th_contr_etapas_proceso e ON pe.th_eta_id = e.th_etapa_id
        WHERE pe.th_pla_id = {$pla_id}
          AND pe.th_pla_eta_estado = 1
          AND e.th_etapa_estado = 1
        ORDER BY e.th_etapa_orden, e.th_etapa_nombre
        ";

        return $this->db->datos($sql);
    }
}