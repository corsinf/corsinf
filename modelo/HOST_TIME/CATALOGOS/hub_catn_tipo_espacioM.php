<?php

require_once(dirname(__DIR__, 2) . '/GENERAL/BaseModel.php');

class hub_catn_tipo_espacioM extends BaseModel
{
    protected $tabla = 'hub_catn_tipo_espacios';

    protected $primaryKey = 'id_tipo_espacio AS _id';

    protected $camposPermitidos = [
        'nombre',
        'descripcion',
        'id_unidad_tiempo',
        'es_exclusivo',
        'is_deleted',
        'id_usuario_crea',
        'fecha_creacion',
        'id_usuario_modifica',
        'fecha_modificacion'
    ];
    public function listar_tipo_espacio($id = '')
    {
        $sql = "
    SELECT 
        te.id_tipo_espacio AS _id,
        te.nombre,
        te.descripcion,
        te.id_unidad_tiempo,
        te.es_exclusivo,
        te.is_deleted,
        te.fecha_creacion,
        ut.nombre AS nombre_unidad_tiempo,
        ut.prefijo

    FROM hub_catn_tipo_espacios te

    LEFT JOIN hub_cats_unidad_tiempo ut 
        ON te.id_unidad_tiempo = ut.id_unidad_tiempo

    WHERE te.is_deleted = 0
    ";
        if ($id != '') {
            $sql .= " AND te.id_tipo_espacio = " . intval($id);
        }

        $sql .= " ORDER BY te.fecha_creacion DESC";

        return $this->db->datos($sql);
    }
}
