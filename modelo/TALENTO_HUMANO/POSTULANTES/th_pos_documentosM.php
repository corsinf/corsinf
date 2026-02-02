<?php

require_once(dirname(__DIR__, 2) . '/GENERAL/BaseModel.php');

class th_pos_documentosM extends BaseModel
{
    protected $tabla = 'th_pos_documentos';
    protected $primaryKey = 'th_poi_id AS _id';

    protected $camposPermitidos = [
        'th_poi_id',
        'th_pos_id',
        'id_documento',
        'th_poi_ruta_archivo',
        'th_poi_fecha_creacion',
        'th_poi_fecha_modificacion',
        'th_poi_estado',
    ];

    public function listar_documentos_postulante($th_pos_id = null, $th_poi_id = null)
    {
        $sql = "
    SELECT
        pd.th_poi_id AS _id,
        pd.th_pos_id,
        pd.id_documento,
        cat.descripcion AS nombre_documento,
        pd.th_poi_ruta_archivo,
        pd.th_poi_estado AS estado,
        pd.th_poi_fecha_creacion
    FROM th_pos_documentos pd 
    INNER JOIN th_cat_doc_identidad cat
        ON pd.id_documento = cat.id_documento
    WHERE pd.th_poi_estado = 1
    ";

        // Filtro por ID de Postulante (listado general de documentos del usuario)
        if (!empty($th_pos_id)) {
            $th_pos_id = intval($th_pos_id);
            $sql .= " AND pd.th_pos_id = $th_pos_id";
        }

        // Filtro por ID de Documento específico (para el modal de edición o vista previa)
        if (!empty($th_poi_id)) {
            $th_poi_id = intval($th_poi_id);
            $sql .= " AND pd.th_poi_id = $th_poi_id";
        }

        $sql .= " ORDER BY cat.descripcion ASC";

        return $this->db->datos($sql);
    }
}
