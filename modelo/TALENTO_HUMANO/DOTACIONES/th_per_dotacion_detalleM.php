<?php

require_once(dirname(__DIR__, 2) . '/GENERAL/BaseModel.php');

class th_per_dotacion_detalleM extends BaseModel
{
    protected $tabla = 'th_per_dotacion_detalle';

    protected $primaryKey = 'th_dotd_id AS _id';

    protected $camposPermitidos = [
        'th_dot_id AS th_dot_id',
        'id_dotacion_item AS id_dotacion_item',
        'id_talla AS id_talla',
        'th_dotd_cantidad AS th_dotd_cantidad',
        'th_dotd_estado_item AS th_dotd_estado_item',
        'th_dotd_estado AS th_dotd_estado',
        'th_dotd_fecha_creacion AS th_dotd_fecha_creacion',
        'th_dotd_fecha_modificacion AS th_dotd_fecha_modificacion'
    ];

    public function listar_detalle_dotacion($th_dot_id)
    {
        $th_dot_id = intval($th_dot_id);

        $sql = "
        SELECT 
            d.th_dotd_id,
            d.th_dot_id,
            d.id_dotacion_item,
            i.nombre AS nombre_item,
            i.tipo_item,
            d.id_talla,
            ISNULL(t.codigo, 'N/A') AS codigo_talla,
            ISNULL(t.descripcion, 'Sin Talla') AS descripcion_talla,
            d.th_dotd_cantidad,
            d.th_dotd_estado_item
        FROM th_per_dotacion_detalle d
        INNER JOIN th_cat_dotacion_item i 
            ON d.id_dotacion_item = i.id_dotacion_item
        LEFT JOIN th_cat_talla t 
            ON d.id_talla = t.id_talla
        WHERE d.th_dot_id = $th_dot_id
          AND d.th_dotd_estado = 1
        ORDER BY d.th_dotd_id DESC
    ";

        return $this->db->datos($sql);
    }
}
