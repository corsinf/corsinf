<?php

require_once(dirname(__DIR__, 2) . '/GENERAL/BaseModel.php');

class cn_plaza_historialM extends BaseModel
{
    protected $tabla = 'cn_plaza_historial';
    protected $primaryKey = 'id_plaza_historial AS _id';
    protected $camposPermitidos = [
        'cn_pla_id',
        'id_plaza_estados',
        'id_usuario',
        'accion',
        'fecha_creacion',
    ];

    public function listar_historial_plaza($id_plaza = null, $id_historial = null)
    {
        $sql = "SELECT 
                h.id_plaza_historial AS _id,
                h.cn_pla_id,
                h.id_plaza_estados,
                h.id_usuario,
                h.accion,
                h.fecha_creacion,
                h.modificado_usuario,
                e.codigo,
                e.descripcion AS estado_descripcion,
                e.orden,
                e.editable,
                e.permite_postulacion,
                e.permite_evaluacion,
                e.visible_postulantes,
                e.roles
            FROM cn_plaza_historial h
            INNER JOIN cn_cat_plaza_estados e ON h.id_plaza_estados = e.id_plaza_estados
            WHERE h.is_delete = 0"; 

        if ($id_plaza !== null && $id_plaza !== '') {
            $id_plaza = intval($id_plaza);
            $sql .= " AND h.cn_pla_id = $id_plaza";
        }

        if ($id_historial !== null && $id_historial !== '') {
            $id_historial = intval($id_historial);
            $sql .= " AND h.id_plaza_historial = $id_historial";
        }

        $sql .= " ORDER BY h.fecha_creacion DESC;";

        return $this->db->datos($sql);
    }
}
