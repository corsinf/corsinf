<?php

/**
 * @deprecated Archivo dado de baja el 13/02/2026.
 * @note Este archivo se mantiene como respaldo, pero ya no se utilizará en producción.
 * @warning No modificar este archivo. Para cambios, referirse a la nueva implementación.
 */

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
     * Listar detalles (th_cat_requisitos_detalles) NO asignados a un th_car_req (cargo_requisito)
     */
    public function listar_detalles_no_asignados($th_car_req_id)
    {
        $id = intval($th_car_req_id);

        $sql = "
        SELECT d.id_requisitos_detalle,
               d.nombre,
               d.descripcion,
               d.tipo_dato,
               d.es_obligatorio,
               d.estado,
               d.fecha_creacion,
               d.fecha_modificacion
        FROM th_cat_requisitos_detalles d
        LEFT JOIN th_contr_union_cargo_requisito_detalle ud
            ON ud.th_reqdet_id = d.id_requisitos_detalle
            AND ud.th_car_req_id = $id
            AND ud.th_req_reqdet_estado = 1
        WHERE d.estado = 1
          AND ud.th_reqdet_id IS NULL
        ORDER BY d.nombre;
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
            SELECT d.id_requisitos_detalle,
                   d.nombre as nombre,
                   d.descripcion as descripcion,
                   d.tipo_dato,
                   d.es_obligatorio,
                   ud.th_req_reqdet_id,
                   ud.th_req_reqdet_estado,
                   ud.th_req_reqdet_fecha_creacion,
                   ud.th_req_reqdet_fecha_modificacion
            FROM th_contr_union_cargo_requisito_detalle ud
            INNER JOIN th_cat_requisitos_detalles d ON d.id_requisitos_detalle = ud.th_reqdet_id
            WHERE ud.th_car_req_id = {$id}
              AND ud.th_req_reqdet_estado = 1
              AND d.estado = 1
            ORDER BY d.nombre
        ";

        return $this->db->datos($sql);
    }
}
