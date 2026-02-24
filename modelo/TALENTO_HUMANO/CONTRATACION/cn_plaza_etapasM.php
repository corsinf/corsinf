<?php

require_once(dirname(__DIR__, 2) . '/GENERAL/BaseModel.php');

class cn_plaza_etapasM extends BaseModel
{
    protected $tabla = 'cn_plaza_etapas';

    protected $primaryKey = 'cn_plaet_id AS _id';

    protected $camposPermitidos = [
        'cn_pla_id',
        'id_etapa',
        'cn_plaet_orden',
        'cn_plaet_obligatoria',
        'estado',
        'fecha_creacion',
    ];

    public function listar_etapas_por_plaza($plaza_id)
    {
        $plaza_id = intval($plaza_id);
        $sql = "
        SELECT
            pe.cn_plaet_id          AS _id,
            pe.cn_pla_id,
            pe.id_etapa,
            pe.cn_plaet_orden,
            pe.cn_plaet_obligatoria,
            pe.estado,
            pe.fecha_creacion,
            ce.codigo               AS etapa_codigo,
            ce.nombre               AS etapa_nombre,
            ce.tipo                 AS etapa_tipo,
            ce.requiere_puntaje     AS etapa_requiere_puntaje,
            ce.es_final             AS etapa_es_final
        FROM cn_plaza_etapas pe
        INNER JOIN cn_cat_plaza_etapas ce ON pe.id_etapa = ce.id_etapa
        WHERE pe.cn_pla_id  = $plaza_id
          AND pe.estado      = 1
          AND ce.estado      = 1
        ORDER BY pe.cn_plaet_orden ASC
    ";
        return $this->db->datos($sql);
    }

    
    public function buscar_existente($id_plaza, $id_etapa)
    {
        $id_plaza = intval($id_plaza);
        $id_etapa = intval($id_etapa);

        $sql = "
        SELECT cn_plaet_id
        FROM cn_plaza_etapas
        WHERE cn_pla_id = $id_plaza
          AND id_etapa  = $id_etapa
    ";

        $resultado = $this->db->datos($sql);

        // Retorna el cn_plaet_id si existe, o null si no
        return !empty($resultado) ? $resultado[0]['cn_plaet_id'] : null;
    }
}
