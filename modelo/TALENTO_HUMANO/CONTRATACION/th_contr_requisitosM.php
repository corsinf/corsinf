<?php

require_once(dirname(__DIR__, 2) . '/GENERAL/BaseModel.php');

class th_contr_requisitosM extends BaseModel
{
    // Nombre real de la tabla en BD
    protected $tabla = 'th_contr_requisitos';

    // Clave primaria (se expone como _id)
    protected $primaryKey = 'th_req_id AS _id';

    // Campos que puedes insertar/actualizar
    protected $camposPermitidos = [
        'th_req_tipo as tipo',
        'th_req_descripcion as descripcion',
        'th_req_obligatorio as obligatorio',
        'th_req_ponderacion as ponderacion',
        'th_req_estado',
        'th_req_fecha_creacion',
        'th_req_fecha_modificacion'
    ];

    public function listar_requisitos_no_asignados($pla_id)
{
    $pla_id = intval($pla_id);

    $sql = "
        SELECT r.*
        FROM th_contr_requisitos r
        WHERE r.th_req_estado = 1
          AND NOT EXISTS (
              SELECT 1
              FROM th_contr_plaza_requisitos pr
              WHERE pr.th_req_id = r.th_req_id
                AND pr.th_pla_id = {$pla_id}
                AND pr.th_pr_estado = 1
          )
        ORDER BY r.th_req_tipo, r.th_req_descripcion
    ";

    return $this->db->datos($sql);
}



    
}