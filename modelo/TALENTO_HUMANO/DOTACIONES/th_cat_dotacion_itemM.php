<?php

require_once(dirname(__DIR__, 2) . '/GENERAL/BaseModel.php');

class th_cat_dotacion_itemM extends BaseModel
{
    protected $tabla = 'th_cat_dotacion_item';

    protected $primaryKey = 'id_dotacion_item AS _id';

    protected $camposPermitidos = [
        'id_dotacion AS id_dotacion',
        'nombre AS nombre',
        'req_talla AS req_talla',
        'tipo_item AS tipo_item',
        'estado AS estado',
        'fecha_creacion AS fecha_creacion'
    ];

    public function buscar_items_dotacion($parametros)
    {
        $query = isset($parametros['query']) ? trim($parametros['query']) : '';
        $id_dotacion = intval($parametros['id_dotacion']); // ID de la categoría (Uniforme, EPP, etc.)
        $th_dot_id = isset($parametros['th_dot_id']) ? intval($parametros['th_dot_id']) : 0;

        $sql = "
        SELECT 
            i.id_dotacion_item AS id,
            i.nombre AS text,
            i.req_talla,
            i.tipo_item,
            CASE 
                WHEN d.th_dotd_id IS NOT NULL THEN 1 
                ELSE 0 
            END AS ya_asignado
        FROM th_cat_dotacion_item i
        LEFT JOIN th_per_dotacion_detalle d 
            ON i.id_dotacion_item = d.id_dotacion_item 
            AND d.th_dot_id = $th_dot_id
            AND d.th_dotd_estado = 1
        WHERE i.estado = 1 
        AND i.id_dotacion = $id_dotacion
    ";

        if ($query !== '') {
            $sql .= " AND i.nombre LIKE '%" . addslashes($query) . "%'";
        }

        // Opcional: Si quieres que solo aparezcan los que NO han sido asignados aún
        if ($th_dot_id > 0) {
            $sql .= " AND d.th_dotd_id IS NULL";
        }

        $sql .= " ORDER BY i.nombre ASC";

        return $this->db->datos($sql);
    }
}
