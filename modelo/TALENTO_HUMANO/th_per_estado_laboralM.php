<?php

require_once(dirname(__DIR__, 1) . '/GENERAL/BaseModel.php');

class th_per_estado_laboralM extends BaseModel
{
    protected $tabla = 'th_per_estado_laboral';

    protected $primaryKey = 'th_est_id AS _id';

    protected $camposPermitidos = [
        'th_per_id',
        'id_cargo',
        'id_seccion',
        'id_nomina',
        'th_est_check_estado_laboral',
        'th_est_remuneracion',
        'th_est_estado_laboral',
        'th_est_fecha_contratacion',
        'th_est_fecha_salida',
        'th_est_estado',
        'th_est_fecha_creacion',
        'th_est_fecha_modificacion'
    ];

    public function listar_estado_laboral_por_persona($id)
    {
        $id = intval($id);

        $sql = "
            SELECT 
                el.th_est_id AS _id,
                el.th_per_id,
                el.id_cargo,
                el.id_seccion,
                el.id_nomina,
                el.th_est_check_estado_laboral,
                el.th_est_remuneracion,
                el.th_est_estado_laboral,
                el.th_est_fecha_contratacion,
                el.th_est_fecha_salida,
                c.nombre AS cargo_nombre,
                c.descripcion AS cargo_descripcion,
                s.descripcion AS seccion_descripcion
            FROM th_per_estado_laboral el
            LEFT JOIN th_cat_cargo c 
                ON el.id_cargo = c.id_cargo
            LEFT JOIN th_cat_seccion s 
                ON el.id_seccion = s.id_seccion
            WHERE el.th_per_id = $id
              AND el.th_est_estado = 1
            ORDER BY el.th_est_fecha_contratacion DESC
        ";

        return $this->db->datos($sql);
    }

    public function listar_estado_laboral_por_id($id)
    {
        $id = intval($id);

        $sql = "
            SELECT 
                el.th_est_id AS _id,
                el.th_per_id,
                el.id_cargo,
                el.id_seccion,
                el.id_nomina,
                el.th_est_check_estado_laboral,
                el.th_est_remuneracion,
                el.th_est_estado_laboral,
                el.th_est_fecha_contratacion,
                el.th_est_fecha_salida,
                c.id_cargo,
                c.nombre AS cargo_nombre,
                c.descripcion AS cargo_descripcion,
                s.id_seccion,
                s.descripcion AS seccion_descripcion
            FROM th_per_estado_laboral el
            LEFT JOIN th_cat_cargo c 
                ON el.id_cargo = c.id_cargo
            LEFT JOIN th_cat_seccion s 
                ON el.id_seccion = s.id_seccion
            WHERE el.th_est_id = $id
              AND el.th_est_estado = 1
        ";

        return $this->db->datos($sql);
    }
}
