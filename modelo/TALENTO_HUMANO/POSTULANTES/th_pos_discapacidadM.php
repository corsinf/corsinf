<?php
require_once(dirname(__DIR__, 2) . '/GENERAL/BaseModel.php');

class th_pos_discapacidadM extends BaseModel
{
    protected $tabla = 'th_pos_discapacidad';
    protected $primaryKey = 'th_pos_dis_id AS _id';

    protected $camposPermitidos = [
        'th_pos_id',
        'id_discapacidad',
        'id_escala_dis',
        'th_pos_dis_porcentaje',
        'th_pos_dis_escala',
        'th_pos_dis_estado',
        'th_pos_dis_fecha_creacion',
        'th_pos_dis_fecha_modificacion',
        'th_pos_dis_sustituto AS sustituto'
    ];

    public function listar_discapacidad_postulante($th_pos_id = null, $th_pos_dis_id = null)
    {
        $sql =
            "SELECT
                pd.th_pos_dis_id AS _id,
                pd.th_pos_id,
                pd.id_discapacidad,
                pd.id_escala_dis,
                e.descripcion AS escala_discapacidad,
                d.descripcion AS discapacidad,
                pd.th_pos_dis_porcentaje,
                pd.th_pos_dis_escala,
                pd.th_pos_dis_sustituto AS sustituto
            FROM th_pos_discapacidad pd
            INNER JOIN th_cat_discapacidad d
                ON pd.id_discapacidad = d.id_discapacidad
            INNER JOIN th_cat_discapacidad_escala e
                ON pd.id_escala_dis = e.id_escala_dis
            WHERE th_pos_dis_estado = 1 
            ";

        // Filtro por ID de Postulante (listado general)
        if (!empty($th_pos_id)) {
            $th_pos_id = intval($th_pos_id);
            $sql .= " AND pd.th_pos_id = $th_pos_id";
        }

        // Filtro por ID específico de registro (edición)
        if (!empty($th_pos_dis_id)) {
            $th_pos_dis_id = intval($th_pos_dis_id);
            $sql .= " AND pd.th_pos_dis_id = $th_pos_dis_id";
        }

        $sql .= " ORDER BY d.descripcion ASC";

        return $this->db->datos($sql);
    }
}
