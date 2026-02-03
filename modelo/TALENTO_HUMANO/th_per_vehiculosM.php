<?php

require_once(dirname(__DIR__, 1) . '/GENERAL/BaseModel.php');

class th_per_vehiculosM extends BaseModel
{
    protected $tabla = 'th_per_vehiculos';
    protected $primaryKey = 'th_per_veh_id AS _id';

    protected $camposPermitidos = [
        'th_per_id AS th_per_id',
        'id_vehiculo AS id_vehiculo',
        'th_per_veh_placa_original AS placa_original',
        'th_per_veh_placa_sintesis AS placa_sintesis',
        'th_per_veh_nombre_propietario AS nombre_propietario',
        'th_per_veh_estado AS estado',
        'th_per_veh_fecha_creacion AS fecha_creacion',
        'th_per_veh_fecha_modificacion AS fecha_modificacion',
    ];

    public function listar_vehiculos_con_tipo($id = null)
    {
        $sql = "
        SELECT
            pv.th_per_veh_id AS _id,
            pv.th_per_id,
            pv.id_vehiculo,
            pv.th_per_veh_placa_original,
            pv.th_per_veh_placa_sintesis,
            pv.th_per_veh_estado,
            pv.th_per_veh_nombre_propietario,
            pv.th_per_veh_fecha_creacion,
            pv.th_per_veh_fecha_modificacion,
            tv.descripcion AS tipo_vehiculo_descripcion
        FROM th_per_vehiculos pv
        LEFT JOIN th_cat_tipo_vehiculo tv
            ON pv.id_vehiculo = tv.id_vehiculo
    ";

        if (!empty($id)) {
            $id = intval($id);
            $sql .= " WHERE pv.th_per_veh_id = $id";
        }

        return $this->db->datos($sql);
    }

    public function listar_vehiculos_por_persona_con_tipo($th_per_id = null)
    {
        $sql = "
        SELECT
            pv.th_per_veh_id AS _id,
            pv.th_per_id,
            pv.id_vehiculo,
            pv.th_per_veh_placa_original,
            pv.th_per_veh_placa_sintesis,
            pv.th_per_veh_nombre_propietario,
            pv.th_per_veh_estado,
            pv.th_per_veh_fecha_creacion,
            pv.th_per_veh_fecha_modificacion,
            tv.descripcion AS tipo_vehiculo_descripcion
        FROM th_per_vehiculos pv
        LEFT JOIN th_cat_tipo_vehiculo tv
            ON pv.id_vehiculo = tv.id_vehiculo
        WHERE pv.th_per_veh_estado = 1
    ";

        if (!empty($th_per_id)) {
            $th_per_id = intval($th_per_id);
            $sql .= " AND pv.th_per_id = $th_per_id";
        }

        $sql .= " ORDER BY pv.th_per_veh_placa_original ASC";

        return $this->db->datos($sql);
    }
}
