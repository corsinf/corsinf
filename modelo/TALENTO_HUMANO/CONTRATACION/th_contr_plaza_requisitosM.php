<?php

require_once(dirname(__DIR__, 2) . '/GENERAL/BaseModel.php');

class th_contr_plaza_requisitosM extends BaseModel
{
    // Nombre real de la tabla en la base de datos
    protected $tabla = 'th_contr_plaza_requisitos';

    // Clave primaria (alias a _id para mantener consistencia)
    protected $primaryKey = 'th_pr_id AS _id';

    // Campos que puedes insertar o actualizar (mapeados a nombres amigables)
    protected $camposPermitidos = [
        'th_pla_id AS pla_id',
        'th_req_id AS req_id',
        'th_car_estado AS estado',
        'th_pr_fecha_creacion AS fecha_creacion',
        'th_pr_fecha_modificacion AS fecha_modificacion'
    ];

    public function listar_requisitos_no_asignados($pla_id)
{
    $sql = "
    SELECT r.*
    FROM th_contr_requisitos r
    WHERE r.th_req_estado = 1
      AND NOT EXISTS (
        SELECT 1
        FROM th_contr_plaza_requisitos pr
        WHERE pr.th_req_id = r.th_req_id
          AND pr.th_pla_id = {$pla_id}
          AND pr.th_car_estado = 1
      )
    ORDER BY r.th_req_descripcion ASC
    ";

     return $this->db->datos($sql);
}

function listar_requisitos_por_plaza($pla_id)
{
    $pla_id = intval($pla_id);

    $sql = "
    SELECT
        r.th_req_id,
        r.th_req_tipo,
        r.th_req_descripcion,
        r.th_req_obligatorio,
        pr.th_car_estado,
        pr.th_pr_fecha_creacion
    FROM th_contr_plaza_requisitos pr
    INNER JOIN th_contr_requisitos r
        ON pr.th_req_id = r.th_req_id
    WHERE pr.th_pla_id = '$pla_id'
      AND pr.th_car_estado = 1
      AND r.th_req_estado = 1
    ORDER BY r.th_req_tipo, r.th_req_descripcion
    ";

    return $this->db->datos($sql);
}

}