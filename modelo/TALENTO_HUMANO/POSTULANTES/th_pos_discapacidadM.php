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
        'th_pos_dis_escala',
        'th_pos_dis_estado',
        'th_pos_dis_fecha_creacion',
        'th_pos_dis_fecha_modificacion',
        'th_pos_dis_sustituto AS sustituto',
        'id_dis_gravedad',
        'id_dis_porcentaje',
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
                pd.th_pos_dis_escala,
                pd.th_pos_dis_sustituto AS sustituto,
                pd.id_dis_gravedad,
                pd.id_dis_porcentaje,
                dis_gra.descripcion AS descripcion_dis_gravedad,
                dis_por.descripcion AS descripcion_dis_porcentaje
            FROM th_pos_discapacidad pd
            LEFT JOIN th_cat_discapacidad d
                ON pd.id_discapacidad = d.id_discapacidad
            LEFT JOIN th_cat_discapacidad_escala e
                ON pd.id_escala_dis = e.id_escala_dis
            LEFT JOIN th_cat_discapacidad_gravedad dis_gra
                ON pd.id_dis_gravedad = dis_gra.id_dis_gravedad
            LEFT JOIN th_cat_discapacidad_porcentaje dis_por
                ON pd.id_dis_porcentaje = dis_por.id_dis_porcentaje
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
