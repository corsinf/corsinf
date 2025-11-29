<?php

require_once(dirname(__DIR__, 2) . '/GENERAL/BaseModel.php');

class th_contr_union_cargo_requisito_detalleM extends BaseModel
{
    // Nombre real de la tabla
    protected $tabla = 'th_contr_union_cargo_requisito_detalle';

    // Primary key (alias _id para compatibilidad con DataTables / select2)
    protected $primaryKey = 'th_req_reqdet_id AS _id';

    // Campos permitidos para insertar/editar
    protected $camposPermitidos = [
        'th_car_req_id',
        'th_reqdet_id',
        'th_req_reqdet_estado',
        'th_req_reqdet_fecha_creacion',
        'th_req_reqdet_fecha_modificacion'
    ];

    /**
     * Listar detalles (th_contr_requisitos_detalles) NO asignados a un th_car_req (cargo_requisito)
     */
    public function listar_detalles_no_asignados($th_car_req_id)
    {
        $id = intval($th_car_req_id);

        $sql = "
            SELECT d.th_reqdet_id,
                   d.th_reqdet_nombre,
                   d.th_reqdet_descripcion,
                   d.th_reqdet_tipo_dato,
                   d.th_reqdet_obligatorio,
                   d.th_reqdet_estado,
                   d.th_reqdet_fecha_creacion,
                   d.th_reqdet_fecha_modificacion
            FROM th_contr_requisitos_detalles d
            WHERE d.th_reqdet_estado = 1
              AND NOT EXISTS (
                  SELECT 1
                  FROM th_contr_union_cargo_requisito_detalle ud
                  WHERE ud.th_reqdet_id = d.th_reqdet_id
                    AND ud.th_car_req_id = {$id}
                    AND ud.th_req_reqdet_estado = 1
              )
            ORDER BY d.th_reqdet_nombre
        ";

        return $this->db->datos($sql);
    }

    /**
     * Listar detalles asignados a un th_car_req (cargo_requisito)
     */
    public function listar_detalles_asignados($th_car_req_id)
    {
        $id = intval($th_car_req_id);

        $sql = "
            SELECT d.th_reqdet_id,
                   d.th_reqdet_nombre as nombre,
                   d.th_reqdet_descripcion as descripcion,
                   d.th_reqdet_tipo_dato,
                   d.th_reqdet_obligatorio,
                   ud.th_req_reqdet_id,
                   ud.th_req_reqdet_estado,
                   ud.th_req_reqdet_fecha_creacion,
                   ud.th_req_reqdet_fecha_modificacion
            FROM th_contr_union_cargo_requisito_detalle ud
            INNER JOIN th_contr_requisitos_detalles d ON d.th_reqdet_id = ud.th_reqdet_id
            WHERE ud.th_car_req_id = {$id}
              AND ud.th_req_reqdet_estado = 1
              AND d.th_reqdet_estado = 1
            ORDER BY d.th_reqdet_nombre
        ";

        return $this->db->datos($sql);
    }

    
}