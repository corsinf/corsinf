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
        'th_est_estado_laboral',
        'th_est_fecha_contratacion',
        'th_est_fecha_salida',
        'th_est_estado',
        'th_est_fecha_creacion',
        'th_est_fecha_modificacion',
    ];

    public function listar_estado_laboral_por_persona($id)
    {
        $sql = "SELECT 
                    el.th_est_id AS _id,
                    el.th_per_id,
                    el.id_cargo,
                    el.id_seccion,
                    el.th_est_estado_laboral,
                    el.th_est_fecha_contratacion,
                    el.th_est_fecha_salida,
                    c.th_cat_car_descripcion AS cargo_descripcion,
                    s.th_cat_sec_descripcion AS seccion_descripcion
                FROM 
                    _talentoh.th_per_estado_laboral el
                LEFT JOIN 
                    _talentoh.th_cat_cargos c ON el.id_cargo = c.th_cat_car_id
                LEFT JOIN 
                    _talentoh.th_cat_secciones s ON el.id_seccion = s.th_cat_sec_id
                WHERE 
                    el.th_per_id = '$id'
                    AND el.th_est_estado = 1
                ORDER BY 
                    el.th_est_fecha_contratacion DESC";

        return $this->db->datos($sql);
    }

    public function listar_estado_laboral_por_id($id)
    {
        $sql = "SELECT 
                    th_est_id AS _id,
                    th_per_id,
                    id_cargo,
                    id_seccion,
                    th_est_estado_laboral,
                    th_est_fecha_contratacion,
                    th_est_fecha_salida
                FROM 
                    _talentoh.th_per_estado_laboral
                WHERE 
                    th_est_id = '$id'
                    AND th_est_estado = 1";

        return $this->db->datos($sql);
    }
}
