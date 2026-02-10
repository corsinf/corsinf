<?php

require_once(dirname(__DIR__, 2) . '/GENERAL/BaseModel.php');

class th_cat_requisitosM extends BaseModel
{
    // Nombre real de la tabla en BD
    protected $tabla = 'th_cat_requisitos';

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
        FROM th_cat_requisitos r
        LEFT JOIN th_contr_plaza_requisitos pr
            ON pr.th_req_id = r.th_req_id
            AND pr.th_pla_id = $pla_id
            AND pr.th_pr_estado = 1
        WHERE r.th_req_estado = 1
          AND pr.th_req_id IS NULL
        ORDER BY r.th_req_tipo, r.th_req_descripcion;
    ";

    return $this->db->datos($sql);
}



    
}