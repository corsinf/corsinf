<?php

require_once(dirname(__DIR__, 2) . '/GENERAL/BaseModel.php');

class th_pos_idiomasM extends BaseModel
{
    protected $tabla = 'th_pos_idiomas';
    protected $primaryKey = 'th_idi_id AS _id';

    protected $camposPermitidos = [
        'th_pos_id',
        'id_idiomas',
        'id_idiomas_nivel',
        'th_idi_institucion',
        'th_idi_fecha_inicio_idioma',
        'th_idi_fecha_fin_idioma',
        'th_idi_fecha_creacion',
        'th_idi_fecha_modificacion',
        'th_idi_estado',
        'th_idi_actualidad',
        'id_certificacion',
    ];

    public function listar_idiomas_completo($th_pos_id = null, $th_idi_id = null)
    {
        $sql = "
    SELECT
        i.th_idi_id AS _id,
        i.th_pos_id,
        i.id_idiomas,
        i.id_idiomas_nivel,
        i.id_certificacion,
        i.th_idi_institucion,
        i.th_idi_fecha_inicio_idioma,
        i.th_idi_fecha_fin_idioma,
        i.th_idi_actualidad,
        i.th_idi_estado,
        cat.descripcion AS nombre_idioma,
        niv.descripcion AS nivel_idioma_descripcion,
        cert.descripcion AS nombre_certificacion
    FROM th_pos_idiomas i
    LEFT JOIN th_cat_idiomas cat 
        ON i.id_idiomas = cat.id_idiomas
    LEFT JOIN th_cat_idiomas_nivel niv 
        ON i.id_idiomas_nivel = niv.id_idiomas_nivel
    LEFT JOIN th_cat_tipo_certficacion cert 
        ON i.id_certificacion = cert.id_certificacion
    WHERE i.th_idi_estado = 1
    ";

        // Filtro por ID de Postulante (para el listado general)
        if (!empty($th_pos_id)) {
            $th_pos_id = intval($th_pos_id);
            $sql .= " AND i.th_pos_id = $th_pos_id";
        }

        // Filtro por ID de registro específico (para editar)
        if (!empty($th_idi_id)) {
            $th_idi_id = intval($th_idi_id);
            $sql .= " AND i.th_idi_id = $th_idi_id";
        }

        $sql .= " ORDER BY cat.descripcion ASC";

        return $this->db->datos($sql);
    }
}
