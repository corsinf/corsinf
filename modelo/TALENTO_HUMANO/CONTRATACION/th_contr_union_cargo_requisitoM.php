<?php

require_once(dirname(__DIR__, 2) . '/GENERAL/BaseModel.php');

class th_contr_union_cargo_requisitoM extends BaseModel
{
    // Nombre real de la tabla
    protected $tabla = 'th_contr_union_cargo_requisito';

    // Primary key (con alias _id opcional para uso con DataTables o Select2)
    protected $primaryKey = 'th_carreq_id AS _id';

    // Campos permitidos para insertar o actualizar
    protected $camposPermitidos = [
        'th_car_id',
        'th_car_req_id',
        'th_carreq_estado',
        'th_carreq_fecha_creacion',
        'th_carreq_fecha_modificacion'
    ];

    public function listar_requisitos_no_asignados($car_id)
{
    $car_id = intval($car_id);

    $sql = "
        SELECT r.*
        FROM th_contr_cargo_requisitos r
        WHERE r.th_car_req_estado = 1
          AND NOT EXISTS (
              SELECT 1
              FROM th_contr_union_cargo_requisito u
              WHERE u.th_car_req_id = r.th_car_req_id
                AND u.th_car_id = {$car_id}
                AND u.th_carreq_estado = 1
          )
        ORDER BY r.th_car_req_nombre
    ";

    return $this->db->datos($sql);
}

public function listar_requisitos_asignados($car_id)
{
    $car_id = intval($car_id);

    $sql = "
        SELECT 
            u.th_carreq_id,               
            r.th_car_req_id,                    
            r.th_car_req_nombre AS nombre,
            r.th_car_req_descripcion AS descripcion,
            u.th_carreq_estado,
            u.th_carreq_fecha_creacion,
            u.th_carreq_fecha_modificacion
        FROM th_contr_union_cargo_requisito u
        INNER JOIN th_contr_cargo_requisitos r 
            ON r.th_car_req_id = u.th_car_req_id
        WHERE u.th_car_id = {$car_id}
          AND u.th_carreq_estado = 1
          AND r.th_car_req_estado = 1
        ORDER BY r.th_car_req_nombre;
    ";

    return $this->db->datos($sql);
}




}