<?php

require_once(dirname(__DIR__, 2) . '/GENERAL/BaseModel.php');

class th_contr_cargo_aspectos_intrinsecosM extends BaseModel
{
    // Nombre real de la tabla
    protected $tabla = 'th_contr_cargo_aspectos_intrinsecos';

    // Clave primaria
    protected $primaryKey = 'th_carasp_id AS _id';

    // Campos permitidos para INSERT o UPDATE
    protected $camposPermitidos = [
        'th_car_id AS car_id',
        'th_carasp_nivel_cargo AS nivel_cargo',
        'th_carasp_subordinacion AS subordinacion',
        'th_carasp_supervision AS supervision',
        'th_carasp_comunicaciones_colaterales AS comunicaciones_colaterales',
        'th_carasp_estado AS estado',
        'th_carasp_fecha_creacion AS fecha_creacion',
        'th_carasp_fecha_modificacion AS fecha_modificacion',
        'th_carasp_subordinacion_id',
        'th_carasp_supervision_id',
        'th_carasp_comunicaciones_id'
    ];

    public function listar_aspecto_cargo_completo($id_cargo = '')
{
    $sql = "
    SELECT 
        a.th_carasp_id,
        a.th_car_id,
        a.th_carasp_nivel_cargo,
        a.th_carasp_subordinacion,
        a.th_carasp_subordinacion_id,
        c_sub.th_car_nombre as subordinacion_cargo_nombre,
        c_sub.th_car_descripcion as subordinacion_cargo_descripcion,
        a.th_carasp_supervision,
        a.th_carasp_supervision_id,
        c_sup.th_car_nombre as supervision_cargo_nombre,
        c_sup.th_car_descripcion as supervision_cargo_descripcion,
        a.th_carasp_comunicaciones_colaterales,
        a.th_carasp_comunicaciones_id,
        c_com.th_car_nombre as comunicaciones_cargo_nombre,
        c_com.th_car_descripcion as comunicaciones_cargo_descripcion,
        a.th_carasp_estado,
        a.th_carasp_fecha_creacion,
        a.th_carasp_fecha_modificacion,
        c.th_car_nombre as cargo_nombre,
        c.th_car_descripcion as cargo_descripcion
    FROM th_contr_cargo_aspectos_intrinsecos a
    INNER JOIN th_contr_cargos c ON a.th_car_id = c.th_car_id
    LEFT JOIN th_contr_cargos c_sub ON a.th_carasp_subordinacion_id = c_sub.th_car_id
    LEFT JOIN th_contr_cargos c_sup ON a.th_carasp_supervision_id = c_sup.th_car_id
    LEFT JOIN th_contr_cargos c_com ON a.th_carasp_comunicaciones_id = c_com.th_car_id
    WHERE a.th_carasp_estado = 1
    ";
    
    if ($id_cargo != '') {
        $sql .= " AND a.th_car_id = {$id_cargo}";
        return $this->db->datos($sql);
    } else {
        return $this->db->datos($sql);
    }
}
}